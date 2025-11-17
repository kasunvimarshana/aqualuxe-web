<?php
/**
 * Theme Setup Functions
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register custom image sizes for the theme
 */
function aqualuxe_register_image_sizes() {
	// These are registered in functions.php during 'after_setup_theme'
	// This function is for reference and documentation
	
	// Featured image size for blog posts and pages
	// add_image_size( 'aqualuxe-featured', 1200, 675, true );
	
	// Card image size for grid layouts
	// add_image_size( 'aqualuxe-card', 600, 400, true );
	
	// Thumbnail size for widgets and small displays
	// add_image_size( 'aqualuxe-thumbnail', 300, 300, true );
	
	// Hero image size for full-width sections
	// add_image_size( 'aqualuxe-hero', 1920, 1080, true );
}

/**
 * Add custom image sizes to media library dropdown
 *
 * @param array $sizes Current image sizes.
 * @return array Modified image sizes.
 */
function aqualuxe_custom_image_sizes( $sizes ) {
	return array_merge(
		$sizes,
		array(
			'aqualuxe-featured'  => esc_html__( 'AquaLuxe Featured', 'aqualuxe' ),
			'aqualuxe-card'      => esc_html__( 'AquaLuxe Card', 'aqualuxe' ),
			'aqualuxe-thumbnail' => esc_html__( 'AquaLuxe Thumbnail', 'aqualuxe' ),
			'aqualuxe-hero'      => esc_html__( 'AquaLuxe Hero', 'aqualuxe' ),
		)
	);
}
add_filter( 'image_size_names_choose', 'aqualuxe_custom_image_sizes' );

/**
 * Set up theme defaults and register support for various WordPress features.
 * This function is called in functions.php
 */
function aqualuxe_theme_support_setup() {
	// This is handled in functions.php
	// This function is for reference and documentation
}

/**
 * Add preconnect for Google Fonts if used.
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
 * Register custom query vars
 *
 * @param array $vars The array of available query variables.
 * @return array Modified array of query variables.
 */
function aqualuxe_register_query_vars( $vars ) {
	$vars[] = 'aqualuxe_filter';
	return $vars;
}
add_filter( 'query_vars', 'aqualuxe_register_query_vars' );

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function aqualuxe_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
	}
}
add_action( 'wp_head', 'aqualuxe_pingback_header' );

/**
 * Change the excerpt length
 *
 * @param int $length Excerpt length.
 * @return int Modified excerpt length.
 */
function aqualuxe_excerpt_length( $length ) {
	return 30;
}
add_filter( 'excerpt_length', 'aqualuxe_excerpt_length' );

/**
 * Change the excerpt more string
 *
 * @param string $more The excerpt more string.
 * @return string Modified excerpt more string.
 */
function aqualuxe_excerpt_more( $more ) {
	return '&hellip;';
}
add_filter( 'excerpt_more', 'aqualuxe_excerpt_more' );

/**
 * Add custom body classes
 *
 * @param array $classes Body classes.
 * @return array Modified body classes.
 */
function aqualuxe_body_classes( $classes ) {
	// Add a class if WooCommerce is active
	if ( class_exists( 'WooCommerce' ) ) {
		$classes[] = 'woocommerce-active';
	} else {
		$classes[] = 'woocommerce-inactive';
	}
	
	// Add a class for the color scheme
	$color_scheme = get_theme_mod( 'aqualuxe_color_scheme', 'light' );
	$classes[] = 'color-scheme-' . sanitize_html_class( $color_scheme );
	
	// Add a class for the layout
	$layout = get_theme_mod( 'aqualuxe_layout', 'right-sidebar' );
	$classes[] = 'layout-' . sanitize_html_class( $layout );
	
	// Add a class if we're on a single post or page
	if ( is_singular() && ! is_front_page() ) {
		$classes[] = 'singular';
	}
	
	return $classes;
}
add_filter( 'body_class', 'aqualuxe_body_classes' );