<?php
/**
 * WooCommerce Subscriptions compatibility.
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Add custom wrapper for subscription products
add_action( 'woocommerce_before_single_product_summary', 'aqualuxe_subscription_wrapper_start', 5 );
add_action( 'woocommerce_after_single_product_summary', 'aqualuxe_subscription_wrapper_end', 5 );

function aqualuxe_subscription_wrapper_start() {
    global $product;
    if ( $product && $product->is_type( 'subscription' ) ) {
        echo '<div class="aqualuxe-subscription-wrapper">';
    }
}

function aqualuxe_subscription_wrapper_end() {
    global $product;
    if ( $product && $product->is_type( 'subscription' ) ) {
        echo '</div>';
    }
}
