<?php
/**
 * Security Hardening for AquaLuxe.
 *
 * @package AquaLuxe
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * The main security hardening class.
 */
class AquaLuxe_Security {

	/**
	 * Initialize security hardening measures.
	 */
	public static function init() {
		// Remove WordPress version number.
		remove_action( 'wp_head', 'wp_generator' );

		// Disable XML-RPC.
		add_filter( 'xmlrpc_enabled', '__return_false' );

		// Disable file editing from the dashboard.
		if ( ! defined( 'DISALLOW_FILE_EDIT' ) ) {
			define( 'DISALLOW_FILE_EDIT', true );
		}
	}
}
