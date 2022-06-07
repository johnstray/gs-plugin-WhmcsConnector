<?php
/**
 * Plugin Name: WHMCS Connector
 * Description: Connects a WHMCS installation to GetSimple to allow the fetching of information.
 * 
 * @package: gs-WhmcsConnector
 * @version: 1.0.0-alpha
 * @author: John Stray <getsimple@johnstray.com>
 */

# Prevent impropper loading of this file. Must be loaded via GetSimple's plugin interface
if ( defined('IN_GS') === false ) { die( 'You cannot load this file directly!' ); }

// Find an announcement id in the array of arguments
$ifaid = array_values(array_filter( $arguments, function($argument) {
    return strpos($argument, 'id-') !== false;
}));
if ( isset($ifaid[0]) ) { $announcement_id = (int) substr($ifaid[0], 3); } else { $announcement_id = null; }

// Find the start offset in the array of arguments - defaults to 0
$ifls = array_values(array_filter( $arguments, function($argument) {
    return strpos($argument, 'ls-') !== false;
}));
if ( isset($ifls[0]) ) { $limit_start = (int) substr($ifls[0], 3); } else { $limit_start = 0; }

// Find the result limit in the array of arguments - defaults to 10
$ifln = array_values(array_filter( $arguments, function($argument) {
    return strpos($argument, 'ln-') !== false;
}));
if ( isset($ifln[0]) ) { $limit_num = (int) substr($ifln[0], 3); } else { $limit_num = 10; }


// IF $announcement_id - show single announcement
if ( $announcement_id !== null ) {

    // Get the array for the post we are going to show
    // Large limit given here in the hopes that the announcement we want will be returned.
    // Probably not the most efficient, but probably the best we can manage
    $announcements = $this->apiCall( 'GetAnnouncements', ['limitstart'=>0, 'limitnum'=>65536], true );
    
    if ( empty($announcements) === false && $announcements['result'] == 'success' ) {
    
        $announcement = array();
        $announcements = $announcements['announcements']['announcement'];
        foreach ( $announcements as $announcement_data )  {
            if ( $announcement_data['id'] == $announcement_id ) {
                $announcement = $announcement_data;
                break;
            }
        }
        
        // Start an output buffer and bring in the theme template, then put the buffer content into the $replace variable
        // The template should use the array of variables to fill out sections
        if ( file_exists(WHMCSTEMPLATES . 'announcement.inc.php') ) {
            ob_start();
            include ( WHMCSTEMPLATES . 'announcement.inc.php' );
            $replace = ob_get_clean();
        } else {
            // The current theme does not support this plugin. For now we will set the $replace variable to  an empty
            // string until we figure out something better for this.
            $replace = '';
        }
    
    } else { 
        // The API call failed.. Set the $replace variable to an empty string
        $replace = '';
    }
    
// ELSE show a list of announcements
} else {
    
    // Get an array of announcements from the api
    $announcements = $this->apiCall( 'GetAnnouncements', ['limitstart'=>$limit_start, 'limitnum'=>$limit_num], true );
    
    if ( empty($announcements) === false && $announcements['result'] == 'success' ) {
        $announcements = $announcements['announcements']['announcement'];
        
        // Start an output buffer and bring in the theme template, then put the buffer content into the $replace variable
        // The template should loop over the array of variables to build a list of announcements
        if ( file_exists(WHMCSTEMPLATES . 'announcements.inc.php') ) {
            ob_start();
            include ( WHMCSTEMPLATES . 'announcements.inc.php' );
            $replace = ob_get_clean();
        } else {
            // The current theme does not support this plugin. For now we will set the $replace variable to  an empty
            // string until we figure out something better for this.
            $replace = '';
        }
    } else {
        // The API call failed.. Set the $replace variable to an empty string
        $replace = '';
    }
}