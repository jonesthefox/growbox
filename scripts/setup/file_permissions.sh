#!/bin/bash

# shellcheck disable=SC2010

SCRIPTDIR=$( cd -- "$( dirname -- "${BASH_SOURCE[0]}" )" &> /dev/null && pwd )
ROOTPATH=$( cd -- "$( dirname -- "$SCRIPTDIR/../../../" )" &> /dev/null && pwd )

chmod 766 "$ROOTPATH"/config.ini
ls -lah "$ROOTPATH"/config.ini

chmod 766 "$ROOTPATH"/sensors.ini
ls -lah "$ROOTPATH"/sensors.ini

chmod +x "$ROOTPATH"/backend/*.py
ls -lah "$ROOTPATH"/backend/*.py

sudo chown www-data.www-data "$ROOTPATH"/.passwd
chmod 666 "$ROOTPATH"/.passwd
ls -lah "$ROOTPATH"/.passwd

chmod 766 "$ROOTPATH"/db/*.sqlite
ls -lah "$ROOTPATH"/db/*.sqlite

chmod 777 "$ROOTPATH"/tmp
ls -lah "$ROOTPATH"/ | grep tmp

chmod 777 "$ROOTPATH"/log
ls -lah "$ROOTPATH"/ | grep log

sudo chmod 777 "$ROOTPATH"/log/growbox.log
sudo chown www-data.www-data "$ROOTPATH"/log/growbox.log

ls -lah "$ROOTPATH"/log | grep growbox.log

chmod 777 "$ROOTPATH"/db
ls -lah "$ROOTPATH"/ | grep db

chmod 777 "$ROOTPATH"/frontend/projects
ls -lah "$ROOTPATH"/frontend | grep projects

chmod 777 "$ROOTPATH"/frontend/projects/old
ls -lah "$ROOTPATH"/frontend/projects | grep old

chmod 766 "$ROOTPATH"/backend/includes/sensors/*.json
ls -lah "$ROOTPATH"/backend/includes/sensors/*.json

sudo chown root.root "$ROOTPATH"/scripts/wrapper/*_wrapper
sudo chmod a+s "$ROOTPATH"/scripts/wrapper/*_wrapper

echo 'done, all permissions set.'
