import configparser

from datetime import datetime
from dateutil.tz import tzutc, tzlocal
from typing import Union

import os
import signal
import sysv_ipc

from microcontroller import Pin


def handle_exit(sig, frame):
    _ = sig, frame
    raise SystemExit


def debugmessage(debugstring: str, debug: bool) -> None:
    """ prints debug strings to stdout. handy for testing
    :rtype: None
    :param debugstring: the string to write
    :param debug: prints the message when true
    """
    if debug:
        print(debugstring)


def logwrite(daemon: str, logfile: str, logwritestring: str, cli: bool = False) -> None:
    """ writes to the logfile (daemon start / stop and stuff)
    :rtype: None
    :param daemon: the daemon
    :param logfile: the logfile
    :param logwritestring: the string to write
    :param cli: changes the logstring when true
    """

    if not cli:
        logstring = "{} DAEMON({}: {})\n".format(now(), daemon, logwritestring)
    else:
        logstring = "{} CLI({})\n".format(now(), logwritestring)

    with open(logfile, 'a') as logfilehandler:
        logfilehandler.write(str(logstring))


def checkpid(pidfile: str) -> bool:
    """
    check for pidfile and running process. if pid is there but no process is
    running, pidfile will be removed. returns true when the daemon can be started and
    false when it should not start

    :rtype: bool
    :param pidfile: the pidfile
    :return: true if PID is not running, false otherwise
    """
    try:
        with open(pidfile, 'r') as processid:
            pid = int(processid.read())
            try:
                os.kill(pid, 0)
            except OSError:
                # PID NOT RUNNING
                try:
                    os.remove(pidfile)
                except OSError:
                    print("cannot remove pidfile, daemon might need root privileges.")
                    return False
                else:
                    return True

            else:
                # PID RUNNING
                return False

    except FileNotFoundError:
        # FILE NOT FOUND
        return True


def now(time: bool = False, timezone: str = "local", as_tuple: bool = False) -> Union[str, tuple[str]]:
    """ returns timestamp y-m-d h:m:s or h:m:s when parameter time is true
    :param as_tuple: returns timestamp as tuple when true
    :rtype: str
    :param time: shows only time when true, date time when false
    :param timezone: string "local" or "utc"
    :return: time or datetime
    """
    if timezone == "utc":
        timestamp = datetime.now(tzutc())
    elif timezone == "local":
        timestamp = datetime.now(tzlocal())

    if not time:
        if not as_tuple:
            return timestamp.strftime("%Y-%m-%d %H:%M:%S")
        else:
            result = (timestamp.strftime("%Y-%m-%d %H:%M:%S"),)
            return result
    else:
        if not as_tuple:
            return timestamp.strftime("%H:%M:%S")
        else:
            result = (timestamp.strftime("%H:%M:%S"),)
            return result


def readshm(shmid: int) -> str:
    """
    reads the binary sensordata from SHM
    :rtype: tuple
    :param shmid: the SHM ID to be read
    :return: tuple with timestamp, temperature, humidity, moist, cputemp, co2
    """
    shm = sysv_ipc.SharedMemory(shmid, 0, 0)
    sensordata = shm.read()
    shm.detach()
    sensordata = sensordata.decode('utf-8')
    sensordata = sensordata.split('\00')

    return sensordata[0]


def configfile(cfg: str = "config") -> str:
    """
    returns configfile with full path
    :param cfg: config / sensors
    :rtype: str
    :return: path/to/config.ini or path/to/sensors.ini
    """
    scriptpath = os.path.realpath(__file__)
    configpath = scriptpath.removesuffix("backend/includes/includes.py")
    if cfg == "config":
        file = str(configpath) + str("config.ini")
    elif cfg == "sensors":
        file = str(configpath) + str("sensors.ini")
    return file


def setup(daemon: str) -> tuple[str, str, str, int, float]:
    """
    returns pid-,run- and logfile of the set daemon, the sensordaemon id and the sleep between iterations value

    :param daemon: the daemon
    :return: tuple containing pidfile, runfile, logfile, sensordaemon id and sleep between iterations (in seconds) of the given daemon
    :rtype: tuple
    """
    config = configparser.ConfigParser()
    config.read(configfile())
    result = ("{}/tmp/{}".format(config['default']['rootpath'], config[daemon]['pidfile']),
        "{}/tmp/{}".format(config['default']['rootpath'], config[daemon]['runfile']),
        "{}/log/growbox.log".format(config['default']['rootpath']),
        int(config['sensordaemon']['sensordaemon_id']),
        float(config[daemon]['sleep_between_readings']))

    return result


def checkstate(runfile: str, mode: str, state: str = str(0)) -> bool:
    """
    read and write the state of the device. 0=off, 1=on.
    :rtype: bool
    :param runfile: the runfile
    :param mode: r/w (= read / write)
    :param state: write state 0 (off) / 1 (on)
    :return: true when state is 1, false when 0
    """
    if mode == "w":
        with open(runfile, 'w') as runfilehandler:
            runfilehandler.write(str(state))

    elif mode == "r":
        with open(runfile, 'r') as runfilehandler:
            state = runfilehandler.read()
        if state == str(0):
            return False
        elif state == str(1):
            return True


def run(daemon: str, pidfile: str, debug: bool) -> bool:
    """
    checks if the hardware for the specified daemon is attached, if a project is running and if a daemon already runs.
    returns true when both is yes in config and no daemon is running, false otherwise.
    :rtype: bool
    :param daemon: the daemon
    :param pidfile: the pidfile
    :param debug: debug switch
    :return: true / false
    """

    config = configparser.ConfigParser()
    config.read(configfile())

    if not config.getboolean(daemon, 'attached'):
        debugmessage("hardware not attached in config.ini, exiting", debug)
        return False

    else:
        if not daemon == "sensors":
            if not config.getboolean('plant', 'active'):
                debugmessage("no project active, exiting", debug)
                return False

        if not checkpid(pidfile):
            debugmessage("daemon already running", debug)
            return False

        else:
            pid = os.getpid()
            with open(pidfile, 'w') as pidfilehandler:
                pidfilehandler.write(str(pid))

            signal.signal(signal.SIGTERM, handle_exit)
            debugmessage("started with PID {}".format(pid), debug)
            return True


def getpin(pin: int, returntype: str) -> Union[Pin, int]:
    """
    takes a raspberry hardware pin and returns something that circuitpython or RPi.GPIO (GPIO.BCM) can understand

    :rtype: Union[Pin, int]
    :param pin: int pin number
    :param returntype: 'circuitpython' / 'bcm'
    :return: Pin object or int
    """
    import board

    if returntype == "circuitpython":
        if pin == 3:
            return board.D2

        elif pin == 5:
            return board.D3

        elif pin == 7:
            return board.D4

        elif pin == 11:
            return board.D17

        elif pin == 13:
            return board.D27

        elif pin == 15:
            return board.D22

        elif pin == 19:
            return board.D10

        elif pin == 21:
            return board.D9

        elif pin == 23:
            return board.D11

        elif pin == 27:
            return board.D0

        elif pin == 29:
            return board.D5

        elif pin == 31:
            return board.D6

        elif pin == 33:
            return board.D13

        elif pin == 35:
            return board.D19

        elif pin == 37:
            return board.D26

        elif pin == 8:
            return board.D14

        elif pin == 10:
            return board.D15

        elif pin == 12:
            return board.D18

        elif pin == 16:
            return board.D23

        elif pin == 18:
            return board.D24

        elif pin == 22:
            return board.D25

        elif pin == 24:
            return board.D8

        elif pin == 26:
            return board.D7

        elif pin == 28:
            return board.D1

        elif pin == 32:
            return board.D12

        elif pin == 36:
            return board.D16

        elif pin == 38:
            return board.D20

        elif pin == 40:
            return board.D21

    if returntype == "bcm":
        if pin == 3:
            return 2

        elif pin == 5:
            return 3

        elif pin == 7:
            return 4

        elif pin == 11:
            return 17

        elif pin == 13:
            return 27

        elif pin == 15:
            return 22

        elif pin == 19:
            return 10

        elif pin == 21:
            return 9

        elif pin == 23:
            return 11

        elif pin == 27:
            return 0

        elif pin == 29:
            return 5

        elif pin == 31:
            return 6

        elif pin == 33:
            return 13

        elif pin == 35:
            return 19

        elif pin == 37:
            return 26

        elif pin == 8:
            return 14

        elif pin == 10:
            return 15

        elif pin == 12:
            return 18

        elif pin == 16:
            return 23

        elif pin == 18:
            return 24

        elif pin == 22:
            return 25

        elif pin == 24:
            return 8

        elif pin == 26:
            return 7

        elif pin == 28:
            return 1

        elif pin == 32:
            return 12

        elif pin == 36:
            return 16

        elif pin == 38:
            return 20

        elif pin == 40:
            return 21

