<?php

class Chart
{

    /**
     * Generates the sensor chart
     * PHP datetime class and functions are made by satan himself to drive developers insane!!!!!
     * it seems, that every the DateTime class and date() automatically append your local timezone.
     *
     * so it may be worth to explain how the timestamp is handled:
     * the sensordaemon writes an unix timestamp (in UTC notabene) into the db sensordata table (int(timestamp), string(sensordata))
     * gen_sensor_chart() takes that timestamp and creates a datetime string (YY-mm-dd H:i:s+timezoneoffset) timezone offset seems
     * always to be your local timezone, and it seems to ignore a set timezone. so we cut off the +timezoneoffset (02:00 in my timezone)
     * and replace it with the correct +00:00 that "declares" this timestamp as UTC.
     *
     * then we use the DateTime php class with the manipulated string as datetime and declare that as DateTimeZone object in UTC.
     * next, we use ->setTimezone with a DateTimeZone object containing the local timezone (with date_default_timezone_get()) and lookitthat,
     * utc unix timestamp -> to local datetime in 3 lines - last line is setting the desired format of the local timestamp.
     *
     * @param string $count how many datapoints (cron generates one value of every sensor every ten minutes, 6 values per hour)
     * @param string|null $offset the offset for pagination
     * @param bool $showdate shows the date if true, shows time only when false
     * @return array{timestamp: string, temp: float, rh: float, moist: float, cputemp: float, co2: int, prev: int, next: int}
     */
    static function sensorChartData(string $database, string $count, string $offset = NULL, bool $showdate = NULL): array
    {
        $db = new SQLite3($database);
        $sensorNames = explode(',',Cfg::read('sensors','types'));
        $sensorCount = count($sensorNames) -1;
        $dataArray = array();
        $dataArray["timestamp"] = "";

        for ($i = 0; $i <= $sensorCount; $i++) { $dataArray[$sensorNames[$i]] = ""; }

        $whereClause = ($offset != 0) ? "WHERE timestamp < $offset" : "";
        $resource = $db->query("SELECT * FROM (SELECT timestamp, data FROM sensordata $whereClause ORDER BY timestamp DESC LIMIT $count) ORDER BY timestamp");
        while ($array = $resource->fetchArray())
        {
            $format = ($showdate == 0) ? "H:i" : "Y-m-d H:i";

            try { $timestamp = new DateTime(substr(date("c", $array[0]), 0, -6) . "+00:00", new DateTimeZone("UTC")); }
            catch (Exception $e) { Bootstrap::error($e); }

            $timestamp->setTimezone(new DateTimeZone(date_default_timezone_get()));
            $dataArray["timestamp"] .= "'".$timestamp->format($format)."', ";
            $data = json_decode(gzuncompress($array[1]), true);
            for ($i = 0; $i <= $sensorCount; $i++) {
                if (array_key_exists($i, $data))
                { $dataArray[$sensorNames[$i]] .= "$data[$i], "; }
                else { $dataArray[$sensorNames[$i]] .= "0, "; }
            }
        }

        $dataArray = array_map(fn($item) => substr($item,0,-2),$dataArray);
        $dataArray["prev"] = strtotime(substr($dataArray["timestamp"],1,16));

        return $dataArray;
    }

    static function sensorChart(string $offset = null): string
    {
        include("Sensors.php");
        $constructor = '';
        $constructorJS = '<script>';

        $sensorTypes = explode(',',Cfg::read('sensors','types'));
        $color = Template::sensorColors();
        $chartBox = file_get_contents(Bootstrap::TEMPLATEDIR."/box-constructs/chart-box.php");
        $chartPagination = file_get_contents(Bootstrap::TEMPLATEDIR."/box-constructs/chart-pagination-box.php");
        $chartHelper = file_get_contents(Bootstrap::TEMPLATEDIR."/js/chart-helper.js");

        $data = self::sensorChartData(Bootstrap::DBDIR,Cfg::read('web','chartcount'),$offset, true);
        $timestamp = $data['timestamp'];
        unset($data['timestamp']);

        $constructor .= str_replace('##prev##', $data['prev'], $chartPagination);
        $constructor = str_replace('##next##', (is_null($offset)) ? 'style="display:none"' : '', $constructor);
        $constructor = str_replace('##FORM_BACK##', _FORM_BACK, $constructor);
        $constructor = str_replace('##FORM_NEXT##', _FORM_NEXT, $constructor);

        unset($data['prev']);

        foreach ($sensorTypes as $key) {
            $icon = Sensors::sensorIcon($key);

            $constructor .= str_replace('##icon##',$icon['icon'],$chartBox);
            $constructor = str_replace('##sensortype##',$icon['type'],$constructor);
            $constructor = str_replace('##sensor##',$key,$constructor);

            $constructorJS .= str_replace('_handle_',$icon['name'].'Handle',$chartHelper);
            $constructorJS = str_replace('_sensor_',$key,$constructorJS);
            $constructorJS = str_replace('_timestampdata_',$timestamp,$constructorJS);
            $constructorJS = str_replace('_label_',($icon['type-js'] != '') ? $icon['type-js'] : $icon['type'],$constructorJS);
            $constructorJS = str_replace('_sensordata_',$data[$key],$constructorJS);
            $constructorJS = str_replace('_sensorcolor_',$color[$key],$constructorJS);
            $constructorJS = str_replace('_appendlabel_',$icon['append'],$constructorJS);
        }
        $constructorJS .= '</script>';
        return $constructor . $constructorJS;
    }

}
