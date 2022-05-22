<?php

/** @noinspection CommaExpressionJS HtmlUnknownAttribute*/

class Sensors
{

    /**
     * @var string the sensors.ini configfile, relative to webroot (/frontend)
     */
    static string $sensorConfigFile = "../sensors.ini";
    static string $sensorDeliversConfigFile = "../sensordeliver.ini";

    /**
     * generates html checkboxes for each sensor available and can update the appropriate json files in backend/includes/sensors
     * to set them as active or inactive
     *
     * html generator mode:
     * checks SENSOR.json if the module is active (available to configure and actually use) or not.
     * generates html checkboxes in tr td tags for use in a table.
     *
     * update mode:
     * iterates through array and updates the corresponding json files accordingly. generates error message if something went wrong.
     *
     * @param array|null $sensorArray array containing filenames and setting for active/inactive
     * @return string|array returns html string without parameter, array $message if parameter is an array.
     */
    static function sensorActive(array $sensorArray = null): string|array
    {
        if ($sensorArray == null) {
            $content = "";
            $moduleList = self::moduleList();
            foreach ($moduleList as $module) {
                $sensor = json_decode(file_get_contents($module), true, flags: JSON_OBJECT_AS_ARRAY);
                $module = basename($module, '.json');
                $checked = ($sensor['active'] == "yes") ? 'checked="checked"' : null;
                $content .= '<tr><td>' . $sensor['name'] . '</td><td><input type="hidden" id="sensor[' . $module . ']" name="sensor[' . $module . ']" value="no"><input type="checkbox" id="sensor[' . $module . ']" name="sensor[' . $module . ']" value="yes" ' . $checked . '></td></tr>';
            }
            $content .= (count($moduleList) % 2 == 0) ? '<tr></tr>' : null;
        }

        else {
            $result = '<table>';
            foreach ($sensorArray as $module => $value) {
                $file = Cfg::read('default','rootpath').'/backend/includes/sensors/'.$module.'.json';
                $sensor = json_decode(file_get_contents($file), true, flags: JSON_OBJECT_AS_ARRAY);
                $sensor['active'] = $value;
                try { file_put_contents($file, json_encode($sensor)); }
                catch (Exception $e) { $message[] = array("messageType" => 'error', "messageText" => $e->getMessage()); }
                if (!isset($e)) { $result .= "<tr><td>$module:</td><td>"._MESSAGE_CONFIGCHANGED."</td></tr>"; }
            }
            $result .= '</table>';
            $message[] = array("messageType" => 'success', "messageText" => $result);
            $message[] = array("messageType" => 'warning', "messageText" => _SENSORS_ACTIVATOR_WARNING);
        }

        return (!isset($content)) ? $message : $content;
    }

    /**
     * generates html select boxes in tr td tags - hate me :) but this way i can control the table outside of the code in the template,
     * have a look in tmpl/default/pages/sensors-tmpl.php
     * it also generates the appropriate values for name & id of the select and the correct numbers to control the disable function from
     * tmpl/default/js/sensors.js
     *
     * returns n select boxes (n = number of active sensors as in SENSOR.json from backend/includes/sensors/) with default value "----" selected
     * this entry is disabled to ensure htm5 required shows an effect when a position has nothing selected.
     * on selection of a certain sensor, it gets disabled in the other select boxes to circumvent double selection of a sensor module (which could result in
     * the end of the world as we know it..). when you go back to a set select (on focus) it saves the last selected value in a parameter of the select tag
     * to undisable that entry in the other select boxes when you choose something else (that is not disabled by another field).
     *
     * @param array|null $position array with the sensors in the desired position
     * @return string|array
     */
    static function sensorChooser(array $position = null): string|array
    {
        if ($position == null) {
            $sensorList = Cfg::read('sensors', 'sensors');
            $moduleList = self::moduleList();
            $message = '';
            $count = 0;

            foreach ($moduleList as $mod) {
                $sensor = json_decode(file_get_contents($mod), true, flags: JSON_OBJECT_AS_ARRAY);
                if ($sensor['active'] == 'yes') { $count++; }
            }

            if ($sensorList != "") { $sensorList = explode(',', $sensorList); }

            for ($i = 0; $i < $count; $i++) {
                $cfgOrder = '';
                /** @noinspection JSVoidFunctionReturnValueUsed */
                $constructor = '<tr><td>' . $i . '</td><td><select name="position[' . $i . ']" id="position[' . $i . ']" onfocus="previous(this.id, this.value);" onchange="disabler(' . $i . ', ' . $count . ', this.value)" required><option value="" disabled selected>-----</option>';

                foreach ($moduleList as $module) {
                    $sensor = json_decode(file_get_contents($module), true, flags: JSON_OBJECT_AS_ARRAY);
                    $constructor .= ($sensor['active'] == 'yes') ? '<option value="' . $sensor['section'] . '">' . $sensor['id'] . '</option>' : null;
                    $cfgOrder .= (is_array($sensorList)) ? (array_key_exists($i,$sensorList) && $sensorList[$i] == $sensor['section']) ? $sensor['id'] : null : "Null";
                }
                $constructor .= '</select></td><td>' . $cfgOrder . '</td></tr>';
                $message .= $constructor;
            }
            $message .= ($count % 2 == 0) ? '<tr></tr>' : null;
        }

        else {
            $sensors = implode(',', $position);
            $types = str_replace('+',',', strtolower($sensors));

            try { $sensors = Cfg::write('sensors','sensors', $sensors, true); }
            catch (Exception $e ) { $message[] = array("messageType" => 'error', "messageText" => $e); }
            $message[] = ($sensors != "") ? array("messageType" => 'success', "messageText" => $sensors) : array("messageType" => 'info', "messageText" => _MESSAGE_NOCHANGE);

            try { $types = Cfg::write('sensors','types', $types, true); }
            catch (Exception $e ) { $message[] = array("messageType" => 'error', "messageText" => $e); }
            $message[] = ($types != "") ? array("messageType" => 'success', "messageText" => $types) : array("messageType" => 'info', "messageText" => _MESSAGE_NOCHANGE);
        }

        return $message;
    }

    /**
     * generates a array containing either all modifier.json path/to/file from backend/includes/modifiers OR a specific
     * file, when $module is set
     * @param string|null $module the desired modifier
     * @return array
     */
    static function modifierList(string $module = null): array
    {
        if ($module != null)
        { return glob(Cfg::read('default','rootpath').'/backend/includes/modifiers/'.$module.'.json', GLOB_BRACE); }

        else { return glob(Cfg::read('default','rootpath').'/backend/includes/modifiers/*.json', GLOB_BRACE); }

    }

    /**
     * generates a array containing all SENSOR.json path/to/file from backend/includes/sensors
     * @return array
     */
    static function moduleList(): array
    {
        return glob(Cfg::read('default','rootpath').'/backend/includes/sensors/*.json', GLOB_BRACE);
    }

    /**
     * generates html options for sensor modules in settings
     * reads *.json in backend/includes/sensors/
     *
     * @return string html options
     */
    static function sensorModules(): string
    {
        $sensorBoxes = "";
        foreach (self::moduleList() as $module) {
            $moduleCfg = json_decode(file_get_contents($module), true, flags: JSON_OBJECT_AS_ARRAY);
            $sensorBoxTemplate = file_get_contents("tmpl/" . Cfg::read('web', 'template') . "/box-constructs/sensor-box.php");

            if ($moduleCfg['active'] != "no") {
                $optionName = match ($moduleCfg['option_name']) {
                    "channel" => _SENSORS_CHANNEL,
                    "path" => _SENSORS_PATH,
                    "pin" => _SENSORS_PIN,
                    "serial" => _SENSORS_SERIAL,
                    default => $moduleCfg['option_name']
                };

                $inputType = match ($moduleCfg['option']) {
                    'int' => 'number',
                    'string', 'str' => 'text'
                };

                $delivers = constant(Sensors::readDeliversCfg('sensor','delivers', true)[$moduleCfg['delivers']]);

                $helper = (isset(self::readCfg($moduleCfg['section'], 'helper')[1]))
                    ? self::readCfg($moduleCfg['section'], 'helper')[1]
                    : "";

                $value = ($helper != "")
                    ? 'value="' . $helper . '"'
                    : 'placeholder="' . $moduleCfg['option_default'] . '"';

                $search = array('##id##', '##confirm##', '##helpername##',
                    '##description##', '##delivers##', '##delivertype##',
                    '##option_name##', 'number', '##value##',
                    '##module##', '##helper##', '##formsend##');

                $replace = array($moduleCfg['name'], _FORM_WARNING_SURE, $moduleCfg['name'],
                    $moduleCfg["description_" . Cfg::read('web', 'language')], _SENSORS_DELIVERS, $delivers,
                    $optionName, $inputType, $value,
                    $moduleCfg['section'], $moduleCfg['helper'], _FORM_CHANGE);

                $sensorBoxConstructor = str_replace($search,$replace,$sensorBoxTemplate);

                unset($replace);

                $replace = ($moduleCfg['modifier'] != "")
                    ? self::modifierModules($moduleCfg['modifier'], $moduleCfg['section'])
                    : '';

                $sensorBoxConstructor = str_replace("##modifierbox##", $replace, $sensorBoxConstructor);

                $sensorBoxes .= $sensorBoxConstructor;
            }
        }
        return $sensorBoxes;
    }

    /**
     * generates html options for sensor modules in settings when $
     * reads *.json in backend/includes/sensors/
     *
     * @return string html options
     */
    static function modifierModules(string $module = null, string $section = null): string
    {
        $modifierBoxes = "";
        foreach (self::modifierList($module) as $modifier) {
            $modifierCfg = json_decode(file_get_contents($modifier), true, flags: JSON_OBJECT_AS_ARRAY);

            $delivers = match ($modifierCfg['delivers']) {
                "%" => _SENSORS_MODIFIER_PERCENT,
                default => $modifierCfg['delivers']
            };

            $description = "description_" . Cfg::read('web', 'language');

            if ($module != null) {
                $modifierBoxTemplate = file_get_contents("tmpl/" . Cfg::read('web', 'template') . "/box-constructs/modifier-box.php");
                $search = array('##modifier##', '##modifiername##', '##delivers##', '##delivertype##', '##modifiercfgbox##');
                $replace = array(_SENSORS_MODIFIER, $modifierCfg['name'], _SENSORS_DELIVERS, $delivers,
                    self::modifierOptions($section, $modifierCfg['option'], $modifierCfg['option_name'], $modifierCfg['option_default'],$modifierCfg['name']));
            }
            else {
                $modifierBoxTemplate = file_get_contents("tmpl/" . Cfg::read('web', 'template') . "/box-constructs/modifier-overview-box.php");
                $search = array('##id##', '##modifiername##', '##description##', '##delivers##', '##delivertype##');
                $replace = array($modifierCfg['name'], $modifierCfg['name'], $modifierCfg[$description], _SENSORS_DELIVERS, $delivers);
            }

            $modifierBoxes .= str_replace($search, $replace, $modifierBoxTemplate);
        }

        return $modifierBoxes;
    }

    /**
     * generates html inputs for the modifier options
     *
     * @param string $section section in sensors.ini
     * @param string $option type of the option "int"|"string"
     * @param string $option_name the name of the option (i.E. 'dry' 'wet' from the ScaleMoisture modifier)
     * @param string $option_default the default
     * @param string $helper the name of the modifier module
     * @return string constructed html code
     */
    static function modifierOptions(string $section, string $option, string $option_name, string $option_default, string $helper): string
    {
        $optionTemplate = file_get_contents(Bootstrap::TEMPLATEDIR."/box-constructs/modifier-cfg-box.php");
        $optionBox = "";
        $i = 0;

        $option = explode(',', $option);
        $option_name = explode(',',$option_name);
        $option_default = explode(',',$option_default);

        if (count($option) > 1) {
            foreach ($option as $item) {
                $inputType = match ($item) {
                    'int' => 'number',
                    'string', 'str' => 'text'
                };

                $optionName = $option_name[$i];

                if (isset(self::readCfg($section,'helper')[$i+3])) {
                    $value = 'value="'.self::readCfg($section,'helper')[$i+3].'"';
                }
                else {
                    $value = 'placeholder="'.$option_default[$i].'"';
                }

                $search = array('##option_name##', '##modifier##', '##option_id##', 'number', '##value##');
                $replace = array($optionName, $helper, $i, $inputType, $value);
                $optionBoxConstructor = str_replace($search, $replace, $optionTemplate);

                $optionBox .= $optionBoxConstructor;
                $i++;
            }
        }

        else {
            $inputType = match ($option[$i]) {
                'int' => 'number',
                'string' => 'text'};

            $optionName = $option_name[$i];

            if (isset(self::readCfg($modifierCfg['section'],'helper')[$i+3])) {
                $value = 'value="'.self::readCfg($modifierCfg['section'],'helper')[$i+3].'"';
            }
            else {
                $value = 'placeholder="'.$option_default[$i].'"';
            }

            $search = array('##option_name##', '##inputtype##', '##value##');
            $replace = array($optionName, $inputType, $value);
            $optionBoxConstructor = str_replace($search,$replace, $optionTemplate);

            $optionBox = $optionBoxConstructor;
        }

        return $optionBox;
    }

    /**
     * reads a value from sensors.ini
     * @param $configSection string the section
     * @param $configKey string the key
     * @param bool $std dont process value with explode
     * @return array|bool returns string or bool
     */
    static function readCfg(string $configSection, string $configKey, bool $std = false): array|bool
    {
        $config = parse_ini_file(self::$sensorConfigFile, TRUE, INI_SCANNER_RAW);
        if (array_key_exists($configSection, $config))
        {
            $return = $std
                ? $config[$configSection][$configKey]
                : explode(",", $config[$configSection][$configKey]);
        }
        else { $return = false; }

        return $return;
    }

    /**
     * reads a value from sensordeliver.ini
     * @param $configSection string the section
     * @param $configKey string the key
     * @param bool $std dont process value with explode
     * @return array|bool returns string or bool
     */
    static function readDeliversCfg(string $configSection, string $configKey, bool $std = false): array|bool
    {
        $config = parse_ini_file(self::$sensorDeliversConfigFile,TRUE, INI_SCANNER_RAW);
        if (array_key_exists($configSection, $config))
        {
            $return = $std
                ? $config[$configSection][$configKey]
                : explode(",", $config[$configSection][$configKey]);
        }
        else { $return = false; }

        return $return;
    }


    /**
     * updates module and modifier configuration in sensors.ini
     * @param array $cfg the sanitized $_POST['cfg'] array
     * @return array returns messagebox on error/success
     */
    static function updateCfg(array $cfg): array
    {
        $config = parse_ini_file(self::$sensorConfigFile, TRUE, INI_SCANNER_RAW);
        $count = count($cfg);
        $configSection = $cfg['module'];
        $configKey = 'helper';
        $configDataNew = '';

        if ($count == 3) {
            $configValueNew = $cfg['helper'].",".$cfg['option'];
        }

        elseif ($count == 5) {
            $configValueNew = $cfg['helper'].",".$cfg['option'].",".$cfg['modifiermodule'];
            foreach ($cfg['modifier'] as $item) { $configValueNew .= ",".$item; }
        }

        if (array_key_exists($configSection,$config))
        { $configValueOld = $config[$configSection][$configKey]; }
        else { $configValueOld = ""; }
        if ($configValueNew == $configValueOld) {
            $result = "<table><tr><td>"._MESSAGE_NOCHANGE."</td></tr></table>";
            $message[] = array("messageType" => 'info', "messageText" => $result);
            return $message;
        }

        else {
            /** @noinspection PhpAutovivificationOnFalseValuesInspection */
            $config[$configSection][$configKey] = $configValueNew;
            foreach ($config as $section => $section_content) {
                echo $section."<br>";
                $section_content = array_map(function ($value, $key) { return "$key=$value"; },
                    array_values($section_content), array_keys($section_content));
                $section_content2 = implode("\n", $section_content);
                $configDataNew .= "[$section]\n$section_content2\n";
            }
        }

        $result = "<table>";

        try { file_put_contents(self::$sensorConfigFile, $configDataNew); }
        catch (Exception $e) { $message[] = array("messageType" => 'error', "messageText" => $e->getMessage()); }
        if (!isset($e)) {
            $result .= "<tr><td>"._MESSAGE_CONFIGCHANGED."</td></tr>";
            $result .= "<tr><td><small>$configValueOld =&gt; $configValueNew</small></td></tr>";
            $result .= '</table>';
            if (!class_exists('Logger')) { include("includes/Logger.php"); }
            Logger::write(LoggerAction::updateConfig, array("configSection" => $configSection,
                "configKey" => $configKey, "configValueOld" => $configValueOld, "configValueNew" => $configValueNew));
        }

        $message[] = array("messageType" => 'success', "messageText" => $result);

        return $message;
    }

    /**
     * generates table content (tr and td) containing sensordata from shm
     *
     * @return string table content
     */
    static function sensorDataSHM(): string
    {
        $id = Cfg::read('sensordaemon','sensordaemon_id');
        $sensorTypes = explode(',',Cfg::read('sensors','types'));
        $result = '';

        try {
            $shm_id = shmop_open($id, 'a', 0, 0);
            $sensorData = shmop_read($shm_id, 0, 512);
            $sensorData = explode("\00", $sensorData);
            $output = explode(' ',$sensorData[0]);
        }

        catch (Exception $e) { $output = _MESSAGE_FAIL; }

        if (!isset($e)) {
            $result .=  '<tr><td><i class="fa fa-clock w3-margin-right"></i></td><td>'.$output[0].'</td><td class="w3-right">'.$output[1].'</td></tr>';
            unset($output[0], $output[1]);

            foreach (array_values($output) as $key => $value) {
                $sensor = self::sensorIcon($sensorTypes[$key]);
                $result .=  '<tr><td><i class="fa '.$sensor['icon'].' w3-margin-right"></i></td><td>'.$sensor['type'].'</td><td class="w3-right">'.$value.' '.$sensor['append'].'</td></tr>';
            }
        }

        else { $result .=  '<tr><td><i class="fa fa-exclamation-triangle w3-margin-right"></i></td><td></td><td class="w3-right">'.$output.'</td></tr>'; }

        return $result;
    }

    /**
     * generates icon, name of the sensor and value to append
     * @param string $type
     * @return array{icon: string, name: string, type: string, type-js: string, append: string}
     */
    static function sensorIcon(string $type): array
    {
        $sensor = json_decode(file_get_contents("tmpl/" . Cfg::read('web', 'template') . "/sensor-icons.json"), true, flags: JSON_OBJECT_AS_ARRAY);
        if (array_key_exists($type,$sensor)) {
            $return['icon'] = $sensor[$type]['icon'];
            $return['name'] = $sensor[$type]['name'];
            $return['type'] = constant($sensor[$type]['type']);
            $return['type-js'] = ($sensor[$type]['type-js'] != "") ? constant($sensor[$type]['type-js']) : "";
            $return['append'] = (defined($sensor[$type]['append'])) ? constant($sensor[$type]['append']) : $sensor[$type]['append'];
        }
        else { $return = false; }
        return $return;
    }

}