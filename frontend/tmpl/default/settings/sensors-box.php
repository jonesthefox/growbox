 <div class="w3-container w3-card w3-white w3-margin-bottom w3-round-large">
        <h3><i class="fa fa-microchip w3-margin-right w3-padding-small"></i><?=_SENSORS;?></h3>

        <div class="w3-container w3-card w3-white w3-margin-bottom w3-round-large">
            <div class="w3-tag w3-white w3-right w3-margin-top" onclick="showHelp('activator')"><i class="fa fa-question-circle"></i></div>
            <form name="sensors" action="/settings/<?=$GLOBALS['settingsBox'];?>" onsubmit="return confirm('<?=_FORM_WARNING_SURE;?>');" method="POST" enctype="multipart/form-data">
                <h4><i class="fa fa-square-check w3-margin-right w3-padding-small"></i><?=_SENSORS_ACTIVATOR;?></h4>
                <p class="w3-pale-yellow w3-border w3-padding w3-round-large" id="activator" style="display: none" onclick="showHelp('activator')"><i class="fa fa-info-circle w3-text-blue w3-large w3-cell-middle"></i> <i><small><?=_SENSORS_ACTIVATOR_INFO;?></small></i></p>
                <table class="w3-table w3-striped w3-small">
                    <tr>
                        <th><?=_SENSORS_SENSOR;?></th>
                        <th><?=_SENSORS_ACTIVATOR_ACTIVE;?></th>
                    </tr>
                    <?=Sensors::sensorActive();?>
                    <tr>
                        <td><input type="hidden" name="op" value="sensorActivator"></td>
                        <td><br><button class="w3-button w3-border w3-round-large w3-right w3-margin-bottom" type="submit"><?=_FORM_CHANGE;?></button></td>
                    </tr>
                </table>
            </form>
        </div>

        <div class="w3-container w3-card w3-white w3-margin-bottom w3-round-large">
            <div class="w3-tag w3-white w3-right w3-margin-top" onclick="showHelp('chooser')"><i class="fa fa-question-circle"></i></div>
            <form name="sensors" action="/settings/<?=$GLOBALS['settingsBox'];?>" onsubmit="return confirm('<?=_FORM_WARNING_SURE;?>');" method="POST" enctype="multipart/form-data">
                <h4><i class="fa fa-list-ol w3-margin-right w3-padding-small"></i><?=_SENSORS_CHOOSER;?></h4>
                <p class="w3-pale-yellow w3-border w3-padding w3-round-large" id="chooser" style="display: none" onclick="showHelp('chooser')"><i class="fa fa-info-circle w3-text-blue w3-large w3-cell-middle"></i> <i><small><?=_SENSORS_CHOOSER_INFO;?></small></i></p>
                <table class="w3-table w3-striped w3-small">
                    <tr>
                        <th>#</th>
                        <th><?=_SENSORS_SENSOR;?></th>
                        <th><?=_SENSORS_CHOOSER_FROMCONFIG;?></th>
                    </tr>
                    <?=Sensors::sensorChooser();?>
                    <tr>
                        <td><br><input type="hidden" name="op" value="sensorOrder"><button class="w3-left w3-button w3-round-large w3-border w3-margin-bottom" type="button" onclick="resetter(); reset()"><?=_FORM_RESET;?></button></td>
                        <td></td>
                        <td><br><button class="w3-button w3-border w3-round-large w3-right" type="submit"><?=_FORM_CHANGE;?></button></td>
                    </tr>
                </table>
            </form>
        </div>
    </div>

    <div class="w3-container w3-card w3-white w3-margin-bottom w3-round-large">
        <h3><i class="fa fa-screwdriver-wrench w3-margin-right w3-padding-small"></i><?=_SENSORS_MODULE_CONFIG;?></h3>

        <?=Sensors::sensorModules();?>

    </div>

    <div class="w3-container w3-card w3-white w3-margin-bottom w3-round-large">
        <h3><i class="fa fa-calculator w3-margin-right w3-padding-small"></i><?=_SENSORS_MODIFIERS;?></h3>

        <?=Sensors::modifierModules();?>

    </div>
<!--suppress HtmlUnknownTarget -->
<script src="<?=Bootstrap::TEMPLATEDIRHTML;?>/js/showhelp.js?v=1"></script>
 <!--suppress HtmlUnknownTarget -->
<script src="<?=Bootstrap::TEMPLATEDIRHTML;?>/js/sensors.js"></script>