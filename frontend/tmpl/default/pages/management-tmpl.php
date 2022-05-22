<!-- Page Container -->
<div class="w3-content w3-margin-top" style="max-width:1400px;" xmlns="http://www.w3.org/1999/html">

    <!-- The Grid -->
    <div class="w3-row-padding">

        <?php include(Bootstrap::TEMPLATEDIR."/left-column-box.php");?>

        <!-- Right Column -->
        <div class="w3-twothird w3-margin-top">

            <div class="w3-container w3-card w3-white w3-margin-bottom w3-margin-top w3-round-large">
            <h1><i class="fa fa-folder-open w3-margin-right"></i><?=_PROJECT_MANAGEMENT;?></h1>
        </div>

        <div class="w3-container w3-card w3-white w3-margin-bottom w3-round-large">
            <h3><i class="fa fa-seedling w3-margin-right w3-padding-small"></i><?=_PROJECT_PLANT;?></h3>
            
            <div class="w3-container w3-card w3-white w3-margin-bottom w3-round-large" <?=Bootstrap::HIDDEN?>>
                <div class="w3-tag w3-white w3-right w3-margin-top" onclick="showHelp(1)"><i class="fa fa-question-circle"></i></div>
                <form name="phase" action="/settings/" onsubmit="return confirm('<?=_FORM_WARNING_SURE;?>');" method="POST" enctype="multipart/form-data">
                    <h4><i class="fa fa-wrench w3-margin-right"></i><?=_PROJECT_GROWPHASE;?></h4>
                    <p class="w3-pale-yellow w3-border w3-padding w3-round-large" id="1" style="display: none" onclick="showHelp(1)"><i class="fa fa-info-circle w3-text-blue w3-large w3-cell-middle"></i> <i class="w3-text-black"><small><?=_PROJECT_GROWPHASE_INFO;?></small></i></p>
                    <table class="w3-table">
                        <tr>
                            <td><label for="plant[phase]"><?=_PROJECT_GROWPHASE;?></label></td>
                            <td>
                                <select class="w3-right" name="plant[phase]" id="plant[phase]">
                                    <option value="grow" <?=(Cfg::read('plant','phase') == "grow") ? "selected" : "";?>><?=_PLANT_GROW;?></option>
                                    <option value="bloom" <?=(Cfg::read('plant','phase') == "bloom") ? "selected" : "";?>><?=_PLANT_BLOOM;?></option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><input type="hidden" name="op" value="updateConfig"><input type="hidden" name="origin" value="management"></td>
                            <td><input class="w3-right w3-margin-bottom" type="submit" value="<?=_FORM_CHANGE;?>"></td>
                        </tr>
                    </table>
                </form>
            </div>

            <div class="w3-container w3-card w3-white w3-margin-bottom w3-round-large" <?=Bootstrap::HIDDEN;?>>
                <form name="endproject" action="/management/" onsubmit="return confirm('<?=_PROJECT_WARNING_ENDPROJECT;?>');" method="POST" enctype="multipart/form-data">
                <h4><i class="fa fa-check w3-margin-right"></i><?=_PROJECT_ENDPROJECT;?></h4>
                <p class="w3-pale-red w3-border w3-padding w3-round-large"><i class="fa fa-exclamation-triangle w3-text-red"></i> <?=_PROJECT_WARNING_ENDPROJECT;?><br><i> <strong><?=_PROJECT_WARNING_ENDPROJECT_LONG;?></strong></i></p>
                    <table class="w3-table">
                        <tr>
                            <td><label for="projectSuccess"><?=_MESSAGE_SUCCESSFUL;?></label></td>
                            <td><select class="w3-right" name="projectSuccess" id="projectSuccess">
                                    <option value="1"><?=_FORM_YES;?></option>
                                    <option value="0"><?=_FORM_NO;?></option>
                                </select></td>
                        </tr>
                        <tr>
                            <td><label for="projectNote"><?=_PROJECT_ENDNOTE;?></label></td>
                            <td><textarea class="w3-margin-bottom w3-right" id="projectNote" name="projectNote" rows="4" cols="20" required></textarea></td>
                        </tr>
                        <tr>
                            <td><input type="hidden" name="op" value="endProject"></td>
                            <td><input class="w3-right w3-margin-bottom" type="submit" value="<?=_PROJECT_ENDPROJECT;?>"></td>
                        </tr>
                    </table>
                </form>
            </div>

            <div class="w3-container w3-card w3-white w3-margin-bottom w3-round-large" <?=(Bootstrap::HIDDEN == "") ? "hidden" : "";?>>
                <form name="newproject" id="newproject" action="/management/" onsubmit="return confirm('<?=_FORM_WARNING_SURE;?>');" method="POST" enctype="multipart/form-data">
                    <h4><i class="fa fa-circle-plus w3-margin-right"></i><?=_PROJECT_NEWPROJECT;?></h4>
                    <table class="w3-table">
                        <tr>
                            <td><label for="plant[species]"><?=_PLANT_SPECIES;?></label></td>
                            <td><input class="w3-right" type="text" id="plant[species]" name="plant[species]" placeholder="<?=_PROJECT_NEWPROJECT_PLACEHOLDER_PLANT;?>" required></td>
                        </tr>
                        <tr>
                            <td><label for="light[day]"><?=_LIGHT_DAYLENGTH;?></label></td>
                            <td><input class="w3-right" type="number" style="width: 80px;" value="<?=Cfg::read('light','day') / 60 / 60;?>" id="light[day]" name="light[day]" onkeyup='if(!validdaynum(this.value)) this.value="24";' onchange='if(!validdaynum(this.value)) this.value="24"; nightLength(this,"newproject")' required></td>
                        </tr>
                        <tr>
                            <td><label for="light[night]"><?=_LIGHT_NIGHTLENGTH;?></label></td>
                            <td><input class="w3-right" type="number" style="width: 80px;" value="<?=Cfg::read('light','night') / 60 / 60;?>" id="light[night]" name="light[night]" disabled="disabled"></td>
                        </tr>
                        <tr>
                            <td><label for="project[note]"><?=_PROJECT_STARTNOTE;?></label></td>
                            <td><textarea class="w3-margin-bottom w3-right" id="project[note]" name="project[note]" rows="4" cols="20" required></textarea></td>
                        </tr>
                        <tr>
                            <td><label for="plant[phase]"><?=_PROJECT_GROWPHASE;?></label></td>
                            <td>
                                <select class="w3-right" name="plant[phase]" id="plant[phase]">
                                    <option value="grow"><?=_PLANT_GROW;?></option>
                                    <option value="bloom"><?=_PLANT_BLOOM;?></option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><input type="hidden" name="op" value="newProject"></td>
                            <td><input class="w3-right w3-margin-bottom" type="submit" value="<?=_FORM_START;?>"></td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>

            <!-- End Right Column -->
        </div>
        <!--suppress HtmlUnknownTarget -->
    <script src="<?=Bootstrap::TEMPLATEDIRHTML;?>/js/management.js"></script>
        <!--suppress HtmlUnknownTarget -->
    <script src="<?=Bootstrap::TEMPLATEDIRHTML;?>/js/showhelp.js"></script>
        <!-- End Grid -->
    </div>
    <!-- End Page Container -->
</div>
