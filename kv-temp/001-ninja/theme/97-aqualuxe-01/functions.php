<?php
/**
 * Functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Core theme setup.
require_once get_template_directory() . '/inc/class-aqualuxe-theme.php';

/**
 * Begins execution of the theme.
 *
 * Since everything within the theme is registered via hooks,
 * kicking off the theme from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function aqualuxe_run() {
	new AquaLuxe_Theme();
}
aqualuxe_run();
