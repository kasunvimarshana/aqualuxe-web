<?php
/**
 * Wholesale module functions
 *
 * @package AquaLuxe
 * @subpackage Modules
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Check if current user is a wholesale user
 *
 * @return bool True if user is a wholesale user
 */
function aqualuxe_wholesale_is_wholesale_user() {
    // Get wholesale user roles
    $wholesale_roles = aqualuxe_wholesale_get_user_roles();
    
    // Check if user has any wholesale role
    $user = wp_get_current_user();
    
    if (!$user || !$user->exists()) {
        return false;
    }
    
    foreach ($wholesale_roles as $role => $data) {
        if (in_array($role, (array) $user->roles)) {
            return true;
        }
    }
    
    return false;
}

/**
 * Check if current user is a specific wholesale role
 *
 * @param string $role Wholesale role to check
 * @return bool True if user has the specified wholesale role
 */
function aqualuxe_wholesale_is_user_role($role) {
    // Check if role exists
    $wholesale_roles = aqualuxe_wholesale_get_user_roles();
    
    if (!isset($wholesale_roles[$role])) {
        return false;
    }
    
    // Check if user has the role
    $user = wp_get_current_user();
    
    if (!$user || !$user->exists()) {
        return false;
    }
    
    return in_array($role, (array) $user->roles);
}

/**
 * Get wholesale user roles
 *
 * @return array Wholesale user roles
 */
function aqualuxe_wholesale_get_user_roles() {
    $default_roles = array(
        'wholesale_customer' => array(
            'name' => __('Wholesale Customer', 'aqualuxe'),
            'discount' => 10,
            'min_order' => 500,
        ),
        'wholesale_distributor' => array(
            'name' => __('Wholesale Distributor', 'aqualuxe'),
            'discount' => 20,
            'min_order' => 1000,
        ),
        'wholesale_partner' => array(
            'name' => __('Wholesale Partner', 'aqualuxe'),
            'discount' => 30,
            'min_order' => 2500,
        ),
    );
    
    // Get custom roles from options
    $custom_roles = aqualuxe_get_module_option('wholesale', 'user_roles', array());
    
    // Merge default and custom roles
    $roles = array_merge($default_roles, $custom_roles);
    
    return apply_filters('aqualuxe_wholesale_user_roles', $roles);
}

/**
 * Get wholesale discount for current user
 *
 * @return float Wholesale discount percentage
 */
function aqualuxe_wholesale_get_user_discount() {
    // Check if user is a wholesale user
    if (!aqualuxe_wholesale_is_wholesale_user()) {
        return 0;
    }
    
    // Get user roles
    $user = wp_get_current_user();
    $wholesale_roles = aqualuxe_wholesale_get_user_roles();
    
    // Find the highest discount
    $discount = 0;
    
    foreach ($wholesale_roles as $role => $data) {
        if (in_array($role, (array) $user->roles) && isset($data['discount'])) {
            $discount = max($discount, (float) $data['discount']);
        }
    }
    
    return apply_filters('aqualuxe_wholesale_user_discount', $discount, $user->ID);
}

/**
 * Get wholesale minimum order amount for current user
 *
 * @return float Minimum order amount
 */
function aqualuxe_wholesale_get_minimum_order_amount() {
    // Check if user is a wholesale user
    if (!aqualuxe_wholesale_is_wholesale_user()) {
        return 0;
    }
    
    // Get user roles
    $user = wp_get_current_user();
    $wholesale_roles = aqualuxe_wholesale_get_user_roles();
    
    // Find the minimum order amount
    $min_order = 0;
    
    foreach ($wholesale_roles as $role => $data) {
        if (in_array($role, (array) $user->roles) && isset($data['min_order'])) {
            if ($min_order === 0 || $data['min_order'] < $min_order) {
                $min_order = (float) $data['min_order'];
            }
        }
    }
    
    return apply_filters('aqualuxe_wholesale_minimum_order_amount', $min_order, $user->ID);
}

/**
 * Check if current page is a wholesale page
 *
 * @return bool True if current page is a wholesale page
 */
function aqualuxe_wholesale_is_wholesale_page() {
    // Get wholesale pages
    $wholesale_pages = aqualuxe_wholesale_get_pages();
    
    // Check if current page is a wholesale page
    if (is_page() && !empty($wholesale_pages)) {
        $current_page_id = get_the_ID();
        
        foreach ($wholesale_pages as $page_id) {
            if ($current_page_id == $page_id) {
                return true;
            }
        }
    }
    
    // Check for wholesale query var
    if (get_query_var('wholesale', false)) {
        return true;
    }
    
    return false;
}

/**
 * Get wholesale pages
 *
 * @return array Wholesale page IDs
 */
function aqualuxe_wholesale_get_pages() {
    $pages = array();
    
    // Registration page
    $registration_page_id = aqualuxe_get_module_option('wholesale', 'registration_page', 0);
    if ($registration_page_id) {
        $pages[] = $registration_page_id;
    }
    
    // Terms page
    $terms_page_id = aqualuxe_get_module_option('wholesale', 'terms_page', 0);
    if ($terms_page_id) {
        $pages[] = $terms_page_id;
    }
    
    // Dashboard page
    $dashboard_page_id = aqualuxe_get_module_option('wholesale', 'dashboard_page', 0);
    if ($dashboard_page_id) {
        $pages[] = $dashboard_page_id;
    }
    
    return apply_filters('aqualuxe_wholesale_pages', $pages);
}

/**
 * Get wholesale price for a product
 *
 * @param int|WC_Product $product Product ID or product object
 * @param float $price Regular price
 * @return float Wholesale price
 */
function aqualuxe_wholesale_get_price($product, $price = null) {
    // Check if user is a wholesale user
    if (!aqualuxe_wholesale_is_wholesale_user()) {
        return $price;
    }
    
    // Get product object
    if (is_numeric($product)) {
        $product = wc_get_product($product);
    }
    
    if (!$product) {
        return $price;
    }
    
    // Get price if not provided
    if ($price === null) {
        $price = $product->get_price();
    }
    
    // Check if product has specific wholesale price
    $wholesale_price = get_post_meta($product->get_id(), '_wholesale_price', true);
    
    if ($wholesale_price !== '' && $wholesale_price !== false) {
        return (float) $wholesale_price;
    }
    
    // Apply discount
    $discount = aqualuxe_wholesale_get_user_discount();
    
    if ($discount > 0) {
        $price = $price * (1 - $discount / 100);
    }
    
    return apply_filters('aqualuxe_wholesale_price', $price, $product, $discount);
}

/**
 * Check if product is available for wholesale
 *
 * @param int|WC_Product $product Product ID or product object
 * @return bool True if product is available for wholesale
 */
function aqualuxe_wholesale_is_product_available($product) {
    // Get product object
    if (is_numeric($product)) {
        $product = wc_get_product($product);
    }
    
    if (!$product) {
        return false;
    }
    
    // Check if product is excluded from wholesale
    $excluded = get_post_meta($product->get_id(), '_wholesale_exclude', true);
    
    if ($excluded === 'yes') {
        return false;
    }
    
    // Check if product category is excluded from wholesale
    $excluded_categories = aqualuxe_get_module_option('wholesale', 'excluded_categories', array());
    
    if (!empty($excluded_categories)) {
        $product_categories = wp_get_post_terms($product->get_id(), 'product_cat', array('fields' => 'ids'));
        
        if (array_intersect($product_categories, $excluded_categories)) {
            return false;
        }
    }
    
    return true;
}

/**
 * Get wholesale registration fields
 *
 * @return array Registration fields
 */
function aqualuxe_wholesale_get_registration_fields() {
    $default_fields = array(
        'company_name' => array(
            'label' => __('Company Name', 'aqualuxe'),
            'type' => 'text',
            'required' => true,
            'class' => array('form-row-wide'),
            'priority' => 10,
        ),
        'company_address' => array(
            'label' => __('Company Address', 'aqualuxe'),
            'type' => 'textarea',
            'required' => true,
            'class' => array('form-row-wide'),
            'priority' => 20,
        ),
        'tax_id' => array(
            'label' => __('Tax ID / VAT Number', 'aqualuxe'),
            'type' => 'text',
            'required' => true,
            'class' => array('form-row-wide'),
            'priority' => 30,
        ),
        'business_type' => array(
            'label' => __('Business Type', 'aqualuxe'),
            'type' => 'select',
            'required' => true,
            'class' => array('form-row-wide'),
            'options' => array(
                '' => __('Select business type', 'aqualuxe'),
                'retailer' => __('Retailer', 'aqualuxe'),
                'distributor' => __('Distributor', 'aqualuxe'),
                'manufacturer' => __('Manufacturer', 'aqualuxe'),
                'other' => __('Other', 'aqualuxe'),
            ),
            'priority' => 40,
        ),
        'business_phone' => array(
            'label' => __('Business Phone', 'aqualuxe'),
            'type' => 'tel',
            'required' => true,
            'class' => array('form-row-wide'),
            'priority' => 50,
        ),
        'website' => array(
            'label' => __('Website', 'aqualuxe'),
            'type' => 'url',
            'required' => false,
            'class' => array('form-row-wide'),
            'priority' => 60,
        ),
        'how_heard' => array(
            'label' => __('How did you hear about us?', 'aqualuxe'),
            'type' => 'select',
            'required' => false,
            'class' => array('form-row-wide'),
            'options' => array(
                '' => __('Select an option', 'aqualuxe'),
                'search' => __('Search Engine', 'aqualuxe'),
                'social' => __('Social Media', 'aqualuxe'),
                'referral' => __('Referral', 'aqualuxe'),
                'exhibition' => __('Trade Show/Exhibition', 'aqualuxe'),
                'other' => __('Other', 'aqualuxe'),
            ),
            'priority' => 70,
        ),
        'notes' => array(
            'label' => __('Additional Notes', 'aqualuxe'),
            'type' => 'textarea',
            'required' => false,
            'class' => array('form-row-wide'),
            'priority' => 80,
        ),
        'terms' => array(
            'label' => __('I have read and agree to the wholesale terms and conditions', 'aqualuxe'),
            'type' => 'checkbox',
            'required' => true,
            'class' => array('form-row-wide'),
            'priority' => 90,
        ),
    );
    
    // Get custom fields from options
    $custom_fields = aqualuxe_get_module_option('wholesale', 'registration_fields', array());
    
    // Merge default and custom fields
    $fields = array_merge($default_fields, $custom_fields);
    
    // Sort fields by priority
    uasort($fields, function($a, $b) {
        return $a['priority'] - $b['priority'];
    });
    
    return apply_filters('aqualuxe_wholesale_registration_fields', $fields);
}

/**
 * Get wholesale application status
 *
 * @param int $user_id User ID
 * @return string Application status
 */
function aqualuxe_wholesale_get_application_status($user_id) {
    $status = get_user_meta($user_id, '_wholesale_application_status', true);
    
    if (!$status) {
        return 'none';
    }
    
    return $status;
}

/**
 * Update wholesale application status
 *
 * @param int $user_id User ID
 * @param string $status Application status
 * @return bool True if status was updated
 */
function aqualuxe_wholesale_update_application_status($user_id, $status) {
    $valid_statuses = array('pending', 'approved', 'rejected', 'none');
    
    if (!in_array($status, $valid_statuses)) {
        return false;
    }
    
    return update_user_meta($user_id, '_wholesale_application_status', $status);
}

/**
 * Get wholesale dashboard URL
 *
 * @return string Dashboard URL
 */
function aqualuxe_wholesale_get_dashboard_url() {
    $dashboard_page_id = aqualuxe_get_module_option('wholesale', 'dashboard_page', 0);
    
    if ($dashboard_page_id) {
        return get_permalink($dashboard_page_id);
    }
    
    return home_url('/my-account/');
}

/**
 * Get wholesale registration URL
 *
 * @return string Registration URL
 */
function aqualuxe_wholesale_get_registration_url() {
    $registration_page_id = aqualuxe_get_module_option('wholesale', 'registration_page', 0);
    
    if ($registration_page_id) {
        return get_permalink($registration_page_id);
    }
    
    return home_url('/my-account/');
}

/**
 * Get wholesale terms URL
 *
 * @return string Terms URL
 */
function aqualuxe_wholesale_get_terms_url() {
    $terms_page_id = aqualuxe_get_module_option('wholesale', 'terms_page', 0);
    
    if ($terms_page_id) {
        return get_permalink($terms_page_id);
    }
    
    return '';
}

/**
 * Check if wholesale registration is enabled
 *
 * @return bool True if wholesale registration is enabled
 */
function aqualuxe_wholesale_is_registration_enabled() {
    return (bool) aqualuxe_get_module_option('wholesale', 'enable_registration', true);
}

/**
 * Check if wholesale requires approval
 *
 * @return bool True if wholesale requires approval
 */
function aqualuxe_wholesale_requires_approval() {
    return (bool) aqualuxe_get_module_option('wholesale', 'require_approval', true);
}

/**
 * Get wholesale order requirements
 *
 * @return array Order requirements
 */
function aqualuxe_wholesale_get_order_requirements() {
    $default_requirements = array(
        'min_order_amount' => true,
        'min_order_quantity' => false,
        'min_quantity_per_product' => false,
        'min_order_amount_value' => 500,
        'min_order_quantity_value' => 10,
        'min_quantity_per_product_value' => 5,
    );
    
    // Get custom requirements from options
    $custom_requirements = aqualuxe_get_module_option('wholesale', 'order_requirements', array());
    
    // Merge default and custom requirements
    $requirements = array_merge($default_requirements, $custom_requirements);
    
    return apply_filters('aqualuxe_wholesale_order_requirements', $requirements);
}

/**
 * Check if order meets wholesale requirements
 *
 * @param WC_Order $order Order object
 * @return bool|array True if order meets requirements, array of errors otherwise
 */
function aqualuxe_wholesale_check_order_requirements($order) {
    // Check if user is a wholesale user
    $user_id = $order->get_user_id();
    $user = get_user_by('id', $user_id);
    
    if (!$user || !aqualuxe_wholesale_is_wholesale_user()) {
        return true;
    }
    
    // Get order requirements
    $requirements = aqualuxe_wholesale_get_order_requirements();
    $errors = array();
    
    // Check minimum order amount
    if ($requirements['min_order_amount']) {
        $min_amount = aqualuxe_wholesale_get_minimum_order_amount();
        
        if ($min_amount > 0 && $order->get_subtotal() < $min_amount) {
            $errors[] = sprintf(
                __('Minimum order amount for wholesale is %s. Your order total is %s.', 'aqualuxe'),
                wc_price($min_amount),
                wc_price($order->get_subtotal())
            );
        }
    }
    
    // Check minimum order quantity
    if ($requirements['min_order_quantity']) {
        $min_quantity = $requirements['min_order_quantity_value'];
        $order_quantity = 0;
        
        foreach ($order->get_items() as $item) {
            $order_quantity += $item->get_quantity();
        }
        
        if ($min_quantity > 0 && $order_quantity < $min_quantity) {
            $errors[] = sprintf(
                __('Minimum order quantity for wholesale is %d. Your order quantity is %d.', 'aqualuxe'),
                $min_quantity,
                $order_quantity
            );
        }
    }
    
    // Check minimum quantity per product
    if ($requirements['min_quantity_per_product']) {
        $min_quantity = $requirements['min_quantity_per_product_value'];
        
        foreach ($order->get_items() as $item) {
            if ($item->get_quantity() < $min_quantity) {
                $errors[] = sprintf(
                    __('Minimum quantity per product for wholesale is %d. %s quantity is %d.', 'aqualuxe'),
                    $min_quantity,
                    $item->get_name(),
                    $item->get_quantity()
                );
            }
        }
    }
    
    if (!empty($errors)) {
        return $errors;
    }
    
    return true;
}

/**
 * Get wholesale tax exemption status
 *
 * @param int $user_id User ID
 * @return bool True if user is tax exempt
 */
function aqualuxe_wholesale_is_tax_exempt($user_id = null) {
    if (!$user_id) {
        $user_id = get_current_user_id();
    }
    
    if (!$user_id) {
        return false;
    }
    
    return (bool) get_user_meta($user_id, '_wholesale_tax_exempt', true);
}

/**
 * Set wholesale tax exemption status
 *
 * @param int $user_id User ID
 * @param bool $status Tax exemption status
 * @return bool True if status was updated
 */
function aqualuxe_wholesale_set_tax_exempt($user_id, $status) {
    return update_user_meta($user_id, '_wholesale_tax_exempt', (bool) $status);
}

/**
 * Get wholesale user data
 *
 * @param int $user_id User ID
 * @return array Wholesale user data
 */
function aqualuxe_wholesale_get_user_data($user_id) {
    $data = array();
    
    // Get registration fields
    $fields = aqualuxe_wholesale_get_registration_fields();
    
    foreach ($fields as $key => $field) {
        $data[$key] = get_user_meta($user_id, '_wholesale_' . $key, true);
    }
    
    // Get application status
    $data['application_status'] = aqualuxe_wholesale_get_application_status($user_id);
    
    // Get tax exemption status
    $data['tax_exempt'] = aqualuxe_wholesale_is_tax_exempt($user_id);
    
    return $data;
}

/**
 * Get wholesale user role
 *
 * @param int $user_id User ID
 * @return string|bool Wholesale role or false if not a wholesale user
 */
function aqualuxe_wholesale_get_user_role($user_id) {
    $user = get_user_by('id', $user_id);
    
    if (!$user) {
        return false;
    }
    
    $wholesale_roles = aqualuxe_wholesale_get_user_roles();
    
    foreach ($wholesale_roles as $role => $data) {
        if (in_array($role, (array) $user->roles)) {
            return $role;
        }
    }
    
    return false;
}

/**
 * Set wholesale user role
 *
 * @param int $user_id User ID
 * @param string $role Wholesale role
 * @return bool True if role was set
 */
function aqualuxe_wholesale_set_user_role($user_id, $role) {
    $user = get_user_by('id', $user_id);
    
    if (!$user) {
        return false;
    }
    
    $wholesale_roles = aqualuxe_wholesale_get_user_roles();
    
    if (!isset($wholesale_roles[$role])) {
        return false;
    }
    
    // Remove existing wholesale roles
    foreach ($wholesale_roles as $wholesale_role => $data) {
        $user->remove_role($wholesale_role);
    }
    
    // Add new wholesale role
    $user->add_role($role);
    
    return true;
}