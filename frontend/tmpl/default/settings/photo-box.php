<div class="w3-container w3-card w3-white w3-margin-bottom w3-round-large">
    <h3><i class="fa fa-image w3-margin-right w3-padding-small"></i><?=_PHOTO;?></h3>

    <div class="w3-container w3-card w3-white w3-margin-bottom w3-round-large">
        <div class="w3-tag w3-white w3-right w3-margin-top" onclick="showHelp(1)"><i class="fa fa-question-circle"></i></div>
        <form name="camera" action="/settings/<?=$GLOBALS['settingsBox'];?>" onsubmit="return confirm('<?=_FORM_WARNING_SURE;?>');" method="POST" enctype="multipart/form-data">
        <h4><i class="fa fa-camera w3-margin-right"></i><?=_PHOTO_CAMERA;?></h4>
        <p class="w3-pale-yellow w3-border w3-padding w3-round-large" id="1" style="display: none" onclick="showHelp(1)"><i class="fa fa-info-circle w3-text-blue w3-large w3-cell-middle"></i> <i><small><?=_PHOTO_CAMERA_INFO;?></small></i></p>
        <table class="w3-table w3-small">
            <tr>
                <td><label for="camera[attached]"><?=_PHOTO_CAMERA_ATTACHED;?></label></td>
                <td>
                    <select class="w3-right" name="camera[attached]" id="camera[attached]">
                        <option value="yes" <?=(Cfg::read('camera','attached') === true) ? "selected" : "";?>><?=_FORM_YES;?></option>
                        <option value="no" <?=(Cfg::read('camera','attached') === false) ? "selected" : "";?>><?=_FORM_NO;?></option>
                    </select>
                </td>
            </tr>
            <tr>
                <td><label for="camera[shutter]"><?=_PHOTO_SHUTTER;?></label></td>
                <td><input class="w3-right" type="number" style="width: 80px;" value="<?=Cfg::read('camera','shutter');?>" id="camera[shutter]" name="camera[shutter]" required></td>
            </tr>
            <tr>
                <td><label for="camera[quality]"><?=_PHOTO_QUALITY;?></label></td>
                <td><input class="w3-right" type="number" style="width: 80px;" value="<?=Cfg::read('camera','quality');?>" id="camera[quality]" name="camera[quality]" required></td>
            </tr>
            <tr>
                <td><label for="camera[timelapse_width]"><?=_PHOTO_WIDTH;?></label></td>
                <td><input class="w3-right" type="number" style="width: 80px;" value="<?=Cfg::read('camera','timelapse_width');?>" id="camera[timelapse_width]" name="camera[timelapse_width]" required></td>
            </tr>
            <tr>
                <td><label for="camera[timelapse_height]"><?=_PHOTO_HEIGHT;?></label></td>
                <td><input class="w3-right" type="number" style="width: 80px;" value="<?=Cfg::read('camera','timelapse_height');?>" id="camera[timelapse_height]" name="camera[timelapse_height]" required></td>
            </tr>
            <tr>
                <td><label for="camera[frontend_width]"><?=_PHOTO_FRONTEND_WIDTH;?></label></td>
                <td><input class="w3-right" type="number" style="width: 80px;" value="<?=Cfg::read('camera','frontend_width');?>" id="camera[frontend_width]" name="camera[frontend_width]" required></td>
            </tr>
            <tr>
                <td><label for="camera[frontend_height]"><?=_PHOTO_FRONTEND_HEIGHT;?></label></td>
                <td><input class="w3-right" type="number" style="width: 80px;" value="<?=Cfg::read('camera','frontend_height');?>" id="camera[frontend_height]" name="camera[frontend_height]" required></td>
            </tr>
            <tr>
                <td><label for="camera[awb]"><?=_PHOTO_AWB;?></label></td>
                <td><input class="w3-right" type="text" style="width: 80px;" value="<?=Cfg::read('camera','awb');?>" id="camera[awb]" name="camera[awb]" required></td>
            </tr>
            <tr>
                <td><label for="camera[camera]"><?=_PHOTO_CAMERAAPP;?></label></td>
                <td><input class="w3-right w3-margin-bottom" type="text" style="width: 120px;" value="<?=Cfg::read('camera','camera');?>" id="camera[camera]" name="camera[camera]" required></td>
            </tr>
            <tr>
                <td><input type="hidden" name="op" value="updateConfig"></td>
                <td><button class="w3-button w3-border w3-round-large w3-right w3-margin-bottom" type="submit"><?=_FORM_CHANGE;?></button></td>
            </tr>
        </table>
        </form>
    </div>
</div>
