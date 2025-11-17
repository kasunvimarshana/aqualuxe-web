<?php
/**
 * Dokan compatibility.
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Dokan store page
add_action( 'woocommerce_after_main_content', 'aqualuxe_dokan_store_wrapper_end', 5 );
add_action( 'woocommerce_before_main_content', 'aqualuxe_dokan_store_wrapper_start', 15 );

function aqualuxe_dokan_store_wrapper_start() {
    if ( function_exists( 'dokan_is_store_page' ) && dokan_is_store_page() ) {
        echo '<div class="dokan-store-wrap container mx-auto my-8 px-4">';
    }
}

function aqualuxe_dokan_store_wrapper_end() {
    if ( function_exists( 'dokan_is_store_page' ) && dokan_is_store_page() ) {
        echo '</div>';
    }
}
