<?php
/**
 * Security Hardening Functions
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Disable file editing in the WordPress admin
 */
function aqualuxe_disable_file_editing() {
	if ( ! defined( 'DISALLOW_FILE_EDIT' ) ) {
		define( 'DISALLOW_FILE_EDIT', true );
	}
}
add_action( 'init', 'aqualuxe_disable_file_editing' );

/**
 * Add security headers
 */
function aqualuxe_security_headers() {
	// X-Content-Type-Options
	header( 'X-Content-Type-Options: nosniff' );
	
	// X-Frame-Options
	header( 'X-Frame-Options: SAMEORIGIN' );
	
	// X-XSS-Protection
	header( 'X-XSS-Protection: 1; mode=block' );
	
	// Referrer-Policy
	header( 'Referrer-Policy: strict-origin-when-cross-origin' );
	
	// Permissions-Policy (formerly Feature-Policy)
	header( 'Permissions-Policy: camera=(), microphone=(), geolocation=()' );
}
add_action( 'send_headers', 'aqualuxe_security_headers' );

/**
 * Remove WordPress version number from various places
 */
function aqualuxe_remove_version() {
	return '';
}
add_filter( 'the_generator', 'aqualuxe_remove_version' );

/**
 * Remove WordPress version from RSS feeds
 */
function aqualuxe_remove_version_from_rss() {
	return '';
}
add_filter( 'the_generator', 'aqualuxe_remove_version_from_rss' );

/**
 * Remove WordPress version from scripts and styles
 *
 * @param string $src Script or style source URL.
 * @return string Modified URL.
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
 * Disable XML-RPC
 */
function aqualuxe_disable_xmlrpc() {
	// Disable XML-RPC methods that require authentication
	add_filter( 'xmlrpc_enabled', '__return_false' );
	
	// Disable X-Pingback header
	add_filter( 'wp_headers', 'aqualuxe_disable_x_pingback' );
}
add_action( 'init', 'aqualuxe_disable_xmlrpc' );

/**
 * Remove X-Pingback header
 *
 * @param array $headers HTTP headers.
 * @return array Modified headers.
 */
function aqualuxe_disable_x_pingback( $headers ) {
	unset( $headers['X-Pingback'] );
	return $headers;
}

/**
 * Disable REST API for unauthenticated users if needed
 * Note: This is commented out by default as it may break some legitimate functionality
 */
/*
function aqualuxe_restrict_rest_api( $result ) {
	if ( ! is_user_logged_in() ) {
		return new WP_Error( 'rest_api_restricted', esc_html__( 'REST API restricted to authenticated users.', 'aqualuxe' ), array( 'status' => 401 ) );
	}
	return $result;
}
add_filter( 'rest_authentication_errors', 'aqualuxe_restrict_rest_api' );
*/

/**
 * Block suspicious queries
 *
 * @param string $query The query.
 * @return string|bool The query or false if blocked.
 */
function aqualuxe_block_suspicious_queries( $query ) {
	if ( ! current_user_can( 'administrator' ) ) {
		// Block queries containing suspicious SQL
		$suspicious = array(
			'SELECT',
			'UNION',
			'TRUNCATE',
			'DROP',
			'CONCAT',
			'DELETE',
			'CHAR(',
			'UPDATE',
			'INSERT',
			'EXEC',
		);
		
		foreach ( $suspicious as $pattern ) {
			if ( stripos( $query, $pattern ) !== false ) {
				return false;
			}
		}
	}
	
	return $query;
}
add_filter( 'query', 'aqualuxe_block_suspicious_queries' );

/**
 * Disable user enumeration
 *
 * @param object $query The WP_Query object.
 */
function aqualuxe_disable_user_enumeration( $query ) {
	if ( ! is_admin() && ! current_user_can( 'list_users' ) ) {
		if ( isset( $query->query_vars['author_name'] ) ) {
			$query->query_vars['author_name'] = '';
		}
		
		if ( isset( $query->query_vars['author'] ) ) {
			$query->query_vars['author'] = '';
		}
	}
}
add_action( 'pre_get_posts', 'aqualuxe_disable_user_enumeration' );

/**
 * Add nonce field to login form
 */
function aqualuxe_add_login_nonce() {
	wp_nonce_field( 'aqualuxe_login_nonce', 'aqualuxe_login_nonce_field' );
}
add_action( 'login_form', 'aqualuxe_add_login_nonce' );

/**
 * Verify login nonce
 *
 * @param string $user_login Username.
 * @param object $user WP_User object.
 */
function aqualuxe_verify_login_nonce( $user_login, $user ) {
	if ( ! isset( $_POST['aqualuxe_login_nonce_field'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['aqualuxe_login_nonce_field'] ) ), 'aqualuxe_login_nonce' ) ) {
		wp_nonce_ays( 'aqualuxe_login_nonce' );
	}
}
add_action( 'wp_login', 'aqualuxe_verify_login_nonce', 10, 2 );

/**
 * Add nonce field to comment form
 *
 * @param array $fields Comment form fields.
 * @return array Modified comment form fields.
 */
function aqualuxe_add_comment_nonce( $fields ) {
	$fields['aqualuxe_comment_nonce'] = wp_nonce_field( 'aqualuxe_comment_nonce', 'aqualuxe_comment_nonce_field', true, false );
	return $fields;
}
add_filter( 'comment_form_default_fields', 'aqualuxe_add_comment_nonce' );

/**
 * Verify comment nonce
 *
 * @param array $commentdata Comment data.
 * @return array Modified comment data.
 */
function aqualuxe_verify_comment_nonce( $commentdata ) {
	if ( ! isset( $_POST['aqualuxe_comment_nonce_field'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['aqualuxe_comment_nonce_field'] ) ), 'aqualuxe_comment_nonce' ) ) {
		wp_die(
			esc_html__( 'Security check failed. Please try again.', 'aqualuxe' ),
			esc_html__( 'Comment Security Error', 'aqualuxe' ),
			array(
				'response'  => 403,
				'back_link' => true,
			)
		);
	}
	
	return $commentdata;
}
add_filter( 'preprocess_comment', 'aqualuxe_verify_comment_nonce' );

/**
 * Sanitize input data
 *
 * @param mixed $input Input data.
 * @return mixed Sanitized input data.
 */
function aqualuxe_sanitize_input( $input ) {
	if ( is_array( $input ) ) {
		foreach ( $input as $key => $value ) {
			$input[ $key ] = aqualuxe_sanitize_input( $value );
		}
		return $input;
	}
	
	return sanitize_text_field( $input );
}

/**
 * Sanitize textarea input
 *
 * @param string $input Textarea input.
 * @return string Sanitized textarea input.
 */
function aqualuxe_sanitize_textarea( $input ) {
	return sanitize_textarea_field( $input );
}

/**
 * Sanitize URL input
 *
 * @param string $input URL input.
 * @return string Sanitized URL input.
 */
function aqualuxe_sanitize_url( $input ) {
	return esc_url_raw( $input );
}

/**
 * Sanitize email input
 *
 * @param string $input Email input.
 * @return string Sanitized email input.
 */
function aqualuxe_sanitize_email( $input ) {
	return sanitize_email( $input );
}

/**
 * Sanitize hex color input
 *
 * @param string $input Hex color input.
 * @return string Sanitized hex color input.
 */
function aqualuxe_sanitize_hex_color( $input ) {
	return sanitize_hex_color( $input );
}

/**
 * Sanitize select input
 *
 * @param string $input Select input.
 * @param object $setting Setting object.
 * @return string Sanitized select input.
 */
function aqualuxe_sanitize_select( $input, $setting ) {
	$choices = $setting->manager->get_control( $setting->id )->choices;
	return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
}

/**
 * Sanitize checkbox input
 *
 * @param bool $input Checkbox input.
 * @return bool Sanitized checkbox input.
 */
function aqualuxe_sanitize_checkbox( $input ) {
	return ( isset( $input ) && true === $input );
}

/**
 * Sanitize number input
 *
 * @param int $input Number input.
 * @param object $setting Setting object.
 * @return int Sanitized number input.
 */
function aqualuxe_sanitize_number( $input, $setting ) {
	$input = absint( $input );
	$min   = isset( $setting->manager->get_control( $setting->id )->input_attrs['min'] ) ? $setting->manager->get_control( $setting->id )->input_attrs['min'] : false;
	$max   = isset( $setting->manager->get_control( $setting->id )->input_attrs['max'] ) ? $setting->manager->get_control( $setting->id )->input_attrs['max'] : false;
	$step  = isset( $setting->manager->get_control( $setting->id )->input_attrs['step'] ) ? $setting->manager->get_control( $setting->id )->input_attrs['step'] : false;
	
	if ( $min && $input < $min ) {
		$input = $min;
	}
	
	if ( $max && $input > $max ) {
		$input = $max;
	}
	
	if ( $step && ( $input % $step ) !== 0 ) {
		$input = round( $input / $step ) * $step;
	}
	
	return $input;
}