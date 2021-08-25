<?php if ( defined('IN_GS') === false ) { die( 'You cannot lod this file directly!' ); }
/**
 * Plugin Name: WHMCS Connector
 * Description: Connects a WHMCS installation to GetSimple to allow the fetching of information.
 * Version: 1.0.0-alpha
 * Author: John Stray
 */ ?>

<h3 class="floated" style="float:left;">WHMCS Connector Settings</h3>
<div class="edit-nav">
    <p class="text 1">&nbsp;</p>
    <div class="clear"></div>
</div>
<p class="text 2">Configure the settings for the plugin here.</p>

<form class="largeform" action="load.php?id=<?php echo WHMCSFILE; ?>" method="post">
    <div class="widesec">
        <p>
            <label for="apiurl">WHMCS API URL</label>
            <span class="hint">The URL of a WHMCS installation to which this plugin will connect to and communicate with.</span>
            <input class="text" type="text" name="apiurl" value="<?php echo $savedSettings['apiurl']; ?>" />
        </p>
    </div>
    <div class="clear"></div>
    <div class="leftsec">
        <p>
            <label for="apikey">WHMCS API Key</label>
            <span class="hint">Your API Key as defined within the WHMCS installation</span>
            <input class="text" type="text" name="apikey" value="<?php echo $savedSettings['apikey']; ?>" />
        </p>
    </div>
    <div class="rightsec">
        <p>
            <label for="apisecret">WHMCS API Secret</label>
            <span class="hint">Your API Secret as defined within the WHMCS installation</span>
            <input class="text" type="password" name="apisecret" value="<?php echo $savedSettings['apisecret']; ?>" />
        </p>
    </div>
    <div class="clear"></div>
    <div class="saveButtonZone">
        <span><button class="submit" type="submit">Save Settings</button></span>
        &nbsp;&nbsp;or&nbsp;&nbsp;
        <a href="load.php?id=<?php echo WHMCSFILE; ?>" class="cancel">Cancel</a>
    </div>
</form>
