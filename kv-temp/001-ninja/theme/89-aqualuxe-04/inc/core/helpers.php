<?php
/**
 * Helper functions
 *
 * @package AquaLuxe\Core
 */

namespace AquaLuxe\Core;

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Read mix-manifest.json and resolve versioned asset path.
 * Never enqueue raw src files.
 */
function asset_uri( string $path ): string {
	$manifest_path = AQUALUXE_DIR . '/assets/dist/mix-manifest.json';
	$dist_uri      = AQUALUXE_URI . '/assets/dist';
	$path          = '/' . ltrim( $path, '/' );
	if ( file_exists( $manifest_path ) ) {
		$manifest = json_decode( (string) file_get_contents( $manifest_path ), true );
		if ( isset( $manifest[ $path ] ) ) {
			return $dist_uri . $manifest[ $path ];
		}
	}
	return $dist_uri . $path; // Fallback in dev.
}

/** Sanitize array recursively */
function sanitize_array( $data ) {
	if ( is_array( $data ) ) {
		return array_map( __NAMESPACE__ . '\\sanitize_array', $data );
	}
	return is_scalar( $data ) ? \sanitize_text_field( (string) $data ) : $data;
}

/** Nonce helper */
function nonce_field( string $action, string $name = '_alx_nonce' ) {
	\wp_nonce_field( $action, $name );
}

/** Verify nonce */
function verify_nonce( string $action, string $name = '_alx_nonce' ): bool {
	return (bool) ( isset( $_POST[ $name ] ) && \wp_verify_nonce( \sanitize_text_field( \wp_unslash( $_POST[ $name ] ) ), $action ) );
}

/** Feature detection */
function has_woocommerce(): bool { return class_exists( 'WooCommerce' ); }
