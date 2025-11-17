<?php
/**
 * AquaLuxe Theme Functions
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Define theme version.
if ( ! defined( 'AQUALUXE_VERSION' ) ) {
	define( 'AQUALUXE_VERSION', '1.0.0' );
}

// Define theme directory path.
if ( ! defined( 'AQUALUXE_PATH' ) ) {
	define( 'AQUALUXE_PATH', get_template_directory() );
}

// Define theme directory URI.
if ( ! defined( 'AQUALUXE_URI' ) ) {
	define( 'AQUALUXE_URI', get_template_directory_uri() );
}

// Include theme setup.
require_once AQUALUXE_PATH . '/inc/theme-setup.php';

// Include enqueue scripts and styles.
require_once AQUALUXE_PATH . '/inc/enqueue.php';

// Include customizer settings.
require_once AQUALUXE_PATH . '/inc/customizer.php';

// Include template functions.
require_once AQUALUXE_PATH . '/inc/template-functions.php';

// Include template tags.
require_once AQUALUXE_PATH . '/inc/template-tags.php';

// Include WooCommerce functions if WooCommerce is active.
if ( class_exists( 'WooCommerce' ) ) {
	require_once AQUALUXE_PATH . '/inc/woocommerce.php';
}

// Include demo content importer.
require_once AQUALUXE_PATH . '/inc/demo-importer.php';