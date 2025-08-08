<?php
/**
 * WooCommerce Customizations - Luxury Ornamental Fish Theme
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

if (!function_exists('aqualuxe_woocommerce_setup')) {
    /**
     * Set up WooCommerce customizations.
     *
     * @since 1.0.0
     */
    function aqualuxe_woocommerce_setup() {
        // Add theme support for WooCommerce features
        add_theme_support('woocommerce');
        add_theme_support('wc-product-gallery-zoom');
        add_theme_support('wc-product-gallery-lightbox');
        add_theme_support('wc-product-gallery-slider');
    }
}
add_action('after_setup_theme', 'aqualuxe_woocommerce_setup');

if (!function_exists('aqualuxe_woocommerce_scripts')) {
    /**
     * Enqueue WooCommerce-specific scripts and styles.
     *
     * @since 1.0.0
     */
    function aqualuxe_woocommerce_scripts() {
        // Enqueue WooCommerce styles
        wp_enqueue_style(
            'aqualuxe-woocommerce-style',
            get_stylesheet_directory_uri() . '/assets/css/woocommerce.css',
            array('aqualuxe-style'),
            AQUALUXE_VERSION
        );
        
        // Enqueue WooCommerce scripts
        wp_enqueue_script(
            'aqualuxe-woocommerce-js',
            get_stylesheet_directory_uri() . '/assets/js/woocommerce.js',
            array('jquery', 'wc-add-to-cart-variation'),
            AQUALUXE_VERSION,
            true
        );
        
        // Localize script for AJAX
        wp_localize_script('aqualuxe-woocommerce-js', 'aqualuxe_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('aqualuxe_nonce'),
            'quick_view_enabled' => get_theme_mod('aqualuxe_quick_view', true) ? '1' : '0',
        ));
    }
}
add_action('wp_enqueue_scripts', 'aqualuxe_woocommerce_scripts');

if (!function_exists('aqualuxe_woocommerce_quick_view')) {
    /**
     * Handle quick view AJAX request.
     *
     * @since 1.0.0
     */
    function aqualuxe_woocommerce_quick_view() {
        // Verify nonce
        if (!wp_verify_nonce($_POST['nonce'], 'aqualuxe_nonce')) {
            wp_die('Invalid nonce');
        }
        
        // Get product ID
        $product_id = intval($_POST['product_id']);
        
        // Get product
        $product = wc_get_product($product_id);
        
        if (!$product) {
            wp_die('Product not found');
        }
        
        // Start output buffering
        ob_start();
        
        // Load quick view template
        wc_get_template('content-quick-view.php');
        
        // Get content
        $content = ob_get_clean();
        
        // Return JSON response
        wp_send_json_success(array(
            'content' => $content,
        ));
    }
}
add_action('wp_ajax_aqualuxe_quick_view', 'aqualuxe_woocommerce_quick_view');
add_action('wp_ajax_nopriv_aqualuxe_quick_view', 'aqualuxe_woocommerce_quick_view');

if (!function_exists('aqualuxe_woocommerce_add_to_cart')) {
    /**
     * Handle AJAX add to cart request.
     *
     * @since 1.0.0
     */
    function aqualuxe_woocommerce_add_to_cart() {
        // Verify nonce
        if (!wp_verify_nonce($_POST['nonce'], 'aqualuxe_nonce')) {
            wp_die('Invalid nonce');
        }
        
        // Get product ID and quantity
        $product_id = intval($_POST['product_id']);
        $quantity = intval($_POST['quantity']);
        
        // Add to cart
        $result = WC()->cart->add_to_cart($product_id, $quantity);
        
        if ($result) {
            // Get cart count
            $cart_count = WC()->cart->get_cart_contents_count();
            
            // Return success response
            wp_send_json_success(array(
                'message' => __('Product added to cart successfully!', 'aqualuxe'),
                'cart_count' => $cart_count,
            ));
        } else {
            // Return error response
            wp_send_json_error(array(
                'message' => __('Could not add product to cart.', 'aqualuxe'),
            ));
        }
    }
}
add_action('wp_ajax_aqualuxe_add_to_cart', 'aqualuxe_woocommerce_add_to_cart');
add_action('wp_ajax_nopriv_aqualuxe_add_to_cart', 'aqualuxe_woocommerce_add_to_cart');

if (!function_exists('aqualuxe_woocommerce_cart_count_fragment')) {
    /**
     * Add cart count to cart fragment.
     *
     * @param array $fragments Cart fragments.
     * @return array Updated cart fragments.
     */
    function aqualuxe_woocommerce_cart_count_fragment($fragments) {
        ob_start();
        ?>
        <span class="cart-count"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
        <?php
        $fragments['.cart-count'] = ob_get_clean();
        
        return $fragments;
    }
}
add_filter('woocommerce_add_to_cart_fragments', 'aqualuxe_woocommerce_cart_count_fragment');

if (!function_exists('aqualuxe_woocommerce_loop_columns')) {
    /**
     * Change number of products per row.
     *
     * @return int Number of products per row.
     */
    function aqualuxe_woocommerce_loop_columns() {
        return 3;
    }
}
add_filter('loop_shop_columns', 'aqualuxe_woocommerce_loop_columns');

if (!function_exists('aqualuxe_woocommerce_related_products_args')) {
    /**
     * Change number of related products.
     *
     * @param array $args Related products arguments.
     * @return array Updated arguments.
     */
    function aqualuxe_woocommerce_related_products_args($args) {
        $args['posts_per_page'] = 3;
        $args['columns'] = 3;
        return $args;
    }
}
add_filter('woocommerce_output_related_products_args', 'aqualuxe_woocommerce_related_products_args');

if (!function_exists('aqualuxe_woocommerce_upsell_display')) {
    /**
     * Change number of upsells.
     *
     * @param int $limit Number of upsells.
     * @param int $columns Number of columns.
     * @param string $orderby Order by.
     * @param string $order Order.
     */
    function aqualuxe_woocommerce_upsell_display($limit, $columns, $orderby, $order) {
        woocommerce_upsell_display(3, 3);
    }
}
add_action('woocommerce_upsell_display', 'aqualuxe_woocommerce_upsell_display', 10, 4);

if (!function_exists('aqualuxe_woocommerce_breadcrumb_defaults')) {
    /**
     * Customize breadcrumb defaults.
     *
     * @param array $defaults Default breadcrumb settings.
     * @return array Updated breadcrumb settings.
     */
    function aqualuxe_woocommerce_breadcrumb_defaults($defaults) {
        $defaults['delimiter'] = '<span class="breadcrumb-separator">/</span>';
        $defaults['wrap_before'] = '<nav class="woocommerce-breadcrumb">';
        $defaults['wrap_after'] = '</nav>';
        $defaults['before'] = '';
        $defaults['after'] = '';
        $defaults['home'] = __('Home', 'aqualuxe');
        return $defaults;
    }
}
add_filter('woocommerce_breadcrumb_defaults', 'aqualuxe_woocommerce_breadcrumb_defaults');

if (!function_exists('aqualuxe_woocommerce_pagination_args')) {
    /**
     * Customize pagination arguments.
     *
     * @param array $args Pagination arguments.
     * @return array Updated pagination arguments.
     */
    function aqualuxe_woocommerce_pagination_args($args) {
        $args['prev_text'] = '&larr;';
        $args['next_text'] = '&rarr;';
        $args['type'] = 'list';
        return $args;
    }
}
add_filter('woocommerce_pagination_args', 'aqualuxe_woocommerce_pagination_args');

if (!function_exists('aqualuxe_woocommerce_product_thumbnails_columns')) {
    /**
     * Change number of product thumbnail columns.
     *
     * @return int Number of columns.
     */
    function aqualuxe_woocommerce_product_thumbnails_columns() {
        return 4;
    }
}
add_filter('woocommerce_product_thumbnails_columns', 'aqualuxe_woocommerce_product_thumbnails_columns');

if (!function_exists('aqualuxe_woocommerce_single_product_carousel_options')) {
    /**
     * Customize single product carousel options.
     *
     * @param array $options Carousel options.
     * @return array Updated carousel options.
     */
    function aqualuxe_woocommerce_single_product_carousel_options($options) {
        $options['directionNav'] = true;
        $options['controlNav'] = true;
        $options['smoothHeight'] = true;
        return $options;
    }
}
add_filter('woocommerce_single_product_carousel_options', 'aqualuxe_woocommerce_single_product_carousel_options');