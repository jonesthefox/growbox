#!/bin/bash
# shellcheck disable=SC2162

SCRIPTDIR=$( cd -- "$( dirname -- "${BASH_SOURCE[0]}" )" &> /dev/null && pwd )
ROOTPATH=$( cd -- "$( dirname -- "$SCRIPTDIR/../../../" )" &> /dev/null && pwd )

echo "now we will setup your growbox. It will ask for your password to run sudo"
read -p "Press ENTER to continue or CTRL-C to abort..."

curl -sSL https://packages.sury.org/php/README.txt | sudo bash -x
sudo apt-get update

sudo apt-get install php-common php8.1-cli php8.1-common php8.1-fpm php8.1-opcache php8.1-readline php8.1-sqlite3 python3-pip python3-spidev libgpiod2 lighttpd python3-dateutil ffmpeg bc ca-certificates apt-transport-https software-properties-common wget curl lsb-release
sudo pip3 install rpi_ws281x adafruit-circuitpython-neopixel adafruit_circuitpython-dht

sudo usermod -a -G spi,i2c,gpio,tty,video,dialout "$USER"
sudo usermod -a -G spi,i2c,gpio,tty,video,dialout www-data

echo "Please enter a hostname (i.E. growbox)"
read -p "Hostname: " host

sudo sed -i "s/raspberrypi/$host/g" /etc/hosts /etc/hostname

sed -i "s_/var/www/html_$ROOTPATH/frontend_g" /etc/lighttpd/lighttpd.conf

sudo lighttpd-enable-mod rewrite
sudo lighttpd-enable-mod fastcgi-php-fpm

sudo cp "$ROOTPATH"/scripts/setup/lighttpd/10-rewrite.conf /etc/lighttpd/conf-enabled/10-rewrite.conf
sudo cp "$ROOTPATH"/scripts/setup/lighttpd/15-fastcgi-php-fpm.conf /etc/lighttpd/conf-enabled/15-fastcgi-php-fpm.conf
sudo cp "$ROOTPATH"/scripts/setup/cron.d/growbox /etc/cron.d/growbox

sudo service cron reload

cd "$ROOTPATH/scripts/wrapper/sources" || exit
make all

ln -s "$ROOTPATH"/docs/assets "$ROOTPATH"/frontend/assets

"$ROOTPATH"/scripts/setup/file_permissions.sh

php "$ROOTPATH"/scripts/setup/includes/genpasswd.php

echo "Done. Your growbox should be reachable under http://$host.local"
echo "If no error message occured you should reboot now. If not, check what went wrong and file a issue."
read -p "Press ENTER to continue or CTRL-C to abort..."

sudo reboot
