<?php
/** @noinspection PhpUnused */

/* language */
const _LANGUAGE_CODE = 'de-DE';
const _LANGUAGE_CODE_SHORT = 'de';

/* header */
const _APP_TITLE = 'growbox';
const _APP_KEYWORDS = 'Growbox, Pflanze, Botanik';
const _APP_DESCRIPTION = 'Kontrolliere dein Projekt';
const _OGTITLE = 'Deine Growbox';
const _VERSION = 'Version';

/* footer */
const _EXECUTION_TIME = 'Laufzeit';

/* plant */
const _PLANT_ACTUALPROJECT = 'Aktuelles Projekt';
const _PLANT_SPECIES = 'Spezies';
const _PLANT_ID = 'Identität';
const _PLANT_DAYTIME = 'Tageszeit';
const _PLANT_DAY = 'Tag';
const _PLANT_NIGHT = 'Nacht';
const _PLANT_DUSK = 'Sonnenuntergang';
const _PLANT_DAWN = 'Sonnenaufgang';
const _PLANT_GROW = 'Wachstum';
const _PLANT_BLOOM = 'Blüte';

/* sensors */
const _SENSOR_TEMP = 'Temperatur';
const _SENSOR_RH = 'Relative Luftfeuchtigkeit';
const _SENSOR_RH_ABBREV = 'RH';
const _SENSOR_RH_ABBREV_LONG = 'Rel. Luftfeuchtigkeit';
const _SENSOR_TEMP_RH = 'Temperatur & Rel. Luftfeuchtigkeit';
const _SENSOR_MOIST = 'Bodenfeuchte';
const _SENSOR_CPUTEMP = 'CPU Temperatur';
const _SENSOR_CPU = 'CPU';
const _SENSOR_CO2 = 'CO\u2082';
const _SENSOR_CO2_HTML = 'CO<sub>2</sub>';
const _SENSOR_PPM = 'ppm';
const _SENSOR_VALUES = 'Werte';
const _SENSOR_TIMESTAMP = 'Zeitstempel';
const _SENSOR_AIRONOFF = 'Belüftung';
const _SENSOR_WATERONOFF = 'Bewässerung';
const _SENSOR_BRIGHTNESS = 'Helligkeit';

/* forms */
const _FORM_LOGIN = 'Login';
const _FORM_CHANGE = 'Ändern';
const _FORM_RESET = 'Reset';
const _FORM_START = 'Starten';
const _FORM_STOP = 'Stoppen';
const _FORM_BACK = 'Zurück';
const _FORM_NEXT = 'Vor';
const _FORM_YES = 'Ja';
const _FORM_NO = 'Nein';
const _FORM_WARNING_SURE = 'Sicher?';

/* control */
const _CONTROLCENTER = 'Kontrollzentrum';
const _CONTROLCENTER_LIGHT_INFO = 'Hier kannst Du verschiedene Beleuchtungen ausprobieren (z.B. zum angeben). Zuvor gesetzte Beleuchtung wird überschrieben und nicht fortgesetzt.<br><strong>Dies kann seltsame Nebeneffekte erzeugen wenn der Dämon läuft, schalte ihn vorher aus. Es wird keine bestätigung verlangt wenn Du das Formular sendest! Du wurdest gewarnt!</strong>';
const _CONTROLCENTER_AIR_INFO = 'Hier kannst Du die Belüftung ein- oder ausschalten.<br><strong>Dies kann seltsame Nebeneffekte erzeugen wenn der Relay Dämon läuft, schalte ihn vorher aus. Es wird keine bestätigung verlangt wenn Du das Formular sendest! Du wurdest gewarnt!</strong>';
const _CONTROLCENTER_WATER_INFO = 'Hier kannst Du die Bewässerung ein- oder ausschalten.<br><strong>Dies kann seltsame Nebeneffekte erzeugen wenn der Relay Dämon läuft, schalte ihn vorher aus. Es wird keine bestätigung verlangt wenn Du das Formular sendest! Du wurdest gewarnt!</strong>';
const _CONTROLCENTER_COLOR = 'Farbe';
const _CONTROLCENTER_COLOR_INFO = 'Wähle eine Farbe mit der Beleuchtet werden soll (Weiss wird nicht gesetzt).';
const _CONTROLCENTER_STOP_INFO = 'Hier kannst du das Licht ausschalten.';

/* archive */
const _PROJECTARCHIVE = 'Archiv';
const _PROJECTARCHIVE_PROJECTS = 'Projekte';
const _IMG_ALT = 'Bild deines Projekts';

/* management */
const _PROJECT_MANAGEMENT = 'Verwaltung';
const _PROJECT_PLANT = 'Pflanze';
const _PROJECT_ACTIVE = 'Aktiv';
const _PROJECT_DAYSRUNNING = 'Tage';
const _PROJECT_GROWPHASE = 'Growphase';
const _PROJECT_GROWPHASE_INFO = 'Passe die Growphase an. Dies hat Auswirkung auf die Beleuchtung.';
const _PROJECT_ENDPROJECT = 'Projekt beenden';
const _PROJECT_WARNING_ENDPROJECT = 'Achtung! Du bist daran das Projekt beenden.';
const _PROJECT_WARNING_ENDPROJECT_LONG = 'DIES KANN NICHT RÜCKGÄNGIG GEMACHT WERDEN! Die Aktion wird einige Zeit dauern, bis das Timelapse Video erstellt wurde, verlasse die Seite nicht bis sie fertig geladen ist!';
const _PROJECT_ENDNOTE = 'End Notiz';
const _PROJECT_NEWPROJECT = 'Neues Projekt';
const _PROJECT_NEWPROJECT_TEXT = 'Dies startet ein neues Projekt.';
const _PROJECT_NEWPROJECT_PLACEHOLDER_PLANT = 'Taraxacum Ruderalia';
const _PROJECT_STARTNOTE = 'Start Notiz';

/* management backend */
const _PROJECT_BACKEND_NEWPROJECT_MKDIR = 'Projekt Bildverzeichnis erstellt';
const _PROJECT_BACKEND_NEWPROJECT_COPY_INDEX = 'Directory index kopiert';
const _PROJECT_BACKEND_NEWPROJECT_COPY_DEFAULTIMAGE = 'Platzhalter Bild kopiert';
const _PROJECT_BACKEND_NEWPROJECT_COPY_DEFAULTDB = 'Datenbank erstellt';
const _PROJECT_BACKEND_NEWPROJECT_DATABASE = 'Datenbank aktualisiert';
const _PROJECT_BACKEND_NEWPROJECT_CREATED = 'Neues Projekt erstellt';
const _PROJECT_BACKEND_ENDMESSAGE = 'Projekt erfolgreich beendet. Du kannst es dir im  <a href="/archive/">Archiv</a> anschauen. Nun reinige deine Growbox sorgfältig und <a href="/management/">starte ein neues Projekt</a>!';
const _PROJECT_BACKEND_DONE_MULTIMEDIA = 'Multimedia erledigt.';
const _PROJECT_BACKEND_DONE_CONFIG = 'config.ini geändert.';
const _PROJECT_BACKEND_DONE_DB = 'Datenbank geschrieben.';
const _PROJECT_BACKEND_DONE_FS = 'Projektverzeichnis und Datenbank ins Archiv verschoben';
const _PROJECT_BACKEND_BACKUP_CONFIG = 'Backup von config.ini erstellt';
const _PROJECT_BACKEND_BACKUP_CONFIG_REMOVED = 'Backup von config.ini entfernt';

/* charts */
const _CHART = 'Statistik';
const _DAILYAVG = 'Täglicher &Oslash;';
const _HOURLYAVG = 'Stündlicher &Oslash;';

/* main */
const _MAIN = 'Dashboard';
const _MAIN_NOPROJECT_ACTIVE = 'Kein Projekt aktiv.';
const _MAIN_NOPROJECT_STARTONE = '<a href="/management/">starte eins</a>!';

/* login */
const _LOGIN = 'Login';
const _LOGIN_LOGOUT = 'Logout';
const _LOGIN_INFO = 'Bitte logge dich ein.';
const _LOGIN_USERNAME = 'Benutzername';
const _LOGIN_PASSWORD = 'Passwort';
const _LOGIN_PASSWORD_INFO = 'Ändere dein Passwort.';
const _LOGIN_SECURITY = 'Sicherheit';
const _LOGIN_WRONG_CREDENTIALS = 'Falscher Benutzername und/oder Passwort';
const _LOGIN_SUCCESSFUL = 'Login erfolgreich';
const _LOGIN_LOGOUT_SUCCESSFUL = 'Logout erfolgreich. Tschüss!';

/* settings */
const _SETTINGS = 'Einstellungen';
const _SETTINGS_GPIO = 'GPIO Pin';
const _SETTINGS_ATTACHED = 'Hardware verbunden';

/* air */
const _AIR = 'Luft';
const _AIR_INDEX = 'Die Position des Wertes der relativen Luftfeuchtigkeit in den Sensordaten';
const _AIR_RH_INFO = 'Stelle die gewünschte maximale Luftfeuchtigkeit ein. Oberhalb der gewünschten Einstellung wird die Lüftung aktiviert.<br>Stelle die Zeit im Deaktivierungs Zeitplan ein, in der der Dämon das Relais nicht schaltet (wenn z.B. der Lärm deine Pflanzen beim schlafen stört). Format: Stunden, kommasepariert. 0,11 = 0:00 - 11:00';
const _AIR_RH_SETTING_MIN = 'RH Minimum %';
const _AIR_RH_SETTING_MAX = 'RH Maximum %';
const _AIR_VENTILATION = 'Belüftung';
const _AIR_VENTILATION_INFO = 'Ändere Einstellungen der Belüftung, die GPIO Pin Nummer deines Raspberry und die Position des Wertes der relativen Luftfeuchtigkeit in den <a href="/doc/#SENSORS">Sensordaten</a>.';
const _AIR_QUIETHOURS = 'Deaktivierungs Zeitplan';

/* cooling */
const _COOLING = 'Licht Kühlung';
const _COOLING_INFO = 'LED Leuchten können sehr heiss werden, um ihre Lebenszeit zu verlängern, solltest du einen Kühlkörper und einen Lüfter anbringen - welchen du hier konfigurieren kannst.';

/* frontend */
const _FRONTEND = 'Frontend';
const _FRONTEND_WEBFRONTEND = 'Web Frontend';
const _FRONTEND_WEBFRONTEND_INFO = 'Ändere das Look and feel vom Webfrontend.';
const _FRONTEND_LANGUAGE = 'Sprache';
const _FRONTEND_THEME = 'Skin';
const _FRONTEND_CHARTCOUNT = 'Datenpunkte';
const _FRONTEND_CHARTCOUNT_INFO = 'Anzahl für den Chart.';
const _FRONTEND_LOGCOUNT = 'Log Zeilen';
const _FRONTEND_LOGCOUNT_INFO = 'Anzahl der angezeigten Zeilen des Log.';
const _FRONTEND_SANDBOX = 'Sandbox';
const _FRONTEND_SANDBOX_INFO = 'Aktiviere Sandbox. Sandbox überspringt den Application Controller und ist nützlich um Dinge zu testen. Wird nicht benötigt wenn du nicht am Quellcode arbeitest.';

/* light */
const _LIGHT = 'Licht';
const _LIGHT_DAYNIGHT = 'Tag/Nachtlänge';
const _LIGHT_DAYNIGHT_INFO = 'Ändere die gewünschte Tag/Nachtlänge.';
const _LIGHT_LIGHTING = 'Beleuchtung';
const _LIGHT_DAYLENGTH = 'Tag <small>(Stunden)</small>';
const _LIGHT_NIGHTLENGTH = 'Nacht <small>(Stunden)</small>';
const _LIGHT_DUSKDAWN = 'Dämmerung';
const _LIGHT_DUSKDAWN_INFO = 'Hier kannst du die dauer der Dämmerung einstellen. Am Morgen wird das licht in dieser Zeit bis zur maximalen Helligkeit hoch- am Abend heruntergedimmt. Dim-Schritt ist die Dauer die gewartet wird, bevor das script die Helligkeit steigert/senkt.';
const _LIGHT_DIM = 'Dämmerung <small>(Minuten)</small>';
const _LIGHT_DIMSTEP = 'Dim-Schritt <small>(Sekunden)</small>';
const _LIGHT_NEOPIXEL = 'Neopixel';
const _LIGHT_NEOPIXEL_INFO = 'Ändere die Reihenfolge der Neopixel (RGBW/GRBW), die Anzahl Pixel und den Raspberry GPIO Pin der verbundenen Neopixel Matrix. Bitte beachte, dass die NeoPixel mit Pin 12 / 19 / 32 oder 40 verbunden werden <b>MUSS</b>!';
const _LIGHT_NUMPIXEL = 'Pixel';
const _LIGHT_PIXEL_ORDER = 'Pixel Reihenfolge';
const _LIGHT_BRIGHTNESS = 'Helligkeit';
const _LIGHT_BRIGHTNESS_INFO = 'Wähle die Helligkeit von 0 (min) bis 255 (max) für den jeweiligen Kanal (R,G,B,W). <a href="/doc/#LIGHT">Dokumentation</a>';
const _LIGHT_BRIGHTNESS_R = 'Rot';
const _LIGHT_BRIGHTNESS_G = 'Grün';
const _LIGHT_BRIGHTNESS_B = 'Blau';
const _LIGHT_BRIGHTNESS_W = 'Weiss';
const _LIGHT_OPTIONS = 'Optionen';
const _LIGHT_SLEEPUNTILSTORE = 'Zwischenspeichern (Sekunden)';
const _LIGHT_SLEEPUNTILSTORE_INFO = 'Hier kannst du einstellen wie viele sekunden im Modus Tag gewartet werden soll, bis die verbleibende Zeit gespeichert wird.';
const _LIGHT_SPECTRUM = 'Spektrum (Annäherung)';

/* network */
const _NETWORK = 'Netzwerk';
const _NETWORK_INFO = 'Ändere den Hostnamen deiner Growbox.';
const _NETWORK_HOST = 'Host';
const _NETWORK_HOSTNAME = 'Hostname';
const _NETWORK_HTTPHOST = 'HTTP Host';

/* photo */
const _PHOTO = 'Foto';
const _PHOTO_CAMERA = 'Kamera';
const _PHOTO_CAMERA_INFO = 'Hier kannst du deine verbundene Kamera konfigurieren.';
const _PHOTO_CAMERA_ATTACHED = 'Kamera verbunden';
const _PHOTO_SHUTTER = 'Shutter speed (ms)';
const _PHOTO_QUALITY = 'Qualität (%)';
const _PHOTO_WIDTH = 'Breite (px)';
const _PHOTO_HEIGHT = 'Höhe (px)';
const _PHOTO_AWB = 'AWB';
const _PHOTO_CAMERAAPP = 'Kamera App';

/* sensors */
const _SENSORS = 'Sensoren';
const _SENSORS_SENSOR = 'Sensor';
const _SENSORS_ACTIVATOR = 'Aktivieren';
const _SENSORS_ACTIVATOR_ACTIVE = 'Aktiv';
const _SENSORS_ACTIVATOR_INFO = 'Hier kannst du deine Sensor Module aktivieren.';
const _SENSORS_ACTIVATOR_WARNING = 'Achtung, wenn du sensormodule deaktiviert hast, musst du unbedingt die Reihenfolge anpassen!';
const _SENSORS_CHOOSER = 'Reihenfolge';
const _SENSORS_CHOOSER_FROMCONFIG = 'config.ini';
const _SENSORS_CHOOSER_INFO = 'Hier kannst du die Reihenfolge der Sensoren angeben. Sie werden in dieser Reihenfolge ausgelesen und wiedergegeben. Das ist wichtig für den Relay Dämon, damit er zur Steuerung den richtigen Wert beobachten kann!';
const _SENSORS_MODULE_CONFIG = 'Module konfigurieren';
const _SENSORS_DELIVERS = 'Liefert';
const _SENSORS_MODIFIER = 'Modifikator';
const _SENSORS_MODIFIERS = 'Modifikatoren';
const _SENSORS_MODIFIER_PERCENT = 'Prozent';
const _SENSORS_PIN = 'Pin';
const _SENSORS_CHANNEL = 'Kanal';
const _SENSORS_SERIAL = 'Serieller Port';
const _SENSORS_PATH = 'Pfad';

/* water */
const _WATER = 'Wasser';
const _WATER_INDEX = 'Die Position des Bodenfeuchte Wertes in den Sensordaten';
const _WATER_MINMAX = 'Mindest-/Maximalwerte';
const _WATER_MINMAX_INFO = 'Hier kannst du die Bewässerung einstellen, basierend auf dem Wert des Bodenfeuchte Sensors.<br>Stelle die Zeit im Deaktivierungs Zeitplan ein, in der der Dämon das Relais nicht schaltet (wenn z.B. der Lärm deine Pflanzen beim schlafen stört). Format: Stunden, kommasepariert. 0,11 = 0:00 - 11:00';
const _WATER_MOIST_MIN = 'Minimum (in %)<br><small>(ab diesem Wert wird die Bewässerung aktiviert)</small>';
const _WATER_MOIST_MAX = 'Maximum (in %)<br><small>(die Bewässerung wird deaktiviert)</small>';
const _WATER_WATERING = 'Bewässerung';
const _WATER_WATERING_INFO = 'Ändere Einstellungen der Bewässerung, die GPIO Pin Nummer deines Raspberry und die Position des Bodenfeuchte Wertes in den <a href="/doc/#SENSORS">Sensordaten</a>.';
const _WATER_QUIETHOURS = 'Deaktivierungs Zeitplan';

/* doc */
const _DOC = 'Dokumentation';

/* log */
const _LOGGING = 'Log';
const _LOGGING_LOG = 'Projektlog';
const _LOGGING_CRON = 'Cron Log';
const _LOGGING_EMPTY = 'Leer';

/* messages */
const _MESSAGE_ERROR_UNDEFINED = 'Computer sagt: NEIN!';
const _MESSAGE_SUCCESS = 'Erfolg';
const _MESSAGE_SUCCESSFUL = 'Erfolgreich';
const _MESSAGE_CONFIGCHANGED = 'Konfiguration geändert.';
const _MESSAGE_NOCHANGE = 'Nichts geändert';
const _MESSAGE_FAIL = 'Fehlschlag';
const _MESSAGE_ERROR = 'Fehler!';
const _MESSAGE_WARNING = 'Warnung!';
const _MESSAGE_INFO = 'Info';
const _MESSAGE_PASSWORD_REHASH = 'Neuer Password Hash generiert';

/* placeholders */
const _PLACEHOLDER = 'Lorem ipsum';
const _PLACEHOLDER_LONG = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.';

/* system */
const _SYSTEM_REBOOT = 'Neustart';
const _SYSTEM_SHUTDOWN = 'Ausschalten';
const _SYSTEM_SHUTDOWN_INFO = 'System wird ausgeschaltet. Anschliessend ziehe bitte den Stromstecker!';
