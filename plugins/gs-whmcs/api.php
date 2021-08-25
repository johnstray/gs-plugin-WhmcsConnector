<?php if ( defined('IN_GS') === false ) { die( 'You cannot lod this file directly!' ); }
/**
 * Plugin Name: WHMCS Connector
 * Description: Connects a WHMCS installation to GetSimple to allow the fetching of information.
 * Version: 1.0.0-alpha
 * Author: John Stray
 */

function gs_whmcs_api ( $command, $params )
{
    $results = array();
    
    # Import the settings
    require_once( 'settings.php' );
    $settings = gs_whmcs_getSettings();
    
    $postFields = array(
        'identifier' => $settings['apikey'],
        'secret' => $settings['apisecret'],
        'action' => $command,
        'responsetype' => 'json'
    );
    
    # Add parameter array to $postFields
    $postFields = array_merge( $postFields, $params );
    
    # Initialise connection
    $ch = curl_init();
    
    # Set connection options
    curl_setopt( $ch, CURLOPT_URL, $settings['apiurl'] . 'includes/api.php' );
    curl_setopt( $ch, CURLOPT_POST, 1 );
    curl_setopt( $ch, CURLOPT_TIMEOUT, 30 );
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
    curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, 1 );
    curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 2 );
    curl_setopt( $ch, CURLOPT_POSTFIELDS, http_build_query( $postFields ) );
    
    # Perform the connection
    $response = curl_exec( $ch );
    
    # Did we have an error?
    if ( curl_error($ch) ) {
        die( "Unable to connect: " . curl_errno($ch) . ' - ' . curl_error($ch) );
    }
    
    # Close connection
    curl_close( $ch );
    
    # Decode response
    $results = json_decode( $response, true );
    
    return $results;
}
