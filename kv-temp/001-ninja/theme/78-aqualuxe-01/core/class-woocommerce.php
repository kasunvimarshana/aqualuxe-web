<?php
/**
 * WooCommerce integration
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class AquaLuxe_WooCommerce {
    
    /**
     * Constructor
     */
    public function __construct() {
        // Only load if WooCommerce is active
        if (!class_exists('WooCommerce')) {
            return;
        }
        
        add_action('after_setup_theme', [$this, 'setup']);
        add_action('wp_enqueue_scripts', [$this, 'woocommerce_scripts']);
        add_filter('woocommerce_enqueue_styles', '__return_empty_array');
        
        // Template hooks
        $this->setup_template_hooks();
        
        // Product customizations
        add_filter('woocommerce_product_tabs', [$this, 'custom_product_tabs']);
        add_action('woocommerce_single_product_summary', [$this, 'add_product_features'], 25);
        
        // Shop customizations
        add_filter('woocommerce_show_page_title', '__return_false');
        add_filter('woocommerce_get_image_size_gallery_thumbnail', [$this, 'gallery_thumbnail_size']);
        
        // Cart and checkout
        add_filter('woocommerce_cross_sells_columns', [$this, 'cross_sells_columns']);
        add_filter('woocommerce_output_related_products_args', [$this, 'related_products_args']);
        
        // Ajax functionality
        add_action('wp_ajax_aqualuxe_quick_view', [$this, 'ajax_quick_view']);
        add_action('wp_ajax_nopriv_aqualuxe_quick_view', [$this, 'ajax_quick_view']);
        
        // Graceful fallbacks for when WooCommerce is disabled
        add_action('init', [$this, 'register_fallback_shortcodes']);
    }
    
    /**
     * Setup WooCommerce support
     */
    public function setup() {
        add_theme_support('woocommerce', [
            'thumbnail_image_width' => 300,
            'gallery_thumbnail_image_width' => 100,
            'single_image_width' => 600,
        ]);
        
        add_theme_support('wc-product-gallery-zoom');
        add_theme_support('wc-product-gallery-lightbox');
        add_theme_support('wc-product-gallery-slider');
    }
    
    /**
     * Enqueue WooCommerce specific scripts
     */
    public function woocommerce_scripts() {
        if (is_woocommerce() || is_cart() || is_checkout() || is_account_page()) {
            wp_enqueue_script(
                'aqualuxe-woocommerce',
                AQUALUXE_ASSETS_URI . '/dist/js/woocommerce.js',
                ['jquery', 'wc-add-to-cart-variation'],
                AQUALUXE_VERSION,
                true
            );
            
            wp_localize_script('aqualuxe-woocommerce', 'aqualuxe_wc', [
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('aqualuxe_wc_nonce'),
                'strings' => [
                    'quick_view' => esc_html__('Quick View', 'aqualuxe'),
                    'add_to_cart' => esc_html__('Add to Cart', 'aqualuxe'),
                    'select_options' => esc_html__('Select Options', 'aqualuxe'),
                    'out_of_stock' => esc_html__('Out of Stock', 'aqualuxe'),
                ],
            ]);
        }
    }
    
    /**
     * Setup template hooks
     */
    private function setup_template_hooks() {
        // Remove default WooCommerce actions
        remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
        remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);
        remove_action('woocommerce_sidebar', 'woocommerce_get_sidebar', 10);
        
        // Add custom wrapper
        add_action('woocommerce_before_main_content', [$this, 'wrapper_start'], 10);
        add_action('woocommerce_after_main_content', [$this, 'wrapper_end'], 10);
        
        // Product loop modifications
        add_action('woocommerce_before_shop_loop_item', [$this, 'product_loop_start'], 1);
        add_action('woocommerce_after_shop_loop_item', [$this, 'product_loop_end'], 100);
        add_action('woocommerce_after_shop_loop_item_title', [$this, 'add_product_excerpt'], 5);
        
        // Add quick view button
        add_action('woocommerce_after_shop_loop_item', [$this, 'add_quick_view_button'], 15);
        
        // Single product modifications
        remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40);
        add_action('woocommerce_single_product_summary', [$this, 'custom_single_meta'], 40);
        
        // Breadcrumbs
        remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);
        add_action('aqualuxe_before_content', 'woocommerce_breadcrumb', 10);
    }
    
    /**
     * Content wrapper start
     */
    public function wrapper_start() {
        echo '<div class="aqualuxe-woocommerce-wrapper">';
        echo '<div class="container mx-auto px-4">';
        echo '<div class="flex flex-wrap -mx-4">';
        echo '<main class="w-full lg:w-3/4 px-4">';
    }
    
    /**
     * Content wrapper end
     */
    public function wrapper_end() {
        echo '</main>';
        
        // Add sidebar for shop pages
        if (is_shop() || is_product_category() || is_product_tag()) {
            echo '<aside class="w-full lg:w-1/4 px-4">';
            if (is_active_sidebar('sidebar-shop')) {
                dynamic_sidebar('sidebar-shop');
            }
            echo '</aside>';
        }
        
        echo '</div>'; // flex wrapper
        echo '</div>'; // container
        echo '</div>'; // main wrapper
    }
    
    /**
     * Product loop start
     */
    public function product_loop_start() {
        echo '<div class="product-loop-wrapper relative group">';
    }
    
    /**
     * Product loop end
     */
    public function product_loop_end() {
        echo '</div>';
    }
    
    /**
     * Add product excerpt to shop loop
     */
    public function add_product_excerpt() {
        global $product;
        
        $excerpt = $product->get_short_description();
        if ($excerpt) {
            echo '<div class="product-excerpt text-sm text-gray-600 mb-2">';
            echo wp_trim_words($excerpt, 15);
            echo '</div>';
        }
    }
    
    /**
     * Add quick view button
     */
    public function add_quick_view_button() {
        global $product;
        
        echo '<button class="quick-view-btn absolute top-4 right-4 bg-white rounded-full p-2 shadow-md opacity-0 group-hover:opacity-100 transition-opacity" data-product-id="' . esc_attr($product->get_id()) . '">';
        echo '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">';
        echo '<path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>';
        echo '<path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path>';
        echo '</svg>';
        echo '</button>';
    }
    
    /**
     * Custom single product meta
     */
    public function custom_single_meta() {
        global $product;
        
        echo '<div class="product-meta-custom mt-6 p-4 bg-gray-50 rounded-lg">';
        
        // SKU
        if (function_exists('wc_product_sku_enabled') && wc_product_sku_enabled() && ($product->get_sku() || $product->is_type('variable'))) {
            echo '<div class="sku_wrapper mb-2">';
            echo '<span class="font-semibold">' . esc_html__('SKU:', 'aqualuxe') . '</span> ';
            echo '<span class="sku">' . (($sku = $product->get_sku()) ? $sku : esc_html__('N/A', 'aqualuxe')) . '</span>';
            echo '</div>';
        }
        
        // Categories
        if (function_exists('wc_get_product_category_list')) {
            echo wc_get_product_category_list($product->get_id(), ', ', '<div class="posted_in mb-2"><span class="font-semibold">' . _n('Category:', 'Categories:', count($product->get_category_ids()), 'aqualuxe') . '</span> ', '</div>');
        }
        
        // Tags
        if (function_exists('wc_get_product_tag_list')) {
            echo wc_get_product_tag_list($product->get_id(), ', ', '<div class="tagged_as mb-2"><span class="font-semibold">' . _n('Tag:', 'Tags:', count($product->get_tag_ids()), 'aqualuxe') . '</span> ', '</div>');
        }
        
        echo '</div>';
    }
    
    /**
     * Add product features
     */
    public function add_product_features() {
        global $product;
        
        $features = get_post_meta($product->get_id(), '_product_features', true);
        
        if ($features && is_array($features)) {
            echo '<div class="product-features mt-4">';
            echo '<h4 class="text-lg font-semibold mb-2">' . esc_html__('Features', 'aqualuxe') . '</h4>';
            echo '<ul class="list-disc list-inside space-y-1">';
            
            foreach ($features as $feature) {
                echo '<li>' . esc_html($feature) . '</li>';
            }
            
            echo '</ul>';
            echo '</div>';
        }
    }
    
    /**
     * Custom product tabs
     */
    public function custom_product_tabs($tabs) {
        global $product;
        
        // Add care instructions tab for fish products
        if (has_term('fish', 'product_cat', $product->get_id())) {
            $tabs['care_instructions'] = [
                'title' => esc_html__('Care Instructions', 'aqualuxe'),
                'priority' => 25,
                'callback' => [$this, 'care_instructions_tab_content'],
            ];
        }
        
        // Add compatibility tab for equipment
        if (has_term('equipment', 'product_cat', $product->get_id())) {
            $tabs['compatibility'] = [
                'title' => esc_html__('Compatibility', 'aqualuxe'),
                'priority' => 30,
                'callback' => [$this, 'compatibility_tab_content'],
            ];
        }
        
        return $tabs;
    }
    
    /**
     * Care instructions tab content
     */
    public function care_instructions_tab_content() {
        global $product;
        
        $care_instructions = get_post_meta($product->get_id(), '_care_instructions', true);
        
        if ($care_instructions) {
            echo '<h3>' . esc_html__('Care Instructions', 'aqualuxe') . '</h3>';
            echo wpautop($care_instructions);
        } else {
            echo '<p>' . esc_html__('Care instructions will be provided with your purchase.', 'aqualuxe') . '</p>';
        }
    }
    
    /**
     * Compatibility tab content
     */
    public function compatibility_tab_content() {
        global $product;
        
        $compatibility = get_post_meta($product->get_id(), '_compatibility_info', true);
        
        if ($compatibility) {
            echo '<h3>' . esc_html__('Compatibility Information', 'aqualuxe') . '</h3>';
            echo wpautop($compatibility);
        } else {
            echo '<p>' . esc_html__('Please contact us for compatibility information.', 'aqualuxe') . '</p>';
        }
    }
    
    /**
     * Gallery thumbnail size
     */
    public function gallery_thumbnail_size($size) {
        return [
            'width' => 100,
            'height' => 100,
            'crop' => 1,
        ];
    }
    
    /**
     * Cross sells columns
     */
    public function cross_sells_columns() {
        return 3;
    }
    
    /**
     * Related products args
     */
    public function related_products_args($args) {
        $args['posts_per_page'] = 4;
        $args['columns'] = 4;
        return $args;
    }
    
    /**
     * Ajax quick view
     */
    public function ajax_quick_view() {
        check_ajax_referer('aqualuxe_wc_nonce', 'nonce');
        
        $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
        
        if (!$product_id) {
            wp_die();
        }
        
        global $post, $product;
        
        $post = get_post($product_id);
        
        if (function_exists('wc_get_product')) {
            $product = wc_get_product($product_id);
        } else {
            wp_die();
        }
        
        if (!$product) {
            wp_die();
        }
        
        if (function_exists('wc_get_template')) {
            wc_get_template('quick-view/quick-view.php', [
                'product' => $product,
            ], '', AQUALUXE_THEME_DIR . '/woocommerce/');
        }
        
        wp_die();
    }
    
    /**
     * Register fallback shortcodes for when WooCommerce is disabled
     */
    public function register_fallback_shortcodes() {
        if (!class_exists('WooCommerce')) {
            add_shortcode('products', [$this, 'fallback_products_shortcode']);
            add_shortcode('featured_products', [$this, 'fallback_featured_products_shortcode']);
            add_shortcode('product_categories', [$this, 'fallback_product_categories_shortcode']);
        }
    }
    
    /**
     * Fallback products shortcode
     */
    public function fallback_products_shortcode($atts) {
        $atts = shortcode_atts([
            'limit' => 4,
            'category' => '',
        ], $atts);
        
        ob_start();
        
        echo '<div class="fallback-products-notice p-4 bg-blue-50 border border-blue-200 rounded-lg">';
        echo '<h3 class="text-lg font-semibold text-blue-800 mb-2">' . esc_html__('Shop Coming Soon', 'aqualuxe') . '</h3>';
        echo '<p class="text-blue-700">' . esc_html__('Our online shop is currently being set up. Please contact us directly for product inquiries and purchases.', 'aqualuxe') . '</p>';
        echo '<a href="/contact" class="inline-block mt-3 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors">' . esc_html__('Contact Us', 'aqualuxe') . '</a>';
        echo '</div>';
        
        return ob_get_clean();
    }
    
    /**
     * Fallback featured products shortcode
     */
    public function fallback_featured_products_shortcode($atts) {
        return $this->fallback_products_shortcode($atts);
    }
    
    /**
     * Fallback product categories shortcode
     */
    public function fallback_product_categories_shortcode($atts) {
        ob_start();
        
        echo '<div class="fallback-categories grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">';
        
        $categories = [
            ['name' => 'Fish', 'description' => 'Premium freshwater and marine fish'],
            ['name' => 'Plants', 'description' => 'Beautiful aquatic plants for your aquascape'],
            ['name' => 'Equipment', 'description' => 'Professional aquarium equipment'],
            ['name' => 'Supplies', 'description' => 'Food, chemicals, and accessories'],
        ];
        
        foreach ($categories as $category) {
            echo '<div class="category-item bg-white border border-gray-200 rounded-lg p-6 text-center hover:shadow-md transition-shadow">';
            echo '<h3 class="text-lg font-semibold mb-2">' . esc_html($category['name']) . '</h3>';
            echo '<p class="text-gray-600 mb-4">' . esc_html($category['description']) . '</p>';
            echo '<a href="/contact" class="inline-block px-4 py-2 bg-aqua-500 text-white rounded hover:bg-aqua-600 transition-colors">' . esc_html__('Learn More', 'aqualuxe') . '</a>';
            echo '</div>';
        }
        
        echo '</div>';
        
        return ob_get_clean();
    }
}

// Initialize
new AquaLuxe_WooCommerce();
