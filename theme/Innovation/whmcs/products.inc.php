<?php if ( defined('IN_GS') === false ) { die( 'You cannot lod this file directly!' ); }
/**
 * Plugin Name: WHMCS Connector
 * Description: Connects a WHMCS installation to GetSimple to allow the fetching of information.
 * Version: 1.0.0-alpha
 * Author: John Stray
 */

//@TODO: Refactor this for the default Innovation theme

$getProducts = gs_whmcs_api( 'GetProducts', ['gid'=>'1'] );
$products = $getProducts['products']['product'];
ob_start(); ?>

<div class="plan-navtab">

    <div class="nav-wrapper d-flex justify-content-center mb-70" data-animate="fadeInUp" data-delay=".3">
        <ul class="nav nav-tabs justify-content-center" role="tablist">
            <li class="nav-item">
                <a class="active" data-toggle="tab" href="#monthly" role="tab">Monthly</a>
            </li>
            <li class="nav-item">
                <a data-toggle="tab" href="#quarterly" role="tab">Quarterly</a>
            </li>
            <li class="nav-item">
                <a data-toggle="tab" href="#yearly" role="tab">Yearly</a>
            </li>
        </ul>
    </div>

    <div class="tab-content">
      
        <div id="monthly" class="tab-pane fade show active" role="tabpanel">
            <div class="product-slider">

                <?php foreach ( $products as $plan ) {
                    $freq = 'monthly';
                    include('single-product.inc.php');
                } ?>
                
            </div>
        </div>
      
        <div id="quarterly" class="tab-pane fade" role="tabpanel">
            <div class="product-slider">

                <?php foreach ( $products as $plan ) {
                    $freq = 'quarterly';
                    include('single-product.inc.php');
                } ?>
                
            </div>
        </div>
      
        <div id="yearly" class="tab-pane fade" role="tabpanel">
            <div class="product-slider">

                <?php foreach ( $products as $plan ) {
                    $freq = 'yearly';
                    include('single-product.inc.php');
                } ?>
                
            </div>
        </div>
      
    </div>
</div>

<?php $replace = ob_get_clean(); ?>
