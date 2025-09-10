<?php
namespace AquaLuxe\Integrations;

use function add_theme_support;
use function add_filter;
use function add_action;

class WooCommerceCompat
{
    public function boot(): void
    {
        // Declare Woo support and image sizes.
        add_action('after_setup_theme', function () {
            add_theme_support('woocommerce');
            add_theme_support('wc-product-gallery-zoom');
            add_theme_support('wc-product-gallery-lightbox');
            add_theme_support('wc-product-gallery-slider');
        });

        // Products per row default.
        add_filter('loop_shop_columns', fn() => 3);

        // Mini cart fragment for ajax updates (optional).
        add_filter('woocommerce_add_to_cart_fragments', function ($fragments) {
            ob_start();
            echo '<span class="aqlx-cart-count">' . (int) WC()->cart->get_cart_contents_count() . '</span>';
            $fragments['span.aqlx-cart-count'] = ob_get_clean();
            return $fragments;
        });
    }
}
