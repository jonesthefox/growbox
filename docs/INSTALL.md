# Installation

## Raspbian

Write Raspberry Pi OS on an SD Card. There are a Plenty of Resources and Tutorials on this Task, so feel free to google it.

At time of writing, Bullseye is the latest. It brings PHP8 and Python3, yay - but we need php 8.1 and at this time 
(may 2022) its not there. so we'll install it ourselfes!

## setup

Run the setup script, it asks you all the necessary stuff and configures your growbox!
you will find it in growbox/scripts/setup/setup.sh

## manual setup

When Everything works fine (i.E. Network Connection / SSH access) it's time to install the Webserver, PHP-8, Python3-pip, 
python3-spidev, libgpiod2, ffmpeg and bc.
We also install (via pip) the necessary python libraries to access light and sensors. 

> sudo apt-get update
> sudo apt-get install python3-pip python3-spidev libgpiod2 lighttpd python3-dateutil ffmpeg bc ca-certificates 
apt-transport-https software-properties-common wget curl lsb-release

> curl -sSL https://packages.sury.org/php/README.txt | sudo bash -x
> sudo apt-get update

> sudo apt-get install php-common php8.1-cli php8.1-common php8.1-fpm php8.1-opcache php8.1-readline php8.1-sqlite3

> sudo pip3 install rpi_ws281x adafruit-circuitpython-neopixel adafruit_circuitpython-dht

In my setup i use the DHT22 temperature & relative humidity sensor, an analog capacitive soil moisture sensor attached to a 
mcp3008 a/d converter chip, an MH-Z19C NDIR co2 sensor, the WPM422 2-channel relay and
a neopixel rgbw 64 matrix (natural white, ~4500K).

next, we enable the user of our choice to access spi, i2c and gpio. make sure that your user has access to
the serial tty device.

> sudo usermod -a -G spi,i2c,gpio,tty your_username

If you like, replace in the files /etc/hostname and /etc/hosts 'raspberrypi' with something witty. Like "growbox".

> sed -i 's/raspberrypi/your-smart-hostname/g' /etc/hosts /etc/hostname

## file permissions

Some files and directories need permissions to allow www-data to write it.
You should run scripts/setup/file_permissions.sh 
> some files (the wrappers to control neopixel) need the suid bit set, so the script will ask you for your password to run sudo.
if your user is not in the sudoers list, please consult the internet for some tutorial.

## lighttpd

In /etc/lighttpd/lighttpd.conf change server.document-root according to your install directory. Let's assume, you installed 
growbox to your home directory (i.e. /home/your_username/growbox/), the line should read like this (remember to change 
your_username to your actual username):

> server.document-root        = "/home/your_username/growbox/frontend"

Next, enable the rewrite mod and php-fpm:

> sudo lighttpd-enable-mod rewrite
> sudo lighttpd-enable-mod fastcgi-php-fpm

Go to /etc/lighttpd/conf-enabled and edit the rewrite config. Should be something like 10-rewrite.conf :

> server.modules += ( "mod_rewrite" )
> url.rewrite-once = ( "^/projects/(.*)" => "/projects/$1" )
> url.rewrite-once += ( "^/tmpl/(.*)" => "/tmpl/$1" )
> url.rewrite-once += ( "^/doc-assets/(.*)" => "/doc-assets/$1" )
> url.rewrite-once += ( "^/(.*)/" => "/index.php?page=$1" )

and finally, edit the fastcgi-php-fpm config file. In my Case, 15-fastcgi-php-fpm.conf : 

> fastcgi.server += ( ".php" =>
>        ((
>                "socket" => "/run/php/php8.1-fpm.sock",
>                "broken-scriptfilename" => "enable"
>        ))
> )

To have the light and sensors automatically running, copy the file growbox/scripts/setup/cron.d/growbox to 
/etc/cron.d/growbox

then reload cron with
> sudo service cron reload

## If you have an relay and want to have it controlled by the relay daemon:

**BE WARNED the relay is not yet tested with a water pump, so it may or may not drown your plant and make a mess! DONT USE IT WITHOUT A MOISTURE SENSOR! REMEMBER: WATER AND ELECTRICITY IS NOT A GOOD COMBINATION!**

When you want to control a water pump, you SHOULD run the relay daemon in a terminal, set the debug flag (-v) and look 
carefully what it does. Don't let the daemon operate without human supervision until it is tested enough and has some way 
to control the water flow more precisely. At the moment, the daemon reads the moisture every 10 seconds as configured in config.ini. It 
starts the relay channel when the moisture level is below the set percentage and stops when it reaches the max value. 
Depending on your pump and the reaction time of your moisture sensor, it could pour oceans into your growbox, drown your plant, make a hell of a mess, ruins your floor
and probably the ceiling of the neighbor under you! **Consider yourself informed and warned!**

You can add a line to /etc/cron.d/growbox like this:

> */10 * * * * root /path/to/growbox/backend/relay.py -d ELEMENT 2>&1 | /path/to/growbox/scripts/sh/timestamp.sh >> /path/to/growbox/log/cron.log

set the corresponding pins in config.ini or via frontend settings and replace ELEMENT with 'air' or 'water' (without 
parenthesis). after that, you should reload cron. 

now reboot, and hopefully you can access the web-frontend via 
http://your-smart-hostname.local/

## pins

setup the gpio pins (the pin number on your raspberry!) in config.ini (pin= in section light, water, air) or in the 
frontend settings in the corresponding section.
Please note, that the NeoPixel must be connected to pin 12, 19, 32 or 40!

here is a list of my attached sensors and the pins they're connected to.

| Device                              | raspberry pin | description |
|-------------------------------------|---------------|-------------|
| Neopixel Matrix RGBW                | 2             | 5v          |
|                                     | 13            | gnd         |
|                                     | 12            | gpio 18     |
|                                     | 1             | 3.3v        |
|                                     |               |             |
| DHT22 Temperature & Humidity Sensor | 11            | gpio 17     |
|                                     | 17            | 3.3v        |
|                                     | 9             | gnd         |
|                                     |               |             |
| MCP3008 (A/D converter)             | 21            | gpio 9      |
|                                     | 23            | gpio 11     |
|                                     | 19            | gpio 10     |
|                                     | 24            | gpio 1      |
|                                     |               |             |
| MH-Z19 (CO2 Sensor)                 | 8             | gpio 14     |
|                                     | 10            | gpio 10     |
|                                     | 6             | gnd         |
|                                     | 4             | 5v          |
|                                     |               |             |
| WPM422 (2 Channel Relay Module)     | 25            | gnd         |
|                                     | 13            | gpio 27     |
|                                     | 15            | gpio 22     |
|                                     |               |             |

And here a list of the MCP3008 a/d converter connection:

| MCP3008 Pin | Description | Raspberry Pin | Description                                          |
|-------------|-------------|---------------|------------------------------------------------------|
| 1           | CH0         | NA            | Analog input, connected to soil moisture sensor Aout |
| 2 - 8       | CH1 - CH7   | NA            | Analog inputs channel 1-7                            |
| 9           | DGND        | 6             | gnd                                                  |
| 10          | CS/SHDN     | 24            | CE0                                                  |
| 11          | Din         | 19            | MOSI                                                 |
| 12          | Dout        | 21            | MISO                                                 |
| 13          | CLK         | 23            | SCLK                                                 |
| 14          | AGND        | 6             | gnd                                                  |
| 15          | Vref        | 1             | 3.3v                                                 |
| 16          | Vdd         | 1             | 3.3v                                                 |
