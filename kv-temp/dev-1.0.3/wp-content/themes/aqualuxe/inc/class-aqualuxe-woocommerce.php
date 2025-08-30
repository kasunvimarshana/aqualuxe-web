<?php

/**
 * AquaLuxe WooCommerce Integration
 *
 * @package AquaLuxe
 */

if (! defined('ABSPATH')) {
    exit;
}

class AquaLuxe_WooCommerce
{

    /**
     * Constructor
     */
    public function __construct()
    {
        // Theme support for WooCommerce
        add_action('after_setup_theme', [$this, 'theme_support']);

        // Remove default WooCommerce styles
        add_filter('woocommerce_enqueue_styles', '__return_empty_array');

        // Enqueue WooCommerce custom styles
        add_action('wp_enqueue_scripts', [$this, 'enqueue_styles']);

        // Override number of products per row
        add_filter('loop_shop_columns', [$this, 'products_per_row']);

        // Override number of products per page
        add_filter('loop_shop_per_page', [$this, 'products_per_page']);

        // AJAX add-to-cart fragments
        add_filter('woocommerce_add_to_cart_fragments', [$this, 'update_cart_count']);

        // // Lazy Loading Images (Enable for all images except decorative)
        // add_filter('wp_get_attachment_image_attributes', function ($attr) {
        //     if (empty($attr['class']) || strpos($attr['class'], 'no-lazy') === false) {
        //         $attr['loading'] = 'lazy';
        //     }
        //     return $attr;
        // });
    }

    /**
     * Add theme support for WooCommerce features
     */
    public function theme_support()
    {
        add_theme_support('woocommerce', [
            'thumbnail_image_width' => 400,
            'single_image_width'    => 800,
            'product_grid'          => [
                'default_rows'    => 4,
                'min_rows'        => 2,
                'max_rows'        => 8,
                'default_columns' => 4,
                'min_columns'     => 2,
                'max_columns'     => 5,
            ],
        ]);

        // Gallery support
        add_theme_support('wc-product-gallery-zoom');
        add_theme_support('wc-product-gallery-lightbox');
        add_theme_support('wc-product-gallery-slider');
    }

    /**
     * Enqueue WooCommerce styles
     */
    public function enqueue_styles()
    {
        wp_enqueue_style(
            'aqualuxe-woocommerce',
            get_template_directory_uri() . '/assets/css/woocommerce.css',
            [],
            wp_get_theme()->get('Version')
        );
    }

    /**
     * Products per row
     */
    public function products_per_row()
    {
        return 4; // Change as needed
    }

    /**
     * Products per page
     */
    public function products_per_page()
    {
        return 12; // Change as needed
    }

    /**
     * Update cart count dynamically via AJAX
     */
    public function update_cart_count($fragments)
    {
        ob_start();
?>
        <span class="cart-count"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
<?php
        $fragments['span.cart-count'] = ob_get_clean();
        return $fragments;
    }
}

// Initialize
new AquaLuxe_WooCommerce();
