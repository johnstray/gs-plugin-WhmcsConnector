<?php if ( defined('IN_GS') === false ) { die( 'You cannot lod this file directly!' ); }
/**
 * Plugin Name: WHMCS Connector
 * Description: Connects a WHMCS installation to GetSimple to allow the fetching of information.
 * Version: 1.0.0-alpha
 * Author: John Stray
 */

# Define some important stuff
define( 'WHMCSFILE', basename(__FILE__, ".php") );
define( 'WHMCSVERS', "1.0.0-alpha" );
define( 'WHMCSPATH', GSPLUGINPATH . '/' . WHMCSFILE . '/' );

require_once ( WHMCSFILE . '/common.php' );

# Setup languages and language settings
# @TODO: Enable localisation of this plugin
//i18n_merge( WHMCSFILE ) || i18n_merge( WHMCSFILE, "en_US" );

# Register plugin with system
register_plugin (
    WHMCSFILE,                                      // Plugin ID
    "WHMCS Connector",                              // Plugin Title
    WHMCSVERS,                                      // Plugin Version
    "John Stray",                                   // Plugin Author
    "",                                             // Plugin Author URL
    "Connects a WHMCS installation to GetSimple" .  // Plugin Description
    "to allow the fetching of information for" .
    "display on the website.",
    'settings',                                     // Where the settings page sits
    'gs_whmcs_main'                          // Main controller function
);

# Tab / Sidebar Actions
add_action( 'settings-sidebar', 'createSideMenu', array(WHMCSFILE, "WHMCS Connector Settings") );

# Filters
add_filter( 'content', 'whmcs_connector_filter' );
