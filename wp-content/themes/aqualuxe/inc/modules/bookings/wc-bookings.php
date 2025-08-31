<?php
/**
 * WooCommerce Bookings compatibility.
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Add custom wrapper for booking form
add_action( 'woocommerce_before_booking_form', 'aqualuxe_booking_form_wrapper_start', 5 );
add_action( 'woocommerce_after_booking_form', 'aqualuxe_booking_form_wrapper_end', 15 );

function aqualuxe_booking_form_wrapper_start() {
    echo '<div class="aqualuxe-booking-form-wrapper border border-gray-200 p-6 rounded-lg bg-gray-50">';
}

function aqualuxe_booking_form_wrapper_end() {
    echo '</div>';
}
