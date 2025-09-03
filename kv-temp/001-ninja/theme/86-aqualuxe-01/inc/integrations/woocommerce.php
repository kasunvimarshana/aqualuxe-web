<?php
namespace AquaLuxe\Integrations;

class WooCommerce
{
    public static function init(): void
    {
        if (!class_exists('WooCommerce')) {
            return; // Dual-state: do nothing if Woo not active
        }
        \add_theme_support('woocommerce');
        \add_filter('woocommerce_enqueue_styles', '__return_empty_array'); // use theme CSS only
        \add_action('after_setup_theme', [__CLASS__, 'images']);
        \add_filter('loop_shop_per_page', fn() => 12, 20);
        \add_filter('woocommerce_output_related_products_args', function ($args) {
            $args['posts_per_page'] = 4; $args['columns'] = 4; return $args;
        }, 10);
    }

    public static function images(): void
    {
        \add_theme_support('wc-product-gallery-zoom');
        \add_theme_support('wc-product-gallery-lightbox');
        \add_theme_support('wc-product-gallery-slider');
    }
}
