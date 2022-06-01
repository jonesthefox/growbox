<div class="w3-container w3-card w3-white w3-margin-bottom w3-round-large">
    <h3><i class="fa fa-wind w3-margin-right w3-padding-small"></i><?=_AIR;?></h3>

    <div class="w3-container w3-card w3-margin-bottom w3-round-large">
        <div class="w3-tag w3-white w3-right w3-margin-top" onclick="showHelp(1)"><i class="fa fa-question-circle"></i></div>
        <form name="humidity" action="/settings/<?=$GLOBALS['settingsBox'];?>" onsubmit="return confirm('<?=_FORM_WARNING_SURE;?>');" method="POST" enctype="multipart/form-data">
            <h4><i class="fa fa-fan w3-margin-right"></i><?=_AIR_VENTILATION;?></h4>
            <p class="w3-pale-yellow w3-border w3-padding w3-round-large" id="1" style="display: none" onclick="showHelp(1)"><i class="fa fa-info-circle w3-text-blue w3-large w3-cell-middle"></i> <i><small><?=_AIR_VENTILATION_INFO;?></small></i></p>
            <table class="w3-table w3-small">
                <tr>
                    <td><label for="air[attached]"><?=_SETTINGS_ATTACHED;?></label></td>
                    <td>
                        <select class="w3-right" name="air[attached]" id="air[attached]">
                            <option value="yes" <?=(Cfg::read('air','attached') === true) ? "selected" : "";?>><?=_FORM_YES;?></option>
                            <option value="no" <?=(Cfg::read('air','attached') === false) ? "selected" : "";?>><?=_FORM_NO;?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><label for="air[gpio]"><?=_SETTINGS_GPIO;?></label></td>
                    <td><input class="w3-right" type="number" style="width: 80px;" value="<?=Cfg::read('air','pin');?>" id="air[gpio]" name="air[gpio]" required></td>
                </tr>
                <tr>
                    <td><label for="air[index]"><?=_AIR_INDEX;?></label></td>
                    <td><input class="w3-right w3-margin-bottom" type="number" style="width: 80px;" value="<?=Cfg::read('air','index');?>" id="air[index]" name="air[index]" required></td>
                </tr>
                <tr>
                    <td><input type="hidden" name="op" value="updateConfig"></td>
                    <td><button class="w3-button w3-border w3-round-large w3-right w3-margin-bottom" type="submit"><?=_FORM_CHANGE;?></button></td>
                </tr>
            </table>
        </form>
    </div>

    <div class="w3-container w3-card w3-margin-bottom w3-round-large">
        <div class="w3-tag w3-white w3-right w3-margin-top" onclick="showHelp(2)"><i class="fa fa-question-circle"></i></div>
        <form name="options" action="/settings/<?=$GLOBALS['settingsBox'];?>" onsubmit="return confirm('<?=_FORM_WARNING_SURE;?>');" method="POST" enctype="multipart/form-data">
            <h4><i class="fa fa-cloud-rain w3-margin-right"></i><?=_SENSOR_RH;?></h4>
            <p class="w3-pale-yellow w3-border w3-padding w3-round-large" id="2" style="display: none" onclick="showHelp(2)"><i class="fa fa-info-circle w3-text-blue w3-large w3-cell-middle"></i> <i><small><?=_AIR_RH_INFO;?></small></i></p>
            <table class="w3-table w3-small">
                <tr>
                    <td><label for="air[relay_mode]"><?=_SETTINGS_MODE;?></label></td>
                    <td>
                        <select class="w3-right" name="air[relay_mode]" id="air[relay_mode]">
                            <option value="max-on" <?=(Cfg::read('air','relay_mode') === "max-on") ? "selected" : "";?>><?=_SETTINGS_MAXON;?></option>
                            <option value="max-off" <?=(Cfg::read('air','relay_mode') === "max-off") ? "selected" : "";?>><?=_SETTINGS_MAXOFF;?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><label for="air[min]"><?=_AIR_RH_SETTING_MIN;?></label></td>
                    <td><input class="w3-right" type="number" min="0" max="100" step="0.1" style="width: 80px;" value="<?=Cfg::read('air','min');?>" id="air[min]" name="air[min]" required></td>
                </tr>
                <tr>
                    <td><label for="air[max]"><?=_AIR_RH_SETTING_MAX;?></label></td>
                    <td> <input class="w3-right" type="number" min="0" max="100" step="0.1" style="width: 80px;" value="<?=Cfg::read('air','max');?>" id="air[max]" name="air[max]" required></td>
                </tr>
                <tr>
                    <td><label for="air[quiet_hours]"><?=_AIR_QUIETHOURS;?></label></td>
                    <td> <input class="w3-right w3-margin-bottom" type="text" min="0" max="100" style="width: 80px;" value="<?=Cfg::read('air','quiet_hours');?>" id="air[quiet_hours]" name="air[quiet_hours]" required></td>
                </tr
                <tr>
                    <td><input type="hidden" name="op" value="updateConfig"></td>
                    <td><button class="w3-button w3-border w3-round-large w3-right w3-margin-bottom" type="submit"><?=_FORM_CHANGE;?></button></td>
                </tr>
            </table>
        </form>
    </div>

</div>
