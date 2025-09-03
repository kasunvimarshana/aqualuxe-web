<?php
namespace Aqualuxe\Modules\Woo;

defined('ABSPATH') || exit;

final class WooModule {
    public static function register(): void {
        if ( ! class_exists( 'WooCommerce' ) ) {
            return; // Dual-state: skip if WC is not active
        }
        \add_action( 'after_setup_theme', [ __CLASS__, 'supports' ] );
        \add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' ); // Let theme handle styles
        \add_action( 'wp_enqueue_scripts', [ __CLASS__, 'assets' ], 30 );
        \add_filter( 'body_class', [ __CLASS__, 'body_class' ] );
    }

    public static function supports(): void {
        \add_theme_support( 'woocommerce' );
        \add_theme_support( 'wc-product-gallery-zoom' );
        \add_theme_support( 'wc-product-gallery-lightbox' );
        \add_theme_support( 'wc-product-gallery-slider' );
        \add_image_size( 'aqlx-product-thumb', 400, 400, true );
    }

    public static function assets(): void {
        // Could enqueue additional WC-specific JS/CSS; keep minimal for now
    }

    public static function body_class( array $classes ): array {
        $classes[] = 'woocommerce-enabled';
        return $classes;
    }
}
