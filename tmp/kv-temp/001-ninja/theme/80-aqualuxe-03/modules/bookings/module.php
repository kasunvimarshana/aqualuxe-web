<?php
/**
 * Bookings module (lightweight):
 * - Detect WooCommerce Bookings products and add a small badge.
 * - Provide a simple shortcode to link to a booking product.
 *
 * Fully guarded to work without WooCommerce or the Bookings plugin.
 */

if (!defined('ABSPATH')) { exit; }

// Contribute unified badge for booking products
add_filter('aqualuxe/product_badges', function(array $badges, $product){
    $is_booking = false;
    try {
        if (method_exists($product, 'is_type') && $product->is_type('booking')) {
            $is_booking = true;
        } elseif (class_exists('WC_Product_Booking') && is_a($product, 'WC_Product_Booking')) {
            $is_booking = true;
        }
    } catch (\Throwable $e) {}
    if ($is_booking) $badges[] = esc_html__('Bookable','aqualuxe');
    return $badges;
}, 10, 2);

// Shortcode: [aqualuxe_booking_link id="123" label="Book now"]
add_shortcode('aqualuxe_booking_link', function($atts){
    $atts = shortcode_atts([
        'id' => 0,
        'label' => __('Book now','aqualuxe')
    ], $atts, 'aqualuxe_booking_link');
    $id = absint($atts['id']);
    if (!$id) return '';
    $url = get_permalink($id);
    if (!$url) return '';
    return '<a class="button aqlx-booking" href="' . esc_url($url) . '">' . esc_html($atts['label']) . '</a>';
});
