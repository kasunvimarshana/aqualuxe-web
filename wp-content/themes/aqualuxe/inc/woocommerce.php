<?php
/**
 * WooCommerce Integration
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Add theme support for WooCommerce
add_action('after_setup_theme', 'aqualuxe_woocommerce_setup');
if (!function_exists('aqualuxe_woocommerce_setup')) {
    /**
     * Add theme support for WooCommerce
     *
     * @since 1.0.0
     */
    function aqualuxe_woocommerce_setup() {
        add_theme_support('woocommerce');
        add_theme_support('wc-product-gallery-zoom');
        add_theme_support('wc-product-gallery-lightbox');
        add_theme_support('wc-product-gallery-slider');
    }
}

// Enqueue WooCommerce styles and scripts
add_action('wp_enqueue_scripts', 'aqualuxe_woocommerce_scripts');
if (!function_exists('aqualuxe_woocommerce_scripts')) {
    /**
     * Enqueue WooCommerce styles and scripts
     *
     * @since 1.0.0
     */
    function aqualuxe_woocommerce_scripts() {
        if (class_exists('WooCommerce')) {
            // Only load WooCommerce styles and scripts on WooCommerce pages
            if (is_woocommerce() || is_cart() || is_checkout() || is_account_page()) {
                wp_enqueue_style(
                    'aqualuxe-woocommerce-style',
                    get_stylesheet_directory_uri() . '/assets/css/woocommerce.css',
                    array(),
                    AQUALUXE_VERSION
                );
                
                wp_enqueue_script(
                    'aqualuxe-woocommerce-js',
                    get_stylesheet_directory_uri() . '/assets/js/woocommerce.js',
                    array('jquery'),
                    AQUALUXE_VERSION,
                    true
                );
                
                // Localize script for AJAX
                wp_localize_script('aqualuxe-woocommerce-js', 'aqualuxe_ajax', array(
                    'ajax_url' => admin_url('admin-ajax.php'),
                    'nonce' => wp_create_nonce('aqualuxe_nonce'),
                ));
            }
        }
    }
}

// Remove default WooCommerce styles
add_filter('woocommerce_enqueue_styles', '__return_empty_array');

// Change number of products per row
add_filter('loop_shop_columns', 'aqualuxe_loop_columns');
if (!function_exists('aqualuxe_loop_columns')) {
    /**
     * Change number of products per row
     *
     * @since 1.0.0
     */
    function aqualuxe_loop_columns() {
        return 3; // 3 products per row
    }
}

// Change number of products per page
add_filter('loop_shop_per_page', 'aqualuxe_loop_shop_per_page', 20);
if (!function_exists('aqualuxe_loop_shop_per_page')) {
    /**
     * Change number of products per page
     *
     * @since 1.0.0
     */
    function aqualuxe_loop_shop_per_page($cols) {
        return 9; // 9 products per page
    }
}

// Add custom WooCommerce functions
add_action('init', 'aqualuxe_woocommerce_init');
if (!function_exists('aqualuxe_woocommerce_init')) {
    /**
     * Initialize WooCommerce functions
     *
     * @since 1.0.0
     */
    function aqualuxe_woocommerce_init() {
        // Add custom image sizes for products
        add_image_size('aqualuxe-product-thumbnail', 300, 300, true);
        add_image_size('aqualuxe-product-medium', 600, 600, true);
        add_image_size('aqualuxe-product-large', 900, 900, true);
    }
}

// Customize product gallery thumbnail columns
add_filter('woocommerce_product_thumbnails_columns', 'aqualuxe_thumbnail_columns');
if (!function_exists('aqualuxe_thumbnail_columns')) {
    /**
     * Change number of thumbnail columns in product gallery
     *
     * @since 1.0.0
     */
    function aqualuxe_thumbnail_columns() {
        return 4; // 4 thumbnails per row
    }
}

// Customize related products
add_filter('woocommerce_output_related_products_args', 'aqualuxe_related_products_args');
if (!function_exists('aqualuxe_related_products_args')) {
    /**
     * Change number of related products
     *
     * @since 1.0.0
     */
    function aqualuxe_related_products_args($args) {
        $args['posts_per_page'] = 3; // 3 related products
        $args['columns'] = 3; // 3 columns
        return $args;
    }
}

// Customize upsells
remove_action('woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15);
add_action('woocommerce_after_single_product_summary', 'aqualuxe_upsell_display', 15);
if (!function_exists('aqualuxe_upsell_display')) {
    /**
     * Change number of upsells
     *
     * @since 1.0.0
     */
    function aqualuxe_upsell_display() {
        woocommerce_upsell_display(3, 3); // 3 upsells, 3 columns
    }
}

// Add AJAX add to cart functionality
add_action('wp_ajax_aqualuxe_add_to_cart', 'aqualuxe_ajax_add_to_cart');
add_action('wp_ajax_nopriv_aqualuxe_add_to_cart', 'aqualuxe_ajax_add_to_cart');
if (!function_exists('aqualuxe_ajax_add_to_cart')) {
    /**
     * Handle AJAX add to cart
     *
     * @since 1.0.0
     */
    function aqualuxe_ajax_add_to_cart() {
        // Verify nonce
        if (!wp_verify_nonce($_POST['nonce'], 'aqualuxe_nonce')) {
            wp_send_json_error(array(
                'message' => __('Security verification failed. Please refresh the page and try again.', 'aqualuxe')
            ));
        }
        
        $product_id = intval($_POST['product_id']);
        $quantity = intval($_POST['quantity']) ? intval($_POST['quantity']) : 1;
        $variation_id = intval($_POST['variation_id']);
        $variation = array();
        
        // Handle variable products
        if ($variation_id) {
            $variation = array();
            foreach ($_POST['variation'] as $key => $value) {
                $variation[sanitize_text_field($key)] = sanitize_text_field($value);
            }
        }
        
        // Add to cart
        $added = WC()->cart->add_to_cart($product_id, $quantity, $variation_id, $variation);
        
        if ($added) {
            // Return success response
            wp_send_json_success(array(
                'message' => __('Product added to cart successfully!', 'aqualuxe'),
                'cart_count' => WC()->cart->get_cart_contents_count(),
                'cart_total' => WC()->cart->get_cart_total(),
                'product_data' => $added
            ));
        } else {
            // Return error response with more details
            $product = wc_get_product($product_id);
            if ($product && !$product->is_in_stock()) {
                wp_send_json_error(array(
                    'message' => __('Sorry, this product is out of stock.', 'aqualuxe')
                ));
            } else {
                wp_send_json_error(array(
                    'message' => __('Could not add product to cart. Please try again.', 'aqualuxe')
                ));
            }
        }
    }
}

// Add quick view functionality
add_action('wp_ajax_aqualuxe_quick_view', 'aqualuxe_quick_view');
add_action('wp_ajax_nopriv_aqualuxe_quick_view', 'aqualuxe_quick_view');
if (!function_exists('aqualuxe_quick_view')) {
    /**
     * Handle quick view AJAX request
     *
     * @since 1.0.0
     */
    function aqualuxe_quick_view() {
        // Verify nonce
        if (!wp_verify_nonce($_POST['nonce'], 'aqualuxe_nonce')) {
            wp_send_json_error(array(
                'message' => __('Security verification failed. Please refresh the page and try again.', 'aqualuxe')
            ));
        }
        
        $product_id = intval($_POST['product_id']);
        $product = wc_get_product($product_id);
        
        if (!$product) {
            wp_send_json_error(array(
                'message' => __('Product not found.', 'aqualuxe')
            ));
        }
        
        // Return product data
        ob_start();
        include AQUALUXE_CHILD_THEME_DIR . '/woocommerce/content-quick-view.php';
        $content = ob_get_clean();
        
        wp_send_json_success(array(
            'content' => $content,
            'product_type' => $product->get_type()
        ));
    }
}