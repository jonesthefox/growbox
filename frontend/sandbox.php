<?php

    /** welcome to the growbox sandbox. it is intended to test stuff within the templates */

    include('includes/Bootstrap.php');

    /** enable error reporting */
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    if (!isset($_SESSION["login"])) {
        Template::show('login',(isset($backendMessage)) ? $backendMessage : NULL);
    }

    else {
        /**
         * Testcode here
         *
         * you can use $backendMessage for results
         *
         * $backendMessage[] = array("messageType" => 'info', "messageText" => 'text');
         * $backendMessage[] = array("messageType" => 'success', "messageText" => 'text');
         * $backendMessage[] = array("messageType" => 'error', "messageText" => 'text in a sticky message');
         * $backendMessage[] = array("messageType" => 'warning', "messageText" => 'text in a sticky message');
         */

        /** show the template */
        Template::show('sandbox',(isset($backendMessage)) ? $backendMessage : NULL);
    }
