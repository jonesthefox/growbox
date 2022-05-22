<!-- Page Container -->
<div class="w3-content w3-margin-top" style="max-width:1400px;" xmlns="http://www.w3.org/1999/html">

    <!-- The Grid -->
    <div class="w3-row-padding">

        <?php include(Bootstrap::TEMPLATEDIR."/left-column-box.php");?>

        <!-- Right Column -->
    <div class="w3-twothird w3-margin-top">

      <div class="w3-container w3-card w3-white w3-margin-bottom w3-margin-top w3-round-large">
          <h1><i class="fa fa-wrench w3-margin-right"></i><?=_SETTINGS;?></h1>
      </div>

          <?php include(Bootstrap::TEMPLATEDIR."/settings/".$GLOBALS['settingsBox']."-box.php"); /* $GLOBALS['settingsBox'] defined in index.php */ ?>

        <!-- End Right Column -->
    </div>
        <!--suppress HtmlUnknownTarget -->
<script src="<?=Bootstrap::TEMPLATEDIRHTML;?>/js/showhelp.js?v=1"></script>
        <!-- End Grid -->
    </div>
    <!-- End Page Container -->
</div>
