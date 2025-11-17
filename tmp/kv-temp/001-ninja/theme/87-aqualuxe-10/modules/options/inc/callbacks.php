<?php
/**
 * Sanitization callbacks for the Customizer.
 *
 * @package Aqualuxe
 * @subpackage Modules/Options
 */

namespace Aqualuxe\Modules\Options;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Sanitizes image uploads.
 *
 * Accepts an image URL or an attachment ID.
 *
 * @param string|int $input The input to sanitize.
 * @return string The sanitized image URL.
 */
function sanitize_image( $input ): string {
	if ( is_numeric( $input ) ) {
		$attachment_id = absint( $input );
		$url = wp_get_attachment_url( $attachment_id );
		return $url ? esc_url_raw( $url ) : '';
	}
	return esc_url_raw( $input );
}
