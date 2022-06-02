<div class="w3-container w3-card w3-white w3-margin-bottom w3-round-large">
    <h3><i class="fa fa-thermometer-half w3-margin-right w3-padding-small"></i><?=_TEMPERATURE;?></h3>

    <div class="w3-container w3-card w3-margin-bottom w3-round-large">
        <div class="w3-tag w3-white w3-right w3-margin-top" onclick="showHelp(1)"><i class="fa fa-question-circle"></i></div>
        <form name="temperature" action="/settings/<?=$GLOBALS['settingsBox'];?>" onsubmit="return confirm('<?=_FORM_WARNING_SURE;?>');" method="POST" enctype="multipart/form-data">
            <h4><i class="fa fa-fan w3-margin-right"></i><?=_TEMPERATURE_COOLING;?></h4>
            <p class="w3-pale-yellow w3-border w3-padding w3-round-large" id="1" style="display: none" onclick="showHelp(1)"><i class="fa fa-info-circle w3-text-blue w3-large w3-cell-middle"></i> <i><small><?=_TEMPERATURE_COOLING_INFO;?></small></i></p>
            <table class="w3-table w3-small">
                <tr>
                    <td><label for="temperature[attached]"><?=_SETTINGS_ATTACHED;?></label></td>
                    <td>
                        <select class="w3-right" name="temperature[attached]" id="temperature[attached]">
                            <option value="yes" <?=(Cfg::read('temperature','attached') === true) ? "selected" : "";?>><?=_FORM_YES;?></option>
                            <option value="no" <?=(Cfg::read('temperature','attached') === false) ? "selected" : "";?>><?=_FORM_NO;?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><label for="temperature[gpio]"><?=_SETTINGS_GPIO;?></label></td>
                    <td><input class="w3-right" type="number" style="width: 80px;" value="<?=Cfg::read('temperature','pin');?>" id="temperature[gpio]" name="temperature[gpio]" required></td>
                </tr>
                <tr>
                    <td><label for="temperature[index]"><?=_TEMPERATURE_INDEX;?></label></td>
                    <td><input class="w3-right w3-margin-bottom" type="number" style="width: 80px;" value="<?=Cfg::read('temperature','index');?>" id="temperature[index]" name="temperature[index]" required></td>
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
            <h4><i class="fa fa-thermometer-half w3-margin-right"></i><?=_TEMPERATURE;?></h4>
            <p class="w3-pale-yellow w3-border w3-padding w3-round-large" id="2" style="display: none" onclick="showHelp(2)"><i class="fa fa-info-circle w3-text-blue w3-large w3-cell-middle"></i> <i><small><?=_TEMPERATURE_MINMAX_INFO;?></small></i></p>
            <table class="w3-table w3-small">
                <tr>
                    <td><label for="temperature[relay_mode]"><?=_SETTINGS_MODE;?></label></td>
                    <td>
                        <select class="w3-right" name="temperature[relay_mode]" id="temperature[relay_mode]">
                            <option value="max-on" <?=(Cfg::read('temperature','relay_mode') === "max-on") ? "selected" : "";?>><?=_SETTINGS_MAXON;?></option>
                            <option value="max-off" <?=(Cfg::read('temperature','relay_mode') === "max-off") ? "selected" : "";?>><?=_SETTINGS_MAXOFF;?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><label for="temperature[min]"><?=_TEMPERATURE_TEMP_MIN;?></label></td>
                    <td><input class="w3-right" type="number" min="0" max="100" step="0.1" style="width: 80px;" value="<?=Cfg::read('temperature','min');?>" id="temperature[min]" name="temperature[min]" required></td>
                </tr>
                <tr>
                    <td><label for="temperature[max]"><?=_TEMPERATURE_TEMP_MAX;?></label></td>
                    <td> <input class="w3-right" type="number" min="0" max="100" step="0.1" style="width: 80px;" value="<?=Cfg::read('temperature','max');?>" id="temperature[max]" name="temperature[max]" required></td>
                </tr>
                <tr>
                    <td><input type="hidden" name="op" value="updateConfig"></td>
                    <td><button class="w3-button w3-border w3-round-large w3-right w3-margin-bottom" type="submit"><?=_FORM_CHANGE;?></button></td>
                </tr>
            </table>
        </form>
    </div>

</div>
