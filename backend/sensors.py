#!/usr/bin/python3

import argparse
import codecs
import configparser

from datetime import datetime
from sysv_ipc import Semaphore, SharedMemory

from includes.includes import configfile
from includes.includes import debugmessage
from includes.includes import logwrite
from includes.includes import now
from includes.includes import readshm
from includes.includes import run

import json
import os
import sqlite3
import sys
import time

import sysv_ipc

DAEMON = "sensors"

config = configparser.ConfigParser()
config.read(configfile())

sensorcfg = configparser.ConfigParser()
sensorcfg.read(configfile(cfg="sensors"))

project = str(config['plant']['project'])
sensordaemon_id = int(config['sensordaemon']['sensordaemon_id'])

pidfile = "{}/tmp/sensors.pid".format(config['default']['rootpath'])
logfile = "{}/log/growbox.log".format(config['default']['rootpath'])

SHM_SIZE = int(config['sensordaemon']['shm_size'])
NULL_CHAR = '\0'
PERMISSIONS = 0o644

sleep_between_iterations = float(config['sensordaemon']['sleep_between_readings'])


def writesqlite() -> None:
    """
    writes the actual sensordata to a sqlite database as "unix timestamp (UTC)": gzip(json)

    :rtype: None
    """
    unixtime = datetime.timestamp(datetime.strptime(now(timezone="utc"), "%Y-%m-%d %H:%M:%S"))

    sensordata = str(readshm(sensordaemon_id))
    sensordata = sensordata.split(' ')
    sensordata.pop(0)
    sensordata.pop(0)

    i = 0
    dbstring = {}
    for data in sensordata:
        dbstring[i] = data
        i += 1

    gzipstring = codecs.encode(bytes(json.dumps(dbstring, separators=(',', ':')), "utf-8"), "zlib")
    database = "{}/db/{}".format(config['default']['rootpath'], config['db']['database'])
    con = sqlite3.connect(database)
    cur = con.cursor()
    cur.execute("Insert INTO sensordata (timestamp, data) VALUES (?, ?)", (unixtime, gzipstring))
    con.commit()
    con.close()


def openshm() -> tuple[Semaphore, SharedMemory]:
    """
    creates semaphore and shared memory and releases semaphore.

    :rtype: tuple[Semaphore, SharedMemory]
    :return: semaphore and shm objects
    """
    sem = sysv_ipc.Semaphore(sensordaemon_id, sysv_ipc.IPC_CREX, PERMISSIONS, 3)
    shm = sysv_ipc.SharedMemory(sensordaemon_id, sysv_ipc.IPC_CREX, PERMISSIONS, SHM_SIZE)
    sem.release()
    return sem, shm


def writeshm(sem: Semaphore, shm: SharedMemory, data: tuple) -> None:
    """
    writes sensordata to Shared Memory

    :rtype: None
    :param sem: Semaphore
    :param shm: SharedMemory
    :param data: sensordata with timestamp, space separated
    """

    data = ' '.join([str(value) for value in data])

    if DEBUG:
        binary = ' '.join(format(ord(x), 'b') for x in data)
        size = len(' '.join(format(ord(x), 'b') for x in data).encode('utf-8'))
        binary = binary.encode('utf-8')

    data += NULL_CHAR
    data = data.encode()

    sem.acquire()

    if DEBUG:
        debugmessage("write {} bytes to shared memory segment {}".format(size, hex(shm.key)), DEBUG)
        debugmessage("-------------------------------------------------------", DEBUG)
        debugmessage(str(binary), DEBUG)
        debugmessage("-------------------------------------------------------", DEBUG)

    shm.write(data)
    sem.release()


def closeshm(sem: Semaphore, shm: SharedMemory) -> None:
    """
    Removes Semaphore and SHM

    :rtype: None
    :param sem: Semaphore
    :param shm: Shared Memory
    """
    debugmessage("removing semaphore and shm segment", DEBUG)
    sem.remove()
    shm.remove()
    debugmessage("done.", DEBUG)


def readsensors() -> tuple:
    """
    reads sensors and returns timestamp and values

    it reads config.ini [sensors] -> sensors, imports the corresponding modules and invokes the read method.
    every module MUST at least have the read() method implemented, and accept one option when the class is invoked
    (i.E. pin number, channel, path..).

    the config [sensortype] -> helper is a comma separated string: module,option,modifier,extra_args
    the modifier is somewhat special, i need it for my cap-shyg analog soil moisture sensor. let's see that line:

    helper=MCP3008,0,ScaleMoisture,3296,1381

    the sensor needs the mcp3008 module with the option 0 (means the sensor data pin is connected to channel 0 of the chip)
    it uses the ScaleMoisture modifier with the parameters 3296,1381. the modifier takes the raw numbers of the dry and wet
    values (in that order!), processes the value measured and returns a percent value.

    :rtype: tuple[timestamp, sensordata0, sensordata1, sensordataN]
    :return:
    """

    sys.path.append("{}/backend/includes/sensors".format(config['default']['rootpath']))
    sys.path.append("{}/backend/includes/modifiers".format(config['default']['rootpath']))

    result = ()
    result = result + now(as_tuple=True)
    sensorcfg.read(configfile(cfg="sensors"))
    config.read(configfile())

    sensors_list = config['sensors']['sensors'].split(",")

    for sensor in sensors_list:
        cfg = sensorcfg[sensor]['helper'].split(",")
        helper = cfg[0]
        option = cfg[1]
        module = __import__(helper)
        class_ = getattr(module, helper)
        device = class_(option=option)

        if len(cfg) > 2:
            modifier = __import__(cfg[2])
            modifier_class_ = getattr(modifier, cfg[2])
            data = modifier_class_.function(device.read(), int(cfg[3]), int(cfg[4]))

        else:
            data = device.read()

        result = result + data
    return result


def end(sem: Semaphore, shm: SharedMemory) -> None:
    """
    closes semaphore and shm, removes pidfile

    :rtype: None
    :param sem: Semaphore
    :param shm: Shared Memory
    """
    debugmessage("exiting now", DEBUG)
    debugmessage("close semaphore and shm", DEBUG)
    closeshm(sem, shm)
    debugmessage("removing pidfile", DEBUG)
    os.remove(pidfile)


def daemon(memory: tuple[Semaphore, SharedMemory]) -> None:
    """
    main loop for daemon mode. checks every sleep_between_iterations (config.ini) the sensors and
    writes the result to shm

    :param memory: Tuple Semaphore, Shared Memory
    :rtype: None
    """
    sem, shm = memory
    while True:
        try:
            debugmessage("=======================================================", DEBUG)
            debugmessage("reading sensors..", DEBUG)
            # debugmessage(str(readsensors()), DEBUG) # this really slows down, because readsensors() is called twice.
            writeshm(sem, shm, readsensors())
            debugmessage("sleeping {} sec until next iteration".format(sleep_between_iterations), DEBUG)
            time.sleep(sleep_between_iterations)

        except KeyboardInterrupt:
            debugmessage("Interrupt", DEBUG)
            end(sem, shm)
            logwrite(DAEMON, logfile, "stopped by SIGINT")
            sys.exit(0)

        except SystemExit:
            debugmessage("Terminated", DEBUG)
            end(sem, shm)
            logwrite(DAEMON, logfile, "stopped by SIGTERM")
            sys.exit(0)


if __name__ == '__main__':
    parser = argparse.ArgumentParser(description='''The sensordaemon''')
    group = parser.add_mutually_exclusive_group()
    parser.add_argument("-v", "--verbose",
                        action="store_true",
                        help='''debug increase output verbosity to stdout. don't do that in production.''')

    group.add_argument("-db", "--database",
                       action='store_true',
                       help='''stores sensordata in sqlite db''')

    group.add_argument("-o", "--stdout",
                       action='store_true',
                       help='''return temperature humidity moisture cputemp and co2 as whitespace separated string''')

    group.add_argument("-d", "--daemon",
                       action='store_true',
                       help='''starts the sensordaemon''')

    group.add_argument("-r", "--readsensors",
                       action='store_true',
                       help='''calls readsensors()''')

    if len(sys.argv) == 1:
        parser.print_help(sys.stderr)
        sys.exit(1)

    args = parser.parse_args()

    if args.verbose:
        DEBUG = True
    else:
        DEBUG = False

    if args.database:
        writesqlite()

    elif args.stdout:
        if os.path.exists(pidfile):
            debugmessage("daemon running, reading from shm", DEBUG)
            print(readshm(sensordaemon_id))
            sys.exit(0)
        else:
            debugmessage("daemon not running, reading directly from sensors", DEBUG)
            print(readsensors())
            sys.exit(0)

    elif args.daemon:
        if not run(DAEMON, pidfile, logfile, DEBUG):
            exit(0)
        else:
            # sleep 15 sec to let the sensors settle..
            time.sleep(15)
            daemon(openshm())
            # runs forever

    elif args.readsensors:
        print(readsensors())

    else:
        print("nein.")

    sys.exit(1)
