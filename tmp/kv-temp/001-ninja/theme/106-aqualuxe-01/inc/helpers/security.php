<?php
/**
 * Security helpers.
 *
 * @package AquaLuxe
 */

declare( strict_types=1 );

if ( ! defined( 'ABSPATH' ) ) { exit; }

/** Sanitize array recursively (basic). */
function aqualuxe_recursive_sanitize_text( $data ) {
	if ( is_array( $data ) ) {
		return array_map( 'aqualuxe_recursive_sanitize_text', $data );
	}
	return is_scalar( $data ) ? sanitize_text_field( (string) $data ) : $data;
}

/** Verify nonce quickly. */
function aqualuxe_verify_nonce_or_die( string $action, string $field = '_wpnonce' ): void {
	$nonce = $_REQUEST[ $field ] ?? ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	if ( ! wp_verify_nonce( $nonce, $action ) ) {
		wp_send_json_error( [ 'message' => __( 'Security check failed.', 'aqualuxe' ) ], 403 );
	}
}
