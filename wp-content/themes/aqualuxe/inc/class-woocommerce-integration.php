<?php
/**
 * AquaLuxe WooCommerce Integration
 * 
 * Handles WooCommerce compatibility and customizations.
 * 
 * @package AquaLuxe
 * @version 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class AquaLuxe_WooCommerce_Integration {
    
    /**
     * Initialize WooCommerce integration
     */
    public static function init() {
        // Theme support
        add_action('after_setup_theme', [__CLASS__, 'add_theme_support']);
        
        // Remove default WooCommerce styles
        add_filter('woocommerce_enqueue_styles', '__return_empty_array');
        
        // Customize WooCommerce structure
        self::customize_woocommerce_structure();
        
        // Add custom hooks
        self::add_custom_hooks();
        
        // Customize product display
        self::customize_product_display();
        
        // Customize cart and checkout
        self::customize_cart_checkout();
        
        // Add AJAX functionality
        self::add_ajax_functionality();
    }
    
    /**
     * Add WooCommerce theme support
     */
    public static function add_theme_support() {
        add_theme_support('woocommerce', [
            'thumbnail_image_width' => 300,
            'single_image_width' => 600,
            'product_grid' => [
                'default_rows' => 3,
                'min_rows' => 2,
                'max_rows' => 8,
                'default_columns' => 4,
                'min_columns' => 2,
                'max_columns' => 6
            ]
        ]);
        
        add_theme_support('wc-product-gallery-zoom');
        add_theme_support('wc-product-gallery-lightbox');
        add_theme_support('wc-product-gallery-slider');
    }
    
    /**
     * Customize WooCommerce structure
     */
    private static function customize_woocommerce_structure() {
        // Remove default WooCommerce wrappers
        remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
        remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);
        
        // Add custom wrappers
        add_action('woocommerce_before_main_content', [__CLASS__, 'wrapper_start'], 10);
        add_action('woocommerce_after_main_content', [__CLASS__, 'wrapper_end'], 10);
        
        // Customize breadcrumbs
        add_filter('woocommerce_breadcrumb_defaults', [__CLASS__, 'custom_breadcrumbs']);
        
        // Customize pagination
        add_filter('woocommerce_pagination_args', [__CLASS__, 'custom_pagination']);
    }
    
    /**
     * Add custom hooks for enhanced functionality
     */
    private static function add_custom_hooks() {
        // Add quick view functionality
        add_action('woocommerce_after_shop_loop_item', [__CLASS__, 'add_quick_view_button'], 15);
        
        // Add wishlist functionality
        add_action('woocommerce_after_shop_loop_item', [__CLASS__, 'add_wishlist_button'], 20);
        
        // Add product badges
        add_action('woocommerce_before_shop_loop_item_title', [__CLASS__, 'add_product_badges'], 5);
        
        // Customize single product layout
        add_action('woocommerce_single_product_summary', [__CLASS__, 'add_product_features'], 25);
        add_action('woocommerce_single_product_summary', [__CLASS__, 'add_shipping_info'], 35);
        
        // Add related products customization
        add_filter('woocommerce_output_related_products_args', [__CLASS__, 'related_products_args']);
    }
    
    /**
     * Customize product display
     */
    private static function customize_product_display() {
        // Change number of products per row
        add_filter('loop_shop_columns', [__CLASS__, 'loop_columns']);
        
        // Change number of products per page
        add_filter('loop_shop_per_page', [__CLASS__, 'products_per_page']);
        
        // Customize product thumbnails
        add_filter('woocommerce_get_image_size_gallery_thumbnail', [__CLASS__, 'gallery_thumbnail_size']);
        
        // Add product countdown for sales
        add_action('woocommerce_single_product_summary', [__CLASS__, 'add_sale_countdown'], 15);
    }
    
    /**
     * Customize cart and checkout
     */
    private static function customize_cart_checkout() {
        // Add estimated delivery
        add_action('woocommerce_cart_totals_after_order_total', [__CLASS__, 'add_estimated_delivery']);
        
        // Customize checkout fields
        add_filter('woocommerce_checkout_fields', [__CLASS__, 'custom_checkout_fields']);
        
        // Add trust signals
        add_action('woocommerce_review_order_before_payment', [__CLASS__, 'add_checkout_trust_signals']);
    }
    
    /**
     * Add AJAX functionality
     */
    private static function add_ajax_functionality() {
        // Quick view AJAX
        add_action('wp_ajax_aqualuxe_quick_view', [__CLASS__, 'quick_view_ajax']);
        add_action('wp_ajax_nopriv_aqualuxe_quick_view', [__CLASS__, 'quick_view_ajax']);
        
        // Wishlist AJAX
        add_action('wp_ajax_aqualuxe_wishlist', [__CLASS__, 'wishlist_ajax']);
        add_action('wp_ajax_nopriv_aqualuxe_wishlist', [__CLASS__, 'wishlist_ajax']);
        
        // Add to cart AJAX for variable products
        add_action('wp_ajax_aqualuxe_add_to_cart_variable', [__CLASS__, 'add_to_cart_variable_ajax']);
        add_action('wp_ajax_nopriv_aqualuxe_add_to_cart_variable', [__CLASS__, 'add_to_cart_variable_ajax']);
    }
    
    /**
     * Custom wrapper start
     */
    public static function wrapper_start() {
        echo '<div class="aqualuxe-woocommerce-wrapper">';
        echo '<div class="container mx-auto px-4">';
    }
    
    /**
     * Custom wrapper end
     */
    public static function wrapper_end() {
        echo '</div>'; // container
        echo '</div>'; // wrapper
    }
    
    /**
     * Custom breadcrumbs
     */
    public static function custom_breadcrumbs($args) {
        $args['delimiter'] = ' <span class="breadcrumb-separator">/</span> ';
        $args['wrap_before'] = '<nav class="woocommerce-breadcrumb breadcrumbs" aria-label="breadcrumb">';
        $args['wrap_after'] = '</nav>';
        return $args;
    }
    
    /**
     * Custom pagination
     */
    public static function custom_pagination($args) {
        $args['prev_text'] = '<i class="fas fa-chevron-left"></i> ' . __('Previous', 'aqualuxe');
        $args['next_text'] = __('Next', 'aqualuxe') . ' <i class="fas fa-chevron-right"></i>';
        return $args;
    }
    
    /**
     * Add quick view button
     */
    public static function add_quick_view_button() {
        global $product;
        
        echo '<div class="product-actions">';
        echo '<button class="quick-view-btn" data-product-id="' . esc_attr($product->get_id()) . '">';
        echo '<i class="fas fa-eye"></i> ' . esc_html__('Quick View', 'aqualuxe');
        echo '</button>';
        echo '</div>';
    }
    
    /**
     * Add wishlist button
     */
    public static function add_wishlist_button() {
        global $product;
        
        $wishlist_items = get_user_meta(get_current_user_id(), 'aqualuxe_wishlist', true);
        $wishlist_items = is_array($wishlist_items) ? $wishlist_items : [];
        $is_in_wishlist = in_array($product->get_id(), $wishlist_items);
        
        echo '<button class="wishlist-btn" data-product-id="' . esc_attr($product->get_id()) . '" ' . 
             'data-in-wishlist="' . esc_attr($is_in_wishlist ? '1' : '0') . '">';
        echo '<i class="' . ($is_in_wishlist ? 'fas' : 'far') . ' fa-heart"></i>';
        echo '<span class="sr-only">' . esc_html__('Add to Wishlist', 'aqualuxe') . '</span>';
        echo '</button>';
    }
    
    /**
     * Add product badges
     */
    public static function add_product_badges() {
        global $product;
        
        echo '<div class="product-badges">';
        
        // Sale badge
        if ($product->is_on_sale()) {
            $percentage = '';
            if ($product->get_regular_price() && $product->get_sale_price()) {
                $percentage = round((($product->get_regular_price() - $product->get_sale_price()) / $product->get_regular_price()) * 100);
                $percentage = '-' . $percentage . '%';
            }
            echo '<span class="badge badge-sale">' . esc_html($percentage ?: __('Sale', 'aqualuxe')) . '</span>';
        }
        
        // New badge (products newer than 30 days)
        $post_date = get_the_date('U');
        $current_date = current_time('U');
        if (($current_date - $post_date) < (30 * 24 * 60 * 60)) {
            echo '<span class="badge badge-new">' . esc_html__('New', 'aqualuxe') . '</span>';
        }
        
        // Featured badge
        if ($product->is_featured()) {
            echo '<span class="badge badge-featured">' . esc_html__('Featured', 'aqualuxe') . '</span>';
        }
        
        // Out of stock badge
        if (!$product->is_in_stock()) {
            echo '<span class="badge badge-out-of-stock">' . esc_html__('Out of Stock', 'aqualuxe') . '</span>';
        }
        
        echo '</div>';
    }
    
    /**
     * Add product features
     */
    public static function add_product_features() {
        global $product;
        
        $features = get_post_meta($product->get_id(), '_aqualuxe_product_features', true);
        if ($features) {
            echo '<div class="product-features">';
            echo '<h4>' . esc_html__('Key Features', 'aqualuxe') . '</h4>';
            echo '<ul>';
            foreach ($features as $feature) {
                echo '<li><i class="fas fa-check"></i> ' . esc_html($feature) . '</li>';
            }
            echo '</ul>';
            echo '</div>';
        }
    }
    
    /**
     * Add shipping information
     */
    public static function add_shipping_info() {
        echo '<div class="shipping-info">';
        echo '<div class="shipping-item">';
        echo '<i class="fas fa-truck"></i>';
        echo '<span>' . esc_html__('Free shipping on orders over $50', 'aqualuxe') . '</span>';
        echo '</div>';
        echo '<div class="shipping-item">';
        echo '<i class="fas fa-shield-alt"></i>';
        echo '<span>' . esc_html__('Live arrival guarantee', 'aqualuxe') . '</span>';
        echo '</div>';
        echo '<div class="shipping-item">';
        echo '<i class="fas fa-clock"></i>';
        echo '<span>' . esc_html__('Ships within 1-2 business days', 'aqualuxe') . '</span>';
        echo '</div>';
        echo '</div>';
    }
    
    /**
     * Related products arguments
     */
    public static function related_products_args($args) {
        $args['posts_per_page'] = 4;
        $args['columns'] = 4;
        return $args;
    }
    
    /**
     * Shop loop columns
     */
    public static function loop_columns() {
        return get_theme_mod('aqualuxe_shop_columns', 4);
    }
    
    /**
     * Products per page
     */
    public static function products_per_page() {
        return get_theme_mod('aqualuxe_products_per_page', 12);
    }
    
    /**
     * Gallery thumbnail size
     */
    public static function gallery_thumbnail_size($size) {
        $size['width'] = 150;
        $size['height'] = 150;
        $size['crop'] = 1;
        return $size;
    }
    
    /**
     * Add sale countdown
     */
    public static function add_sale_countdown() {
        global $product;
        
        if ($product->is_on_sale()) {
            $sale_end = get_post_meta($product->get_id(), '_sale_price_dates_to', true);
            if ($sale_end) {
                echo '<div class="sale-countdown" data-end-time="' . esc_attr($sale_end) . '">';
                echo '<h4>' . esc_html__('Sale ends in:', 'aqualuxe') . '</h4>';
                echo '<div class="countdown-timer">';
                echo '<span class="days">00</span>d ';
                echo '<span class="hours">00</span>h ';
                echo '<span class="minutes">00</span>m ';
                echo '<span class="seconds">00</span>s';
                echo '</div>';
                echo '</div>';
            }
        }
    }
    
    /**
     * Add estimated delivery
     */
    public static function add_estimated_delivery() {
        $delivery_date = date('M j, Y', strtotime('+3 days'));
        echo '<tr class="estimated-delivery">';
        echo '<th>' . esc_html__('Estimated Delivery', 'aqualuxe') . '</th>';
        echo '<td>' . esc_html($delivery_date) . '</td>';
        echo '</tr>';
    }
    
    /**
     * Custom checkout fields
     */
    public static function custom_checkout_fields($fields) {
        // Add aquarium setup assistance field
        $fields['billing']['aquarium_assistance'] = [
            'type' => 'checkbox',
            'label' => __('I would like aquarium setup assistance', 'aqualuxe'),
            'required' => false,
            'class' => ['form-row-wide'],
            'priority' => 25
        ];
        
        return $fields;
    }
    
    /**
     * Add checkout trust signals
     */
    public static function add_checkout_trust_signals() {
        echo '<div class="checkout-trust-signals">';
        echo '<div class="trust-signal">';
        echo '<i class="fas fa-lock"></i>';
        echo '<span>' . esc_html__('SSL Encrypted Checkout', 'aqualuxe') . '</span>';
        echo '</div>';
        echo '<div class="trust-signal">';
        echo '<i class="fas fa-shield-alt"></i>';
        echo '<span>' . esc_html__('100% Secure Payment', 'aqualuxe') . '</span>';
        echo '</div>';
        echo '<div class="trust-signal">';
        echo '<i class="fas fa-undo"></i>';
        echo '<span>' . esc_html__('30-Day Return Policy', 'aqualuxe') . '</span>';
        echo '</div>';
        echo '</div>';
    }
    
    /**
     * Quick view AJAX handler
     */
    public static function quick_view_ajax() {
        check_ajax_referer('aqualuxe_nonce', 'nonce');
        
        $product_id = intval($_POST['product_id']);
        $product = wc_get_product($product_id);
        
        if (!$product) {
            wp_die();
        }
        
        // Set up global product data
        global $woocommerce, $product, $post;
        $post = get_post($product_id);
        setup_postdata($post);
        
        // Load quick view template
        wc_get_template('quick-view/content-quick-view.php', ['product' => $product], '', AQUALUXE_PATH . '/woocommerce/');
        
        wp_die();
    }
    
    /**
     * Wishlist AJAX handler
     */
    public static function wishlist_ajax() {
        check_ajax_referer('aqualuxe_nonce', 'nonce');
        
        if (!is_user_logged_in()) {
            wp_send_json_error(['message' => __('Please login to use wishlist', 'aqualuxe')]);
        }
        
        $product_id = intval($_POST['product_id']);
        $user_id = get_current_user_id();
        $wishlist = get_user_meta($user_id, 'aqualuxe_wishlist', true);
        $wishlist = is_array($wishlist) ? $wishlist : [];
        
        if (in_array($product_id, $wishlist)) {
            // Remove from wishlist
            $wishlist = array_diff($wishlist, [$product_id]);
            $action = 'removed';
            $message = __('Removed from wishlist', 'aqualuxe');
        } else {
            // Add to wishlist
            $wishlist[] = $product_id;
            $action = 'added';
            $message = __('Added to wishlist', 'aqualuxe');
        }
        
        update_user_meta($user_id, 'aqualuxe_wishlist', $wishlist);
        
        wp_send_json_success([
            'action' => $action,
            'message' => $message,
            'count' => count($wishlist)
        ]);
    }
    
    /**
     * Add to cart variable products AJAX handler
     */
    public static function add_to_cart_variable_ajax() {
        check_ajax_referer('aqualuxe_nonce', 'nonce');
        
        $product_id = intval($_POST['product_id']);
        $variation_id = intval($_POST['variation_id']);
        $quantity = intval($_POST['quantity']);
        
        $passed_validation = apply_filters('woocommerce_add_to_cart_validation', true, $product_id, $quantity);
        
        if ($passed_validation && WC()->cart->add_to_cart($product_id, $quantity, $variation_id)) {
            wp_send_json_success([
                'message' => __('Product added to cart', 'aqualuxe'),
                'cart_count' => WC()->cart->get_cart_contents_count()
            ]);
        } else {
            wp_send_json_error(['message' => __('Could not add product to cart', 'aqualuxe')]);
        }
    }
}
