<?php
namespace AquaLuxe\Integrations;

class WooCommerce
{
    public static function init(): void
    {
        if (!\class_exists('WooCommerce')) {
            return; // Dual-state: do nothing if Woo not active
        }
        \add_theme_support('woocommerce');
        \add_filter('woocommerce_enqueue_styles', '__return_empty_array'); // use theme CSS only
        \add_action('after_setup_theme', [__CLASS__, 'images']);
        \add_filter('loop_shop_per_page', fn() => 12, 20);
        \add_filter('woocommerce_output_related_products_args', function ($args) {
            $args['posts_per_page'] = 4; $args['columns'] = 4; return $args;
        }, 10);
        // Quick view trigger button in loop
        \add_action('woocommerce_after_shop_loop_item', [__CLASS__, 'quick_view_button'], 15);
    }

    public static function images(): void
    {
        \add_theme_support('wc-product-gallery-zoom');
        \add_theme_support('wc-product-gallery-lightbox');
        \add_theme_support('wc-product-gallery-slider');
    }

    public static function quick_view_button(): void
    {
        global $product; if (!$product) { return; }
        $id = \method_exists($product, 'get_id') ? (int) $product->get_id() : 0;
        echo '<a href="#" class="btn btn-secondary qv-btn mt-2" data-qv-id="' . \esc_attr($id) . '">' . \esc_html__('Quick view', 'aqualuxe') . '</a>';
    }
}
