<?php
/**
 * AquaLuxe Theme Customizer Output
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Generate CSS from customizer settings.
 */
function aqualuxe_customizer_css() {
	// Get customizer settings.
	$primary_color = get_theme_mod( 'aqualuxe_primary_color', '#14b8a6' );
	$secondary_color = get_theme_mod( 'aqualuxe_secondary_color', '#bfa094' );
	$text_color = get_theme_mod( 'aqualuxe_text_color', '#333333' );
	$background_color = get_theme_mod( 'aqualuxe_background_color', '#ffffff' );
	$dark_text_color = get_theme_mod( 'aqualuxe_dark_text_color', '#f3f4f6' );
	$dark_background_color = get_theme_mod( 'aqualuxe_dark_background_color', '#1f2937' );
	$container_width = get_theme_mod( 'aqualuxe_container_width', '1280' );
	$base_font_size = get_theme_mod( 'aqualuxe_base_font_size', '16' );
	$line_height = get_theme_mod( 'aqualuxe_line_height', '1.6' );
	$body_font = get_theme_mod( 'aqualuxe_body_font', 'Inter' );
	$heading_font = get_theme_mod( 'aqualuxe_heading_font', 'Playfair Display' );
	$header_background_color = get_theme_mod( 'aqualuxe_header_background_color', '#ffffff' );
	$footer_background_color = get_theme_mod( 'aqualuxe_footer_background_color', '#042f2e' );
	$footer_text_color = get_theme_mod( 'aqualuxe_footer_text_color', '#ffffff' );

	// Start CSS.
	$css = '';

	// Root variables.
	$css .= ':root {
		--aqualuxe-primary-color: ' . esc_attr( $primary_color ) . ';
		--aqualuxe-secondary-color: ' . esc_attr( $secondary_color ) . ';
		--aqualuxe-text-color: ' . esc_attr( $text_color ) . ';
		--aqualuxe-background-color: ' . esc_attr( $background_color ) . ';
		--aqualuxe-dark-text-color: ' . esc_attr( $dark_text_color ) . ';
		--aqualuxe-dark-background-color: ' . esc_attr( $dark_background_color ) . ';
		--aqualuxe-container-width: ' . esc_attr( $container_width ) . 'px;
		--aqualuxe-base-font-size: ' . esc_attr( $base_font_size ) . 'px;
		--aqualuxe-line-height: ' . esc_attr( $line_height ) . ';
		--aqualuxe-body-font: "' . esc_attr( $body_font ) . '", sans-serif;
		--aqualuxe-heading-font: "' . esc_attr( $heading_font ) . '", serif;
		--aqualuxe-header-background-color: ' . esc_attr( $header_background_color ) . ';
		--aqualuxe-footer-background-color: ' . esc_attr( $footer_background_color ) . ';
		--aqualuxe-footer-text-color: ' . esc_attr( $footer_text_color ) . ';
	}';

	// Body styles.
	$css .= 'body {
		font-family: var(--aqualuxe-body-font);
		font-size: var(--aqualuxe-base-font-size);
		line-height: var(--aqualuxe-line-height);
		color: var(--aqualuxe-text-color);
		background-color: var(--aqualuxe-background-color);
	}';

	// Heading styles.
	$css .= 'h1, h2, h3, h4, h5, h6 {
		font-family: var(--aqualuxe-heading-font);
	}';

	// Container styles.
	$css .= '.container {
		max-width: var(--aqualuxe-container-width);
	}';

	// Link styles.
	$css .= 'a {
		color: var(--aqualuxe-primary-color);
	}
	a:hover {
		color: ' . esc_attr( aqualuxe_adjust_brightness( $primary_color, -20 ) ) . ';
	}';

	// Button styles.
	$css .= '.btn-primary, .button, button[type="submit"], input[type="submit"] {
		background-color: var(--aqualuxe-primary-color);
		border-color: var(--aqualuxe-primary-color);
		color: #ffffff;
	}
	.btn-primary:hover, .button:hover, button[type="submit"]:hover, input[type="submit"]:hover {
		background-color: ' . esc_attr( aqualuxe_adjust_brightness( $primary_color, -20 ) ) . ';
		border-color: ' . esc_attr( aqualuxe_adjust_brightness( $primary_color, -20 ) ) . ';
	}
	.btn-secondary {
		background-color: var(--aqualuxe-secondary-color);
		border-color: var(--aqualuxe-secondary-color);
		color: #ffffff;
	}
	.btn-secondary:hover {
		background-color: ' . esc_attr( aqualuxe_adjust_brightness( $secondary_color, -20 ) ) . ';
		border-color: ' . esc_attr( aqualuxe_adjust_brightness( $secondary_color, -20 ) ) . ';
	}';

	// Header styles.
	$css .= '.site-header {
		background-color: var(--aqualuxe-header-background-color);
	}';

	// Footer styles.
	$css .= '.site-footer {
		background-color: var(--aqualuxe-footer-background-color);
		color: var(--aqualuxe-footer-text-color);
	}
	.site-footer a {
		color: ' . esc_attr( aqualuxe_adjust_brightness( $footer_text_color, -20 ) ) . ';
	}
	.site-footer a:hover {
		color: var(--aqualuxe-footer-text-color);
	}';

	// Dark mode styles.
	if ( get_theme_mod( 'aqualuxe_enable_dark_mode', true ) ) {
		$css .= '.dark-mode {
			color: var(--aqualuxe-dark-text-color);
			background-color: var(--aqualuxe-dark-background-color);
		}
		.dark-mode .site-header {
			background-color: ' . esc_attr( aqualuxe_adjust_brightness( $dark_background_color, -10 ) ) . ';
		}
		.dark-mode a {
			color: ' . esc_attr( aqualuxe_adjust_brightness( $primary_color, 20 ) ) . ';
		}
		.dark-mode a:hover {
			color: ' . esc_attr( aqualuxe_adjust_brightness( $primary_color, 40 ) ) . ';
		}';
	}

	// Output the CSS.
	wp_add_inline_style( 'aqualuxe-styles', $css );
}
add_action( 'wp_enqueue_scripts', 'aqualuxe_customizer_css', 20 );

/**
 * Generate editor styles from customizer settings.
 */
function aqualuxe_editor_customizer_css() {
	// Get customizer settings.
	$primary_color = get_theme_mod( 'aqualuxe_primary_color', '#14b8a6' );
	$secondary_color = get_theme_mod( 'aqualuxe_secondary_color', '#bfa094' );
	$text_color = get_theme_mod( 'aqualuxe_text_color', '#333333' );
	$background_color = get_theme_mod( 'aqualuxe_background_color', '#ffffff' );
	$base_font_size = get_theme_mod( 'aqualuxe_base_font_size', '16' );
	$line_height = get_theme_mod( 'aqualuxe_line_height', '1.6' );
	$body_font = get_theme_mod( 'aqualuxe_body_font', 'Inter' );
	$heading_font = get_theme_mod( 'aqualuxe_heading_font', 'Playfair Display' );

	// Start CSS.
	$css = '';

	// Root variables.
	$css .= ':root {
		--aqualuxe-primary-color: ' . esc_attr( $primary_color ) . ';
		--aqualuxe-secondary-color: ' . esc_attr( $secondary_color ) . ';
		--aqualuxe-text-color: ' . esc_attr( $text_color ) . ';
		--aqualuxe-background-color: ' . esc_attr( $background_color ) . ';
		--aqualuxe-base-font-size: ' . esc_attr( $base_font_size ) . 'px;
		--aqualuxe-line-height: ' . esc_attr( $line_height ) . ';
		--aqualuxe-body-font: "' . esc_attr( $body_font ) . '", sans-serif;
		--aqualuxe-heading-font: "' . esc_attr( $heading_font ) . '", serif;
	}';

	// Body styles.
	$css .= '.editor-styles-wrapper {
		font-family: var(--aqualuxe-body-font);
		font-size: var(--aqualuxe-base-font-size);
		line-height: var(--aqualuxe-line-height);
		color: var(--aqualuxe-text-color);
		background-color: var(--aqualuxe-background-color);
	}';

	// Heading styles.
	$css .= '.editor-styles-wrapper h1, .editor-styles-wrapper h2, .editor-styles-wrapper h3, .editor-styles-wrapper h4, .editor-styles-wrapper h5, .editor-styles-wrapper h6 {
		font-family: var(--aqualuxe-heading-font);
	}';

	// Link styles.
	$css .= '.editor-styles-wrapper a {
		color: var(--aqualuxe-primary-color);
	}
	.editor-styles-wrapper a:hover {
		color: ' . esc_attr( aqualuxe_adjust_brightness( $primary_color, -20 ) ) . ';
	}';

	// Button styles.
	$css .= '.editor-styles-wrapper .wp-block-button__link {
		background-color: var(--aqualuxe-primary-color);
		border-color: var(--aqualuxe-primary-color);
		color: #ffffff;
	}
	.editor-styles-wrapper .wp-block-button__link:hover {
		background-color: ' . esc_attr( aqualuxe_adjust_brightness( $primary_color, -20 ) ) . ';
		border-color: ' . esc_attr( aqualuxe_adjust_brightness( $primary_color, -20 ) ) . ';
	}
	.editor-styles-wrapper .is-style-outline .wp-block-button__link {
		color: var(--aqualuxe-primary-color);
		border-color: var(--aqualuxe-primary-color);
		background-color: transparent;
	}
	.editor-styles-wrapper .is-style-outline .wp-block-button__link:hover {
		color: ' . esc_attr( aqualuxe_adjust_brightness( $primary_color, -20 ) ) . ';
		border-color: ' . esc_attr( aqualuxe_adjust_brightness( $primary_color, -20 ) ) . ';
	}';

	// Output the CSS.
	wp_add_inline_style( 'aqualuxe-editor-styles', $css );
}
add_action( 'enqueue_block_editor_assets', 'aqualuxe_editor_customizer_css', 20 );

/**
 * Adjust brightness of a color.
 *
 * @param string $hex_code Hex color code.
 * @param int    $adjust_percent Percentage to adjust brightness.
 * @return string Adjusted hex color code.
 */
function aqualuxe_adjust_brightness( $hex_code, $adjust_percent ) {
	$hex_code = ltrim( $hex_code, '#' );

	if ( strlen( $hex_code ) === 3 ) {
		$hex_code = $hex_code[0] . $hex_code[0] . $hex_code[1] . $hex_code[1] . $hex_code[2] . $hex_code[2];
	}

	$hex_code = array_map( 'hexdec', str_split( $hex_code, 2 ) );

	foreach ( $hex_code as & $color ) {
		$adjustable_limit = $adjust_percent < 0 ? $color : 255 - $color;
		$adjust_amount = ceil( $adjustable_limit * abs( $adjust_percent ) / 100 );

		if ( $adjust_percent > 0 ) {
			$color = $color + $adjust_amount;
		} else {
			$color = $color - $adjust_amount;
		}
	}

	$hex_code = array_map( 'dechex', $hex_code );

	foreach ( $hex_code as & $color ) {
		$color = str_pad( $color, 2, '0', STR_PAD_LEFT );
	}

	return '#' . implode( '', $hex_code );
}

/**
 * Add preconnect for Google Fonts.
 *
 * @param array  $urls           URLs to print for resource hints.
 * @param string $relation_type  The relation type the URLs are printed.
 * @return array URLs to print for resource hints.
 */
function aqualuxe_resource_hints( $urls, $relation_type ) {
	if ( wp_style_is( 'aqualuxe-fonts', 'queue' ) && 'preconnect' === $relation_type ) {
		$urls[] = array(
			'href' => 'https://fonts.gstatic.com',
			'crossorigin',
		);
	}

	return $urls;
}
add_filter( 'wp_resource_hints', 'aqualuxe_resource_hints', 10, 2 );

/**
 * Enqueue Google Fonts.
 */
function aqualuxe_fonts_url() {
	$fonts_url = '';
	$font_families = array();

	// Get customizer settings.
	$body_font = get_theme_mod( 'aqualuxe_body_font', 'Inter' );
	$heading_font = get_theme_mod( 'aqualuxe_heading_font', 'Playfair Display' );

	// Add body font.
	if ( 'system-ui' !== $body_font ) {
		$font_families[] = $body_font . ':400,500,600,700';
	}

	// Add heading font.
	if ( 'system-ui' !== $heading_font && $heading_font !== $body_font ) {
		$font_families[] = $heading_font . ':400,500,600,700';
	}

	// Build the fonts URL.
	if ( ! empty( $font_families ) ) {
		$query_args = array(
			'family' => urlencode( implode( '|', $font_families ) ),
			'display' => 'swap',
		);

		$fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css2' );
	}

	return $fonts_url;
}

/**
 * Enqueue Google Fonts.
 */
function aqualuxe_enqueue_fonts() {
	$fonts_url = aqualuxe_fonts_url();

	if ( ! empty( $fonts_url ) ) {
		wp_enqueue_style( 'aqualuxe-fonts', $fonts_url, array(), AQUALUXE_VERSION );
	}
}
add_action( 'wp_enqueue_scripts', 'aqualuxe_enqueue_fonts' );
add_action( 'enqueue_block_editor_assets', 'aqualuxe_enqueue_fonts' );

/**
 * Add preload for critical fonts.
 */
function aqualuxe_preload_fonts() {
	if ( get_theme_mod( 'aqualuxe_preload_fonts', true ) ) {
		$fonts_url = aqualuxe_fonts_url();

		if ( ! empty( $fonts_url ) ) {
			echo '<link rel="preload" href="' . esc_url( $fonts_url ) . '" as="style" crossorigin>' . "\n";
		}
	}
}
add_action( 'wp_head', 'aqualuxe_preload_fonts', 1 );