<?php if ( defined('IN_GS') === false ) { die( 'You cannot lod this file directly!' ); }
/**
 * Plugin Name: WHMCS Connector
 * Description: Connects a WHMCS installation to GetSimple to allow the fetching of information.
 * Version: 1.0.0-alpha
 * Author: John Stray
 */

/**---------------------------------------------------------------------------------------------------------------------
 * settings()
 * Description
 * 
 * @return void
 */
function gs_whmcs_settings () : void
{
    $defaultSettings = array(
        'apiurl' => '',
        'apikey' => '',
        'apisecret' => ''
    );
    
    if ( file_exists(WHMCSSETTINGS) == false ) {
        // Create the settings file
        if ( gs_whmcs_saveSettings( $defaultSettings ) == false ) {
            gs_whmcs_displayMessage( i18n_r(WHMCSFILE . '/SETTINGS_CREATE_ERROR'), 'error' );
        } else {
            gs_whmcs_displayMessage( i18n_r(WHMCSFILE . '/SETTINGS_CREATE_OK') );
        }
    }
    
    // Load the settings file
    $updateSettings = false;
    $savedSettings = gs_whmcs_getSettings();
    if ( count($savedSettings) == 0 ) {
        gs_whmcs_displayMessage( i18n_r(WHMCSFILE . '/SETTINGS_UNDEFINED') );
        $updateSettings = true;
    }
    
    // Check for missing settings
    $missingSettings = array_diff_key( $defaultSettings, $savedSettings );
    if ( count($missingSettings) > 0 ) {
        foreach ( $missingSettings as $key => $value ) {
            $savedSettings[$key] = $value;
        } $updateSettings = true;
        gs_whmcs_displayMessage( i18n_r(WHMCSFILE . '/SETTINGS_UPDATE_ADDED'), 'info' );
    }
    
    // Check for redundant settings
    foreach ( $savedSettings as $key => $value ) {
        if ( array_key_exists($key, $defaultSettings) == false ) {
            unset( $savedSettings[$key] );
            $updateSettings = true;
            gs_whmcs_displayMessage( i18n_r(WHMCSFILE . '/SETTINGS_UPDATE_REMOVE'), 'info' );
        }
    }
    
    // Check for settings being saved
    foreach ( $savedSettings as $key => $value ) {
        if ( isset($_POST[$key]) ) {
            // @TODO: Sanitize the POST input here
            $savedSettings[$key] = $_POST[$key];
            $updateSettings = true;
        }
    }
    
    // Write settings to file after update
    if ( $updateSettings == true ) {
        if ( gs_whmcs_saveSettings( $savedSettings ) ) {
            gs_whmcs_displayMessage( i18n_r(WHMCSFILE . '/SETTINGS_SAVE_OK') );
        } else {
            gs_whmcs_displayMessage( i18n_r(WHMCSFILE . '/SETTINGS_SAVE_ERROR'), 'error' );
        }
    }
    
    // Include the settings HTML Page file
    require( WHMCSPATH . 'includes/settings.inc.php');
    
}

/**---------------------------------------------------------------------------------------------------------------------
 * saveSettings()
 * Description
 * 
 * @param array $settings - An array of setting to be saved to the settings file
 * @return bool $success - True if the settings file was saved successfully, otherwise False
 */
function gs_whmcs_saveSettings ( array $settings = array() ) : bool
{
    $xml = new SimpleXMLExtended('<?xml version="1.0"?><settings></settings>');
    foreach ( $settings as $key => $value ) {
        $settingKey = $xml->addChild( (string) $key );
        $settingKey->addCData( (string) $value );
    }
    
    if ( XMLsave( $xml, WHMCSSETTINGS ) ) { return true; }
    
    return false;
}

/**---------------------------------------------------------------------------------------------------------------------
 * getSettings()
 * Description
 * 
 * @return array $settings - An array of currently configured settings as found in the settings file
 */
function gs_whmcs_getSettings () : array
{
    $settings = array();
    
    $settingsData = getXML(WHMCSSETTINGS);
    if ( $settingsData == false || empty($settingsData) ) {
        die( i18n_r(WHMCSFILE . '/SETTINGS_GET_ERROR') );
    }
    
    $settingsData = json_decode(json_encode($settingsData), true);
    foreach ( $settingsData as $setting => $value ) {
        if ( empty($value) ) {
            $settings[$setting] = (string) '';
        } else {
            $settings[$setting] = (string) $value;
        }
    }
    
    return $settings;
}
