<!-- Page Container -->
<div class="w3-content w3-margin-top" style="max-width:1400px;" xmlns="http://www.w3.org/1999/html">

    <!-- The Grid -->
    <div class="w3-row-padding">

        <?php include(Bootstrap::TEMPLATEDIR."/left-column-box.php");?>

        <!-- Right Column -->
        <div class="w3-twothird w3-margin-top">

    <div class="w3-container w3-card w3-white w3-margin-bottom w3-margin-top w3-round-large">
        <h1><i class="fa fa-clipboard-list w3-margin-right"></i><?=_LOGGING;?></h1>
    </div>

    <div class="w3-container w3-card w3-white w3-margin-bottom w3-round-large">
        <h3><?=_LOGGING_LOG;?></h3>
        <table class="w3-table w3-striped w3-bordered w3-margin-bottom w3-tiny">
        <?=Template::generateLog(TemplateLog::Project,intval(Cfg::read('web','logcount')));?>
        </table>
    </div>

    <div class="w3-container w3-card w3-white w3-margin-bottom w3-round-large">
        <h3><?=_LOGGING_CRON;?></h3>
        <table class="w3-table w3-striped w3-bordered w3-margin-bottom w3-tiny">
        <?=Template::generateLog(TemplateLog::Cron,intval(Cfg::read('web','logcount')));?>
        </table>
    </div>
    <!-- End Right Column -->
</div>
        <!-- End Grid -->
    </div>
    <!-- End Page Container -->
</div>
