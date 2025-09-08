<?php
namespace AquaLuxe\Modules\Woocommerce;

if (!defined('ABSPATH')) { exit; }

class Woocommerce {
    public static function init(): void {
        if (!class_exists('WooCommerce')) { return; }
        \add_action('after_setup_theme', [__CLASS__, 'support']);
        \add_filter('woocommerce_enqueue_styles', '__return_empty_array'); // We style via theme assets
    }
    public static function support(): void {
        \add_theme_support('woocommerce');
        \add_theme_support('wc-product-gallery-zoom');
        \add_theme_support('wc-product-gallery-lightbox');
        \add_theme_support('wc-product-gallery-slider');
    }
}
