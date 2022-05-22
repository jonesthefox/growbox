<?php
/** @noinspection PhpUnused */

/* language */
const _LANGUAGE_CODE = 'en-US';
const _LANGUAGE_CODE_SHORT = 'en';

/* header */
const _APP_TITLE = 'growbox';
const _APP_KEYWORDS = 'Growbox, Plant, Botanics';
const _APP_DESCRIPTION = 'Control your Project';
const _OGTITLE = 'Your Growbox';
const _VERSION = 'Version';

/* footer */
const _EXECUTION_TIME = 'Runtime';

/* plant */
const _PLANT_ACTUALPROJECT = 'Actual Project';
const _PLANT_SPECIES = 'Species';
const _PLANT_ID = 'Identity';
const _PLANT_DAYTIME = 'Daytime';
const _PLANT_DAY = 'Day';
const _PLANT_NIGHT = 'Night';
const _PLANT_DUSK = 'Dusk';
const _PLANT_DAWN = 'Dawn';
const _PLANT_GROW = 'Grow';
const _PLANT_BLOOM = 'Blom';

/* sensors */
const _SENSOR_TEMP = 'Temperature';
const _SENSOR_RH = 'Relative humidity';
const _SENSOR_RH_ABBREV = 'RH';
const _SENSOR_RH_ABBREV_LONG = 'Rel. humidity';
const _SENSOR_TEMP_RH = 'Temperature & rel. humidity';
const _SENSOR_MOIST = 'Soil Moisture';
const _SENSOR_CPUTEMP = 'CPU Temperature';
const _SENSOR_CPU = 'CPU';
const _SENSOR_CO2 = 'CO\u2082';
const _SENSOR_CO2_HTML = 'CO<sub>2</sub>';
const _SENSOR_PPM = 'ppm';
const _SENSOR_VALUES = 'Values';
const _SENSOR_TIMESTAMP = 'Timetamp';
const _SENSOR_AIRONOFF = 'Ventilation';
const _SENSOR_WATERONOFF = 'Watering';
const _SENSOR_BRIGHTNESS = 'Brightness';

/* forms */
const _FORM_LOGIN = 'Login';
const _FORM_CHANGE = 'Change';
const _FORM_RESET = 'Reset';
const _FORM_START = 'Start';
const _FORM_STOP = 'Stop';
const _FORM_BACK = 'Back';
const _FORM_NEXT = 'Next';
const _FORM_YES = 'Yes';
const _FORM_NO = 'No';
const _FORM_WARNING_SURE = 'Sure?';

/* control */
const _CONTROLCENTER = 'Controlcenter';
const _CONTROLCENTER_LIGHT_INFO = 'Here you can try out different illuminations (e.g. to show off). Previously set lighting will be overwritten and not continued.<br><strong>This can create strange side effects when the daemon is running, turn it off first. There will be no confirmation on submit of this form! You have been warned!</strong>';
const _CONTROLCENTER_AIR_INFO = 'Here you can turn on- or off the ventilation.<br><strong>This can create strange side effects when the relay daemon is running, turn it off first. There will be no confirmation on submit of this form! You have been warned!</strong>';
const _CONTROLCENTER_WATER_INFO = 'Here you can turn on- or off the watering.<br><strong>This can create strange side effects when the relay daemon is running, turn it off first. There will be no confirmation on submit of this form! You have been warned!</strong>';
const _CONTROLCENTER_COLOR = 'Color';
const _CONTROLCENTER_COLOR_INFO = 'Choose a color for the light (White wont be set).';
const _CONTROLCENTER_STOP_INFO = 'Here you can turn the light off.';

/* archive */
const _PROJECTARCHIVE = 'Archive';
const _PROJECTARCHIVE_PROJECTS = 'Projects';
const _IMG_ALT = 'Image of your project';

/* management */
const _PROJECT_MANAGEMENT = 'Management';
const _PROJECT_PLANT = 'Plant';
const _PROJECT_ACTIVE = 'Active';
const _PROJECT_DAYSRUNNING = 'Days';
const _PROJECT_GROWPHASE = 'Growphase';
const _PROJECT_GROWPHASE_INFO = 'Adjust the growing phase. This will affect the lighting.';
const _PROJECT_ENDPROJECT = 'Finish Project';
const _PROJECT_WARNING_ENDPROJECT = 'Warning! You are about to end the Project.';
const _PROJECT_WARNING_ENDPROJECT_LONG = 'THIS CANNOT BE UNDONE! This will take a while until the Timelapse Video is generated. Please stay on the Page until it is loaded completely!';
const _PROJECT_ENDNOTE = 'End Note';
const _PROJECT_NEWPROJECT = 'New Project';
const _PROJECT_NEWPROJECT_TEXT = 'This starts a new project.';
const _PROJECT_NEWPROJECT_PLACEHOLDER_PLANT = 'Taraxacum Ruderalia';
const _PROJECT_STARTNOTE = 'Start Note';

/* management backend */
const _PROJECT_BACKEND_NEWPROJECT_MKDIR = 'Created project imagedirectory';
const _PROJECT_BACKEND_NEWPROJECT_COPY_INDEX = 'Copied directory index';
const _PROJECT_BACKEND_NEWPROJECT_COPY_DEFAULTIMAGE = 'Copied default image';
const _PROJECT_BACKEND_NEWPROJECT_COPY_DEFAULTDB = 'Database created';
const _PROJECT_BACKEND_NEWPROJECT_DATABASE = 'Database updated';
const _PROJECT_BACKEND_NEWPROJECT_CREATED = 'New project created';
const _PROJECT_BACKEND_ENDMESSAGE = 'Projekt ended successful. You can view it in the <a href="/archive/">archive</a>. Now clean your Growbox carefully and <a href="/management/">start a new project</a>!';
const _PROJECT_BACKEND_DONE_MULTIMEDIA = 'Multimedia done.';
const _PROJECT_BACKEND_DONE_CONFIG = 'config.ini changed.';
const _PROJECT_BACKEND_DONE_DB = 'Database written.';
const _PROJECT_BACKEND_DONE_FS = 'Projectfolder and database moved to archive';
const _PROJECT_BACKEND_BACKUP_CONFIG = 'Backup config.ini';
const _PROJECT_BACKEND_BACKUP_CONFIG_REMOVED = 'Removed config.ini backup';

/* charts */
const _CHART = 'Chart';
const _DAILYAVG = 'Daily &Oslash;';
const _HOURLYAVG = 'Hourly &Oslash;';

/* main */
const _MAIN = 'Dashboard';
const _MAIN_NOPROJECT_ACTIVE = 'No project running.';
const _MAIN_NOPROJECT_STARTONE = '<a href="/management/">start one</a>!';

/* login */
const _LOGIN = 'Login';
const _LOGIN_LOGOUT = 'Logout';
const _LOGIN_INFO = 'Please log in.';
const _LOGIN_USERNAME = 'Username';
const _LOGIN_PASSWORD = 'Password';
const _LOGIN_PASSWORD_INFO = 'Change your password.';
const _LOGIN_SECURITY = 'Security';
const _LOGIN_WRONG_CREDENTIALS = 'Wrong username and/or password';
const _LOGIN_SUCCESSFUL = 'Login successful';
const _LOGIN_LOGOUT_SUCCESSFUL = 'Logout successful. Bye!';

/* settings */
const _SETTINGS = 'Settings';
const _SETTINGS_GPIO = 'GPIO Pin';
const _SETTINGS_ATTACHED = 'Hardware attached';

/* air */
const _AIR = 'Air';
const _AIR_INDEX = 'The position of the relative humidity value in the sensordata';
const _AIR_RH_INFO = 'Set the desired humidity. Above the desired value, the ventilation will be activated.<br>Set the time in the deactivation schedule, when the daemon does not switch the relay (e.g. if the noise disturbs your plants while sleeping). Format: hours, comma separated. 0,11 = 0:00 - 11:00';
const _AIR_RH_SETTING_MIN = 'RH Minimum %';
const _AIR_RH_SETTING_MAX = 'RH Maximum %';
const _AIR_VENTILATION = 'Ventilation';
const _AIR_VENTILATION_INFO = 'Change ventilation Settings, the GPIO pin number of your raspberry and the index of the relative humidity in the <a href="/doc/#SENSORS">sensordata</a>.';
const _AIR_QUIETHOURS = 'Deactivation schedule';

/* cooling */
const _COOLING = 'Light Cooling';
const _COOLING_INFO = 'LED lights may get really hot, to expand lifetime you should attach a heatsink and a fan - which you can configure here.';

/* frontend */
const _FRONTEND = 'Frontend';
const _FRONTEND_WEBFRONTEND = 'Web frontend';
const _FRONTEND_WEBFRONTEND_INFO = 'Change the look and feel of the webfrontend.';
const _FRONTEND_LANGUAGE = 'Language';
const _FRONTEND_THEME = 'Skin';
const _FRONTEND_CHARTCOUNT = 'Datapoints';
const _FRONTEND_CHARTCOUNT_INFO = 'Data count for chart.';
const _FRONTEND_LOGCOUNT = 'Log lines';
const _FRONTEND_LOGCOUNT_INFO = 'The number of log lines to be shown.';
const _FRONTEND_SANDBOX = 'Sandbox';
const _FRONTEND_SANDBOX_INFO = 'Activate sandbox. Sandbox overrides index application controller and is useful for testing. Not needed unless you are working on the sourcecode.';

/* light */
const _LIGHT = 'Light';
const _LIGHT_DAYNIGHT = 'Day/Nightlength';
const _LIGHT_DAYNIGHT_INFO = 'Change the desired Day/Night Length.';
const _LIGHT_LIGHTING = 'Lighting';
const _LIGHT_DAYLENGTH = 'Day <small>(hours)</small>';
const _LIGHT_NIGHTLENGTH = 'Night <small>(hours)</small>';
const _LIGHT_DUSKDAWN = 'Twilight';
const _LIGHT_DUSKDAWN_INFO = 'Here you can set the duration of the twilight. In the morning the light will be dimmed up to the maximum brightness - in the evening it will be dimmed down. Dim-step is the duration that is waited before the script increases/decreases the brightness.';
const _LIGHT_DIM = 'Twilight <small>minutes</small>)';
const _LIGHT_DIMSTEP = 'Dim-step <small>(seconds)</small>';
const _LIGHT_NEOPIXEL = 'Neopixel';
const _LIGHT_NEOPIXEL_INFO = 'Change Pixel order (RGBW / GRBW), the pixel count and the raspberry GPIO pin of the attached neopixel matrix. Please note, that the NeoPixel <b>MUST</b> be connected to pin 12 / 19 / 32 or 40!';
const _LIGHT_NUMPIXEL = 'Pixel';
const _LIGHT_PIXEL_ORDER = 'Pixel order';
const _LIGHT_BRIGHTNESS = 'Brightness';
const _LIGHT_BRIGHTNESS_INFO = 'Set Brightness from 0 (min) to 255 (max) for the channel (R, G, B, W). <a href="/doc/#LIGHT">Documentation</a>';
const _LIGHT_BRIGHTNESS_R = 'Red';
const _LIGHT_BRIGHTNESS_G = 'Green';
const _LIGHT_BRIGHTNESS_B = 'Blue';
const _LIGHT_BRIGHTNESS_W = 'White';
const _LIGHT_OPTIONS = 'Options';
const _LIGHT_SLEEPUNTILSTORE = 'Cache (seconds)';
const _LIGHT_SLEEPUNTILSTORE_INFO = 'Here you can set how many seconds to wait until the remaining time of the day mode is saved.';
const _LIGHT_SPECTRUM = 'Spectrum (approximation)';

/* network */
const _NETWORK = 'Netzwork';
const _NETWORK_INFO = 'Change the hostname of your growbox.';
const _NETWORK_HOST = 'Host';
const _NETWORK_HOSTNAME = 'Hostname';
const _NETWORK_HTTPHOST = 'HTTP host';

/* photo */
const _PHOTO = 'Photo';
const _PHOTO_CAMERA = 'Camera';
const _PHOTO_CAMERA_INFO = 'Here you can configure your attached Camera.';
const _PHOTO_CAMERA_ATTACHED = 'Camera attached';
const _PHOTO_SHUTTER = 'Shutter speed (ms)';
const _PHOTO_QUALITY = 'Quality (%)';
const _PHOTO_WIDTH = 'Image width (px)';
const _PHOTO_HEIGHT = 'Image height (px)';
const _PHOTO_AWB = 'AWB';
const _PHOTO_CAMERAAPP = 'Camera application';

/* sensors */
const _SENSORS = 'Sensors';
const _SENSORS_SENSOR = 'Sensor';
const _SENSORS_ACTIVATOR = 'Activate';
const _SENSORS_ACTIVATOR_ACTIVE = 'Active';
const _SENSORS_ACTIVATOR_INFO = 'Here you can activate your sensor modules.';
const _SENSORS_ACTIVATOR_WARNING = 'Attention, if you deactivated sensormodule(s), you have to adjust the order!';
const _SENSORS_CHOOSER = 'Order';
const _SENSORS_CHOOSER_FROMCONFIG = 'config.ini';
const _SENSORS_CHOOSER_INFO = 'Here you can specify the order of the sensors. They are read out and reproduced in this order. This is important for the Relay daemon, so that it can observe the correct value for the controlling!';
const _SENSORS_MODULE_CONFIG = 'Modules configuration';
const _SENSORS_DELIVERS = 'Delivers';
const _SENSORS_MODIFIER = 'Modifier';
const _SENSORS_MODIFIERS = 'Modifiers';
const _SENSORS_MODIFIER_PERCENT = 'Percent';
const _SENSORS_PIN = 'Pin';
const _SENSORS_CHANNEL = 'Channel';
const _SENSORS_SERIAL = 'Serial Port';
const _SENSORS_PATH = 'Path';

/* water */
const _WATER = 'Water';
const _WATER_INDEX = 'The position of the soil moisture value in the sensordata';
const _WATER_MINMAX = 'Min-/maximum values';
const _WATER_MINMAX_INFO = 'Here you can adjust watering settings, based on the soil moisture sensore value.<br>Set the time in the deactivation schedule, when the daemon does not switch the relay (e.g. if the noise disturbs your plants while sleeping). Format: hours, comma separated. 0,11 = 0:00 - 11:00';
const _WATER_MOIST_MIN = 'Minimum (in %)<br><small>(reaching this value triggers the watering)</small>';
const _WATER_MOIST_MAX = 'Maximum (in %)<br><small>(the watering stops)</small>';
const _WATER_WATERING = 'Watering';
const _WATER_WATERING_INFO = 'Change watering settings, the GPIO pin number of your raspberry and the index of the soil moisture value in the <a href="/doc/#SENSORS">sensordata</a>.';
const _WATER_QUIETHOURS = 'Deactivation schedule';

/* doc */
const _DOC = 'Documentation';

/* log */
const _LOGGING = 'Log';
const _LOGGING_LOG = 'Projectlog';
const _LOGGING_CRON = 'Cron log';
const _LOGGING_EMPTY = 'Empty';

/* messages */
const _MESSAGE_ERROR_UNDEFINED = 'Computer says: NO!';
const _MESSAGE_SUCCESS = 'Success';
const _MESSAGE_SUCCESSFUL = 'Successful';
const _MESSAGE_CONFIGCHANGED = 'Configuration changed successful';
const _MESSAGE_NOCHANGE = 'Nothing changed';
const _MESSAGE_FAIL = 'Fail';
const _MESSAGE_ERROR = 'Error!';
const _MESSAGE_WARNING = 'Warning!';
const _MESSAGE_INFO = 'Info';
const _MESSAGE_PASSWORD_REHASH = 'New password hash generated';

/* placeholders */
const _PLACEHOLDER = 'Lorem ipsum';
const _PLACEHOLDER_LONG = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.';

/* system */
const _SYSTEM_REBOOT = 'Reboot';
const _SYSTEM_SHUTDOWN = 'Shutdown';
const _SYSTEM_SHUTDOWN_INFO = 'Shuts the system down. Pull the power plug afterwards!';
