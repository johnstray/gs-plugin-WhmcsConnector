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

# Define the plugin identifier
define( 'WHMCSFILE', basename(__FILE__, ".php") );

# Setup languages and language settings
i18n_merge( WHMCSFILE ) || i18n_merge( WHMCSFILE, "en_US" );

# Require the common file
require_once ( WHMCSFILE . '/common.php' );

# Register plugin with system
register_plugin (
    WHMCSFILE,                                                      // Plugin ID
    i18n_r(WHMCSFILE . '/PLUGIN_NAME'),                             // Plugin Title
    WHMCSVERS,                                                      // Plugin Version
    "John Stray",                                                   // Plugin Author
    "https://johnstray.com/get-simple/plugin/gs-whmcsconnector/",   // Plugin Author URL
    i18n_r(WHMCSFILE . '/PLUGIN_DESC'),                             // Plugin Description
    'settings',                                                     // Where the settings page sits
    'WhmcsConnector_main'                                           // Main controller function
);
