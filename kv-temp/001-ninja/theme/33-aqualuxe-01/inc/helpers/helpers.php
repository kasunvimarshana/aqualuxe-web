<?php
/**
 * Helper functions for the theme
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Sanitize checkbox.
 *
 * @param bool $checked Whether the checkbox is checked.
 * @return bool
 */
function aqualuxe_sanitize_checkbox( $checked ) {
	return ( isset( $checked ) && true === $checked ) ? true : false;
}

/**
 * Sanitize number.
 *
 * @param int $number Number to sanitize.
 * @param int $min Minimum value.
 * @param int $max Maximum value.
 * @return int
 */
function aqualuxe_sanitize_number( $number, $min = 0, $max = 100 ) {
	return max( $min, min( $max, absint( $number ) ) );
}

/**
 * Sanitize float.
 *
 * @param float $number Number to sanitize.
 * @param float $min Minimum value.
 * @param float $max Maximum value.
 * @return float
 */
function aqualuxe_sanitize_float( $number, $min = 0, $max = 100 ) {
	return max( $min, min( $max, floatval( $number ) ) );
}

/**
 * Sanitize select.
 *
 * @param string $input Select value.
 * @param object $setting Setting object.
 * @return string
 */
function aqualuxe_sanitize_select( $input, $setting ) {
	// Get the list of possible select options.
	$choices = $setting->manager->get_control( $setting->id )->choices;
	
	// Return input if valid or return default option.
	return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
}

/**
 * Sanitize radio.
 *
 * @param string $input Radio value.
 * @param object $setting Setting object.
 * @return string
 */
function aqualuxe_sanitize_radio( $input, $setting ) {
	// Get the list of possible radio options.
	$choices = $setting->manager->get_control( $setting->id )->choices;
	
	// Return input if valid or return default option.
	return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
}

/**
 * Sanitize hex color.
 *
 * @param string $color Color to sanitize.
 * @return string
 */
function aqualuxe_sanitize_hex_color( $color ) {
	if ( '' === $color ) {
		return '';
	}
	
	// 3 or 6 hex digits, or the empty string.
	if ( preg_match( '|^#([A-Fa-f0-9]{3}){1,2}$|', $color ) ) {
		return $color;
	}
	
	return '';
}

/**
 * Sanitize rgba color.
 *
 * @param string $color Color to sanitize.
 * @return string
 */
function aqualuxe_sanitize_rgba_color( $color ) {
	if ( '' === $color ) {
		return '';
	}
	
	// rgba color.
	if ( preg_match( '/^rgba\(\s*(\d+),\s*(\d+),\s*(\d+),\s*(\d*(?:\.\d+)?)\s*\)$/', $color, $matches ) ) {
		$red   = aqualuxe_sanitize_number( $matches[1], 0, 255 );
		$green = aqualuxe_sanitize_number( $matches[2], 0, 255 );
		$blue  = aqualuxe_sanitize_number( $matches[3], 0, 255 );
		$alpha = aqualuxe_sanitize_float( $matches[4], 0, 1 );
		
		return 'rgba(' . $red . ',' . $green . ',' . $blue . ',' . $alpha . ')';
	}
	
	// rgb color.
	if ( preg_match( '/^rgb\(\s*(\d+),\s*(\d+),\s*(\d+)\s*\)$/', $color, $matches ) ) {
		$red   = aqualuxe_sanitize_number( $matches[1], 0, 255 );
		$green = aqualuxe_sanitize_number( $matches[2], 0, 255 );
		$blue  = aqualuxe_sanitize_number( $matches[3], 0, 255 );
		
		return 'rgb(' . $red . ',' . $green . ',' . $blue . ')';
	}
	
	// hex color.
	if ( preg_match( '|^#([A-Fa-f0-9]{3}){1,2}$|', $color ) ) {
		return $color;
	}
	
	return '';
}

/**
 * Sanitize dimensions.
 *
 * @param array $dimensions Dimensions to sanitize.
 * @return array
 */
function aqualuxe_sanitize_dimensions( $dimensions ) {
	if ( ! is_array( $dimensions ) ) {
		return array();
	}
	
	$sanitized_dimensions = array();
	
	foreach ( $dimensions as $key => $value ) {
		$sanitized_dimensions[ $key ] = absint( $value );
	}
	
	return $sanitized_dimensions;
}

/**
 * Sanitize sortable.
 *
 * @param array $input Sortable value.
 * @param object $setting Setting object.
 * @return array
 */
function aqualuxe_sanitize_sortable( $input, $setting ) {
	// Get the list of possible sortable options.
	$choices = $setting->manager->get_control( $setting->id )->choices;
	
	// Return input if valid or return default option.
	$sanitized_input = array();
	
	foreach ( $input as $value ) {
		if ( array_key_exists( $value, $choices ) ) {
			$sanitized_input[] = $value;
		}
	}
	
	return $sanitized_input;
}

/**
 * Sanitize typography.
 *
 * @param array $input Typography value.
 * @return array
 */
function aqualuxe_sanitize_typography( $input ) {
	if ( ! is_array( $input ) ) {
		return array();
	}
	
	$sanitized_input = array();
	
	// Font family.
	if ( isset( $input['font-family'] ) ) {
		$sanitized_input['font-family'] = sanitize_text_field( $input['font-family'] );
	}
	
	// Font size.
	if ( isset( $input['font-size'] ) ) {
		$sanitized_input['font-size'] = sanitize_text_field( $input['font-size'] );
	}
	
	// Font weight.
	if ( isset( $input['font-weight'] ) ) {
		$sanitized_input['font-weight'] = sanitize_text_field( $input['font-weight'] );
	}
	
	// Line height.
	if ( isset( $input['line-height'] ) ) {
		$sanitized_input['line-height'] = sanitize_text_field( $input['line-height'] );
	}
	
	// Letter spacing.
	if ( isset( $input['letter-spacing'] ) ) {
		$sanitized_input['letter-spacing'] = sanitize_text_field( $input['letter-spacing'] );
	}
	
	// Text transform.
	if ( isset( $input['text-transform'] ) ) {
		$sanitized_input['text-transform'] = sanitize_text_field( $input['text-transform'] );
	}
	
	// Text decoration.
	if ( isset( $input['text-decoration'] ) ) {
		$sanitized_input['text-decoration'] = sanitize_text_field( $input['text-decoration'] );
	}
	
	return $sanitized_input;
}

/**
 * Sanitize html.
 *
 * @param string $html HTML to sanitize.
 * @return string
 */
function aqualuxe_sanitize_html( $html ) {
	return wp_kses_post( $html );
}

/**
 * Verify nonce.
 *
 * @param string $nonce Nonce to verify.
 * @param string $action Action name.
 * @return bool
 */
function aqualuxe_verify_nonce( $nonce, $action ) {
	if ( ! isset( $nonce ) ) {
		return false;
	}
	return wp_verify_nonce( $nonce, $action );
}

/**
 * Check if user has capability.
 *
 * @param string $capability Capability to check.
 * @return bool
 */
function aqualuxe_current_user_can( $capability ) {
	return current_user_can( $capability );
}

/**
 * Check if AJAX request.
 *
 * @return bool
 */
function aqualuxe_is_ajax() {
	return wp_doing_ajax();
}

/**
 * Check if REST request.
 *
 * @return bool
 */
function aqualuxe_is_rest() {
	return defined( 'REST_REQUEST' ) && REST_REQUEST;
}

/**
 * Get theme option.
 *
 * @param string $option Option name.
 * @param mixed  $default Default value.
 * @return mixed
 */
function aqualuxe_get_theme_option( $option, $default = '' ) {
	return get_theme_mod( $option, $default );
}

/**
 * Get image URL by ID.
 *
 * @param int    $attachment_id Attachment ID.
 * @param string $size Image size.
 * @return string
 */
function aqualuxe_get_image_url( $attachment_id, $size = 'full' ) {
	$image = wp_get_attachment_image_src( $attachment_id, $size );
	
	if ( ! $image ) {
		return '';
	}
	
	return $image[0];
}

/**
 * Get image alt by ID.
 *
 * @param int $attachment_id Attachment ID.
 * @return string
 */
function aqualuxe_get_image_alt( $attachment_id ) {
	$alt = get_post_meta( $attachment_id, '_wp_attachment_image_alt', true );
	
	if ( ! $alt ) {
		$alt = get_the_title( $attachment_id );
	}
	
	return $alt;
}

/**
 * Get image caption by ID.
 *
 * @param int $attachment_id Attachment ID.
 * @return string
 */
function aqualuxe_get_image_caption( $attachment_id ) {
	$attachment = get_post( $attachment_id );
	
	if ( ! $attachment ) {
		return '';
	}
	
	return $attachment->post_excerpt;
}

/**
 * Get image description by ID.
 *
 * @param int $attachment_id Attachment ID.
 * @return string
 */
function aqualuxe_get_image_description( $attachment_id ) {
	$attachment = get_post( $attachment_id );
	
	if ( ! $attachment ) {
		return '';
	}
	
	return $attachment->post_content;
}

/**
 * Get SVG icon.
 *
 * @param string $icon Icon name.
 * @param array  $args Icon arguments.
 * @return string
 */
function aqualuxe_get_svg_icon( $icon, $args = array() ) {
	// Set defaults.
	$defaults = array(
		'class'   => '',
		'width'   => 24,
		'height'  => 24,
		'fill'    => 'currentColor',
		'aria-hidden' => 'true',
		'focusable' => 'false',
	);
	
	// Parse args.
	$args = wp_parse_args( $args, $defaults );
	
	// Set aria-hidden to true if icon is decorative.
	if ( $args['aria-hidden'] ) {
		$args['aria-hidden'] = 'true';
	}
	
	// Set focusable to false if icon is decorative.
	if ( ! $args['focusable'] ) {
		$args['focusable'] = 'false';
	}
	
	// Set class.
	$class = 'svg-icon';
	
	if ( ! empty( $args['class'] ) ) {
		$class .= ' ' . $args['class'];
	}
	
	// Start SVG.
	$svg = '<svg class="' . esc_attr( $class ) . '" width="' . esc_attr( $args['width'] ) . '" height="' . esc_attr( $args['height'] ) . '" fill="' . esc_attr( $args['fill'] ) . '" aria-hidden="' . esc_attr( $args['aria-hidden'] ) . '" focusable="' . esc_attr( $args['focusable'] ) . '" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">';
	
	// Add path.
	switch ( $icon ) {
		case 'search':
			$svg .= '<path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>';
			break;
		case 'cart':
			$svg .= '<path d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>';
			break;
		case 'user':
			$svg .= '<path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>';
			break;
		case 'heart':
			$svg .= '<path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>';
			break;
		case 'menu':
			$svg .= '<path d="M4 6h16M4 12h16M4 18h16"></path>';
			break;
		case 'close':
			$svg .= '<path d="M6 18L18 6M6 6l12 12"></path>';
			break;
		case 'chevron-down':
			$svg .= '<path d="M19 9l-7 7-7-7"></path>';
			break;
		case 'chevron-up':
			$svg .= '<path d="M5 15l7-7 7 7"></path>';
			break;
		case 'chevron-left':
			$svg .= '<path d="M15 19l-7-7 7-7"></path>';
			break;
		case 'chevron-right':
			$svg .= '<path d="M9 5l7 7-7 7"></path>';
			break;
		case 'arrow-left':
			$svg .= '<path d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>';
			break;
		case 'arrow-right':
			$svg .= '<path d="M14 5l7 7m0 0l-7 7m7-7H3"></path>';
			break;
		case 'arrow-up':
			$svg .= '<path d="M5 10l7-7m0 0l7 7m-7-7v18"></path>';
			break;
		case 'arrow-down':
			$svg .= '<path d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>';
			break;
		case 'check':
			$svg .= '<path d="M5 13l4 4L19 7"></path>';
			break;
		case 'plus':
			$svg .= '<path d="M12 4v16m8-8H4"></path>';
			break;
		case 'minus':
			$svg .= '<path d="M20 12H4"></path>';
			break;
		case 'star':
			$svg .= '<path d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>';
			break;
		case 'facebook':
			$svg .= '<path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"></path>';
			break;
		case 'twitter':
			$svg .= '<path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"></path>';
			break;
		case 'instagram':
			$svg .= '<path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"></path>';
			break;
		case 'linkedin':
			$svg .= '<path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"></path>';
			break;
		case 'pinterest':
			$svg .= '<path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.162-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.72-.359-1.781c0-1.663.967-2.911 2.168-2.911 1.024 0 1.518.769 1.518 1.688 0 1.029-.653 2.567-.992 3.992-.285 1.193.6 2.165 1.775 2.165 2.128 0 3.768-2.245 3.768-5.487 0-2.861-2.063-4.869-5.008-4.869-3.41 0-5.409 2.562-5.409 5.199 0 1.033.394 2.143.889 2.741.099.12.112.225.085.345-.09.375-.293 1.199-.334 1.363-.053.225-.172.271-.401.165-1.495-.69-2.433-2.878-2.433-4.646 0-3.776 2.748-7.252 7.92-7.252 4.158 0 7.392 2.967 7.392 6.923 0 4.135-2.607 7.462-6.233 7.462-1.214 0-2.354-.629-2.758-1.379l-.749 2.848c-.269 1.045-1.004 2.352-1.498 3.146 1.123.345 2.306.535 3.55.535 6.607 0 11.985-5.365 11.985-11.987C23.97 5.39 18.592.026 11.985.026L12.017 0z"></path>';
			break;
		case 'youtube':
			$svg .= '<path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"></path>';
			break;
		case 'email':
			$svg .= '<path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"></path>';
			break;
		case 'phone':
			$svg .= '<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>';
			break;
		case 'location':
			$svg .= '<path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"></path>';
			break;
		case 'calendar':
			$svg .= '<path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V10h14v10zm0-12H5V6h14v2zm-7 5h5v5h-5v-5z"></path>';
			break;
		case 'clock':
			$svg .= '<path d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10 10-4.5 10-10S17.5 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm.5-13H11v6l5.2 3.2.8-1.3-4.5-2.7V7z"></path>';
			break;
		case 'info':
			$svg .= '<path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"></path>';
			break;
		case 'warning':
			$svg .= '<path d="M1 21h22L12 2 1 21zm12-3h-2v-2h2v2zm0-4h-2v-4h2v4z"></path>';
			break;
		case 'error':
			$svg .= '<path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"></path>';
			break;
		case 'success':
			$svg .= '<path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"></path>';
			break;
		default:
			$svg .= '';
			break;
	}
	
	// End SVG.
	$svg .= '</svg>';
	
	return $svg;
}

/**
 * Display SVG icon.
 *
 * @param string $icon Icon name.
 * @param array  $args Icon arguments.
 */
function aqualuxe_svg_icon( $icon, $args = array() ) {
	echo aqualuxe_get_svg_icon( $icon, $args ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Get post views.
 *
 * @param int $post_id Post ID.
 * @return int
 */
function aqualuxe_get_post_views( $post_id ) {
	$count_key = 'aqualuxe_post_views_count';
	$count = get_post_meta( $post_id, $count_key, true );
	
	if ( '' === $count ) {
		delete_post_meta( $post_id, $count_key );
		add_post_meta( $post_id, $count_key, '0' );
		return 0;
	}
	
	return absint( $count );
}

/**
 * Set post views.
 *
 * @param int $post_id Post ID.
 */
function aqualuxe_set_post_views( $post_id ) {
	$count_key = 'aqualuxe_post_views_count';
	$count = get_post_meta( $post_id, $count_key, true );
	
	if ( '' === $count ) {
		$count = 0;
		delete_post_meta( $post_id, $count_key );
		add_post_meta( $post_id, $count_key, '0' );
	} else {
		$count++;
		update_post_meta( $post_id, $count_key, $count );
	}
}

/**
 * Get post reading time.
 *
 * @param int $post_id Post ID.
 * @return int
 */
function aqualuxe_get_post_reading_time( $post_id ) {
	$content = get_post_field( 'post_content', $post_id );
	$word_count = str_word_count( strip_tags( $content ) );
	$reading_time = ceil( $word_count / 200 );
	
	return $reading_time;
}

/**
 * Get related posts.
 *
 * @param int   $post_id Post ID.
 * @param int   $number Number of posts.
 * @param array $args Query arguments.
 * @return array
 */
function aqualuxe_get_related_posts( $post_id, $number = 3, $args = array() ) {
	$args = wp_parse_args(
		$args,
		array(
			'post_type'      => 'post',
			'posts_per_page' => $number,
			'post__not_in'   => array( $post_id ),
			'orderby'        => 'rand',
		)
	);
	
	// Get post categories.
	$categories = get_the_category( $post_id );
	
	if ( $categories ) {
		$category_ids = array();
		
		foreach ( $categories as $category ) {
			$category_ids[] = $category->term_id;
		}
		
		$args['category__in'] = $category_ids;
	}
	
	// Get post tags.
	$tags = get_the_tags( $post_id );
	
	if ( $tags ) {
		$tag_ids = array();
		
		foreach ( $tags as $tag ) {
			$tag_ids[] = $tag->term_id;
		}
		
		$args['tag__in'] = $tag_ids;
	}
	
	return get_posts( $args );
}

/**
 * Get post author box.
 *
 * @param int $post_id Post ID.
 * @return string
 */
function aqualuxe_get_post_author_box( $post_id ) {
	$author_id = get_post_field( 'post_author', $post_id );
	$author_name = get_the_author_meta( 'display_name', $author_id );
	$author_description = get_the_author_meta( 'description', $author_id );
	$author_url = get_author_posts_url( $author_id );
	$author_avatar = get_avatar( $author_id, 96, '', $author_name, array( 'class' => 'rounded-full' ) );
	
	$html = '<div class="author-box bg-white dark:bg-dark-700 rounded-lg shadow-soft p-6">';
	$html .= '<div class="author-box-header flex items-center mb-4">';
	$html .= '<div class="author-box-avatar mr-4">' . $author_avatar . '</div>';
	$html .= '<div class="author-box-meta">';
	$html .= '<h3 class="author-box-name text-lg font-medium mb-1">' . esc_html( $author_name ) . '</h3>';
	$html .= '<div class="author-box-bio">' . wp_kses_post( $author_description ) . '</div>';
	$html .= '</div>';
	$html .= '</div>';
	$html .= '<div class="author-box-footer">';
	$html .= '<a href="' . esc_url( $author_url ) . '" class="author-box-link text-primary-600 dark:text-primary-400 hover:underline">' . esc_html__( 'View all posts by', 'aqualuxe' ) . ' ' . esc_html( $author_name ) . '</a>';
	$html .= '</div>';
	$html .= '</div>';
	
	return $html;
}

/**
 * Display post author box.
 *
 * @param int $post_id Post ID.
 */
function aqualuxe_post_author_box( $post_id ) {
	echo aqualuxe_get_post_author_box( $post_id ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Get social sharing buttons.
 *
 * @param int $post_id Post ID.
 * @return string
 */
function aqualuxe_get_social_sharing( $post_id ) {
	// Check if social sharing is enabled in the customizer.
	$social_sharing_enabled = get_theme_mod( 'aqualuxe_enable_social_sharing', true );
	
	// If social sharing is not enabled in the customizer, return empty string.
	if ( ! $social_sharing_enabled ) {
		return '';
	}
	
	// Get the current post URL and title.
	$post_url = urlencode( get_permalink( $post_id ) );
	$post_title = urlencode( get_the_title( $post_id ) );
	
	// Get the social networks to display.
	$social_networks = get_theme_mod( 'aqualuxe_social_sharing_networks', array( 'facebook', 'twitter', 'linkedin', 'pinterest' ) );
	
	// If no social networks are selected, return empty string.
	if ( empty( $social_networks ) ) {
		return '';
	}
	
	// Start output.
	$html = '<div class="social-sharing">';
	$html .= '<h3 class="social-sharing-title">' . esc_html__( 'Share this post', 'aqualuxe' ) . '</h3>';
	$html .= '<ul class="social-sharing-list flex flex-wrap gap-2">';
	
	// Facebook.
	if ( in_array( 'facebook', $social_networks, true ) ) {
		$html .= '<li class="social-sharing-item">';
		$html .= '<a href="https://www.facebook.com/sharer/sharer.php?u=' . $post_url . '" target="_blank" rel="noopener noreferrer" class="social-sharing-link facebook" aria-label="' . esc_attr__( 'Share on Facebook', 'aqualuxe' ) . '">';
		$html .= aqualuxe_get_svg_icon( 'facebook' );
		$html .= '<span class="sr-only">' . esc_html__( 'Facebook', 'aqualuxe' ) . '</span>';
		$html .= '</a>';
		$html .= '</li>';
	}
	
	// Twitter.
	if ( in_array( 'twitter', $social_networks, true ) ) {
		$html .= '<li class="social-sharing-item">';
		$html .= '<a href="https://twitter.com/intent/tweet?url=' . $post_url . '&text=' . $post_title . '" target="_blank" rel="noopener noreferrer" class="social-sharing-link twitter" aria-label="' . esc_attr__( 'Share on Twitter', 'aqualuxe' ) . '">';
		$html .= aqualuxe_get_svg_icon( 'twitter' );
		$html .= '<span class="sr-only">' . esc_html__( 'Twitter', 'aqualuxe' ) . '</span>';
		$html .= '</a>';
		$html .= '</li>';
	}
	
	// LinkedIn.
	if ( in_array( 'linkedin', $social_networks, true ) ) {
		$html .= '<li class="social-sharing-item">';
		$html .= '<a href="https://www.linkedin.com/shareArticle?mini=true&url=' . $post_url . '&title=' . $post_title . '" target="_blank" rel="noopener noreferrer" class="social-sharing-link linkedin" aria-label="' . esc_attr__( 'Share on LinkedIn', 'aqualuxe' ) . '">';
		$html .= aqualuxe_get_svg_icon( 'linkedin' );
		$html .= '<span class="sr-only">' . esc_html__( 'LinkedIn', 'aqualuxe' ) . '</span>';
		$html .= '</a>';
		$html .= '</li>';
	}
	
	// Pinterest.
	if ( in_array( 'pinterest', $social_networks, true ) ) {
		// Get the featured image URL for Pinterest.
		$featured_image = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), 'large' );
		$featured_image_url = $featured_image ? urlencode( $featured_image[0] ) : '';
		
		$html .= '<li class="social-sharing-item">';
		$html .= '<a href="https://pinterest.com/pin/create/button/?url=' . $post_url . '&media=' . $featured_image_url . '&description=' . $post_title . '" target="_blank" rel="noopener noreferrer" class="social-sharing-link pinterest" aria-label="' . esc_attr__( 'Share on Pinterest', 'aqualuxe' ) . '">';
		$html .= aqualuxe_get_svg_icon( 'pinterest' );
		$html .= '<span class="sr-only">' . esc_html__( 'Pinterest', 'aqualuxe' ) . '</span>';
		$html .= '</a>';
		$html .= '</li>';
	}
	
	// Email.
	if ( in_array( 'email', $social_networks, true ) ) {
		$html .= '<li class="social-sharing-item">';
		$html .= '<a href="mailto:?subject=' . $post_title . '&body=' . $post_url . '" class="social-sharing-link email" aria-label="' . esc_attr__( 'Share via Email', 'aqualuxe' ) . '">';
		$html .= aqualuxe_get_svg_icon( 'email' );
		$html .= '<span class="sr-only">' . esc_html__( 'Email', 'aqualuxe' ) . '</span>';
		$html .= '</a>';
		$html .= '</li>';
	}
	
	$html .= '</ul>';
	$html .= '</div>';
	
	return $html;
}

/**
 * Display social sharing buttons.
 *
 * @param int $post_id Post ID.
 */
function aqualuxe_social_sharing( $post_id ) {
	echo aqualuxe_get_social_sharing( $post_id ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Get social media links.
 *
 * @return array
 */
function aqualuxe_get_social_media_links() {
	$social_media_links = array();
	
	// Facebook.
	$facebook_url = get_theme_mod( 'aqualuxe_facebook_url', '' );
	if ( $facebook_url ) {
		$social_media_links['facebook'] = array(
			'url'   => $facebook_url,
			'label' => esc_html__( 'Facebook', 'aqualuxe' ),
			'icon'  => 'facebook',
		);
	}
	
	// Twitter.
	$twitter_url = get_theme_mod( 'aqualuxe_twitter_url', '' );
	if ( $twitter_url ) {
		$social_media_links['twitter'] = array(
			'url'   => $twitter_url,
			'label' => esc_html__( 'Twitter', 'aqualuxe' ),
			'icon'  => 'twitter',
		);
	}
	
	// Instagram.
	$instagram_url = get_theme_mod( 'aqualuxe_instagram_url', '' );
	if ( $instagram_url ) {
		$social_media_links['instagram'] = array(
			'url'   => $instagram_url,
			'label' => esc_html__( 'Instagram', 'aqualuxe' ),
			'icon'  => 'instagram',
		);
	}
	
	// LinkedIn.
	$linkedin_url = get_theme_mod( 'aqualuxe_linkedin_url', '' );
	if ( $linkedin_url ) {
		$social_media_links['linkedin'] = array(
			'url'   => $linkedin_url,
			'label' => esc_html__( 'LinkedIn', 'aqualuxe' ),
			'icon'  => 'linkedin',
		);
	}
	
	// Pinterest.
	$pinterest_url = get_theme_mod( 'aqualuxe_pinterest_url', '' );
	if ( $pinterest_url ) {
		$social_media_links['pinterest'] = array(
			'url'   => $pinterest_url,
			'label' => esc_html__( 'Pinterest', 'aqualuxe' ),
			'icon'  => 'pinterest',
		);
	}
	
	// YouTube.
	$youtube_url = get_theme_mod( 'aqualuxe_youtube_url', '' );
	if ( $youtube_url ) {
		$social_media_links['youtube'] = array(
			'url'   => $youtube_url,
			'label' => esc_html__( 'YouTube', 'aqualuxe' ),
			'icon'  => 'youtube',
		);
	}
	
	return $social_media_links;
}

/**
 * Get social media icons.
 *
 * @return string
 */
function aqualuxe_get_social_media_icons() {
	$social_media_links = aqualuxe_get_social_media_links();
	
	if ( empty( $social_media_links ) ) {
		return '';
	}
	
	$html = '<ul class="social-media-icons flex flex-wrap gap-2">';
	
	foreach ( $social_media_links as $social_media ) {
		$html .= '<li class="social-media-icon">';
		$html .= '<a href="' . esc_url( $social_media['url'] ) . '" target="_blank" rel="noopener noreferrer" class="social-media-link" aria-label="' . esc_attr( $social_media['label'] ) . '">';
		$html .= aqualuxe_get_svg_icon( $social_media['icon'] );
		$html .= '<span class="sr-only">' . esc_html( $social_media['label'] ) . '</span>';
		$html .= '</a>';
		$html .= '</li>';
	}
	
	$html .= '</ul>';
	
	return $html;
}

/**
 * Display social media icons.
 */
function aqualuxe_social_media_icons() {
	echo aqualuxe_get_social_media_icons(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Get contact information.
 *
 * @return array
 */
function aqualuxe_get_contact_info() {
	$contact_info = array();
	
	// Phone.
	$phone = get_theme_mod( 'aqualuxe_phone', '' );
	if ( $phone ) {
		$contact_info['phone'] = array(
			'value' => $phone,
			'label' => esc_html__( 'Phone', 'aqualuxe' ),
			'icon'  => 'phone',
			'url'   => 'tel:' . preg_replace( '/[^0-9+]/', '', $phone ),
		);
	}
	
	// Email.
	$email = get_theme_mod( 'aqualuxe_email', '' );
	if ( $email ) {
		$contact_info['email'] = array(
			'value' => $email,
			'label' => esc_html__( 'Email', 'aqualuxe' ),
			'icon'  => 'email',
			'url'   => 'mailto:' . $email,
		);
	}
	
	// Address.
	$address = get_theme_mod( 'aqualuxe_address', '' );
	if ( $address ) {
		$contact_info['address'] = array(
			'value' => $address,
			'label' => esc_html__( 'Address', 'aqualuxe' ),
			'icon'  => 'location',
			'url'   => 'https://maps.google.com/?q=' . urlencode( $address ),
		);
	}
	
	// Hours.
	$hours = get_theme_mod( 'aqualuxe_hours', '' );
	if ( $hours ) {
		$contact_info['hours'] = array(
			'value' => $hours,
			'label' => esc_html__( 'Hours', 'aqualuxe' ),
			'icon'  => 'clock',
			'url'   => '',
		);
	}
	
	return $contact_info;
}

/**
 * Get contact information HTML.
 *
 * @return string
 */
function aqualuxe_get_contact_info_html() {
	$contact_info = aqualuxe_get_contact_info();
	
	if ( empty( $contact_info ) ) {
		return '';
	}
	
	$html = '<ul class="contact-info">';
	
	foreach ( $contact_info as $info ) {
		$html .= '<li class="contact-info-item flex items-center mb-2">';
		$html .= '<div class="contact-info-icon mr-2">' . aqualuxe_get_svg_icon( $info['icon'] ) . '</div>';
		$html .= '<div class="contact-info-content">';
		$html .= '<span class="contact-info-label font-medium">' . esc_html( $info['label'] ) . ':</span> ';
		
		if ( ! empty( $info['url'] ) ) {
			$html .= '<a href="' . esc_url( $info['url'] ) . '" class="contact-info-value">' . esc_html( $info['value'] ) . '</a>';
		} else {
			$html .= '<span class="contact-info-value">' . esc_html( $info['value'] ) . '</span>';
		}
		
		$html .= '</div>';
		$html .= '</li>';
	}
	
	$html .= '</ul>';
	
	return $html;
}

/**
 * Display contact information.
 */
function aqualuxe_contact_info() {
	echo aqualuxe_get_contact_info_html(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Get copyright text.
 *
 * @return string
 */
function aqualuxe_get_copyright_text() {
	$copyright_text = get_theme_mod( 'aqualuxe_copyright_text', '' );
	
	if ( ! $copyright_text ) {
		$copyright_text = sprintf(
			/* translators: %1$s: Current year, %2$s: Site name */
			esc_html__( '&copy; %1$s %2$s. All rights reserved.', 'aqualuxe' ),
			date_i18n( 'Y' ),
			get_bloginfo( 'name' )
		);
	}
	
	return $copyright_text;
}

/**
 * Display copyright text.
 */
function aqualuxe_copyright_text() {
	echo wp_kses_post( aqualuxe_get_copyright_text() );
}

/**
 * Get footer credits.
 *
 * @return string
 */
function aqualuxe_get_footer_credits() {
	$footer_credits = get_theme_mod( 'aqualuxe_footer_credits', '' );
	
	if ( ! $footer_credits ) {
		$footer_credits = sprintf(
			/* translators: %1$s: Theme name, %2$s: Theme author */
			esc_html__( 'Powered by %1$s theme by %2$s.', 'aqualuxe' ),
			'AquaLuxe',
			'<a href="https://example.com/" target="_blank" rel="noopener noreferrer">NinjaTech AI</a>'
		);
	}
	
	return $footer_credits;
}

/**
 * Display footer credits.
 */
function aqualuxe_footer_credits() {
	echo wp_kses_post( aqualuxe_get_footer_credits() );
}