<?php
/**
 * WooCommerce Simple Auctions compatibility.
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Add custom wrapper for auction products
add_action( 'woocommerce_before_single_product_summary', 'aqualuxe_auction_wrapper_start', 5 );
add_action( 'woocommerce_after_single_product_summary', 'aqualuxe_auction_wrapper_end', 5 );

function aqualuxe_auction_wrapper_start() {
    global $product;
    if ( $product && $product->is_type( 'auction' ) ) {
        echo '<div class="aqualuxe-auction-wrapper">';
    }
}

function aqualuxe_auction_wrapper_end() {
    global $product;
    if ( $product && $product->is_type( 'auction' ) ) {
        echo '</div>';
    }
}
