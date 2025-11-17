<?php
/**
 * AquaLuxe functions and definitions
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
require_once AQUALUXE_THEME_DIR . '/core/class-aqualuxe-theme-setup.php';

// Initialize the theme.
if ( class_exists( 'AquaLuxe_Theme_Setup' ) ) {
	AquaLuxe_Theme_Setup::instance()->init();
}
