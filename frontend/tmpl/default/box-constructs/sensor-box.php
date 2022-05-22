    <div class="w3-container w3-card w3-white w3-margin-bottom w3-round-large">
        <div class="w3-tag w3-white w3-right w3-margin-top" onclick="showHelp('##id##')"><i class="fa fa-question-circle"></i></div>
        <form name="sensors" action="/settings/sensors" onsubmit="return confirm('##confirm##');" method="POST" enctype="multipart/form-data">
            <h4><i class="fa fa-microchip w3-margin-right"></i>##helpername##</h4>
            <p class="w3-pale-yellow w3-border w3-padding w3-round-large" id="##id##" style="display: none" onclick="showHelp('##id##')"><i class="fa fa-info-circle w3-text-blue w3-large w3-cell-middle"></i> <i><small>##description##</small></i></p>
            <table class="w3-table w3-striped w3-small">
                <tr>
                    <td>##delivers##:</td>
                    <td>##delivertype##</td>
                </tr>
                <tr>
                    <td><label for="cfg[option]">##option_name##</label></td>
                    <td><!--suppress HtmlUnknownAttribute -->
                        <input class="w3-right" type="number" ##value## id="cfg[option]" name="cfg[option]" required></td>
                </tr>
                ##modifierbox##
                <tr>
                    <td><input type="hidden" name="cfg[helper]" value="##helper##"><input type="hidden" name="cfg[module]" value="##module##"><input type="hidden" name="op" value="updateSensorCfg"></td>
                    <td><br><button class="w3-button w3-round-large w3-border w3-right w3-margin-bottom" type="submit">##formsend##</button></td>
                </tr>
            </table>
        </form>
    </div>