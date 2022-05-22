# wrappers

why those evil suid wrappers, you may ask. and you are right - i also don't really like this approach, but 
in my opinion they are hard to avoid. 

the story is short: all three wrappers use the neopixel led matrix. unfortunately, when you want to access the
neopixel, you need access to /dev/mem - which is for root only. bad things may happen when a program like a webserver
has root access to /dev/mem. so to avoid that, i wrote those suid wrappers, where just one command is called.

sources are available in the scripts/wrapper/sources folder. go there, try 'make all' to rebuild them. possibly
you'll need to install some gcc dependencies..
after building, sudo chown root.root and sudo chmod a+s the wrappers.

## last_shot_and_timelapse_suid_wrapper

TODO: actually, i should rebuild that. this one calls scripts/sh/last_shot_and_timelapse.sh that will (as the name may imply)
make a last shot of your project, create a timelapse m4v movie and remove all the cluttering images to regain some disk space. 
it needs root access to turn the light on to make a photo in case the light is turned off. after the shot it will turn off the light.


## light_control_suid_wrapper

calls scripts/python/light.py -c which takes 4 mandatory parameters for red, green, blue and white
e.g. 

> light.py -c x x x x

where x is a integer in the range 0-255.
It is used to control the light via the web frontend, to show off or to fiddle around with different light settings.
-c 0 0 0 0 can be used to turn the light off, but it's preferred to use the stop button on the frontend.

## light_stop_suid_wrapper

calls scripts/python/light.py -s without any further arguments. as the name says, it stops the light, detaches the neopixels
write the action to the logfile, remove pidfiles and stuff.
