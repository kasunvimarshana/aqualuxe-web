<?php
/**
 * Customizer Sanitization Functions
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
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

/**
 * Sanitize float input
 *
 * @param float $input Float input.
 * @param object $setting Setting object.
 * @return float Sanitized float input.
 */
function aqualuxe_sanitize_float( $input, $setting ) {
	$input = (float) $input;
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

/**
 * Sanitize radio input
 *
 * @param string $input Radio input.
 * @param object $setting Setting object.
 * @return string Sanitized radio input.
 */
function aqualuxe_sanitize_radio( $input, $setting ) {
	$choices = $setting->manager->get_control( $setting->id )->choices;
	return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
}

/**
 * Sanitize image input
 *
 * @param string $input Image input.
 * @param object $setting Setting object.
 * @return string Sanitized image input.
 */
function aqualuxe_sanitize_image( $input, $setting ) {
	$mimes = array(
		'jpg|jpeg|jpe' => 'image/jpeg',
		'gif'          => 'image/gif',
		'png'          => 'image/png',
		'bmp'          => 'image/bmp',
		'tif|tiff'     => 'image/tiff',
		'ico'          => 'image/x-icon',
		'svg'          => 'image/svg+xml',
	);
	
	// Return an array with file extension and mime type.
	$file = wp_check_filetype( $input, $mimes );
	
	// If $input has a valid mime type, return it; otherwise, return the default.
	return ( $file['ext'] ? $input : $setting->default );
}

/**
 * Sanitize hex color input
 *
 * @param string $input Hex color input.
 * @return string Sanitized hex color input.
 */
function aqualuxe_sanitize_hex_color( $input ) {
	if ( '' === $input ) {
		return '';
	}
	
	// 3 or 6 hex digits, or the empty string.
	if ( preg_match( '|^#([A-Fa-f0-9]{3}){1,2}$|', $input ) ) {
		return $input;
	}
	
	return '';
}

/**
 * Sanitize rgba color input
 *
 * @param string $input RGBA color input.
 * @return string Sanitized RGBA color input.
 */
function aqualuxe_sanitize_rgba_color( $input ) {
	if ( '' === $input ) {
		return '';
	}
	
	// If string does not start with 'rgba', then treat as hex.
	if ( false === strpos( $input, 'rgba' ) ) {
		return aqualuxe_sanitize_hex_color( $input );
	}
	
	// Match rgba(r,g,b,a) format.
	preg_match( '/^rgba\(\s*([0-9]+)\s*,\s*([0-9]+)\s*,\s*([0-9]+)\s*,\s*([0-9\.]+)\s*\)$/', $input, $rgba_matches );
	
	if ( ! empty( $rgba_matches ) && 5 === count( $rgba_matches ) ) {
		$red   = intval( $rgba_matches[1] );
		$green = intval( $rgba_matches[2] );
		$blue  = intval( $rgba_matches[3] );
		$alpha = floatval( $rgba_matches[4] );
		
		if ( $red >= 0 && $red <= 255 && $green >= 0 && $green <= 255 && $blue >= 0 && $blue <= 255 && $alpha >= 0 && $alpha <= 1 ) {
			return "rgba($red,$green,$blue,$alpha)";
		}
	}
	
	return '';
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
 * Sanitize text input
 *
 * @param string $input Text input.
 * @return string Sanitized text input.
 */
function aqualuxe_sanitize_text( $input ) {
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
 * Sanitize HTML input
 *
 * @param string $input HTML input.
 * @return string Sanitized HTML input.
 */
function aqualuxe_sanitize_html( $input ) {
	return wp_kses_post( $input );
}

/**
 * Sanitize font input
 *
 * @param string $input Font input.
 * @return string Sanitized font input.
 */
function aqualuxe_sanitize_font( $input ) {
	$valid_fonts = array(
		'Montserrat'       => 'Montserrat',
		'Roboto'           => 'Roboto',
		'Open Sans'        => 'Open Sans',
		'Lato'             => 'Lato',
		'Raleway'          => 'Raleway',
		'Poppins'          => 'Poppins',
		'Playfair Display' => 'Playfair Display',
	);
	
	if ( array_key_exists( $input, $valid_fonts ) ) {
		return $input;
	}
	
	return 'Montserrat';
}

/**
 * Sanitize font weight input
 *
 * @param string $input Font weight input.
 * @return string Sanitized font weight input.
 */
function aqualuxe_sanitize_font_weight( $input ) {
	$valid_weights = array(
		'100' => '100',
		'200' => '200',
		'300' => '300',
		'400' => '400',
		'500' => '500',
		'600' => '600',
		'700' => '700',
		'800' => '800',
		'900' => '900',
	);
	
	if ( array_key_exists( $input, $valid_weights ) ) {
		return $input;
	}
	
	return '400';
}

/**
 * Sanitize dimensions input
 *
 * @param string $input Dimensions input.
 * @return string Sanitized dimensions input.
 */
function aqualuxe_sanitize_dimensions( $input ) {
	$values = explode( ',', $input );
	$values = array_map( 'absint', $values );
	return implode( ',', $values );
}

/**
 * Sanitize range input
 *
 * @param int $input Range input.
 * @param object $setting Setting object.
 * @return int Sanitized range input.
 */
function aqualuxe_sanitize_range( $input, $setting ) {
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

/**
 * Sanitize date input
 *
 * @param string $input Date input.
 * @return string Sanitized date input.
 */
function aqualuxe_sanitize_date( $input ) {
	$date = strtotime( $input );
	return ( $date ) ? date( 'Y-m-d', $date ) : '';
}

/**
 * Sanitize time input
 *
 * @param string $input Time input.
 * @return string Sanitized time input.
 */
function aqualuxe_sanitize_time( $input ) {
	$time = strtotime( $input );
	return ( $time ) ? date( 'H:i:s', $time ) : '';
}

/**
 * Sanitize datetime input
 *
 * @param string $input Datetime input.
 * @return string Sanitized datetime input.
 */
function aqualuxe_sanitize_datetime( $input ) {
	$datetime = strtotime( $input );
	return ( $datetime ) ? date( 'Y-m-d H:i:s', $datetime ) : '';
}

/**
 * Sanitize phone input
 *
 * @param string $input Phone input.
 * @return string Sanitized phone input.
 */
function aqualuxe_sanitize_phone( $input ) {
	return preg_replace( '/[^0-9\+\-\(\) ]/', '', $input );
}

/**
 * Sanitize slug input
 *
 * @param string $input Slug input.
 * @return string Sanitized slug input.
 */
function aqualuxe_sanitize_slug( $input ) {
	return sanitize_title( $input );
}

/**
 * Sanitize file input
 *
 * @param string $input File input.
 * @return string Sanitized file input.
 */
function aqualuxe_sanitize_file( $input ) {
	return esc_url_raw( $input );
}

/**
 * Sanitize multi-select input
 *
 * @param array $input Multi-select input.
 * @param object $setting Setting object.
 * @return array Sanitized multi-select input.
 */
function aqualuxe_sanitize_multi_select( $input, $setting ) {
	$choices = $setting->manager->get_control( $setting->id )->choices;
	$result  = array();
	
	foreach ( $input as $value ) {
		if ( array_key_exists( $value, $choices ) ) {
			$result[] = $value;
		}
	}
	
	return $result;
}

/**
 * Sanitize sortable input
 *
 * @param array $input Sortable input.
 * @param object $setting Setting object.
 * @return array Sanitized sortable input.
 */
function aqualuxe_sanitize_sortable( $input, $setting ) {
	$choices = $setting->manager->get_control( $setting->id )->choices;
	$result  = array();
	
	foreach ( $input as $value ) {
		if ( array_key_exists( $value, $choices ) ) {
			$result[] = $value;
		}
	}
	
	return $result;
}

/**
 * Sanitize Google Fonts input
 *
 * @param string $input Google Fonts input.
 * @return string Sanitized Google Fonts input.
 */
function aqualuxe_sanitize_google_font( $input ) {
	$valid_fonts = array(
		'Montserrat'       => 'Montserrat',
		'Roboto'           => 'Roboto',
		'Open Sans'        => 'Open Sans',
		'Lato'             => 'Lato',
		'Raleway'          => 'Raleway',
		'Poppins'          => 'Poppins',
		'Playfair Display' => 'Playfair Display',
	);
	
	if ( array_key_exists( $input, $valid_fonts ) ) {
		return $input;
	}
	
	return 'Montserrat';
}

/**
 * Sanitize CSS input
 *
 * @param string $input CSS input.
 * @return string Sanitized CSS input.
 */
function aqualuxe_sanitize_css( $input ) {
	return wp_strip_all_tags( $input );
}

/**
 * Sanitize JavaScript input
 *
 * @param string $input JavaScript input.
 * @return string Sanitized JavaScript input.
 */
function aqualuxe_sanitize_js( $input ) {
	return wp_strip_all_tags( $input );
}