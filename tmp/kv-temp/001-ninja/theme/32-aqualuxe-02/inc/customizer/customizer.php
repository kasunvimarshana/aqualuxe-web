<?php
/**
 * AquaLuxe Theme Customizer
 *
 * @package AquaLuxe
 * @since 1.1.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Load the customizer class.
 */
require_once AQUALUXE_DIR . '/inc/customizer/class-customizer.php';

/**
 * Load customizer controls.
 */
require_once AQUALUXE_DIR . '/inc/customizer/controls/class-toggle-control.php';
require_once AQUALUXE_DIR . '/inc/customizer/controls/class-heading-control.php';
require_once AQUALUXE_DIR . '/inc/customizer/controls/class-divider-control.php';
require_once AQUALUXE_DIR . '/inc/customizer/controls/class-typography-control.php';

/**
 * Load customizer sections.
 */
require_once AQUALUXE_DIR . '/inc/customizer/sections/class-colors.php';
require_once AQUALUXE_DIR . '/inc/customizer/sections/class-typography.php';
require_once AQUALUXE_DIR . '/inc/customizer/sections/class-layout.php';
require_once AQUALUXE_DIR . '/inc/customizer/sections/class-social-media.php';
require_once AQUALUXE_DIR . '/inc/customizer/sections/class-performance.php';

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial(
			'blogname',
			array(
				'selector'        => '.site-title a',
				'render_callback' => 'aqualuxe_customize_partial_blogname',
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'blogdescription',
			array(
				'selector'        => '.site-description',
				'render_callback' => 'aqualuxe_customize_partial_blogdescription',
			)
		);
	}
}
add_action( 'customize_register', 'aqualuxe_customize_register' );

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function aqualuxe_customize_partial_blogname() {
	bloginfo( 'name' );
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function aqualuxe_customize_partial_blogdescription() {
	bloginfo( 'description' );
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function aqualuxe_customize_preview_js() {
	wp_enqueue_script( 'aqualuxe-customizer', AQUALUXE_URI . '/assets/js/customizer.js', array( 'customize-preview' ), AQUALUXE_VERSION, true );
	wp_enqueue_script( 'aqualuxe-customizer-preview', AQUALUXE_URI . '/assets/js/customizer/customizer-preview.js', array( 'customize-preview', 'jquery' ), AQUALUXE_VERSION, true );
}
add_action( 'customize_preview_init', 'aqualuxe_customize_preview_js' );

/**
 * Binds JS handlers to make Theme Customizer controls work better.
 */
function aqualuxe_customize_controls_js() {
	wp_enqueue_script( 'aqualuxe-customizer-controls', AQUALUXE_URI . '/assets/js/customizer/customizer-controls.js', array( 'customize-controls', 'jquery' ), AQUALUXE_VERSION, true );
}
add_action( 'customize_controls_enqueue_scripts', 'aqualuxe_customize_controls_js' );

/**

/**
 * Sanitize rgba color.
 *
 * @param string $color RGBA color.
 * @return string
 */
function aqualuxe_sanitize_rgba( $color ) {
	if ( '' === $color ) {
		return '';
	}

	// If string does not start with 'rgba', then treat as hex.
	// sanitize the hex color and finally convert hex to rgba.
	if ( false === strpos( $color, 'rgba' ) ) {
		return sanitize_hex_color( $color );
	}

	// By now we know the string is formatted as an rgba color so we need to further sanitize it.
	$color = str_replace( ' ', '', $color );
	sscanf( $color, 'rgba(%d,%d,%d,%f)', $red, $green, $blue, $alpha );

	return 'rgba(' . $red . ',' . $green . ',' . $blue . ',' . $alpha . ')';
}

/**
 * Sanitize number.
 *
 * @param string $number  The number.
 * @param object $setting The setting.
 * @return string
 */
function aqualuxe_sanitize_number( $number, $setting ) {
	// Ensure $number is an absolute integer (whole number, zero or greater).
	$number = absint( $number );

	// Get the input attributes associated with the setting.
	$atts = $setting->manager->get_control( $setting->id )->input_attrs;

	// Get min.
	$min = isset( $atts['min'] ) ? $atts['min'] : $number;

	// Get max.
	$max = isset( $atts['max'] ) ? $atts['max'] : $number;

	// Get step.
	$step = isset( $atts['step'] ) ? $atts['step'] : 1;

	// If the input is valid, return it; otherwise, return the default.
	return ( $min <= $number && $number <= $max && is_int( $number / $step ) ? $number : $setting->default );
}

/**
 * Sanitize textarea.
 *
 * @param string $text The text.
 * @return string
 */
function aqualuxe_sanitize_textarea( $text ) {
	return wp_kses_post( $text );
}

/**
 * Sanitize image.
 *
 * @param string $image Image URL.
 * @param object $setting Setting object.
 * @return string
 */
function aqualuxe_sanitize_image( $image, $setting ) {
	// Array with valid image file types.
	$mimes = array(
		'jpg|jpeg|jpe' => 'image/jpeg',
		'gif'          => 'image/gif',
		'png'          => 'image/png',
		'bmp'          => 'image/bmp',
		'tif|tiff'     => 'image/tiff',
		'ico'          => 'image/x-icon',
		'svg'          => 'image/svg+xml',
		'webp'         => 'image/webp',
	);

	// Return an array with file extension and mime_type.
	$file = wp_check_filetype( $image, $mimes );

	// If $image has a valid mime_type, return it; otherwise, return the default.
	return ( $file['ext'] ? $image : $setting->default );
}

/**
 * Sanitize dimensions.
 *
 * @param array $dimensions Dimensions array.
 * @return array
 */
function aqualuxe_sanitize_dimensions( $dimensions ) {
	if ( ! is_array( $dimensions ) ) {
		return array();
	}

	foreach ( $dimensions as $key => $value ) {
		$dimensions[ $key ] = absint( $value );
	}

	return $dimensions;
}

/**
 * Sanitize sortable.
 *
 * @param array $input Sortable array.
 * @return array
 */
function aqualuxe_sanitize_sortable( $input ) {
	if ( ! is_array( $input ) ) {
		return array();
	}

	foreach ( $input as $key => $value ) {
		$input[ $key ] = sanitize_text_field( $value );
	}

	return $input;
}

/**
 * Sanitize typography.
 *
 * @param array $input Typography array.
 * @return array
 */
function aqualuxe_sanitize_typography( $input ) {
	if ( ! is_array( $input ) ) {
		return array();
	}

	$allowed_fields = array(
		'font-family',
		'font-size',
		'font-weight',
		'line-height',
		'letter-spacing',
		'text-transform',
		'text-decoration',
		'font-style',
		'color',
	);

	foreach ( $input as $key => $value ) {
		if ( ! in_array( $key, $allowed_fields, true ) ) {
			unset( $input[ $key ] );
			continue;
		}

		switch ( $key ) {
			case 'font-family':
				$input[ $key ] = sanitize_text_field( $value );
				break;
			case 'font-size':
			case 'line-height':
			case 'letter-spacing':
				$input[ $key ] = sanitize_text_field( $value );
				break;
			case 'font-weight':
				$input[ $key ] = absint( $value );
				break;
			case 'text-transform':
			case 'text-decoration':
			case 'font-style':
				$input[ $key ] = sanitize_text_field( $value );
				break;
			case 'color':
				$input[ $key ] = sanitize_hex_color( $value );
				break;
		}
	}

	return $input;
}