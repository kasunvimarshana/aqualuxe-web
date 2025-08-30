<?php
/**
 * Analytics Helper Functions
 *
 * Functions to help with analytics integration and provide fallbacks when WooCommerce is not active.
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Check if analytics features are available
 * 
 * @return bool True if analytics features are available, false otherwise
 */
function aqualuxe_is_analytics_available() {
    // Check if WooCommerce is active since analytics depends on it
    return function_exists('aqualuxe_is_woocommerce_active') && aqualuxe_is_woocommerce_active();
}

/**
 * Safely get WooCommerce product
 *
 * @param int $product_id Product ID
 * @return WC_Product|false Product object or false if WooCommerce is not active
 */
function aqualuxe_analytics_get_product($product_id) {
    if (!function_exists('wc_get_product')) {
        return false;
    }
    return wc_get_product($product_id);
}

/**
 * Safely get WooCommerce order
 *
 * @param int $order_id Order ID
 * @return WC_Order|false Order object or false if WooCommerce is not active
 */
function aqualuxe_analytics_get_order($order_id) {
    if (!function_exists('wc_get_order')) {
        return false;
    }
    return wc_get_order($order_id);
}

/**
 * Safely get WooCommerce orders
 *
 * @param array $args Query arguments
 * @return array|false Array of orders or false if WooCommerce is not active
 */
function aqualuxe_analytics_get_orders($args) {
    if (!function_exists('wc_get_orders')) {
        return false;
    }
    return wc_get_orders($args);
}

/**
 * Safely get WooCommerce order notes
 *
 * @param array $args Query arguments
 * @return array|false Array of order notes or false if WooCommerce is not active
 */
function aqualuxe_analytics_get_order_notes($args) {
    if (!function_exists('wc_get_order_notes')) {
        return false;
    }
    return wc_get_order_notes($args);
}

/**
 * Safely get WooCommerce order note
 *
 * @param int $note_id Note ID
 * @return object|false Order note or false if WooCommerce is not active
 */
function aqualuxe_analytics_get_order_note($note_id) {
    if (!function_exists('wc_get_order_note')) {
        return false;
    }
    return wc_get_order_note($note_id);
}

/**
 * Safely get WooCommerce order status name
 *
 * @param string $status Order status
 * @return string|false Order status name or false if WooCommerce is not active
 */
function aqualuxe_analytics_get_order_status_name($status) {
    if (!function_exists('wc_get_order_status_name')) {
        return $status;
    }
    return wc_get_order_status_name($status);
}

/**
 * Safely get WooCommerce order statuses
 *
 * @return array|false Order statuses or false if WooCommerce is not active
 */
function aqualuxe_analytics_get_order_statuses() {
    if (!function_exists('wc_get_order_statuses')) {
        return array();
    }
    return wc_get_order_statuses();
}

/**
 * Safely get WooCommerce order coupon discount amount
 *
 * @param WC_Order $order Order object
 * @param string $coupon_code Coupon code
 * @return float|false Discount amount or false if WooCommerce is not active
 */
function aqualuxe_analytics_get_order_coupon_discount_amount($order, $coupon_code) {
    if (!function_exists('wc_get_order_coupon_discount_amount')) {
        return 0;
    }
    return wc_get_order_coupon_discount_amount($order, $coupon_code);
}

/**
 * Safely get WooCommerce order coupon discount tax amount
 *
 * @param WC_Order $order Order object
 * @param string $coupon_code Coupon code
 * @return float|false Discount tax amount or false if WooCommerce is not active
 */
function aqualuxe_analytics_get_order_coupon_discount_tax_amount($order, $coupon_code) {
    if (!function_exists('wc_get_order_coupon_discount_tax_amount')) {
        return 0;
    }
    return wc_get_order_coupon_discount_tax_amount($order, $coupon_code);
}

/**
 * Safely get WooCommerce rating HTML
 *
 * @param float $rating Rating
 * @param int $count Count of ratings
 * @return string Rating HTML or empty string if WooCommerce is not active
 */
function aqualuxe_get_rating_html($rating, $count = 0) {
    if (!function_exists('wc_get_rating_html')) {
        // Fallback rating HTML
        $html = '<div class="star-rating">';
        $html .= '<span style="width:' . (($rating / 5) * 100) . '%">';
        $html .= sprintf(esc_html__('Rated %s out of 5', 'aqualuxe'), $rating);
        $html .= '</span>';
        $html .= '</div>';
        return $html;
    }
    return wc_get_rating_html($rating, $count);
}