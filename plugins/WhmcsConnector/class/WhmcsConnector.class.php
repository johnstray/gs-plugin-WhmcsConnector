<?php
/**
 * Plugin Name: WHMCS Connector
 * Description: Connects a WHMCS installation to GetSimple to allow the fetching of information.
 * 
 * @package: gs-WhmcsConnector
 * @version: 1.0.0-alpha
 * @author: John Stray <get-simple@jonstray.com>
 */

# Prevent impropper loading of this class. Must be loaded via GetSimple's plugin interface
if ( defined('IN_GS') === false ) { die( 'You cannot load this file directly!' ); }

class WhmcsConnector {

    public $settings = array();
    
    /**
     * Class Constructor
     * Checks over the settings file and loads the settings into the class object
     * 
     * @since 1.0
     * @return void
     */
    public function __construct()
    {
        $defaultSettings = array(
            'apiurl' => '',
            'apikey' => '',
            'apisecret' => '',
            'blogenable' => 'false',
            'blogcategory' => '',
            'blogauthor' => '',
        );
        
        // Check if the settings file exists, creating it if it does not exist
        if ( file_exists(WHMCSSETTINGS) === false ) {
            if ( $this->saveSettings($defaultSettings) === false ) {
                $this->displayMessage( i18n_r(WHMCSFILE . '/SETTINGS_CREATE_ERROR'), 'error' );
                return false;
            } else {
                $this->displayMessage( i18n_r(WHMCSFILE . '/SETTINGS_CREATE_OK'), 'success' );
            }
        }
        
        $updateSettings = false;
        
        // Load the settings from file
        $savedSettings = $this->getSettings();
        
        // Check for missing settings
        $missingSettings = array_diff_key( $defaultSettings, $savedSettings );
        if ( count($missingSettings) > 0 ) {
            foreach ( $missingSettings as $key => $value ) {
                $savedSettings[$key] = $value;
                $this->debugLog( "Added missing setting '$key' with value '$value'", 'INFO' );
                $updateSettings = true;
            }
        }
        
        // Check for redundant settings
        foreach ( $savedSettings as $key => $value ) {
            if ( array_key_exists($key, $defaultSettings) === false ) {
                unset( $savedSettings[$key] );
                $this->debugLog( "Removed redundant setting '$key'", 'INFO' );
                $updateSettings = true;
            }
        }
        
        // Put settings into class variable
        $this->settings = $savedSettings;
        
        // If settings were updated, save them to file
        if ( $updateSettings === true ) {
            if ( $this->saveSettings( $savedSettings ) ) {
                $this->displayMessage( i18n_r(WHMCSFILE . '/SETTINGS_SAVE_OK'), 'success' );
                $this->debugLog( i18n_r(WHMCSFILE . '/SETTINGS_SAVE_OK'), 'INFO' );
            } else {
                $this->displayMessage( i18n_r(WHMCSFILE . '/SETTINGS_SAVE_ERROR'), 'error' );
                $this->debugLog( i18n_r(WHMCSFILE . '/SETTINGS_SAVE_ERROR'), 'ERROR' );
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * Settings Director
     * Controls the flow of settings for this plugin, including the backend settings page
     *
     * @since 1.0
     * @return void
     */
    public function settingsDirector() : void
    {
        // Have new settings been submitted for saving?
        if ( isset($_GET['action']) && $_GET['action'] == 'update-settings' ) {
            if ( $this->saveSettings($_POST) ) {
                $this->displayMessage( i18n_r(WHMCSFILE . '/SETTINGS_SAVE_OK'), 'success' );
            } else {
                $this->displayMessage( i18n_r(WHMCSFILE . '/SETTINGS_SAVE_ERROR'), 'error' );
            }
        }
        
        // @NOTE: If any future settings actions need to take place, handle them here.
        
        // Show the Settings backend admin page
        $savedSettings = $this->getSettings();
        $testConnect = $this->testConnect();
        
        // Check for Blog Importing support
        $blogExists = false;
        if ( file_exists(WHMCSPATH . 'class/BlogImporter.class.php') ) {
            require( WHMCSPATH . 'class/BlogImporter.class.php' );
            $BlogImporter = new WhmcsBlogImporter();
            if ( $BlogImporter->blogExists() ) { $blogExists = true; }
        }
        
        if ( defined('GSBACK') ) {
            require( WHMCSPATH . "includes/html/settings.inc.php" );
            
            // Insert copyright footer to the bottom of the page
            echo "</div><div class=\"gs_whmcs_ui_copyright-text\">WHMCS Connector Plugin &copy; 2022 John Stray - Licenced under <a href=\"https://www.gnu.org/licenses/gpl-3.0.en.html\">GNU GPLv3</a>";
            echo "<div>If you like this plugin or have found it useful, please consider a <a href=\"https://paypal.me/JohnStray\">donation</a></div>";
        }
            
    }
    
    /**
     * Get array of settings
     * Gets the configuration settings from file and returns them as an array
     * 
     * @since 1.0
     * @return array An array of currently configured settings as found in the settings file
     */
    private function getSettings() : array
    {
        $settings = array();
        
        // Attempt to retrieve the settings data from the XML file
        $settingsData = getXML(WHMCSSETTINGS);
        if ( $settingsData == false || empty($settingsData) ) {
            $this->displayMessage( i18n_r(WHMCSFILE . '/SETTINGS_GET_ERROR'), 'error' );
            return $settings; // Return the currently empty array
        }
        
        // Build the settings array from the XML data
        $settingsData = json_decode(json_encode($settingsData), true);
        foreach ( $settingsData as $setting => $value ) {
            if ( empty($value) ) {
                $settings[$setting] = (string) '';
            } else {
                $settings[$setting] = (string) $value;
            }
        }
        
        // Return the settings array
        return $settings;
    }
    
    /**
     * Get setting value
     * Returns the string value of a specific setting, checking first if it exists
     * 
     * @since 1.0
     * @param string $setting The setting to get the value for
     * @return string The string value of the requested setting
     */
    private function getSetting( string $setting ) : string
    {
        // Get the array of settings
        $settings = $this-getSettings();
        
        // Check if the requested setting exists, then return it's value'
        if ( isset($settings[$setting]) ) {
            return (string) $settings[$setting];
        }
        
        // Return empty string since we couldn't get the setting
        return '';
    }
    
    /**
     * Save settings
     * Saves an array of configuration settings to the settings file
     * 
     * @since 1.0
     * @param array $settings An array of setting to be saved to the settings file
     * @return bool True if the settings file was saved successfully, otherwise False
     */
    private function saveSettings( array $settings = array() ) : bool 
    {
        $settingsXml = new SimpleXMLExtended('<?xml version="1.0"?><settings></settings>');
        foreach ( $settings as $key => $value ) {
            // @TODO: Sanitise the settings values here
            $settingKey = $settingsXml->addChild( (string) $key );
            $settingKey->addCData( (string) $value );
        }
        
        if ( XMLsave( $settingsXml, WHMCSSETTINGS ) ) { return true; }
        
        return false;
    }
    
    /**
     * Page content filter
     * Filters the page output content, looking for special tags that will then be replaced by a function's output
     * 
     * @since 1.0
     * @param string $content The input page content to be filtered
     * @return string Filtered content with special tags replaced with plugin content
     */
    public function filterContent( string $content = '' ) : string
    {
        $matches = array();
        preg_match_all( '/(?<=\{w\{)(.*?)(?=\}\})/', $content, $matches, PREG_OFFSET_CAPTURE );
        
        if ( $matches != false && count($matches) > 0 ) {
            $difference = 0;
            foreach ( $matches[0] as $match ) {
                
                // Figure out the action and parameters
                $action = explode('|', $match[0]);
                $function = $action[0];
                if ( isset($action[1]) ) {
                    $arguments = explode(',', $action[1]);
                } else { $arguments = array(); }
                
                // What API functions are supported?
                $apiFunctionFiles = glob(WHMCSPATH . '/api_functions/*.php');
                $apiFunctions = array();
                foreach ( $apiFunctionFiles as $file ) {
                    if ( is_file($file) && is_readable($file) ) {
                        $apiFunctions[] = basename($file, '.php');
                    }
                }
                
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
    
    /**
     * Call the API
     * This is where the magic happens. Makes a call to the API via a HTTP request using the configured settings and the
     * given command and parameters. Will return an array containing the data returned from the API
     * 
     * @since 1.0
     * @param string $command The API command to call
     * @param array $params An array of parameters to pass to the API with the call
     * @param bool $ignore_cache Setting to true ignores the cache when making the API call
     * @return array An array of data returned from the API
     */
    private function apiCall( string $command, array $params = array(), bool $ignore_cache = false ) : array
    {
        $results = array();
    
        // Import the settings
        $settings = $this->getSettings();
        
        // Validate the settings
        // @TODO: Avoid dying here. Instead put info into the debug log and return a failure result array
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
        $cache_content = $this->cacheGet( $request_id );
        
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
            // @TODO: Avoid dying here. Instead put info into the debug log and return a failure result array
            if ( curl_error($ch) ) {
                die( i18n_r(WHMCSFILE . '/CURL_ERROR') . curl_errno($ch) . ' - ' . curl_error($ch) );
            }
            
            // Close connection
            curl_close( $ch );
            
            // Cache this response
            $this->cachePut( $request_id, $response );
        
        } else { // Cache available, using it
            $response = $cache_content;
        }
        
        // Decode response
        $results = json_decode( $response, true );
        
        return $results;
    }
    
    /**
     * Cache Put
     * Puts a set of data into cache ready for easy later retrieval
     * 
     * @since 1.0
     * @param string $request_id An identifier for the data, such as the request url
     * @param string $content The string of data to store in the cache
     * @return bool Returns true on success or false on failure
     */
    private function cachePut( string $request_id, string $content ) : bool
    {
        if ( WHMCSAPICACHE ) {
            
            // Hash the request ID, this will be our filename
            $cache_id = md5( $request_id );
            
            // Base64 encode the api response
            $cache_content = base64_encode( $content );
            
            // Check that our cache directory exists and is writeable
            if ( file_exists(WHMCSAPICACHEDIR) === false ) {
                if ( mkdir(WHMCSAPICACHEDIR) === false ) { // Attept to create the cache directory
                    $this->debugLog('Cache directory does not exist and could not be created. API request not cached.', 'ERROR'); return false; }
            }
            if ( is_dir(WHMCSAPICACHEDIR) === false ) {
                debugLog('Cache is not a directory. API request not cached.', 'ERROR'); return false; }
            if ( is_writable(WHMCSAPICACHEDIR) === false ) {
                $this->debugLog('Cache directory is not writeable. API request not cached.', 'ERROR'); return false; }
            
            // Save the cache content into a file
            if ( file_put_contents( WHMCSAPICACHEDIR . $cache_id . '.cache', $cache_content ) === false ) {
                // Failed to save the cache file
                $this->debugLog('Could not save entry to cache. API request not cached.', 'ERROR');
                return false;
            }
            
            // Cache item saved successfully!
            return true;
        }
        
        // Cache is disabled in gsconfig.php
        return false;
    }
    
    /**
     * Cache Get
     * Gets and returns a piece of data from the cache
     * 
     * @since 1.0
     * @param string $request_id The identifier for the cached data, such as the request url
     * @return string The piece of data that was stored in the cache
     */
    private function cacheGet( string $request_id ) : string
    {
        $cache_file = WHMCSAPICACHEDIR . md5($request_id) . '.cache';
        $cache_content = '';
        
        if ( file_exists($cache_file) ) { // Check if cache file exists
            if ( is_readable($cache_file) ) { // Check if cache file is readable
                if ( time() - filemtime($cache_file) < WHMCSAPICACHESTALE || WHMCSAPICACHESTALE === -1 ) { // Check if cache file is not stale
                    $cache_content = file_get_contents($cache_file);
                    if ( $cache_content === false ) {
                        $this->debugLog('Could not read the content of the cache file. The cache was missed for this request.', 'ERROR');
                        $cache_content = '';
                    }
                } else {
                    $this->debugLog('The cache file is too stale. The cache was missed for this request.', 'NOTICE');
                }
            } else {
                $this->debugLog ('The cache file is not readable. The cache was missed for this request.', 'ERROR');
            }
        } else {
            $this->debugLog('The cache file does not exist. The cache was missed for this request.', 'NOTICE');
        }
        
        return base64_decode($cache_content);
    }
    
    private function testConnect() : array
    {
        $testConnect = array( 'result' => "failed" );
        
        // Import the settings
        $settings = $this->getSettings();
        
        // Validate the settings
        if ( count($settings) < 3 ) {
            $testConnect['status'] = i18n_r(WHMCSFILE . '/SETTINGS_MISSING');
            return $testConnect;
        } else {
            if ( isset($settings['apikey']) == false || empty($settings['apikey']) ) {
                $testConnect['status'] = i18n_r(WHMCSFILE . '/API_KEY_INVALID');
                return $testConnect;
            }
            if ( isset($settings['apisecret']) == false || empty($settings['apisecret']) ) {
                $testConnect['status'] = i18n_r(WHMCSFILE . '/API_SECRET_INVALID');
                return $testConnect;
            }
            if ( isset($settings['apiurl']) == false || empty($settings['apiurl']) ) {
                $testConnect['status'] = i18n_r(WHMCSFILE . '/API_URL_INVALID');
                return $testConnect;
            }
        }
        
        // Test for Version config value
        $apiCallResult = $this->apiCall( 'GetConfigurationValue', ['setting' => "Version"], true );
        if ( is_array($apiCallResult) && count($apiCallResult) > 0 ) {
            if ( $apiCallResult['result'] == "success" && isset($apiCallResult['value']) ) {
                $testConnect['result'] = "success";
                $testConnect['whmcs']['version'] = $apiCallResult['value'];
            } else {
                $testConnect['status'] = $apiCallResult['message'];
                return $testConnect;
            }
        } else {
            $testConnect['status'] = i18n_r(WHMCSFILE . '/UI_TC_TEST_FAILED');
            return $testConnect;
        }
        
        // Test for SystemURL Config Value
        $apiCallResult = $this->apiCall( 'GetConfigurationValue', ['setting' => "SystemURL"], true );
        if ( is_array($apiCallResult) && count($apiCallResult) > 0 ) {
            if ( $apiCallResult['result'] == "success" && isset($apiCallResult['value']) ) {
                $testConnect['result'] = "success";
                $testConnect['whmcs']['system_url'] = $apiCallResult['value'];
            } else {
                $testConnect['status'] = $apiCallResult['message'];
                return $testConnect;
            }
        } else {
            $testConnect['status'] = i18n_r(WHMCSFILE . '/UI_TC_TEST_FAILED');
            return $testConnect;
        }
        
        // Test for MaintenanceMode config value
        $apiCallResult = $this->apiCall( 'GetConfigurationValue', ['setting' => "MaintenanceMode"], true );
        if ( is_array($apiCallResult) && count($apiCallResult) > 0 ) {
            if ( $apiCallResult['result'] == "success" && isset($apiCallResult['value']) ) {
                $testConnect['result'] = "success";
                $testConnect['whmcs']['maintenance_mode'] = $apiCallResult['value'];
            } else {
                $testConnect['status'] = $apiCallResult['message'];
                return $testConnect;
            }
        } else {
            $testConnect['status'] = i18n_r(WHMCSFILE . '/UI_TC_TEST_FAILED');
            return $testConnect;
        }
        
        // Test for CompanyName config value
        $apiCallResult = $this->apiCall( 'GetConfigurationValue', ['setting' => "CompanyName"], true );
        if ( is_array($apiCallResult) && count($apiCallResult) > 0 ) {
            if ( $apiCallResult['result'] == "success" && isset($apiCallResult['value']) ) {
                $testConnect['result'] = "success";
                $testConnect['whmcs']['company_name'] = $apiCallResult['value'];
            } else {
                $testConnect['status'] = $apiCallResult['message'];
                return $testConnect;
            }
        } else {
            $testConnect['status'] = i18n_r(WHMCSFILE . '/UI_TC_TEST_FAILED');
            return $testConnect;
        }
        
        // Test for LogoURL Config Value
        $apiCallResult = $this->apiCall( 'GetConfigurationValue', ['setting' => "LogoURL"], true );
        if ( is_array($apiCallResult) && count($apiCallResult) > 0 ) {
            if ( $apiCallResult['result'] == "success" && isset($apiCallResult['value']) ) {
                $testConnect['result'] = "success";
                $testConnect['whmcs']['logo_url'] = $apiCallResult['value'];
            } else {
                $testConnect['status'] = $apiCallResult['message'];
                return $testConnect;
            }
        } else {
            $testConnect['status'] = i18n_r(WHMCSFILE . '/UI_TC_TEST_FAILED');
            return $testConnect;
        }
        
        return $testConnect;
    }
    
    /**
     * Backend message display
     * Displays a notification message in GetSimple's backend admin area
     * 
     * @since 1.0
     * @param string $message The content of the message to display
     * @param string $type The type of message to display, one of ['info', 'success', 'warning', 'error']
     * @param bool $close Set to true to include a close button on the message, will also cause message to persist
     * @return void
     */
    private function displayMessage( string $message= '', string $type = 'info', bool $close = false ) : void
    {
        if(is_frontend() == false) {
            $removeit = ($close ? ".removeit()" : "");
            $type = ucfirst($type);
            if($close == false) {
                $message = $message . ' <a href="#" onclick="clearNotify();" style="float:right;">X</a>';
            }
            echo "<script>notify".$type."('".$message."').popit()".$removeit.";</script>";
        }
    }
    
    /**
     * Debug Logging
     * Adds a debugging message to GetSimple's debugLog
     * 
     * @since 1.0
     * @param string $message The message content to add to the debug log
     * @param string $type The type of message to add, one of ['INFO', 'NOTICE', 'WARN', 'ERROR']
     * @return void
     */
    private function debugLog( string $message= "", string $type = 'INFO' ) : void 
    {
        debugLog( "WHMCS Connector Plugin [" . $type . "]: " . $message );
    }

}