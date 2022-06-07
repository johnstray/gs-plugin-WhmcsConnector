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

<style>
    .whmcs-announcement-title { margin-bottom: 0; }
    .whmcs-announcement-date { font-size: 75%; font-style: italic; margin-bottom: 0; }
    .whmcs-announcement-body { font-size: 90%; }
</style>

<h3 class="whmcs-announcements-title">WHMCS Announcements</h3>
<p class="whmcs-announcements-intro">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis nec massa augue. Morbi eu iaculis dolor, dapibus rhoncus felis. Ut ligula ante, interdum efficitur justo vitae, tempor cursus ex.</p>

<div class="whmcs-announcements">
    <?php foreach ( $announcements as $announcement ) { ?>

        <div class="whmcs-announcement">
            <h4 class="whmcs-announcement-title"><?php echo $announcement['title']; ?></h4>
            <p class="whmcs-announcement-date">Posted on: <?php echo $announcement['date']; ?></p>
            <div class="whmcs-announcement-body">
                <?php echo $announcement['announcement']; ?>
            </div>
        </div>
        
    <?php } ?>
</div>

