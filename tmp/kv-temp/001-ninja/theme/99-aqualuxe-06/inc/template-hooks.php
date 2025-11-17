<?php
/**
 * Template Hooks
 *
 * Action and filter hooks used throughout the theme
 *
 * @package AquaLuxe
 * @since 2.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Header Hooks
 *
 * @see aqualuxe_header_search() - 10
 * @see aqualuxe_header_cart() - 20
 * @see aqualuxe_header_account() - 30
 * @see aqualuxe_header_dark_mode_toggle() - 40
 */

/**
 * Content Hooks
 *
 * @see aqualuxe_output_content_wrapper() - 10
 */

/**
 * Footer Hooks
 *
 * @see aqualuxe_output_social_links() - 10
 */

/**
 * Posts Hooks
 *
 * @see aqualuxe_posts_navigation() - 10
 */

/**
 * Sidebar Hooks
 *
 * @see aqualuxe_get_sidebar() - 20
 */

/**
 * Theme-specific action hooks
 */

// Header hooks
add_action( 'aqualuxe_header_start', '__return_false' );
add_action( 'aqualuxe_header_end', '__return_false' );
add_action( 'aqualuxe_after_header', '__return_false' );

// Content hooks
add_action( 'aqualuxe_before_main_content', '__return_false' );
add_action( 'aqualuxe_main_content_start', '__return_false' );
add_action( 'aqualuxe_main_content_end', '__return_false' );
add_action( 'aqualuxe_after_main_content', '__return_false' );
add_action( 'aqualuxe_content_start', '__return_false' );
add_action( 'aqualuxe_content_end', '__return_false' );

// Footer hooks
add_action( 'aqualuxe_before_footer', '__return_false' );
add_action( 'aqualuxe_footer_start', '__return_false' );
add_action( 'aqualuxe_footer_end', '__return_false' );
add_action( 'aqualuxe_after_footer', '__return_false' );
add_action( 'aqualuxe_before_closing_body', '__return_false' );

// Post/Loop hooks
add_action( 'aqualuxe_after_posts_loop', '__return_false' );

/**
 * Theme-specific filter hooks
 */

/**
 * Modify excerpt length
 */
function aqualuxe_filter_excerpt_length( $length ) {
	if ( is_admin() ) {
		return $length;
	}
	
	return apply_filters( 'aqualuxe_excerpt_length', 25 );
}
add_filter( 'excerpt_length', 'aqualuxe_filter_excerpt_length', 999 );

/**
 * Modify excerpt more
 */
function aqualuxe_filter_excerpt_more( $more ) {
	if ( is_admin() ) {
		return $more;
	}
	
	return apply_filters( 'aqualuxe_excerpt_more', '&hellip;' );
}
add_filter( 'excerpt_more', 'aqualuxe_filter_excerpt_more' );

/**
 * Add theme support for WooCommerce
 */
function aqualuxe_add_woocommerce_support() {
	add_theme_support( 'woocommerce', array(
		'thumbnail_image_width' => 300,
		'gallery_thumbnail_image_width' => 100,
		'single_image_width' => 600,
		'product_grid' => array(
			'default_rows' => 3,
			'min_rows' => 2,
			'max_rows' => 8,
			'default_columns' => 4,
			'min_columns' => 2,
			'max_columns' => 5,
		),
	) );
}
add_action( 'after_setup_theme', 'aqualuxe_add_woocommerce_support' );

/**
 * Disable WooCommerce default styles
 */
add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );

/**
 * Custom body classes
 */
function aqualuxe_custom_body_classes( $classes ) {
	// Add page slug if it doesn't exist
	if ( is_single() || is_page() && ! is_front_page() ) {
		if ( ! in_array( basename( get_permalink() ), $classes ) ) {
			$classes[] = basename( get_permalink() );
		}
	}
	
	// Add class if we have a sidebar
	if ( aqualuxe_show_sidebar() ) {
		$classes[] = 'has-sidebar';
	} else {
		$classes[] = 'no-sidebar';
	}
	
	// Add WooCommerce classes
	if ( aqualuxe_is_woocommerce_active() ) {
		$classes[] = 'woocommerce-enabled';
		
		if ( aqualuxe_is_woocommerce_page() ) {
			$classes[] = 'woocommerce-page';
		}
	}
	
	// Add blog classes
	if ( aqualuxe_is_blog() ) {
		$classes[] = 'blog-page';
	}
	
	return $classes;
}
add_filter( 'body_class', 'aqualuxe_custom_body_classes' );

/**
 * Custom post classes
 */
function aqualuxe_custom_post_classes( $classes ) {
	// Add featured image class
	if ( has_post_thumbnail() ) {
		$classes[] = 'has-thumbnail';
	}
	
	// Add excerpt class
	if ( has_excerpt() ) {
		$classes[] = 'has-excerpt';
	}
	
	return $classes;
}
add_filter( 'post_class', 'aqualuxe_custom_post_classes' );

/**
 * Modify comment form fields
 */
function aqualuxe_modify_comment_form_fields( $fields ) {
	// Add CSS classes to form fields
	$fields['author'] = str_replace( '<input', '<input class="form-control"', $fields['author'] );
	$fields['email']  = str_replace( '<input', '<input class="form-control"', $fields['email'] );
	$fields['url']    = str_replace( '<input', '<input class="form-control"', $fields['url'] );
	
	return $fields;
}
add_filter( 'comment_form_default_fields', 'aqualuxe_modify_comment_form_fields' );

/**
 * Modify comment form args
 */
function aqualuxe_modify_comment_form_args( $args ) {
	$args['class_submit'] = 'btn btn-primary';
	$args['comment_field'] = str_replace( '<textarea', '<textarea class="form-control"', $args['comment_field'] );
	
	return $args;
}
add_filter( 'comment_form_defaults', 'aqualuxe_modify_comment_form_args' );

/**
 * Remove unnecessary emoji scripts
 */
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );

/**
 * Remove unnecessary generator meta tag
 */
remove_action( 'wp_head', 'wp_generator' );

/**
 * Custom search form
 */
function aqualuxe_get_search_form( $form ) {
	$form = '<form role="search" method="get" class="search-form" action="' . home_url( '/' ) . '">
		<label class="screen-reader-text" for="s">' . __( 'Search for:', 'aqualuxe' ) . '</label>
		<input type="search" class="search-field" placeholder="' . esc_attr_x( 'Search &hellip;', 'placeholder', 'aqualuxe' ) . '" value="' . get_search_query() . '" name="s" />
		<button type="submit" class="search-submit">
			' . aqualuxe_get_svg_icon( 'search' ) . '
			<span class="screen-reader-text">' . _x( 'Search', 'submit button', 'aqualuxe' ) . '</span>
		</button>
	</form>';
	
	return $form;
}
add_filter( 'get_search_form', 'aqualuxe_get_search_form' );

/**
 * Add custom image sizes to media library
 */
function aqualuxe_custom_image_sizes( $sizes ) {
	return array_merge( $sizes, array(
		'aqualuxe-hero' => __( 'Hero Image', 'aqualuxe' ),
		'aqualuxe-featured' => __( 'Featured Image', 'aqualuxe' ),
		'aqualuxe-card' => __( 'Card Image', 'aqualuxe' ),
		'aqualuxe-thumbnail' => __( 'Thumbnail', 'aqualuxe' ),
	) );
}
add_filter( 'image_size_names_choose', 'aqualuxe_custom_image_sizes' );