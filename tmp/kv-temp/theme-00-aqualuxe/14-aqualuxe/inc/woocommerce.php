<?php
/**
 * AquaLuxe WooCommerce Functions
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Add theme support for WooCommerce
function aqualuxe_woocommerce_setup() {
	add_theme_support( 'woocommerce' );
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );
}

// add_action( 'after_setup_theme', 'aqualuxe_woocommerce_setup' );
// We need to delay the WooCommerce setup until init action to avoid translation issues
add_action( 'init', 'aqualuxe_woocommerce_setup' );

// Remove default WooCommerce styles
add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );

// Wrap content with divs for styling
add_action( 'woocommerce_before_main_content', 'aqualuxe_wrapper_start', 10 );
add_action( 'woocommerce_after_main_content', 'aqualuxe_wrapper_end', 10 );

function aqualuxe_wrapper_start() {
	echo '<div id="primary" class="content-area">';
	echo '<main id="main" class="site-main">';
}

function aqualuxe_wrapper_end() {
	echo '</main>';
	echo '</div>';
}

// Change number of products per row
add_filter( 'loop_shop_columns', 'aqualuxe_loop_columns' );
if ( ! function_exists( 'aqualuxe_loop_columns' ) ) {
	function aqualuxe_loop_columns() {
		return 3; // 3 products per row
	}
}

// Change number of related products
add_filter( 'woocommerce_output_related_products_args', 'aqualuxe_related_products_args' );
function aqualuxe_related_products_args( $args ) {
	$args['posts_per_page'] = 3; // 3 related products
	$args['columns'] = 3; // 3 columns
	return $args;
}

// Move breadcrumbs
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
add_action( 'woocommerce_before_shop_loop', 'woocommerce_breadcrumb', 10 );

// Customize product gallery thumbnail columns
add_filter( 'woocommerce_product_thumbnails_columns', 'aqualuxe_thumbnail_columns' );
function aqualuxe_thumbnail_columns() {
	return 4; // 4 thumbnails per row
}

// Customize product gallery image size
add_filter( 'woocommerce_gallery_thumbnail_size', 'aqualuxe_gallery_thumbnail_size' );
function aqualuxe_gallery_thumbnail_size( $size ) {
	return 'woocommerce_thumbnail';
}

// Customize single product image size
add_filter( 'woocommerce_get_image_size_woocommerce_single', 'aqualuxe_custom_single_image_size' );
function aqualuxe_custom_single_image_size( $size ) {
	return array(
		'width'  => 600,
		'height' => 600,
		'crop'   => 1,
	);
}

// Customize shop page image size
add_filter( 'woocommerce_get_image_size_woocommerce_thumbnail', 'aqualuxe_custom_shop_image_size' );
function aqualuxe_custom_shop_image_size( $size ) {
	return array(
		'width'  => 300,
		'height' => 300,
		'crop'   => 1,
	);
}

// Add custom WooCommerce styles
function aqualuxe_woocommerce_styles() {
	wp_enqueue_style( 'aqualuxe-woocommerce', AQUALUXE_URI . '/assets/css/woocommerce.css', array(), AQUALUXE_VERSION );
}
add_action( 'wp_enqueue_scripts', 'aqualuxe_woocommerce_styles' );

// Add custom WooCommerce scripts
function aqualuxe_woocommerce_scripts() {
	wp_enqueue_script( 'aqualuxe-woocommerce-js', AQUALUXE_URI . '/assets/js/woocommerce.js', array( 'jquery' ), AQUALUXE_VERSION, true );
}
add_action( 'wp_enqueue_scripts', 'aqualuxe_woocommerce_scripts' );

// Customize add to cart message
add_filter( 'wc_add_to_cart_message_html', 'aqualuxe_add_to_cart_message', 10, 3 );
function aqualuxe_add_to_cart_message( $message, $products, $show_qty ) {
	$titles = array();
	$count  = 0;

	foreach ( $products as $product_id => $qty ) {
		$titles[] = ( $qty > 1 ? $qty . ' &times; ' : '' ) . sprintf( '&ldquo;%s&rdquo;', strip_tags( get_the_title( $product_id ) ) );
		$count += $qty;
	}

	$titles = array_filter( $titles );
	$added_text = sprintf( _n( '%s has been added to your cart.', '%s have been added to your cart.', $count, 'aqualuxe' ), wc_format_list_of_items( $titles ) );

	// Add undo link
	if ( wc_string_to_bool( get_option( 'woocommerce_cart_redirect_after_add' ) ) ) {
		$return_to = wp_get_referer() ? wp_get_referer() : wc_get_page_permalink( 'shop' );
		$message   = sprintf( '<span class="added-to-cart">%s</span> <a href="%s" class="button wc-forward">%s</a>', $added_text, esc_url( $return_to ), esc_html__( 'Continue Shopping', 'aqualuxe' ) );
	} else {
		$message   = sprintf( '<span class="added-to-cart">%s</span> <a href="%s" class="button wc-forward">%s</a>', $added_text, esc_url( wc_get_page_permalink( 'cart' ) ), esc_html__( 'View Cart', 'aqualuxe' ) );
	}

	return $message;
}