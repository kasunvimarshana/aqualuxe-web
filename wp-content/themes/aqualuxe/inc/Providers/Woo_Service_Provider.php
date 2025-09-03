<?php
namespace Aqualuxe\Providers;

use Aqualuxe\Support\Container;

class Woo_Service_Provider
{
    public function register(Container $c): void
    {
        add_action('after_setup_theme', function () {
            add_theme_support('woocommerce');
            add_theme_support('wc-product-gallery-zoom');
            add_theme_support('wc-product-gallery-lightbox');
            add_theme_support('wc-product-gallery-slider');
        });

        add_filter('woocommerce_enqueue_styles', '__return_empty_array'); // Use theme styles

        // Graceful fallbacks handled by templates checking class_exists('WooCommerce')
    }

    public function boot(Container $c): void {}
}
