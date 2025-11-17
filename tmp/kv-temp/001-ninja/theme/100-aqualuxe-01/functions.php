<?php
/**
 * Theme functions and definitions.
 *
 * @package AquaLuxe
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Define theme constants.
define( 'AQUALUXE_VERSION', '1.0.0' );
define( 'AQUALUXE_THEME_DIR', get_template_directory() );
define( 'AQUALUXE_THEME_URI', get_template_directory_uri() );

// Core theme setup.
require_once AQUALUXE_THEME_DIR . '/core/setup.php';

// Enqueue scripts and styles.
require_once AQUALUXE_THEME_DIR . '/core/enqueue.php';

// Template functions.
require_once AQUALUXE_THEME_DIR . '/core/template-functions.php';

// Template tags.
require_once AQUALUXE_THEME_DIR . '/core/template-tags.php';

// WooCommerce configuration.
if ( class_exists( 'WooCommerce' ) ) {
	require_once AQUALUXE_THEME_DIR . '/core/woocommerce.php';
}

// Load modules.
require_once AQUALUXE_THEME_DIR . '/modules/loader.php';
