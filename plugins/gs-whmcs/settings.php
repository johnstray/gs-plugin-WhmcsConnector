<?php if ( defined('IN_GS') === false ) { die( 'You cannot lod this file directly!' ); }
/**
 * Plugin Name: WHMCS Connector
 * Description: Connects a WHMCS installation to GetSimple to allow the fetching of information.
 * Version: 1.0.0-alpha
 * Author: John Stray
 */

function gs_whmcs_settings()
{
    $defaultSettings = array(
        'apiurl' => '',
        'apikey' => '',
        'apisecret' => ''
    );
    
    if ( file_exists(WHMCSSETTINGS) == false ) {
        # Create the settings file
        if ( gs_whmcs_saveSettings( $defaultSettings ) == false ) {
            // @TODO: Handle a return of false here
            gs_whmcs_displayMessage( 'Could not create settings file' );
        } else {
            gs_whmcs_displayMessage( 'Created Settings File' );
        }
    }
    
    # Load the settings file
    $savedSettings = gs_whmcs_getSettings();
    if ( count($savedSettings) == 0 ) {
        // @TODO: Handle empty settings array here, something went wrong getting them.
    }
    $updateSettings = false;
    
    # Check for missing settings
    $missingSettings = array_diff_key( $defaultSettings, $savedSettings );
    if ( count($missingSettings) > 0 ) {
        foreach ( $missingSettings as $key => $value ) {
            $savedSettings[$key] = $value;
        } $updateSettings = true;
        gs_whmcs_displayMessage( 'Added missing settings values' );
    }
    
    # Check for redundant settings
    foreach ( $savedSettings as $key => $value ) {
        if ( array_key_exists($key, $defaultSettings) == false ) {
            unset( $savedSettings[$key] );
            $updateSettings = true;
            gs_whmcs_displayMessage( 'Redundant setting found' );
        }
    }
    
    # Check for settings being saved
    foreach ( $savedSettings as $key => $value ) {
        if ( isset($_POST[$key]) ) {
            // @TODO: Sanitize the POST input here
            $savedSettings[$key] = $_POST[$key];
            $updateSettings = true;
        }
    }
    
    # Write settings to file after update
    if ( $updateSettings == true ) {
        // @TODO: Add error handling here
        if ( gs_whmcs_saveSettings( $savedSettings ) ) {
            gs_whmcs_displayMessage( 'Settings saved' );
        } else {
            gs_whmcs_displayMessage( 'Could not save settings', 'error' );
        }
    }
    
    # Include the settings HTML Page file
    require( WHMCSPATH . 'includes/settings.inc.php');
    
}

function gs_whmcs_saveSettings ( $settings = array() ) : bool
{
    $xml = new SimpleXMLExtended('<?xml version="1.0"?><settings></settings>');
    foreach ( $settings as $key => $value ) {
        $settingKey = $xml->addChild( (string) $key );
        $settingKey->addCData( (string) $value );
    }
    
    if ( XMLsave( $xml, WHMCSSETTINGS ) ) { return true; }
    
    return false;
}

function gs_whmcs_getSettings () : array
{
    $settings = array();
    
    $settingsData = getXML(WHMCSSETTINGS); // @TODO: Add error handling here
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
