<div class="w3-container w3-card w3-white w3-margin-bottom w3-round-large">
    <h6><i class="fa fa-folder w3-margin-right w3-padding-small"></i><?=_PLANT_ACTUALPROJECT;?></h6>
    <div class="w3-container w3-card w3-white w3-margin-bottom w3-round-large">
        <table class="w3-table w3-tiny">
            <tr>
                <td><b><?=_PLANT_SPECIES;?></b><br><?=Cfg::read('plant','species');?></td>
                <td><b><?=_PROJECT_ACTIVE;?></b><br><?=Project::daysrunning(Cfg::read('plant','startdate'), date("Y-m-d"));?> <?=_PROJECT_DAYSRUNNING;?></td>
                <td><b><?=_PROJECT_GROWPHASE;?></b><br><?=Project::growPhase()?></td>
                <td><b><?=_PLANT_DAYTIME;?></b><br><?=Project::dayTime();?></td>
            </tr>
        </table>
    </div>
</div>