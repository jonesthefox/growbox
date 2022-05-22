#!/bin/python3
"""
Easy handling for gpio toggling from the frontend. Takes gpio pin as first parameter, state (1 = on / 0 = off) as second.
If device is not attached, just quit.
"""
import configparser
import RPi.GPIO as GPIO
from includes.includes import getpin
from includes.includes import checkstate
from includes.includes import configfile
import sys

config = configparser.ConfigParser()
config.read(configfile())

gpio = int(sys.argv[1])
mode = int(sys.argv[2])

if gpio == int(config['air']['pin']):
    element = str('air')

elif gpio == int(config['water']['pin']):
    element = str('water')

if config[element]['attached'] == 'no':
    exit(0)

else:
    runfile = "{}/tmp/{}.run".format(config['default']['rootpath'], element)

    GPIO.setmode(GPIO.BCM)

    RELAY_GPIO = getpin(gpio, returntype='bcm')
    GPIO.setwarnings(False)
    GPIO.setup(RELAY_GPIO, GPIO.OUT)

    GPIO.output(RELAY_GPIO, mode)
    checkstate(runfile, 'w', str(mode))