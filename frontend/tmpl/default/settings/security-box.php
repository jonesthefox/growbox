<div class="w3-container w3-card w3-white w3-margin-bottom w3-round-large">
    <h3><i class="fa fa-key w3-margin-right w3-padding-small"></i><?=_LOGIN_SECURITY;?></h3>

    <div class="w3-container w3-card w3-margin-bottom w3-round-large">
        <div class="w3-tag w3-white w3-right w3-margin-top" onclick="showHelp(1)"><i class="fa fa-question-circle"></i></div>
        <form name="humidity" action="/settings/<?=$GLOBALS['settingsBox'];?>" onsubmit="return confirm('<?=_FORM_WARNING_SURE;?>');" method="POST" enctype="multipart/form-data">
            <h4><i class="fa fa-unlock-keyhole w3-margin-right"></i><?=_LOGIN_PASSWORD;?></h4>
            <p class="w3-pale-yellow w3-border w3-padding w3-round-large" id="1" style="display: none" onclick="showHelp(1)"><i class="fa fa-info-circle w3-text-blue w3-large w3-cell-middle"></i> <i><small><?=_LOGIN_PASSWORD_INFO;?></small></i></p>
            <table class="w3-table w3-small">
                <tr>
                    <td><label for="pass"><?=_LOGIN_PASSWORD;?></label></td>
                    <td><input class="w3-right w3-margin-bottom" type="password" size="15" id="pass" name="pass" required></td>
                </tr>
                <tr>
                    <td><input type="hidden" name="op" value="changePassword"></td>
                    <td><button class="w3-button w3-border w3-round-large w3-right w3-margin-bottom" type="submit"><?=_FORM_CHANGE;?></button></td>
                </tr>
            </table>
        </form>
    </div>
</div>
