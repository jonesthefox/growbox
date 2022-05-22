<?php
$messages = array('Annular containment beam decalibrated!', 'All transporters offline!', 'Darmok and jalad at tanagra! His arms are wide!', 'Warp core break imminent!', 'Holodecks offline!', 'Forcefield malfunction!');
$message = $messages[rand(0, count($messages) -1)];
?>
<style>
    body, h1, h2, h3, h4, p, a { color: #e0e2f4; }

    body,
    p { font: normal 20px/1.25rem monospace; }
    h1 { font: normal 2.75rem/1.05em monospace; }
    h2 { font: normal 2.25rem/1.25em monospace; }
    h3 { font: lighter 1.5rem/1.25em monospace; }
    h4 { font: lighter 1.0rem/1.2222222em monospace; }
    small { font: lighter 0.8rem/1.0em monospace; }

    /* body { background: #a70404; } */
    body { background: #00f; }

    .container { width: 92%; margin: auto; max-width: 1200px; }

    .bsod { padding-top: 2%;
    .title { margin-bottom: 50px; }
    }
</style>
<main class="bsod container">
    <h1 class="title"><span>:(</span></h1>
    <h3><?=$message;?></h3>
    <h4><?=$error;?></h4>
    <small>Runtime: <?php
        $runtime = round(((microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"]) * 1000), 4);
        if ($runtime > 1000) { echo round($runtime / 1000, 2) ."s"; } else { echo $runtime ."ms"; }
        ?></small>
</main>