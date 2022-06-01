<div class="w3-container w3-card w3-white w3-margin-bottom w3-round-large">
    <h3><i class="fa fa-water w3-margin-right w3-padding-small"></i><?=_WATER;?></h3>

    <div class="w3-container w3-card w3-margin-bottom w3-round-large">
        <div class="w3-tag w3-white w3-right w3-margin-top" onclick="showHelp(1)"><i class="fa fa-question-circle"></i></div>
        <form name="watering" action="/settings/<?=$GLOBALS['settingsBox'];?>" onsubmit="return confirm('<?=_FORM_WARNING_SURE;?>');" method="POST" enctype="multipart/form-data">
            <h4><i class="fa fa-faucet w3-margin-right"></i><?=_WATER_WATERING;?></h4>
            <p class="w3-pale-yellow w3-border w3-padding w3-round-large" id="1" style="display: none" onclick="showHelp(1)"><i class="fa fa-info-circle w3-text-blue w3-large w3-cell-middle"></i> <i><small><?=_WATER_WATERING_INFO;?></small></i></p>
            <table class="w3-table w3-small">
                <tr>
                    <td><label for="water[attached]"><?=_SETTINGS_ATTACHED;?></label></td>
                    <td>
                        <select class="w3-right" name="water[attached]" id="water[attached]">
                            <option value="yes" <?=(Cfg::read('water','attached') === true ) ? "selected" : "";?>><?=_FORM_YES;?></option>
                            <option value="no" <?=(Cfg::read('water','attached') === false) ? "selected" : "";?>><?=_FORM_NO;?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><label for="water[gpio]"><?=_SETTINGS_GPIO;?></label></td>
                    <td><input class="w3-right" type="number" style="width: 80px;" value="<?=Cfg::read('water','pin');?>" id="water[gpio]" name="water[gpio]" required></td>
                </tr>
                <tr>
                    <td><label for="water[index]"><?=_WATER_INDEX;?></label></td>
                    <td><input class="w3-right w3-margin-bottom" type="number" style="width: 80px;" value="<?=Cfg::read('water','index');?>" id="water[index]" name="water[index]" required></td>
                </tr>
                <tr>
                    <td><input type="hidden" name="op" value="updateConfig"></td>
                    <td><button class="w3-button w3-border w3-round-large w3-right w3-margin-bottom" type="submit"><?=_FORM_CHANGE;?></button></td>
                </tr>
            </table>
        </form>
    </div>

    <div class="w3-container w3-card w3-white w3-margin-bottom w3-round-large">
        <div class="w3-tag w3-white w3-right w3-margin-top" onclick="showHelp(2)"><i class="fa fa-question-circle"></i></div>
        <form name="options" action="/settings/<?=$GLOBALS['settingsBox'];?>" onsubmit="return confirm('<?=_FORM_WARNING_SURE;?>');" method="POST" enctype="multipart/form-data">
            <h4><i class="fa fa-tint w3-margin-right"></i><?=_WATER_MINMAX;?></h4>
            <p class="w3-pale-yellow w3-border w3-padding w3-round-large" id="2" style="display: none" onclick="showHelp(2)"><i class="fa fa-info-circle w3-text-blue w3-large w3-cell-middle"></i> <i><small><?=_WATER_MINMAX_INFO;?></small></i></p>
            <table class="w3-table w3-small">
                <tr>
                    <td><label for="water[relay_mode]"><?=_SETTINGS_MODE;?></label></td>
                    <td>
                        <select class="w3-right" name="water[relay_mode]" id="water[relay_mode]">
                            <option value="max-on" <?=(Cfg::read('water','relay_mode') === "max-on") ? "selected" : "";?>><?=_SETTINGS_MAXON;?></option>
                            <option value="max-off" <?=(Cfg::read('water','relay_mode') === "max-off") ? "selected" : "";?>><?=_SETTINGS_MAXOFF;?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><label for="water[min]"><?=_WATER_MOIST_MIN;?></label></td>
                    <td><input class="w3-right" type="number" min="0" max="100" step="0.1" style="width: 80px;" value="<?=Cfg::read('water','min');?>" id="water[min]" name="water[min]" required></td>
                </tr>
                <tr>
                    <td><label for="water[max]"><?=_WATER_MOIST_MAX;?></label></td>
                    <td><input class="w3-right" type="number" min="0" max="100" step="0.1" style="width: 80px;" value="<?=Cfg::read('water','max');?>" id="water[max]" name="water[max]" required></td>
                </tr>
                <tr>
                    <td><label for="water[quiet_hours]"><?=_AIR_QUIETHOURS;?></label></td>
                    <td> <input class="w3-right w3-margin-bottom" type="text" min="0" max="100" style="width: 80px;" value="<?=Cfg::read('water','quiet_hours');?>" id="water[quiet_hours]" name="water[quiet_hours]" required></td>
                </tr
                <tr>
                    <td><input type="hidden" name="op" value="updateConfig"></td>
                    <td><button class="w3-button w3-border w3-round-large w3-right w3-margin-bottom" type="submit"><?=_FORM_CHANGE;?></button></td>
                </tr>
            </table>
        </form>
    </div>

</div>
