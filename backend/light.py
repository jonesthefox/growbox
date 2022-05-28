#!/usr/bin/python3

import argparse, configparser, json
import RPi.GPIO as GPIO

from includes.includes import configfile, checkstate, logwrite, run, getpin, now

import sys, math, neopixel, os, time

DAEMON = "light"

DEBUG = False
TEST = False

config = configparser.ConfigParser()
config.read(configfile())

pidfile = "{}/tmp/light.pid".format(config['default']['rootpath'])
sunfile = "{}/tmp/sun.run".format(config['default']['rootpath'])
logfile = "{}/log/growbox.log".format(config['default']['rootpath'])
sunlog = "{}/log/sun.log".format(config['default']['rootpath'])

cooling_runfile = "{}/tmp/cooling.run".format(config['default']['rootpath'])

num_pixels = int(config['light']['num_pixels'])
ORDER = str(config['light']['order'])

PIN = int(config['light']['pin'])

phase = str(config['plant']['phase'])
if phase == "grow":
    rgbw = str(config['light']['rgbw_grow'])
elif phase == "bloom":
    rgbw = str(config['light']['rgbw_bloom'])


def debugmessage(string: str, debug: bool) -> None:
    """ writes to the logfile (daemon start / stop and stuff)
    :rtype: None
    :param string: the message
    :param debug: writes the message when true
    """
    if debug:
        logstring = "{} DAEMON({} {})\n".format(now(), DAEMON, string)
        with open(sunlog, 'a') as handler:
            handler.write(str(logstring))


def write_state(what: str, val: str) -> None:
    """
    write the state to the sunfile. sunfile keeps track of the state (noon/sunset/sunrise/night), the restore state,
    the rgbw values and the brightness in percent.

    :rtype: None
    :param what: 'state', 'restore', 'rgbw', 'brightness'
    :param val: the value
    """
    with open(sunfile, 'r+') as json_file:
        data = json.load(json_file)
        data[what] = val
        json_file.seek(0)
        json.dump(data, json_file, indent=4)
        json_file.truncate()


def end(nostore: bool = False) -> None:
    """
    Ends the daemon by deinitializing neopixels, writing 0 to the brightness file and removing the pidfile
    :type nostore: bool set true to disable storing of the state (i.E. when it's already stored by the exception handling)
    :rtype: None
    """
    pixels = neopixel.NeoPixel(getpin(PIN, returntype='circuitpython'), num_pixels, bpp=4, brightness=1.0, auto_write=False, pixel_order=ORDER)
    debugmessage("exiting now, turning off..", DEBUG)
    pixels.fill((0, 0, 0, 0))
    pixels.show()
    if not nostore:
        write_state('rgbw', '0,0,0,0')
        write_state('state', 'night')
        write_state('brightness', '0.00')
        write_state('restore', '')

    logwrite(DAEMON, logfile, "off")
    if checkstate(cooling_runfile, 'r'):
        cooling(0)

    try:
        os.remove(pidfile)
        debugmessage("removing pidfile", DEBUG)

    except FileNotFoundError:
        debugmessage("daemon not running, no pidfile to remove", DEBUG)

    logwrite(DAEMON, logfile, "stopped")


def read_restorestate():
    """ check for sunfile restore, returns string when it exists
    :return: content of sunfile when there is one or false
    """
    try:
        with open(sunfile, 'r') as json_file:
            data = json.load(json_file)
            return str(data['restore'])

    except FileNotFoundError:
        # FILE NOT FOUND
        return False


def store(mode: str, remaining: int = 0, red: int = 0, green: int = 0, blue: int = 0, white: int = 0) -> None:
    """ stores the information necessary to resume operations in case of interrupt (i.e. SIGINT / SIGTERM)

    :rtype: None
    :param mode: sunrise, noon, sunset
    :param remaining: remaining time in seconds when mode = noon
    :param red: brightness for red led
    :param green: brightness for green led
    :param blue: brightness for blue led
    :param white: brightness for white led
    """

    if mode == "noon":
        write_state('restore', "noon {}".format(remaining))

    elif mode == "sunrise":
        write_state('restore', "sunrise {} {} {} {}".format(red, green, blue, white))

    elif mode == "sunset":
        write_state('restore', "sunset {} {} {} {}".format(red, green, blue, white))


def restore(res: str) -> None:
    """
    restores previous state of light when interrupted

    :param res: content of sunfile (space separated string)
    :rtype: None
    """
    res = res.split(" ")
    if res[0] == "noon":
        debugmessage("restoring state noon", DEBUG)
        logwrite(DAEMON, logfile, "restoring state noon")
        sun(str(res[0]), noontime=int(res[1]))
        sun("sunset", r, g, b, w)
        end()

    elif res[0] == "sunrise":
        debugmessage("restoring state sunrise", DEBUG)
        logwrite(DAEMON, logfile, "restoring state sunrise")
        sun(str(res[0]), red=int(res[1]), green=int(res[2]), blue=int(res[3]), white=int(res[4]), resume=True)
        sun("noon", noontime=day)
        sun("sunset", r, g, b, w)
        end()

    elif res[0] == "sunset":
        debugmessage("restoring state sunset", DEBUG)
        logwrite(DAEMON, logfile, "restore state sunset")
        sun(str(res[0]), red=int(res[1]), green=int(res[2]), blue=int(res[3]), white=int(res[4]), resume=True)
        end()


def sun(mode: str, red: int = 0, green: int = 0, blue: int = 0, white: int = 0, resume: bool = False, noontime: int = 0) -> None:
    """
    :rtype: None
    :param mode: string "sunrise" / "noon" / "sunset"
    :param red: red led intensity or value of r_dim in resume mode
    :param green: green led intensity or value of g_dim in resume mode
    :param blue: blue led intensity or value of b_dim in resume mode
    :param white: white led intensity or value of w_dim in resume mode
    :param resume last operation when true
    :param noontime remaining noon time in seconds
    """

    r_inc = int(math.ceil(r / dim_steps))
    g_inc = int(math.ceil(g / dim_steps))
    b_inc = int(math.ceil(b / dim_steps))
    w_inc = int(math.ceil(w / dim_steps))

    done = False

    pixels = neopixel.NeoPixel(getpin(PIN, returntype='circuitpython'), num_pixels, bpp=4, brightness=1.0, auto_write=False, pixel_order=ORDER)

    if mode == "sunrise":
        """ here we dim the light from 0% to 100% """
        write_state('state', mode)
        logwrite(DAEMON, logfile, "sunrise")
        while not done:
            try:
                debugmessage("target r: {} g: {} b: {} w: {}".format(r, g, b, w), DEBUG)
                debugmessage("{} steps, increments: r: {} g: {} b: {} w: {}".format(dim_steps, r_inc, g_inc, b_inc, w_inc), DEBUG)
                debugmessage("=======================================", DEBUG)
                debugmessage("Sunrise {} sec.".format(dim), DEBUG)
                debugmessage("---------------------------------------", DEBUG)

                percent = int(0)
                r_dim = int(0)
                g_dim = int(0)
                b_dim = int(0)
                w_dim = int(0)

                if resume:
                    r_dim = int(red)
                    g_dim = int(green)
                    b_dim = int(blue)
                    w_dim = int(white)

                while r_dim <= r and g_dim <= g and b_dim <= b and w_dim <= w:
                    r_dim += r_inc
                    g_dim += g_inc
                    b_dim += b_inc
                    w_dim += w_inc
                    if r_dim > int(255) or r_dim > r \
                            or g_dim > int(255) or g_dim > g \
                            or b_dim > int(255) or b_dim > b \
                            or w_dim > int(255) or w_dim > w:
                        break
                    pixels.fill((r_dim, g_dim, b_dim, w_dim))
                    pixels.show()
                    write_state('rgbw', "{},{},{},{}".format(r_dim, g_dim, b_dim, w_dim))

                    store("sunrise", red=r_dim, green=g_dim, blue=b_dim, white=w_dim)
                    percent += percent_step
                    if percent >= int(100):
                        percent = int(100)

                    write_state('brightness', str(percent))
                    debugmessage("value  r: {} g: {} b: {} w: {}".format(r_dim, g_dim, b_dim, w_dim), DEBUG)
                    time.sleep(dim_step)

                done = True

            except KeyboardInterrupt:
                debugmessage("Interrupt, turning the light off, delete pidfile", DEBUG)
                store("sunrise", red=r_dim, green=g_dim, blue=b_dim, white=w_dim)
                end(nostore=True)
                logwrite(DAEMON, logfile, "Sunrise stopped by SIGINT")
                sys.exit(0)
            except SystemExit:
                debugmessage("Terminated, turning the light off, delete pidfile", DEBUG)
                store("sunrise", red=r_dim, green=g_dim, blue=b_dim, white=w_dim)
                end(nostore=True)
                logwrite(DAEMON, logfile, "Sunrise stopped by SIGTERM")
                sys.exit(0)

    elif mode == "sunset":
        """ here we dim the light from 100% to 0% """
        write_state('state', mode)
        logwrite(DAEMON, logfile, "sunset")
        cooling(0)
        while not done:
            try:
                debugmessage("=======================================", DEBUG)
                debugmessage("Sunset {} sec.".format(dim), DEBUG)
                debugmessage("---------------------------------------", DEBUG)
                percent = int(100)
                r_dim = r
                g_dim = g
                b_dim = b
                w_dim = w

                if resume:
                    r_dim = int(red)
                    g_dim = int(green)
                    b_dim = int(blue)
                    w_dim = int(white)

                while r_dim >= int(0) and g_dim >= int(0) and b_dim >= int(0) and w_dim >= int(0):
                    r_dim -= r_inc
                    g_dim -= g_inc
                    b_dim -= b_inc
                    w_dim -= w_inc
                    if r_dim < int(0) or g_dim < int(0) or b_dim < int(0) or w_dim < int(0):
                        break
                    pixels.fill((r_dim, g_dim, b_dim, w_dim))
                    pixels.show()
                    write_state('rgbw', "{},{},{},{}".format(r_dim, g_dim, b_dim, w_dim))
                    store("sunset", red=r_dim, green=g_dim, blue=b_dim, white=w_dim)
                    percent -= percent_step
                    if percent <= int(0):
                        percent = int(0)
                    write_state('brightness', str(percent))
                    debugmessage("value  r: {} g: {} b: {} w: {}".format(r_dim, g_dim, b_dim, w_dim), DEBUG)
                    time.sleep(dim_step)

                pixels.fill((0, 0, 0, 0))
                pixels.show()
                debugmessage("value  r: 0 g: 0 b: 0 w: 0", DEBUG)
                debugmessage("---------------------------------------", DEBUG)
                debugmessage("Night", DEBUG)
                debugmessage("=======================================", DEBUG)
                done = True

            except KeyboardInterrupt:
                debugmessage("Interrupt, turning the light off, delete pidfile", DEBUG)
                store("sunset", red=r_dim, green=g_dim, blue=b_dim, white=w_dim)
                end(nostore=True)
                logwrite(DAEMON, logfile, "Sunset stopped by SIGINT")
                sys.exit(0)
            except SystemExit:
                debugmessage("Terminated, turning the light off, delete pidfile", DEBUG)
                store("sunset", red=r_dim, green=g_dim, blue=b_dim, white=w_dim)
                end(nostore=True)
                logwrite(DAEMON, logfile, "Sunset stopped by SIGTERM")
                sys.exit(0)

    elif mode == "noon":
        write_state('state', mode)
        write_state('brightness', str(100.00))
        logwrite(DAEMON, logfile, "noon")
        debugmessage("=======================================", DEBUG)
        debugmessage("Noon {} sec.".format(day), DEBUG)
        debugmessage("---------------------------------------", DEBUG)
        debugmessage("value  r: {} g: {} b: {} w: {}".format(r, g, b, w), DEBUG)
        try:
            cooling(1)
            pixels.fill((r, g, b, w))
            pixels.show()
            write_state('rgbw', "{},{},{},{}".format(r, g, b, w))
            while noontime > 0:
                store("noon", remaining=noontime)
                time.sleep(sleepuntilstore)
                noontime -= sleepuntilstore
                if noontime <= 0:
                    break

        except KeyboardInterrupt:
            debugmessage("Interrupt, turning the light off, delete pidfile", DEBUG)
            store("noon", remaining=noontime)
            end(nostore=True)
            logwrite(DAEMON, logfile, "Noon stopped by SIGINT")
            sys.exit(0)
        except SystemExit:
            debugmessage("Terminated, turning the light off, delete pidfile", DEBUG)
            store("noon", remaining=noontime)
            end(nostore=True)
            logwrite(DAEMON, logfile, "Noon stopped by SIGTERM")
            sys.exit(0)


def cooling(state: int) -> None:
    """
    starts/stops the cooling of the light emitting device.

    Is probably also involved in the control of the annular containment beam..

    :rtype: None
    :param state: 1=on 0=off
    """
    if config['cooling']['attached'] == 'yes':

        GPIO.setmode(GPIO.BCM)

        relaypin = getpin(int(config['cooling']['pin']), returntype='bcm')
        GPIO.setwarnings(False)
        GPIO.setup(relaypin, GPIO.OUT)

        GPIO.output(relaypin, state)
        checkstate(cooling_runfile, 'w', str(state))
        if state == 1:
            cooling_mode = 'on'
        elif state == 0:
            cooling_mode = 'off'

        logwrite(DAEMON, logfile, "cooling {}".format(cooling_mode))


if __name__ == '__main__':
    parser = argparse.ArgumentParser(description='''The lightdaemon''')
    group = parser.add_mutually_exclusive_group()
    parser.add_argument("-v", "--verbose",
                        action="store_true",
                        help='''debug increase output verbosity to stdout. don't do that in production.''')

    parser.add_argument("-t", "--test",
                        action="store_true",
                        help='''sets dim 10 sec, dim_step 1 sec and day 25 sec for testing the day.''')

    group.add_argument("-c", "--control", nargs=4, metavar=('red', 'green', 'blue', 'white'),
                       type=int,
                       help='''controls the light directly with values from 0 to 255''')

    group.add_argument("-s", "--stop",
                       action='store_true',
                       help='''turn the light off and stop the lightdaemon''')

    group.add_argument("-d", "--daemon",
                       action='store_true',
                       help='''start the lightdaemon and checks if it was running and continues if needed''')

    parser.add_argument("-m", "--morning",
                        action="store_true",
                        help='''starts the lightdaemon and starts the day''')

    if len(sys.argv) == 1:
        parser.print_help(sys.stderr)
        sys.exit(1)

    args = parser.parse_args()

    if args.verbose:
        DEBUG = True

    if args.test:
        TEST = True

    if args.stop:
        end()
        sys.exit(0)

    elif args.control:
        r = args.control[0]
        g = args.control[1]
        b = args.control[2]
        w = args.control[3]
        if r > int(255) or r < int(0) \
                or g > int(255) or g < int(0) \
                or b > int(255) or b < int(0) \
                or w > int(255) or w < int(0):
            print("Error: Values must be 0-255")
            sys.exit(1)

        neopixels = neopixel.NeoPixel(getpin(PIN, returntype='circuitpython'), num_pixels, bpp=4, brightness=1.0, auto_write=False, pixel_order=ORDER)
        debugmessage("r: {} g: {} b: {} w: {}".format(r, g, b, w), DEBUG)
        neopixels.fill((r, g, b, w))
        neopixels.show()
        write_state('rgbw', "{},{},{},{}".format(r, g, b, w))
        write_state('state', 'noon')
        write_state('brightness', '100.00')
        cooling(1)
        logwrite(DAEMON, logfile, "control mode (r: {}, g: {}, b: {}, w: {})".format(r, g, b, w))
        sys.exit(0)

    elif args.daemon:
        if run(DAEMON, pidfile, DEBUG):
            # noinspection PyUnboundLocalVariable
            r, g, b, w = tuple([int(i) for i in rgbw.split(",")])
            if not TEST:
                dim = int(config['light']['dim'])  # 3600 sec
                dim_step = int(config['light']['dim_step'])  # 60 sec
                day = int(config['light']['day']) - (2 * dim)  # 57600 sec - 2xdim = 50400 = daylength w/o sunset/sunrise
                # night = int(config['light']['night']) not used anywhere
                sleepuntilstore = int(config['light']['sleepuntilstore'])

            else:
                # test mode
                dim = int(10)  # 10 sec
                dim_step = int(1)  # 1 sec
                day = int(25) - (2 * dim)  # 25 sec - 2xdim = 5 = daylength w/o sunset/sunrise
                sleepuntilstore = int(1)

            dim_steps = math.ceil(dim / dim_step)
            percent_step = 100 / dim_steps

            if args.morning:
                sun("sunrise", r, g, b, w)
                # the light is set up, now we sleep for the daytime
                sun("noon", noontime=day)
                # the sunset starts, dimming down
                sun("sunset", r, g, b, w)
                # good night
                end()

            else:
                restoring = read_restorestate()
                if restoring != '':
                    logwrite(DAEMON, logfile, "resuming operation..")
                    debugmessage("runfile found", DEBUG)
                    restore(restoring)
                else:
                    debugmessage("nothing to do, exiting", DEBUG)
                    os.remove(pidfile)
        else:
            sys.exit(0)

    else:
        print("nein.")
        sys.exit(1)
