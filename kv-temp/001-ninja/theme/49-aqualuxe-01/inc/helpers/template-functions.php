<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package AquaLuxe
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function aqualuxe_body_classes( $classes ) {
	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	// Adds a class if WooCommerce is active.
	if ( class_exists( 'WooCommerce' ) ) {
		$classes[] = 'woocommerce-active';
	} else {
		$classes[] = 'woocommerce-inactive';
	}

	// Add class for dark mode
	if ( aqualuxe_is_dark_mode() ) {
		$classes[] = 'dark-mode';
	} else {
		$classes[] = 'light-mode';
	}

	// Add class for multilingual
	if ( aqualuxe_is_multilingual() ) {
		$classes[] = 'multilingual-site';
	}

	// Add class for multivendor
	if ( aqualuxe_is_multivendor() ) {
		$classes[] = 'multivendor-site';
	}

	return $classes;
}
add_filter( 'body_class', 'aqualuxe_body_classes' );

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
 * Check if dark mode is enabled
 *
 * @return bool
 */
function aqualuxe_is_dark_mode() {
	$default = 'light';
	
	// Check user preference from cookie
	if ( isset( $_COOKIE['aqualuxe_color_scheme'] ) ) {
		$mode = sanitize_text_field( $_COOKIE['aqualuxe_color_scheme'] );
		return 'dark' === $mode;
	}
	
	// Check theme default setting
	$default_mode = get_theme_mod( 'aqualuxe_default_color_scheme', $default );
	return 'dark' === $default_mode;
}

/**
 * Check if site is multilingual
 *
 * @return bool
 */
function aqualuxe_is_multilingual() {
	// Check for WPML
	if ( defined( 'ICL_SITEPRESS_VERSION' ) ) {
		return true;
	}
	
	// Check for Polylang
	if ( function_exists( 'pll_languages_list' ) ) {
		return true;
	}
	
	return false;
}

/**
 * Check if site is multivendor
 *
 * @return bool
 */
function aqualuxe_is_multivendor() {
	// Check for WC Marketplace
	if ( class_exists( 'WCMp' ) ) {
		return true;
	}
	
	// Check for Dokan
	if ( class_exists( 'WeDevs_Dokan' ) ) {
		return true;
	}
	
	// Check for WC Vendors
	if ( class_exists( 'WC_Vendors' ) ) {
		return true;
	}
	
	return false;
}

/**
 * Check if WooCommerce is active
 *
 * @return bool
 */
function aqualuxe_is_woocommerce_active() {
	return class_exists( 'WooCommerce' );
}

/**
 * Get currency symbol
 *
 * @return string
 */
function aqualuxe_get_currency_symbol() {
	if ( aqualuxe_is_woocommerce_active() ) {
		return get_woocommerce_currency_symbol();
	}
	
	return '$'; // Default fallback
}

/**
 * Add schema.org markup
 */
function aqualuxe_schema_markup() {
	// Default schema
	$schema = 'https://schema.org/WebPage';
	
	// Check for specific page types
	if ( is_home() || is_archive() || is_attachment() || is_tax() || is_single() ) {
		$schema = 'https://schema.org/Blog';
	} elseif ( is_author() ) {
		$schema = 'https://schema.org/ProfilePage';
	} elseif ( is_search() ) {
		$schema = 'https://schema.org/SearchResultsPage';
	}
	
	// Check for WooCommerce specific pages
	if ( aqualuxe_is_woocommerce_active() ) {
		if ( is_shop() || is_product_category() || is_product_tag() ) {
			$schema = 'https://schema.org/CollectionPage';
		} elseif ( is_product() ) {
			$schema = 'https://schema.org/Product';
		} elseif ( is_cart() ) {
			$schema = 'https://schema.org/Cart';
		} elseif ( is_checkout() ) {
			$schema = 'https://schema.org/CheckoutPage';
		} elseif ( is_account_page() ) {
			$schema = 'https://schema.org/ProfilePage';
		}
	}
	
	echo 'itemscope itemtype="' . esc_attr( $schema ) . '"';
}

/**
 * Add Open Graph meta tags
 */
function aqualuxe_add_opengraph_tags() {
	global $post;
	
	if ( is_singular() && $post ) {
		echo '<meta property="og:title" content="' . esc_attr( get_the_title() ) . '" />' . "\n";
		echo '<meta property="og:type" content="article" />' . "\n";
		echo '<meta property="og:url" content="' . esc_url( get_permalink() ) . '" />' . "\n";
		
		if ( has_post_thumbnail( $post->ID ) ) {
			$thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'large' );
			if ( $thumbnail ) {
				echo '<meta property="og:image" content="' . esc_url( $thumbnail[0] ) . '" />' . "\n";
			}
		}
		
		$excerpt = get_the_excerpt();
		if ( ! empty( $excerpt ) ) {
			echo '<meta property="og:description" content="' . esc_attr( $excerpt ) . '" />' . "\n";
		}
	} else {
		echo '<meta property="og:title" content="' . esc_attr( get_bloginfo( 'name' ) ) . '" />' . "\n";
		echo '<meta property="og:type" content="website" />' . "\n";
		echo '<meta property="og:url" content="' . esc_url( home_url( '/' ) ) . '" />' . "\n";
		
		$logo_id = get_theme_mod( 'custom_logo' );
		if ( $logo_id ) {
			$logo = wp_get_attachment_image_src( $logo_id, 'full' );
			if ( $logo ) {
				echo '<meta property="og:image" content="' . esc_url( $logo[0] ) . '" />' . "\n";
			}
		}
		
		echo '<meta property="og:description" content="' . esc_attr( get_bloginfo( 'description' ) ) . '" />' . "\n";
	}
	
	echo '<meta property="og:site_name" content="' . esc_attr( get_bloginfo( 'name' ) ) . '" />' . "\n";
}
add_action( 'wp_head', 'aqualuxe_add_opengraph_tags', 5 );

/**
 * Implement lazy loading for images
 *
 * @param string $html Image HTML.
 * @return string Modified image HTML.
 */
function aqualuxe_lazy_load_images( $html ) {
	// Don't lazy load if the content has already been processed
	if ( strpos( $html, 'data-src' ) !== false ) {
		return $html;
	}
	
	// Don't lazy-load if the image already has loading attribute
	if ( strpos( $html, 'loading=' ) !== false ) {
		return $html;
	}
	
	return str_replace( '<img', '<img loading="lazy"', $html );
}
add_filter( 'the_content', 'aqualuxe_lazy_load_images', 99 );
add_filter( 'post_thumbnail_html', 'aqualuxe_lazy_load_images', 99 );
add_filter( 'woocommerce_product_get_image', 'aqualuxe_lazy_load_images', 99 );

/**
 * Toggle dark mode via AJAX
 */
function aqualuxe_toggle_dark_mode() {
	// Check nonce
	if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( $_POST['nonce'] ), 'aqualuxe-dark-mode-nonce' ) ) {
		wp_send_json_error( 'Invalid nonce' );
	}
	
	// Get mode from request
	$mode = isset( $_POST['mode'] ) ? sanitize_text_field( $_POST['mode'] ) : 'light';
	
	// Set cookie for 30 days
	setcookie( 'aqualuxe_color_scheme', $mode, time() + ( 30 * DAY_IN_SECONDS ), '/' );
	
	wp_send_json_success( array( 'mode' => $mode ) );
}
add_action( 'wp_ajax_aqualuxe_toggle_dark_mode', 'aqualuxe_toggle_dark_mode' );
add_action( 'wp_ajax_nopriv_aqualuxe_toggle_dark_mode', 'aqualuxe_toggle_dark_mode' );