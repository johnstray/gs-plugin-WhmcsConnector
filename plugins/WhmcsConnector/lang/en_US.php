<?php
/**
 * Plugin Name: WHMCS Connector
 * Description: Connects a WHMCS installation to GetSimple to allow the fetching of information.
 * 
 * @package: gs-WhmcsConnector
 * @version: 1.0.0-alpha
 * @author: John Stray <get-simple@johnstray.com>
 */

# Prevent impropper loading of this file. Must be loaded via GetSimple's plugin interface
if ( defined('IN_GS') === false ) { die( 'You cannot load this file directly!' ); }

$i18n = array (

    # General Information
    'PLUGIN_NAME' => "WHMCS API Connector",
    'PLUGIN_DESC' => "Connects a WHMCS installation to GetSimple to allow the fetching of information for display on the website.",
    'SIDEBAR_BUTTON' => "WHMCS API Settings",
    
    # Error Messages
    'SETTINGS_CREATE_OK' => "Successfully created a settings file with default settings.",
    'SETTINGS_CREATE_ERROR' => "A problem prevented the settings file from being created. Settings may not be saved until this is resolved.",
    'SETTINGS_UPDATE_ADDED' => "Settings file will be updated to include new settings options. You may need to save your settings for this change to take effect.",
    'SETTINGS_UPDATE_REMOVE' => "Obsolete redundant settings values will be removed from your settings file. You may need to save your settings for this change to take effect.",
    'SETTINGS_SAVE_OK' => "The settings file has successfully been updated and saved.",
    'SETTINGS_SAVE_ERROR' => "A problem has prevented the settings file from being saved. Settings may not take effect until this has been resolved, and the settings saved again.",
    'SETTINGS_GET_ERROR' => "A problem has prevented the settings file from being loaded. You will need to resolve this issue before continuing.",
    'SETTINGS_UNDEFINED' => "The settings file is unusually empty. It will be updated to include the default settings.",
    'SETTINGS_MISSING' => "Settings that are required for proper functioning are missing. Please visit the Plugin Settings page and save or update your settings.",
    
    'API_KEY_INVALID' => "The configured API Key is not valid. Please go to the Plugin Settings page and configure it correctly.",
    'API_SECRET_INVALID' => "The configures API Secret is not valid. Please go to the Plugin Settings page and configure it correctly.",
    'API_URL_INVALID' => "The configured API URL is not valid. Please go to the Plugin Settings page and configure it correctly.",
    
    'CURL_ERROR' => "A cURL error occurred: ",
    
    # Settings UI
    'UI_PAGE_TITLE' => "WHMCS API Connector Settings",
    'UI_PAGE_INTRO' => "Fill out the settings fields below with the relevant details. You will need your API credentials for your WHMCS installation.",
    'UI_APIURL_LABEL' => "WHMCS API URL",
    'UI_APIURL_HINT' => "The URL of a WHMCS installation to connect to",
    'UI_APIKEY_LABEL' => "WHMCS API Key",
    'UI_APIKEY_HINT' => "Your API Key as defined within the WHMCS installation",
    'UI_APISECRET_LABEL' => "WHMCS API Secret",
    'UI_APISECRET_HINT' => "Your API Secret as defined within the WHMCS installation",
    
    'UI_SAVE_BUTTON' => "Save Settings",
    'UI_CANCEL_BUTTON' => "Cancel",
    'UI_TEST_CONNECT_BUTTON' => "Test Connection",
    'UI_TEST_CONNECT_BUTTON_HINT' => "Test the connection to the WHMCS instance using the saved settings",
    
    'UI_TC_CONNECT_OK_LABEL' => "Successfully connected to WHMCS instance!",
    'UI_TC_CONNECT_FAIL_LABEL' => "Failed to connect to WHMCS instance.",
    'UI_TC_LOGO_PREALT' => "Company logo for: ",
    'UI_TC_VERSION_LABEL' => "Detected WHMCS Version",
    'UI_TC_MAINTENANCE_MODE_LABEL' => "Maintenance Mode",
    'UI_TC_ACCESS_BUTTON' => "Go to WHMCS instance",
    'UI_TC_ACCESS_BUTTON_HINT' => "Visit the site of the configured WHMCS instance",
    'UI_TC_TEST_FAILED' => "Something went wrong while trying to comunicate with the remote server.",
    
);