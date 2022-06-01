<!-- links sit on top -->
<div class="w3-top">
    <div class="w3-bar w3-black w3-tiny">
        <a href="/" class="w3-bar-item w3-button"><i class="fa fa-home"></i></a>

        <a href="/charts/" class="w3-bar-item w3-button"><i class="fa fa-chart-area"></i></a>

        <div class="w3-dropdown-hover">
            <button class="w3-button">
                <i class="fa fa-folder"></i> <i class="fa fa-caret-down"></i>
            </button>
            <div id="projectsDropdown" class="w3-dropdown-content w3-bar-block w3-card-4">
                <a href="/archive/" class="w3-bar-item w3-button"><i class="fa fa-folder-tree"></i> <?=_PROJECTARCHIVE;?></a>
                <a href="/management/" class="w3-bar-item w3-button"><i class="fa fa-folder-open"></i> <?=_PROJECT_MANAGEMENT;?></a>
            </div>
        </div>

        <!--<a href="javascript:window.location.reload()" class="w3-bar-item w3-button w3-right"><i class="fa fa-sync-alt"></i></a>-->

        <a href="/logout/" class="w3-bar-item w3-button w3-right"><i class="fa fa-right-from-bracket"></i></a>

        <?=(Cfg::read('web', 'sandbox') === true) ? '<a href="/sandbox.php" class="w3-bar-item w3-button w3-right"><i class="fa fa-paw"></i></a>' : ''; ?>

        <a href="/log/" class="w3-bar-item w3-button w3-right"><i class="fa fa-clipboard-list"></i></a>

        <a href="/control/" class="w3-bar-item w3-button w3-right">
            <i class="fa fa-lightbulb w3-text-<?=Template::lightStatus();?>"></i>
            <i class="fa fa-fan w3-text-<?=Template::relayStatus('air');?>"></i>
            <i class="fa fa-thermometer-half w3-text-<?=Template::relayStatus('temperature');?>"></i>
            <i class="fa fa-faucet w3-text-<?=Template::relayStatus('water');?>"></i>
        </a>

        <div class="w3-dropdown-hover w3-right">
            <button class="w3-button">
                <i class="fa fa-cog"></i> <i class="fa fa-caret-down"></i>
            </button>
            <div id="settingsDropdown" class="w3-dropdown-content w3-bar-block w3-card-4">
                <a href="/settings/sensors" class="w3-bar-item w3-button"><i class="fa fa-microchip"></i> <?=_SENSORS;?></a>
                <a href="/settings/light" class="w3-bar-item w3-button"><i class="fa fa-sun"></i> <?=_LIGHT;?></a>
                <a href="/settings/water" class="w3-bar-item w3-button"><i class="fa fa-water"></i> <?=_WATER;?></a>
                <a href="/settings/air" class="w3-bar-item w3-button"><i class="fa fa-wind"></i> <?=_AIR;?></a>
                <a href="/settings/temperature" class="w3-bar-item w3-button"><i class="fa fa-thermometer-half"></i> <?=_TEMPERATURE;?></a>
                <a href="/settings/photo" class="w3-bar-item w3-button"><i class="fa fa-image"></i> <?=_PHOTO;?></a>
                <a href="/settings/frontend" class="w3-bar-item w3-button"><i class="fa fa-desktop"></i> <?=_FRONTEND;?></a>
                <a href="/settings/network" class="w3-bar-item w3-button"><i class="fa fa-wifi"></i> <?=_NETWORK;?></a>
                <a href="/settings/security" class="w3-bar-item w3-button"><i class="fa fa-key"></i> <?=_LOGIN_SECURITY;?></a>
                <a href="/doc/" class="w3-bar-item w3-button"><i class="fa fa-question-circle"></i> <?=_DOC;?></a>
                <a href="/reboot/" onclick="return confirm('<?=_FORM_WARNING_SURE;?>');" class="w3-bar-item w3-button"><i class="fa fa-rotate"></i> <?=_SYSTEM_REBOOT;?></a>
                <a href="/shutdown/" onclick="return confirm('<?=_SYSTEM_SHUTDOWN_INFO;?>');" class="w3-bar-item w3-button"><i class="fa fa-power-off"></i> <?=_SYSTEM_SHUTDOWN;?></a>
            </div>
        </div>

    </div>
</div>
