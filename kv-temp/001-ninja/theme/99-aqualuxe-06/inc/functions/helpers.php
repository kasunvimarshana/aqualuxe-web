<?php
/**
 * Helper Functions
 *
 * Common utility functions used throughout the theme
 *
 * @package AquaLuxe
 * @since 2.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Check if WooCommerce is active
 *
 * @return bool True if WooCommerce is active
 */
function aqualuxe_is_woocommerce_active() {
	return class_exists( 'WooCommerce' );
}

/**
 * Check if we're on a WooCommerce page
 *
 * @return bool True if on WooCommerce page
 */
function aqualuxe_is_woocommerce_page() {
	if ( ! aqualuxe_is_woocommerce_active() ) {
		return false;
	}

	return is_woocommerce() || is_cart() || is_checkout() || is_account_page();
}

/**
 * Get theme option with default value
 *
 * @param string $option_name Option name
 * @param mixed  $default     Default value
 * @return mixed Option value or default
 */
function aqualuxe_get_option( $option_name, $default = false ) {
	return get_theme_mod( $option_name, $default );
}

/**
 * Get post excerpt with custom length
 *
 * @param int    $length Post ID (optional)
 * @param int    $length Excerpt length in words
 * @param string $more   More text
 * @return string Post excerpt
 */
function aqualuxe_get_excerpt( $post_id = null, $length = 25, $more = '...' ) {
	$post = get_post( $post_id );
	if ( ! $post ) {
		return '';
	}

	$excerpt = $post->post_excerpt ? $post->post_excerpt : $post->post_content;
	$excerpt = strip_tags( strip_shortcodes( $excerpt ) );
	$words = explode( ' ', $excerpt );

	if ( count( $words ) > $length ) {
		$excerpt = implode( ' ', array_slice( $words, 0, $length ) ) . $more;
	}

	return $excerpt;
}

/**
 * Get reading time estimate
 *
 * @param int $post_id Post ID (optional)
 * @param int $wpm     Words per minute (default 200)
 * @return int Reading time in minutes
 */
function aqualuxe_get_reading_time( $post_id = null, $wpm = 200 ) {
	$post = get_post( $post_id );
	if ( ! $post ) {
		return 0;
	}

	$content = strip_tags( strip_shortcodes( $post->post_content ) );
	$word_count = str_word_count( $content );
	
	return max( 1, ceil( $word_count / $wpm ) );
}

/**
 * Get social share links
 *
 * @param int $post_id Post ID (optional)
 * @return array Social share data
 */
function aqualuxe_get_social_share_links( $post_id = null ) {
	$post = get_post( $post_id );
	if ( ! $post ) {
		return array();
	}

	$url = get_permalink( $post );
	$title = get_the_title( $post );
	$excerpt = aqualuxe_get_excerpt( $post->ID, 20 );

	return array(
		'facebook' => array(
			'url' => 'https://www.facebook.com/sharer/sharer.php?u=' . urlencode( $url ),
			'label' => esc_html__( 'Share on Facebook', 'aqualuxe' ),
		),
		'twitter' => array(
			'url' => 'https://twitter.com/intent/tweet?url=' . urlencode( $url ) . '&text=' . urlencode( $title ),
			'label' => esc_html__( 'Share on Twitter', 'aqualuxe' ),
		),
		'linkedin' => array(
			'url' => 'https://www.linkedin.com/sharing/share-offsite/?url=' . urlencode( $url ),
			'label' => esc_html__( 'Share on LinkedIn', 'aqualuxe' ),
		),
		'pinterest' => array(
			'url' => 'https://pinterest.com/pin/create/button/?url=' . urlencode( $url ) . '&description=' . urlencode( $title ),
			'label' => esc_html__( 'Share on Pinterest', 'aqualuxe' ),
		),
		'email' => array(
			'url' => 'mailto:?subject=' . urlencode( $title ) . '&body=' . urlencode( $excerpt . ' ' . $url ),
			'label' => esc_html__( 'Share via Email', 'aqualuxe' ),
		),
	);
}

/**
 * Sanitize HTML classes
 *
 * @param array|string $classes Classes to sanitize
 * @return string Sanitized classes
 */
function aqualuxe_sanitize_html_classes( $classes ) {
	if ( is_array( $classes ) ) {
		$classes = implode( ' ', $classes );
	}

	return esc_attr( $classes );
}

/**
 * Get responsive image attributes
 *
 * @param int    $image_id   Image attachment ID
 * @param string $size       Image size
 * @param array  $attributes Additional attributes
 * @return string Image HTML
 */
function aqualuxe_get_responsive_image( $image_id, $size = 'large', $attributes = array() ) {
	if ( ! $image_id ) {
		return '';
	}

	$default_attributes = array(
		'class' => 'responsive-image',
		'loading' => 'lazy',
		'decoding' => 'async',
	);

	$attributes = wp_parse_args( $attributes, $default_attributes );

	return wp_get_attachment_image( $image_id, $size, false, $attributes );
}

/**
 * Get SVG icon
 *
 * @param string $icon Icon name
 * @param array  $args Icon arguments
 * @return string SVG icon HTML
 */
function aqualuxe_get_svg_icon( $icon, $args = array() ) {
	$defaults = array(
		'class' => 'icon icon-' . $icon,
		'width' => 24,
		'height' => 24,
		'aria-hidden' => 'true',
		'focusable' => 'false',
	);

	$args = wp_parse_args( $args, $defaults );

	$icons = array(
		'arrow-right' => '<path d="M5 12h14m-7-7l7 7-7 7"/>',
		'arrow-left' => '<path d="M19 12H5m7 7l-7-7 7-7"/>',
		'chevron-down' => '<path d="M6 9l6 6 6-6"/>',
		'chevron-up' => '<path d="M18 15l-6-6-6 6"/>',
		'menu' => '<path d="M4 6h16M4 12h16M4 18h16"/>',
		'close' => '<path d="M18 6L6 18M6 6l12 12"/>',
		'search' => '<path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>',
		'cart' => '<path d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17M17 13a2 2 0 100 4 2 2 0 000-4zM9 19a2 2 0 100 4 2 2 0 000-4z"/>',
		'heart' => '<path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>',
		'user' => '<path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>',
		'sun' => '<circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/>',
		'moon' => '<path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/>',
	);

	$svg_path = isset( $icons[ $icon ] ) ? $icons[ $icon ] : '';

	if ( empty( $svg_path ) ) {
		return '';
	}

	$class = esc_attr( $args['class'] );
	$width = intval( $args['width'] );
	$height = intval( $args['height'] );
	$aria_hidden = $args['aria-hidden'] === 'true' ? 'aria-hidden="true"' : '';
	$focusable = $args['focusable'] === 'false' ? 'focusable="false"' : '';

	return sprintf(
		'<svg class="%s" width="%d" height="%d" %s %s viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">%s</svg>',
		$class,
		$width,
		$height,
		$aria_hidden,
		$focusable,
		$svg_path
	);
}

/**
 * Get breadcrumb navigation
 *
 * @return array Breadcrumb items
 */
function aqualuxe_get_breadcrumbs() {
	$breadcrumbs = array();

	// Home
	$breadcrumbs[] = array(
		'title' => esc_html__( 'Home', 'aqualuxe' ),
		'url' => home_url( '/' ),
		'current' => false,
	);

	if ( is_home() && ! is_front_page() ) {
		$page_id = get_option( 'page_for_posts' );
		$breadcrumbs[] = array(
			'title' => get_the_title( $page_id ),
			'url' => '',
			'current' => true,
		);
	} elseif ( is_category() ) {
		$category = get_queried_object();
		$breadcrumbs[] = array(
			'title' => $category->name,
			'url' => '',
			'current' => true,
		);
	} elseif ( is_tag() ) {
		$tag = get_queried_object();
		$breadcrumbs[] = array(
			'title' => $tag->name,
			'url' => '',
			'current' => true,
		);
	} elseif ( is_single() ) {
		$post = get_queried_object();
		
		// Add category for posts
		if ( $post->post_type === 'post' ) {
			$categories = get_the_category( $post->ID );
			if ( ! empty( $categories ) ) {
				$breadcrumbs[] = array(
					'title' => $categories[0]->name,
					'url' => get_category_link( $categories[0]->term_id ),
					'current' => false,
				);
			}
		}
		
		$breadcrumbs[] = array(
			'title' => get_the_title( $post ),
			'url' => '',
			'current' => true,
		);
	} elseif ( is_page() ) {
		$post = get_queried_object();
		
		// Add parent pages
		if ( $post->post_parent ) {
			$parents = array();
			$parent_id = $post->post_parent;
			
			while ( $parent_id ) {
				$parent = get_post( $parent_id );
				$parents[] = array(
					'title' => get_the_title( $parent ),
					'url' => get_permalink( $parent ),
					'current' => false,
				);
				$parent_id = $parent->post_parent;
			}
			
			$breadcrumbs = array_merge( $breadcrumbs, array_reverse( $parents ) );
		}
		
		$breadcrumbs[] = array(
			'title' => get_the_title( $post ),
			'url' => '',
			'current' => true,
		);
	}

	return apply_filters( 'aqualuxe_breadcrumbs', $breadcrumbs );
}

/**
 * Format currency for display
 *
 * @param float  $amount   Amount to format
 * @param string $currency Currency code
 * @return string Formatted currency
 */
function aqualuxe_format_currency( $amount, $currency = 'USD' ) {
	$currencies = array(
		'USD' => array( 'symbol' => '$', 'position' => 'before' ),
		'EUR' => array( 'symbol' => '€', 'position' => 'after' ),
		'GBP' => array( 'symbol' => '£', 'position' => 'before' ),
		'JPY' => array( 'symbol' => '¥', 'position' => 'before' ),
	);

	$currency_info = isset( $currencies[ $currency ] ) ? $currencies[ $currency ] : $currencies['USD'];
	$formatted_amount = number_format( $amount, 2 );

	if ( $currency_info['position'] === 'before' ) {
		return $currency_info['symbol'] . $formatted_amount;
	} else {
		return $formatted_amount . $currency_info['symbol'];
	}
}

/**
 * Check if development mode is enabled
 *
 * @return bool True if in development mode
 */
function aqualuxe_is_development() {
	return defined( 'WP_DEBUG' ) && WP_DEBUG === true;
}