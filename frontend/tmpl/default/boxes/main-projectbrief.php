<div class="w3-container w3-card w3-white w3-margin-bottom w3-round-large">
    <h6><i class="fa fa-folder w3-margin-right w3-padding-small"></i><?=_PLANT_ACTUALPROJECT;?></h6>
    <div class="w3-container w3-card w3-white w3-margin-bottom w3-round-large">
        <table class="w3-table w3-striped w3-margin-bottom w3-tiny">
            <tr>
                <td><i class="fa fa-seedling w3-margin-right w3-padding-small"></i></td>
                <td><b><?=_PLANT_SPECIES;?></b></td>
                <td class="w3-right"><?=Cfg::read('plant','species');?></td>
            </tr>
            <tr>
                <td><i class="fa fa-calendar w3-margin-right w3-padding-small"></i></td>
                <td><b><?=_PROJECT_ACTIVE;?></b></td>
                <td class="w3-right"><?=Project::daysrunning(Cfg::read('plant','startdate'), date("Y-m-d"));?> <?=_PROJECT_DAYSRUNNING;?></td>
            </tr>
            <tr>
                <td><i class="fa fa-leaf w3-margin-right w3-padding-small"></i></td>
                <td><b><?=_PROJECT_GROWPHASE;?></b></td>
                <td class="w3-right"><?=Project::growPhase()?></td>
            </tr>
            <tr>
                <td><i class="fa fa-sun w3-padding-small"></i></td>
                <td><b><?=_PLANT_DAYTIME;?></b></td>
                <td class="w3-right"><?=Project::dayTime();?></td>
            </tr>
        </table>
    </div>
</div>