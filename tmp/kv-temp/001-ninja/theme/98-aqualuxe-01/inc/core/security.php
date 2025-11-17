<?php
namespace AquaLuxe\Core;

/**
 * Security hardening utils and global nonce policy.
 */
final class Security {
	public static function init(): void {
		\add_action( 'send_headers', [ __CLASS__, 'headers' ] );
		\add_action( 'init', [ __CLASS__, 'register_nonces' ] );
	}

	public static function headers(): void {
		\header( 'X-Frame-Options: SAMEORIGIN' );
		\header( 'X-Content-Type-Options: nosniff' );
		\header( 'Referrer-Policy: no-referrer-when-downgrade' );
		// Allow images/fonts cross-origin when needed, customize per env.
		// header( 'Cross-Origin-Resource-Policy: same-site' );
	}

	public static function register_nonces(): void {
		// Reserved for registering nonces/actions. See Assets::enqueue_front() for global nonce.
	}

	public static function verify_nonce( string $nonce, string $action = 'aqualuxe_nonce' ): bool {
		return \wp_verify_nonce( $nonce, $action ) !== false;
	}

	public static function sanitize_text( $value ): string {
		return is_string( $value ) ? \sanitize_text_field( $value ) : '';
	}
}
