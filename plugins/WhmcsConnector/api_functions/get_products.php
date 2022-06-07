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

// Find a product id in the array of arguments
$ifpid = array_values(array_filter( $arguments, function($argument) {
    return strpos($argument, 'pid-') !== false;
}));
if ( isset($ifpid[0]) ) { $product_id = (int) substr($ifpid[0], 4); } else { $product_id = null; }

// Find a group id in the array of arguments
$ifgid = array_values(array_filter( $arguments, function($argument) {
    return strpos($argument, 'gid-') !== false;
}));
if ( isset($ifgid[0]) ) { $group_id = (int) substr($ifgid[0], 4); } else { $group_id = null; }

// Find a module id in the array of arguments
$ifmid = array_values(array_filter( $arguments, function($argument) {
    return strpos($argument, 'mid-') !== false;
}));
if ( isset($ifmid[0]) ) { $module_id = (int) substr($ifmid[0], 4); } else { $module_id = null; }


// IF $product_id, show an individual product


// ELSE show a list of products
    
    // Filter by $group_id and/or $module_id if given