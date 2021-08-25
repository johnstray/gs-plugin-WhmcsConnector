<?php if ( defined('IN_GS') === false ) { die( 'You cannot lod this file directly!' ); }
/**
 * Plugin Name: WHMCS Connector
 * Description: Connects a WHMCS installation to GetSimple to allow the fetching of information.
 * Version: 1.0.0-alpha
 * Author: John Stray
 */ ?>

<div class="single-plan-item">
    <div class="plan-header">
        <h4><?php echo $plan['name']; ?></h4>
        <span>$<?php echo $plan['myprice']; ?><small>/<?php echo $freq; ?></small></span>
    </div>
    <div class="plan-content">
        <p class="plan-description"><?php echo $plan['description']; ?></p>
        <hr />
        <ul>
            <?php foreach ( $plan['features'] as $featureName => $featureValue ) { ?>
                <li><?php echo $featureValue; ?> <?php echo $featureName; ?></li>
            <?php } ?>
        </ul>
        <div class="plan-btn-wrapper text-center">
            <a href="#" class="plan-btn btn-white">Order Now</a>
        </div>
    </div>
</div>
