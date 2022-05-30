<?php

/* welcome to growbox. All the magic starts here */

include('includes/Bootstrap.php');

/* Switch decides what page to show based on INPUT_GET->page */
$frontendContent = (isset($_GET['page'])) ? $_GET['page'] : 'main';

/* manifest does not need the user to be logged in, all the other pages do */

$frontendContent = ($frontendContent != "manifest")
    ? (!isset($_SESSION["login"]))
        ? 'login'
        : $frontendContent
    : $frontendContent;

switch ($frontendContent) {
    case "manifest":
        exit(Template::generateManifest());

    case "logout":
        Session::logout();
        $backendMessage[] = array("messageType" => 'success', "messageText" => "LOGOUT_SUCCESS");
        $frontendContent = 'login';
        break;

    case "reboot":
        exit(exec(Bootstrap::ROOTPATH."/scripts/wrapper/reboot_suid_wrapper &"));

    case "shutdown":
        exit(exec(Bootstrap::ROOTPATH."/scripts/wrapper/shutdown_suid_wrapper &"));

    case "":
    case "main":
        include("includes/Sensors.php");
        include("includes/Project.php");
        $frontendContent = 'main';
        break;

    case "charts":
        include("includes/Chart.php");
        break;

    case "image":
        $camera = "/usr/bin/".Cfg::read('camera','camera');
        $cameraOptions = "-t 1 -n --width ".Cfg::read('camera','frontend_width')." --height ".Cfg::read('camera','frontend_height')." --awb ".Cfg::read('camera','awb')." -q ".Cfg::read('camera','quality')." --sharpness 5.0 -o - 2>/dev/null";
        header('Content-Type: image/jpeg');
        exit(gzencode(passthru($camera ." ". $cameraOptions), encoding: ZLIB_ENCODING_GZIP));

    case "archive":
        include("includes/Project.php");
        break;

    /* case login checks for $_POST["op"] */
    case "login":
        if (!empty($_POST["op"])) {
            if (htmlspecialchars($_POST['op']) == "login") {
                if (Session::login(htmlspecialchars($_POST['user']), htmlspecialchars($_POST['pass']))) {
                    $backendMessage[] = array("messageType" => 'success', "messageText" => "LOGIN_SUCCESS");
                    $frontendContent = 'main';
                    include("includes/Sensors.php");
                    include("includes/Project.php");
                }
                else { $backendMessage[] = array("messageType" => 'error', "messageText" => "LOGIN_FAIL"); }
            }
        }
        else { $frontendContent = (isset($_SESSION["login"])) ? 'main' : 'login'; }
        break;

    /* case control checks for $_POST["op"] */
    case "control":
        $GLOBALS["grow"] = explode(",",Cfg::read('light','rgbw_grow'));
        $GLOBALS["bloom"] = explode(",",Cfg::read('light','rgbw_bloom'));

        if (!empty($_POST["op"])) {
            $stopped = false;
            switch (htmlspecialchars($_POST['op'])) {
                case "stop":
                    exec(Bootstrap::ROOTPATH . "/scripts/wrapper/light_stop_suid_wrapper");
                    $message = _MESSAGE_SUCCESSFUL;
                    $_SESSION['defaultRed'] = 0;
                    $_SESSION['defaultGreen'] = 0;
                    $_SESSION['defaultBlue'] = 0;
                    $_SESSION['defaultWhite'] = 0;
                    session_write_close();
                    $stopped = true;
                    break;

                case "color":
                    $brightness = Template::hex2rgb(htmlspecialchars($_POST["color"]));
                    $brightness["white"] = 0;
                    break;

                case "control":
                    $brightness = array(
                        "red" => htmlspecialchars($_POST["red"]),
                        "green" => htmlspecialchars($_POST["green"]),
                        "blue" => htmlspecialchars($_POST["blue"]),
                        "white" => htmlspecialchars($_POST["white"])
                    );
                    break;

                case "gpio_toggle":
                    include("includes/Logger.php");
                    $logMessage = array();
                    $pin = match (htmlspecialchars($_POST["element"])) {
                        "air" => Cfg::read('air', 'pin'),
                        "water" => Cfg::read('water', 'pin')
                    };
                    if (array_key_exists('stop', $_POST)) {
                        exec(Bootstrap::ROOTPATH . "/backend/gpio_toggle.py $pin 0");
                        $logMessage['text'] = $_POST['element']." => off";
                        Logger::write(LoggerAction::relayToggle,$logMessage);

                    } elseif (array_key_exists('start', $_POST)) {
                        exec(Bootstrap::ROOTPATH . "/backend/gpio_toggle.py $pin 1");
                        $logMessage['text'] = $_POST['element']." => on";
                        Logger::write(LoggerAction::relayToggle,$logMessage);
                    }
                    $message = _MESSAGE_SUCCESSFUL;
                    $stopped = true;
                    break;
            }

            if (!$stopped) {
                exec(Bootstrap::ROOTPATH."/scripts/wrapper/light_control_suid_wrapper " . $brightness["red"] . " " . $brightness["green"] . " " . $brightness["blue"] . " " . $brightness["white"]);
                $message = "<table><tr><td>" . _LIGHT_BRIGHTNESS_R . ":</td><td>" . $brightness["red"] . "</td></tr><tr><td>" . _LIGHT_BRIGHTNESS_G . ":</td><td>" . $brightness["green"] . "</td></tr><tr><td>" . _LIGHT_BRIGHTNESS_B . ":</td><td>" . $brightness["blue"] . "</td></tr><tr><td>" . _LIGHT_BRIGHTNESS_W . ":</td><td>" . $brightness["white"] . "</td></tr></table>";

                $_SESSION['defaultRed'] = $brightness['red'];
                $_SESSION['defaultGreen'] = $brightness['green'];
                $_SESSION['defaultBlue'] = $brightness['blue'];
                $_SESSION['defaultWhite'] = $brightness['white'];
                $_SESSION['color'] = sprintf("#%02x%02x%02x", $_SESSION['defaultRed'], $_SESSION['defaultGreen'], $_SESSION['defaultBlue']);
                session_write_close();
            }

            $backendMessage[] = array("messageType" => 'success', "messageText" => $message);
        }

        else {
            $json = json_decode(file_get_contents(Bootstrap::TMPPATH."/sun.run"),true, flags: JSON_OBJECT_AS_ARRAY);
            $rgbw = explode(',', $json['rgbw']);

            $_SESSION['defaultRed'] = $rgbw[0];
            $_SESSION['defaultGreen'] = $rgbw[1];
            $_SESSION['defaultBlue'] = $rgbw[2];
            $_SESSION['defaultWhite'] = $rgbw[3];

            $_SESSION['color'] = ($rgbw[0] == "0" && $rgbw[1] == "0" && $rgbw[2] == "0" && $rgbw[3] == "0")
                ? "#000000"
                : sprintf("#%02x%02x%02x", $_SESSION['defaultRed'], $_SESSION['defaultGreen'], $_SESSION['defaultBlue']);
            session_write_close();
        }
        break;

    /* case management checks for $_POST["op"] */
    case "management":
        if (!empty($_POST["op"])) {
            include("includes/Project.php");
            switch (htmlspecialchars($_POST['op'])) {
                case "newProject":
                    $backendMessage = Project::newProject(htmlspecialchars($_POST['plant']['species']),htmlspecialchars($_POST['plant']['phase']),htmlspecialchars($_POST['light']['day']),htmlspecialchars($_POST['light']['night']), htmlspecialchars($_POST['project']['note']));
                    break;

                case "endProject":
                    $projectNote = htmlspecialchars($_POST['projectNote']);
                    $projectSuccess = filter_input(INPUT_POST, 'projectSuccess', FILTER_SANITIZE_NUMBER_INT);
                    try { $endProject = Project::endProject($projectSuccess, $projectNote); }
                    catch (Exception $e) { $backendMessage[] = array("messageType" => 'error', "messageText" => $e->getMessage()); }
                    if (!isset($e)) { $backendMessage[] = array("messageType" => 'success', "messageText" => "PROJECT_ENDMESSAGE"); $backendMessage[] = $endProject; }
                    break;
            }
        }
        break;

    /* case settings checks for $_POST["op"] */
    case "settings":
        include("includes/Sensors.php");
        include("includes/Helper.php");

        if (!empty($_POST["op"])) {
            $result = "";
            $backendOperation = htmlspecialchars($_POST['op']);
            if ($backendOperation == "updateConfig") {
                unset($_POST["op"]); // not needed anymore. drop it, or it will cause an error with Cfg::write

                if (!empty($_POST["origin"])) {
                    $destination = htmlspecialchars($_POST["origin"]);
                    unset($_POST["origin"]); // not needed anymore. drop it, or it will cause an error with Cfg::write
                }

                foreach ($_POST as $array => $arrayName) {
                    foreach ($arrayName as $configKey => $configValue) {
                        $configSection = $array;
                        try { $backendConfigResponse = Cfg::write($configSection, $configKey, htmlspecialchars($configValue),true);}
                        catch (Exception $e) { $backendMessage[] = array("messageType" => 'error', "messageText" => $e->getMessage()); }
                        if (!isset($e)) { $result .= $backendConfigResponse; }
                    }
                }

                $backendMessage[] = ($result != "") ? array("messageType" => 'success', "messageText" => $result) : array("messageType" => 'info', "messageText" => _MESSAGE_NOCHANGE);
            }

            elseif (htmlspecialchars($_POST["op"] == "changePassword")) {
                $backendMessage[] = Session::passwd(htmlspecialchars($_POST["pass"]));
            }
            elseif ($backendOperation == "sensorActivator") {
                $backendMessage = Sensors::sensorActive(Helper::htmlspecialchars_array($_POST['sensor']));
            }
            elseif ($backendOperation == "sensorOrder") {
                $backendMessage = Sensors::sensorChooser(Helper::htmlspecialchars_array($_POST['position']));
            }
            elseif ($backendOperation == "updateSensorCfg") {
                $backendMessage = Sensors::updateCfg(Helper::htmlspecialchars_array($_POST['cfg']));
            }

        }

        $GLOBALS['grow'] = explode(",",Cfg::read('light','rgbw_grow'));
        $GLOBALS['bloom'] = explode(",",Cfg::read('light','rgbw_bloom'));
        $GLOBALS['settingsBox'] = explode("/",$_SERVER["REQUEST_URI"])[2];

        $frontendContent = (isset($destination)) ? $destination : "settings";
        break;

}

Template::show($frontendContent, (isset($backendMessage)) ? $backendMessage : NULL);
