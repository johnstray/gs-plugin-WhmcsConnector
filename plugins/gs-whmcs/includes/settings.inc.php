<?php if ( defined('IN_GS') === false ) { die( 'You cannot load this file directly!' ); }
/**
 * Plugin Name: WHMCS Connector
 * Description: Connects a WHMCS installation to GetSimple to allow the fetching of information.
 * Version: 1.0.0-alpha
 * Author: John Stray
 */ ?>

<h3 class="floated" style="float:left;"><?php i18n(WHMCSFILE . '/UI_PAGE_TITLE'); ?></h3>
<div class="edit-nav">
    <p class="text 1">
        <a href="load.php?id=<?php echo WHMCSFILE; ?>&testConnect=true" title="<?php i18n(WHMCSFILE . '/UI_TEST_CONNECT_BUTTON_HINT'); ?>"><?php i18n(WHMCSFILE . '/UI_TEST_CONNECT_BUTTON'); ?></a>
    </p>
    <div class="clear"></div>
</div>
<p class="text 2"><?php i18n(WHMCSFILE . '/UI_PAGE_INTRO'); ?></p>

<form class="largeform" action="load.php?id=<?php echo WHMCSFILE; ?>" method="post">
    
    <div class="leftsec">
        <p>
            <label for="apiurl"><?php i18n(WHMCSFILE . '/UI_APIURL_LABEL'); ?></label>
            <span class="hint"><?php i18n(WHMCSFILE . '/UI_APIURL_HINT'); ?></span>
            <input class="text" type="text" name="apiurl" value="<?php echo $savedSettings['apiurl']; ?>" />
        </p>
        <p>
            <label for="apikey"><?php i18n(WHMCSFILE . '/UI_APIKEY_LABEL'); ?></label>
            <span class="hint"><?php i18n(WHMCSFILE . '/UI_APIKEY_HINT'); ?></span>
            <input class="text" type="text" name="apikey" value="<?php echo $savedSettings['apikey']; ?>" />
        </p>
        <p>
            <label for="apisecret"><?php i18n(WHMCSFILE . '/UI_APISECRET_LABEL'); ?></label>
            <span class="hint"><?php i18n(WHMCSFILE . '/UI_APISECRET_HINT'); ?></span>
            <input class="text" type="password" name="apisecret" value="<?php echo $savedSettings['apisecret']; ?>" />
        </p>
    </div>
    
    <div class="rightsec" id="gs_whmcs_ui_tc_instance-info" style="text-align:center;">
        <?php if ( isset($_GET['testConnect']) ) { ?>
            <?php $testConnect = gs_whmcs_testConnect(); ?>
            <?php if ( $testConnect['result'] == "success" ) { ?>
                <p class="gs_whmcs_ui_tc_connect-success" style="color:darkgreen;"><i><?php i18n(WHMCSFILE . '/UI_TC_CONNECT_OK_LABEL'); ?></i></p>
                <img class="gs_whmcs_ui_tc_logo" src="<?php echo $testConnect['whmcs']['logo_url']; ?>" alt="<?php echo i18n_r(WHMCSFILE . '/UI_TC_LOGO_PREALT') . $testConnect['whmcs']['company_name']; ?>" style="max-height:50px;margin:10px 0;" />
                <h3 class="gs_whmcs_ui_tc_company-name" style="margin-bottom:0;"><?php echo $testConnect['whmcs']['company_name']; ?></h3>
                <p class="gs_whmcs_ui_tc_version" style="margin-bottom:0;"><?php i18n(WHMCSFILE . '/UI_TC_VERSION_LABEL'); ?>: <?php echo $testConnect['whmcs']['version']; ?></p>
                <p class="gs_whmcs_ui_tc_maintenance-mode">
                    <i><?php i18n(WHMCSFILE . '/UI_TC_MAINTENANCE_MODE_LABEL'); ?>: <?php echo $testConnect['whmcs']['maintenance_mode']; ?></i>
                </p>
                <a class="gs_whmcs_ui_tc_access-button" href="<?php echo $testConnect['whmcs']['system_url']; ?>" title="<?php i18n(WHMCSFILE . '/UI_TC_ACCESS_BUTTON_HINT'); ?>">
                    <?php i18n(WHMCSFILE . '/UI_TC_ACCESS_BUTTON'); ?>
                </a>
            <?php } else { ?>
                <p class="gs_whmcs_ui_tc_connect-failed" style="color:darkred;"><i><?php i18n(WHMCSFILE . '/UI_TC_CONNECT_FAIL_LABEL'); ?></i></p>
                <?php if ( isset($testConnect['status']) ) { ?>
                    <p><?php echo $testConnect['status']; ?></p>
                <?php } ?>
            <?php } ?>
        <?php } ?>
    </div>
    <div class="clear"></div>
    
    <div class="saveButtonZone">
        <span><button class="submit" type="submit"><?php i18n(WHMCSFILE . '/UI_SAVE_BUTTON'); ?></button></span>
        &nbsp;&nbsp;or&nbsp;&nbsp;
        <a href="load.php?id=<?php echo WHMCSFILE; ?>" class="cancel"><?php i18n(WHMCSFILE . '/UI_CANCEL_BUTTON'); ?></a>
    </div>
</form>
