<?php
/**
 * Bookings Module
 *
 * Provides integration with WooCommerce Bookings.
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Check if WooCommerce Bookings is active.
 *
 * @return bool
 */
function aqualuxe_is_bookings_active() {
    return class_exists( 'WC_Bookings' );
}

/**
 * Custom functions and hooks for WooCommerce Bookings.
 */
if ( aqualuxe_is_bookings_active() ) {

    /**
     * Enqueue custom styles for the bookings module.
     */
    function aqualuxe_bookings_styles() {
        // Example: wp_enqueue_style( 'aqualuxe-bookings', AQUALUXE_THEME_URI . 'modules/bookings/assets/css/bookings.css' );
    }
    add_action( 'wp_enqueue_scripts', 'aqualuxe_bookings_styles' );

}
