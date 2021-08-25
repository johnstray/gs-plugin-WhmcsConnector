<?php if ( defined('IN_GS') === false ) { die( 'You cannot lod this file directly!' ); }
/**
 * Plugin Name: WHMCS Connector
 * Description: Connects a WHMCS installation to GetSimple to allow the fetching of information.
 * Version: 1.0.0-alpha
 * Author: John Stray
 */

# Settings file location
define( 'WHMCSSETTINGS', GSDATAOTHERPATH . 'whmcsconnector.xml' );

function gs_whmcs_main()
{
    // Load the settings page
    require_once( WHMCSPATH . 'settings.php' );
    gs_whmcs_settings();
}

function gs_whmcs_filter( $content ) : string
{
    $matches = array();
    preg_match_all( '/(?<=\{w\{)(.*?)(?=\}\})/', $content, $matches, PREG_OFFSET_CAPTURE );
    
    if ( $matches != false && count($matches) > 0 ) {
        $difference = 0;
        foreach ( $matches[0] as $match ) {
        
            // Figure out the action and parameters
            $action = explode('|', $match[0]);
            $function = $action[0];
            $arguments = explode(',', $action[1]);
            
            // What API functions are supported?
            $apiFunctionFiles = glob(__DIR__ . '/functions/*.php');
            $apiFunctions = array();
            foreach ( $apiFunctionFiles as $file ) {
                if ( is_file($file) && is_readable($file) ) {
                    $apiFunctions[] = basename($file, '.php');
                }
            }
            
            // Bring in the API
            require_once( 'api.php' );
            
            // Build the replacement content
            $replace = ''; # NOTE: Functions must fill this variable
            if ( in_array($function, $apiFunctions) ) {
                include( 'functions/' . $function . '.php' );
            }
            
            // Calculate where the replacement will be
            $string = '{w{' . $match[0] . '}}';
            $length = strlen($string);
            $offset = ($match[1] - 3) + $difference;
            $diff = strlen($replace) - $length;
            $difference = $difference + $diff;
            
            // Do the actual replacement
            $content = substr_replace( $content, $replace, $offset, $length );
        }
    }
    
    return $content;
}

/**-------------------------------------------------------------------------------------------------
 * display_message()
 * Displays a Success/Error message only on the backend
 * 
 * @return void
 */
function gs_whmcs_displayMessage($message = '???', $type = 'info', $close = false) {
    if(is_frontend() == false) {
        $removeit = ($close ? ".removeit()" : "");
        $type = ucfirst($type);
        if($close == false) {
            $message = $message . ' <a href="#" onclick="clearNotify();" style="float:right;">X</a>';
        }
        echo "<script>notify".$type."('".$message."').popit()".$removeit.";</script>";
    }
}
