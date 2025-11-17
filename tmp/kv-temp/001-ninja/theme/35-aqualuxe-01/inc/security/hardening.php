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
 */
if ( ! defined( 'DISALLOW_FILE_EDIT' ) ) {
	define( 'DISALLOW_FILE_EDIT', true );
}

/**
 * Remove WordPress version number from various places.
 *
 * @return string Empty string.
 */
function aqualuxe_remove_version() {
	return '';
}
add_filter( 'the_generator', 'aqualuxe_remove_version' );

/**
 * Remove WordPress version from RSS feeds.
 */
function aqualuxe_remove_version_from_rss() {
	return '';
}
add_filter( 'the_generator', 'aqualuxe_remove_version_from_rss' );

/**
 * Remove WordPress version from scripts and styles.
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
 * Disable XML-RPC.
 */
add_filter( 'xmlrpc_enabled', '__return_false' );

/**
 * Remove X-Pingback header.
 *
 * @param array $headers HTTP headers.
 * @return array Modified headers.
 */
function aqualuxe_remove_x_pingback( $headers ) {
	unset( $headers['X-Pingback'] );
	return $headers;
}
add_filter( 'wp_headers', 'aqualuxe_remove_x_pingback' );

/**
 * Add security headers.
 *
 * @param array $headers HTTP headers.
 * @return array Modified headers.
 */
function aqualuxe_add_security_headers( $headers ) {
	// Prevent MIME type sniffing.
	$headers['X-Content-Type-Options'] = 'nosniff';

	// Prevent clickjacking.
	$headers['X-Frame-Options'] = 'SAMEORIGIN';

	// Enable browser XSS protection.
	$headers['X-XSS-Protection'] = '1; mode=block';

	// Referrer policy.
	$headers['Referrer-Policy'] = 'strict-origin-when-cross-origin';

	return $headers;
}
add_filter( 'wp_headers', 'aqualuxe_add_security_headers' );

/**
 * Disable REST API for unauthenticated users if needed.
 * Note: This may break some plugins or Gutenberg, so use with caution.
 *
 * @param WP_Error|bool $access Current access state.
 * @return WP_Error|bool Modified access state.
 */
function aqualuxe_restrict_rest_api( $access ) {
	// Uncomment the following to restrict REST API to authenticated users only.
	// if ( ! is_user_logged_in() ) {
	//     return new WP_Error( 'rest_api_restricted', esc_html__( 'REST API restricted to authenticated users.', 'aqualuxe' ), array( 'status' => rest_authorization_required_code() ) );
	// }
	return $access;
}
// Uncomment the following line to enable REST API restriction.
// add_filter( 'rest_authentication_errors', 'aqualuxe_restrict_rest_api' );

/**
 * Disable user enumeration.
 *
 * @param WP_Error $errors Errors object.
 * @param string   $redirect_to Redirect URL.
 * @return WP_Error Modified errors object.
 */
function aqualuxe_disable_user_enumeration( $errors, $redirect_to ) {
	if ( ! empty( $_GET['author'] ) ) {
		$errors = new WP_Error( 'forbidden', esc_html__( 'User enumeration is not allowed.', 'aqualuxe' ) );
	}
	return $errors;
}
add_filter( 'wp_login_errors', 'aqualuxe_disable_user_enumeration', 10, 2 );

/**
 * Sanitize filename on upload.
 *
 * @param string $filename The filename.
 * @return string Sanitized filename.
 */
function aqualuxe_sanitize_file_name( $filename ) {
	$filename = remove_accents( $filename );
	$filename = sanitize_file_name( $filename );
	return $filename;
}
add_filter( 'sanitize_file_name', 'aqualuxe_sanitize_file_name', 10, 1 );

/**
 * Add nonce to logout URL.
 *
 * @param string $logout_url The logout URL.
 * @param string $redirect The redirect URL.
 * @return string Modified logout URL.
 */
function aqualuxe_secure_logout_url( $logout_url, $redirect ) {
	return wp_nonce_url( $logout_url, 'log-out' );
}
add_filter( 'logout_url', 'aqualuxe_secure_logout_url', 10, 2 );

/**
 * Verify nonce on logout.
 */
function aqualuxe_verify_logout_nonce() {
	if ( isset( $_GET['action'] ) && 'logout' === $_GET['action'] ) {
		check_admin_referer( 'log-out' );
	}
}
add_action( 'wp_logout', 'aqualuxe_verify_logout_nonce' );

/**
 * Disable author archives for security.
 */
function aqualuxe_disable_author_archives() {
	if ( is_author() ) {
		wp_redirect( home_url(), 301 );
		exit;
	}
}
add_action( 'template_redirect', 'aqualuxe_disable_author_archives' );

/**
 * Remove author from RSS feeds.
 *
 * @param string $content The content.
 * @return string Modified content.
 */
function aqualuxe_remove_rss_author( $content ) {
	return preg_replace( '/<dc:creator>.*?<\/dc:creator>/i', '', $content );
}
add_filter( 'the_content_feed', 'aqualuxe_remove_rss_author' );
add_filter( 'the_excerpt_rss', 'aqualuxe_remove_rss_author' );