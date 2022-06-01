<?php


/** @noinspection HtmlUnknownAttribute */

/**
 * The Template class:
 * generates and displays frontend templates
 * generates message-boxes from frontend messages
 * generates a json manifest
 * generates html options for templates and languages defined by theme.json and language.json in the corresponding directories
 * generates the Documentation page, from the .md files in docs
 */
class Template
{
    /**
     * renders a frontend page displayed in the users webbrowser.
     * - it sets $projectRunning and $hidden to offer control over the content when a project is running or not.
     * - it reads the template set in config.ini
     *
     * there are some values catched from $frontendContent where some logic happens for certain contents.
     * probably i will move that logic into the application controller (index.php) to reduce complexity of this function..
     *
     * @param $frontendContent string|null (main / archive / control / settings / charts / sensordata / manifest are catched for further logic)
     * @param array|null $backendMessage messageType (info / success / error / nochange / warning), messageText
     * @return never displays (includes) the template, assembled based on the content setting and messages if needed, then exit
     */
    static function show(string $frontendContent = NULL, array $backendMessage = NULL): never
    {
        file_exists(Bootstrap::TEMPLATEDIR."/pages/$frontendContent-tmpl.php") ? null : die(Bootstrap::error(__CLASS__ .": file '$frontendContent-tmpl.php' not found"));

        (isset($backendMessage)) ? $messageBox = self::message($backendMessage) : 0;

        require(Bootstrap::TEMPLATEDIR."/header-tmpl.php");
        require(Bootstrap::TEMPLATEDIR."/pages/$frontendContent-tmpl.php");
        require(Bootstrap::TEMPLATEDIR."/footer-tmpl.php");
        session_write_close();
        exit(0);
    }
    
    /**
     * Assembles info Messages from the message-box.php template.
     * displays infos for operations, i.e. info, error or success of an operation, or a warning after a operation
     * that can be included at the right place of the template.
     *
     * @param $backendMessage array messageType (info, error, success, nochange, warning), messageText
     * @return string returns string of assembled message box(es)
     */
    static function message(array $backendMessage): string
    {
        $backendMessageBox = "";
        $backendMessageTemplate = file_get_contents(Bootstrap::TEMPLATEDIR."/box-constructs/message-box.php");
        $i = 300;
        $o = 3000;

        foreach ($backendMessage as $item)
        {
            $backendMessageType = $item["messageType"];

            $backendMessageText = match ($item["messageText"]) {
                "OPERATION_SUCCESSFUL" => _MESSAGE_CONFIGCHANGED,
                "ERROR_UNDEFINED" => _MESSAGE_ERROR_UNDEFINED,
                "PROJECT_ENDMESSAGE" => _PROJECT_BACKEND_ENDMESSAGE,
                "LOGIN_FAIL" => _LOGIN_WRONG_CREDENTIALS,
                "LOGIN_SUCCESS" => _LOGIN_SUCCESSFUL,
                "LOGOUT_SUCCESS" => _LOGIN_LOGOUT_SUCCESSFUL,
                default => $item["messageText"],
            };

            switch ($backendMessageType) {
                case "info":
                    $backendMessageType = _MESSAGE_INFO;
                    $backendMessageIcon = "fa-info-circle";
                    $backendMessageColor = "w3-pale-blue";
                    $backendMessageSidebarColor = "w3-border-blue";
                    $backendMessagePin = false;
                    break;

                case "error":
                    $backendMessageType = _MESSAGE_ERROR;
                    $backendMessageIcon = "fa-exclamation-circle";
                    $backendMessageColor = "w3-pale-red";
                    $backendMessageSidebarColor = "w3-border-red";
                    $backendMessagePin = true;
                    break;

                case "success":
                    $backendMessageType = _MESSAGE_SUCCESS;
                    $backendMessageIcon = "fa-check-circle";
                    $backendMessageColor = "w3-pale-green";
                    $backendMessageSidebarColor = "w3-border-green";
                    $backendMessagePin = false;
                    break;

                case "nochange":
                    $backendMessageType = _MESSAGE_NOCHANGE;
                    $backendMessageIcon = "fa-info-circle";
                    $backendMessageColor = "w3-pale-blue";
                    $backendMessageSidebarColor = "w3-border-blue";
                    $backendMessagePin = false;
                    break;

                case "warning":
                    $backendMessageType = _MESSAGE_WARNING;
                    $backendMessageIcon = "fa-exclamation-circle";
                    $backendMessageColor = "w3-pale-yellow";
                    $backendMessageSidebarColor = "w3-border-yellow";
                    $backendMessagePin = true;
                    break;

                default:
                $backendMessageType = _MESSAGE_ERROR_UNDEFINED;
                $backendMessageIcon = "fa-exclamation-circle";
                $backendMessageColor = "w3-pale-red";
                $backendMessageSidebarColor = "w3-border-red";
                $backendMessagePin = true;
                break;
            }

            if ($backendMessagePin === true) {
                $tmp = $o;
                $o = 2147483647;
            }

            $search = array('##messageColor##', '##sidebarColor##', '##messageIcon##', '##messageType##', '##messageText##', '_identity_', '_timeout_', '_timeoutfadeout_');
            $replace = array($backendMessageColor, $backendMessageSidebarColor, $backendMessageIcon, $backendMessageType, $backendMessageText, 'id_'.$i, $i, $o);
            $backendMessageBoxConstructor = str_replace($search,$replace, $backendMessageTemplate);

            if ($backendMessagePin === true) { $o = $tmp; }

        $backendMessageBox .= $backendMessageBoxConstructor;
        $i = $i +200;
        $o = $o +500;
        }

    return $backendMessageBox;
    }

    /**
     * returns JSON encoded webmanifest from the Array in manifest-tmpl.php
     * @return string
     */
    static function generateManifest(): string
    {
        $json = "";
        include_once(Bootstrap::TEMPLATEDIR."/manifest-tmpl.php");
        header('Content-Type: application/json; charset=utf-8');
        return json_encode($json, JSON_UNESCAPED_SLASHES);
    }

    /**
     * generates html options for template select in settings
     * reads theme.json in /tmpl/*
     *
     * @return string html options
     */
    static function listTemplates(): string
    {
        $string = "";
        $tmpl = glob('tmpl/*/theme.json', GLOB_BRACE);
        foreach ($tmpl as $item) {
            $json = json_decode(file_get_contents($item), true);
            $string .= "<option value=\"{$json['theme']['dir']}\" ". ($json['theme']['dir'] == Cfg::read('web','template') ? "selected" : "") .">{$json['theme']['name']} {$json['theme']['version']}</option>\n";
        }
        return $string;
    }

    /**
     * generates html options for language select in settings
     * reads lang.json in /lang/*
     *
     * @return string html options
     */
    static function listLanguages(): string
    {
        $string = "";
        $json = json_decode(file_get_contents(Cfg::read('default','rootpath')."/lang/languages.json"),true);
        foreach ($json as $lang) {
            $string .= "<option value=\"{$lang['id']}\" ". ($lang['id'] == _LANGUAGE_CODE_SHORT ? "selected" : "") .">{$lang['name']}</option>\n";
        }
        return $string;
    }

    /**
     * generates documentation, returns parsed .md files from /docs and little navigation
     *
     * @return string navigation and parsed text
     */
    static function generateDoc(): string
    {
        include('includes/3rdparty/Parsedown.php');
        $documentationContent= "";
        $Parsedown = new Parsedown();

        $documentationDocpageTemplate = file_get_contents(Bootstrap::TEMPLATEDIR."/box-constructs/doc-page-tmpl.php");
        $documentationNavigationTemplate = file(Bootstrap::TEMPLATEDIR."/box-constructs/doc-page-nav-tmpl.php");
        $documentationFiles = glob(Bootstrap::ROOTPATH . '/docs/*.md', GLOB_BRACE);

        $documentationNavigation = $documentationNavigationTemplate[0];

        foreach ($documentationFiles as $documentationPageSource) {
            $documentationPageContent = file_get_contents($documentationPageSource);
            $documentationPageContentFilename = basename($documentationPageSource, ".md");

            $search = array('##fileName##', '##parsedText##', 'assets/');
            $replace = array($documentationPageContentFilename, $Parsedown->text($documentationPageContent), '../assets/');

            $documentationPageString = str_replace($search,$replace, $documentationDocpageTemplate);
            $documentationNavigation .= str_replace("##fileName##", $documentationPageContentFilename, $documentationNavigationTemplate[1]);

            $documentationContent.= $documentationPageString;
        }

        $documentationNavigation .= "$documentationNavigationTemplate[2]<br>";

        return $documentationNavigation . $documentationContent;
    }

    /**
     * generates a nice table with log entries
     *
     * @param LogFile $log 'project' or 'cron'
     * @param int $lines the number of lines to be shown
     * @return string
     */
    static function generateLog(LogFile $log, int $lines=100): string
    {
        $logTableTemplate = file_get_contents(Bootstrap::TEMPLATEDIR . "/box-constructs/log-table.php");
        $logTable = '';

        $logfile = file($log->path());

        if ($logfile) {
            $logfile = array_reverse($logfile,true);
            $logarray = array_slice($logfile, 0,$lines);

            if ($log == LogFile::Project) {
                foreach ($logarray as $value) {
                    $timestamp = explode(" ", $value);
                    $logstring = substr($value, 20);
                    $logstring = explode("(", $logstring);

                    $idicon = match ($logstring[0]) {
                        "CHANGED" => "fa-gear",
                        "DAEMON" => "fa-ghost",
                        "NEW" => "fa-folder-tree",
                        "LOGON" => "fa-user",
                        "TOGGLE" => "fa-toggle-off",
                        "ERROR" => "fa-circle-exclamation",
                        default => "fa-circle-question"
                    };

                    $logstring = implode("(", $logstring);
                    preg_match('/^[A-Z]+\((.*)\)$/', $logstring, $match);

                    $search = array('##date##', '##time##', '##idicon##', '##message##');
                    $replace = array($timestamp[0], $timestamp[1], $idicon, $match[1]);

                    $logConstruct = $logTableTemplate;
                    $logTable .= str_replace($search, $replace, $logConstruct);
                }
            }

                else {
                    foreach ($logarray as $value) {
                        $logTable .= "<tr><td>$value</td></tr>";
                    }
                }
            }

        else { $logTable = "<tr><td>"._LOGGING_EMPTY."</td></tr>"; }

        return $logTable;
    }

    /**
     * returns an array with sensornames as key (i.e. "temp") and a color ( 255,0,0) as value
     *
     * @return array{temp: string, rh: string, moist: string, cputemp: string, co2: string}
     */
    static function sensorColors(): array
    {
        $sensorColor = explode('-',Cfg::read('web','sensorcolor'));
        $sensorNames = explode(',',Cfg::read('sensors','types'));
        $sensorCount = count($sensorNames) -1;
        $sensorColorArray = array();

        for ($i = 0; $i <= $sensorCount; $i++) { $sensorColorArray[$sensorNames[$i]] = $sensorColor[$i]; }
        return $sensorColorArray;
    }

    /**
     * converts hex color value to rgb value
     * @param string $color
     * @return array
     */
    static function hex2rgb(string $color): array
    {
        if ( $color[0] == '#' ) {
            $color = substr( $color, 1 );
        }
        if ( strlen( $color ) == 6 ) {
            list( $r, $g, $b ) = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
        } elseif ( strlen( $color ) == 3 ) {
            list( $r, $g, $b ) = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
        }
        $r = hexdec( $r );
        $g = hexdec( $g );
        $b = hexdec( $b );
        return array( 'red' => $r, 'green' => $g, 'blue' => $b );
    }

    /**
     * displays certain boxes on the dashboard, like the image box, the project box, the sensors..
     * @param Box $box the box to display
     * @return ?string
     */
    static function showBox(Box $box): ?string
    {
        switch ($box) {
            case Box::startnewproject:
                (!Bootstrap::PROJECTACTIVE) ? include(Bootstrap::TEMPLATEDIR."/boxes/main-startnewproject.php") : null;
                break;

            case Box::projectbrief:
                (Bootstrap::PROJECTACTIVE) ? include(Bootstrap::TEMPLATEDIR."/boxes/main-projectbrief.php") : null;
                break;

            case Box::image:
                (Bootstrap::PROJECTACTIVE)
                    ? (Project::dayTime() != _PLANT_NIGHT)
                        ? include(Bootstrap::TEMPLATEDIR."/boxes/main-camera.php")
                        : include(Bootstrap::TEMPLATEDIR."/boxes/main-image.php")
                    : include(Bootstrap::TEMPLATEDIR."/boxes/main-camera.php");
                break;

            case Box::sensors:
                include(Bootstrap::TEMPLATEDIR."/boxes/main-sensors.php");
                break;

            case Box::spectrum:
                include(Bootstrap::TEMPLATEDIR."/boxes/control-spectrum.php");
                break;
        }
        return null;
    }

    /**
     * tells if relay is started or stopped
     * @param string $relay 'air' | 'water'
     * @return string 'stopped' | 'started'
     */
    static function relayStatus(string $relay): string
    {
        return (file_get_contents(Bootstrap::TMPPATH."/$relay.run") == "0") ? 'red' : 'green';
    }

    /**
     * tells if light is on or off
     * @return string 'stopped' | 'started'
     */
    static function lightStatus(): string
    {
        $json = json_decode(file_get_contents(Bootstrap::TMPPATH."/sun.run"), true, flags: JSON_OBJECT_AS_ARRAY);

        return ($json['rgbw'] != "0,0,0,0") ? (file_get_contents(Bootstrap::TMPPATH."/temperature.run") === '1') ? 'blue' : 'green' : 'red';
    }
}
