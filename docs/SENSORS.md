# Sensors

Here are some (possibly useful) informations about the sensors and how the data is handled.

## Sensordata

> If you came here from the settings page in the frontend, you can scroll down to the readshm() function
(the last paragraph) if you don't want to know all the nifty details :)

### The Sensor Daemon

I called him Heimdall, because he sees everything.. or at least, he should. Because there are literally 
thousands of sensors, measuring different things and being accessed a different way, i have no idea
how to manage that in a more plug & play way. But i try my best to keep everything as modular as 
possible that (hopefully) only small changes need to be done to adapt a new / different sensor.

The sensordaemon is written in python and has some functions for reading and storing the sensordata.
It includes sensorclasses.py that has a class for the MH-Z19 co2 sensor (based on the mh_z19 python library by Dr. Takeyuki Ueda [github](https://github.com/UedaTakeyuki/mh-z19)) and 
a class for reading the MCP3008 a/d converter chip found on [tutorials-raspberrypi.de](https://tutorials-raspberrypi.de/raspberry-pi-mcp3008-analoge-signale-auslesen/).

I won't go too deep into details here, but i did my best to document how every function works - so 
feel free to look at the code if you want further details.

### Measuring and handling

Let's concentrate here on the readsensors(), the readshm() and the writesqlite() function!

#### readsensors()

(some things changed!)

As the name may or may not imply, this function reads the sensors. 

It reads config.ini [sensors] -> sensors comma separated list, imports the corresponding modules and invokes 
the read method in a loop.
Every module MUST at least have the read() method implemented, and accept one option when the class is invoked
(i.E. pin number, channel, path..).

the config [sensortype] -> helper is a comma separated string: module,option,modifier,extra_args
the modifier is somewhat special, i need it for my cap-shyg analog soil moisture sensor. let's see that line:

```
[MOIST]
helper=MCP3008,0,ScaleMoisture,3296,1381
```

the sensor needs the mcp3008 module with the option 0 (means the sensor data pin is connected to channel 0 of the chip)
it uses the ScaleMoisture modifier with the parameters 3296,1381. the modifier takes the raw numbers of the dry and wet
values (in that order!), processes the value measured and returns a percent value.

When everything went fine, it returns a tuple (just like an array in php world) with indices like this:

| index | value                | description       |
|-------|----------------------|-------------------|
| 0     | 2022-04-12 15:34:58  | datetime          |
| 1     | 20.4                 | temperature (°C)  |
| 2     | 30.3                 | humidity (%)      |
| 3     | 12                   | soil moisture (%) |
| 4     | 40                   | cpu temp (°C)     |
| 5     | 500                  | co2 (ppm)         |


This function is actually called in:
- the daemon loop, where it reads the sensors every N seconds and writes the data into the 
shared memory segment (SHM)
- the writesqlite() function that gets called by cron every 10 minutes

#### readshm()

this function is used when you want to see the most actual sensordata or for the relay daemon to 
watch over some value and initiate an action if it is above or below your desired default. 

it returns a space separated string.


the layout of the returned values is like:

| index | value      | description       |
|-------|------------|-------------------|
| 0     | 2022-04-12 | date              |
| 1     | 15:34:58   | timestamp         |
| 2     | 20.4       | temperature (°C)  |
| 3     | 30.3       | humidity (%)      |
| 4     | 12         | soil moisture (%) |
| 5     | 40         | cpu temp (°C)     |
| 6     | 500        | co2 (ppm)         |


so if you changed something with the sensors, the air/water settings page should reflect the new index,
that the relay daemon sees the right value to react to.

#### writesqlite()

creates a unix timestamp in utc to compensate the local timezone and avoid some problems with daylight
saving times. To keep the code simple, i decided to use utc only in this function so we don't need to do
too many conversions from utc to localtime and save processor cycles for other things.

To keep the database as small as possible i decided to use a key:value storage method. I decided to use 
sqlite, because its handy and does not need a separate server to keep things as simple as possible.
the index key is the unix timestamp (thus it should not repeat, resulting in strange behavior when you
have DST in your region..) and the data is a gzip encoded json string.  


# Sensor types

## Moisture Sensors

### Capacitive Soil Moisture Sensor v1.2 aka. CAP-SHYG
It seems it is manufactured in china. It's an analog device, so i had to
connect it with a MCP3008 ADC. To calibrate it, you can use
cap-shyg-calibrate.py do what the computer says and everything will be fine. lucky you! 

#### allright, but what's happening and why do i have to do that?!
Actually you need to take 2 measures:
one when bone dry (i.e. freshly unpacked) and one wet. You put the sensor
into a cup of water. but mind the circuits! in my use case (very high humidity) 
i put some hotglue over the circuits to seal it kinda effectively..
then it says some sensor values (in my case, 3620 when dry - 1440 when wet). 
because i'm a nice guy, you don't really have to do something with that
values, just enjoy them (or not) and the script adds that automatically to
the config file to be used for calculating an approximation of the
percentage of the soil moisture,

#### approxi-what?!
Because the real percentage of the soil moisture is depending on pH levels,
composition of your substrate - heck, even when you used fertilizer before
measuring! it will affect the measure of that device, because Tldr; it's a
capacitive device, measuring the electrical capacity of the substrate you
stick it in. the composition, pH levels and also the fertilizer can slightly
change the capacity of the substrate and so you can't have a EXACT
scientific nasa value for that, unless you are willing to contribute to this
project.

## CO2 Sensors

### About Calibration
Most of those devices have a ABC (Automatic Baseline Calibration) that once a
day sets the lowest measured value from the last 24 hours as the new minimum. 
Because the nature of our air, it's acually not possible (and in fact not a good idea) 
to reach a CO2 level of 0ppm. Our atmosphere has a CO2 concentration of roughly
400ppm CO2. The Sensor expects you, to open your windows once a day, that the CO2 level
settles again at 400ppm. Because your growbox should be airtight, you have to 
manually disable ABC in your sensor if possible. Otherwise, the measurings will
drift constantly, causing your measurings to display wrong values.

### Winsen MH-Z19C


## Sensor Modules (make your own)

Making your own sensor modules to have data observed over time is really easy. You need to create just two files in 
growbox-path/backend/includes/sensors/

#### SENSORNAME.py 

For reference i use the AIRRELAY module here. See the other Modules sources if you want to know more.
The AIRRELAY module is really simple. It reads a certain path/to/file, and outputs the data as tuple.

```python3
class AIRRELAY:
    def __init__(self, option: str) -> None:
        self.file = option

    def read(self) -> tuple[int]:
        with open(self.file, 'r') as file:
            data = int(file.read())

        result = (data,)
        return result

```

##### Rules:
- Please name the class same as the filename! 
- You can / should use the __init__ method to initialize your sensor, if it needs that, see backend/includes/sensors/MCP3308.py 
for a example of a more specialized initialization.
- the __init__ method MUST take the typed parameter option (i.e. str / int) if it needs configuration (in this example, the
- AIRRELAY module just needs the path/to/file - we prefer absolute paths!). I decided to use comma separated
string values when there is more than one option to set. Feel free to .split() it :)
- your class needs at the very least the read() method! The sensordaemon reads its values from the sensors by 
including the class, initialize it with some parameter and then runs the read() method.
- your method MUST return the data as a typed tuple (i.E. tuple[int], tuple[str], tuple[float]).


#### SENSORNAME.json

```json
{
  "id": "AIRRELAY",
  "active": "yes",
  "helper": "AIRRELAY",
  "section": "AIRRELAY",
  "name": "Air Relay",
  "delivers": "aironoff",
  "option": "string",
  "option_name": "path",
  "option_default": "/home/fox/growbox/tmp/air.run",
  "modifier": "",
  "description_de": "Status des Relais. 1 = an / 0 = aus.",
  "description_en": "State of the relay. 1 = on / 0 = off"
}
```

##### Description:

> id: the name of the module (i.e. AIRRELAY)

> active: when yes, you can use it in the sensor chooser in the frontend settings. if no, you'll have to activate the module
> in the frontend first.

> helper: the filename of the sensor module. this one can be different, i.e. when the sensor needs some circuitry in between, just like 
> the CAP-SHYG analog capacitive soil moisture sensor does. It uses an MCP3008 a/d convertor chip. Because other sensors might also
> need a/d conversion i decided to take this approach to reduce duplicated code. Please refer to
> growbox-path/backend/includes/sensors/CAP-SHYG.json and growbox-path/backend/includes/sensors/MCP3008.py to get an idea. 
> CAP-SHYG also uses a scalingModifier..

> name: name of the module in human readable form.

> delivers: is used to describe what the sensor delivers. configured values are: 'temp,rh' (temperature AND relative humidity, as
> with the DHT22 sensor), 'temp', 'rh', 'moist' (soil moisture), 'cputemp', 'co2', 'aironoff', 'wateronoff' and 'brightness'. If you need
> a new type, you have to define it in growbox-path/sensordeliver.ini and growbox-path/frontend/template/templatename/sensor-icons.json

> option: the type of the option 'string', 'str' or 'int'. this value is used in the frontend config section to set the
> input type of the input box. 

> option_name: the name of the option in human readable (understandable) format. Preferably one word, like
> 'path', 'channel', 'port'..

> option_default: a default that can be used in the input box in frontend settings. something like '/path/to/file'
> for a path option, '0' for a channel, '/dev/ttyAMA0' for a port..

> modifier: some modules (at least CAP-SHYG does) need a scaleModifier. I'll use the CAP-SHYG to explain that:
> the sensor is analog capacitive and uses a a/d convertor chip (as you might have noticed, because i'm repeating
> this over and over..). This chip returns a voltage as an integer number. in case of this sensor, a low number means wetness
> a high number dryness. To convert this value to a percent value, we need to calculate it - so the modifier takes additional
> arguments (in this case the before measured dry and wet value) plus the actual value to calculate. I decided to use this approach
> again, to reduce redundancies by reuseable code. Please refer to growbox-path/backend/includes/modifiers/
> its constructed like the sensor modules with .py and .json files.

> description_(2 letter langcode): a human readable (and most preferably easy understandable) description of 
> the module in that language (at the time of writing i have 2 languages, english and german (en / de) so..)
> feel free to add more languages ^^

### Please make sure, that the .json files are a+rw by chmod 766 your.json or run growbox-path/scripts/setup/file_permissions.sh 

The config for the modules is stored in growbox-path/sensors.ini and can be changed in frontend.
