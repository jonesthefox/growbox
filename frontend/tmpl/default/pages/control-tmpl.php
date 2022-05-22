<?php /* $GLOBALS["grow"] & $GLOBALS["bloom"] defined in index.php */ ?>

<!-- Page Container -->
<div class="w3-content w3-margin-top" style="max-width:1400px;" xmlns="http://www.w3.org/1999/html">

    <!-- The Grid -->
    <div class="w3-row-padding">

        <?php include(Bootstrap::TEMPLATEDIR."/left-column-box.php");?>

        <!-- Right Column -->
        <div class="w3-twothird w3-margin-top">

            <div class="w3-container w3-card w3-white w3-margin-bottom w3-margin-top w3-round-large">
                <h1><i class="fa fa-sliders-h w3-margin-right"></i><?=_CONTROLCENTER;?></h1>
            </div>

            <div class="w3-container w3-card w3-white w3-margin-bottom w3-round-large" <?=(Cfg::read('light','attached')) == 'yes' ? '' : 'hidden';?>>
                <h3><i class="fa fa-lightbulb w3-margin-right w3-padding-small"></i><?=_LIGHT;?></h3>
                <div class="w3-container w3-card w3-white w3-margin-bottom w3-pale-red w3-round-large">
                    <p><i class="fa fa-warning w3-text-red w3-xlarge w3-cell-middle"></i> <i class="w3-text-black"><small><?=_CONTROLCENTER_LIGHT_INFO;?></small></i></p>
                </div>

                <div class="w3-container w3-card w3-white w3-margin-bottom w3-round-large">
                    <div class="w3-tag w3-white w3-right w3-margin-top" onclick="showHelp(1)"><i class="fa fa-question-circle"></i></div>
                    <form name="rgbwbrightness" id="rgbwbrightness" action="/control/" method="POST" enctype="multipart/form-data">
                    <h4 class="w3-text-black"><i class="fa fa-sun w3-margin-right"></i><?=_LIGHT_BRIGHTNESS;?></h4>
                    <p class="w3-pale-yellow w3-border w3-padding w3-round-large" id="1" style="display: none;" onclick="showHelp(1)"><i class="fa fa-info-circle w3-text-blue w3-large w3-cell-middle"></i> <i class="w3-text-black"><small><?=_LIGHT_BRIGHTNESS_INFO;?></small></i></p>
                        <table class="w3-table w3-small">
                            <tr>
                                <td><label for="red"><?=_LIGHT_BRIGHTNESS_R;?></label></td>
                                <td></td>
                                <td><input class="w3-right" type="number" min="0" max="255" value="<?=$_SESSION['defaultRed'];?>" style="width: 80px;" onkeyup='if(!validlightnum(this.value)) this.value="255"; showColor(document.getElementById("red").value, document.getElementById("green").value, document.getElementById("blue").value); updateChart(spectral);' onchange='if(!validlightnum(this.value)) this.value="255";' id="red" name="red" required></td>
                            </tr>
                            <tr>
                                <td><label for="green"><?=_LIGHT_BRIGHTNESS_G;?></label></td>
                                <td></td>
                                <td><input class="w3-right" type="number" min="0" max="255" value="<?=$_SESSION['defaultGreen'];?>" style="width: 80px;" onkeyup='if(!validlightnum(this.value)) this.value="255"; showColor(document.getElementById("red").value, document.getElementById("green").value, document.getElementById("blue").value); updateChart(spectral);' onchange='if(!validlightnum(this.value)) this.value="255";' id="green" name="green" required></td>
                            </tr>
                            <tr>
                                <td><label for="blue"><?=_LIGHT_BRIGHTNESS_B;?></label></td>
                                <td></td>
                                <td><input class="w3-right" type="number" min="0" max="255" value="<?=$_SESSION['defaultBlue'];?>" style="width: 80px;" onkeyup='if(!validlightnum(this.value)) this.value="255"; showColor(document.getElementById("red").value, document.getElementById("green").value, document.getElementById("blue").value); updateChart(spectral);' onchange='if(!validlightnum(this.value)) this.value="255";' id="blue" name="blue" required></td>
                            </tr>
                            <tr>
                                <td><label for="white"><?=_LIGHT_BRIGHTNESS_W;?></label></td>
                                <td></td>
                                <td><input class="w3-right w3-margin-bottom" type="number" min="0" max="255" value="<?=$_SESSION['defaultWhite'];?>" style="width: 80px;" onkeyup='if(!validlightnum(this.value)) this.value="255"; showColor(document.getElementById("red").value, document.getElementById("green").value, document.getElementById("blue").value); updateChart(spectral);' onchange='if(!validlightnum(this.value)) this.value="255";' id="white" name="white" required></td>

                            </tr>
                            <tr>
                                <td><input type="hidden" name="op" value="control">
                                    <button class="w3-button w3-border w3-round-large w3-margin-bottom" type="button" onclick="grow(); updateChart(spectral);"><?=_PLANT_GROW;?></button><br>
                                    <button class="w3-button w3-border w3-round-large w3-margin-bottom" type="button" onclick="bloom(); updateChart(spectral);"><?=_PLANT_BLOOM;?></button></td>
                                <td><i class="w3-button w3-border w3-round-large w3-margin-bottom" id="colorbox" style="padding-right: 40px; background-color: rgb(<?=$_SESSION['defaultRed'];?>, <?=$_SESSION['defaultGreen'];?>, <?=$_SESSION['defaultBlue'];?>);">&nbsp;</i></td>
                                <td><button class="w3-button w3-border w3-round-large w3-right w3-margin-bottom" type="submit"><?=_FORM_START;?></button><br><br><br>
                                    <button class="w3-button w3-border w3-round-large w3-right w3-margin-bottom" type="button" onclick="reset(); updateChart(spectral);"><?=_FORM_RESET;?></button></td>
                            </tr>
                        </table>
                    </form>
                </div>

                <?=Template::showBox(Box::spectrum);?>

                <div class="w3-container w3-card w3-white w3-margin-bottom w3-round-large">
                    <div class="w3-tag w3-white w3-right w3-margin-top" onclick="showHelp(2)"><i class="fa fa-question-circle"></i></div>
                    <form name="color" id="color" action="/control/" method="POST" enctype="multipart/form-data">
                        <h4 class="w3-text-black"><i class="fa fa-palette w3-margin-right"></i><?=_CONTROLCENTER_COLOR;?></h4>
                        <p class="w3-pale-yellow w3-border w3-padding w3-round-large" id="2" style="display: none" onclick="showHelp(2)"><i class="fa fa-info-circle w3-text-blue w3-large w3-cell-middle"></i> <i class="w3-text-black"><small><?=_CONTROLCENTER_COLOR_INFO;?></small></i></p>
                        <table class="w3-table w3-small">
                            <tr>
                                <td><label for="color"><?=_CONTROLCENTER_COLOR;?></label></td>
                                <td><input type="color" id="color" name="color" value="<?=$_SESSION['color'];?>"></td>
                            </tr>
                            <tr>
                                <td><input type="hidden" name="op" value="color"></td>
                                <td><button class="w3-button w3-border w3-round-large w3-right" type="submit"><?=_FORM_START;?></button></td>
                            </tr>
                        </table>
                    </form>
                </div>

                <div class="w3-container w3-card w3-white w3-margin-bottom w3-round-large">
                    <div class="w3-tag w3-white w3-right w3-margin-top" onclick="showHelp(3)"><i class="fa fa-question-circle"></i></div>
                    <form name="stop" id="stop" action="/control/" method="POST" enctype="multipart/form-data">
                    <h4 class="w3-text-black"><i class="fa fa-stop w3-margin-right"></i><?=_FORM_STOP;?></h4>
                    <p class="w3-pale-yellow w3-border w3-padding w3-round-large" id="3" style="display: none" onclick="showHelp(3)"><i class="fa fa-info-circle w3-text-blue w3-large w3-cell-middle"></i> <i class="w3-text-black"><small><?=_CONTROLCENTER_STOP_INFO;?></small></i></p>
                        <table class="w3-table w3-small">
                            <tr>
                                <td><input type="hidden" name="op" value="stop"></td>
                                <td><button class="w3-button w3-border w3-round-large w3-right" type="submit"><?=_FORM_STOP;?></button></td>
                            </tr>
                        </table>
                    </form>
                </div>
            </div>

            <div class="w3-container w3-card w3-white w3-margin-bottom w3-round-large" <?=(Cfg::read('air','attached')) == 'yes' ? '' : 'hidden';?>>
                <h3><i class="fa fa-wind w3-margin-right w3-padding-small"></i><?=_AIR;?></h3>
                <div class="w3-container w3-card w3-white w3-margin-bottom w3-pale-red w3-round-large">
                    <p><i class="fa fa-warning w3-text-red w3-xlarge w3-cell-middle"></i> <i class="w3-text-black"><small><?=_CONTROLCENTER_AIR_INFO;?></small></i></p>
                </div>

                <div class="w3-container w3-card w3-white w3-margin-bottom w3-round-large">
                    <form name="air" id="air" action="/control/" method="POST" enctype="multipart/form-data">
                        <h4 class="w3-text-black"><i class="fa fa-play w3-margin-right"></i><i class="fa fa-stop w3-margin-right"></i><?=_FORM_STOP;?></h4>
                        <table class="w3-table w3-small">
                            <tr>
                                <td><input type="hidden" name="op" value="gpio_toggle"><input type="hidden" name="element" value="air"></td>
                                <td class="w3-right"><button class="w3-button w3-border w3-round-large" type="submit" name="start" id="start" <?=(Template::relayStatus('air')) == 'green' ? 'disabled' : '';?>><?=_FORM_START;?></button>&nbsp;&nbsp;<button class="w3-button w3-border w3-round-large" type="submit" name="stop" id="stop" <?=(Template::relayStatus('air')) == 'red' ? 'disabled' : '';?>><?=_FORM_STOP;?></button></td>
                            </tr>
                        </table>
                    </form>
                </div>
            </div>

            <div class="w3-container w3-card w3-white w3-margin-bottom w3-round-large" <?=(Cfg::read('water','attached')) == 'yes' ? '' : 'hidden';?>>
                <h3><i class="fa fa-water w3-margin-right w3-padding-small"></i><?=_WATER;?></h3>
                <div class="w3-container w3-card w3-white w3-margin-bottom w3-pale-red w3-round-large">
                    <p><i class="fa fa-warning w3-text-red w3-xlarge w3-cell-middle"></i> <i class="w3-text-black"><small><?=_CONTROLCENTER_WATER_INFO;?></small></i></p>
                </div>

                <div class="w3-container w3-card w3-white w3-margin-bottom w3-round-large">
                    <form name="water" id="water" action="/control/" method="POST" enctype="multipart/form-data">
                        <h4 class="w3-text-black"><i class="fa fa-play w3-margin-right"></i><i class="fa fa-stop w3-margin-right"></i><?=_FORM_STOP;?></h4>
                        <table class="w3-table w3-small">
                            <tr>
                                <td><input type="hidden" name="op" value="gpio_toggle"><input type="hidden" name="element" value="water"></td>
                                <td class="w3-right"><button class="w3-button w3-border w3-round-large" type="submit" name="start" id="start" <?=(Template::relayStatus('water')) == 'green' ? 'disabled' : '';?>><?=_FORM_START;?></button>&nbsp;&nbsp;<button class="w3-button w3-border w3-round-large" type="submit" name="stop" id="stop" <?=(Template::relayStatus('water')) == 'red' ? 'disabled' : '';?>><?=_FORM_STOP;?></button></td>
                            </tr>
                        </table>
                    </form>
                </div>
            </div>
            <!-- End Right Column -->
        </div>
        <!--suppress HtmlUnknownTarget -->
<script src="<?=Bootstrap::TEMPLATEDIRHTML;?>/js/showhelp.js"></script>

        <script>
            function validlightnum(a) {
                return !(a < 0 || a > 255);
            }

            function grow() {
                document.getElementById("red").value = "<?=$GLOBALS["grow"][0];?>";
                document.getElementById("green").value = "<?=$GLOBALS["grow"][1];?>";
                document.getElementById("blue").value = "<?=$GLOBALS["grow"][2];?>";
                document.getElementById("white").value = "<?=$GLOBALS["grow"][3];?>";
                showColor(<?=$GLOBALS["grow"][0];?>,<?=$GLOBALS["grow"][1];?>,<?=$GLOBALS["grow"][2];?>);
            }

            function bloom() {
                document.getElementById("red").value = "<?=$GLOBALS["bloom"][0];?>";
                document.getElementById("green").value = "<?=$GLOBALS["bloom"][1];?>";
                document.getElementById("blue").value = "<?=$GLOBALS["bloom"][2];?>";
                document.getElementById("white").value = "<?=$GLOBALS["bloom"][3];?>";
                showColor(<?=$GLOBALS["bloom"][0];?>,<?=$GLOBALS["bloom"][1];?>,<?=$GLOBALS["bloom"][2];?>);
            }

            function reset() {
                document.getElementById("red").value = "<?=$_SESSION['defaultRed'];?>";
                document.getElementById("green").value = "<?=$_SESSION['defaultGreen'];?>";
                document.getElementById("blue").value = "<?=$_SESSION['defaultBlue'];?>";
                document.getElementById("white").value = "<?=$_SESSION['defaultWhite'];?>";
            }

            function showColor(r,g,b) {
                let x = document.getElementById("colorbox");
                x.style.backgroundColor = 'rgb(' + [r,g,b].join(',') + ')';
            }
        </script>

        <!-- End Grid -->
    </div>
    <!-- End Page Container -->
</div>
