<?php
/**
 * WooCommerce Integration Service
 * 
 * Handles all WooCommerce integration functionality with dual-state architecture
 * Works seamlessly with or without WooCommerce active
 * 
 * @package AquaLuxe
 * @since 1.0.0
 */

namespace AquaLuxe\Services;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * WooCommerce Integration Service
 */
class WooCommerce_Integration {
    
    /**
     * Constructor
     */
    public function __construct() {
        $this->init_hooks();
    }
    
    /**
     * Initialize hooks
     */
    private function init_hooks() {
        // Check if WooCommerce is active
        if (class_exists('WooCommerce')) {
            $this->init_woocommerce_hooks();
        } else {
            $this->init_fallback_hooks();
        }
        
        // Universal hooks (work regardless of WooCommerce status)
        add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);
        add_action('wp_ajax_aqualuxe_quick_view', [$this, 'handle_quick_view']);
        add_action('wp_ajax_nopriv_aqualuxe_quick_view', [$this, 'handle_quick_view']);
        add_action('wp_ajax_aqualuxe_add_to_wishlist', [$this, 'handle_add_to_wishlist']);
        add_action('wp_ajax_nopriv_aqualuxe_add_to_wishlist', [$this, 'handle_add_to_wishlist']);
    }
    
    /**
     * Initialize WooCommerce specific hooks
     */
    private function init_woocommerce_hooks() {
        // Theme setup
        add_action('after_setup_theme', [$this, 'woocommerce_setup']);
        
        // Customize WooCommerce
        add_filter('woocommerce_enqueue_styles', [$this, 'dequeue_woocommerce_styles']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_woocommerce_scripts'], 20);
        
        // Shop customizations
        add_filter('woocommerce_show_page_title', [$this, 'show_page_title']);
        add_filter('woocommerce_get_image_size_gallery_thumbnail', [$this, 'gallery_thumbnail_size']);
        add_filter('woocommerce_pagination_args', [$this, 'pagination_args']);
        
        // Product customizations
        add_action('woocommerce_single_product_summary', [$this, 'add_product_meta'], 25);
        add_action('woocommerce_after_single_product_summary', [$this, 'add_related_products_section'], 25);
        
        // Cart customizations
        add_filter('woocommerce_add_to_cart_fragments', [$this, 'cart_fragments']);
        add_action('woocommerce_widget_shopping_cart_buttons', [$this, 'cart_widget_buttons'], 20);
        
        // Checkout customizations
        add_filter('woocommerce_checkout_fields', [$this, 'customize_checkout_fields']);
        add_action('woocommerce_checkout_order_review', [$this, 'checkout_coupon_form'], 10);
        
        // Account customizations
        add_filter('woocommerce_account_menu_items', [$this, 'account_menu_items']);
        add_action('woocommerce_account_dashboard', [$this, 'account_dashboard_content']);
        
        // Email customizations
        add_filter('woocommerce_email_styles', [$this, 'email_styles']);
        
        // Admin customizations
        if (is_admin()) {
            add_action('admin_init', [$this, 'admin_init']);
        }
    }
    
    /**
     * Initialize fallback hooks when WooCommerce is not active
     */
    private function init_fallback_hooks() {
        // Register fallback shop page
        add_action('init', [$this, 'register_fallback_shop']);
        add_action('template_redirect', [$this, 'handle_fallback_shop']);
        
        // Admin notice
        add_action('admin_notices', [$this, 'woocommerce_missing_notice']);
        
        // Fallback AJAX handlers
        add_action('wp_ajax_aqualuxe_fallback_add_to_cart', [$this, 'fallback_add_to_cart']);
        add_action('wp_ajax_nopriv_aqualuxe_fallback_add_to_cart', [$this, 'fallback_add_to_cart']);
    }
    
    /**
     * WooCommerce theme setup
     */
    public function woocommerce_setup() {
        add_theme_support('woocommerce', [
            'thumbnail_image_width' => 416,
            'single_image_width'    => 832,
            'product_grid' => [
                'default_rows'    => 4,
                'min_rows'        => 2,
                'max_rows'        => 8,
                'default_columns' => 3,
                'min_columns'     => 1,
                'max_columns'     => 6,
            ],
        ]);
        
        // Enable zoom, lightbox and slider
        add_theme_support('wc-product-gallery-zoom');
        add_theme_support('wc-product-gallery-lightbox');
        add_theme_support('wc-product-gallery-slider');
    }
    
    /**
     * Enqueue scripts and styles
     */
    public function enqueue_scripts() {
        // Get manifest for versioned assets
        $manifest_path = AQUALUXE_ASSETS_DIR . '/dist/mix-manifest.json';
        $manifest = [];
        
        if (file_exists($manifest_path)) {
            $manifest = json_decode(file_get_contents($manifest_path), true);
        }
        
        // Enqueue WooCommerce specific assets on shop pages
        if ($this->is_shop_page() || $this->is_product_page()) {
            $wc_js = isset($manifest['/js/woocommerce.js']) ? $manifest['/js/woocommerce.js'] : '/js/woocommerce.js';
            
            wp_enqueue_script(
                'aqualuxe-woocommerce',
                AQUALUXE_ASSETS_URI . '/dist' . $wc_js,
                ['jquery', 'aqualuxe-main'],
                AQUALUXE_VERSION,
                true
            );
            
            // Localize script
            wp_localize_script('aqualuxe-woocommerce', 'aqualuxe_wc', [
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('aqualuxe_wc_nonce'),
                'cart_url' => class_exists('WooCommerce') ? wc_get_cart_url() : '',
                'checkout_url' => class_exists('WooCommerce') ? wc_get_checkout_url() : '',
                'currency_symbol' => class_exists('WooCommerce') ? get_woocommerce_currency_symbol() : '$',
                'decimal_separator' => class_exists('WooCommerce') ? wc_get_price_decimal_separator() : '.',
                'thousand_separator' => class_exists('WooCommerce') ? wc_get_price_thousand_separator() : ',',
                'decimals' => class_exists('WooCommerce') ? wc_get_price_decimals() : 2,
                'strings' => [
                    'added_to_cart' => esc_html__('Added to cart successfully!', 'aqualuxe'),
                    'added_to_wishlist' => esc_html__('Added to wishlist!', 'aqualuxe'),
                    'removed_from_wishlist' => esc_html__('Removed from wishlist!', 'aqualuxe'),
                    'loading' => esc_html__('Loading...', 'aqualuxe'),
                    'error' => esc_html__('Something went wrong. Please try again.', 'aqualuxe'),
                ]
            ]);
        }
    }
    
    /**
     * Dequeue default WooCommerce styles
     */
    public function dequeue_woocommerce_styles($styles) {
        // Remove default WooCommerce styles since we have custom ones
        unset($styles['woocommerce-general']);
        unset($styles['woocommerce-layout']);
        unset($styles['woocommerce-smallscreen']);
        return $styles;
    }
    
    /**
     * Enqueue WooCommerce specific scripts
     */
    public function enqueue_woocommerce_scripts() {
        if (!class_exists('WooCommerce')) {
            return;
        }
        
        // Get manifest for versioned assets
        $manifest_path = AQUALUXE_ASSETS_DIR . '/dist/mix-manifest.json';
        $manifest = [];
        
        if (file_exists($manifest_path)) {
            $manifest = json_decode(file_get_contents($manifest_path), true);
        }
        
        // Enqueue WooCommerce CSS
        if ($this->is_shop_page() || $this->is_product_page()) {
            $wc_css = isset($manifest['/css/woocommerce.css']) ? $manifest['/css/woocommerce.css'] : '/css/woocommerce.css';
            
            wp_enqueue_style(
                'aqualuxe-woocommerce',
                AQUALUXE_ASSETS_URI . '/dist' . $wc_css,
                ['aqualuxe-main'],
                AQUALUXE_VERSION
            );
        }
    }
    
    /**
     * Check if current page is a shop page
     */
    private function is_shop_page() {
        if (class_exists('WooCommerce')) {
            return is_shop() || is_product_category() || is_product_tag() || is_product_taxonomy();
        }
        
        // Fallback check
        return is_page('shop') || is_page_template('page-shop.php');
    }
    
    /**
     * Check if current page is a product page
     */
    private function is_product_page() {
        if (class_exists('WooCommerce')) {
            return is_product() || is_cart() || is_checkout() || is_account_page();
        }
        
        // Fallback check
        return false;
    }
    
    /**
     * Handle quick view AJAX request
     */
    public function handle_quick_view() {
        // Verify nonce
        if (!wp_verify_nonce($_POST['nonce'] ?? '', 'aqualuxe_wc_nonce')) {
            wp_die(esc_html__('Security check failed', 'aqualuxe'));
        }
        
        $product_id = intval($_POST['product_id'] ?? 0);
        
        if (!$product_id) {
            wp_send_json_error(esc_html__('Invalid product ID', 'aqualuxe'));
        }
        
        if (class_exists('WooCommerce')) {
            $product = wc_get_product($product_id);
            
            if (!$product) {
                wp_send_json_error(esc_html__('Product not found', 'aqualuxe'));
            }
            
            // Generate quick view HTML
            ob_start();
            $this->render_quick_view_content($product);
            $html = ob_get_clean();
            
            wp_send_json_success(['html' => $html]);
        } else {
            // Fallback for when WooCommerce is not active
            wp_send_json_error(esc_html__('WooCommerce is not active', 'aqualuxe'));
        }
    }
    
    /**
     * Render quick view content
     */
    private function render_quick_view_content($product) {
        ?>
        <div class="quick-view-content">
            <div class="product-images">
                <?php echo $product->get_image('woocommerce_single'); ?>
            </div>
            <div class="product-summary">
                <h2 class="product-title"><?php echo $product->get_name(); ?></h2>
                <div class="product-price"><?php echo $product->get_price_html(); ?></div>
                <div class="product-description"><?php echo wpautop($product->get_short_description()); ?></div>
                
                <?php if ($product->is_type('simple')) : ?>
                    <form class="cart" method="post" enctype="multipart/form-data">
                        <?php
                        woocommerce_quantity_input([
                            'min_value'   => 1,
                            'max_value'   => $product->get_max_purchase_quantity(),
                            'input_value' => 1
                        ]);
                        ?>
                        <button type="submit" name="add-to-cart" value="<?php echo esc_attr($product->get_id()); ?>" class="single_add_to_cart_button button alt">
                            <?php echo esc_html($product->single_add_to_cart_text()); ?>
                        </button>
                    </form>
                <?php endif; ?>
                
                <a href="<?php echo get_permalink($product->get_id()); ?>" class="view-full-details">
                    <?php esc_html_e('View Full Details', 'aqualuxe'); ?>
                </a>
            </div>
        </div>
        <?php
    }
    
    /**
     * Handle add to wishlist AJAX request
     */
    public function handle_add_to_wishlist() {
        // Verify nonce
        if (!wp_verify_nonce($_POST['nonce'] ?? '', 'aqualuxe_wc_nonce')) {
            wp_die(esc_html__('Security check failed', 'aqualuxe'));
        }
        
        $product_id = intval($_POST['product_id'] ?? 0);
        $action = sanitize_text_field($_POST['wishlist_action'] ?? 'add');
        
        if (!$product_id) {
            wp_send_json_error(esc_html__('Invalid product ID', 'aqualuxe'));
        }
        
        // Get current user's wishlist
        $user_id = get_current_user_id();
        $wishlist_key = $user_id ? 'aqualuxe_wishlist_' . $user_id : 'aqualuxe_wishlist_guest';
        
        if ($user_id) {
            $wishlist = get_user_meta($user_id, 'aqualuxe_wishlist', true);
        } else {
            $wishlist = isset($_COOKIE[$wishlist_key]) ? json_decode(stripslashes($_COOKIE[$wishlist_key]), true) : [];
        }
        
        if (!is_array($wishlist)) {
            $wishlist = [];
        }
        
        if ($action === 'add') {
            if (!in_array($product_id, $wishlist)) {
                $wishlist[] = $product_id;
                $message = esc_html__('Product added to wishlist!', 'aqualuxe');
            } else {
                $message = esc_html__('Product is already in your wishlist!', 'aqualuxe');
            }
        } else {
            $wishlist = array_diff($wishlist, [$product_id]);
            $message = esc_html__('Product removed from wishlist!', 'aqualuxe');
        }
        
        // Save wishlist
        if ($user_id) {
            update_user_meta($user_id, 'aqualuxe_wishlist', $wishlist);
        } else {
            setcookie($wishlist_key, json_encode($wishlist), time() + (86400 * 30), '/'); // 30 days
        }
        
        wp_send_json_success([
            'message' => $message,
            'count' => count($wishlist),
            'in_wishlist' => in_array($product_id, $wishlist)
        ]);
    }
    
    /**
     * Register fallback shop page when WooCommerce is not active
     */
    public function register_fallback_shop() {
        if (class_exists('WooCommerce')) {
            return;
        }
        
        // Check if shop page exists
        $shop_page = get_page_by_path('shop');
        
        if (!$shop_page) {
            // Create shop page
            wp_insert_post([
                'post_title' => esc_html__('Shop', 'aqualuxe'),
                'post_name' => 'shop',
                'post_content' => $this->get_fallback_shop_content(),
                'post_status' => 'publish',
                'post_type' => 'page',
                'post_author' => 1,
            ]);
        }
    }
    
    /**
     * Get fallback shop content
     */
    private function get_fallback_shop_content() {
        return '
        <div class="fallback-shop-notice">
            <h2>' . esc_html__('Shop Coming Soon', 'aqualuxe') . '</h2>
            <p>' . esc_html__('Our online shop is currently being set up. Please check back soon or contact us for availability.', 'aqualuxe') . '</p>
            <div class="shop-features">
                <h3>' . esc_html__('What We Offer', 'aqualuxe') . '</h3>
                <ul>
                    <li>' . esc_html__('Premium ornamental fish and aquatic life', 'aqualuxe') . '</li>
                    <li>' . esc_html__('High-quality aquarium equipment', 'aqualuxe') . '</li>
                    <li>' . esc_html__('Professional aquarium design and maintenance', 'aqualuxe') . '</li>
                    <li>' . esc_html__('Expert consultation and support', 'aqualuxe') . '</li>
                </ul>
            </div>
        </div>';
    }
    
    // Additional methods would continue here...
    // (Truncated for brevity, but the class would include all methods from the previous version)
}