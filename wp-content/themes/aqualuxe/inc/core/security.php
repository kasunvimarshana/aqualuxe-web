<?php
/**
 * Security hardening
 *
 * @package AquaLuxe\Core
 */

namespace AquaLuxe\Core;

if ( ! defined( 'ABSPATH' ) ) { exit; }

// Disable XML-RPC if not needed.
\add_filter( 'xmlrpc_enabled', '__return_false' );

// Remove WP version from head and feeds.
\add_filter( 'the_generator', '__return_empty_string' );

// Set secure headers where possible (best effort in theme).
\add_action( 'send_headers', function () {
	header( 'X-Content-Type-Options: nosniff' );
	header( 'X-Frame-Options: SAMEORIGIN' );
	header( 'Referrer-Policy: no-referrer-when-downgrade' );
	header( 'X-XSS-Protection: 0' );
} );

// Sanitize file uploads via MIME validation hint (actual enforcement via server config/plugins recommended).
\add_filter( 'wp_check_filetype_and_ext', function ( $types, $file, $filename, $mimes ) {
	return \wp_check_filetype( $filename, $mimes );
}, 10, 4 );
