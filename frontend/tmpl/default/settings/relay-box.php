<div class="w3-container w3-card w3-white w3-margin-bottom w3-round-large">
    <h3><i class="fa fa-microchip w3-margin-right w3-padding-small"></i><?=_RELAY;?></h3>

    <div class="w3-container w3-card w3-margin-bottom w3-round-large">
        <div class="w3-tag w3-white w3-right w3-margin-top" onclick="showHelp(1)"><i class="fa fa-question-circle"></i></div>
        <form name="relay" action="/settings/<?=$GLOBALS['settingsBox'];?>" onsubmit="return confirm('<?=_FORM_WARNING_SURE;?>');" method="POST" enctype="multipart/form-data">
            <h4><i class="fa fa-sliders-h w3-margin-right"></i><?=_SETTINGS_CONFIGURATION;?></h4>
            <p class="w3-pale-yellow w3-border w3-padding w3-round-large" id="1" style="display: none" onclick="showHelp(1)"><i class="fa fa-info-circle w3-text-blue w3-large w3-cell-middle"></i> <i><small><?=_RELAY_INFO;?></small></i></p>
            <table class="w3-table w3-small">
                <tr>
                    <td><label for="relay[quiet_hours]"><?=_RELAY_QUIETHOURS;?></label></td>
                    <td><input class="w3-right" type="text" style="width: 80px;" value="<?=Cfg::read('relay','quiet_hours');?>" id="relay[quiet_hours]" name="relay[quiet_hours]" required></td>
                </tr>
                <tr>
                    <td><label for="relay[sleep_between_readings]"><?=_RELAY_SLEEP_BETWEEN_READINGS;?></label></td>
                    <td><input class="w3-right w3-margin-bottom" type="number" style="width: 80px;" value="<?=Cfg::read('relay','sleep_between_readings');?>" id="relay[sleep_between_readings]" name="relay[sleep_between_readings]" required></td>
                </tr>
                <tr>
                    <td><input type="hidden" name="op" value="updateConfig"></td>
                    <td><button class="w3-button w3-border w3-round-large w3-right w3-margin-bottom" type="submit"><?=_FORM_CHANGE;?></button></td>
                </tr>
            </table>
        </form>
    </div>

</div>
