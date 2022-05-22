<!-- Page Container -->
<div class="w3-content w3-margin-top" style="max-width:1400px;" xmlns="http://www.w3.org/1999/html">

    <!-- The Grid -->
    <div class="w3-row-padding">

        <?php include(Bootstrap::TEMPLATEDIR."/left-column-box.php");?>

        <!-- Right Column -->
        <div class="w3-twothird w3-margin-top">

    <div class="w3-container w3-card w3-white w3-margin-bottom w3-margin-top w3-round-large">
        <h1><i class="fa fa-unlock-keyhole w3-margin-right"></i><?=_LOGIN;?></h1>
    </div>

    <div class="w3-container w3-card w3-white w3-margin-bottom w3-padding-top-24 w3-round-large">
        <form name="login" action="/login/" method="POST" enctype="multipart/form-data">

            <table class="w3-table">
                <tr>
                    <td><label for="user"><?=_LOGIN_USERNAME;?></label></td>
                    <td> <input class="w3-right" type="text" id="user" name="user" required></td>
                </tr>
                <tr>
                    <td><label for="pass"><?=_LOGIN_PASSWORD;?></label></td>
                    <td> <input class="w3-right" type="password" id="pass" name="pass" required></td>
                </tr>
                <tr>
                    <td><input type="hidden" name="op" value="login"></td>
                    <td><input class="w3-right w3-margin-bottom" type="submit" value="<?=_FORM_LOGIN;?>"></td>
                </tr>
            </table>
        </form>
    </div>
    <!-- End Right Column -->
</div>
        <!-- End Grid -->
    </div>
    <!-- End Page Container -->
</div>
