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

# Define some important constants
define( 'WHMCSVERS', "1.0.0-alpha" );
define( 'WHMCSPATH', GSPLUGINPATH . DIRECTORY_SEPARATOR . WHMCSFILE . DIRECTORY_SEPARATOR );
define( 'WHMCSSETTINGS', GSDATAOTHERPATH . 'whmcsconnector.xml' );

# Setup constants that can be overridden
if ( defined('WHMCSAPICACHE') === false ) { define( 'WHMCSAPICACHE', true ); }
if ( defined('WHMCSAPICACHEDIR') === false ) { define( 'WHMCSAPICACHEDIR', GSCACHEPATH . WHMCSFILE . DIRECTORY_SEPARATOR ); }
if ( defined('WHMCSAPICACHESTALE') === false ) { define( 'WHMCSAPICACHESTALE', 3600 ); }

// Make sure the path to the Theme template files is set
if ( defined('WHMCSTEMPLATES') === false ) { define( 'WHMCSTEMPLATES', GSTHEMESPATH . $TEMPLATE . DIRECTORY_SEPARATOR . 'whmcs' . DIRECTORY_SEPARATOR ); }

# Tab / Sidebar Actions
add_action( 'settings-sidebar', 'createSideMenu', array(WHMCSFILE, i18n_r(WHMCSFILE . '/SIDEBAR_BUTTON')) );

# Filters
add_filter( 'content', 'gs_whmcs_filter' );

# Register / Queue Stylesheets
register_style( WHMCSFILE . '_css', $SITEURL . '/plugins/' . WHMCSFILE . '/includes/css/admin_styles.css', '', 'screen' );
queue_style( WHMCSFILE . '_css', GSBACK );


# Main controller function for the admin backend
function WhmcsConnector_main() : void
{
    require( WHMCSPATH . 'class/WhmcsConnector.class.php' );
    $WhmcsConnector = new WhmcsConnector();
    $WhmcsConnector->settingsDirector();
}

# Main function for use within theme templates
function WhmcsConnector( string $action, array $arguments = array(), bool $echo = true ) : string
{
    if ( count($arguments) > 0 ) {
        $filter = $action . '|' . implode(',', $arguments);
    } else {
        $filter = $action;
    }
    
    require( WHMCSPATH . 'class/WhmcsConnector.class.php' );
    $WhmcsConnector = new WhmcsConnector();
    $output = $WhmcsConnector->filterContent( '{w{' . $filter . '}}' );
    
    if ( $echo ) { echo $output; }
    return  $output;
}

# Main function for filtering the page content
function WhmcsConnector_filter( string $content ) : string
{
    require( WHMCSPATH . 'class/WhmcsConnector.class.php' );
    $WhmcsConnector = new WhmcsConnector();
    return $WhmcsConnector->filterContent($content);
}
