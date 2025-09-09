<?php
/**
 * AquaLuxe Theme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package AquaLuxe
 */

// Define theme constants
define( 'AQUALUXE_VERSION', '1.0.0' );
define( 'AQUALUXE_DIR', __DIR__ );
define( 'AQUALUXE_URL', get_stylesheet_directory_uri() );
define( 'AQUALUXE_APP_DIR', AQUALUXE_DIR . '/app' );

// Bootstrap the application
require_once AQUALUXE_APP_DIR . '/bootstrap/app.php';
