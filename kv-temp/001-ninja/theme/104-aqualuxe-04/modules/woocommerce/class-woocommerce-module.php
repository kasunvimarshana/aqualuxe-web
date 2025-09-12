<?php
/**
 * WooCommerce Module
 * 
 * Handles WooCommerce integration with dual-state architecture
 * 
 * @package AquaLuxe
 */

if (!defined('ABSPATH')) {
    exit;
}

class AquaLuxe_Woocommerce_Module {
    
    /**
     * Constructor
     */
    public function __construct() {
        add_action('init', [$this, 'init']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
        add_action('woocommerce_before_shop_loop', [$this, 'shop_toolbar'], 15);
        add_action('woocommerce_after_shop_loop_item', [$this, 'product_actions'], 15);
        
        // Theme support
        add_action('after_setup_theme', [$this, 'add_theme_support']);
        
        // Customizer options
        add_filter('aqualuxe_customizer_sections', [$this, 'add_customizer_options']);
        
        // Product gallery
        add_action('woocommerce_single_product_summary', [$this, 'product_gallery_thumbnails'], 25);
        
        // Ajax handlers
        add_action('wp_ajax_aqualuxe_add_to_wishlist', [$this, 'ajax_add_to_wishlist']);
        add_action('wp_ajax_nopriv_aqualuxe_add_to_wishlist', [$this, 'ajax_add_to_wishlist']);
        add_action('wp_ajax_aqualuxe_quick_view', [$this, 'ajax_quick_view']);
        add_action('wp_ajax_nopriv_aqualuxe_quick_view', [$this, 'ajax_quick_view']);
        
        // Graceful fallbacks
        add_action('template_redirect', [$this, 'handle_fallbacks']);
    }
    
    /**
     * Initialize module
     */
    public function init() {
        if (aqualuxe_is_woocommerce_active()) {
            // WooCommerce is active - full functionality
            $this->setup_woocommerce_hooks();
        } else {
            // WooCommerce not active - graceful fallbacks
            $this->setup_fallback_functionality();
        }
    }
    
    /**
     * Setup WooCommerce hooks when plugin is active
     */
    private function setup_woocommerce_hooks() {
        // Product display hooks
        add_action('woocommerce_before_shop_loop_item', [$this, 'product_wrapper_start'], 5);
        add_action('woocommerce_after_shop_loop_item', [$this, 'product_wrapper_end'], 25);
        
        // Product actions
        add_action('woocommerce_after_shop_loop_item_title', [$this, 'product_excerpt'], 5);
        add_action('woocommerce_after_shop_loop_item_title', [$this, 'product_attributes'], 15);
        
        // Cart enhancements
        add_filter('woocommerce_add_to_cart_fragments', [$this, 'cart_count_fragment']);
        
        // Checkout enhancements
        add_action('woocommerce_checkout_before_customer_details', [$this, 'checkout_progress_bar']);
        
        // Account enhancements
        add_filter('woocommerce_account_menu_items', [$this, 'custom_account_menu_items']);
        
        // Product page enhancements
        add_action('woocommerce_single_product_summary', [$this, 'product_care_info'], 25);
        add_action('woocommerce_single_product_summary', [$this, 'product_shipping_info'], 35);
    }
    
    /**
     * Setup fallback functionality when WooCommerce is not active
     */
    private function setup_fallback_functionality() {
        // Register shop page even without WooCommerce
        add_action('init', [$this, 'create_fallback_shop_page']);
        
        // Add shop link to navigation
        add_filter('wp_nav_menu_items', [$this, 'add_shop_menu_item'], 10, 2);
        
        // Handle shop page template
        add_filter('template_include', [$this, 'shop_template_fallback']);
        
        // Add basic "products" post type for demo purposes
        add_action('init', [$this, 'register_basic_product_post_type']);
    }
    
    /**
     * Create fallback shop page
     */
    public function create_fallback_shop_page() {
        // Check if shop page exists
        $shop_page = get_page_by_path('shop');
        
        if (!$shop_page) {
            $shop_page_id = wp_insert_post([
                'post_title' => __('Shop', 'aqualuxe'),
                'post_name' => 'shop',
                'post_content' => __('Browse our premium collection of aquatic species and equipment.', 'aqualuxe'),
                'post_status' => 'publish',
                'post_type' => 'page',
                'page_template' => 'page-shop-fallback.php'
            ]);
            
            if ($shop_page_id) {
                update_post_meta($shop_page_id, '_aqualuxe_is_shop_fallback', true);
            }
        }
    }
    
    /**
     * Add shop menu item when WooCommerce is not active
     */
    public function add_shop_menu_item($items, $args) {
        if (!aqualuxe_is_woocommerce_active() && $args->theme_location === 'primary') {
            $shop_url = home_url('/shop');
            $shop_item = '<li class="menu-item"><a href="' . esc_url($shop_url) . '">' . esc_html__('Shop', 'aqualuxe') . '</a></li>';
            
            // Insert shop item after home
            $items = preg_replace('/(<li[^>]*>.*?<\/li>)/', '$1' . $shop_item, $items, 1);
        }
        
        return $items;
    }
    
    /**
     * Handle shop template fallback
     */
    public function shop_template_fallback($template) {
        if (is_page('shop') && !aqualuxe_is_woocommerce_active()) {
            $fallback_template = locate_template(['page-shop-fallback.php']);
            if ($fallback_template) {
                return $fallback_template;
            }
        }
        
        return $template;
    }
    
    /**
     * Register basic product post type for fallback
     */
    public function register_basic_product_post_type() {
        if (aqualuxe_is_woocommerce_active()) {
            return; // Don't register if WooCommerce is active
        }
        
        register_post_type('aqualuxe_product', [
            'labels' => [
                'name' => __('Products', 'aqualuxe'),
                'singular_name' => __('Product', 'aqualuxe'),
                'add_new' => __('Add New Product', 'aqualuxe'),
                'add_new_item' => __('Add New Product', 'aqualuxe'),
                'edit_item' => __('Edit Product', 'aqualuxe'),
                'new_item' => __('New Product', 'aqualuxe'),
                'view_item' => __('View Product', 'aqualuxe'),
                'search_items' => __('Search Products', 'aqualuxe'),
                'not_found' => __('No products found', 'aqualuxe'),
                'not_found_in_trash' => __('No products found in trash', 'aqualuxe')
            ],
            'public' => true,
            'has_archive' => true,
            'supports' => ['title', 'editor', 'excerpt', 'thumbnail', 'custom-fields'],
            'menu_icon' => 'dashicons-store',
            'rewrite' => ['slug' => 'products'],
            'show_in_rest' => true
        ]);
        
        // Register product categories
        register_taxonomy('aqualuxe_product_category', 'aqualuxe_product', [
            'labels' => [
                'name' => __('Product Categories', 'aqualuxe'),
                'singular_name' => __('Product Category', 'aqualuxe')
            ],
            'hierarchical' => true,
            'public' => true,
            'show_in_rest' => true,
            'rewrite' => ['slug' => 'product-category']
        ]);
    }
    
    /**
     * Setup WooCommerce hooks when plugin is active
     */
}
