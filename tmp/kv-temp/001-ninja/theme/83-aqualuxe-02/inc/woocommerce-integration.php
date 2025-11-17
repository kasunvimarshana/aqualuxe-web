<?php
/**
 * AquaLuxe WooCommerce Integration
 * 
 * E-commerce functionality for ornamental fish business
 * Handles product management, pricing, shipping, and export features
 * 
 * @package AquaLuxe_Enterprise
 * @version 1.0.0
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * AquaLuxe WooCommerce Manager
 */
class AquaLuxe_WooCommerce {
    
    /**
     * Initialize WooCommerce features
     */
    public static function init() {
        // Theme support
        add_action('after_setup_theme', array(__CLASS__, 'woocommerce_support'));
        
        // Product customizations
        add_action('woocommerce_single_product_summary', array(__CLASS__, 'add_fish_care_info'), 25);
        add_action('woocommerce_single_product_summary', array(__CLASS__, 'add_export_info'), 30);
        
        // Custom product fields
        add_action('woocommerce_product_options_general_product_data', array(__CLASS__, 'add_custom_product_fields'));
        add_action('woocommerce_process_product_meta', array(__CLASS__, 'save_custom_product_fields'));
        
        // Checkout customizations
        add_action('woocommerce_checkout_fields', array(__CLASS__, 'custom_checkout_fields'));
        
        // Order customizations
        add_action('woocommerce_thankyou', array(__CLASS__, 'custom_thank_you_message'));
        
        // Shop customizations
        add_action('woocommerce_shop_loop_item_title', array(__CLASS__, 'add_product_badges'), 5);
        
        // Cart customizations
        add_filter('woocommerce_add_to_cart_fragments', array(__CLASS__, 'cart_count_fragments'));
        
        // Pricing customizations
        add_filter('woocommerce_get_price_html', array(__CLASS__, 'custom_price_html'), 10, 2);
        
        // Shipping customizations
        add_filter('woocommerce_package_rates', array(__CLASS__, 'custom_shipping_rates'), 10, 2);
        
        // Export functionality
        add_action('wp_ajax_request_export_quote', array(__CLASS__, 'handle_export_quote'));
        add_action('wp_ajax_nopriv_request_export_quote', array(__CLASS__, 'handle_export_quote'));
    }
    
    /**
     * Add WooCommerce theme support
     */
    public static function woocommerce_support() {
        add_theme_support('woocommerce');
        add_theme_support('wc-product-gallery-zoom');
        add_theme_support('wc-product-gallery-lightbox');
        add_theme_support('wc-product-gallery-slider');
        
        // Custom image sizes for products
        add_image_size('aqualuxe_product_thumb', 300, 300, true);
        add_image_size('aqualuxe_product_medium', 600, 600, true);
        add_image_size('aqualuxe_product_large', 1200, 1200, true);
    }
    
    /**
     * Add fish care information to product page
     */
    public static function add_fish_care_info() {
        global $product;
        
        $care_level = get_post_meta($product->get_id(), '_aqualuxe_care_level', true);
        $tank_size = get_post_meta($product->get_id(), '_aqualuxe_tank_size', true);
        $water_type = get_post_meta($product->get_id(), '_aqualuxe_water_type', true);
        $temperature = get_post_meta($product->get_id(), '_aqualuxe_temperature', true);
        $ph_level = get_post_meta($product->get_id(), '_aqualuxe_ph_level', true);
        
        if ($care_level || $tank_size || $water_type || $temperature || $ph_level) {
            echo '<div class="aqualuxe-care-info">';
            echo '<h3>' . esc_html__('Care Information', 'aqualuxe-enterprise') . '</h3>';
            echo '<div class="care-info-grid">';
            
            if ($care_level) {
                echo '<div class="care-item">';
                echo '<span class="care-label">' . esc_html__('Care Level:', 'aqualuxe-enterprise') . '</span>';
                echo '<span class="care-value">' . esc_html($care_level) . '</span>';
                echo '</div>';
            }
            
            if ($tank_size) {
                echo '<div class="care-item">';
                echo '<span class="care-label">' . esc_html__('Min. Tank Size:', 'aqualuxe-enterprise') . '</span>';
                echo '<span class="care-value">' . esc_html($tank_size) . '</span>';
                echo '</div>';
            }
            
            if ($water_type) {
                echo '<div class="care-item">';
                echo '<span class="care-label">' . esc_html__('Water Type:', 'aqualuxe-enterprise') . '</span>';
                echo '<span class="care-value">' . esc_html($water_type) . '</span>';
                echo '</div>';
            }
            
            if ($temperature) {
                echo '<div class="care-item">';
                echo '<span class="care-label">' . esc_html__('Temperature:', 'aqualuxe-enterprise') . '</span>';
                echo '<span class="care-value">' . esc_html($temperature) . '</span>';
                echo '</div>';
            }
            
            if ($ph_level) {
                echo '<div class="care-item">';
                echo '<span class="care-label">' . esc_html__('pH Level:', 'aqualuxe-enterprise') . '</span>';
                echo '<span class="care-value">' . esc_html($ph_level) . '</span>';
                echo '</div>';
            }
            
            echo '</div>';
            echo '</div>';
        }
    }
    
    /**
     * Add export information to product page
     */
    public static function add_export_info() {
        global $product;
        
        $exportable = get_post_meta($product->get_id(), '_aqualuxe_exportable', true);
        $cites_required = get_post_meta($product->get_id(), '_aqualuxe_cites_required', true);
        $quarantine_period = get_post_meta($product->get_id(), '_aqualuxe_quarantine_period', true);
        
        if ($exportable === 'yes') {
            echo '<div class="aqualuxe-export-info">';
            echo '<h3>' . esc_html__('Export Information', 'aqualuxe-enterprise') . '</h3>';
            echo '<div class="export-badges">';
            echo '<span class="export-badge available">' . esc_html__('Available for Export', 'aqualuxe-enterprise') . '</span>';
            
            if ($cites_required === 'yes') {
                echo '<span class="export-badge cites">' . esc_html__('CITES Required', 'aqualuxe-enterprise') . '</span>';
            }
            
            if ($quarantine_period) {
                echo '<span class="export-badge quarantine">' . sprintf(esc_html__('Quarantine: %s days', 'aqualuxe-enterprise'), $quarantine_period) . '</span>';
            }
            
            echo '</div>';
            echo '<button type="button" class="btn btn-outline-primary request-export-quote" data-product-id="' . $product->get_id() . '">';
            echo esc_html__('Request Export Quote', 'aqualuxe-enterprise');
            echo '</button>';
            echo '</div>';
        }
    }
    
    /**
     * Add custom product fields to admin
     */
    public static function add_custom_product_fields() {
        global $post;
        
        echo '<div class="options_group">';
        
        // Care Level
        woocommerce_wp_select(array(
            'id' => '_aqualuxe_care_level',
            'label' => __('Care Level', 'aqualuxe-enterprise'),
            'options' => array(
                '' => __('Select care level', 'aqualuxe-enterprise'),
                'beginner' => __('Beginner', 'aqualuxe-enterprise'),
                'intermediate' => __('Intermediate', 'aqualuxe-enterprise'),
                'advanced' => __('Advanced', 'aqualuxe-enterprise'),
                'expert' => __('Expert', 'aqualuxe-enterprise')
            )
        ));
        
        // Tank Size
        woocommerce_wp_text_input(array(
            'id' => '_aqualuxe_tank_size',
            'label' => __('Minimum Tank Size', 'aqualuxe-enterprise'),
            'placeholder' => '20 gallons',
            'desc_tip' => true,
            'description' => __('Minimum recommended tank size for this fish', 'aqualuxe-enterprise')
        ));
        
        // Water Type
        woocommerce_wp_select(array(
            'id' => '_aqualuxe_water_type',
            'label' => __('Water Type', 'aqualuxe-enterprise'),
            'options' => array(
                '' => __('Select water type', 'aqualuxe-enterprise'),
                'freshwater' => __('Freshwater', 'aqualuxe-enterprise'),
                'saltwater' => __('Saltwater', 'aqualuxe-enterprise'),
                'brackish' => __('Brackish', 'aqualuxe-enterprise')
            )
        ));
        
        // Temperature Range
        woocommerce_wp_text_input(array(
            'id' => '_aqualuxe_temperature',
            'label' => __('Temperature Range', 'aqualuxe-enterprise'),
            'placeholder' => '72-78°F',
            'desc_tip' => true,
            'description' => __('Optimal temperature range for this fish', 'aqualuxe-enterprise')
        ));
        
        // pH Level
        woocommerce_wp_text_input(array(
            'id' => '_aqualuxe_ph_level',
            'label' => __('pH Level', 'aqualuxe-enterprise'),
            'placeholder' => '6.5-7.5',
            'desc_tip' => true,
            'description' => __('Optimal pH range for this fish', 'aqualuxe-enterprise')
        ));
        
        // Exportable
        woocommerce_wp_checkbox(array(
            'id' => '_aqualuxe_exportable',
            'label' => __('Available for Export', 'aqualuxe-enterprise'),
            'description' => __('Check if this product can be exported internationally', 'aqualuxe-enterprise')
        ));
        
        // CITES Required
        woocommerce_wp_checkbox(array(
            'id' => '_aqualuxe_cites_required',
            'label' => __('CITES Required', 'aqualuxe-enterprise'),
            'description' => __('Check if CITES documentation is required for export', 'aqualuxe-enterprise')
        ));
        
        // Quarantine Period
        woocommerce_wp_text_input(array(
            'id' => '_aqualuxe_quarantine_period',
            'label' => __('Quarantine Period (days)', 'aqualuxe-enterprise'),
            'type' => 'number',
            'desc_tip' => true,
            'description' => __('Number of days required for quarantine before export', 'aqualuxe-enterprise')
        ));
        
        echo '</div>';
    }
    
    /**
     * Save custom product fields
     */
    public static function save_custom_product_fields($post_id) {
        $fields = array(
            '_aqualuxe_care_level',
            '_aqualuxe_tank_size',
            '_aqualuxe_water_type',
            '_aqualuxe_temperature',
            '_aqualuxe_ph_level',
            '_aqualuxe_exportable',
            '_aqualuxe_cites_required',
            '_aqualuxe_quarantine_period'
        );
        
        foreach ($fields as $field) {
            if (isset($_POST[$field])) {
                update_post_meta($post_id, $field, sanitize_text_field($_POST[$field]));
            }
        }
    }
    
    /**
     * Add custom checkout fields
     */
    public static function custom_checkout_fields($fields) {
        // Add export-related fields
        $fields['billing']['billing_export_purpose'] = array(
            'type' => 'select',
            'label' => __('Purpose of Purchase', 'aqualuxe-enterprise'),
            'required' => false,
            'options' => array(
                '' => __('Select purpose', 'aqualuxe-enterprise'),
                'personal' => __('Personal/Hobby', 'aqualuxe-enterprise'),
                'business' => __('Business/Retail', 'aqualuxe-enterprise'),
                'breeding' => __('Breeding Program', 'aqualuxe-enterprise'),
                'research' => __('Research/Educational', 'aqualuxe-enterprise'),
                'export' => __('Export/Resale', 'aqualuxe-enterprise')
            )
        );
        
        $fields['billing']['billing_aquarium_experience'] = array(
            'type' => 'select',
            'label' => __('Aquarium Experience Level', 'aqualuxe-enterprise'),
            'required' => false,
            'options' => array(
                '' => __('Select experience', 'aqualuxe-enterprise'),
                'beginner' => __('Beginner (0-1 years)', 'aqualuxe-enterprise'),
                'intermediate' => __('Intermediate (2-5 years)', 'aqualuxe-enterprise'),
                'advanced' => __('Advanced (5+ years)', 'aqualuxe-enterprise'),
                'expert' => __('Expert/Professional', 'aqualuxe-enterprise')
            )
        );
        
        return $fields;
    }
    
    /**
     * Custom thank you message
     */
    public static function custom_thank_you_message($order_id) {
        $order = wc_get_order($order_id);
        
        echo '<div class="aqualuxe-thank-you">';
        echo '<h3>' . esc_html__('Thank You for Your AquaLuxe Purchase!', 'aqualuxe-enterprise') . '</h3>';
        echo '<p>' . esc_html__('Your order is being prepared with the utmost care. Here\'s what happens next:', 'aqualuxe-enterprise') . '</p>';
        
        echo '<div class="order-steps">';
        echo '<div class="step">';
        echo '<div class="step-number">1</div>';
        echo '<div class="step-content">';
        echo '<h4>' . esc_html__('Order Processing', 'aqualuxe-enterprise') . '</h4>';
        echo '<p>' . esc_html__('We\'ll carefully select and prepare your fish and products.', 'aqualuxe-enterprise') . '</p>';
        echo '</div>';
        echo '</div>';
        
        echo '<div class="step">';
        echo '<div class="step-number">2</div>';
        echo '<div class="step-content">';
        echo '<h4>' . esc_html__('Quality Check', 'aqualuxe-enterprise') . '</h4>';
        echo '<p>' . esc_html__('All livestock undergoes health inspection and quarantine if needed.', 'aqualuxe-enterprise') . '</p>';
        echo '</div>';
        echo '</div>';
        
        echo '<div class="step">';
        echo '<div class="step-number">3</div>';
        echo '<div class="step-content">';
        echo '<h4>' . esc_html__('Secure Shipping', 'aqualuxe-enterprise') . '</h4>';
        echo '<p>' . esc_html__('Your order will be packaged and shipped with temperature control.', 'aqualuxe-enterprise') . '</p>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
        
        // Add care instructions download
        echo '<div class="care-instructions">';
        echo '<h4>' . esc_html__('Free Care Instructions', 'aqualuxe-enterprise') . '</h4>';
        echo '<p>' . esc_html__('Download our comprehensive care guide for your new aquatic friends.', 'aqualuxe-enterprise') . '</p>';
        echo '<a href="/care-guide.pdf" class="btn btn-primary" download>' . esc_html__('Download Care Guide', 'aqualuxe-enterprise') . '</a>';
        echo '</div>';
        
        echo '</div>';
    }
    
    /**
     * Add product badges in shop loop
     */
    public static function add_product_badges() {
        global $product;
        
        $badges = array();
        
        // New product badge
        $created = get_the_time('U');
        if ((time() - $created) < (30 * DAY_IN_SECONDS)) {
            $badges[] = '<span class="product-badge new">' . esc_html__('New', 'aqualuxe-enterprise') . '</span>';
        }
        
        // Sale badge
        if ($product->is_on_sale()) {
            $badges[] = '<span class="product-badge sale">' . esc_html__('Sale', 'aqualuxe-enterprise') . '</span>';
        }
        
        // Export available badge
        if (get_post_meta($product->get_id(), '_aqualuxe_exportable', true) === 'yes') {
            $badges[] = '<span class="product-badge export">' . esc_html__('Export Available', 'aqualuxe-enterprise') . '</span>';
        }
        
        // Care level badge
        $care_level = get_post_meta($product->get_id(), '_aqualuxe_care_level', true);
        if ($care_level) {
            $badges[] = '<span class="product-badge care-' . esc_attr($care_level) . '">' . esc_html(ucfirst($care_level)) . '</span>';
        }
        
        if (!empty($badges)) {
            echo '<div class="product-badges">' . implode('', $badges) . '</div>';
        }
    }
    
    /**
     * Cart count fragments for AJAX
     */
    public static function cart_count_fragments($fragments) {
        $fragments['.cart-count'] = '<span class="cart-count">' . WC()->cart->get_cart_contents_count() . '</span>';
        return $fragments;
    }
    
    /**
     * Custom price HTML formatting
     */
    public static function custom_price_html($price, $product) {
        // Add currency conversion for international customers
        if (function_exists('aqualuxe_get_customer_currency')) {
            $customer_currency = aqualuxe_get_customer_currency();
            if ($customer_currency !== get_woocommerce_currency()) {
                $converted_price = aqualuxe_convert_currency($product->get_price(), get_woocommerce_currency(), $customer_currency);
                $price .= '<span class="converted-price">(' . wc_price($converted_price, array('currency' => $customer_currency)) . ')</span>';
            }
        }
        
        return $price;
    }
    
    /**
     * Custom shipping rates for livestock
     */
    public static function custom_shipping_rates($rates, $package) {
        $has_livestock = false;
        
        foreach ($package['contents'] as $item) {
            $product = $item['data'];
            $categories = wp_get_post_terms($product->get_id(), 'product_cat', array('fields' => 'slugs'));
            
            if (in_array('livestock', $categories) || in_array('fish', $categories)) {
                $has_livestock = true;
                break;
            }
        }
        
        if ($has_livestock) {
            // Add special livestock shipping method
            $rates['aqualuxe_livestock_shipping'] = new WC_Shipping_Rate(
                'aqualuxe_livestock_shipping',
                __('Livestock Express Shipping', 'aqualuxe-enterprise'),
                25.00,
                array(),
                'aqualuxe_livestock_shipping'
            );
        }
        
        return $rates;
    }
    
    /**
     * Handle export quote requests
     */
    public static function handle_export_quote() {
        check_ajax_referer('aqualuxe_nonce', 'nonce');
        
        $product_id = intval($_POST['product_id']);
        $customer_email = sanitize_email($_POST['customer_email']);
        $destination_country = sanitize_text_field($_POST['destination_country']);
        $quantity = intval($_POST['quantity']);
        
        // Store export quote request
        $quote_data = array(
            'product_id' => $product_id,
            'customer_email' => $customer_email,
            'destination_country' => $destination_country,
            'quantity' => $quantity,
            'date_requested' => current_time('mysql'),
            'status' => 'pending'
        );
        
        // Save to database (you would create a custom table for this)
        // For now, we'll use post meta or a transient
        
        // Send notification email to admin
        $subject = sprintf(__('New Export Quote Request - Product #%d', 'aqualuxe-enterprise'), $product_id);
        $message = sprintf(
            __("New export quote request:\n\nProduct: %s\nCustomer: %s\nDestination: %s\nQuantity: %d\n\nPlease respond promptly.", 'aqualuxe-enterprise'),
            get_the_title($product_id),
            $customer_email,
            $destination_country,
            $quantity
        );
        
        wp_mail(get_option('admin_email'), $subject, $message);
        
        wp_send_json_success(array(
            'message' => __('Export quote request submitted successfully. We will contact you within 24 hours.', 'aqualuxe-enterprise')
        ));
    }
}

// Initialize WooCommerce integration
AquaLuxe_WooCommerce::init();
