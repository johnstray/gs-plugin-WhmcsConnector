<?php if ( defined('IN_GS') === false ) { die( 'You cannot lod this file directly!' ); }
/**
 * Plugin Name: WHMCS Connector
 * Description: Connects a WHMCS installation to GetSimple to allow the fetching of information.
 * Version: 1.0.0-alpha
 * Author: John Stray
 */ ?>

<h3 class="floated" style="float:left;"><?php i18n(WHMCSFILE . '/UI_PAGE_TITLE'); ?></h3>
<div class="edit-nav">
    <p class="text 1">&nbsp;</p>
    <div class="clear"></div>
</div>
<p class="text 2"><?php i18n(WHMCSFILE . '/UI_PAGE_INTRO'); ?></p>

<form class="largeform" action="load.php?id=<?php echo WHMCSFILE; ?>" method="post">
    <div class="widesec">
        <p>
            <label for="apiurl"><?php i18n(WHMCSFILE . '/UI_APIURL_LABEL'); ?></label>
            <span class="hint"><?php i18n(WHMCSFILE . '/UI_APIURL_HINT'); ?></span>
            <input class="text" type="text" name="apiurl" value="<?php echo $savedSettings['apiurl']; ?>" />
        </p>
    </div>
    <div class="clear"></div>
    <div class="leftsec">
        <p>
            <label for="apikey"><?php i18n(WHMCSFILE . '/UI_APIKEY_LABEL'); ?></label>
            <span class="hint"><?php i18n(WHMCSFILE . '/UI_APIKEY_HINT'); ?></span>
            <input class="text" type="text" name="apikey" value="<?php echo $savedSettings['apikey']; ?>" />
        </p>
    </div>
    <div class="rightsec">
        <p>
            <label for="apisecret"><?php i18n(WHMCSFILE . '/UI_APISECRET_LABEL'); ?></label>
            <span class="hint"><?php i18n(WHMCSFILE . '/UI_APISECRET_HINT'); ?></span>
            <input class="text" type="password" name="apisecret" value="<?php echo $savedSettings['apisecret']; ?>" />
        </p>
    </div>
    <div class="clear"></div>
    <div class="saveButtonZone">
        <span><button class="submit" type="submit"><?php i18n(WHMCSFILE . '/UI_SAVE_BUTTON'); ?></button></span>
        &nbsp;&nbsp;or&nbsp;&nbsp;
        <a href="load.php?id=<?php echo WHMCSFILE; ?>" class="cancel"><?php i18n(WHMCSFILE . '/UI_CANCEL_BUTTON'); ?></a>
    </div>
</form>
