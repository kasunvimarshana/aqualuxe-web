<?php
/**
 * AquaLuxe Theme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Load the main theme class.
require_once __DIR__ . '/core/class_aqualuxe_theme.php';

/**
 * Begins execution of the theme.
 *
 * Since everything within the theme is registered via hooks,
 * kicking off the theme from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.1.0
 */
\AquaLuxe\Core\AquaLuxe_Theme::instance();
