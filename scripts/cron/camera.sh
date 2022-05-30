#!/bin/bash

SCRIPTDIR=$( cd -- "$( dirname -- "${BASH_SOURCE[0]}" )" &> /dev/null && pwd )
ROOTPATH=$( cd -- "$( dirname -- "$SCRIPTDIR/../../../" )" &> /dev/null && pwd )
CONFIG=$ROOTPATH/config.ini

ACTIVE=$(awk -F "=" '/active/ {print $2}' "$CONFIG")
if [ "$ACTIVE" = "no" ]
then
exit 0
fi


BRIGHTNESS=$(grep -o '"brightness": "[^"]*' "$ROOTPATH"/tmp/sun.run | grep -o '[^"]*$')
if (( $(echo "$BRIGHTNESS == 0.00" | bc -l) ));
then
exit 0
fi

ID=$(awk -F "=" '/project/ {print $2}' "$CONFIG")
# SHUTTER=$(awk -F "=" '/shutter/ {print $2}' "$CONFIG")
QUALITY=$(awk -F "=" '/quality/ {print $2}' "$CONFIG")
WIDTH=$(awk -F "=" '/timelapse_width/ {print $2}' "$CONFIG")
HEIGHT=$(awk -F "=" '/timelapse_height/ {print $2}' "$CONFIG")
AWB=$(awk -F "=" '/awb/ {print $2}' "$CONFIG")
CAMERA=$(awk -F "=" '/camera/ {print $2}' "$CONFIG")

DATE=$(date +"%Y-%m-%d_%H%M")
DATENICE=$(date +"%Y-%m-%d %H:%M")

readarray -d " " -t SENSORDATA < <( "$ROOTPATH"/backend/sensors.py -o )

rm "$ROOTPATH"/frontend/projects/"$ID"/latest.jpg

$CAMERA -t 1 -n --width "$WIDTH" --height "$HEIGHT" --awb "$AWB" -q "$QUALITY" -o "$ROOTPATH"/frontend/projects/"$ID"/latest.jpg 2>/dev/null
convert -sharpen 0x5 "$ROOTPATH"/frontend/projects/"$ID"/latest.jpg "$ROOTPATH"/frontend/projects/"$ID"/latest.jpg
mogrify -gravity NorthEast -font Helvetica -pointsize 42 -draw "text 10,10 '$DATENICE'" -draw "text 10,50 'temp: ${SENSORDATA[2]}°C'" -draw "text 10,90 'rh: ${SENSORDATA[3]}%'" -draw "text 10,130 'moist: ${SENSORDATA[4]}%'" -draw "text 10,170 'co2: ${SENSORDATA[6]//$'\n'/}ppm'" "$ROOTPATH"/frontend/projects/"$ID"/latest.jpg

cp "$ROOTPATH"/frontend/projects/"$ID"/latest.jpg "$ROOTPATH"/frontend/projects/"$ID"/"$DATE".jpg

if [ -f "$ROOTPATH/frontend/projects/$ID/video.mp4" ]; 
then

ffmpeg -loglevel quiet -r 4 -y -pattern_type glob -i "${ROOTPATH}/frontend/projects/${ID}/latest.jpg" -t 0.25 -c:v libx264 "${ROOTPATH}"/frontend/projects/"${ID}"/latest.mp4
ffmpeg -loglevel quiet -r 4 -i "${ROOTPATH}"/frontend/projects/"${ID}"/video.mp4 -i "${ROOTPATH}"/frontend/projects/"${ID}"/latest.mp4 -filter_complex "[0:v:0][1:v:0]concat=n=2:v=1[outv]" -map "[outv]" -y "${ROOTPATH}"/frontend/projects/"${ID}"/tmp.mp4

rm "$ROOTPATH"/frontend/projects/"$ID"/video.mp4
rm "$ROOTPATH"/frontend/projects/"$ID"/latest.mp4
mv "$ROOTPATH"/frontend/projects/"$ID"/tmp.mp4 "$ROOTPATH"/frontend/projects/"$ID"/video.mp4

else

ffmpeg -loglevel quiet -r 4 -y -pattern_type glob -i "${ROOTPATH}/frontend/projects/$ID/latest.jpg" -t 0.25 -c:v libx264 "${ROOTPATH}"/frontend/projects/"$ID"/video.mp4
fi

chown www-data.www-data "$ROOTPATH"/frontend/projects/"$ID"/video.mp4
chown www-data.www-data "$ROOTPATH"/frontend/projects/"$ID"/latest.jpg
chown www-data.www-data "$ROOTPATH"/frontend/projects/"$ID"/"$DATE".jpg
