<div class="w3-container w3-card w3-white w3-margin-bottom w3-round-large">
    <h3><i class="fa fa-desktop w3-margin-right w3-padding-small"></i><?=_FRONTEND;?></h3>

    <div class="w3-container w3-card w3-white w3-margin-bottom w3-round-large">
        <div class="w3-tag w3-white w3-right w3-margin-top" onclick="showHelp(1)"><i class="fa fa-question-circle"></i></div>
        <form name="frontend" action="/settings/<?=$GLOBALS['settingsBox'];?>" onsubmit="return confirm('<?=_FORM_WARNING_SURE;?>');" method="POST" enctype="multipart/form-data">
            <h4><i class="fa fa-palette w3-margin-right"></i><?=_FRONTEND_WEBFRONTEND;?></h4>
            <p class="w3-pale-yellow w3-border w3-padding w3-round-large" id="1" style="display: none" onclick="showHelp(1)"><i class="fa fa-info-circle w3-text-blue w3-large w3-cell-middle"></i> <i><small><?=_FRONTEND_WEBFRONTEND_INFO;?></small></i></p>
            <table class="w3-table w3-small">
                <tr>
                    <td><label for="web[language]"><?=_FRONTEND_LANGUAGE;?></label></td>
                    <td>
                        <select class="w3-right" name="web[language]" id="web[language]">
                            <?=Template::listLanguages();?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><label for="web[template]"><?=_FRONTEND_THEME;?></label></td>
                    <td>
                        <select class="w3-right" name="web[template]" id="web[template]">
                            <?=Template::listTemplates();?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><label for="web[chartcount]"><?=_FRONTEND_CHARTCOUNT;?><br><small>(<?=_FRONTEND_CHARTCOUNT_INFO;?>)</small></label></td>
                    <td><input class="w3-right" type="number" style="width: 80px;" value="<?=Cfg::read('web','chartcount');?>" id="web[chartcount]" name="web[chartcount]" required></td>
                </tr>
                <tr>
                    <td><label for="web[logcount]"><?=_FRONTEND_LOGCOUNT;?><br><small>(<?=_FRONTEND_LOGCOUNT_INFO;?>)</small></label></td>
                    <td><input class="w3-right" type="number" style="width: 80px;" value="<?=Cfg::read('web','logcount');?>" id="web[logcount]" name="web[logcount]" required></td>
                </tr>
                <tr>
                    <td><i class="fa fa-paw w3-margin-right w3-large"></i><label for="web[sandbox]"><?=_FRONTEND_SANDBOX;?><br> <small>(<?=_FRONTEND_SANDBOX_INFO;?>)</small></label></td>
                    <td>
                        <select class="w3-right w3-margin-bottom" name="web[sandbox]" id="web[sandbox]">
                            <option value="yes" <?=(Cfg::read('web','sandbox') === true) ? "selected" : "";?>><?=_FORM_YES;?></option>
                            <option value="no" <?=(Cfg::read('web','sandbox') === false) ? "selected" : "";?>><?=_FORM_NO;?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><input type="hidden" name="op" value="updateConfig"></td>
                    <td><button class="w3-button w3-border w3-round-large w3-right w3-margin-bottom" type="submit"><?=_FORM_CHANGE;?></button></td>
                </tr>
            </table>
        </form>
    </div>
</div>
