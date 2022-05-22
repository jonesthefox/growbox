<div class="w3-container w3-card w3-white w3-margin-bottom w3-round-large">
    <h3><i class="fa fa-lightbulb w3-margin-right w3-padding-small"></i><?=_LIGHT;?></h3>

    <div class="w3-container w3-card w3-white w3-margin-bottom w3-round-large">
        <div class="w3-tag w3-white w3-right w3-margin-top" onclick="showHelp(1)"><i class="fa fa-question-circle"></i></div>
        <form name="daylength" id="daylength" action="/settings/<?=$GLOBALS['settingsBox'];?>" onsubmit="calculateDayNightLength(); return confirm('<?=_FORM_WARNING_SURE;?>');" method="POST" enctype="multipart/form-data">
            <h4><i class="fa fa-sun"></i><i class="fa fa-moon w3-margin-right"></i><?=_LIGHT_DAYNIGHT;?></h4>
            <p class="w3-pale-yellow w3-border w3-padding w3-round-large" id="1" style="display: none" onclick="showHelp(1)"><i onclick="showHelp(1)" class="fa fa-info-circle w3-text-blue w3-large w3-cell-middle"></i> <i><small><?=_LIGHT_DAYNIGHT_INFO;?></small></i></p>
            <table class="w3-table w3-small">
                <tr>
                    <td><label for="light[day]"><?=_LIGHT_DAYLENGTH;?></label></td>
                    <td><input class="w3-right" type="number" style="width: 80px;" value="<?=Cfg::read('light','day') / 60 / 60;?>" id="light[day]" name="light[day]" onkeyup='if(!validdaynum(this.value)) this.value="24";' onchange='if(!validdaynum(this.value)) this.value="24"; nightLength(this,"daylength")' required></td>
                </tr>
                <tr>
                    <td><label for="light[night]"><?=_LIGHT_NIGHTLENGTH;?></label></td>
                    <td><input class="w3-right w3-margin-bottom" type="number" style="width: 80px;" value="<?=Cfg::read('light','night') / 60 / 60;?>" id="light[night]" name="light[night]" disabled="disabled"></td>
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
        <form name="duskdawn" id="duskdawn" action="/settings/<?=$GLOBALS['settingsBox'];?>" onsubmit="calculateDuskDawn(); return confirm('<?=_FORM_WARNING_SURE;?>');" method="POST" enctype="multipart/form-data">
            <h4><i class="fa fa-clock w3-margin-right"></i><?=_LIGHT_DUSKDAWN;?></h4>
            <p class="w3-pale-yellow w3-border w3-padding w3-round-large" id="2" style="display: none" onclick="showHelp(2)"><i class="fa fa-info-circle w3-text-blue w3-large w3-cell-middle"></i> <i><small><?=_LIGHT_DUSKDAWN_INFO;?></small></i></p>
            <table class="w3-table w3-small">
                <tr>
                    <td><label for="light[dim]"><?=_LIGHT_DIM;?></label></td>
                    <td><input class="w3-right" type="number" style="width: 80px;" id="light[dim]" name="light[dim]" value="<?=Cfg::read('light','dim') / 60;?>" required></td>
                </tr>
                <tr>
                    <td><label for="light[dimstep]"><?=_LIGHT_DIMSTEP;?></label></td>
                    <td><input class="w3-right w3-margin-bottom" type="number" style="width: 80px;" id="light[dimstep]" name="light[dimstep]" value="<?=Cfg::read('light','dim_step');?>" required></td>
                </tr>
                <tr>
                    <td><input type="hidden" name="op" value="updateConfig"></td>
                    <td><button class="w3-button w3-border w3-round-large w3-right w3-margin-bottom" type="submit"><?=_FORM_CHANGE;?></button></td>
                </tr>
            </table>
        </form>
    </div>

    <div class="w3-container w3-card w3-white w3-margin-bottom w3-round-large">
        <div class="w3-tag w3-white w3-right w3-margin-top" onclick="showHelp(3)"><i class="fa fa-question-circle"></i></div>
        <form name="neopixel" action="/settings/<?=$GLOBALS['settingsBox'];?>" onsubmit="return confirm('<?=_FORM_WARNING_SURE;?>');" method="POST" enctype="multipart/form-data">
            <h4><i class="fa fa-microchip w3-margin-right"></i><?=_LIGHT_NEOPIXEL;?></h4>
            <p class="w3-pale-yellow w3-border w3-padding w3-round-large" id="3" style="display: none" onclick="showHelp(3)"><i class="fa fa-info-circle w3-text-blue w3-large w3-cell-middle"></i> <i><small><?=_LIGHT_NEOPIXEL_INFO;?></small></i></p>
            <table class="w3-table w3-small">
                <tr>
                    <td><label for="light[attached]"><?=_SETTINGS_ATTACHED;?></label></td>
                    <td>
                        <select class="w3-right" name="light[attached]" id="light[attached]">
                            <option value="yes" <?=(Cfg::read('light','attached') === true ) ? "selected" : "";?>><?=_FORM_YES;?></option>
                            <option value="no" <?=(Cfg::read('light','attached') === false) ? "selected" : "";?>><?=_FORM_NO;?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><label for="light[gpio]"><?=_SETTINGS_GPIO;?></label></td>
                    <td>
                        <select class="w3-right" name="light[gpio]" id="light[gpio]">
                            <option value="12" <?=(Cfg::read('light','gpio') == 12 ) ? "selected" : "";?>>12</option>
                            <option value="19" <?=(Cfg::read('light','gpio') == 19 ) ? "selected" : "";?>>19</option>
                            <option value="32" <?=(Cfg::read('light','gpio') == 32 ) ? "selected" : "";?>>32</option>
                            <option value="40" <?=(Cfg::read('light','gpio') == 40) ? "selected" : "";?>>40</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><label for="light[order]"><?=_LIGHT_PIXEL_ORDER;?></label></td>
                    <td>
                        <select class="w3-right" name="light[order]" id="light[order]">
                            <option value="RGBW" <?=(Cfg::read('light','order') == "RGBW") ? "selected" : "";?>>RGBW</option>
                            <option value="GRBW" <?=(Cfg::read('light','order') == "GRBW") ? "selected" : "";?>>GRBW</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><label for="light[num_pixels]"><?=_LIGHT_NUMPIXEL;?></label></td>
                    <td><input class="w3-right w3-margin-bottom" type="number" style="width: 80px;" value="<?=Cfg::read('light','num_pixels');?>" id="light[num_pixels]" name="light[num_pixels]" required></td>
                </tr>
                <tr>
                    <td><input type="hidden" name="op" value="updateConfig"></td>
                    <td><button class="w3-button w3-border w3-round-large w3-right w3-margin-bottom" type="submit"><?=_FORM_CHANGE;?></button></td>
                </tr>
            </table>
        </form>
    </div>

    <div class="w3-container w3-card w3-white w3-margin-bottom w3-round-large">
        <div class="w3-tag w3-white w3-right w3-margin-top" onclick="showHelp(4)"><i class="fa fa-question-circle"></i></div>
        <form name="rgbwbrightness" id="rgbwbrightness" action="/settings/<?=$GLOBALS['settingsBox'];?>" onsubmit="generateBrightness(); return confirm('<?=_FORM_WARNING_SURE;?>');" method="POST" enctype="multipart/form-data">
        <h4><i class="fa fa-sun w3-margin-right"></i><?=_LIGHT_BRIGHTNESS;?></h4>
        <p class="w3-pale-yellow w3-border w3-padding w3-round-large" id="4" style="display: none" onclick="showHelp(4)"><i class="fa fa-info-circle w3-text-blue w3-large w3-cell-middle"></i> <i><small><?=_LIGHT_BRIGHTNESS_INFO;?></small></i></p>
            <h5><?=_PLANT_GROW;?></h5>
            <table class="w3-table w3-small">
                <tr>
                    <td><label for="intensity_grow_r"><?=_LIGHT_BRIGHTNESS_R;?></label></td>
                    <td><input class="w3-right" type="number" min="0" max="255" style="width: 80px;" value="<?=$GLOBALS["grow"][0]; /* defined in index.php */ ?>" onkeyup='if(!validlightnum(this.value)) this.value="255";' onchange='if(!validlightnum(this.value)) this.value="255";' id="intensity_grow_r" name="intensity_grow_r" required></td>
                </tr>
                <tr>
                    <td><label for="intensity_grow_g"><?=_LIGHT_BRIGHTNESS_G;?></label></td>
                    <td><input class="w3-right" type="number" min="0" max="255" style="width: 80px;" value="<?=$GLOBALS["grow"][1];?>" onkeyup='if(!validlightnum(this.value)) this.value="255";' onchange='if(!validlightnum(this.value)) this.value="255";' id="intensity_grow_g" name="intensity_grow_g" required></td>
                </tr>
                <tr>
                    <td><label for="intensity_grow_b"><?=_LIGHT_BRIGHTNESS_B;?></label></td>
                    <td><input class="w3-right" type="number" min="0" max="255" style="width: 80px;" value="<?=$GLOBALS["grow"][2];?>" onkeyup='if(!validlightnum(this.value)) this.value="255";' onchange='if(!validlightnum(this.value)) this.value="255";' id="intensity_grow_b" name="intensity_grow_b" required></td>
                </tr>
                <tr>
                    <td><label for="intensity_grow_w"><?=_LIGHT_BRIGHTNESS_W;?></label></td>
                    <td><input class="w3-right" type="number" min="0" max="255" style="width: 80px;" value="<?=$GLOBALS["grow"][3];?>" onkeyup='if(!validlightnum(this.value)) this.value="255";' onchange='if(!validlightnum(this.value)) this.value="255";' id="intensity_grow_w" name="intensity_grow_w" required></td>
                </tr>
            </table>
            <br>
            <h5><?=_PLANT_BLOOM;?></h5>
            <table class="w3-table w3-small">
                <tr>
                    <td><label for="intensity_bloom_r"><?=_LIGHT_BRIGHTNESS_R;?></label></td>
                    <td><input class="w3-right" type="number" min="0" max="255" style="width: 80px;" value="<?=$GLOBALS["bloom"][0];?>" onkeyup='if(!validlightnum(this.value)) this.value="255";' onchange='if(!validlightnum(this.value)) this.value="255";' id="intensity_bloom_r" name="intensity_bloom_r" required></td>
                </tr>
                <tr>
                    <td><label for="intensity_bloom_g"><?=_LIGHT_BRIGHTNESS_G;?></label></td>
                    <td><input class="w3-right" type="number" min="0" max="255" style="width: 80px;" value="<?=$GLOBALS["bloom"][1];?>" onkeyup='if(!validlightnum(this.value)) this.value="255";' onchange='if(!validlightnum(this.value)) this.value="255";' id="intensity_bloom_g" name="intensity_bloom_g" required></td>
                </tr>
                <tr>
                    <td><label for="intensity_bloom_b"><?=_LIGHT_BRIGHTNESS_B;?></label></td>
                    <td><input class="w3-right" type="number" min="0" max="255" style="width: 80px;" value="<?=$GLOBALS["bloom"][2];?>" onkeyup='if(!validlightnum(this.value)) this.value="255";' onchange='if(!validlightnum(this.value)) this.value="255";' id="intensity_bloom_b" name="intensity_bloom_b" required></td>
                </tr>
                <tr>
                    <td><label for="intensity_bloom_w"><?=_LIGHT_BRIGHTNESS_W;?></label></td>
                    <td><input class="w3-right w3-margin-bottom" type="number" min="0" max="255" style="width: 80px;" value="<?=$GLOBALS["bloom"][3];?>" onkeyup='if(!validlightnum(this.value)) this.value="255";' onchange='if(!validlightnum(this.value)) this.value="255";' id="intensity_bloom_w" name="intensity_bloom_w" required></td>
                </tr>
                <tr>
                    <td>
                        <input type="hidden" id="light[rgbw_grow]" name="light[rgbw_grow]" value="">
                        <input type="hidden" id="light[rgbw_bloom]" name="light[rgbw_bloom]" value="">
                        <input type="hidden" name="op" value="updateConfig">
                    </td>
                    <td><button class="w3-button w3-border w3-round-large w3-right w3-margin-bottom" type="submit"><?=_FORM_CHANGE;?></button></td>
                </tr>
            </table>
        </form>
    </div>

    <div class="w3-container w3-card w3-margin-bottom w3-round-large">
        <div class="w3-tag w3-white w3-right w3-margin-top" onclick="showHelp(5)"><i class="fa fa-question-circle"></i></div>
        <form name="cooling" action="/settings/<?=$GLOBALS['settingsBox'];?>" onsubmit="return confirm('<?=_FORM_WARNING_SURE;?>');" method="POST" enctype="multipart/form-data">
            <h4><i class="fa fa-fan w3-margin-right"></i><?=_COOLING;?></h4>
            <p class="w3-pale-yellow w3-border w3-padding w3-round-large" id="5" style="display: none" onclick="showHelp(5)"><i class="fa fa-info-circle w3-text-blue w3-large w3-cell-middle"></i> <i><small><?=_COOLING_INFO;?></small></i></p>
            <table class="w3-table w3-small">
                <tr>
                    <td><label for="cooling[attached]"><?=_SETTINGS_ATTACHED;?></label></td>
                    <td>
                        <select class="w3-right" name="cooling[attached]" id="cooling[attached]">
                            <option value="yes" <?=(Cfg::read('cooling','attached') === true) ? "selected" : "";?>><?=_FORM_YES;?></option>
                            <option value="no" <?=(Cfg::read('cooling','attached') === false) ? "selected" : "";?>><?=_FORM_NO;?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><label for="cooling[gpio]"><?=_SETTINGS_GPIO;?></label></td>
                    <td><input class="w3-right w3-margin-bottom" type="number" style="width: 80px;" value="<?=Cfg::read('air','pin');?>" id="cooling[gpio]" name="cooling[gpio]" required></td>
                </tr>
                <tr>
                    <td><input type="hidden" name="op" value="updateConfig"></td>
                    <td><button class="w3-button w3-border w3-round-large w3-right w3-margin-bottom" type="submit"><?=_FORM_CHANGE;?></button></td>
                </tr>
            </table>
        </form>
    </div>

    <div class="w3-container w3-card w3-white w3-margin-bottom w3-round-large">
        <div class="w3-tag w3-white w3-right w3-margin-top" onclick="showHelp(6)"><i class="fa fa-question-circle"></i></div>
        <form name="daylength" id="daylength" action="/settings/<?=$GLOBALS['settingsBox'];?>" onsubmit="return confirm('<?=_FORM_WARNING_SURE;?>');" method="POST" enctype="multipart/form-data">
            <h4><i class="fa fa-file-lines w3-margin-right"></i><?=_LIGHT_OPTIONS;?></h4>
            <p class="w3-pale-yellow w3-border w3-padding w3-round-large" id="6" style="display: none" onclick="showHelp(6)"><i onclick="showHelp(6)" class="fa fa-info-circle w3-text-blue w3-large w3-cell-middle"></i> <i><small><?=_LIGHT_SLEEPUNTILSTORE_INFO;?></small></i></p>
            <table class="w3-table w3-small">
                <tr>
                    <td><label for="light[sleepuntilstore]"><?=_LIGHT_SLEEPUNTILSTORE;?></label></td>
                    <td><input class="w3-right w3-margin-bottom" type="number" style="width: 80px;" value="<?=Cfg::read('light','sleepuntilstore');?>" id="light[sleepuntilstore]" name="light[sleepuntilstore]" required></td>
                </tr>
                <tr>
                    <td><input type="hidden" name="op" value="updateConfig"></td>
                    <td><button class="w3-button w3-border w3-round-large w3-right w3-margin-bottom" type="submit"><?=_FORM_CHANGE;?></button></td>
                </tr>
            </table>
        </form>
    </div>
</div>
<!--suppress HtmlUnknownTarget -->
<script src="<?=Bootstrap::TEMPLATEDIRHTML;?>/js/light-box.js"></script>