<?php
/**
 * Security hardening functions
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Disable file editing in the WordPress admin.
 * This prevents users from editing plugin and theme files directly from the admin.
 */
if ( ! defined( 'DISALLOW_FILE_EDIT' ) ) {
	define( 'DISALLOW_FILE_EDIT', true );
}

/**
 * Remove WordPress version number from various places
 *
 * @return string Empty string.
 */
function aqualuxe_remove_version() {
	return '';
}
add_filter( 'the_generator', 'aqualuxe_remove_version' );

/**
 * Remove WordPress version from RSS feeds
 */
add_filter( 'the_generator', 'aqualuxe_remove_version' );

/**
 * Remove WordPress version from scripts and styles
 *
 * @param string $src The source URL of the enqueued style or script.
 * @return string The modified source URL.
 */
function aqualuxe_remove_wp_version_strings( $src ) {
	global $wp_version;
	
	$parts = explode( '?ver=', $src );
	if ( ! empty( $parts[1] ) && $parts[1] === $wp_version ) {
		return $parts[0] . '?ver=' . AQUALUXE_VERSION;
	}
	
	return $src;
}
add_filter( 'script_loader_src', 'aqualuxe_remove_wp_version_strings' );
add_filter( 'style_loader_src', 'aqualuxe_remove_wp_version_strings' );

/**
 * Disable XML-RPC functionality if not needed
 */
function aqualuxe_disable_xmlrpc() {
	// Check if XML-RPC should be disabled (can be controlled via theme options).
	$disable_xmlrpc = apply_filters( 'aqualuxe_disable_xmlrpc', true );
	
	if ( $disable_xmlrpc ) {
		add_filter( 'xmlrpc_enabled', '__return_false' );
		add_filter( 'wp_headers', 'aqualuxe_remove_x_pingback' );
	}
}
add_action( 'init', 'aqualuxe_disable_xmlrpc' );

/**
 * Remove X-Pingback header
 *
 * @param array $headers The list of headers to be sent.
 * @return array Modified headers.
 */
function aqualuxe_remove_x_pingback( $headers ) {
	unset( $headers['X-Pingback'] );
	return $headers;
}

/**
 * Add security headers
 *
 * @param array $headers The list of headers to be sent.
 * @return array Modified headers.
 */
function aqualuxe_security_headers( $headers ) {
	// X-Content-Type-Options: Prevents MIME type sniffing.
	$headers['X-Content-Type-Options'] = 'nosniff';
	
	// X-XSS-Protection: Enables XSS filtering in browsers.
	$headers['X-XSS-Protection'] = '1; mode=block';
	
	// X-Frame-Options: Prevents your site from being framed by other sites.
	$headers['X-Frame-Options'] = 'SAMEORIGIN';
	
	// Referrer-Policy: Controls how much referrer information is included with requests.
	$headers['Referrer-Policy'] = 'strict-origin-when-cross-origin';
	
	// Permissions-Policy: Controls which features and APIs can be used.
	$headers['Permissions-Policy'] = 'geolocation=(), microphone=(), camera=()';
	
	return $headers;
}
add_filter( 'wp_headers', 'aqualuxe_security_headers' );

/**
 * Disable REST API for unauthenticated users if configured
 *
 * @param WP_Error|null|bool $result WP_Error if authentication error, null if authentication
 *                                    method wasn't used, true if authentication succeeded.
 * @return WP_Error|null|bool
 */
function aqualuxe_restrict_rest_api( $result ) {
	// Check if REST API should be restricted (can be controlled via theme options).
	$restrict_rest_api = apply_filters( 'aqualuxe_restrict_rest_api', false );
	
	if ( $restrict_rest_api && ! is_user_logged_in() ) {
		return new WP_Error(
			'rest_not_logged_in',
			__( 'You are not currently logged in.', 'aqualuxe' ),
			[ 'status' => 401 ]
		);
	}
	
	return $result;
}
// Uncomment the line below to enable REST API restriction (disabled by default).
// add_filter( 'rest_authentication_errors', 'aqualuxe_restrict_rest_api' );

/**
 * Disable user enumeration
 *
 * @param WP_Error $errors Errors object.
 * @return WP_Error
 */
function aqualuxe_disable_user_enumeration( $errors ) {
	if ( is_wp_error( $errors ) ) {
		$error_codes = $errors->get_error_codes();
		
		if ( in_array( 'invalid_username', $error_codes, true ) || in_array( 'incorrect_password', $error_codes, true ) ) {
			$errors = new WP_Error(
				'invalid_login',
				__( '<strong>Error</strong>: Invalid username or password.', 'aqualuxe' ),
				'login'
			);
		}
	}
	
	return $errors;
}
add_filter( 'wp_login_errors', 'aqualuxe_disable_user_enumeration' );

/**
 * Block username enumeration via author parameter
 */
function aqualuxe_block_user_enumeration() {
	if ( isset( $_GET['author'] ) && ! is_admin() && ! current_user_can( 'edit_posts' ) ) {
		wp_safe_redirect( home_url(), 301 );
		exit;
	}
}
add_action( 'template_redirect', 'aqualuxe_block_user_enumeration' );

/**
 * Verify nonces for all admin actions
 */
function aqualuxe_verify_nonce() {
	if ( ! isset( $_REQUEST['_wpnonce'] ) || ! wp_verify_nonce( sanitize_key( $_REQUEST['_wpnonce'] ), 'aqualuxe-nonce' ) ) {
		return;
	}
	
	// Process the admin action here.
}
add_action( 'admin_init', 'aqualuxe_verify_nonce' );

/**
 * Sanitize all inputs
 *
 * @param string $input The input to sanitize.
 * @return string Sanitized input.
 */
function aqualuxe_sanitize_input( $input ) {
	return sanitize_text_field( $input );
}

/**
 * Sanitize textarea inputs
 *
 * @param string $input The input to sanitize.
 * @return string Sanitized input.
 */
function aqualuxe_sanitize_textarea( $input ) {
	return sanitize_textarea_field( $input );
}

/**
 * Sanitize URL inputs
 *
 * @param string $input The input to sanitize.
 * @return string Sanitized input.
 */
function aqualuxe_sanitize_url( $input ) {
	return esc_url_raw( $input );
}

/**
 * Sanitize email inputs
 *
 * @param string $input The input to sanitize.
 * @return string Sanitized input.
 */
function aqualuxe_sanitize_email( $input ) {
	return sanitize_email( $input );
}

/**
 * Sanitize hex color inputs
 *
 * @param string $input The input to sanitize.
 * @return string Sanitized input.
 */
function aqualuxe_sanitize_hex_color( $input ) {
	return sanitize_hex_color( $input );
}

/**
 * Sanitize checkbox inputs
 *
 * @param bool $input The input to sanitize.
 * @return bool Sanitized input.
 */
function aqualuxe_sanitize_checkbox( $input ) {
	return ( isset( $input ) && true === $input ) ? true : false;
}

/**
 * Sanitize select inputs
 *
 * @param string $input The input to sanitize.
 * @param object $setting The setting object.
 * @return string Sanitized input.
 */
function aqualuxe_sanitize_select( $input, $setting ) {
	// Get the list of choices from the control associated with the setting.
	$choices = $setting->manager->get_control( $setting->id )->choices;
	
	// Return input if valid or return default option.
	return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
}