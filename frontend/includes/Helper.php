<?php

class Helper
{

    /**
     * this is a little helper function to sanitize strings or whole arrays with htmlspecialchars
     *
     * @param array|string $array an array or string containing potentially bad characters that break your script
     * @return array|string the sanitized array or string
     */
    static function htmlspecialchars_array(array|string $array): array|string
    {
        // return array_map(fn($a) => htmlspecialchars($a), $array);
        return (is_array($array)) ? array_map('self::htmlspecialchars_array',$array) : htmlspecialchars(($array));
    }
}