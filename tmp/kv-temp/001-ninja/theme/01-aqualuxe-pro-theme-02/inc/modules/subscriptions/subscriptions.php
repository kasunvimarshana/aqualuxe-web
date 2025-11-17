<?php
/**
 * AquaLuxe Subscriptions Module
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Load compatibility file if WooCommerce Subscriptions is active.
if ( class_exists( 'WC_Subscriptions' ) ) {
    require_once AQUALUXE_THEME_DIR . '/inc/modules/subscriptions/wc-subscriptions.php';
}
