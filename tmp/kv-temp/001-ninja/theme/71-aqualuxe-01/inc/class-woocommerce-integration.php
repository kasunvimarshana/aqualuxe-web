<?php
/**
 * WooCommerce Integration Class
 * 
 * @package AquaLuxe
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * WooCommerce Integration class
 */
class WooCommerce_Integration {
    
    /**
     * Instance of this class
     */
    private static $instance = null;
    
    /**
     * Constructor
     */
    private function __construct() {
        $this->init_hooks();
    }
    
    /**
     * Get instance
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Initialize hooks
     */
    private function init_hooks() {
        // Theme support
        add_action('after_setup_theme', [$this, 'setup_theme_support']);
        
        // Enqueue scripts and styles
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
        
        // Modify WooCommerce templates
        add_filter('woocommerce_locate_template', [$this, 'locate_template'], 10, 3);
        
        // Customize shop page
        add_action('woocommerce_before_shop_loop', [$this, 'shop_header'], 5);
        add_action('woocommerce_before_shop_loop', [$this, 'shop_filters'], 15);
        
        // Customize product loop
        add_action('woocommerce_before_shop_loop_item_title', [$this, 'product_loop_badges'], 5);
        add_action('woocommerce_after_shop_loop_item_title', [$this, 'product_loop_rating'], 5);
        add_action('woocommerce_after_shop_loop_item', [$this, 'product_loop_actions'], 15);
        
        // Customize single product
        add_action('woocommerce_single_product_summary', [$this, 'product_badges'], 6);
        add_action('woocommerce_single_product_summary', [$this, 'product_share'], 35);
        
        // Customize cart
        add_filter('woocommerce_add_to_cart_fragments', [$this, 'cart_count_fragment']);
        add_action('woocommerce_before_cart', [$this, 'cart_progress_bar']);
        
        // Customize checkout
        add_action('woocommerce_before_checkout_form', [$this, 'checkout_progress_bar']);
        
        // Account customizations
        add_filter('woocommerce_account_menu_items', [$this, 'customize_account_menu']);
        
        // Ajax actions
        add_action('wp_ajax_aqualuxe_add_to_wishlist', [$this, 'ajax_add_to_wishlist']);
        add_action('wp_ajax_nopriv_aqualuxe_add_to_wishlist', [$this, 'ajax_add_to_wishlist']);
        add_action('wp_ajax_aqualuxe_quick_view', [$this, 'ajax_quick_view']);
        add_action('wp_ajax_nopriv_aqualuxe_quick_view', [$this, 'ajax_quick_view']);
        
        // Remove default actions
        $this->remove_default_actions();
        
        // Add custom actions
        $this->add_custom_actions();
    }
    
    /**
     * Setup WooCommerce theme support
     */
    public function setup_theme_support() {
        add_theme_support('woocommerce', [
            'thumbnail_image_width' => 300,
            'single_image_width'    => 600,
            'product_grid'          => [
                'default_rows'    => 4,
                'min_rows'        => 2,
                'max_rows'        => 8,
                'default_columns' => 4,
                'min_columns'     => 2,
                'max_columns'     => 5,
            ],
        ]);
        
        add_theme_support('wc-product-gallery-zoom');
        add_theme_support('wc-product-gallery-lightbox');
        add_theme_support('wc-product-gallery-slider');
    }
    
    /**
     * Enqueue WooCommerce specific assets
     */
    public function enqueue_assets() {
        if (is_woocommerce() || is_cart() || is_checkout() || is_account_page()) {
            $asset_manager = Asset_Manager::get_instance();
            
            if ($asset_manager->asset_exists('js/woocommerce.js')) {
                wp_enqueue_script(
                    'aqualuxe-woocommerce',
                    $asset_manager->get_asset_url('js/woocommerce.js'),
                    ['aqualuxe-script'],
                    null,
                    true
                );
                
                wp_localize_script('aqualuxe-woocommerce', 'aqualuxeWC', [
                    'ajaxUrl' => admin_url('admin-ajax.php'),
                    'nonce' => wp_create_nonce('aqualuxe_wc_nonce'),
                    'cartUrl' => wc_get_cart_url(),
                    'checkoutUrl' => wc_get_checkout_url(),
                    'accountUrl' => wc_get_page_permalink('myaccount'),
                    'wishlistEnabled' => get_theme_mod('aqualuxe_wishlist_enabled', true),
                    'quickViewEnabled' => get_theme_mod('aqualuxe_quick_view_enabled', true),
                    'strings' => [
                        'addedToCart' => __('Added to cart', 'aqualuxe'),
                        'addedToWishlist' => __('Added to wishlist', 'aqualuxe'),
                        'removedFromWishlist' => __('Removed from wishlist', 'aqualuxe'),
                        'selectOptions' => __('Select options', 'aqualuxe'),
                        'viewCart' => __('View cart', 'aqualuxe'),
                        'loading' => __('Loading...', 'aqualuxe'),
                        'error' => __('An error occurred', 'aqualuxe'),
                    ]
                ]);
            }
        }
    }
    
    /**
     * Locate custom WooCommerce templates
     */
    public function locate_template($template, $template_name, $template_path) {
        $theme_template = AQUALUXE_THEME_DIR . '/woocommerce/' . $template_name;
        
        if (file_exists($theme_template)) {
            return $theme_template;
        }
        
        return $template;
    }
    
    /**
     * Add shop header
     */
    public function shop_header() {
        if (is_shop() && !is_search()) {
            echo '<div class="shop-header mb-8 text-center">';
            echo '<h1 class="shop-title text-3xl font-bold mb-4">' . woocommerce_page_title(false) . '</h1>';
            
            if ($shop_description = get_the_content()) {
                echo '<div class="shop-description text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">';
                echo $shop_description;
                echo '</div>';
            }
            echo '</div>';
        }
    }
    
    /**
     * Add shop filters
     */
    public function shop_filters() {
        if (is_shop() || is_product_category() || is_product_tag()) {
            echo '<div class="shop-filters mb-6 flex flex-wrap items-center justify-between gap-4">';
            
            // Product count and ordering
            echo '<div class="shop-controls flex items-center gap-4">';
            woocommerce_result_count();
            woocommerce_catalog_ordering();
            echo '</div>';
            
            // Filter toggle for mobile
            echo '<button class="filter-toggle btn btn-secondary lg:hidden" data-target="#shop-sidebar">';
            echo __('Filters', 'aqualuxe');
            echo '</button>';
            
            echo '</div>';
        }
    }
    
    /**
     * Add product loop badges
     */
    public function product_loop_badges() {
        global $product;
        
        echo '<div class="product-badges absolute top-2 left-2 z-10 flex flex-col gap-1">';
        
        if ($product->is_on_sale()) {
            $percentage = '';
            if ($product->get_regular_price() && $product->get_sale_price()) {
                $percentage = round((($product->get_regular_price() - $product->get_sale_price()) / $product->get_regular_price()) * 100);
                $percentage = '-' . $percentage . '%';
            }
            echo '<span class="badge badge-sale bg-coral-500 text-white px-2 py-1 text-xs font-semibold rounded">';
            echo $percentage ?: __('Sale', 'aqualuxe');
            echo '</span>';
        }
        
        if ($product->is_featured()) {
            echo '<span class="badge badge-featured bg-luxe-500 text-white px-2 py-1 text-xs font-semibold rounded">';
            echo __('Featured', 'aqualuxe');
            echo '</span>';
        }
        
        if (!$product->is_in_stock()) {
            echo '<span class="badge badge-out-of-stock bg-gray-500 text-white px-2 py-1 text-xs font-semibold rounded">';
            echo __('Out of Stock', 'aqualuxe');
            echo '</span>';
        }
        
        echo '</div>';
    }
    
    /**
     * Add product loop rating
     */
    public function product_loop_rating() {
        global $product;
        
        if (get_option('woocommerce_enable_review_rating') === 'no') {
            return;
        }
        
        $rating_count = $product->get_rating_count();
        $review_count = $product->get_review_count();
        $average = $product->get_average_rating();
        
        if ($rating_count > 0) {
            echo '<div class="product-rating flex items-center gap-2 mb-2">';
            echo wc_get_rating_html($average, $rating_count);
            echo '<span class="rating-count text-sm text-gray-500">(' . $review_count . ')</span>';
            echo '</div>';
        }
    }
    
    /**
     * Add product loop actions
     */
    public function product_loop_actions() {
        global $product;
        
        echo '<div class="product-actions flex items-center gap-2 mt-4">';
        
        // Quick view button
        if (get_theme_mod('aqualuxe_quick_view_enabled', true)) {
            echo '<button class="quick-view-btn btn btn-secondary flex-1" data-product-id="' . $product->get_id() . '">';
            echo __('Quick View', 'aqualuxe');
            echo '</button>';
        }
        
        // Wishlist button
        if (get_theme_mod('aqualuxe_wishlist_enabled', true)) {
            $wishlist_products = get_user_meta(get_current_user_id(), 'aqualuxe_wishlist', true) ?: [];
            $is_in_wishlist = in_array($product->get_id(), $wishlist_products);
            
            echo '<button class="wishlist-btn btn ' . ($is_in_wishlist ? 'btn-primary' : 'btn-secondary') . ' w-10 h-10 flex items-center justify-center" data-product-id="' . $product->get_id() . '">';
            echo '<svg class="w-5 h-5" fill="' . ($is_in_wishlist ? 'currentColor' : 'none') . '" stroke="currentColor" viewBox="0 0 24 24">';
            echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>';
            echo '</svg>';
            echo '</button>';
        }
        
        echo '</div>';
    }
    
    /**
     * Add product badges on single product
     */
    public function product_badges() {
        global $product;
        
        echo '<div class="product-badges-single flex flex-wrap gap-2 mb-4">';
        
        if ($product->is_on_sale()) {
            echo '<span class="badge badge-sale bg-coral-500 text-white px-3 py-1 text-sm font-semibold rounded-full">';
            echo __('On Sale', 'aqualuxe');
            echo '</span>';
        }
        
        if ($product->is_featured()) {
            echo '<span class="badge badge-featured bg-luxe-500 text-white px-3 py-1 text-sm font-semibold rounded-full">';
            echo __('Featured', 'aqualuxe');
            echo '</span>';
        }
        
        // Custom badges based on product attributes
        $attributes = $product->get_attributes();
        foreach ($attributes as $attribute) {
            if ($attribute->get_name() === 'pa_badge') {
                $terms = wp_get_post_terms($product->get_id(), $attribute->get_name());
                foreach ($terms as $term) {
                    echo '<span class="badge badge-custom bg-primary-500 text-white px-3 py-1 text-sm font-semibold rounded-full">';
                    echo esc_html($term->name);
                    echo '</span>';
                }
            }
        }
        
        echo '</div>';
    }
    
    /**
     * Add product share buttons
     */
    public function product_share() {
        if (!get_theme_mod('aqualuxe_product_sharing', true)) {
            return;
        }
        
        global $product;
        
        $product_url = get_permalink();
        $product_title = get_the_title();
        $product_image = wp_get_attachment_image_url($product->get_image_id(), 'large');
        
        echo '<div class="product-share mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">';
        echo '<h4 class="text-sm font-semibold mb-3">' . __('Share this product', 'aqualuxe') . '</h4>';
        echo '<div class="share-buttons flex items-center gap-3">';
        
        $share_networks = [
            'facebook' => 'https://www.facebook.com/sharer/sharer.php?u=' . urlencode($product_url),
            'twitter' => 'https://twitter.com/intent/tweet?url=' . urlencode($product_url) . '&text=' . urlencode($product_title),
            'pinterest' => 'https://pinterest.com/pin/create/button/?url=' . urlencode($product_url) . '&media=' . urlencode($product_image) . '&description=' . urlencode($product_title),
            'whatsapp' => 'https://api.whatsapp.com/send?text=' . urlencode($product_title . ' ' . $product_url),
        ];
        
        foreach ($share_networks as $network => $url) {
            echo '<a href="' . esc_url($url) . '" class="share-btn btn btn-secondary w-10 h-10 flex items-center justify-center" target="_blank" rel="noopener">';
            echo aqualuxe_get_social_icon($network, 'w-4 h-4');
            echo '</a>';
        }
        
        echo '</div>';
        echo '</div>';
    }
    
    /**
     * Update cart count fragment for AJAX
     */
    public function cart_count_fragment($fragments) {
        $count = WC()->cart->get_cart_contents_count();
        
        $fragments['.cart-count'] = '<span class="cart-count absolute -top-2 -right-2 bg-primary-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">' . $count . '</span>';
        
        return $fragments;
    }
    
    /**
     * Add cart progress bar
     */
    public function cart_progress_bar() {
        $free_shipping_threshold = get_option('woocommerce_free_shipping_threshold', 0);
        
        if ($free_shipping_threshold > 0) {
            $cart_total = WC()->cart->get_subtotal();
            $remaining = $free_shipping_threshold - $cart_total;
            $percentage = min(($cart_total / $free_shipping_threshold) * 100, 100);
            
            echo '<div class="cart-progress mb-6 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">';
            
            if ($remaining > 0) {
                echo '<p class="text-sm mb-2">' . sprintf(__('Spend %s more for free shipping!', 'aqualuxe'), wc_price($remaining)) . '</p>';
            } else {
                echo '<p class="text-sm mb-2 text-green-600">' . __('You qualify for free shipping!', 'aqualuxe') . '</p>';
            }
            
            echo '<div class="progress-bar bg-gray-200 dark:bg-gray-700 rounded-full h-2">';
            echo '<div class="progress-fill bg-primary-500 h-2 rounded-full transition-all duration-300" style="width: ' . $percentage . '%"></div>';
            echo '</div>';
            echo '</div>';
        }
    }
    
    /**
     * Add checkout progress bar
     */
    public function checkout_progress_bar() {
        $steps = [
            'cart' => __('Cart', 'aqualuxe'),
            'checkout' => __('Checkout', 'aqualuxe'),
            'order-received' => __('Complete', 'aqualuxe'),
        ];
        
        $current_step = 'checkout';
        if (is_wc_endpoint_url('order-received')) {
            $current_step = 'order-received';
        }
        
        echo '<div class="checkout-progress mb-8">';
        echo '<div class="flex items-center justify-between">';
        
        $step_index = 0;
        foreach ($steps as $step_key => $step_label) {
            $is_current = $step_key === $current_step;
            $is_completed = array_search($current_step, array_keys($steps)) > $step_index;
            
            echo '<div class="step flex-1 text-center">';
            echo '<div class="step-indicator w-8 h-8 mx-auto mb-2 rounded-full flex items-center justify-center ' . 
                 ($is_current ? 'bg-primary-500 text-white' : ($is_completed ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-600')) . '">';
            echo $step_index + 1;
            echo '</div>';
            echo '<div class="step-label text-sm ' . ($is_current ? 'font-semibold' : 'text-gray-600') . '">';
            echo $step_label;
            echo '</div>';
            echo '</div>';
            
            if ($step_index < count($steps) - 1) {
                echo '<div class="step-connector flex-1 h-px bg-gray-200 mt-4 mx-4"></div>';
            }
            
            $step_index++;
        }
        
        echo '</div>';
        echo '</div>';
    }
    
    /**
     * Customize account menu
     */
    public function customize_account_menu($items) {
        // Remove logout and re-add at the end
        if (isset($items['customer-logout'])) {
            $logout = $items['customer-logout'];
            unset($items['customer-logout']);
        }
        
        // Add custom menu items
        if (get_theme_mod('aqualuxe_wishlist_enabled', true)) {
            $items['wishlist'] = __('Wishlist', 'aqualuxe');
        }
        
        // Add logout back at the end
        if (isset($logout)) {
            $items['customer-logout'] = $logout;
        }
        
        return $items;
    }
    
    /**
     * AJAX handler for add to wishlist
     */
    public function ajax_add_to_wishlist() {
        check_ajax_referer('aqualuxe_wc_nonce', 'nonce');
        
        if (!is_user_logged_in()) {
            wp_send_json_error(['message' => __('You must be logged in to use the wishlist.', 'aqualuxe')]);
        }
        
        $product_id = intval($_POST['product_id']);
        $user_id = get_current_user_id();
        
        $wishlist = get_user_meta($user_id, 'aqualuxe_wishlist', true) ?: [];
        
        if (in_array($product_id, $wishlist)) {
            // Remove from wishlist
            $wishlist = array_diff($wishlist, [$product_id]);
            $action = 'removed';
        } else {
            // Add to wishlist
            $wishlist[] = $product_id;
            $action = 'added';
        }
        
        update_user_meta($user_id, 'aqualuxe_wishlist', $wishlist);
        
        wp_send_json_success([
            'action' => $action,
            'product_id' => $product_id,
            'count' => count($wishlist),
            'message' => $action === 'added' ? __('Added to wishlist', 'aqualuxe') : __('Removed from wishlist', 'aqualuxe')
        ]);
    }
    
    /**
     * AJAX handler for quick view
     */
    public function ajax_quick_view() {
        check_ajax_referer('aqualuxe_wc_nonce', 'nonce');
        
        $product_id = intval($_POST['product_id']);
        $product = wc_get_product($product_id);
        
        if (!$product) {
            wp_send_json_error(['message' => __('Product not found.', 'aqualuxe')]);
        }
        
        global $woocommerce_loop;
        $woocommerce_loop['is_quick_view'] = true;
        
        ob_start();
        wc_get_template_part('content', 'quick-view-product');
        $html = ob_get_clean();
        
        wp_send_json_success(['html' => $html]);
    }
    
    /**
     * Remove default WooCommerce actions
     */
    private function remove_default_actions() {
        // Remove breadcrumbs (we'll add our own)
        remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);
        
        // Remove result count and ordering from default position
        remove_action('woocommerce_before_shop_loop', 'woocommerce_output_all_notices', 10);
        remove_action('woocommerce_before_shop_loop', 'woocommerce_result_count', 20);
        remove_action('woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30);
        
        // Remove default product loop hooks
        remove_action('woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10);
        remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5);
        remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);
        
        // Remove default single product hooks
        remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);
        remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);
        
        // Add them back in different positions
        add_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 25);
        add_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);
    }
    
    /**
     * Add custom WooCommerce actions
     */
    private function add_custom_actions() {
        // Add custom breadcrumbs
        add_action('woocommerce_before_main_content', [$this, 'custom_breadcrumbs'], 20);
        
        // Add mobile filters toggle
        add_action('woocommerce_sidebar', [$this, 'mobile_filters_modal'], 5);
        
        // Add product schema
        add_action('woocommerce_single_product_summary', [$this, 'product_schema'], 5);
        
        // Add recently viewed products
        add_action('woocommerce_after_single_product_summary', [$this, 'recently_viewed_products'], 25);
        
        // Add related products with custom styling
        remove_action('woocommerce_output_related_products', 'woocommerce_output_related_products', 20);
        add_action('woocommerce_after_single_product_summary', [$this, 'custom_related_products'], 20);
    }
    
    /**
     * Add custom breadcrumbs
     */
    public function custom_breadcrumbs() {
        if (!is_woocommerce() && !is_cart() && !is_checkout() && !is_account_page()) {
            return;
        }
        
        echo '<div class="woocommerce-breadcrumbs py-4 bg-gray-50 dark:bg-gray-800">';
        echo '<div class="container mx-auto px-4">';
        aqualuxe_breadcrumbs();
        echo '</div>';
        echo '</div>';
    }
    
    /**
     * Add mobile filters modal
     */
    public function mobile_filters_modal() {
        if (is_shop() || is_product_category() || is_product_tag()) {
            echo '<div id="shop-sidebar" class="shop-sidebar-modal fixed inset-0 z-50 bg-black bg-opacity-50 hidden lg:hidden">';
            echo '<div class="sidebar-content bg-white dark:bg-gray-900 w-80 h-full overflow-y-auto p-6">';
            echo '<div class="sidebar-header flex justify-between items-center mb-6">';
            echo '<h3 class="text-lg font-semibold">' . __('Filters', 'aqualuxe') . '</h3>';
            echo '<button class="sidebar-close text-gray-500 hover:text-gray-700">';
            echo '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">';
            echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>';
            echo '</svg>';
            echo '</button>';
            echo '</div>';
            
            if (is_active_sidebar('shop-sidebar')) {
                dynamic_sidebar('shop-sidebar');
            }
            
            echo '</div>';
            echo '</div>';
        }
    }
    
    /**
     * Add product schema markup
     */
    public function product_schema() {
        global $product;
        
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Product',
            'name' => $product->get_name(),
            'description' => $product->get_short_description() ?: $product->get_description(),
            'sku' => $product->get_sku(),
            'image' => wp_get_attachment_image_url($product->get_image_id(), 'large'),
            'offers' => [
                '@type' => 'Offer',
                'price' => $product->get_price(),
                'priceCurrency' => get_woocommerce_currency(),
                'availability' => $product->is_in_stock() ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
                'seller' => [
                    '@type' => 'Organization',
                    'name' => get_bloginfo('name'),
                ],
            ],
        ];
        
        if ($product->get_rating_count() > 0) {
            $schema['aggregateRating'] = [
                '@type' => 'AggregateRating',
                'ratingValue' => $product->get_average_rating(),
                'reviewCount' => $product->get_review_count(),
            ];
        }
        
        echo '<script type="application/ld+json">' . wp_json_encode($schema) . '</script>';
    }
    
    /**
     * Display recently viewed products
     */
    public function recently_viewed_products() {
        // This would integrate with a recently viewed products plugin or custom implementation
        // For now, we'll show a placeholder
        echo '<div class="recently-viewed-products mt-12">';
        echo '<h3 class="text-2xl font-bold mb-6">' . __('Recently Viewed', 'aqualuxe') . '</h3>';
        echo '<div class="recently-viewed-placeholder text-center py-8 text-gray-500">';
        echo __('Recently viewed products will appear here.', 'aqualuxe');
        echo '</div>';
        echo '</div>';
    }
    
    /**
     * Custom related products display
     */
    public function custom_related_products() {
        global $product;
        
        $related_ids = wc_get_related_products($product->get_id(), 4);
        
        if (empty($related_ids)) {
            return;
        }
        
        echo '<div class="related-products mt-12">';
        echo '<h3 class="text-2xl font-bold mb-6">' . __('Related Products', 'aqualuxe') . '</h3>';
        echo '<div class="grid grid-cols-2 md:grid-cols-4 gap-6">';
        
        foreach ($related_ids as $related_id) {
            $related_product = wc_get_product($related_id);
            if (!$related_product) continue;
            
            echo '<div class="related-product">';
            echo '<a href="' . esc_url(get_permalink($related_id)) . '" class="block">';
            echo get_the_post_thumbnail($related_id, 'woocommerce_thumbnail', ['class' => 'w-full h-48 object-cover rounded-lg mb-3']);
            echo '<h4 class="font-semibold mb-1">' . esc_html($related_product->get_name()) . '</h4>';
            echo '<div class="price text-primary-600 font-bold">' . $related_product->get_price_html() . '</div>';
            echo '</a>';
            echo '</div>';
        }
        
        echo '</div>';
        echo '</div>';
    }
}
