<div class="w3-container w3-padding-top-48"></div>
<footer class="w3-bottom w3-small">
    <div class="w3-container w3-black w3-center">
    <p><i class="fa fa-seedling"></i>
    <small>
        <?=_EXECUTION_TIME;?>: <?php
        $runtime = round(((microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"]) * 1000), 4);
        if ($runtime > 1000) { echo round($runtime / 1000, 2) ."s"; } else { echo $runtime ."ms"; }
        ?>
    </small>
    </p>
    </div>
</footer>