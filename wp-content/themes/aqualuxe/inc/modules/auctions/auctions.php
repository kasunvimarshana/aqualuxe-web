<?php
/**
 * AquaLuxe Auctions Module
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Load compatibility file if WooCommerce Simple Auctions is active.
if ( class_exists( 'WooCommerce_simple_auctions' ) ) {
    require_once AQUALUXE_THEME_DIR . '/inc/modules/auctions/wc-auctions.php';
}
