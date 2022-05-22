    <!-- Left Column -->
    <div class="w3-third w3-margin-top">
        <div class="w3-white w3-card w3-margin-top w3-round-large">
            <div class="w3-display-container">
                <div class="w3-image w3-xxxlarge">
                    <i class="fa-regular fa-seedling w3-margin-right w3-padding-small w3-text-green"></i>
                </div>
                <div class="w3-display-bottomright w3-container">
                    <h2 class="w3-display-bottomright w3-container"><?=_APP_TITLE;?></h2><small>(<?=_VERSION;?> <?=Cfg::read('default','version');?>)</small>
                </div>
            </div>
        </div>

        <?=(isset($messageBox)) ? $messageBox : ""; ?>

    </div>
    <!-- End Left Column -->