<?php if ( defined('IN_GS') === false ) { die( 'You cannot lod this file directly!' ); }
/**
 * Plugin Name: WHMCS Connector
 * Description: Connects a WHMCS installation to GetSimple to allow the fetching of information.
 * Version: 1.0.0-alpha
 * Author: John Stray
 */

/**---------------------------------------------------------------------------------------------------------------------
 * api()
 * Description
 * 
 * @return array $results - An array containing the results of the API call
 */
function gs_whmcs_api ( string $command, array $params = array() ) : array
{
    $results = array();
    
    // Import the settings
    require_once( 'settings.php' );
    $settings = gs_whmcs_getSettings();
    
    // Validate the settings
    if ( count($settings) < 3 ) {
        die ( i18n_r(WHMCSFILE . '/SETTINGS_MISSING') );
    } else {
        if ( isset($settings['apikey']) == false || empty($settings['apikey']) ) {
            die( i18n_r(WHMCSFILE . '/API_KEY_INVALID') );
        }
        if ( isset($settings['apisecret']) == false || empty($settings['apisecret']) ) {
            die( i18n_r(WHMCSFILE . '/API_SECRET_INVALID') );
        }
        if ( isset($settings['apiurl']) == false || empty($settings['apiurl']) ) {
            die( i18n_r(WHMCSFILE . '/API_URL_INVALID') );
        }
    }
    
    $postFields = array(
        'identifier' => $settings['apikey'],
        'secret' => $settings['apisecret'],
        'action' => $command,
        'responsetype' => 'json'
    );
    
    // Add parameter array to $postFields
    $postFields = array_merge( $postFields, $params );
    
    // Initialise connection
    $ch = curl_init();
    
    // Set connection options
    curl_setopt( $ch, CURLOPT_URL, $settings['apiurl'] . 'includes/api.php' );
    curl_setopt( $ch, CURLOPT_POST, 1 );
    curl_setopt( $ch, CURLOPT_TIMEOUT, 30 );
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
    curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, 1 );
    curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 2 );
    curl_setopt( $ch, CURLOPT_POSTFIELDS, http_build_query( $postFields ) );
    
    // Perform the connection
    $response = curl_exec( $ch );
    
    // Did we have an error?
    if ( curl_error($ch) ) {
        die( i18n_r(WHMCSFILE . '/CURL_ERROR') . curl_errno($ch) . ' - ' . curl_error($ch) );
    }
    
    // Close connection
    curl_close( $ch );
    
    // Decode response
    $results = json_decode( $response, true );
    
    return $results;
}
