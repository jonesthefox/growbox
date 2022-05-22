#!/usr/bin/python3

import argparse
import configparser
import RPi.GPIO as GPIO
from datetime import datetime, time

from includes.includes import checkstate
from includes.includes import configfile
from includes.includes import debugmessage
from includes.includes import logwrite
from includes.includes import readshm
from includes.includes import run
from includes.includes import setup
from includes.includes import getpin

import os
import sys
from time import sleep


def quiethours(now: time, starttime: time, endtime: time) -> bool:
    """
    check for quiet hours. to suppress activation of the relay when the noise disturbs the sleep of your plant...

    :param now: the time now
    :param starttime: the start hour
    :param endtime: the end hour
    :rtype: bool
    :return: bool true when 'now' falls between the start and endtime or false otherwise
    """
    if starttime <= endtime:
        return starttime <= now < endtime
    else: # over midnight e.g., 23:30-04:15
        return starttime <= now or now < endtime


def end(element: str, gpio: int, pidfile: str, runfile: str, logfile: str, debug: bool):
    """
    ends the daemon. turns off the relay when on and removes the pidfile.

    :param element: the daemon
    :param gpio: gpio for the relay
    :param pidfile: the pidfile
    :param logfile: the logfile
    :param runfile: the runfile
    :param debug: turn on debug messages
    """
    if checkstate(runfile, "r"):
        debugmessage("Stopping relay", debug)
        off(element, gpio, runfile, logfile, debug)
    debugmessage("removing pidfile", debug)
    os.remove(pidfile)


def on(element: str, gpio: int, runfile: str, logfile: str, debug: bool):
    """
    starts relay channel

    :param element: the daemon
    :param gpio: gpio for the relais
    :param runfile: the runfile
    :param logfile: the logfile
    :param debug: turn on debug messages
    """
    checkstate(runfile, "w", str(1))
    GPIO.output(gpio, 1)
    logwrite(element, logfile, "relay started")
    debugmessage("Relay started", debug)


def off(element: str, gpio: int, runfile: str, logfile: str, debug: bool):
    """
    stops relay channel

    :param element: the daemon
    :param gpio: gpio for the relais
    :param runfile: the runfile
    :param logfile: the logfile
    :param debug: turn on debug messages
    """
    checkstate(runfile, "w", str(0))
    GPIO.output(gpio, 0)
    logwrite(element, logfile, "relay stopped")
    debugmessage("Relay stopped", debug)


def daemon(element: str, pidfile: str, runfile: str, logfile: str) -> None:
    """
    main loop for daemon. checks every sleep_between_readings seconds (config.ini) the sensordata in shm and turn the relay on/off

    :param element: the element to control. water/air
    :param pidfile: the pidfile
    :param runfile: the runfile
    :param logfile: the logfile
    :rtype: None
    """

    while True:
        try:
            debugmessage("===================================================================", DEBUG)
            debugmessage("reading sensordata from shm..", DEBUG)

            sensordata = str(readshm(sensordaemon_id))
            sensordata = sensordata.split(' ')
            config.read(configfile())


            if element == "water":
                index = int(config['water']['index'])
                debugmessage("{} measured value={} threshold min={}, threshold max={}".format(sensordata[0], sensordata[index], config[DAEMON]['min'], config[DAEMON]['max']), DEBUG)
                if not quiethours(datetime.now().time(), time(int(quiet_hours_water[0])), time(int(quiet_hours_water[1]))):
                    if float(sensordata[index]) < float(config[DAEMON]['min']):
                        if not checkstate(runfile, "r"):
                            debugmessage("Relay off, turning on now because measured value={} (threshold={})".format(sensordata[index], config[DAEMON]['min']), DEBUG)
                            on(element, RELAY_GPIO, runfile, logfile, DEBUG)
                        else:
                            debugmessage("Relay already on", DEBUG)

                    elif float(sensordata[index]) > float(config[DAEMON]['max']):
                        if checkstate(runfile, "r"):
                            debugmessage("Turning Relay off now because measured value={} (threshold={})".format(sensordata[index], config[DAEMON]['max']), DEBUG)
                            off(element, RELAY_GPIO, runfile, logfile, DEBUG)
                        else:
                            debugmessage("Relay already off", DEBUG)
                else:
                    if checkstate(runfile, 'r'):
                        off(element, RELAY_GPIO, runfile, logfile, DEBUG)
                    debugmessage("No action (quiet hour)", DEBUG)

            elif element == "air":
                index = int(config['air']['index'])
                debugmessage("{} measured value={} threshold min={}, threshold max={}".format(sensordata[0], sensordata[index], config[DAEMON]['min'], config[DAEMON]['max']), DEBUG)
                if not quiethours(datetime.now().time(), time(int(quiet_hours_air[0])), time(int(quiet_hours_air[1]))):
                    if float(sensordata[index]) > float(config[DAEMON]['max']):
                        if not checkstate(runfile, "r"):
                            debugmessage("Relay off, turning on now because measured value={} (threshold={})".format(sensordata[index], config[DAEMON]['max']), DEBUG)
                            on(element, RELAY_GPIO, runfile, logfile, DEBUG)
                        else:
                            debugmessage("Relay already on", DEBUG)

                    elif float(sensordata[index]) < float(config[DAEMON]['min']):
                        if checkstate(runfile, "r"):
                            debugmessage("Turning Relay off now  because measured value={} (threshold={})".format(sensordata[index], config[DAEMON]['min']), DEBUG)
                            off(element, RELAY_GPIO, runfile, logfile, DEBUG)
                        else:
                            debugmessage("Relay already off", DEBUG)
                else:
                    if checkstate(runfile, 'r'):
                        off(element, RELAY_GPIO, runfile, logfile, DEBUG)
                    debugmessage("No action (quiet hour)", DEBUG)

            debugmessage("sleeping {} sec until next iteration".format(sleep_between_readings), DEBUG)
            sleep(sleep_between_readings)

        except KeyboardInterrupt:
            debugmessage("Interrupt", DEBUG)
            end(element, RELAY_GPIO, pidfile, runfile, logfile, DEBUG)
            logwrite(element, logfile, "stopped by SIGINT")
            sys.exit(0)

        except SystemExit:
            debugmessage("Terminated", DEBUG)
            end(element, RELAY_GPIO, pidfile, runfile, logfile, DEBUG)
            logwrite(element, logfile, "stopped by SIGTERM")
            sys.exit(0)


if __name__ == '__main__':
    parser = argparse.ArgumentParser(description='''The relay daemon''')
    group = parser.add_mutually_exclusive_group()
    parser.add_argument("-v", "--verbose",
                        action="store_true",
                        help='''debug increase output verbosity to stdout. don't do that in production.''')

    group.add_argument("-d", "--daemon",
                       type=str, choices=["air", "water"],
                       help='''starts the relay daemon with control of the chosen element''')

    if len(sys.argv) == 1:
        parser.print_help(sys.stderr)
        sys.exit(1)

    args = parser.parse_args()

    if args.verbose:
        DEBUG = True
    else:
        DEBUG = False

    if args.daemon:
        DAEMON = args.daemon
        config = configparser.ConfigParser()
        config.read(configfile())

        quiet_hours_air = config['air']['quiet_hours']
        quiet_hours_air = quiet_hours_air.split(',')
        quiet_hours_water = config['water']['quiet_hours']
        quiet_hours_water = quiet_hours_water.split(',')

        pid, runf, log, sensordaemon_id, sleep_between_readings = setup(DAEMON)

        GPIO.setmode(GPIO.BCM)
        RELAY_GPIO = getpin(int(config[DAEMON]['pin']), returntype='bcm')
        GPIO.setwarnings(False)
        GPIO.setup(RELAY_GPIO, GPIO.OUT)

        if not run(DAEMON, pid, log, DEBUG):
            exit(0)

        else:
            # sleep 5 seconds that the sensordaemon can read the sensors and write to the shm
            # to ensure we can read something from shm in case of a reboot
            debugmessage("Sleeping 5 seconds to ensure there is data in SHM..", DEBUG)
            sleep(5)
            daemon(DAEMON, pid, runf, log)
            # runs forever

        exit(0)

