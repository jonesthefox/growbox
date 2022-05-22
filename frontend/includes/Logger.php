<?php

class Logger
{

    /**
     * writes a message to the logfile defined in config.ini
     *
     * @param LoggerAction $LoggerAction
     * @param array|NULL $message
     * @return void
     */
    public static function write(LoggerAction $LoggerAction, array $message = NULL): void
    {
        $now = date('Y-m-d H:i:s');
        switch ($LoggerAction) {
            case LoggerAction::updateConfig:
                $logString = "$now CHANGED([{$message["configSection"]}] -> {$message["configKey"]}: {$message["configValueOld"]} => {$message["configValueNew"]})\n";
                break;

            case LoggerAction::newProject:
                $logString = "$now NEW([{$message["projectSpecies"]}] ID: {$message["projectID"]} PHASE: {$message["projectPhase"]} DAY: {$message["projectDay"]} NIGHT: {$message["projectNight"]} STARTNOTE: {$message["projectNote"]})\n";
                break;

            case LoggerAction::endProject:
                $logString = "$now END([{$message["projectSpecies"]}] ID: {$message["projectID"]} SUCCESS: {$message["projectSuccess"]} ENDNOTE: {$message["projectEndNote"]})\n";
                break;

            case LoggerAction::changePassword:
                $logString = "$now CHANGED(Password hashed)\n";
                break;

            case LoggerAction::rehashPassword:
                $logString = "$now CHANGED(Password rehashed)\n";
                break;

            case LoggerAction::loginError:
            case LoggerAction::session:
                $logString = "$now LOGON(".$message["text"].")\n";
                break;

            case LoggerAction::relayToggle:
                $logString = "$now TOGGLE(".$message["text"].")\n";
                break;

            case LoggerAction::error:
                $logString = "$now ERROR(".$message["text"].")\n";
                break;
        }

        file_put_contents(Bootstrap::ROOTPATH."/log/growbox.log", $logString,FILE_APPEND);
    }
}
