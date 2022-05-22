<div class="w3-container w3-card w3-white w3-margin-bottom w3-round-large">
    <h4><i class="fa fa-magnifying-glass w3-margin-right w3-padding-small"></i><?=_SENSORS;?></h4>

    <div class="w3-container w3-card w3-white w3-margin-bottom w3-round-large">
        <table class="w3-table w3-striped w3-margin-bottom w3-small">
            <?=Sensors::sensorDataSHM();?>
        </table>
    </div>
</div>