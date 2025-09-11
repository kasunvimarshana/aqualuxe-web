<?php
/**
 * WooCommerce Setup
 *
 * Theme integration with WooCommerce
 *
 * @package AquaLuxe
 * @since 2.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Check if WooCommerce is active
if ( ! aqualuxe_is_woocommerce_active() ) {
	return;
}

/**
 * WooCommerce theme support
 */
function aqualuxe_woocommerce_setup() {
	add_theme_support( 'woocommerce', array(
		'thumbnail_image_width' => 300,
		'single_image_width'    => 600,
		'product_grid'          => array(
			'default_rows'    => 3,
			'min_rows'        => 2,
			'max_rows'        => 8,
			'default_columns' => 4,
			'min_columns'     => 2,
			'max_columns'     => 5,
		),
	) );

	// Add support for WC features
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );
}
add_action( 'after_setup_theme', 'aqualuxe_woocommerce_setup' );

/**
 * Disable WooCommerce default styles
 */
add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );

/**
 * WooCommerce wrapper start
 */
function aqualuxe_woocommerce_wrapper_start() {
	echo '<div class="woocommerce-wrapper">';
}
add_action( 'woocommerce_before_main_content', 'aqualuxe_woocommerce_wrapper_start', 10 );

/**
 * WooCommerce wrapper end
 */
function aqualuxe_woocommerce_wrapper_end() {
	echo '</div><!-- .woocommerce-wrapper -->';
}
add_action( 'woocommerce_after_main_content', 'aqualuxe_woocommerce_wrapper_end', 10 );

/**
 * Remove WooCommerce sidebar
 */
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );

/**
 * Change number of products per row
 */
function aqualuxe_woocommerce_loop_columns() {
	return apply_filters( 'aqualuxe_woocommerce_loop_columns', 4 );
}
add_filter( 'loop_shop_columns', 'aqualuxe_woocommerce_loop_columns' );

/**
 * Change number of products per page
 */
function aqualuxe_woocommerce_products_per_page() {
	return apply_filters( 'aqualuxe_woocommerce_products_per_page', 12 );
}
add_filter( 'loop_shop_per_page', 'aqualuxe_woocommerce_products_per_page', 20 );

/**
 * Customize product loop
 */
function aqualuxe_woocommerce_product_loop_start() {
	echo '<div class="products-grid">';
}
add_action( 'woocommerce_output_related_products_args', 'aqualuxe_woocommerce_product_loop_start', 5 );

/**
 * Add wrapper to single product summary
 */
function aqualuxe_woocommerce_single_product_summary_start() {
	echo '<div class="product-summary-wrapper">';
}
add_action( 'woocommerce_single_product_summary', 'aqualuxe_woocommerce_single_product_summary_start', 1 );

/**
 * Close wrapper for single product summary
 */
function aqualuxe_woocommerce_single_product_summary_end() {
	echo '</div><!-- .product-summary-wrapper -->';
}
add_action( 'woocommerce_single_product_summary', 'aqualuxe_woocommerce_single_product_summary_end', 100 );

/**
 * Modify cross-sells display
 */
function aqualuxe_woocommerce_cross_sell_display() {
	woocommerce_cross_sell_display( 4, 4 );
}
remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );
add_action( 'woocommerce_cart_collaterals', 'aqualuxe_woocommerce_cross_sell_display', 10 );

/**
 * Modify related products display
 */
function aqualuxe_woocommerce_related_products_args( $args ) {
	$args['posts_per_page'] = 4;
	$args['columns'] = 4;
	return $args;
}
add_filter( 'woocommerce_output_related_products_args', 'aqualuxe_woocommerce_related_products_args' );

/**
 * Add custom fields to checkout
 */
function aqualuxe_woocommerce_checkout_fields( $fields ) {
	// Add aquarium size field for relevant products
	$fields['billing']['billing_aquarium_size'] = array(
		'label'       => __( 'Aquarium Size', 'aqualuxe' ),
		'placeholder' => _x( 'e.g., 50 gallons', 'placeholder', 'aqualuxe' ),
		'required'    => false,
		'class'       => array( 'form-row-wide' ),
		'clear'       => true,
	);

	return $fields;
}
add_filter( 'woocommerce_checkout_fields', 'aqualuxe_woocommerce_checkout_fields' );

/**
 * Save custom checkout fields
 */
function aqualuxe_woocommerce_save_checkout_fields( $order_id ) {
	if ( ! empty( $_POST['billing_aquarium_size'] ) ) {
		update_post_meta( $order_id, 'Aquarium Size', sanitize_text_field( $_POST['billing_aquarium_size'] ) );
	}
}
add_action( 'woocommerce_checkout_update_order_meta', 'aqualuxe_woocommerce_save_checkout_fields' );

/**
 * Display custom fields in admin order
 */
function aqualuxe_woocommerce_admin_order_data( $order ) {
	$aquarium_size = get_post_meta( $order->get_id(), 'Aquarium Size', true );
	
	if ( $aquarium_size ) {
		echo '<p><strong>' . __( 'Aquarium Size:', 'aqualuxe' ) . '</strong> ' . esc_html( $aquarium_size ) . '</p>';
	}
}
add_action( 'woocommerce_admin_order_data_after_billing_address', 'aqualuxe_woocommerce_admin_order_data' );

/**
 * Add to cart AJAX support
 */
function aqualuxe_woocommerce_ajax_add_to_cart_handler() {
	if ( ! wp_verify_nonce( $_POST['nonce'], 'aqualuxe_nonce' ) ) {
		wp_die( 'Security check failed' );
	}

	$product_id = absint( $_POST['product_id'] );
	$quantity = absint( $_POST['quantity'] );
	$variation_id = absint( $_POST['variation_id'] );
	$variation = array();

	if ( $variation_id ) {
		$variation_data = $_POST['variation'];
		if ( is_array( $variation_data ) ) {
			foreach ( $variation_data as $key => $value ) {
				$variation[ sanitize_text_field( $key ) ] = sanitize_text_field( $value );
			}
		}
	}

	$passed_validation = apply_filters( 'woocommerce_add_to_cart_validation', true, $product_id, $quantity );

	if ( $passed_validation && WC()->cart->add_to_cart( $product_id, $quantity, $variation_id, $variation ) ) {
		wp_send_json_success( array(
			'message' => __( 'Product added to cart!', 'aqualuxe' ),
			'cart_count' => WC()->cart->get_cart_contents_count(),
			'cart_total' => WC()->cart->get_cart_total(),
		) );
	} else {
		wp_send_json_error( array(
			'message' => __( 'Failed to add product to cart.', 'aqualuxe' ),
		) );
	}
}
add_action( 'wp_ajax_aqualuxe_add_to_cart', 'aqualuxe_woocommerce_ajax_add_to_cart_handler' );
add_action( 'wp_ajax_nopriv_aqualuxe_add_to_cart', 'aqualuxe_woocommerce_ajax_add_to_cart_handler' );

/**
 * Update cart fragments
 */
function aqualuxe_woocommerce_cart_fragments( $fragments ) {
	$cart_count = WC()->cart->get_cart_contents_count();
	
	$fragments['.cart-count'] = '<span class="cart-count">' . esc_html( $cart_count ) . '</span>';
	
	return $fragments;
}
add_filter( 'woocommerce_add_to_cart_fragments', 'aqualuxe_woocommerce_cart_fragments' );

/**
 * Customize breadcrumbs
 */
function aqualuxe_woocommerce_breadcrumbs() {
	return array(
		'delimiter'   => ' / ',
		'wrap_before' => '<nav class="woocommerce-breadcrumb">',
		'wrap_after'  => '</nav>',
		'before'      => '',
		'after'       => '',
		'home'        => _x( 'Home', 'breadcrumb', 'aqualuxe' ),
	);
}
add_filter( 'woocommerce_breadcrumb_defaults', 'aqualuxe_woocommerce_breadcrumbs' );