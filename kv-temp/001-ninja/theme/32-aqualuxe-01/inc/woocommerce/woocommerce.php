<?php
/**
 * WooCommerce compatibility file
 *
 * @link https://woocommerce.com/
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Check if WooCommerce is active
if ( ! class_exists( 'WooCommerce' ) ) {
	return;
}

/**
 * Initialize WooCommerce integration
 */
function aqualuxe_woocommerce_init() {
	// Load the WooCommerce class
	$woocommerce = \AquaLuxe\Core\Theme::get_instance()->get_service('woocommerce');
	
	// If the service isn't available, load the legacy integration
	if (!$woocommerce) {
		aqualuxe_woocommerce_legacy_init();
	}
}
add_action('after_setup_theme', 'aqualuxe_woocommerce_init', 10);

/**
 * Legacy WooCommerce setup function.
 */
function aqualuxe_woocommerce_legacy_init() {
	// Add theme support for WooCommerce.
	add_theme_support( 'woocommerce' );
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );
	
	// Declare WooCommerce support.
	add_theme_support(
		'woocommerce',
		array(
			'thumbnail_image_width' => 400,
			'single_image_width'    => 800,
			'product_grid'          => array(
				'default_rows'    => 3,
				'min_rows'        => 1,
				'max_rows'        => 8,
				'default_columns' => 4,
				'min_columns'     => 1,
				'max_columns'     => 6,
			),
		)
	);
	
	// Add WooCommerce specific hooks
	add_action('wp_enqueue_scripts', 'aqualuxe_woocommerce_scripts');
	add_filter('body_class', 'aqualuxe_woocommerce_active_body_class');
	add_filter('woocommerce_output_related_products_args', 'aqualuxe_woocommerce_related_products_args');
	add_filter('woocommerce_product_thumbnails_columns', 'aqualuxe_woocommerce_thumbnail_columns');
	add_filter('loop_shop_per_page', 'aqualuxe_woocommerce_products_per_page');
	add_filter('woocommerce_loop_columns', 'aqualuxe_woocommerce_loop_columns');
}

/**
 * WooCommerce specific scripts & stylesheets.
 *
 * @return void
 */
function aqualuxe_woocommerce_scripts() {
	// Register WooCommerce styles.
	wp_register_style(
		'aqualuxe-woocommerce',
		AQUALUXE_URI . '/assets/css/woocommerce.css',
		array( 'aqualuxe-style' ),
		AQUALUXE_VERSION
	);
	
	// Enqueue WooCommerce styles.
	wp_enqueue_style( 'aqualuxe-woocommerce' );
	
	// Add RTL support.
	wp_style_add_data( 'aqualuxe-woocommerce', 'rtl', 'replace' );
	
	// Register WooCommerce scripts.
	wp_register_script(
		'aqualuxe-woocommerce',
		AQUALUXE_URI . '/assets/js/woocommerce.js',
		array( 'jquery' ),
		AQUALUXE_VERSION,
		true
	);
	
	// Enqueue WooCommerce scripts.
	wp_enqueue_script( 'aqualuxe-woocommerce' );
	
	// Localize WooCommerce scripts.
	wp_localize_script(
		'aqualuxe-woocommerce',
		'aqualuxeWooCommerce',
		array(
			'ajaxUrl'             => admin_url( 'admin-ajax.php' ),
			'nonce'               => wp_create_nonce( 'aqualuxe-woocommerce' ),
			'addToCartText'       => esc_html__( 'Add to cart', 'aqualuxe' ),
			'viewCartText'        => esc_html__( 'View cart', 'aqualuxe' ),
			'addingToCartText'    => esc_html__( 'Adding...', 'aqualuxe' ),
			'addedToCartText'     => esc_html__( 'Added!', 'aqualuxe' ),
			'wishlistAddText'     => esc_html__( 'Add to wishlist', 'aqualuxe' ),
			'wishlistAddedText'   => esc_html__( 'Added to wishlist', 'aqualuxe' ),
			'wishlistExistsText'  => esc_html__( 'Already in wishlist', 'aqualuxe' ),
			'quickViewText'       => esc_html__( 'Quick view', 'aqualuxe' ),
			'loadingText'         => esc_html__( 'Loading...', 'aqualuxe' ),
			'cartUrl'             => wc_get_cart_url(),
			'isRTL'               => is_rtl(),
		)
	);
}

/**
 * Add 'woocommerce-active' class to the body tag.
 *
 * @param  array $classes CSS classes applied to the body tag.
 * @return array $classes modified to include 'woocommerce-active' class.
 */
function aqualuxe_woocommerce_active_body_class( $classes ) {
	$classes[] = 'woocommerce-active';
	
	// Add shop layout class.
	if ( is_shop() || is_product_category() || is_product_tag() ) {
		$shop_layout = get_theme_mod( 'aqualuxe_shop_layout', 'grid' );
		$classes[]   = 'woocommerce-shop-layout-' . $shop_layout;
		
		// Add sidebar class.
		$shop_sidebar = get_theme_mod( 'aqualuxe_shop_sidebar', 'right' );
		
		if ( 'none' === $shop_sidebar ) {
			$classes[] = 'woocommerce-shop-no-sidebar';
		} else {
			$classes[] = 'woocommerce-shop-has-sidebar';
			$classes[] = 'woocommerce-shop-sidebar-' . $shop_sidebar;
		}
	}
	
	// Add product layout class.
	if ( is_product() ) {
		$product_layout = get_theme_mod( 'aqualuxe_product_layout', 'standard' );
		$classes[]      = 'woocommerce-product-layout-' . $product_layout;
		
		// Add sidebar class.
		$product_sidebar = get_theme_mod( 'aqualuxe_product_sidebar', 'none' );
		
		if ( 'none' === $product_sidebar ) {
			$classes[] = 'woocommerce-product-no-sidebar';
		} else {
			$classes[] = 'woocommerce-product-has-sidebar';
			$classes[] = 'woocommerce-product-sidebar-' . $product_sidebar;
		}
	}
	
	// Add cart layout class.
	if ( is_cart() ) {
		$cart_layout = get_theme_mod( 'aqualuxe_cart_layout', 'standard' );
		$classes[]   = 'woocommerce-cart-layout-' . $cart_layout;
	}
	
	// Add checkout layout class.
	if ( is_checkout() ) {
		$checkout_layout = get_theme_mod( 'aqualuxe_checkout_layout', 'standard' );
		$classes[]       = 'woocommerce-checkout-layout-' . $checkout_layout;
	}
	
	// Add account layout class.
	if ( is_account_page() ) {
		$account_layout = get_theme_mod( 'aqualuxe_account_layout', 'standard' );
		$classes[]      = 'woocommerce-account-layout-' . $account_layout;
	}
	
	return $classes;
}

/**
 * Related Products Args.
 *
 * @param array $args related products args.
 * @return array $args related products args.
 */
function aqualuxe_woocommerce_related_products_args( $args ) {
	$columns = get_theme_mod( 'aqualuxe_related_products_columns', 4 );
	$count   = get_theme_mod( 'aqualuxe_related_products_count', 4 );
	
	$args['posts_per_page'] = $count;
	$args['columns']        = $columns;
	
	return $args;
}

/**
 * Product gallery thumbnail columns.
 *
 * @return integer number of columns.
 */
function aqualuxe_woocommerce_thumbnail_columns() {
	return get_theme_mod( 'aqualuxe_product_gallery_columns', 4 );
}

/**
 * Products per page.
 *
 * @return integer number of products.
 */
function aqualuxe_woocommerce_products_per_page() {
	return get_theme_mod( 'aqualuxe_products_per_page', 12 );
}

/**
 * Product gallery thumbnail columns.
 *
 * @return integer number of columns.
 */
function aqualuxe_woocommerce_loop_columns() {
	return get_theme_mod( 'aqualuxe_shop_columns', 4 );
}