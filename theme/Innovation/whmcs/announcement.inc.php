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

/**
 * Available array elements to use within this template:
 *
 * $announcement = array(
 *     'id'            => "1",
 *     'date'          => "2016-02-24 21:27:04",
 *     'title'         => "Thank you for choosing WHMCS!",
 *     'announcement'  => '<p>Welcome to <a href="https://whmcs.com">WHMCS! You have made a great choice and...',
 *     'published'     => "1",
 *     'parentid'      => "0",
 *     'language'      => "",
 *     'created_at'    => "0000-00-00 00:00:00",
 *     'updated_at'    => "0000-00-00 00:00:00"
 * );
 */ ?>
 
<div class="whmcs-announcement">
    <h3 class="whmcs-announcement-title"><?php echo $announcement['title']; ?></h3>
    <p class="whmcs-announcement-date">Posted on: <?php echo $announcement['date']; ?></p>
    <div class="announcement-body">
        <?php echo $announcement['announcement']; ?>
    </div>
</div>

