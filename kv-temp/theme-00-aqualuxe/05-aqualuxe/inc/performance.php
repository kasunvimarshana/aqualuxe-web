<?php
/**
 * AquaLuxe Performance Optimization Functions
 *
 * @package AquaLuxe
 */

/**
 * Dequeue unnecessary scripts and styles
 */
function aqualuxe_dequeue_scripts() {
	// Remove emoji scripts and styles
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	
	// Remove Really Simple Discovery link
	remove_action( 'wp_head', 'rsd_link' );
	
	// Remove Windows Live Writer link
	remove_action( 'wp_head', 'wlwmanifest_link' );
	
	// Remove WordPress version number
	remove_action( 'wp_head', 'wp_generator' );
	
	// Remove REST API links
	remove_action( 'wp_head', 'rest_output_link_wp_head' );
	remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
}
add_action( 'init', 'aqualuxe_dequeue_scripts' );

/**
 * Add DNS prefetch for external resources
 */
function aqualuxe_dns_prefetch() {
	echo '<link rel="dns-prefetch" href="//fonts.googleapis.com">' . "\n";
	echo '<link rel="dns-prefetch" href="//s.w.org">' . "\n";
	
	if ( class_exists( 'WooCommerce' ) ) {
		echo '<link rel="dns-prefetch" href="//woocommerce.com">' . "\n";
	}
}
add_action( 'wp_head', 'aqualuxe_dns_prefetch', 0 );

/**
 * Preload critical resources
 */
function aqualuxe_preload_resources() {
	// Preload logo if set
	$logo = get_theme_mod( 'aqualuxe_logo', '' );
	if ( ! empty( $logo ) ) {
		echo '<link rel="preload" href="' . esc_url( $logo ) . '" as="image">' . "\n";
	}
	
	// Preload critical CSS
	echo '<link rel="preload" href="' . esc_url( get_stylesheet_uri() ) . '" as="style">' . "\n";
	
	// Preload Google Fonts if used
	if ( aqualuxe_has_google_fonts() ) {
		echo '<link rel="preconnect" href="https://fonts.googleapis.com">' . "\n";
		echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
	}
}
add_action( 'wp_head', 'aqualuxe_preload_resources', 1 );

/**
 * Check if theme uses Google Fonts
 */
function aqualuxe_has_google_fonts() {
	// Check if any Google Fonts are used in theme
	$fonts_used = get_theme_mod( 'aqualuxe_google_fonts', false );
	return $fonts_used;
}

/**
 * Defer non-critical JavaScript
 */
function aqualuxe_defer_scripts( $tag, $handle, $src ) {
	// Defer specific scripts
	$defer_scripts = array(
		'aqualuxe-scripts',
		'aqualuxe-woocommerce',
	);
	
	if ( in_array( $handle, $defer_scripts ) ) {
		return str_replace( ' src', ' defer src', $tag );
	}
	
	return $tag;
}
add_filter( 'script_loader_tag', 'aqualuxe_defer_scripts', 10, 3 );

/**
 * Add async loading for specific scripts
 */
function aqualuxe_async_scripts( $tag, $handle, $src ) {
	// Async specific scripts
	$async_scripts = array(
		// Add script handles that should be loaded asynchronously
	);
	
	if ( in_array( $handle, $async_scripts ) ) {
		return str_replace( ' src', ' async src', $tag );
	}
	
	return $tag;
}
// Uncomment to enable async loading
// add_filter( 'script_loader_tag', 'aqualuxe_async_scripts', 10, 3 );

/**
 * Optimize images
 */
function aqualuxe_optimize_images() {
	// Add WebP support
	add_filter( 'wp_check_filetype_and_ext', 'aqualuxe_webp_support', 10, 5 );
	
	// Add responsive image attributes
	add_filter( 'wp_get_attachment_image_attributes', 'aqualuxe_responsive_images', 10, 3 );
	
	// Add image dimensions
	add_filter( 'post_thumbnail_html', 'aqualuxe_add_image_dimensions', 10, 5 );
}
add_action( 'init', 'aqualuxe_optimize_images' );

/**
 * Add WebP support
 */
function aqualuxe_webp_support( $data, $file, $filename, $mimes, $real_mime ) {
	if ( ! empty( $data['ext'] ) && $data['ext'] === 'webp' ) {
		$data['type'] = 'image/webp';
		$data['ext'] = 'webp';
	}
	
	return $data;
}

/**
 * Add responsive image attributes
 */
function aqualuxe_responsive_images( $attr, $attachment, $size ) {
	// Add srcset and sizes attributes
	if ( ! isset( $attr['srcset'] ) ) {
		$image_srcset = wp_get_attachment_image_srcset( $attachment->ID, $size );
		if ( $image_srcset ) {
			$attr['srcset'] = $image_srcset;
		}
	}
	
	if ( ! isset( $attr['sizes'] ) ) {
		$image_sizes = wp_get_attachment_image_sizes( $attachment->ID, $size );
		if ( $image_sizes ) {
			$attr['sizes'] = $image_sizes;
		}
	}
	
	// Add loading attribute
	if ( ! isset( $attr['loading'] ) ) {
		$attr['loading'] = 'lazy';
	}
	
	return $attr;
}

/**
 * Add image dimensions to prevent layout shift
 */
function aqualuxe_add_image_dimensions( $html, $post_id, $post_thumbnail_id, $size, $attr ) {
	if ( empty( $html ) || ! $post_thumbnail_id ) {
		return $html;
	}
	
	// Get image dimensions
	$image_data = wp_get_attachment_image_src( $post_thumbnail_id, $size );
	if ( ! $image_data ) {
		return $html;
	}
	
	list( $src, $width, $height ) = $image_data;
	
	// Add width and height attributes if not already present
	if ( ! preg_match( '/width=/', $html ) && $width ) {
		$html = str_replace( '<img', '<img width="' . esc_attr( $width ) . '"', $html );
	}
	
	if ( ! preg_match( '/height=/', $html ) && $height ) {
		$html = str_replace( '<img', '<img height="' . esc_attr( $height ) . '"', $html );
	}
	
	return $html;
}

/**
 * Minify CSS
 */
function aqualuxe_minify_css( $css ) {
	if ( is_admin() ) {
		return $css;
	}
	
	// Remove comments
	$css = preg_replace( '!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css );
	
	// Remove whitespace
	$css = preg_replace( '/\s+/', ' ', $css );
	
	// Remove spaces before and after certain characters
	$css = preg_replace( '/\s*([{}|:;,])\s*/', '$1', $css );
	
	// Remove unnecessary semicolons
	$css = preg_replace( '/;}/', '}', $css );
	
	return $css;
}
// Uncomment to enable CSS minification
// add_filter( 'wp_head', 'aqualuxe_minify_css_output' );

/**
 * Minify CSS output
 */
function aqualuxe_minify_css_output() {
	ob_start( 'aqualuxe_minify_css' );
}
// Uncomment to enable CSS minification
// add_action( 'template_redirect', 'aqualuxe_minify_css_output' );

/**
 * Optimize WooCommerce performance
 */
function aqualuxe_woocommerce_performance() {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return;
	}
	
	// Remove generator tag
	remove_action( 'get_the_generator_html', 'wc_generator_tag', 10 );
	remove_action( 'get_the_generator_xhtml', 'wc_generator_tag', 10 );
	
	// Optimize product queries
	add_filter( 'woocommerce_product_query_meta_query', 'aqualuxe_optimize_product_query' );
}
add_action( 'init', 'aqualuxe_woocommerce_performance' );

/**
 * Optimize product queries
 */
function aqualuxe_optimize_product_query( $meta_query ) {
	// Add optimization for product queries
	return $meta_query;
}

/**
 * Add cache headers for static resources
 */
function aqualuxe_cache_headers() {
	if ( is_admin() ) {
		return;
	}
	
	// Set cache headers for static resources
	if ( preg_match( '/\.(css|js|png|jpg|jpeg|gif|webp|svg|ico|woff|woff2|ttf|eot)$/i', $_SERVER['REQUEST_URI'] ) ) {
		// Cache for 1 year
		header( 'Cache-Control: public, max-age=31536000' );
		header( 'Expires: ' . gmdate( 'D, d M Y H:i:s', time() + 31536000 ) . ' GMT' );
	}
}
// Uncomment to enable cache headers
// add_action( 'send_headers', 'aqualuxe_cache_headers' );