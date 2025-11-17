<?php
/**
 * Booking availability functionality
 *
 * @package AquaLuxe
 * @subpackage Modules
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Initialize booking availability
 */
function aqualuxe_bookings_initialize_availability() {
    // Register AJAX actions
    add_action('wp_ajax_aqualuxe_check_booking_availability', 'aqualuxe_bookings_ajax_check_availability');
    add_action('wp_ajax_nopriv_aqualuxe_check_booking_availability', 'aqualuxe_bookings_ajax_check_availability');
    
    // Add availability check to single product page
    add_action('woocommerce_before_add_to_cart_button', 'aqualuxe_bookings_availability_notice', 15);
    
    // Add availability check to cart
    add_action('woocommerce_check_cart_items', 'aqualuxe_bookings_check_cart_items');
    
    // Add availability check to checkout
    add_action('woocommerce_checkout_process', 'aqualuxe_bookings_check_checkout_items');
}

/**
 * AJAX handler for checking booking availability
 */
function aqualuxe_bookings_ajax_check_availability() {
    // Check nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'aqualuxe_bookings_nonce')) {
        wp_send_json_error(array(
            'message' => __('Security check failed.', 'aqualuxe'),
        ));
    }
    
    // Check required fields
    if (!isset($_POST['product_id']) || !isset($_POST['date'])) {
        wp_send_json_error(array(
            'message' => __('Missing required fields.', 'aqualuxe'),
        ));
    }
    
    $product_id = absint($_POST['product_id']);
    $date = sanitize_text_field($_POST['date']);
    $start_time = isset($_POST['start_time']) ? sanitize_text_field($_POST['start_time']) : '';
    $end_time = isset($_POST['end_time']) ? sanitize_text_field($_POST['end_time']) : '';
    $duration = isset($_POST['duration']) ? absint($_POST['duration']) : 0;
    $quantity = isset($_POST['quantity']) ? absint($_POST['quantity']) : 1;
    
    $product = wc_get_product($product_id);
    
    if (!$product || !aqualuxe_bookings_is_booking_product($product)) {
        wp_send_json_error(array(
            'message' => __('Invalid booking product.', 'aqualuxe'),
        ));
    }
    
    // Get booking type
    $booking_type = get_post_meta($product_id, '_booking_type', true);
    
    // Prepare start and end dates
    $start_date = $date;
    $end_date = $date;
    
    if ($booking_type === 'date_time' || $booking_type === 'fixed_time') {
        if (empty($start_time) || empty($end_time)) {
            wp_send_json_error(array(
                'message' => __('Missing start or end time.', 'aqualuxe'),
            ));
        }
        
        $start_date .= ' ' . $start_time;
        $end_date .= ' ' . $end_time;
    } elseif ($booking_type === 'duration') {
        if ($duration <= 0) {
            wp_send_json_error(array(
                'message' => __('Invalid duration.', 'aqualuxe'),
            ));
        }
        
        $duration_unit = get_post_meta($product_id, '_booking_duration_unit', true);
        
        switch ($duration_unit) {
            case 'hour':
                $end_date = date('Y-m-d H:i:s', strtotime($start_date . ' +' . $duration . ' hours'));
                break;
            case 'day':
                $end_date = date('Y-m-d H:i:s', strtotime($start_date . ' +' . $duration . ' days'));
                break;
            case 'week':
                $end_date = date('Y-m-d H:i:s', strtotime($start_date . ' +' . $duration . ' weeks'));
                break;
            case 'month':
                $end_date = date('Y-m-d H:i:s', strtotime($start_date . ' +' . $duration . ' months'));
                break;
        }
    }
    
    // Check availability
    $is_available = aqualuxe_bookings_is_available($product_id, $start_date, $end_date, $quantity);
    
    if ($is_available) {
        wp_send_json_success(array(
            'available' => true,
            'message' => __('The selected booking is available.', 'aqualuxe'),
        ));
    } else {
        wp_send_json_error(array(
            'available' => false,
            'message' => __('The selected booking is not available. Please choose a different date or time.', 'aqualuxe'),
        ));
    }
}

/**
 * Display availability notice on product page
 */
function aqualuxe_bookings_availability_notice() {
    global $product;
    
    if (!$product || !aqualuxe_bookings_is_booking_product($product)) {
        return;
    }
    
    ?>
    <div class="booking-availability-notice" style="display: none;">
        <p class="booking-available"><?php esc_html_e('The selected booking is available.', 'aqualuxe'); ?></p>
        <p class="booking-unavailable"><?php esc_html_e('The selected booking is not available. Please choose a different date or time.', 'aqualuxe'); ?></p>
    </div>
    <?php
}

/**
 * Check booking availability in cart
 */
function aqualuxe_bookings_check_cart_items() {
    if (is_admin()) {
        return;
    }
    
    if (WC()->cart->is_empty()) {
        return;
    }
    
    foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
        $product_id = $cart_item['product_id'];
        $product = wc_get_product($product_id);
        
        if (!$product || !aqualuxe_bookings_is_booking_product($product)) {
            continue;
        }
        
        if (!isset($cart_item['booking_data'])) {
            continue;
        }
        
        $booking_data = $cart_item['booking_data'];
        
        // Get booking type
        $booking_type = $booking_data['booking_type'];
        
        // Prepare start and end dates
        $start_date = $booking_data['booking_date'];
        $end_date = $booking_data['booking_date'];
        
        if ($booking_type === 'date_time' || $booking_type === 'fixed_time') {
            $start_date .= ' ' . $booking_data['booking_start_time'];
            $end_date .= ' ' . $booking_data['booking_end_time'];
        } elseif ($booking_type === 'duration') {
            $duration = $booking_data['booking_duration'];
            $duration_unit = $booking_data['booking_duration_unit'];
            
            switch ($duration_unit) {
                case 'hour':
                    $end_date = date('Y-m-d H:i:s', strtotime($start_date . ' +' . $duration . ' hours'));
                    break;
                case 'day':
                    $end_date = date('Y-m-d H:i:s', strtotime($start_date . ' +' . $duration . ' days'));
                    break;
                case 'week':
                    $end_date = date('Y-m-d H:i:s', strtotime($start_date . ' +' . $duration . ' weeks'));
                    break;
                case 'month':
                    $end_date = date('Y-m-d H:i:s', strtotime($start_date . ' +' . $duration . ' months'));
                    break;
            }
        }
        
        // Check availability
        $quantity = isset($booking_data['booking_quantity']) ? $booking_data['booking_quantity'] : 1;
        
        if (!aqualuxe_bookings_is_available($product_id, $start_date, $end_date, $quantity, 0)) {
            // Remove item from cart
            WC()->cart->remove_cart_item($cart_item_key);
            
            // Add notice
            wc_add_notice(
                sprintf(
                    __('The booking for "%s" is no longer available. It has been removed from your cart.', 'aqualuxe'),
                    $product->get_name()
                ),
                'error'
            );
        }
    }
}

/**
 * Check booking availability at checkout
 */
function aqualuxe_bookings_check_checkout_items() {
    if (WC()->cart->is_empty()) {
        return;
    }
    
    foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
        $product_id = $cart_item['product_id'];
        $product = wc_get_product($product_id);
        
        if (!$product || !aqualuxe_bookings_is_booking_product($product)) {
            continue;
        }
        
        if (!isset($cart_item['booking_data'])) {
            continue;
        }
        
        $booking_data = $cart_item['booking_data'];
        
        // Get booking type
        $booking_type = $booking_data['booking_type'];
        
        // Prepare start and end dates
        $start_date = $booking_data['booking_date'];
        $end_date = $booking_data['booking_date'];
        
        if ($booking_type === 'date_time' || $booking_type === 'fixed_time') {
            $start_date .= ' ' . $booking_data['booking_start_time'];
            $end_date .= ' ' . $booking_data['booking_end_time'];
        } elseif ($booking_type === 'duration') {
            $duration = $booking_data['booking_duration'];
            $duration_unit = $booking_data['booking_duration_unit'];
            
            switch ($duration_unit) {
                case 'hour':
                    $end_date = date('Y-m-d H:i:s', strtotime($start_date . ' +' . $duration . ' hours'));
                    break;
                case 'day':
                    $end_date = date('Y-m-d H:i:s', strtotime($start_date . ' +' . $duration . ' days'));
                    break;
                case 'week':
                    $end_date = date('Y-m-d H:i:s', strtotime($start_date . ' +' . $duration . ' weeks'));
                    break;
                case 'month':
                    $end_date = date('Y-m-d H:i:s', strtotime($start_date . ' +' . $duration . ' months'));
                    break;
            }
        }
        
        // Check availability
        $quantity = isset($booking_data['booking_quantity']) ? $booking_data['booking_quantity'] : 1;
        
        if (!aqualuxe_bookings_is_available($product_id, $start_date, $end_date, $quantity, 0)) {
            // Add notice
            wc_add_notice(
                sprintf(
                    __('The booking for "%s" is no longer available. Please return to the cart and remove this item.', 'aqualuxe'),
                    $product->get_name()
                ),
                'error'
            );
        }
    }
}

/**
 * Get booking availability for a product
 *
 * @param int $product_id Product ID
 * @param string $start_date Start date
 * @param string $end_date End date
 * @return array Availability data
 */
function aqualuxe_bookings_get_availability($product_id, $start_date, $end_date) {
    global $wpdb;
    
    $bookings_table = $wpdb->prefix . 'aqualuxe_bookings';
    
    // Get product
    $product = wc_get_product($product_id);
    
    if (!$product || !aqualuxe_bookings_is_booking_product($product)) {
        return array(
            'available' => false,
            'max_bookings' => 0,
            'booked' => 0,
            'remaining' => 0,
        );
    }
    
    // Get max bookings
    $max_bookings = (int) get_post_meta($product_id, '_booking_max_bookings', true);
    
    if ($max_bookings <= 0) {
        $max_bookings = 1; // Default to 1 if not set
    }
    
    // Get existing bookings
    $query = $wpdb->prepare(
        "SELECT SUM(quantity) as total_quantity FROM {$bookings_table} 
         WHERE product_id = %d 
         AND status IN ('pending', 'confirmed') 
         AND ((start_date <= %s AND end_date >= %s) OR (start_date <= %s AND end_date >= %s) OR (start_date >= %s AND end_date <= %s))",
        $product_id,
        $end_date,
        $start_date,
        $start_date,
        $end_date,
        $start_date,
        $end_date
    );
    
    $result = $wpdb->get_var($query);
    
    $booked_quantity = $result ? (int) $result : 0;
    $remaining_quantity = $max_bookings - $booked_quantity;
    
    return array(
        'available' => $remaining_quantity > 0,
        'max_bookings' => $max_bookings,
        'booked' => $booked_quantity,
        'remaining' => $remaining_quantity,
    );
}

/**
 * Check if a date is available for booking
 *
 * @param int $product_id Product ID
 * @param string $date Date
 * @return bool True if date is available
 */
function aqualuxe_bookings_is_date_available($product_id, $date) {
    // Get product
    $product = wc_get_product($product_id);
    
    if (!$product || !aqualuxe_bookings_is_booking_product($product)) {
        return false;
    }
    
    // Check if date is in the past
    if (strtotime($date) < current_time('timestamp')) {
        return false;
    }
    
    // Check if date is within booking range
    $booking_range_start = get_post_meta($product_id, '_booking_range_start', true);
    $booking_range_end = get_post_meta($product_id, '_booking_range_end', true);
    
    if ($booking_range_start && strtotime($date) < strtotime($booking_range_start)) {
        return false;
    }
    
    if ($booking_range_end && strtotime($date) > strtotime($booking_range_end)) {
        return false;
    }
    
    // Check if date is a blocked date
    $blocked_dates = get_post_meta($product_id, '_booking_blocked_dates', true);
    
    if ($blocked_dates) {
        $blocked_dates = explode(',', $blocked_dates);
        
        if (in_array($date, $blocked_dates)) {
            return false;
        }
    }
    
    // Check if day of week is available
    $available_days = get_post_meta($product_id, '_booking_available_days', true);
    
    if ($available_days && is_array($available_days)) {
        $day_of_week = date('w', strtotime($date));
        
        if (!in_array($day_of_week, $available_days)) {
            return false;
        }
    }
    
    return true;
}

/**
 * Check if a time slot is available for booking
 *
 * @param int $product_id Product ID
 * @param string $date Date
 * @param string $start_time Start time
 * @param string $end_time End time
 * @param int $quantity Quantity
 * @return bool True if time slot is available
 */
function aqualuxe_bookings_is_time_slot_available($product_id, $date, $start_time, $end_time, $quantity = 1) {
    // Check if date is available
    if (!aqualuxe_bookings_is_date_available($product_id, $date)) {
        return false;
    }
    
    // Get product
    $product = wc_get_product($product_id);
    
    if (!$product || !aqualuxe_bookings_is_booking_product($product)) {
        return false;
    }
    
    // Get booking type
    $booking_type = get_post_meta($product_id, '_booking_type', true);
    
    if ($booking_type !== 'date_time' && $booking_type !== 'fixed_time') {
        return false;
    }
    
    // Get time slots
    $time_slots = get_post_meta($product_id, '_booking_time_slots', true);
    
    if (!$time_slots || !is_array($time_slots)) {
        return false;
    }
    
    // Get day of week
    $day_of_week = date('w', strtotime($date));
    
    // Check if time slot is valid
    $valid_slot = false;
    
    foreach ($time_slots as $slot) {
        if (
            isset($slot['days']) && is_array($slot['days']) && in_array($day_of_week, $slot['days']) &&
            isset($slot['start_time']) && isset($slot['end_time']) &&
            $slot['start_time'] === $start_time && $slot['end_time'] === $end_time
        ) {
            $valid_slot = true;
            break;
        }
    }
    
    if (!$valid_slot) {
        return false;
    }
    
    // Check availability
    $start_date = $date . ' ' . $start_time;
    $end_date = $date . ' ' . $end_time;
    
    $availability = aqualuxe_bookings_get_availability($product_id, $start_date, $end_date);
    
    return $availability['remaining'] >= $quantity;
}

/**
 * Check if a duration booking is available
 *
 * @param int $product_id Product ID
 * @param string $date Date
 * @param int $duration Duration
 * @param string $duration_unit Duration unit
 * @param int $quantity Quantity
 * @return bool True if duration booking is available
 */
function aqualuxe_bookings_is_duration_available($product_id, $date, $duration, $duration_unit, $quantity = 1) {
    // Check if date is available
    if (!aqualuxe_bookings_is_date_available($product_id, $date)) {
        return false;
    }
    
    // Get product
    $product = wc_get_product($product_id);
    
    if (!$product || !aqualuxe_bookings_is_booking_product($product)) {
        return false;
    }
    
    // Get booking type
    $booking_type = get_post_meta($product_id, '_booking_type', true);
    
    if ($booking_type !== 'duration') {
        return false;
    }
    
    // Check if duration is valid
    $min_duration = (int) get_post_meta($product_id, '_booking_min_duration', true);
    $max_duration = (int) get_post_meta($product_id, '_booking_max_duration', true);
    
    if ($min_duration > 0 && $duration < $min_duration) {
        return false;
    }
    
    if ($max_duration > 0 && $duration > $max_duration) {
        return false;
    }
    
    // Calculate end date
    $end_date = '';
    
    switch ($duration_unit) {
        case 'hour':
            $end_date = date('Y-m-d H:i:s', strtotime($date . ' +' . $duration . ' hours'));
            break;
        case 'day':
            $end_date = date('Y-m-d H:i:s', strtotime($date . ' +' . $duration . ' days'));
            break;
        case 'week':
            $end_date = date('Y-m-d H:i:s', strtotime($date . ' +' . $duration . ' weeks'));
            break;
        case 'month':
            $end_date = date('Y-m-d H:i:s', strtotime($date . ' +' . $duration . ' months'));
            break;
    }
    
    if (!$end_date) {
        return false;
    }
    
    // Check availability
    $availability = aqualuxe_bookings_get_availability($product_id, $date, $end_date);
    
    return $availability['remaining'] >= $quantity;
}

/**
 * Get available dates for a product
 *
 * @param int $product_id Product ID
 * @param string $start_date Start date
 * @param string $end_date End date
 * @return array Available dates
 */
function aqualuxe_bookings_get_available_dates($product_id, $start_date, $end_date) {
    // Get product
    $product = wc_get_product($product_id);
    
    if (!$product || !aqualuxe_bookings_is_booking_product($product)) {
        return array();
    }
    
    // Convert dates to timestamps
    $start_timestamp = strtotime($start_date);
    $end_timestamp = strtotime($end_date);
    
    if ($start_timestamp > $end_timestamp) {
        return array();
    }
    
    // Get available dates
    $available_dates = array();
    $current_timestamp = $start_timestamp;
    
    while ($current_timestamp <= $end_timestamp) {
        $current_date = date('Y-m-d', $current_timestamp);
        
        if (aqualuxe_bookings_is_date_available($product_id, $current_date)) {
            $available_dates[] = $current_date;
        }
        
        $current_timestamp = strtotime('+1 day', $current_timestamp);
    }
    
    return $available_dates;
}

/**
 * Get booking availability status text
 *
 * @param int $product_id Product ID
 * @param string $start_date Start date
 * @param string $end_date End date
 * @return string Availability status text
 */
function aqualuxe_bookings_get_availability_text($product_id, $start_date, $end_date) {
    $availability = aqualuxe_bookings_get_availability($product_id, $start_date, $end_date);
    
    if ($availability['available']) {
        if ($availability['remaining'] === 1) {
            return __('Only 1 booking available!', 'aqualuxe');
        } elseif ($availability['remaining'] < 5) {
            return sprintf(__('Only %d bookings available!', 'aqualuxe'), $availability['remaining']);
        } else {
            return __('Available', 'aqualuxe');
        }
    } else {
        return __('Not available', 'aqualuxe');
    }
}

/**
 * Get booking availability status HTML
 *
 * @param int $product_id Product ID
 * @param string $start_date Start date
 * @param string $end_date End date
 * @return string Availability status HTML
 */
function aqualuxe_bookings_get_availability_html($product_id, $start_date, $end_date) {
    $availability = aqualuxe_bookings_get_availability($product_id, $start_date, $end_date);
    
    if ($availability['available']) {
        if ($availability['remaining'] === 1) {
            return '<span class="booking-availability booking-limited">' . __('Only 1 booking available!', 'aqualuxe') . '</span>';
        } elseif ($availability['remaining'] < 5) {
            return '<span class="booking-availability booking-limited">' . sprintf(__('Only %d bookings available!', 'aqualuxe'), $availability['remaining']) . '</span>';
        } else {
            return '<span class="booking-availability booking-available">' . __('Available', 'aqualuxe') . '</span>';
        }
    } else {
        return '<span class="booking-availability booking-unavailable">' . __('Not available', 'aqualuxe') . '</span>';
    }
}