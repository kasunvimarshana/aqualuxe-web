<?php
/**
 * Theme bootstrap for AquaLuxe
 *
 * @package aqualuxe
 */

define( 'AQUALUXE_VERSION', '1.0.0' );
define( 'AQUALUXE_TEXT_DOMAIN', 'aqualuxe' );
define( 'AQUALUXE_PATH', trailingslashit( get_template_directory() ) );
define( 'AQUALUXE_URI', trailingslashit( get_template_directory_uri() ) );

// Autoload classes following PSR-4-like structure inside inc/.
spl_autoload_register( function ( $class ) {
	if ( strpos( $class, 'AquaLuxe\\' ) !== 0 ) {
		return;
	}
	$relative = str_replace( [ 'AquaLuxe\\', '\\' ], [ '', '/' ], $class );
	$file     = AQUALUXE_PATH . 'inc/' . strtolower( $relative ) . '.php';
	if ( file_exists( $file ) ) {
		require_once $file;
	}
} );

// Require core bootstrap.
require_once AQUALUXE_PATH . 'inc/core/bootstrap.php';

// Initialize theme.
add_action( 'after_setup_theme', function () {
	AquaLuxe\Core\Bootstrap::init();
} );

