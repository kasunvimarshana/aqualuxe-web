<?php
/**
 * WooCommerce Integration Service
 *
 * Handles WooCommerce integration with graceful fallbacks
 *
 * @package AquaLuxe\Core\Services
 * @version 1.0.0
 */

namespace AquaLuxe\Core\Services;

use AquaLuxe\Core\Interfaces\ServiceInterface;

/**
 * WooCommerce Integration Class
 */
class WooCommerceIntegration implements ServiceInterface {
    
    /**
     * WooCommerce active status
     *
     * @var bool
     */
    private $is_active = false;
    
    /**
     * Initialize WooCommerce integration
     *
     * @return void
     */
    public function init(): void {
        $this->is_active = class_exists('WooCommerce');
        
        if ($this->is_active) {
            $this->setup_woocommerce_support();
            $this->register_hooks();
            $this->customize_woocommerce();
        } else {
            $this->setup_fallbacks();
        }
    }
    
    /**
     * Get service name
     *
     * @return string
     */
    public function getName(): string {
        return 'WooCommerceIntegration';
    }
    
    /**
     * Setup WooCommerce theme support
     *
     * @return void
     */
    private function setup_woocommerce_support(): void {
        add_theme_support('woocommerce', [
            'thumbnail_image_width' => 350,
            'single_image_width' => 600,
            'product_grid' => [
                'default_rows' => 3,
                'min_rows' => 2,
                'max_rows' => 8,
                'default_columns' => 4,
                'min_columns' => 2,
                'max_columns' => 5,
            ],
        ]);
        
        // Gallery features
        add_theme_support('wc-product-gallery-zoom');
        add_theme_support('wc-product-gallery-lightbox');
        add_theme_support('wc-product-gallery-slider');
    }
    
    /**
     * Register WooCommerce hooks
     *
     * @return void
     */
    private function register_hooks(): void {
        // Remove default WooCommerce styles
        add_filter('woocommerce_enqueue_styles', '__return_empty_array');
        
        // Enqueue custom WooCommerce styles
        add_action('wp_enqueue_scripts', [$this, 'enqueue_woocommerce_styles']);
        
        // Customize WooCommerce templates
        add_action('after_setup_theme', [$this, 'remove_woocommerce_actions']);
        add_action('init', [$this, 'add_custom_woocommerce_actions']);
        
        // Cart fragments for AJAX
        add_filter('woocommerce_add_to_cart_fragments', [$this, 'cart_count_fragments']);
        
        // Customize product loop
        add_action('woocommerce_before_shop_loop_item', [$this, 'product_wrapper_start'], 5);
        add_action('woocommerce_after_shop_loop_item', [$this, 'product_wrapper_end'], 25);
        
        // Add quick view
        add_action('woocommerce_after_shop_loop_item', [$this, 'add_quick_view_button'], 15);
        
        // Customize single product
        add_action('woocommerce_single_product_summary', [$this, 'add_product_meta_custom'], 25);
        
        // Customize checkout
        add_filter('woocommerce_checkout_fields', [$this, 'customize_checkout_fields']);
        
        // Add to cart AJAX
        add_action('wp_ajax_aqualuxe_add_to_cart', [$this, 'ajax_add_to_cart']);
        add_action('wp_ajax_nopriv_aqualuxe_add_to_cart', [$this, 'ajax_add_to_cart']);
        
        // Quick view AJAX
        add_action('wp_ajax_aqualuxe_quick_view', [$this, 'ajax_quick_view']);
        add_action('wp_ajax_nopriv_aqualuxe_quick_view', [$this, 'ajax_quick_view']);
        
        // Customize breadcrumbs
        add_filter('woocommerce_breadcrumb_defaults', [$this, 'custom_breadcrumb_args']);
        
        // Currency support hooks
        add_action('wp_head', [$this, 'add_currency_meta']);
    }
    
    /**
     * Enqueue WooCommerce styles
     *
     * @return void
     */
    public function enqueue_woocommerce_styles(): void {
        if (is_woocommerce() || is_cart() || is_checkout() || is_account_page()) {
            wp_enqueue_style(
                'aqualuxe-woocommerce',
                aqualuxe_asset('/css/woocommerce.css'),
                ['aqualuxe-style'],
                AQUALUXE_VERSION
            );
            
            wp_enqueue_script(
                'aqualuxe-woocommerce',
                aqualuxe_asset('/js/woocommerce.js'),
                ['aqualuxe-script', 'jquery'],
                AQUALUXE_VERSION,
                true
            );
            
            wp_localize_script('aqualuxe-woocommerce', 'aqualuxeWC', [
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('aqualuxe_wc_nonce'),
                'cart_url' => wc_get_cart_url(),
                'checkout_url' => wc_get_checkout_url(),
                'strings' => [
                    'adding_to_cart' => __('Adding to cart...', 'aqualuxe'),
                    'added_to_cart' => __('Added to cart!', 'aqualuxe'),
                    'error' => __('Error adding to cart', 'aqualuxe'),
                    'quick_view' => __('Quick View', 'aqualuxe'),
                ],
            ]);
        }
    }
    
    /**
     * Remove default WooCommerce actions
     *
     * @return void
     */
    public function remove_woocommerce_actions(): void {
        // Remove breadcrumbs (we'll add our own)
        remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);
        
        // Remove result count and ordering
        remove_action('woocommerce_before_shop_loop', 'woocommerce_output_all_notices', 10);
        remove_action('woocommerce_before_shop_loop', 'woocommerce_result_count', 20);
        remove_action('woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30);
        
        // Remove single product hooks we'll customize
        remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40);
    }
    
    /**
     * Add custom WooCommerce actions
     *
     * @return void
     */
    public function add_custom_woocommerce_actions(): void {
        // Add custom shop header
        add_action('woocommerce_before_shop_loop', [$this, 'shop_header'], 5);
        
        // Add custom product navigation
        add_action('woocommerce_before_single_product_summary', [$this, 'product_navigation'], 5);
    }
    
    /**
     * Cart count fragments for AJAX
     *
     * @param array $fragments
     * @return array
     */
    public function cart_count_fragments($fragments): array {
        $fragments['.cart-count'] = '<span class="cart-count">' . WC()->cart->get_cart_contents_count() . '</span>';
        $fragments['.cart-total'] = '<span class="cart-total">' . WC()->cart->get_cart_total() . '</span>';
        
        return $fragments;
    }
    
    /**
     * Product wrapper start
     *
     * @return void
     */
    public function product_wrapper_start(): void {
        echo '<div class="product-wrapper relative group">';
    }
    
    /**
     * Product wrapper end
     *
     * @return void
     */
    public function product_wrapper_end(): void {
        echo '</div>';
    }
    
    /**
     * Add quick view button
     *
     * @return void
     */
    public function add_quick_view_button(): void {
        global $product;
        
        echo '<div class="product-actions absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">';
        echo '<button class="quick-view-btn btn btn-primary mr-2" data-product-id="' . esc_attr($product->get_id()) . '">';
        echo __('Quick View', 'aqualuxe');
        echo '</button>';
        echo '</div>';
    }
    
    /**
     * Add custom product meta
     *
     * @return void
     */
    public function add_product_meta_custom(): void {
        global $product;
        
        echo '<div class="product-meta-custom mt-6 pt-6 border-t border-gray-200">';
        
        // SKU
        if (wc_product_sku_enabled() && ($product->get_sku() || $product->is_type('variable'))) {
            echo '<div class="sku-wrapper mb-2">';
            echo '<span class="font-medium">' . __('SKU:', 'aqualuxe') . ' </span>';
            echo '<span class="sku">' . (($sku = $product->get_sku()) ? $sku : __('N/A', 'aqualuxe')) . '</span>';
            echo '</div>';
        }
        
        // Categories
        if ($product->get_categories()) {
            echo '<div class="categories-wrapper mb-2">';
            echo '<span class="font-medium">' . __('Categories:', 'aqualuxe') . ' </span>';
            echo $product->get_categories(', ', '<span class="category">', '</span>');
            echo '</div>';
        }
        
        // Tags
        if ($product->get_tags()) {
            echo '<div class="tags-wrapper mb-2">';
            echo '<span class="font-medium">' . __('Tags:', 'aqualuxe') . ' </span>';
            echo $product->get_tags(', ', '<span class="tag">', '</span>');
            echo '</div>';
        }
        
        echo '</div>';
    }
    
    /**
     * Customize checkout fields
     *
     * @param array $fields
     * @return array
     */
    public function customize_checkout_fields($fields): array {
        // Add custom CSS classes
        foreach ($fields['billing'] as $key => $field) {
            $fields['billing'][$key]['class'][] = 'form-field';
            $fields['billing'][$key]['input_class'][] = 'form-input';
        }
        
        foreach ($fields['shipping'] as $key => $field) {
            $fields['shipping'][$key]['class'][] = 'form-field';
            $fields['shipping'][$key]['input_class'][] = 'form-input';
        }
        
        return $fields;
    }
    
    /**
     * AJAX add to cart
     *
     * @return void
     */
    public function ajax_add_to_cart(): void {
        if (!wp_verify_nonce($_POST['nonce'] ?? '', 'aqualuxe_wc_nonce')) {
            wp_die(__('Security check failed', 'aqualuxe'));
        }
        
        $product_id = intval($_POST['product_id'] ?? 0);
        $quantity = intval($_POST['quantity'] ?? 1);
        $variation_id = intval($_POST['variation_id'] ?? 0);
        
        if (!$product_id) {
            wp_send_json_error(__('Invalid product', 'aqualuxe'));
        }
        
        $result = WC()->cart->add_to_cart($product_id, $quantity, $variation_id);
        
        if ($result) {
            wp_send_json_success([
                'message' => __('Product added to cart', 'aqualuxe'),
                'cart_count' => WC()->cart->get_cart_contents_count(),
                'cart_total' => WC()->cart->get_cart_total(),
            ]);
        } else {
            wp_send_json_error(__('Failed to add product to cart', 'aqualuxe'));
        }
    }
    
    /**
     * AJAX quick view
     *
     * @return void
     */
    public function ajax_quick_view(): void {
        if (!wp_verify_nonce($_POST['nonce'] ?? '', 'aqualuxe_wc_nonce')) {
            wp_die(__('Security check failed', 'aqualuxe'));
        }
        
        $product_id = intval($_POST['product_id'] ?? 0);
        
        if (!$product_id) {
            wp_send_json_error(__('Invalid product', 'aqualuxe'));
        }
        
        global $product;
        $product = wc_get_product($product_id);
        
        if (!$product) {
            wp_send_json_error(__('Product not found', 'aqualuxe'));
        }
        
        ob_start();
        wc_get_template_part('quick-view/quick-view-content');
        $html = ob_get_clean();
        
        wp_send_json_success(['html' => $html]);
    }
    
    /**
     * Shop header
     *
     * @return void
     */
    public function shop_header(): void {
        echo '<div class="shop-header mb-8">';
        echo '<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">';
        
        // Shop title and description
        echo '<div class="shop-info">';
        if (is_shop()) {
            echo '<h1 class="shop-title text-3xl font-bold mb-2">' . woocommerce_page_title(false) . '</h1>';
            $shop_page = get_post(wc_get_page_id('shop'));
            if ($shop_page && $shop_page->post_excerpt) {
                echo '<p class="shop-description text-gray-600">' . $shop_page->post_excerpt . '</p>';
            }
        }
        echo '</div>';
        
        // Shop controls
        echo '<div class="shop-controls flex items-center gap-4">';
        woocommerce_result_count();
        woocommerce_catalog_ordering();
        echo '</div>';
        
        echo '</div>';
        echo '</div>';
    }
    
    /**
     * Product navigation
     *
     * @return void
     */
    public function product_navigation(): void {
        $previous = get_previous_post(true, '', 'product_cat');
        $next = get_next_post(true, '', 'product_cat');
        
        if ($previous || $next) {
            echo '<div class="product-navigation mb-6">';
            echo '<div class="flex justify-between items-center">';
            
            if ($previous) {
                echo '<a href="' . get_permalink($previous->ID) . '" class="prev-product flex items-center text-gray-600 hover:text-primary-600">';
                echo '<svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>';
                echo '<span>' . __('Previous Product', 'aqualuxe') . '</span>';
                echo '</a>';
            } else {
                echo '<div></div>';
            }
            
            if ($next) {
                echo '<a href="' . get_permalink($next->ID) . '" class="next-product flex items-center text-gray-600 hover:text-primary-600">';
                echo '<span>' . __('Next Product', 'aqualuxe') . '</span>';
                echo '<svg class="w-4 h-4 ml-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>';
                echo '</a>';
            }
            
            echo '</div>';
            echo '</div>';
        }
    }
    
    /**
     * Custom breadcrumb args
     *
     * @param array $args
     * @return array
     */
    public function custom_breadcrumb_args($args): array {
        $args['wrap_before'] = '<nav class="woocommerce-breadcrumb breadcrumb mb-6" aria-label="breadcrumb"><ol class="flex items-center space-x-2 text-sm text-gray-600">';
        $args['wrap_after'] = '</ol></nav>';
        $args['before'] = '<li class="breadcrumb-item">';
        $args['after'] = '</li>';
        $args['delimiter'] = '<li class="breadcrumb-separator mx-2">/</li>';
        
        return $args;
    }
    
    /**
     * Add currency meta for international support
     *
     * @return void
     */
    public function add_currency_meta(): void {
        if (is_woocommerce()) {
            $currency = get_woocommerce_currency();
            echo '<meta name="currency" content="' . esc_attr($currency) . '">' . "\n";
            echo '<meta name="currency-symbol" content="' . esc_attr(get_woocommerce_currency_symbol($currency)) . '">' . "\n";
        }
    }
    
    /**
     * Setup fallbacks when WooCommerce is not active
     *
     * @return void
     */
    private function setup_fallbacks(): void {
        // Add body class to indicate WooCommerce is not available
        add_filter('body_class', function($classes) {
            $classes[] = 'no-woocommerce';
            return $classes;
        });
        
        // Provide fallback shop functionality
        add_action('wp_enqueue_scripts', [$this, 'enqueue_fallback_styles']);
        
        // Create fallback shop pages
        add_action('init', [$this, 'create_fallback_shop_pages']);
        
        // Add admin notice
        add_action('admin_notices', [$this, 'woocommerce_missing_notice']);
    }
    
    /**
     * Enqueue fallback styles
     *
     * @return void
     */
    public function enqueue_fallback_styles(): void {
        wp_enqueue_style(
            'aqualuxe-shop-fallback',
            aqualuxe_asset('/css/shop-fallback.css'),
            ['aqualuxe-style'],
            AQUALUXE_VERSION
        );
    }
    
    /**
     * Create fallback shop pages
     *
     * @return void
     */
    public function create_fallback_shop_pages(): void {
        // This would create basic shop-like functionality without WooCommerce
        // Using custom post types and basic cart simulation
    }
    
    /**
     * WooCommerce missing notice
     *
     * @return void
     */
    public function woocommerce_missing_notice(): void {
        if (current_user_can('manage_options')) {
            echo '<div class="notice notice-warning is-dismissible">';
            echo '<p>' . sprintf(
                __('AquaLuxe theme is designed to work with WooCommerce. Please install and activate %sWooCommerce%s for full e-commerce functionality.', 'aqualuxe'),
                '<a href="' . admin_url('plugin-install.php?s=woocommerce&tab=search&type=term') . '">',
                '</a>'
            ) . '</p>';
            echo '</div>';
        }
    }
    
    /**
     * Check if WooCommerce is active
     *
     * @return bool
     */
    public function is_woocommerce_active(): bool {
        return $this->is_active;
    }
}