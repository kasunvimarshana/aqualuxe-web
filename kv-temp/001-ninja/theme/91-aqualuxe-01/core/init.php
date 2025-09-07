<?php
/**
 * Core theme initialization.
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Include core files.
require_once AQUALUXE_THEME_DIR . 'core/setup.php';
require_once AQUALUXE_THEME_DIR . 'core/scripts.php';
require_once AQUALUXE_THEME_DIR . 'core/helpers.php';
require_once AQUALUXE_THEME_DIR . 'core/custom_post_types.php';
require_once AQUALUXE_THEME_DIR . 'core/custom_taxonomies.php';

// WooCommerce compatibility.
if ( class_exists( 'WooCommerce' ) ) {
    require_once AQUALUXE_THEME_DIR . 'woocommerce.php';
}
