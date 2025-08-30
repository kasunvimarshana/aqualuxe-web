<?php
/**
 * WooCommerce Integration Class
 * Handles all WooCommerce related functionality and customizations
 *
 * @package AquaLuxe
 * @version 1.0.0
 */

defined('ABSPATH') || exit;

class AquaLuxe_WooCommerce {
    
    /**
     * Constructor
     */
    public function __construct() {
        // Theme support
        add_action('after_setup_theme', [$this, 'setup_woocommerce_support']);
        
        // Template modifications
        add_action('init', [$this, 'remove_default_hooks']);
        add_action('init', [$this, 'add_custom_hooks']);
        
        // Product customizations
        add_filter('woocommerce_product_tabs', [$this, 'customize_product_tabs']);
        add_filter('woocommerce_single_product_image_thumbnail_html', [$this, 'customize_product_thumbnails'], 10, 2);
        
        // Shop customizations
        add_filter('loop_shop_columns', [$this, 'shop_columns']);
        add_filter('loop_shop_per_page', [$this, 'shop_per_page']);
        
        // Cart customizations
        add_filter('woocommerce_add_to_cart_fragments', [$this, 'cart_fragments']);
        
        // Checkout customizations
        add_filter('woocommerce_checkout_fields', [$this, 'customize_checkout_fields']);
        
        // Email customizations
        add_action('woocommerce_email_header', [$this, 'email_header_logo']);
        
        // Ajax handlers
        add_action('wp_ajax_aqualuxe_quick_view', [$this, 'ajax_quick_view']);
        add_action('wp_ajax_nopriv_aqualuxe_quick_view', [$this, 'ajax_quick_view']);
        
        // Account customizations
        add_filter('woocommerce_account_menu_items', [$this, 'customize_account_menu']);
        
        // Performance optimizations
        add_action('wp_enqueue_scripts', [$this, 'optimize_woocommerce_scripts']);
        
        // Custom product types
        add_action('woocommerce_product_options_general_product_data', [$this, 'add_custom_product_fields']);
        add_action('woocommerce_process_product_meta', [$this, 'save_custom_product_fields']);
    }
    
    /**
     * Setup WooCommerce theme support
     */
    public function setup_woocommerce_support() {
        // Basic WooCommerce support
        add_theme_support('woocommerce');
        add_theme_support('wc-product-gallery-zoom');
        add_theme_support('wc-product-gallery-lightbox');
        add_theme_support('wc-product-gallery-slider');
        
        // Image sizes
        add_theme_support('woocommerce', [
            'thumbnail_image_width' => 400,
            'single_image_width' => 600,
            'product_grid' => [
                'default_rows' => 3,
                'min_rows' => 2,
                'max_rows' => 8,
                'default_columns' => 4,
                'min_columns' => 2,
                'max_columns' => 6,
            ],
        ]);
        
        // Custom image sizes
        add_image_size('aqualuxe-product-thumb', 400, 400, true);
        add_image_size('aqualuxe-product-single', 600, 600, true);
        add_image_size('aqualuxe-product-gallery', 100, 100, true);
    }
    
    /**
     * Remove default WooCommerce hooks
     */
    public function remove_default_hooks() {
        // Remove default WooCommerce breadcrumbs (we'll use our own)
        remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);
        
        // Remove default product meta
        remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40);
        
        // Remove default related products (we'll add them back with custom styling)
        remove_action('woocommerce_output_related_products', 'woocommerce_output_related_products', 20);
        
        // Remove default cross-sells on cart
        remove_action('woocommerce_cart_collaterals', 'woocommerce_cross_sell_display');
    }
    
    /**
     * Add custom WooCommerce hooks
     */
    public function add_custom_hooks() {
        // Add custom product meta
        add_action('woocommerce_single_product_summary', [$this, 'custom_product_meta'], 35);
        
        // Add custom related products
        add_action('woocommerce_after_single_product_summary', [$this, 'custom_related_products'], 25);
        
        // Add quick view button
        add_action('woocommerce_after_shop_loop_item', [$this, 'add_quick_view_button'], 15);
        
        // Add wishlist button
        add_action('woocommerce_after_shop_loop_item', [$this, 'add_wishlist_button'], 16);
        
        // Add product badges
        add_action('woocommerce_before_shop_loop_item_title', [$this, 'add_product_badges'], 5);
        
        // Add product countdown for sales
        add_action('woocommerce_single_product_summary', [$this, 'add_sale_countdown'], 25);
        
        // Add custom cart cross-sells
        add_action('woocommerce_cart_collaterals', [$this, 'custom_cross_sell_display']);
        
        // Add product inquiry form
        add_action('woocommerce_single_product_summary', [$this, 'add_product_inquiry_button'], 45);
    }
    
    /**
     * Customize product tabs
     */
    public function customize_product_tabs($tabs) {
        // Reorder tabs
        if (isset($tabs['description'])) {
            $tabs['description']['priority'] = 10;
            $tabs['description']['title'] = __('Description', 'aqualuxe');
        }
        
        if (isset($tabs['additional_information'])) {
            $tabs['additional_information']['priority'] = 20;
            $tabs['additional_information']['title'] = __('Specifications', 'aqualuxe');
        }
        
        if (isset($tabs['reviews'])) {
            $tabs['reviews']['priority'] = 30;
        }
        
        // Add care instructions tab
        $tabs['care_instructions'] = [
            'title' => __('Care Instructions', 'aqualuxe'),
            'priority' => 25,
            'callback' => [$this, 'care_instructions_tab_content']
        ];
        
        // Add compatibility tab
        $tabs['compatibility'] = [
            'title' => __('Compatibility', 'aqualuxe'),
            'priority' => 27,
            'callback' => [$this, 'compatibility_tab_content']
        ];
        
        return $tabs;
    }
    
    /**
     * Care instructions tab content
     */
    public function care_instructions_tab_content() {
        global $product;
        
        $care_instructions = get_post_meta($product->get_id(), '_care_instructions', true);
        
        if ($care_instructions) {
            echo '<div class="care-instructions-content prose max-w-none">';
            echo wp_kses_post(wpautop($care_instructions));
            echo '</div>';
        } else {
            echo '<p>' . __('No specific care instructions for this product.', 'aqualuxe') . '</p>';
        }
    }
    
    /**
     * Compatibility tab content
     */
    public function compatibility_tab_content() {
        global $product;
        
        $compatibility = get_post_meta($product->get_id(), '_compatibility_info', true);
        
        if ($compatibility) {
            echo '<div class="compatibility-content prose max-w-none">';
            echo wp_kses_post(wpautop($compatibility));
            echo '</div>';
        } else {
            echo '<p>' . __('Universal compatibility - suitable for most aquarium setups.', 'aqualuxe') . '</p>';
        }
    }
    
    /**
     * Set shop columns
     */
    public function shop_columns() {
        return get_theme_mod('shop_columns', 3);
    }
    
    /**
     * Set products per page
     */
    public function shop_per_page() {
        return get_theme_mod('shop_per_page', 12);
    }
    
    /**
     * Cart fragments for AJAX
     */
    public function cart_fragments($fragments) {
        ob_start();
        ?>
        <span class="cart-count"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
        <?php
        $fragments['.cart-count'] = ob_get_clean();
        
        ob_start();
        ?>
        <span class="cart-total"><?php echo WC()->cart->get_cart_subtotal(); ?></span>
        <?php
        $fragments['.cart-total'] = ob_get_clean();
        
        return $fragments;
    }
    
    /**
     * Customize checkout fields
     */
    public function customize_checkout_fields($fields) {
        // Reorder billing fields
        $billing_fields_order = [
            'billing_first_name',
            'billing_last_name',
            'billing_email',
            'billing_phone',
            'billing_company',
            'billing_country',
            'billing_address_1',
            'billing_address_2',
            'billing_city',
            'billing_state',
            'billing_postcode'
        ];
        
        foreach ($billing_fields_order as $index => $field) {
            if (isset($fields['billing'][$field])) {
                $fields['billing'][$field]['priority'] = ($index + 1) * 10;
            }
        }
        
        // Add custom fields
        $fields['billing']['billing_aquarium_type'] = [
            'label' => __('Aquarium Type', 'aqualuxe'),
            'placeholder' => __('e.g., Freshwater, Saltwater, Reef', 'aqualuxe'),
            'required' => false,
            'class' => ['form-row-wide'],
            'priority' => 200
        ];
        
        return $fields;
    }
    
    /**
     * Custom product meta
     */
    public function custom_product_meta() {
        global $product;
        
        echo '<div class="custom-product-meta">';
        
        // Product SKU
        if (wc_product_sku_enabled() && ($product->get_sku() || $product->is_type('variable'))) {
            echo '<span class="sku-wrapper">SKU: <span class="sku">' . ($product->get_sku() ? $product->get_sku() : __('N/A', 'aqualuxe')) . '</span></span>';
        }
        
        // Product categories
        $categories = get_the_terms($product->get_id(), 'product_cat');
        if ($categories && !is_wp_error($categories)) {
            echo '<span class="posted-in">Categories: ';
            $category_links = [];
            foreach ($categories as $category) {
                $category_links[] = '<a href="' . esc_url(get_term_link($category)) . '">' . esc_html($category->name) . '</a>';
            }
            echo implode(', ', $category_links);
            echo '</span>';
        }
        
        // Product tags
        $tags = get_the_terms($product->get_id(), 'product_tag');
        if ($tags && !is_wp_error($tags)) {
            echo '<span class="tagged-as">Tags: ';
            $tag_links = [];
            foreach ($tags as $tag) {
                $tag_links[] = '<a href="' . esc_url(get_term_link($tag)) . '">' . esc_html($tag->name) . '</a>';
            }
            echo implode(', ', $tag_links);
            echo '</span>';
        }
        
        echo '</div>';
    }
    
    /**
     * Add quick view button
     */
    public function add_quick_view_button() {
        global $product;
        
        echo '<div class="quick-view-wrapper">';
        echo '<a href="#" class="quick-view-button btn btn-outline btn-sm" data-product-id="' . $product->get_id() . '">';
        echo '<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">';
        echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>';
        echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>';
        echo '</svg>';
        echo __('Quick View', 'aqualuxe');
        echo '</a>';
        echo '</div>';
    }
    
    /**
     * Add wishlist button
     */
    public function add_wishlist_button() {
        global $product;
        
        echo '<div class="wishlist-wrapper">';
        echo '<a href="#" class="wishlist-button btn btn-outline btn-sm" data-product-id="' . $product->get_id() . '">';
        echo '<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">';
        echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>';
        echo '</svg>';
        echo __('Wishlist', 'aqualuxe');
        echo '</a>';
        echo '</div>';
    }
    
    /**
     * Add product badges
     */
    public function add_product_badges() {
        global $product;
        
        echo '<div class="product-badges">';
        
        // Sale badge
        if ($product->is_on_sale()) {
            $percentage = '';
            if ($product->get_regular_price() && $product->get_sale_price()) {
                $percentage = round((($product->get_regular_price() - $product->get_sale_price()) / $product->get_regular_price()) * 100);
                $percentage = '-' . $percentage . '%';
            }
            echo '<span class="badge sale-badge">' . ($percentage ? $percentage : __('Sale', 'aqualuxe')) . '</span>';
        }
        
        // New badge
        $new_days = get_theme_mod('product_new_days', 30);
        if ((time() - strtotime($product->get_date_created())) < (60 * 60 * 24 * $new_days)) {
            echo '<span class="badge new-badge">' . __('New', 'aqualuxe') . '</span>';
        }
        
        // Out of stock badge
        if (!$product->is_in_stock()) {
            echo '<span class="badge out-of-stock-badge">' . __('Out of Stock', 'aqualuxe') . '</span>';
        }
        
        // Custom badges
        $custom_badge = get_post_meta($product->get_id(), '_custom_badge', true);
        if ($custom_badge) {
            echo '<span class="badge custom-badge">' . esc_html($custom_badge) . '</span>';
        }
        
        echo '</div>';
    }
    
    /**
     * Add sale countdown
     */
    public function add_sale_countdown() {
        global $product;
        
        if ($product->is_on_sale()) {
            $sale_end = get_post_meta($product->get_id(), '_sale_price_dates_to', true);
            
            if ($sale_end) {
                echo '<div class="sale-countdown" data-end-date="' . esc_attr($sale_end) . '">';
                echo '<h4>' . __('Sale ends in:', 'aqualuxe') . '</h4>';
                echo '<div class="countdown-timer"></div>';
                echo '</div>';
            }
        }
    }
    
    /**
     * Ajax quick view handler
     */
    public function ajax_quick_view() {
        if (!wp_verify_nonce($_POST['nonce'], 'aqualuxe_nonce')) {
            wp_die();
        }
        
        $product_id = intval($_POST['product_id']);
        $product = wc_get_product($product_id);
        
        if (!$product) {
            wp_die();
        }
        
        // Load quick view template
        global $woocommerce_loop;
        $woocommerce_loop['quick_view'] = true;
        
        ob_start();
        wc_get_template('single-product/quick-view.php', ['product' => $product]);
        $html = ob_get_clean();
        
        wp_send_json_success(['html' => $html]);
    }
    
    /**
     * Add custom product fields
     */
    public function add_custom_product_fields() {
        echo '<div class="options_group">';
        
        // Care instructions
        woocommerce_wp_textarea_input([
            'id' => '_care_instructions',
            'label' => __('Care Instructions', 'aqualuxe'),
            'description' => __('Enter care instructions for this product.', 'aqualuxe'),
            'desc_tip' => true,
        ]);
        
        // Compatibility info
        woocommerce_wp_textarea_input([
            'id' => '_compatibility_info',
            'label' => __('Compatibility Information', 'aqualuxe'),
            'description' => __('Enter compatibility information for this product.', 'aqualuxe'),
            'desc_tip' => true,
        ]);
        
        // Custom badge
        woocommerce_wp_text_input([
            'id' => '_custom_badge',
            'label' => __('Custom Badge', 'aqualuxe'),
            'description' => __('Enter a custom badge text for this product.', 'aqualuxe'),
            'desc_tip' => true,
        ]);
        
        echo '</div>';
    }
    
    /**
     * Save custom product fields
     */
    public function save_custom_product_fields($post_id) {
        // Care instructions
        if (isset($_POST['_care_instructions'])) {
            update_post_meta($post_id, '_care_instructions', sanitize_textarea_field($_POST['_care_instructions']));
        }
        
        // Compatibility info
        if (isset($_POST['_compatibility_info'])) {
            update_post_meta($post_id, '_compatibility_info', sanitize_textarea_field($_POST['_compatibility_info']));
        }
        
        // Custom badge
        if (isset($_POST['_custom_badge'])) {
            update_post_meta($post_id, '_custom_badge', sanitize_text_field($_POST['_custom_badge']));
        }
    }
    
    /**
     * Optimize WooCommerce scripts
     */
    public function optimize_woocommerce_scripts() {
        // Remove WooCommerce scripts on non-WooCommerce pages
        if (!is_woocommerce() && !is_cart() && !is_checkout() && !is_account_page()) {
            wp_dequeue_style('woocommerce-general');
            wp_dequeue_style('woocommerce-layout');
            wp_dequeue_style('woocommerce-smallscreen');
            wp_dequeue_script('wc-cart-fragments');
        }
    }
}

// Initialize WooCommerce integration
if (class_exists('WooCommerce')) {
    new AquaLuxe_WooCommerce();
}
