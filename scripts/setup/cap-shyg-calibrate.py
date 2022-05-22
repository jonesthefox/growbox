#!/bin/python3

import configparser
import os
import sys
import time

# generate path to includes.py for import

scriptpath = os.path.realpath(__file__)
basepath = scriptpath.removesuffix("scripts/setup/{}".format(os.path.basename(__file__)))
includepath = str(basepath) + str("backend/includes")
sensormodulepath = str(basepath) + str("backend/includes/sensors")
sys.path.append(includepath)
sys.path.append(sensormodulepath)

from includes import configfile

helper = "MCP3008"
option = "0"

module = __import__(helper)
class_ = getattr(module, helper)
device = class_(option=option)


iterations = 100000
val_dry = []
val_wet = []

def query(question: str, default: str = "yes") -> bool:
    """
    Ask a yes/no question via raw_input() and return their answer.

    :rtype: bool
    :param question: is a string that is presented to the user.
    :param default: is the presumed answer if the user just hits <Enter>.
                    It must be "yes" (the default), "no" or None (meaning
                    an answer is required of the user).
    :return: The "answer" return value is True for "yes" or False for "no".
    """
    valid = {"yes": True, "y": True, "ye": True, "no": False, "n": False}
    if default is None:
        prompt = " [y/n] "
    elif default == "yes":
        prompt = " [Y/n] "
    elif default == "no":
        prompt = " [y/N] "
    else:
        raise ValueError("invalid default answer: '%s'" % default)

    while True:
        sys.stdout.write(question + prompt)
        choice = input().lower()
        if default is not None and choice == "":
            return valid[default]
        elif choice in valid:
            return valid[choice]
        else:
            sys.stdout.write("Please respond with 'yes' or 'no' " "(or 'y' or 'n').\n")

print("Welcome to the cap-shyg moist sensor calibration tool!\n\n"
        "Please make sure your sensor is connected.\n"
        "We will first check the most dry, and then the most wet value.\n"
        "(It takes 100000 values, so it may take a while.)\n"
        "After that, we write the values to the config.ini if you like.\n\n")

if not query("Continue?", "no"):
    exit(0)

else:
    input("\nMake sure that your sensor is dry and press ENTER to continue...")
    print("\nMeasuring now...\n")
    for i in range(iterations):
        v = device.read()
        print(v)
        val_dry.append(v)

    dry = str(sum(val_dry) / len(val_dry))
    dry = dry.split(".")
    print("\nDry:", int(dry[0]))

    input ("\nNow place your sensor in a cup of water, and press ENTER to continue...")
    print("\nSleeping 10 seconds to settle the sensor")
    time.sleep(10)

    print("\nMeasuring now...\n")

    for i in range(iterations):
        val_wet.append(device.read())

    wet = str(sum(val_wet) / len(val_wet))
    wet = wet.split(".")
    print("\nWet:", int(wet[0]))

    if not query("\nDo you want to write the new values to config.ini?", "no"):
        print("Leaving config.ini alone!")

    else:
        print ("Updating config.ini")
        config = configparser.ConfigParser()
        config.read(configfile())
        config['sensors']['moistsensor_dry'] = dry[0]
        config['sensors']['moistsensor_wet'] = wet[0]
        with open(configfile(), 'w') as file:
            config.write(file, space_around_delimiters=False)

exit(0)
