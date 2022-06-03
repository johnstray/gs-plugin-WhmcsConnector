<?php if ( defined('IN_GS') === false ) { die( 'You cannot load this file directly!' ); }
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
function gs_whmcs_api ( string $command, array $params = array(), bool $ignore_cache = false ) : array
{
    $results = array();
    
    // Import the settings
    require_once( WHMCSPATH . 'settings.php' );
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
    
    // Build the request_id for caching
    $request_id = $settings['apiurl'] . 'includes/api.php?';
    foreach ( $postFields as $k => $v ) {
        $request_id .= $k . '=' . $v . '&';
    }
    
    // Before making a request, first check the cache
    $cache_content = gs_whmcs_api_cacheGet( $request_id );
    
    if ( $cache_content == '' || $ignore_cache === true ) { // No cache, doing request
    
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
        
        // Cache this response
        gs_whmcs_api_cachePut( $request_id, $response );
    
    } else { // Cache available, using it
        $response = $cache_content;
    }
    
    // Decode response
    $results = json_decode( $response, true );
    
    return $results;
}

function gs_whmcs_api_cachePut ( string $request_id, string $api_response ) : bool
{
    // Check if cache enable constant is set in gsconfig.php, define it to on if it's not
    if ( defined('WHMCSAPICACHE') === false ) { define( 'WHMCSAPICACHE', true ); }
    
    // Check if the cache directory constant is set in gsconfig.php, define it if it's not
    if ( defined('WHMCSAPICACHEDIR') === false ) {
        define( 'WHMCSAPICACHEDIR', GSCACHEPATH . WHMCSFILE . DIRECTORY_SEPARATOR );
    }
    
    if ( WHMCSAPICACHE ) {
        
        // Hash the request ID, this will be our filename
        $cache_id = md5( $request_id );
        
        // Base64 encode the api response
        $cache_content = base64_encode( $api_response );
        
        // Check that our cache directory exists and is writeable
        if ( file_exists(WHMCSAPICACHEDIR) === false ) {
            if ( mkdir(WHMCSAPICACHEDIR) === false ) { // Attept to create the cache directory
                debugLog('WHMCS Connector Plugin [ERROR]: Cache directory does not exist and could not be created. API request not cached.'); return false; }
        }
        if ( is_dir(WHMCSAPICACHEDIR) === false ) {
            debugLog('WHMCS Connector Plugin [ERROR]: Cache is not a directory. API request not cached.'); return false; }
        if ( is_writable(WHMCSAPICACHEDIR) === false ) {
            debugLog('WHMCS Connector Plugin [ERROR]: Cache directory is not writeable. API request not cached.'); return false; }
        
        // Save the cache content into a file
        if ( file_put_contents( WHMCSAPICACHEDIR . $cache_id . '.cache', $cache_content ) === false ) {
            // Failed to save the cache file
            debugLog('WHMCS Connector Plugin [ERROR]: Could not save entry to cache. API request not cached.');
            return false;
        }
        
        // Cache item saved successfully!
        return true;
    }
    
    // Cache is disabled in gsconfig.php
    return false;
}

function gs_whmcs_api_cacheGet ( string $request_id ) : string
{
    // Check if the cache enable constant is set in gsconfig.php, define it to on if it's not
    if ( defined('WHMCSAPICACHE') === false ) { define( 'WHMCSAPICACHE', true ); }
    
    // Check if the cache directory constant is set in gsconfig.php, define it if it's not
    if ( defined('WHMCSAPICACHEDIR') === false ) {
        define( 'WHMCSAPICACHEDIR', GSCACHEPATH . WHMCSFILE . DIRECTORY_SEPARATOR );
    }
    
    // Check if the cache stale time constant is set in gsconfig.php, define it if it's not
    // Always returns stale content if set to -1
    if ( defined('WHMCSAPICACHESTALE') === false ) {
        define( 'WHMCSAPICACHESTALE', 3600 );
    }
    
    $cache_file = WHMCSAPICACHEDIR . md5($request_id) . '.cache';
    $cache_content = '';
    
    if ( file_exists($cache_file) ) { // Check if cache file exists
        if ( is_readable($cache_file) ) { // Check if cache file is readable
            if ( time() - filemtime($cache_file) < WHMCSAPICACHESTALE || WHMCSAPICACHESTALE === -1 ) { // Check if cache file is not stale
                $cache_content = file_get_contents($cache_file);
                if ( $cache_content === false ) {
                    debugLog('WHMCS Connector Plugin [ERROR]: Could not read the content of the cache file. The cache was missed for this request.');
                    $cache_content = '';
                }
            } else {
                debugLog('WHMCS Connector Plugin [NOTICE]: The cache file is too stale. The cache was missed for this request.');
            }
        } else {
            debugLog ('WHMCS Connector Plugin [ERROR]: The cache file is not readable. The cache was missed for this request.');
        }
    } else {
        debugLog('WHMCS Connector Plugin [NOTICE]: The cache file does not exist. The cache was missed for this request.');
    }
    
    return base64_decode($cache_content);
}
