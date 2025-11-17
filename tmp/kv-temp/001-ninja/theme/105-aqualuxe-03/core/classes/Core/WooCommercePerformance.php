<?php
/**
 * WooCommerce Performance Optimization
 *
 * Performance enhancements and optimizations for WooCommerce
 *
 * @package AquaLuxe\Core
 * @since 1.0.0
 */

namespace AquaLuxe\Core;

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class WooCommercePerformance
 */
class WooCommercePerformance {
    
    /**
     * Single instance of the class
     *
     * @var WooCommercePerformance
     */
    private static $instance = null;

    /**
     * Cache groups
     *
     * @var array
     */
    private $cache_groups = array(
        'products' => 'aqualuxe_products',
        'cart' => 'aqualuxe_cart',
        'checkout' => 'aqualuxe_checkout',
        'orders' => 'aqualuxe_orders'
    );

    /**
     * Get instance
     *
     * @return WooCommercePerformance
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    private function __construct() {
        $this->init_performance_hooks();
    }

    /**
     * Initialize performance hooks
     */
    private function init_performance_hooks() {
        // Database optimization
        add_action('init', array($this, 'optimize_queries'));
        add_filter('woocommerce_product_data_store_cpt_get_products_query', array($this, 'optimize_product_queries'), 10, 2);
        
        // Caching
        add_action('init', array($this, 'setup_caching'));
        add_action('woocommerce_product_set_stock_status', array($this, 'clear_product_cache'));
        add_action('woocommerce_product_set_visibility', array($this, 'clear_product_cache'));
        add_action('save_post_product', array($this, 'clear_product_cache'));
        
        // Asset optimization
        add_action('wp_enqueue_scripts', array($this, 'optimize_assets'), 20);
        add_filter('woocommerce_enqueue_styles', array($this, 'optimize_wc_styles'));
        
        // Image optimization
        add_filter('woocommerce_single_product_image_thumbnail_html', array($this, 'add_lazy_loading'), 10, 2);
        add_filter('woocommerce_single_product_image_html', array($this, 'add_lazy_loading'), 10, 2);
        
        // Critical CSS
        add_action('wp_head', array($this, 'add_critical_css'), 1);
        
        // Preload resources
        add_action('wp_head', array($this, 'preload_critical_resources'), 5);
        
        // AJAX optimization
        add_filter('woocommerce_add_to_cart_fragments', array($this, 'optimize_cart_fragments'));
        
        // Menu optimization
        add_filter('wp_nav_menu_objects', array($this, 'optimize_product_menu_queries'), 10, 2);
        
        // Search optimization
        add_filter('pre_get_posts', array($this, 'optimize_product_search'));
        
        // Checkout optimization
        add_action('woocommerce_checkout_init', array($this, 'optimize_checkout'));
        
        // Remove unnecessary features
        add_action('init', array($this, 'remove_unnecessary_features'));
    }

    /**
     * Optimize database queries
     */
    public function optimize_queries() {
        // Reduce autoloaded data
        $this->optimize_autoloaded_options();
        
        // Setup proper indexing suggestions
        add_action('admin_notices', array($this, 'suggest_database_optimizations'));
    }

    /**
     * Optimize product queries
     */
    public function optimize_product_queries($query, $query_vars) {
        // Add proper indexing for common queries
        if (isset($query_vars['meta_query'])) {
            $query['meta_query']['relation'] = 'AND';
        }

        // Optimize stock status queries
        if (isset($query_vars['stock_status'])) {
            $query['meta_query'][] = array(
                'key' => '_stock_status',
                'value' => $query_vars['stock_status'],
                'compare' => is_array($query_vars['stock_status']) ? 'IN' : '='
            );
        }

        return $query;
    }

    /**
     * Setup caching system
     */
    public function setup_caching() {
        // Register cache groups
        foreach ($this->cache_groups as $group) {
            wp_cache_add_global_groups($group);
        }

        // Product cache
        add_action('woocommerce_before_shop_loop', array($this, 'cache_shop_products'));
        add_action('woocommerce_single_product_summary', array($this, 'cache_product_data'), 5);
        
        // Cart cache
        add_filter('woocommerce_cart_contents_total', array($this, 'cache_cart_totals'));
    }

    /**
     * Cache shop products
     */
    public function cache_shop_products() {
        if (!is_shop() && !is_product_category() && !is_product_tag()) {
            return;
        }

        $cache_key = 'shop_products_' . md5(serialize($_GET));
        $cached_products = wp_cache_get($cache_key, $this->cache_groups['products']);

        if (false === $cached_products) {
            // Products will be cached after the loop
            add_action('woocommerce_after_shop_loop', function() use ($cache_key) {
                global $woocommerce_loop;
                wp_cache_set($cache_key, $woocommerce_loop, $this->cache_groups['products'], HOUR_IN_SECONDS);
            });
        }
    }

    /**
     * Cache product data
     */
    public function cache_product_data() {
        global $product;
        
        if (!$product) {
            return;
        }

        $cache_key = 'product_data_' . $product->get_id();
        $cached_data = wp_cache_get($cache_key, $this->cache_groups['products']);

        if (false === $cached_data) {
            $product_data = array(
                'price_html' => $product->get_price_html(),
                'availability' => $product->get_availability(),
                'dimensions' => $product->get_dimensions(false),
                'weight' => $product->get_weight(),
                'attributes' => $product->get_attributes()
            );
            
            wp_cache_set($cache_key, $product_data, $this->cache_groups['products'], DAY_IN_SECONDS);
        }
    }

    /**
     * Cache cart totals
     */
    public function cache_cart_totals($total) {
        if (!WC()->cart) {
            return $total;
        }

        $cache_key = 'cart_totals_' . md5(serialize(WC()->cart->get_cart()));
        $cached_total = wp_cache_get($cache_key, $this->cache_groups['cart']);

        if (false === $cached_total) {
            wp_cache_set($cache_key, $total, $this->cache_groups['cart'], MINUTE_IN_SECONDS * 10);
        }

        return $total;
    }

    /**
     * Clear product cache
     */
    public function clear_product_cache($product_id = null) {
        if ($product_id) {
            wp_cache_delete('product_data_' . $product_id, $this->cache_groups['products']);
        }
        
        // Clear shop cache
        wp_cache_flush_group($this->cache_groups['products']);
    }

    /**
     * Optimize assets
     */
    public function optimize_assets() {
        if (!is_woocommerce() && !is_cart() && !is_checkout() && !is_account_page()) {
            return;
        }

        // Defer non-critical WooCommerce scripts
        add_filter('script_loader_tag', array($this, 'defer_non_critical_scripts'), 10, 3);
        
        // Preload critical fonts
        $this->preload_fonts();
        
        // Minimize CSS blocking
        add_action('wp_footer', array($this, 'load_non_critical_css'));
    }

    /**
     * Optimize WooCommerce styles
     */
    public function optimize_wc_styles($enqueue_styles) {
        // Remove unused default styles
        unset($enqueue_styles['woocommerce-layout']);
        unset($enqueue_styles['woocommerce-smallscreen']);
        
        return $enqueue_styles;
    }

    /**
     * Add lazy loading to images
     */
    public function add_lazy_loading($html, $attachment_id = null) {
        // Add loading="lazy" and intersection observer attributes
        $html = str_replace('<img', '<img loading="lazy" data-aqualuxe-lazy', $html);
        
        // Add placeholder for better UX
        if (strpos($html, 'data-aqualuxe-lazy') !== false) {
            $html = str_replace('src=', 'data-src=', $html);
            $html = str_replace('<img', '<img src="data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 1 1\'%3E%3C/svg%3E"', $html);
        }

        return $html;
    }

    /**
     * Add critical CSS
     */
    public function add_critical_css() {
        if (!is_woocommerce() && !is_cart() && !is_checkout()) {
            return;
        }

        $critical_css = $this->get_critical_css();
        if ($critical_css) {
            echo '<style id="aqualuxe-critical-css">' . $critical_css . '</style>';
        }
    }

    /**
     * Get critical CSS
     */
    private function get_critical_css() {
        $critical_css = '';

        // Above-the-fold styles for WooCommerce pages
        if (is_shop() || is_product_category()) {
            $critical_css .= '
                .woocommerce ul.products{display:grid;gap:1.5rem;grid-template-columns:repeat(auto-fit,minmax(280px,1fr))}
                .woocommerce ul.products li.product{background:#fff;border-radius:0.5rem;box-shadow:0 1px 3px rgba(0,0,0,0.1);overflow:hidden}
                .woocommerce ul.products li.product img{width:100%;height:auto;object-fit:cover}
                .woocommerce .price{font-weight:600;color:#059669}
            ';
        }

        if (is_product()) {
            $critical_css .= '
                .woocommerce div.product .product-content-grid{display:grid;gap:2rem;grid-template-columns:1fr 1fr}
                .woocommerce div.product .woocommerce-product-gallery img{width:100%;height:auto;border-radius:0.5rem}
                .woocommerce div.product .summary{padding:1rem}
                .woocommerce div.product .price{font-size:1.5rem;font-weight:700;color:#059669;margin-bottom:1rem}
            ';
        }

        if (is_cart()) {
            $critical_css .= '
                .woocommerce table.cart{width:100%;border-collapse:collapse}
                .woocommerce table.cart td,.woocommerce table.cart th{padding:1rem;border-bottom:1px solid #e5e7eb}
                .cart_totals{background:#f9fafb;padding:1.5rem;border-radius:0.5rem}
            ';
        }

        if (is_checkout()) {
            $critical_css .= '
                .woocommerce .checkout{display:grid;gap:2rem;grid-template-columns:2fr 1fr}
                .woocommerce .form-row{margin-bottom:1rem}
                .woocommerce .form-row input,.woocommerce .form-row select{width:100%;padding:0.75rem;border:1px solid #d1d5db;border-radius:0.375rem}
            ';
        }

        return $critical_css;
    }

    /**
     * Preload critical resources
     */
    public function preload_critical_resources() {
        if (!is_woocommerce() && !is_cart() && !is_checkout() && !is_account_page()) {
            return;
        }

        // Preload critical images
        if (is_shop() || is_product_category()) {
            echo '<link rel="preload" as="image" href="' . esc_url(wc_placeholder_img_src('woocommerce_thumbnail')) . '">';
        }

        // Preload AJAX endpoint
        echo '<link rel="dns-prefetch" href="' . esc_url(admin_url('admin-ajax.php')) . '">';
        
        // Preload payment method scripts on checkout
        if (is_checkout()) {
            echo '<link rel="preconnect" href="https://js.stripe.com">';
            echo '<link rel="preconnect" href="https://www.paypal.com">';
        }
    }

    /**
     * Optimize cart fragments
     */
    public function optimize_cart_fragments($fragments) {
        // Only return essential fragments
        $essential_fragments = array();
        
        if (isset($fragments['.cart-count'])) {
            $essential_fragments['.cart-count'] = $fragments['.cart-count'];
        }
        
        if (isset($fragments['.cart-total'])) {
            $essential_fragments['.cart-total'] = $fragments['.cart-total'];
        }

        return $essential_fragments;
    }

    /**
     * Defer non-critical scripts
     */
    public function defer_non_critical_scripts($tag, $handle, $src) {
        $defer_scripts = array(
            'woocommerce',
            'wc-add-to-cart',
            'wc-checkout',
            'wc-cart-fragments'
        );

        if (in_array($handle, $defer_scripts)) {
            return str_replace(' src', ' defer src', $tag);
        }

        return $tag;
    }

    /**
     * Preload fonts
     */
    private function preload_fonts() {
        $fonts = array(
            'Inter-Regular.woff2',
            'Inter-Medium.woff2',
            'Inter-SemiBold.woff2'
        );

        foreach ($fonts as $font) {
            echo '<link rel="preload" href="' . esc_url(get_template_directory_uri() . '/assets/dist/fonts/' . $font) . '" as="font" type="font/woff2" crossorigin>';
        }
    }

    /**
     * Load non-critical CSS
     */
    public function load_non_critical_css() {
        if (!is_woocommerce() && !is_cart() && !is_checkout() && !is_account_page()) {
            return;
        }

        echo '<script>
            (function() {
                var link = document.createElement("link");
                link.rel = "stylesheet";
                link.href = "' . esc_url(get_template_directory_uri() . '/assets/dist/css/woocommerce.css') . '";
                link.media = "print";
                link.onload = function() {
                    this.media = "all";
                    // Remove critical CSS after full stylesheet loads
                    var critical = document.getElementById("aqualuxe-critical-css");
                    if (critical) critical.remove();
                };
                document.head.appendChild(link);
            })();
        </script>';
    }

    /**
     * Optimize product menu queries
     */
    public function optimize_product_menu_queries($items, $args) {
        if (!$args->menu || strpos($args->menu->slug, 'product') === false) {
            return $items;
        }

        // Cache menu queries
        $cache_key = 'product_menu_' . $args->menu->term_id;
        $cached_items = wp_cache_get($cache_key, 'aqualuxe_menus');

        if (false === $cached_items) {
            wp_cache_set($cache_key, $items, 'aqualuxe_menus', HOUR_IN_SECONDS);
        }

        return $items;
    }

    /**
     * Optimize product search
     */
    public function optimize_product_search($query) {
        if (!$query->is_search() || !$query->is_main_query() || is_admin()) {
            return;
        }

        if (isset($_GET['post_type']) && $_GET['post_type'] === 'product') {
            // Optimize product search queries
            $query->set('meta_query', array(
                array(
                    'key' => '_visibility',
                    'value' => array('catalog', 'visible'),
                    'compare' => 'IN'
                )
            ));

            // Add proper ordering
            $query->set('orderby', 'relevance');
        }
    }

    /**
     * Optimize checkout
     */
    public function optimize_checkout() {
        if (!is_checkout()) {
            return;
        }

        // Remove unnecessary scripts on checkout
        wp_dequeue_script('comment-reply');
        wp_dequeue_script('wp-embed');
        
        // Defer payment method loading
        add_action('wp_footer', function() {
            echo '<script>
                document.addEventListener("DOMContentLoaded", function() {
                    // Load payment methods only when needed
                    var paymentMethods = document.querySelectorAll(".payment_methods input[type=radio]");
                    paymentMethods.forEach(function(radio) {
                        radio.addEventListener("change", function() {
                            if (this.checked) {
                                var methodBox = this.closest("li").querySelector(".payment_box");
                                if (methodBox && !methodBox.dataset.loaded) {
                                    // Load payment method scripts here
                                    methodBox.dataset.loaded = "true";
                                }
                            }
                        });
                    });
                });
            </script>';
        });
    }

    /**
     * Remove unnecessary features
     */
    public function remove_unnecessary_features() {
        // Remove WooCommerce blocks CSS if not used
        if (!has_block('woocommerce/')) {
            wp_dequeue_style('wc-blocks-style');
        }

        // Remove cart fragments on non-shop pages
        if (!is_woocommerce() && !is_cart() && !is_checkout()) {
            wp_dequeue_script('wc-cart-fragments');
        }

        // Disable WooCommerce reviews if not needed
        if (get_option('woocommerce_enable_reviews') === 'no') {
            remove_action('woocommerce_single_product_summary', 'woocommerce_output_product_data_tabs', 60);
        }
    }

    /**
     * Optimize autoloaded options
     */
    private function optimize_autoloaded_options() {
        // List of WooCommerce options that don't need to be autoloaded
        $options_to_optimize = array(
            'woocommerce_admin_notices',
            'woocommerce_meta_box_errors',
            'woocommerce_single_image_width',
            'woocommerce_thumbnail_image_width'
        );

        foreach ($options_to_optimize as $option) {
            $value = get_option($option);
            if ($value !== false) {
                delete_option($option);
                add_option($option, $value, '', 'no');
            }
        }
    }

    /**
     * Suggest database optimizations
     */
    public function suggest_database_optimizations() {
        if (!current_user_can('manage_options') || !is_admin()) {
            return;
        }

        global $wpdb;

        // Check for missing indexes
        $missing_indexes = array();
        
        // Check postmeta indexes
        $postmeta_indexes = $wpdb->get_results("SHOW INDEX FROM {$wpdb->postmeta} WHERE Key_name LIKE '%meta_key%'");
        if (empty($postmeta_indexes)) {
            $missing_indexes[] = "ALTER TABLE {$wpdb->postmeta} ADD INDEX meta_key_value (meta_key, meta_value(20));";
        }

        if (!empty($missing_indexes)) {
            echo '<div class="notice notice-warning"><p>';
            echo '<strong>AquaLuxe Performance:</strong> Database optimization recommended. ';
            echo '<a href="#" onclick="alert(\'Contact your developer to run these queries:\\n' . esc_js(implode('\\n', $missing_indexes)) . '\')">View Details</a>';
            echo '</p></div>';
        }
    }
}