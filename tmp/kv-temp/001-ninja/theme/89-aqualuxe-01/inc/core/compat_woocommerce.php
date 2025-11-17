<?php
/** WooCommerce compatibility */
namespace AquaLuxe\Core;
if ( ! defined( 'ABSPATH' ) ) { exit; }

\add_action( 'after_setup_theme', function () {
	if ( class_exists( '\\WooCommerce' ) ) {
		\add_theme_support( 'woocommerce' );
		\add_theme_support( 'wc-product-gallery-zoom' );
		\add_theme_support( 'wc-product-gallery-lightbox' );
		\add_theme_support( 'wc-product-gallery-slider' );
	}
} );

// Mini cart fragment fallback.
\add_filter( 'woocommerce_add_to_cart_fragments', function ( $fragments ) {
	$fragments['span.alx-cart-count'] = '<span class="alx-cart-count" aria-live="polite">' . \WC()->cart->get_cart_contents_count() . '</span>';
	return $fragments;
} );
