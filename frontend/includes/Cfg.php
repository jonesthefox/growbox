<?php

/**
 * The Cfg class:
 * reads config
 * writes config
 * (checks if key has changed)
 * -yes-> change in config.ini, write to the log if switch is set
 * --no-> don't change (obviously), return a nothing-changed message if switch is set
 */
class Cfg
{
    /**
     * @var string the configfile, relative to webroot (/http)
     */
    static string $configFile = Bootstrap::ROOTPATH."/config.ini";

    /**
     * reads a value from config.ini
     * @param $configSection string the section
     * @param $configKey string the key
     * @return string|bool returns string or bool
     */
    public static function read(string $configSection, string $configKey): string|bool
    {
        try { $config = parse_ini_file(self::$configFile, TRUE, INI_SCANNER_TYPED); }
        catch (Exception $e) { echo $e->getMessage(); }

        if (!is_bool($config)) {
            return (array_key_exists($configSection,$config) && array_key_exists($configKey,$config[$configSection])) ? $config[$configSection][$configKey] : false;
        }
        else { die('error reading config.ini'); }

    }

    /**
     * Updates the config.ini file
     *
     * @param string $configSection the section
     * @param string $configKey the key
     * @param string $configValueNew the new value
     * @param bool $logWrite writes to log when true
     * @param bool|null $noChangeMessage returns a message even if nothing changed.
     * @return string|false containing successful or unchanged message, false if $noChangeMessage is not set.
     * @noinspection PhpAutovivificationOnFalseValuesInspection
     */
    public static function write(string $configSection, string $configKey, string $configValueNew, bool $logWrite = NULL, bool $noChangeMessage = NULL): string|false
    {
        try { $configData = parse_ini_file(self::$configFile, true, INI_SCANNER_RAW); }
        catch (Exception $e) { echo $e->getMessage(); }

        $configDataNew = "";

        if (!is_bool($configData)) {
            if (!array_key_exists($configSection, $configData)) { $configData[$configSection][$configKey] = ''; }
            if ($configData[$configSection][$configKey] != $configValueNew) {
                $configValueOld = $configData[$configSection][$configKey];
                $configData[$configSection][$configKey] = $configValueNew;
                foreach ($configData as $section => $section_content) {
                    $section_content = array_map(function ($value, $key) { return "$key=$value"; }, array_values($section_content), array_keys($section_content));
                    $section_content2 = implode("\n", $section_content);
                    $configDataNew .= "[$section]\n$section_content2\n";
                }

                if (!file_put_contents(self::$configFile, $configDataNew))
                { die(Bootstrap::error('Cfg: can not write config.ini. please check file permissions or run scripts/setup/file_permissions.sh<br>')); }

                else {
                    if ($logWrite) { if (!class_exists('Logger')) { include("includes/Logger.php"); }
                        Logger::write(LoggerAction::updateConfig, array("configSection" => $configSection, "configKey" => $configKey, "configValueOld" => $configValueOld, "configValueNew" => $configValueNew));
                    }
                    return "[$configSection] =&gt; $configKey = $configValueNew<br>";
                }
            }
            else { return $noChangeMessage ? "[$configSection] =&gt; $configKey<br>" : false; }
        }
        else { die('Cfg: error reading config.ini'); }
    }

}
