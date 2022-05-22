<!-- Page Container -->
<div class="w3-content w3-margin-top" style="max-width:1400px;" xmlns="http://www.w3.org/1999/html">

    <!-- The Grid -->
    <div class="w3-row-padding">

        <?php include(Bootstrap::TEMPLATEDIR."/left-column-box.php");?>

        <!-- Right Column -->
        <div class="w3-twothird w3-margin-top">

        <div class="w3-container w3-card w3-white w3-margin-bottom w3-margin-top w3-round-large">
            <h1><i class="fa fa-paw w3-margin-right"></i><?=_FRONTEND_SANDBOX;?></h1>
        </div>

        <div class="w3-container w3-card w3-white w3-margin-bottom w3-round-large">
            <h3><i class="fa fa-info-circle w3-margin-right w3-padding-small"></i><?=_MESSAGE_INFO;?></h3>

            <div class="w3-container w3-card w3-white w3-margin-bottom w3-margin-top w3-round-large">
                <p><a href="/fail/">trigger error</a></p>
            </div>

            <div class="w3-container w3-card w3-white w3-margin-bottom w3-round-large">
                <h4><i class="fa fa-chart-area w3-margin-right"></i><?=_LIGHT_SPECTRUM;?></h4>
                <canvas id="spectrum" width="100%" height="100" class="w3-small"></canvas>
            </div>

            <div class="w3-container w3-card w3-white w3-margin-bottom w3-margin-top w3-round-large">
                <p><?=_PLACEHOLDER_LONG;?></p>
            </div>
        </div>
    <!-- End Right Column -->
    </div>
        <!-- End Grid -->
    </div>
    <!-- End Page Container -->
</div>
