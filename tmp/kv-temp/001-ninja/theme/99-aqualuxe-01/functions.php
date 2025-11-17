<?php
/**
 * AquaLuxe Theme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package AquaLuxe
 */

// Define theme constants for paths and version.
if ( ! defined( 'AQUALUXE_VERSION' ) ) {
	define( 'AQUALUXE_VERSION', '1.0.0' );
}
if ( ! defined( 'AQUALUXE_THEME_DIR' ) ) {
	define( 'AQUALUXE_THEME_DIR', get_template_directory() );
}
if ( ! defined( 'AQUALUXE_THEME_URI' ) ) {
	define( 'AQUALUXE_THEME_URI', get_template_directory_uri() );
}

// Core theme setup and includes.
require_once AQUALUXE_THEME_DIR . '/inc/class-aqualuxe-theme.php';

/**
 * Initialize the main theme class.
 *
 * Ensures the theme is loaded only once.
 */
function aqualuxe_get_theme_instance() {
	return AquaLuxe_Theme::get_instance();
}
aqualuxe_get_theme_instance();
