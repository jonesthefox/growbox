<?php

class Project
{

    /**
     * grabs old project data, assembles the include for the template, based on - you guess it, a template - yay!
     * @return string returns generated old project boxes
     */
    static function showOldProjects(): string
    {
        $projectTmpl = file_get_contents(Bootstrap::TEMPLATEDIR."/box-constructs/project-box.php");
        $oldProjectsBox = "";

        $oldProject = glob('projects/old/*/', GLOB_BRACE);
        foreach ($oldProject as $project) {
            $db = new SQLITE3($project . Bootstrap::DB);
            $array = json_decode($db->querySingle('SELECT data FROM project'),true);
            $db->close();
            $success = self::success($array['success']);
            $daysactive = self::daysrunning($array['startdate'],$array['enddate']);
            $search = array('##species##', '##icon##', '##color##', '##success##', '##id##', '##startnote##', '##endnote##', '##startdate##', '##enddate##', '##daysactive##', '##daysrunning##', '##imgalt##');
            $replace = array($array['species'], $success['icon'], $success['color'], $success['text'], $array['id'], $array['startnote'], $array['endnote'], $array['startdate'], $array['enddate'], $daysactive, _PROJECT_DAYSRUNNING, _IMG_ALT);

            $oldProjectsBox .= str_replace($search,$replace,$projectTmpl);
        }
        return $oldProjectsBox;
    }

    /**
     * setup and start a new project
     *
     * @param string $projectSpecies the species of the plant
     * @param string $projectPhase the growphase
     * @param string $projectDay daylength in seconds
     * @param string $projectNight nightlength in seconds
     * @param string $projectStartNote the start note
     *
     * @return array generated message(s)
     */
    static function newProject(string $projectSpecies, string $projectPhase, string $projectDay, string $projectNight, string $projectStartNote): array
    {
        $cfgMessageString = "";
        $projectMessageString = "";
        
        $projectID = self::genprojecthash($projectSpecies);
        $projectStartDate = date("Y-m-d");

        /* backup config.ini */
        try { copy(Bootstrap::ROOTPATH."/config.ini",Bootstrap::TMPPATH."/config.bak"); }
        catch (Exception $e) { Bootstrap::error($e->getMessage()); }
        if (!isset($e)) { $projectMessageString .= _PROJECT_BACKEND_BACKUP_CONFIG."<br>"; }

        /* setup config.ini */
        try {
            $cfgResponse = Cfg::write('plant', 'active', 'yes',FALSE);
            $cfgResponse .= Cfg::write('plant', 'project', $projectID,FALSE);
            $cfgResponse .= Cfg::write('plant', 'species', $projectSpecies,FALSE);
            $cfgResponse .= Cfg::write('plant', 'phase', $projectPhase,FALSE);
            $cfgResponse .= Cfg::write('plant', 'startdate', $projectStartDate,FALSE);
            $cfgResponse .= Cfg::write('light', 'day', $projectDay,FALSE);
            $cfgResponse .= Cfg::write('light', 'night', $projectNight,FALSE);
        }
        catch (Exception $e) {
            try { copy(Bootstrap::TMPPATH."/config.bak",Bootstrap::ROOTPATH."/config.ini"); }
            catch (Exception $e) { Bootstrap::error($e->getMessage()); }
            Bootstrap::error($e->getMessage());
        }
        if (!isset($e)) { $cfgMessageString .= $cfgResponse; }

        /* setup project directory */
        try { mkdir(Bootstrap::ROOTPATH."/frontend/projects/$projectID"); }
        catch (Exception $e) { Bootstrap::error($e->getMessage()); }
        if (!isset($e)) { $projectMessageString .= _PROJECT_BACKEND_NEWPROJECT_MKDIR."<br>"; }

        try { copy(Bootstrap::ROOTPATH."/frontend/projects/index.html",Bootstrap::ROOTPATH."/frontend/projects/$projectID/index.html"); }
        catch (Exception $e) { Bootstrap::error($e->getMessage()); }
        if (!isset($e)) { $projectMessageString .= _PROJECT_BACKEND_NEWPROJECT_COPY_INDEX."<br>"; }

        try { copy(Bootstrap::ROOTPATH."/frontend/projects/default.jpg",Bootstrap::ROOTPATH."/frontend/projects/$projectID/latest.jpg"); }
        catch (Exception $e) { Bootstrap::error($e->getMessage()); }
        if (!isset($e)) { $projectMessageString .= _PROJECT_BACKEND_NEWPROJECT_COPY_DEFAULTIMAGE."<br><br>"; }

        /* setup database */
        try { copy(Bootstrap::ROOTPATH . "/db/template.sqlite",Bootstrap::DBDIR); }
        catch (Exception $e) { Bootstrap::error($e->getMessage()); }
        if (!isset($e)) { $projectMessageString .= _PROJECT_BACKEND_NEWPROJECT_COPY_DEFAULTDB."<br><br>"; }

        try {
            $json = json_encode(array(
                'id' => $projectID,
                'startdate' => $projectStartDate,
                'species' => $projectSpecies,
                'day' => $projectDay,
                'dim' => Cfg::read('light','dim'),
                'dim_step' => Cfg::read('light','dim_step'),
                'startnote' => $projectStartNote));

            $db = new SQLITE3(Bootstrap::DBDIR);
            $sqlStatement = $db->prepare('INSERT INTO project (data) VALUES (:json);');
            $sqlStatement->bindParam(':json', $json);
            $sqlStatement->execute();
            $sqlStatement->close();
        }

        catch (Exception $e) { Bootstrap::error($e->getMessage()); }
        if (!isset($e)) {
            $projectMessageString .= _PROJECT_BACKEND_NEWPROJECT_DATABASE."<br><br>";
            $projectMessageString .= _PROJECT_BACKEND_NEWPROJECT_CREATED."<br><br>";
        }
        include("includes/Logger.php");
        Logger::write(LoggerAction::newProject, array("projectID" => $projectID,
                                                            "projectSpecies" => $projectSpecies,
                                                            "projectPhase" => $projectPhase,
                                                            "projectDay" => $projectDay,
                                                            "projectNight" => $projectNight,
                                                            "projectNote" => $projectStartNote));

        /* remove config.ini backup */
        try { unlink(Bootstrap::TMPPATH."/config.bak"); }
        catch (Exception $e) { Bootstrap::error($e->getMessage()); }
        if (!isset($e)) { $projectMessageString .= _PROJECT_BACKEND_BACKUP_CONFIG_REMOVED."<br>"; }

        ($projectMessageString != "") ? $message[] = array("messageType" => 'success', "messageText" => $projectMessageString) : 0;
        ($cfgMessageString != "") ? $message[] = array("messageType" => 'info', "messageText" => $cfgMessageString) : 0;

        return $message;
    }

    /**
     * ends a project in the following way:
     * 1. update sqlite project table, add endnote, enddate and success/failure switch
     * 2. make a last photo, assemble a last timelapse video, delete all pictures except the latest and rename this to
     *    project.jpg (all with wrapper script)
     * 3. update config.ini and set active -> offline
     * 4. move project directory to projects archive, move database to project in archive
     *
     * writes to log and returns (error) messages
     *
     * @param bool $projectSuccess
     * @param string $projectNote
     * @return array
     */
    static function endProject(bool $projectSuccess, string $projectNote): array
    {

        $cfgMessageString = "";
        $projectMessageString = "";

        $db = new SQLITE3(Bootstrap::DBDIR);
        $array = json_decode($db->querySingle('SELECT data FROM project'),true);
        $db->close();

        $endDate = date("Y-m-d");

        try {
            $json = json_encode(array(
                'id' => $array['id'],
                'startdate' => $array['startdate'],
                'enddate' => $endDate,
                'species' => $array['species'],
                'day' => $array['day'],
                'dim' => $array['dim'],
                'startnote' => $array['startnote'],
                'endnote' => $projectNote,
                'success' => $projectSuccess));

            $db = new SQLITE3(Bootstrap::DBDIR);
            $sqlStatement = $db->prepare('UPDATE project SET data = :json WHERE id=1;');
            $sqlStatement->bindParam(':json', $json);
            $sqlStatement->execute();
            $sqlStatement->close();
        }

        catch (Exception $e) { Bootstrap::error($e->getMessage()); }
        if (!isset($e)) { $projectMessageString .= _PROJECT_BACKEND_DONE_DB."<br><br>"; }

        try { exec(Bootstrap::ROOTPATH . "/scripts/wrapper/last_shot_and_timelapse_suid_wrapper"); }
        catch (Exception $e) { Bootstrap::error($e->getMessage()); }
        if (!isset($e)) { $projectMessageString .= _PROJECT_BACKEND_DONE_MULTIMEDIA."<br><br>"; }

        try { $cfgResponse = Cfg::write('plant','active','offline', FALSE); }
        catch (Exception $e) { Bootstrap::error($e->getMessage()); }
        if (!isset($e)) { $cfgMessageString .= $cfgResponse; $projectMessageString .= _PROJECT_BACKEND_DONE_CONFIG; }

        try {
            rename(Bootstrap::ROOTPATH."/projects/".$projectID,Bootstrap::ROOTPATH."/projects/old/".$projectID);
            rename(Bootstrap::DBDIR,Bootstrap::ROOTPATH."/projects/old/".$projectID."/".Bootstrap::DB);
        }
        catch (Exception $e) { Bootstrap::error($e->getMessage()); }
        if (!isset($e)) { $cfgMessageString .= $cfgResponse; $projectMessageString .= _PROJECT_BACKEND_DONE_FS; }

        include("includes/Logger.php");
        Logger::write(LoggerAction::endProject, array("projectID" => $projectID,
            "projectSpecies" => $array['species'],
            "projectSuccess" => $projectSuccess,
            "projectEndNote" => $projectNote));

        ($projectMessageString != "") ? $message[] = array("messageType" => 'success', "messageText" => $projectMessageString) : 0;
        ($cfgMessageString != "") ? $message[] = array("messageType" => 'info', "messageText" => $cfgMessageString) : 0;

        return $message;
    }

    /**
     * Generates a Hash from the Plant Species name and datetime
     *
     * @param string $name the species of the plant
     * @return string the 16 char hash
     */
    static function genprojecthash(string $name) : string
    {
        $now = date("Y-m-d H:i:s.u");
        return substr(md5("$name-$now"),0,16);
    }

    /**
     * returns how many days the project is running
     *
     * @param string $startDate the startdate
     * @param string $endDate the Enddate
     * @return string count of days
     */
    static function daysrunning(string $startDate, string $endDate) : string
    {
        try { return (new DateTime($startDate))->diff(new DateTime($endDate))->days; }
        catch (Exception $e) { Bootstrap::error($e); }
    }

    /**
     * returns array with the building blocks for the project archive fail/success marker
     *
     * @param int $projectSuccess the success state in the project db for the project
     * @return array array("icon" => "fa-times / fa-check", "color" => "red / green", "text" => _FAIL / _SUCCESS
     */
    static function success(int $projectSuccess): array
    {
        return match ($projectSuccess) {
            0 => array("icon" => "fa-x", "color" => "w3-text-red", "text" => _MESSAGE_FAIL),
            1 => array("icon" => "fa-check", "color" => "w3-text-green", "text" => _MESSAGE_SUCCESS),
        };
    }

    /**
     * generates a localized string of the actual growphase
     * @return string
     */
    static function growPhase(): string
    {
        $phase = Cfg::read('plant','phase');
        if ($phase == "grow") {
            $return = _PLANT_GROW;
        }
        elseif ($phase == "bloom") {
            $return = _PLANT_BLOOM;
        }
        return $return;
    }

    static function dayTime(): string{
        $json = json_decode(file_get_contents(Bootstrap::TMPPATH."/sun.run"),true, flags: JSON_OBJECT_AS_ARRAY);
        return match ($json['state']) {
            "sunrise" => _PLANT_DAWN,
            "noon" => _PLANT_DAY,
            "sunset" => _PLANT_DUSK,
            "night" => _PLANT_NIGHT
        };
    }
}