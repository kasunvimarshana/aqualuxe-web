<?php
/**
 * AquaLuxe Pro theme functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package AquaLuxe
 */

if ( ! defined( 'AQUALUXE_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( 'AQUALUXE_VERSION', '1.0.0' );
}

if ( ! defined( 'AQUALUXE_THEME_DIR' ) ) {
	define( 'AQUALUXE_THEME_DIR', __DIR__ );
}

if ( ! defined( 'AQUALUXE_THEME_URI' ) ) {
	define( 'AQUALUXE_THEME_URI', get_template_directory_uri() );
}

// Core theme setup.
require_once AQUALUXE_THEME_DIR . '/inc/core/class-aqualuxe-theme.php';

/**
 * Begins execution of the theme.
 *
 * Since everything within the theme is registered via hooks,
 * kicking off the theme from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_aqualuxe_theme() {
	$theme = new AquaLuxe_Theme();
	$theme->run();
}
run_aqualuxe_theme();

/**
 * WooCommerce compatibility file.
 */
if ( class_exists( 'WooCommerce' ) ) {
    require AQUALUXE_THEME_DIR . '/woocommerce.php';
}

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';
