<?php
/**
 * Bookings compatibility.
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Load WooCommerce Bookings compatibility file if the plugin is active.
 */
if ( class_exists( 'WC_Bookings' ) ) {
    require_once AQUALUXE_THEME_DIR . '/inc/modules/bookings/wc-bookings.php';
}
