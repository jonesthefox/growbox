<?php

/** @noinspection PhpUnused */

/** Template.php */

enum LogFile
{
    case Project;
    case Cron;

    public function path(): string {
        return match($this) {
            LogFile::Project => '../log/growbox.log',
            LogFile::Cron => '../log/cron.log'};
    }
}

enum Box
{
    case startnewproject;
    case projectbrief;
    case image;
    case sensors;
    case spectrum;
}

/** Logger.php */

enum LoggerAction
{
    case updateConfig;
    case newProject;
    case endProject;
    case changePassword;
    case rehashPassword;
    case session;
    case loginError;
    case relayToggle;
    case error;
}
