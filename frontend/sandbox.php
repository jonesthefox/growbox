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

        //$result = array()


        echo "<pre>";
        $i = 0;
        $c = 1;
        //echo "\$spectrum_r = array(\n";
        for ($n = 380; $n <= 820; $n = $n + 5) {
            if ($i % 2 != 0) {
                //echo "\"'" . $n . "nm'\" => 0,\n";
                $c++;
            }
            $i++;
        }

        echo "count: $c\n";

        $spectrum_r = array(
            "'415nm'" => 0,
            "'425nm'" => 0,
            "'435nm'" => 0,
            "'445nm'" => 0,
            "'455nm'" => 0,
            "'465nm'" => 0,
            "'475nm'" => 0,
            "'485nm'" => 0,
            "'495nm'" => 0,
            "'505nm'" => 0,
            "'515nm'" => 0,
            "'525nm'" => 0,
            "'535nm'" => 0,
            "'545nm'" => 0,
            "'555nm'" => 0,
            "'565nm'" => 0,
            "'575nm'" => 0,
            "'585nm'" => 0,
            "'595nm'" => 0,
            "'605nm'" => 0,
            "'615nm'" => 0,
            "'625nm'" => 100,
            "'635nm'" => 0,
            "'645nm'" => 0,
            "'655nm'" => 0,
            "'665nm'" => 0,
            "'675nm'" => 0,
            "'685nm'" => 0,
            "'695nm'" => 0,
            "'705nm'" => 0,
            "'715nm'" => 0,
            "'725nm'" => 0,
            "'735nm'" => 0,
            "'745nm'" => 0,
            "'755nm'" => 0,
            "'765nm'" => 0,
            "'775nm'" => 0,
            "'785nm'" => 0,
            "'795nm'" => 0,
            "'805nm'" => 0);
        $spectrum_g = array(
            "'415nm'" => 0,
            "'425nm'" => 0,
            "'435nm'" => 0,
            "'445nm'" => 0,
            "'455nm'" => 0,
            "'465nm'" => 0,
            "'475nm'" => 0,
            "'485nm'" => 0,
            "'495nm'" => 0,
            "'505nm'" => 0,
            "'515nm'" => 100,
            "'525nm'" => 100,
            "'535nm'" => 0,
            "'545nm'" => 0,
            "'555nm'" => 0,
            "'565nm'" => 0,
            "'575nm'" => 0,
            "'585nm'" => 0,
            "'595nm'" => 0,
            "'605nm'" => 0,
            "'615nm'" => 0,
            "'625nm'" => 0,
            "'635nm'" => 0,
            "'645nm'" => 0,
            "'655nm'" => 0,
            "'665nm'" => 0,
            "'675nm'" => 0,
            "'685nm'" => 0,
            "'695nm'" => 0,
            "'705nm'" => 0,
            "'715nm'" => 0,
            "'725nm'" => 0,
            "'735nm'" => 0,
            "'745nm'" => 0,
            "'755nm'" => 0,
            "'765nm'" => 0,
            "'775nm'" => 0,
            "'785nm'" => 0,
            "'795nm'" => 0,
            "'805nm'" => 0);
        $spectrum_b = array(
            "'415nm'" => 0,
            "'425nm'" => 0,
            "'435nm'" => 0,
            "'445nm'" => 0,
            "'455nm'" => 0,
            "'465nm'" => 100,
            "'475nm'" => 0,
            "'485nm'" => 0,
            "'495nm'" => 0,
            "'505nm'" => 0,
            "'515nm'" => 0,
            "'525nm'" => 0,
            "'535nm'" => 0,
            "'545nm'" => 0,
            "'555nm'" => 0,
            "'565nm'" => 0,
            "'575nm'" => 0,
            "'585nm'" => 0,
            "'595nm'" => 0,
            "'605nm'" => 0,
            "'615nm'" => 0,
            "'625nm'" => 0,
            "'635nm'" => 0,
            "'645nm'" => 0,
            "'655nm'" => 0,
            "'665nm'" => 0,
            "'675nm'" => 0,
            "'685nm'" => 0,
            "'695nm'" => 0,
            "'705nm'" => 0,
            "'715nm'" => 0,
            "'725nm'" => 0,
            "'735nm'" => 0,
            "'745nm'" => 0,
            "'755nm'" => 0,
            "'765nm'" => 0,
            "'775nm'" => 0,
            "'785nm'" => 0,
            "'795nm'" => 0,
            "'805nm'" => 0);
        $spectrum_w = array(
            "'415nm'" => 0,
            "'425nm'" => 5,
            "'435nm'" => 25,
            "'445nm'" => 40,
            "'455nm'" => 62,
            "'465nm'" => 52,
            "'475nm'" => 30,
            "'485nm'" => 18,
            "'495nm'" => 13,
            "'505nm'" => 11,
            "'515nm'" => 12,
            "'525nm'" => 20,
            "'535nm'" => 30,
            "'545nm'" => 35,
            "'555nm'" => 45,
            "'565nm'" => 53,
            "'575nm'" => 59,
            "'585nm'" => 67,
            "'595nm'" => 70,
            "'605nm'" => 72,
            "'615nm'" => 71,
            "'625nm'" => 68,
            "'635nm'" => 65,
            "'645nm'" => 60,
            "'655nm'" => 42,
            "'665nm'" => 30,
            "'675nm'" => 25,
            "'685nm'" => 18,
            "'695nm'" => 15,
            "'705nm'" => 10,
            "'715nm'" => 8,
            "'725nm'" => 7,
            "'735nm'" => 5,
            "'745nm'" => 3,
            "'755nm'" => 2,
            "'765nm'" => 0,
            "'775nm'" => 0,
            "'785nm'" => 0,
            "'795nm'" => 0,
            "'805nm'" => 0);


        //var_dump($result);

        echo implode(', ', array_keys($spectrum_r))."\n";
        echo implode(', ', $spectrum_r)."\n";
        echo implode(', ', $spectrum_g)."\n";
        echo implode(', ', $spectrum_b)."\n";
        echo implode(', ', $spectrum_w)."\n";

        echo "</pre>";


        /** show the template */
        //Template::show('sandbox',(isset($backendMessage)) ? $backendMessage : NULL);
    }

