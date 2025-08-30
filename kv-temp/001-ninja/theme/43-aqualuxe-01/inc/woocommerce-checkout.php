<?php
/**
 * WooCommerce Custom Checkout Fields
 *
 * @package AquaLuxe
 */

/**
 * Add custom checkout fields for aquatic products.
 *
 * @param array $fields Checkout fields.
 * @return array
 */
function aqualuxe_custom_checkout_fields($fields) {
    // Check if cart has aquatic products
    if (!aqualuxe_cart_has_aquatic_products()) {
        return $fields;
    }

    // Add custom fields to shipping section
    $fields['shipping']['shipping_aquarium_size'] = array(
        'label'       => __('Aquarium Size (gallons)', 'aqualuxe'),
        'placeholder' => __('e.g., 55', 'aqualuxe'),
        'required'    => false,
        'class'       => array('form-row-wide'),
        'clear'       => true,
        'priority'    => 110,
    );

    $fields['shipping']['shipping_water_type'] = array(
        'label'       => __('Water Type', 'aqualuxe'),
        'type'        => 'select',
        'options'     => array(
            ''          => __('Select water type', 'aqualuxe'),
            'freshwater' => __('Freshwater', 'aqualuxe'),
            'saltwater'  => __('Saltwater', 'aqualuxe'),
            'brackish'   => __('Brackish', 'aqualuxe'),
        ),
        'required'    => false,
        'class'       => array('form-row-wide'),
        'clear'       => true,
        'priority'    => 120,
    );

    // Add custom fields to order notes section
    $fields['order']['order_special_instructions'] = array(
        'type'        => 'textarea',
        'label'       => __('Special Care Instructions', 'aqualuxe'),
        'placeholder' => __('Any special instructions for handling or acclimating your aquatic species', 'aqualuxe'),
        'required'    => false,
        'class'       => array('notes'),
        'clear'       => true,
        'priority'    => 130,
    );

    // Add custom fields for delivery preferences
    $fields['shipping']['shipping_delivery_preference'] = array(
        'label'       => __('Delivery Preference', 'aqualuxe'),
        'type'        => 'select',
        'options'     => array(
            ''                => __('Select delivery preference', 'aqualuxe'),
            'standard'        => __('Standard Delivery', 'aqualuxe'),
            'temperature_controlled' => __('Temperature Controlled Delivery', 'aqualuxe'),
            'expedited'       => __('Expedited Delivery', 'aqualuxe'),
        ),
        'required'    => false,
        'class'       => array('form-row-wide'),
        'clear'       => true,
        'priority'    => 140,
    );

    return $fields;
}
add_filter('woocommerce_checkout_fields', 'aqualuxe_custom_checkout_fields');

/**
 * Check if cart has aquatic products.
 *
 * @return bool
 */
function aqualuxe_cart_has_aquatic_products() {
    if (!WC()->cart) {
        return false;
    }

    $has_aquatic = false;

    foreach (WC()->cart->get_cart() as $cart_item) {
        $product = $cart_item['data'];
        
        // Check if product is in aquatic category
        if (has_term('aquatic-species', 'product_cat', $product->get_id()) || 
            has_term('fish', 'product_cat', $product->get_id()) || 
            has_term('invertebrates', 'product_cat', $product->get_id()) || 
            has_term('plants', 'product_cat', $product->get_id())) {
            $has_aquatic = true;
            break;
        }
        
        // Check product tags
        if (has_term('live', 'product_tag', $product->get_id()) || 
            has_term('aquatic', 'product_tag', $product->get_id())) {
            $has_aquatic = true;
            break;
        }
    }

    return $has_aquatic;
}

/**
 * Display custom checkout fields in admin order page.
 *
 * @param WC_Order $order Order object.
 */
function aqualuxe_display_admin_order_meta($order) {
    // Aquarium Size
    $aquarium_size = get_post_meta($order->get_id(), '_shipping_aquarium_size', true);
    if (!empty($aquarium_size)) {
        echo '<p><strong>' . __('Aquarium Size:', 'aqualuxe') . '</strong> ' . esc_html($aquarium_size) . ' ' . __('gallons', 'aqualuxe') . '</p>';
    }

    // Water Type
    $water_type = get_post_meta($order->get_id(), '_shipping_water_type', true);
    if (!empty($water_type)) {
        $water_types = array(
            'freshwater' => __('Freshwater', 'aqualuxe'),
            'saltwater'  => __('Saltwater', 'aqualuxe'),
            'brackish'   => __('Brackish', 'aqualuxe'),
        );
        
        echo '<p><strong>' . __('Water Type:', 'aqualuxe') . '</strong> ' . esc_html($water_types[$water_type] ?? $water_type) . '</p>';
    }

    // Special Care Instructions
    $special_instructions = get_post_meta($order->get_id(), '_order_special_instructions', true);
    if (!empty($special_instructions)) {
        echo '<p><strong>' . __('Special Care Instructions:', 'aqualuxe') . '</strong> ' . esc_html($special_instructions) . '</p>';
    }

    // Delivery Preference
    $delivery_preference = get_post_meta($order->get_id(), '_shipping_delivery_preference', true);
    if (!empty($delivery_preference)) {
        $delivery_preferences = array(
            'standard'        => __('Standard Delivery', 'aqualuxe'),
            'temperature_controlled' => __('Temperature Controlled Delivery', 'aqualuxe'),
            'expedited'       => __('Expedited Delivery', 'aqualuxe'),
        );
        
        echo '<p><strong>' . __('Delivery Preference:', 'aqualuxe') . '</strong> ' . esc_html($delivery_preferences[$delivery_preference] ?? $delivery_preference) . '</p>';
    }
}
add_action('woocommerce_admin_order_data_after_shipping_address', 'aqualuxe_display_admin_order_meta', 10, 1);

/**
 * Display custom checkout fields in order emails.
 *
 * @param WC_Order $order Order object.
 * @param bool     $sent_to_admin Whether the email is sent to admin.
 * @param bool     $plain_text Whether the email is plain text.
 */
function aqualuxe_email_order_meta_fields($order, $sent_to_admin, $plain_text) {
    // Aquarium Size
    $aquarium_size = get_post_meta($order->get_id(), '_shipping_aquarium_size', true);
    if (!empty($aquarium_size)) {
        if ($plain_text) {
            echo __('Aquarium Size:', 'aqualuxe') . ' ' . $aquarium_size . ' ' . __('gallons', 'aqualuxe') . "\n";
        } else {
            echo '<p><strong>' . __('Aquarium Size:', 'aqualuxe') . '</strong> ' . esc_html($aquarium_size) . ' ' . __('gallons', 'aqualuxe') . '</p>';
        }
    }

    // Water Type
    $water_type = get_post_meta($order->get_id(), '_shipping_water_type', true);
    if (!empty($water_type)) {
        $water_types = array(
            'freshwater' => __('Freshwater', 'aqualuxe'),
            'saltwater'  => __('Saltwater', 'aqualuxe'),
            'brackish'   => __('Brackish', 'aqualuxe'),
        );
        
        if ($plain_text) {
            echo __('Water Type:', 'aqualuxe') . ' ' . ($water_types[$water_type] ?? $water_type) . "\n";
        } else {
            echo '<p><strong>' . __('Water Type:', 'aqualuxe') . '</strong> ' . esc_html($water_types[$water_type] ?? $water_type) . '</p>';
        }
    }

    // Special Care Instructions
    $special_instructions = get_post_meta($order->get_id(), '_order_special_instructions', true);
    if (!empty($special_instructions)) {
        if ($plain_text) {
            echo __('Special Care Instructions:', 'aqualuxe') . ' ' . $special_instructions . "\n";
        } else {
            echo '<p><strong>' . __('Special Care Instructions:', 'aqualuxe') . '</strong> ' . esc_html($special_instructions) . '</p>';
        }
    }

    // Delivery Preference
    $delivery_preference = get_post_meta($order->get_id(), '_shipping_delivery_preference', true);
    if (!empty($delivery_preference)) {
        $delivery_preferences = array(
            'standard'        => __('Standard Delivery', 'aqualuxe'),
            'temperature_controlled' => __('Temperature Controlled Delivery', 'aqualuxe'),
            'expedited'       => __('Expedited Delivery', 'aqualuxe'),
        );
        
        if ($plain_text) {
            echo __('Delivery Preference:', 'aqualuxe') . ' ' . ($delivery_preferences[$delivery_preference] ?? $delivery_preference) . "\n";
        } else {
            echo '<p><strong>' . __('Delivery Preference:', 'aqualuxe') . '</strong> ' . esc_html($delivery_preferences[$delivery_preference] ?? $delivery_preference) . '</p>';
        }
    }
}
add_action('woocommerce_email_order_meta', 'aqualuxe_email_order_meta_fields', 10, 3);

/**
 * Add custom checkout fields to order details page.
 *
 * @param WC_Order $order Order object.
 */
function aqualuxe_order_details_after_customer_details($order) {
    // Check if any custom fields are filled
    $aquarium_size = get_post_meta($order->get_id(), '_shipping_aquarium_size', true);
    $water_type = get_post_meta($order->get_id(), '_shipping_water_type', true);
    $special_instructions = get_post_meta($order->get_id(), '_order_special_instructions', true);
    $delivery_preference = get_post_meta($order->get_id(), '_shipping_delivery_preference', true);
    
    if (empty($aquarium_size) && empty($water_type) && empty($special_instructions) && empty($delivery_preference)) {
        return;
    }
    
    echo '<section class="woocommerce-aquatic-details">';
    echo '<h2 class="woocommerce-column__title">' . __('Aquatic Details', 'aqualuxe') . '</h2>';
    echo '<table class="woocommerce-table woocommerce-table--aquatic-details shop_table aquatic_details">';
    
    // Aquarium Size
    if (!empty($aquarium_size)) {
        echo '<tr>';
        echo '<th>' . __('Aquarium Size:', 'aqualuxe') . '</th>';
        echo '<td>' . esc_html($aquarium_size) . ' ' . __('gallons', 'aqualuxe') . '</td>';
        echo '</tr>';
    }
    
    // Water Type
    if (!empty($water_type)) {
        $water_types = array(
            'freshwater' => __('Freshwater', 'aqualuxe'),
            'saltwater'  => __('Saltwater', 'aqualuxe'),
            'brackish'   => __('Brackish', 'aqualuxe'),
        );
        
        echo '<tr>';
        echo '<th>' . __('Water Type:', 'aqualuxe') . '</th>';
        echo '<td>' . esc_html($water_types[$water_type] ?? $water_type) . '</td>';
        echo '</tr>';
    }
    
    // Special Care Instructions
    if (!empty($special_instructions)) {
        echo '<tr>';
        echo '<th>' . __('Special Care Instructions:', 'aqualuxe') . '</th>';
        echo '<td>' . esc_html($special_instructions) . '</td>';
        echo '</tr>';
    }
    
    // Delivery Preference
    if (!empty($delivery_preference)) {
        $delivery_preferences = array(
            'standard'        => __('Standard Delivery', 'aqualuxe'),
            'temperature_controlled' => __('Temperature Controlled Delivery', 'aqualuxe'),
            'expedited'       => __('Expedited Delivery', 'aqualuxe'),
        );
        
        echo '<tr>';
        echo '<th>' . __('Delivery Preference:', 'aqualuxe') . '</th>';
        echo '<td>' . esc_html($delivery_preferences[$delivery_preference] ?? $delivery_preference) . '</td>';
        echo '</tr>';
    }
    
    echo '</table>';
    echo '</section>';
}
add_action('woocommerce_order_details_after_customer_details', 'aqualuxe_order_details_after_customer_details', 10, 1);

/**
 * Add custom checkout fields validation.
 *
 * @param array $data Posted data.
 * @param WP_Error $errors Validation errors.
 */
function aqualuxe_checkout_field_validation($data, $errors) {
    // Only validate if cart has aquatic products
    if (!aqualuxe_cart_has_aquatic_products()) {
        return;
    }
    
    // Validate water type if aquarium size is provided
    if (!empty($data['shipping_aquarium_size']) && empty($data['shipping_water_type'])) {
        $errors->add('validation', __('Please select a water type for your aquarium.', 'aqualuxe'));
    }
    
    // Validate delivery preference for live aquatic products
    $has_live_aquatic = false;
    
    foreach (WC()->cart->get_cart() as $cart_item) {
        $product = $cart_item['data'];
        
        if (has_term('live', 'product_tag', $product->get_id())) {
            $has_live_aquatic = true;
            break;
        }
    }
    
    if ($has_live_aquatic && empty($data['shipping_delivery_preference'])) {
        $errors->add('validation', __('Please select a delivery preference for your live aquatic products.', 'aqualuxe'));
    }
}
add_action('woocommerce_after_checkout_validation', 'aqualuxe_checkout_field_validation', 10, 2);

/**
 * Add custom checkout fields to order meta.
 *
 * @param int $order_id Order ID.
 */
function aqualuxe_checkout_field_update_order_meta($order_id) {
    // Aquarium Size
    if (!empty($_POST['shipping_aquarium_size'])) {
        update_post_meta($order_id, '_shipping_aquarium_size', sanitize_text_field($_POST['shipping_aquarium_size']));
    }
    
    // Water Type
    if (!empty($_POST['shipping_water_type'])) {
        update_post_meta($order_id, '_shipping_water_type', sanitize_text_field($_POST['shipping_water_type']));
    }
    
    // Special Care Instructions
    if (!empty($_POST['order_special_instructions'])) {
        update_post_meta($order_id, '_order_special_instructions', sanitize_textarea_field($_POST['order_special_instructions']));
    }
    
    // Delivery Preference
    if (!empty($_POST['shipping_delivery_preference'])) {
        update_post_meta($order_id, '_shipping_delivery_preference', sanitize_text_field($_POST['shipping_delivery_preference']));
    }
}
add_action('woocommerce_checkout_update_order_meta', 'aqualuxe_checkout_field_update_order_meta');

/**
 * Add custom checkout fields to user meta.
 *
 * @param int $customer_id Customer ID.
 */
function aqualuxe_checkout_field_update_user_meta($customer_id) {
    // Aquarium Size
    if (!empty($_POST['shipping_aquarium_size'])) {
        update_user_meta($customer_id, 'shipping_aquarium_size', sanitize_text_field($_POST['shipping_aquarium_size']));
    }
    
    // Water Type
    if (!empty($_POST['shipping_water_type'])) {
        update_user_meta($customer_id, 'shipping_water_type', sanitize_text_field($_POST['shipping_water_type']));
    }
    
    // Delivery Preference
    if (!empty($_POST['shipping_delivery_preference'])) {
        update_user_meta($customer_id, 'shipping_delivery_preference', sanitize_text_field($_POST['shipping_delivery_preference']));
    }
}
add_action('woocommerce_checkout_update_user_meta', 'aqualuxe_checkout_field_update_user_meta');

/**
 * Add custom checkout fields to checkout form.
 */
function aqualuxe_checkout_fields_css() {
    if (!is_checkout()) {
        return;
    }
    
    // Only add CSS if cart has aquatic products
    if (!aqualuxe_cart_has_aquatic_products()) {
        return;
    }
    
    ?>
    <style>
        /* Custom Checkout Fields Styling */
        #shipping_aquarium_size_field,
        #shipping_water_type_field,
        #order_special_instructions_field,
        #shipping_delivery_preference_field {
            padding: 10px;
            margin-bottom: 15px;
            background-color: rgba(0, 115, 170, 0.05);
            border-radius: 4px;
        }
        
        #shipping_aquarium_size_field label,
        #shipping_water_type_field label,
        #order_special_instructions_field label,
        #shipping_delivery_preference_field label {
            font-weight: 600;
            color: #0073aa;
        }
        
        #order_special_instructions {
            min-height: 100px;
        }
        
        /* Aquatic Details Section */
        .woocommerce-aquatic-details {
            margin-top: 30px;
            padding: 20px;
            background-color: rgba(0, 115, 170, 0.05);
            border-radius: 4px;
        }
        
        .woocommerce-aquatic-details h2 {
            margin-top: 0;
            color: #0073aa;
        }
        
        .woocommerce-table--aquatic-details {
            width: 100%;
        }
        
        .woocommerce-table--aquatic-details th {
            text-align: left;
            padding: 10px;
            width: 40%;
        }
        
        .woocommerce-table--aquatic-details td {
            padding: 10px;
        }
    </style>
    <?php
}
add_action('wp_head', 'aqualuxe_checkout_fields_css');

/**
 * Add custom checkout fields to checkout form JavaScript.
 */
function aqualuxe_checkout_fields_js() {
    if (!is_checkout()) {
        return;
    }
    
    // Only add JavaScript if cart has aquatic products
    if (!aqualuxe_cart_has_aquatic_products()) {
        return;
    }
    
    ?>
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            // Show/hide fields based on water type
            $('#shipping_water_type').on('change', function() {
                var waterType = $(this).val();
                
                // If saltwater is selected, show a message about special handling
                if (waterType === 'saltwater') {
                    if ($('#saltwater_notice').length === 0) {
                        $('#shipping_water_type_field').after('<div id="saltwater_notice" class="woocommerce-info" style="margin: 0 0 15px; padding: 10px; background-color: #f7f6f7; color: #515151; border-top: 3px solid #0073aa;"><?php echo esc_js(__('Saltwater species require special handling. Please provide any specific requirements in the Special Care Instructions field.', 'aqualuxe')); ?></div>');
                    }
                } else {
                    $('#saltwater_notice').remove();
                }
            });
            
            // Show/hide fields based on delivery preference
            $('#shipping_delivery_preference').on('change', function() {
                var deliveryPreference = $(this).val();
                
                // If temperature controlled delivery is selected, show additional information
                if (deliveryPreference === 'temperature_controlled') {
                    if ($('#temperature_controlled_notice').length === 0) {
                        $('#shipping_delivery_preference_field').after('<div id="temperature_controlled_notice" class="woocommerce-info" style="margin: 0 0 15px; padding: 10px; background-color: #f7f6f7; color: #515151; border-top: 3px solid #0073aa;"><?php echo esc_js(__('Temperature controlled delivery ensures your aquatic species arrive in optimal condition. Additional shipping fees may apply.', 'aqualuxe')); ?></div>');
                    }
                } else {
                    $('#temperature_controlled_notice').remove();
                }
                
                // If expedited delivery is selected, show additional information
                if (deliveryPreference === 'expedited') {
                    if ($('#expedited_notice').length === 0) {
                        $('#shipping_delivery_preference_field').after('<div id="expedited_notice" class="woocommerce-info" style="margin: 0 0 15px; padding: 10px; background-color: #f7f6f7; color: #515151; border-top: 3px solid #0073aa;"><?php echo esc_js(__('Expedited delivery ensures your aquatic species arrive quickly. Additional shipping fees will apply.', 'aqualuxe')); ?></div>');
                    }
                } else {
                    $('#expedited_notice').remove();
                }
            });
        });
    </script>
    <?php
}
add_action('wp_footer', 'aqualuxe_checkout_fields_js');

/**
 * Add custom checkout fields to order review.
 */
function aqualuxe_review_order_after_shipping() {
    // Only add fields if cart has aquatic products
    if (!aqualuxe_cart_has_aquatic_products()) {
        return;
    }
    
    echo '<div class="aquatic-order-review">';
    echo '<h3>' . __('Aquatic Order Details', 'aqualuxe') . '</h3>';
    
    // Aquarium Size
    $aquarium_size = WC()->checkout->get_value('shipping_aquarium_size');
    if (!empty($aquarium_size)) {
        echo '<p><strong>' . __('Aquarium Size:', 'aqualuxe') . '</strong> ' . esc_html($aquarium_size) . ' ' . __('gallons', 'aqualuxe') . '</p>';
    }
    
    // Water Type
    $water_type = WC()->checkout->get_value('shipping_water_type');
    if (!empty($water_type)) {
        $water_types = array(
            'freshwater' => __('Freshwater', 'aqualuxe'),
            'saltwater'  => __('Saltwater', 'aqualuxe'),
            'brackish'   => __('Brackish', 'aqualuxe'),
        );
        
        echo '<p><strong>' . __('Water Type:', 'aqualuxe') . '</strong> ' . esc_html($water_types[$water_type] ?? $water_type) . '</p>';
    }
    
    // Special Care Instructions
    $special_instructions = WC()->checkout->get_value('order_special_instructions');
    if (!empty($special_instructions)) {
        echo '<p><strong>' . __('Special Care Instructions:', 'aqualuxe') . '</strong> ' . esc_html($special_instructions) . '</p>';
    }
    
    // Delivery Preference
    $delivery_preference = WC()->checkout->get_value('shipping_delivery_preference');
    if (!empty($delivery_preference)) {
        $delivery_preferences = array(
            'standard'        => __('Standard Delivery', 'aqualuxe'),
            'temperature_controlled' => __('Temperature Controlled Delivery', 'aqualuxe'),
            'expedited'       => __('Expedited Delivery', 'aqualuxe'),
        );
        
        echo '<p><strong>' . __('Delivery Preference:', 'aqualuxe') . '</strong> ' . esc_html($delivery_preferences[$delivery_preference] ?? $delivery_preference) . '</p>';
    }
    
    echo '</div>';
}
add_action('woocommerce_review_order_after_shipping', 'aqualuxe_review_order_after_shipping');

/**
 * Add custom checkout fields to thank you page.
 *
 * @param int $order_id Order ID.
 */
function aqualuxe_thankyou_aquatic_details($order_id) {
    $order = wc_get_order($order_id);
    
    // Check if any custom fields are filled
    $aquarium_size = get_post_meta($order_id, '_shipping_aquarium_size', true);
    $water_type = get_post_meta($order_id, '_shipping_water_type', true);
    $special_instructions = get_post_meta($order_id, '_order_special_instructions', true);
    $delivery_preference = get_post_meta($order_id, '_shipping_delivery_preference', true);
    
    if (empty($aquarium_size) && empty($water_type) && empty($special_instructions) && empty($delivery_preference)) {
        return;
    }
    
    echo '<section class="woocommerce-aquatic-details">';
    echo '<h2>' . __('Aquatic Details', 'aqualuxe') . '</h2>';
    echo '<table class="woocommerce-table woocommerce-table--aquatic-details shop_table aquatic_details">';
    
    // Aquarium Size
    if (!empty($aquarium_size)) {
        echo '<tr>';
        echo '<th>' . __('Aquarium Size:', 'aqualuxe') . '</th>';
        echo '<td>' . esc_html($aquarium_size) . ' ' . __('gallons', 'aqualuxe') . '</td>';
        echo '</tr>';
    }
    
    // Water Type
    if (!empty($water_type)) {
        $water_types = array(
            'freshwater' => __('Freshwater', 'aqualuxe'),
            'saltwater'  => __('Saltwater', 'aqualuxe'),
            'brackish'   => __('Brackish', 'aqualuxe'),
        );
        
        echo '<tr>';
        echo '<th>' . __('Water Type:', 'aqualuxe') . '</th>';
        echo '<td>' . esc_html($water_types[$water_type] ?? $water_type) . '</td>';
        echo '</tr>';
    }
    
    // Special Care Instructions
    if (!empty($special_instructions)) {
        echo '<tr>';
        echo '<th>' . __('Special Care Instructions:', 'aqualuxe') . '</th>';
        echo '<td>' . esc_html($special_instructions) . '</td>';
        echo '</tr>';
    }
    
    // Delivery Preference
    if (!empty($delivery_preference)) {
        $delivery_preferences = array(
            'standard'        => __('Standard Delivery', 'aqualuxe'),
            'temperature_controlled' => __('Temperature Controlled Delivery', 'aqualuxe'),
            'expedited'       => __('Expedited Delivery', 'aqualuxe'),
        );
        
        echo '<tr>';
        echo '<th>' . __('Delivery Preference:', 'aqualuxe') . '</th>';
        echo '<td>' . esc_html($delivery_preferences[$delivery_preference] ?? $delivery_preference) . '</td>';
        echo '</tr>';
    }
    
    echo '</table>';
    echo '</section>';
    
    // Add care instructions for aquatic products
    $has_aquatic = false;
    $aquatic_products = array();
    
    foreach ($order->get_items() as $item) {
        $product_id = $item->get_product_id();
        
        // Check if product is in aquatic category
        if (has_term('aquatic-species', 'product_cat', $product_id) || 
            has_term('fish', 'product_cat', $product_id) || 
            has_term('invertebrates', 'product_cat', $product_id) || 
            has_term('plants', 'product_cat', $product_id)) {
            $has_aquatic = true;
            $aquatic_products[] = $item->get_name();
        }
        
        // Check product tags
        if (has_term('live', 'product_tag', $product_id) || 
            has_term('aquatic', 'product_tag', $product_id)) {
            $has_aquatic = true;
            $aquatic_products[] = $item->get_name();
        }
    }
    
    if ($has_aquatic) {
        echo '<section class="woocommerce-aquatic-care">';
        echo '<h2>' . __('Aquatic Care Instructions', 'aqualuxe') . '</h2>';
        echo '<div class="aquatic-care-content">';
        echo '<p>' . __('Thank you for your aquatic purchase! Here are some general care instructions:', 'aqualuxe') . '</p>';
        echo '<ul>';
        echo '<li>' . __('Acclimate your new aquatic species slowly to your tank water.', 'aqualuxe') . '</li>';
        echo '<li>' . __('Monitor water parameters closely for the first few weeks.', 'aqualuxe') . '</li>';
        echo '<li>' . __('Ensure proper filtration and water circulation.', 'aqualuxe') . '</li>';
        echo '<li>' . __('Maintain appropriate water temperature for your species.', 'aqualuxe') . '</li>';
        echo '<li>' . __('Feed appropriately for your specific species.', 'aqualuxe') . '</li>';
        echo '</ul>';
        echo '<p>' . __('For specific care instructions for your purchases, please refer to our care guides or contact our customer support.', 'aqualuxe') . '</p>';
        echo '</div>';
        echo '</section>';
    }
}
add_action('woocommerce_thankyou', 'aqualuxe_thankyou_aquatic_details', 20);

/**
 * Add custom checkout fields styling to thank you page and order details.
 */
function aqualuxe_aquatic_details_css() {
    if (!is_wc_endpoint_url('order-received') && !is_wc_endpoint_url('view-order')) {
        return;
    }
    
    ?>
    <style>
        /* Aquatic Details Section */
        .woocommerce-aquatic-details,
        .woocommerce-aquatic-care {
            margin-top: 30px;
            padding: 20px;
            background-color: rgba(0, 115, 170, 0.05);
            border-radius: 4px;
        }
        
        .woocommerce-aquatic-details h2,
        .woocommerce-aquatic-care h2 {
            margin-top: 0;
            color: #0073aa;
        }
        
        .woocommerce-table--aquatic-details {
            width: 100%;
        }
        
        .woocommerce-table--aquatic-details th {
            text-align: left;
            padding: 10px;
            width: 40%;
        }
        
        .woocommerce-table--aquatic-details td {
            padding: 10px;
        }
        
        .aquatic-care-content {
            margin-top: 15px;
        }
        
        .aquatic-care-content ul {
            margin-left: 20px;
        }
        
        .aquatic-care-content li {
            margin-bottom: 10px;
        }
        
        /* Order Review Section */
        .aquatic-order-review {
            margin-top: 20px;
            padding: 15px;
            background-color: rgba(0, 115, 170, 0.05);
            border-radius: 4px;
        }
        
        .aquatic-order-review h3 {
            margin-top: 0;
            color: #0073aa;
        }
    </style>
    <?php
}
add_action('wp_head', 'aqualuxe_aquatic_details_css');

/**
 * Add shipping class based on delivery preference.
 *
 * @param array $package Shipping package.
 * @return array
 */
function aqualuxe_add_delivery_preference_shipping_class($package) {
    // Check if delivery preference is set
    if (empty($_POST['shipping_delivery_preference'])) {
        return $package;
    }
    
    $delivery_preference = sanitize_text_field($_POST['shipping_delivery_preference']);
    
    // Get shipping class IDs based on delivery preference
    $shipping_class_id = 0;
    
    switch ($delivery_preference) {
        case 'temperature_controlled':
            $shipping_class_id = get_term_by('slug', 'temperature-controlled', 'product_shipping_class')->term_id ?? 0;
            break;
        case 'expedited':
            $shipping_class_id = get_term_by('slug', 'expedited', 'product_shipping_class')->term_id ?? 0;
            break;
    }
    
    // If shipping class ID is found, apply it to all aquatic products
    if ($shipping_class_id > 0) {
        foreach ($package['contents'] as $item_id => $item) {
            $product_id = $item['product_id'];
            
            // Check if product is aquatic
            if (has_term('aquatic-species', 'product_cat', $product_id) || 
                has_term('fish', 'product_cat', $product_id) || 
                has_term('invertebrates', 'product_cat', $product_id) || 
                has_term('plants', 'product_cat', $product_id) ||
                has_term('live', 'product_tag', $product_id) || 
                has_term('aquatic', 'product_tag', $product_id)) {
                
                // Set shipping class
                $package['contents'][$item_id]['data']->set_shipping_class_id($shipping_class_id);
            }
        }
    }
    
    return $package;
}
add_filter('woocommerce_cart_shipping_packages', 'aqualuxe_add_delivery_preference_shipping_class');

/**
 * Register shipping classes for aquatic products.
 */
function aqualuxe_register_shipping_classes() {
    // Check if shipping classes exist
    $temperature_controlled = get_term_by('slug', 'temperature-controlled', 'product_shipping_class');
    $expedited = get_term_by('slug', 'expedited', 'product_shipping_class');
    
    // Register temperature controlled shipping class
    if (!$temperature_controlled) {
        wp_insert_term(
            'Temperature Controlled',
            'product_shipping_class',
            array(
                'slug' => 'temperature-controlled',
                'description' => __('Shipping class for temperature controlled delivery of aquatic species.', 'aqualuxe'),
            )
        );
    }
    
    // Register expedited shipping class
    if (!$expedited) {
        wp_insert_term(
            'Expedited',
            'product_shipping_class',
            array(
                'slug' => 'expedited',
                'description' => __('Shipping class for expedited delivery of aquatic species.', 'aqualuxe'),
            )
        );
    }
}
add_action('init', 'aqualuxe_register_shipping_classes');

/**
 * Add custom checkout fields to user profile.
 *
 * @param WP_User $user User object.
 */
function aqualuxe_add_customer_meta_fields($user) {
    if (!current_user_can('manage_woocommerce')) {
        return;
    }
    
    ?>
    <h2><?php _e('Aquatic Information', 'aqualuxe'); ?></h2>
    <table class="form-table">
        <tr>
            <th><label for="shipping_aquarium_size"><?php _e('Aquarium Size (gallons)', 'aqualuxe'); ?></label></th>
            <td>
                <input type="text" name="shipping_aquarium_size" id="shipping_aquarium_size" value="<?php echo esc_attr(get_user_meta($user->ID, 'shipping_aquarium_size', true)); ?>" class="regular-text" />
            </td>
        </tr>
        <tr>
            <th><label for="shipping_water_type"><?php _e('Water Type', 'aqualuxe'); ?></label></th>
            <td>
                <select name="shipping_water_type" id="shipping_water_type">
                    <option value=""><?php _e('Select water type', 'aqualuxe'); ?></option>
                    <option value="freshwater" <?php selected(get_user_meta($user->ID, 'shipping_water_type', true), 'freshwater'); ?>><?php _e('Freshwater', 'aqualuxe'); ?></option>
                    <option value="saltwater" <?php selected(get_user_meta($user->ID, 'shipping_water_type', true), 'saltwater'); ?>><?php _e('Saltwater', 'aqualuxe'); ?></option>
                    <option value="brackish" <?php selected(get_user_meta($user->ID, 'shipping_water_type', true), 'brackish'); ?>><?php _e('Brackish', 'aqualuxe'); ?></option>
                </select>
            </td>
        </tr>
        <tr>
            <th><label for="shipping_delivery_preference"><?php _e('Delivery Preference', 'aqualuxe'); ?></label></th>
            <td>
                <select name="shipping_delivery_preference" id="shipping_delivery_preference">
                    <option value=""><?php _e('Select delivery preference', 'aqualuxe'); ?></option>
                    <option value="standard" <?php selected(get_user_meta($user->ID, 'shipping_delivery_preference', true), 'standard'); ?>><?php _e('Standard Delivery', 'aqualuxe'); ?></option>
                    <option value="temperature_controlled" <?php selected(get_user_meta($user->ID, 'shipping_delivery_preference', true), 'temperature_controlled'); ?>><?php _e('Temperature Controlled Delivery', 'aqualuxe'); ?></option>
                    <option value="expedited" <?php selected(get_user_meta($user->ID, 'shipping_delivery_preference', true), 'expedited'); ?>><?php _e('Expedited Delivery', 'aqualuxe'); ?></option>
                </select>
            </td>
        </tr>
    </table>
    <?php
}
add_action('show_user_profile', 'aqualuxe_add_customer_meta_fields');
add_action('edit_user_profile', 'aqualuxe_add_customer_meta_fields');

/**
 * Save custom checkout fields in user profile.
 *
 * @param int $user_id User ID.
 */
function aqualuxe_save_customer_meta_fields($user_id) {
    if (!current_user_can('manage_woocommerce')) {
        return;
    }
    
    // Aquarium Size
    if (isset($_POST['shipping_aquarium_size'])) {
        update_user_meta($user_id, 'shipping_aquarium_size', sanitize_text_field($_POST['shipping_aquarium_size']));
    }
    
    // Water Type
    if (isset($_POST['shipping_water_type'])) {
        update_user_meta($user_id, 'shipping_water_type', sanitize_text_field($_POST['shipping_water_type']));
    }
    
    // Delivery Preference
    if (isset($_POST['shipping_delivery_preference'])) {
        update_user_meta($user_id, 'shipping_delivery_preference', sanitize_text_field($_POST['shipping_delivery_preference']));
    }
}
add_action('personal_options_update', 'aqualuxe_save_customer_meta_fields');
add_action('edit_user_profile_update', 'aqualuxe_save_customer_meta_fields');

/**
 * Add custom checkout fields to account edit address form.
 *
 * @param array $fields Address fields.
 * @param string $load_address Address type.
 * @return array
 */
function aqualuxe_address_fields($fields, $load_address) {
    if ('shipping' !== $load_address) {
        return $fields;
    }
    
    // Add aquarium size field
    $fields['shipping_aquarium_size'] = array(
        'label'       => __('Aquarium Size (gallons)', 'aqualuxe'),
        'placeholder' => __('e.g., 55', 'aqualuxe'),
        'required'    => false,
        'class'       => array('form-row-wide'),
        'clear'       => true,
        'priority'    => 110,
    );
    
    // Add water type field
    $fields['shipping_water_type'] = array(
        'label'       => __('Water Type', 'aqualuxe'),
        'type'        => 'select',
        'options'     => array(
            ''          => __('Select water type', 'aqualuxe'),
            'freshwater' => __('Freshwater', 'aqualuxe'),
            'saltwater'  => __('Saltwater', 'aqualuxe'),
            'brackish'   => __('Brackish', 'aqualuxe'),
        ),
        'required'    => false,
        'class'       => array('form-row-wide'),
        'clear'       => true,
        'priority'    => 120,
    );
    
    // Add delivery preference field
    $fields['shipping_delivery_preference'] = array(
        'label'       => __('Delivery Preference', 'aqualuxe'),
        'type'        => 'select',
        'options'     => array(
            ''                => __('Select delivery preference', 'aqualuxe'),
            'standard'        => __('Standard Delivery', 'aqualuxe'),
            'temperature_controlled' => __('Temperature Controlled Delivery', 'aqualuxe'),
            'expedited'       => __('Expedited Delivery', 'aqualuxe'),
        ),
        'required'    => false,
        'class'       => array('form-row-wide'),
        'clear'       => true,
        'priority'    => 130,
    );
    
    return $fields;
}
add_filter('woocommerce_address_to_edit', 'aqualuxe_address_fields', 10, 2);

/**
 * Save custom checkout fields from account edit address form.
 *
 * @param int $user_id User ID.
 * @param array $load_address Address type.
 */
function aqualuxe_save_address_fields($user_id, $load_address) {
    if ('shipping' !== $load_address) {
        return;
    }
    
    // Aquarium Size
    if (isset($_POST['shipping_aquarium_size'])) {
        update_user_meta($user_id, 'shipping_aquarium_size', sanitize_text_field($_POST['shipping_aquarium_size']));
    }
    
    // Water Type
    if (isset($_POST['shipping_water_type'])) {
        update_user_meta($user_id, 'shipping_water_type', sanitize_text_field($_POST['shipping_water_type']));
    }
    
    // Delivery Preference
    if (isset($_POST['shipping_delivery_preference'])) {
        update_user_meta($user_id, 'shipping_delivery_preference', sanitize_text_field($_POST['shipping_delivery_preference']));
    }
}
add_action('woocommerce_customer_save_address', 'aqualuxe_save_address_fields', 10, 2);

/**
 * Add custom checkout fields to checkout form from user meta.
 *
 * @param array $fields Checkout fields.
 * @return array
 */
function aqualuxe_checkout_fields_from_user_meta($fields) {
    // Only add fields if cart has aquatic products
    if (!aqualuxe_cart_has_aquatic_products()) {
        return $fields;
    }
    
    // Get current user
    $user_id = get_current_user_id();
    
    if ($user_id > 0) {
        // Aquarium Size
        $aquarium_size = get_user_meta($user_id, 'shipping_aquarium_size', true);
        if (!empty($aquarium_size)) {
            $fields['shipping']['shipping_aquarium_size']['default'] = $aquarium_size;
        }
        
        // Water Type
        $water_type = get_user_meta($user_id, 'shipping_water_type', true);
        if (!empty($water_type)) {
            $fields['shipping']['shipping_water_type']['default'] = $water_type;
        }
        
        // Delivery Preference
        $delivery_preference = get_user_meta($user_id, 'shipping_delivery_preference', true);
        if (!empty($delivery_preference)) {
            $fields['shipping']['shipping_delivery_preference']['default'] = $delivery_preference;
        }
    }
    
    return $fields;
}
add_filter('woocommerce_checkout_fields', 'aqualuxe_checkout_fields_from_user_meta', 20);