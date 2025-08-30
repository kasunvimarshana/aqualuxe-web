<?php
/**
 * Bookings module functions
 *
 * @package AquaLuxe
 * @subpackage Modules
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Check if current page is a booking page
 *
 * @return bool True if current page is a booking page
 */
function aqualuxe_bookings_is_booking_page() {
    // Get booking pages
    $booking_pages = aqualuxe_bookings_get_pages();
    
    // Check if current page is a booking page
    if (is_page() && !empty($booking_pages)) {
        $current_page_id = get_the_ID();
        
        foreach ($booking_pages as $page_id) {
            if ($current_page_id == $page_id) {
                return true;
            }
        }
    }
    
    // Check for booking query var
    if (get_query_var('booking', false)) {
        return true;
    }
    
    return false;
}

/**
 * Check if current product is a booking product
 *
 * @param int|WC_Product $product Product ID or product object
 * @return bool True if product is a booking product
 */
function aqualuxe_bookings_is_booking_product($product = null) {
    if (!$product) {
        global $product;
    }
    
    if (!$product) {
        return false;
    }
    
    // Get product object
    if (is_numeric($product)) {
        $product = wc_get_product($product);
    }
    
    if (!$product) {
        return false;
    }
    
    return $product->get_type() === 'booking';
}

/**
 * Get booking pages
 *
 * @return array Booking page IDs
 */
function aqualuxe_bookings_get_pages() {
    $pages = array();
    
    // Bookings archive page
    $archive_page_id = aqualuxe_get_module_option('bookings', 'archive_page', 0);
    if ($archive_page_id) {
        $pages[] = $archive_page_id;
    }
    
    // My bookings page
    $my_bookings_page_id = aqualuxe_get_module_option('bookings', 'my_bookings_page', 0);
    if ($my_bookings_page_id) {
        $pages[] = $my_bookings_page_id;
    }
    
    // Dashboard page
    $dashboard_page_id = aqualuxe_get_module_option('bookings', 'dashboard_page', 0);
    if ($dashboard_page_id) {
        $pages[] = $dashboard_page_id;
    }
    
    return apply_filters('aqualuxe_booking_pages', $pages);
}

/**
 * Get booking types
 *
 * @return array Booking types
 */
function aqualuxe_bookings_get_types() {
    $types = array(
        'date' => __('Date', 'aqualuxe'),
        'date_time' => __('Date & Time', 'aqualuxe'),
        'duration' => __('Duration', 'aqualuxe'),
        'fixed_time' => __('Fixed Time Slots', 'aqualuxe'),
    );
    
    return apply_filters('aqualuxe_booking_types', $types);
}

/**
 * Get booking durations
 *
 * @return array Booking durations
 */
function aqualuxe_bookings_get_durations() {
    $durations = array(
        'hour' => __('Hour', 'aqualuxe'),
        'day' => __('Day', 'aqualuxe'),
        'week' => __('Week', 'aqualuxe'),
        'month' => __('Month', 'aqualuxe'),
    );
    
    return apply_filters('aqualuxe_booking_durations', $durations);
}

/**
 * Get booking status labels
 *
 * @return array Booking status labels
 */
function aqualuxe_bookings_get_status_labels() {
    $statuses = array(
        'pending' => __('Pending', 'aqualuxe'),
        'confirmed' => __('Confirmed', 'aqualuxe'),
        'completed' => __('Completed', 'aqualuxe'),
        'cancelled' => __('Cancelled', 'aqualuxe'),
    );
    
    return apply_filters('aqualuxe_booking_status_labels', $statuses);
}

/**
 * Get booking status label
 *
 * @param string $status Booking status
 * @return string Status label
 */
function aqualuxe_bookings_get_status_label($status) {
    $statuses = aqualuxe_bookings_get_status_labels();
    
    return isset($statuses[$status]) ? $statuses[$status] : $status;
}

/**
 * Get booking by ID
 *
 * @param int $booking_id Booking ID
 * @return array|false Booking data or false if not found
 */
function aqualuxe_bookings_get_booking($booking_id) {
    global $wpdb;
    
    $bookings_table = $wpdb->prefix . 'aqualuxe_bookings';
    
    $booking = $wpdb->get_row($wpdb->prepare(
        "SELECT * FROM {$bookings_table} WHERE id = %d",
        $booking_id
    ), ARRAY_A);
    
    if (!$booking) {
        return false;
    }
    
    return $booking;
}

/**
 * Get bookings by product ID
 *
 * @param int $product_id Product ID
 * @param string $status Booking status
 * @param array $args Additional arguments
 * @return array Bookings
 */
function aqualuxe_bookings_get_product_bookings($product_id, $status = '', $args = array()) {
    global $wpdb;
    
    $bookings_table = $wpdb->prefix . 'aqualuxe_bookings';
    
    $query = "SELECT * FROM {$bookings_table} WHERE product_id = %d";
    $params = array($product_id);
    
    if ($status) {
        $query .= " AND status = %s";
        $params[] = $status;
    }
    
    // Date range filter
    if (!empty($args['start_date'])) {
        $query .= " AND start_date >= %s";
        $params[] = $args['start_date'];
    }
    
    if (!empty($args['end_date'])) {
        $query .= " AND end_date <= %s";
        $params[] = $args['end_date'];
    }
    
    // Order by
    $query .= " ORDER BY start_date ASC";
    
    // Limit
    if (!empty($args['limit'])) {
        $query .= " LIMIT %d";
        $params[] = $args['limit'];
        
        if (!empty($args['offset'])) {
            $query .= " OFFSET %d";
            $params[] = $args['offset'];
        }
    }
    
    $bookings = $wpdb->get_results($wpdb->prepare($query, $params), ARRAY_A);
    
    return $bookings ? $bookings : array();
}

/**
 * Get bookings by user ID
 *
 * @param int $user_id User ID
 * @param string $status Booking status
 * @param array $args Additional arguments
 * @return array Bookings
 */
function aqualuxe_bookings_get_user_bookings($user_id, $status = '', $args = array()) {
    global $wpdb;
    
    $bookings_table = $wpdb->prefix . 'aqualuxe_bookings';
    
    $query = "SELECT * FROM {$bookings_table} WHERE user_id = %d";
    $params = array($user_id);
    
    if ($status) {
        $query .= " AND status = %s";
        $params[] = $status;
    }
    
    // Date range filter
    if (!empty($args['start_date'])) {
        $query .= " AND start_date >= %s";
        $params[] = $args['start_date'];
    }
    
    if (!empty($args['end_date'])) {
        $query .= " AND end_date <= %s";
        $params[] = $args['end_date'];
    }
    
    // Order by
    $query .= " ORDER BY start_date ASC";
    
    // Limit
    if (!empty($args['limit'])) {
        $query .= " LIMIT %d";
        $params[] = $args['limit'];
        
        if (!empty($args['offset'])) {
            $query .= " OFFSET %d";
            $params[] = $args['offset'];
        }
    }
    
    $bookings = $wpdb->get_results($wpdb->prepare($query, $params), ARRAY_A);
    
    return $bookings ? $bookings : array();
}

/**
 * Get upcoming bookings
 *
 * @param array $args Additional arguments
 * @return array Bookings
 */
function aqualuxe_bookings_get_upcoming_bookings($args = array()) {
    global $wpdb;
    
    $bookings_table = $wpdb->prefix . 'aqualuxe_bookings';
    
    $query = "SELECT * FROM {$bookings_table} WHERE status = 'confirmed' AND start_date >= %s";
    $params = array(current_time('mysql'));
    
    // Product filter
    if (!empty($args['product_id'])) {
        $query .= " AND product_id = %d";
        $params[] = $args['product_id'];
    }
    
    // User filter
    if (!empty($args['user_id'])) {
        $query .= " AND user_id = %d";
        $params[] = $args['user_id'];
    }
    
    // Date range filter
    if (!empty($args['end_date'])) {
        $query .= " AND start_date <= %s";
        $params[] = $args['end_date'];
    }
    
    // Order by
    $query .= " ORDER BY start_date ASC";
    
    // Limit
    if (!empty($args['limit'])) {
        $query .= " LIMIT %d";
        $params[] = $args['limit'];
        
        if (!empty($args['offset'])) {
            $query .= " OFFSET %d";
            $params[] = $args['offset'];
        }
    }
    
    $bookings = $wpdb->get_results($wpdb->prepare($query, $params), ARRAY_A);
    
    return $bookings ? $bookings : array();
}

/**
 * Create booking
 *
 * @param array $data Booking data
 * @return int|false Booking ID or false on failure
 */
function aqualuxe_bookings_create_booking($data) {
    global $wpdb;
    
    $bookings_table = $wpdb->prefix . 'aqualuxe_bookings';
    
    // Required fields
    $required_fields = array('product_id', 'user_id', 'start_date');
    
    foreach ($required_fields as $field) {
        if (empty($data[$field])) {
            return false;
        }
    }
    
    // Default values
    $defaults = array(
        'end_date' => $data['start_date'],
        'status' => 'pending',
        'quantity' => 1,
        'cost' => 0,
        'date_created' => current_time('mysql'),
    );
    
    $data = wp_parse_args($data, $defaults);
    
    // Insert booking
    $result = $wpdb->insert(
        $bookings_table,
        array(
            'product_id' => $data['product_id'],
            'user_id' => $data['user_id'],
            'order_id' => isset($data['order_id']) ? $data['order_id'] : 0,
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'status' => $data['status'],
            'quantity' => $data['quantity'],
            'cost' => $data['cost'],
            'date_created' => $data['date_created'],
            'meta' => isset($data['meta']) ? maybe_serialize($data['meta']) : '',
        ),
        array(
            '%d',
            '%d',
            '%d',
            '%s',
            '%s',
            '%s',
            '%d',
            '%f',
            '%s',
            '%s',
        )
    );
    
    if (!$result) {
        return false;
    }
    
    $booking_id = $wpdb->insert_id;
    
    // Trigger booking created action
    do_action('aqualuxe_booking_created', $booking_id, $data);
    
    return $booking_id;
}

/**
 * Update booking
 *
 * @param int $booking_id Booking ID
 * @param array $data Booking data
 * @return bool True on success, false on failure
 */
function aqualuxe_bookings_update_booking($booking_id, $data) {
    global $wpdb;
    
    $bookings_table = $wpdb->prefix . 'aqualuxe_bookings';
    
    // Get current booking
    $current_booking = aqualuxe_bookings_get_booking($booking_id);
    
    if (!$current_booking) {
        return false;
    }
    
    // Prepare data
    $update_data = array();
    $update_format = array();
    
    if (isset($data['product_id'])) {
        $update_data['product_id'] = $data['product_id'];
        $update_format[] = '%d';
    }
    
    if (isset($data['user_id'])) {
        $update_data['user_id'] = $data['user_id'];
        $update_format[] = '%d';
    }
    
    if (isset($data['order_id'])) {
        $update_data['order_id'] = $data['order_id'];
        $update_format[] = '%d';
    }
    
    if (isset($data['start_date'])) {
        $update_data['start_date'] = $data['start_date'];
        $update_format[] = '%s';
    }
    
    if (isset($data['end_date'])) {
        $update_data['end_date'] = $data['end_date'];
        $update_format[] = '%s';
    }
    
    if (isset($data['status'])) {
        $update_data['status'] = $data['status'];
        $update_format[] = '%s';
    }
    
    if (isset($data['quantity'])) {
        $update_data['quantity'] = $data['quantity'];
        $update_format[] = '%d';
    }
    
    if (isset($data['cost'])) {
        $update_data['cost'] = $data['cost'];
        $update_format[] = '%f';
    }
    
    if (isset($data['meta'])) {
        $update_data['meta'] = maybe_serialize($data['meta']);
        $update_format[] = '%s';
    }
    
    // Update booking
    $result = $wpdb->update(
        $bookings_table,
        $update_data,
        array('id' => $booking_id),
        $update_format,
        array('%d')
    );
    
    if ($result === false) {
        return false;
    }
    
    // Trigger booking updated action
    do_action('aqualuxe_booking_updated', $booking_id, $data, $current_booking);
    
    return true;
}

/**
 * Delete booking
 *
 * @param int $booking_id Booking ID
 * @return bool True on success, false on failure
 */
function aqualuxe_bookings_delete_booking($booking_id) {
    global $wpdb;
    
    $bookings_table = $wpdb->prefix . 'aqualuxe_bookings';
    
    // Get current booking
    $current_booking = aqualuxe_bookings_get_booking($booking_id);
    
    if (!$current_booking) {
        return false;
    }
    
    // Delete booking
    $result = $wpdb->delete(
        $bookings_table,
        array('id' => $booking_id),
        array('%d')
    );
    
    if (!$result) {
        return false;
    }
    
    // Trigger booking deleted action
    do_action('aqualuxe_booking_deleted', $booking_id, $current_booking);
    
    return true;
}

/**
 * Update booking status
 *
 * @param int $booking_id Booking ID
 * @param string $status New status
 * @return bool True on success, false on failure
 */
function aqualuxe_bookings_update_status($booking_id, $status) {
    // Get current booking
    $current_booking = aqualuxe_bookings_get_booking($booking_id);
    
    if (!$current_booking) {
        return false;
    }
    
    // Check if status is valid
    $valid_statuses = array_keys(aqualuxe_bookings_get_status_labels());
    
    if (!in_array($status, $valid_statuses)) {
        return false;
    }
    
    // Update booking
    $result = aqualuxe_bookings_update_booking($booking_id, array('status' => $status));
    
    if (!$result) {
        return false;
    }
    
    // Trigger booking status updated action
    do_action('aqualuxe_booking_status_updated', $booking_id, $status, $current_booking['status']);
    
    return true;
}

/**
 * Check if booking is available
 *
 * @param int $product_id Product ID
 * @param string $start_date Start date
 * @param string $end_date End date
 * @param int $quantity Quantity
 * @param int $exclude_booking_id Booking ID to exclude
 * @return bool True if booking is available
 */
function aqualuxe_bookings_is_available($product_id, $start_date, $end_date, $quantity = 1, $exclude_booking_id = 0) {
    global $wpdb;
    
    $bookings_table = $wpdb->prefix . 'aqualuxe_bookings';
    
    // Get product
    $product = wc_get_product($product_id);
    
    if (!$product || !aqualuxe_bookings_is_booking_product($product)) {
        return false;
    }
    
    // Get booking type
    $booking_type = get_post_meta($product_id, '_booking_type', true);
    
    // Get max bookings
    $max_bookings = (int) get_post_meta($product_id, '_booking_max_bookings', true);
    
    if ($max_bookings <= 0) {
        $max_bookings = 1; // Default to 1 if not set
    }
    
    // Check if date is in the past
    if (strtotime($start_date) < current_time('timestamp')) {
        return false;
    }
    
    // Check if date is within booking range
    $booking_range_start = get_post_meta($product_id, '_booking_range_start', true);
    $booking_range_end = get_post_meta($product_id, '_booking_range_end', true);
    
    if ($booking_range_start && strtotime($start_date) < strtotime($booking_range_start)) {
        return false;
    }
    
    if ($booking_range_end && strtotime($end_date) > strtotime($booking_range_end)) {
        return false;
    }
    
    // Check if date is a blocked date
    $blocked_dates = get_post_meta($product_id, '_booking_blocked_dates', true);
    
    if ($blocked_dates) {
        $blocked_dates = explode(',', $blocked_dates);
        
        foreach ($blocked_dates as $blocked_date) {
            $blocked_timestamp = strtotime($blocked_date);
            $start_timestamp = strtotime($start_date);
            $end_timestamp = strtotime($end_date);
            
            if ($blocked_timestamp >= $start_timestamp && $blocked_timestamp <= $end_timestamp) {
                return false;
            }
        }
    }
    
    // Check if day of week is available
    $available_days = get_post_meta($product_id, '_booking_available_days', true);
    
    if ($available_days && is_array($available_days)) {
        $start_day = date('w', strtotime($start_date));
        
        if (!in_array($start_day, $available_days)) {
            return false;
        }
    }
    
    // Check for existing bookings
    $query = "SELECT SUM(quantity) as total_quantity FROM {$bookings_table} 
              WHERE product_id = %d 
              AND status IN ('pending', 'confirmed') 
              AND ((start_date <= %s AND end_date >= %s) OR (start_date <= %s AND end_date >= %s) OR (start_date >= %s AND end_date <= %s))";
    
    $params = array(
        $product_id,
        $end_date,
        $start_date,
        $start_date,
        $end_date,
        $start_date,
        $end_date,
    );
    
    if ($exclude_booking_id) {
        $query .= " AND id != %d";
        $params[] = $exclude_booking_id;
    }
    
    $result = $wpdb->get_var($wpdb->prepare($query, $params));
    
    $total_quantity = $result ? (int) $result : 0;
    
    // Check if there's enough availability
    return ($total_quantity + $quantity) <= $max_bookings;
}

/**
 * Get available time slots
 *
 * @param int $product_id Product ID
 * @param string $date Date
 * @return array Available time slots
 */
function aqualuxe_bookings_get_available_time_slots($product_id, $date) {
    global $wpdb;
    
    $bookings_table = $wpdb->prefix . 'aqualuxe_bookings';
    
    // Get product
    $product = wc_get_product($product_id);
    
    if (!$product || !aqualuxe_bookings_is_booking_product($product)) {
        return array();
    }
    
    // Get booking type
    $booking_type = get_post_meta($product_id, '_booking_type', true);
    
    if ($booking_type !== 'fixed_time' && $booking_type !== 'date_time') {
        return array();
    }
    
    // Get time slots
    $time_slots = get_post_meta($product_id, '_booking_time_slots', true);
    
    if (!$time_slots || !is_array($time_slots)) {
        return array();
    }
    
    // Get day of week
    $day_of_week = date('w', strtotime($date));
    
    // Filter time slots by day of week
    $available_slots = array();
    
    foreach ($time_slots as $slot) {
        if (isset($slot['days']) && is_array($slot['days']) && in_array($day_of_week, $slot['days'])) {
            $available_slots[] = $slot;
        }
    }
    
    // Get max bookings
    $max_bookings = (int) get_post_meta($product_id, '_booking_max_bookings', true);
    
    if ($max_bookings <= 0) {
        $max_bookings = 1; // Default to 1 if not set
    }
    
    // Check for existing bookings
    $existing_bookings = $wpdb->get_results($wpdb->prepare(
        "SELECT start_date, end_date, quantity FROM {$bookings_table} 
         WHERE product_id = %d 
         AND status IN ('pending', 'confirmed') 
         AND DATE(start_date) = %s",
        $product_id,
        $date
    ), ARRAY_A);
    
    // Filter out booked slots
    $result = array();
    
    foreach ($available_slots as $slot) {
        $start_time = $slot['start_time'];
        $end_time = $slot['end_time'];
        
        $start_datetime = $date . ' ' . $start_time;
        $end_datetime = $date . ' ' . $end_time;
        
        $booked_quantity = 0;
        
        foreach ($existing_bookings as $booking) {
            $booking_start = $booking['start_date'];
            $booking_end = $booking['end_date'];
            
            if (
                ($booking_start <= $end_datetime && $booking_end >= $start_datetime) ||
                ($booking_start <= $start_datetime && $booking_end >= $end_datetime) ||
                ($booking_start >= $start_datetime && $booking_end <= $end_datetime)
            ) {
                $booked_quantity += (int) $booking['quantity'];
            }
        }
        
        $available_quantity = $max_bookings - $booked_quantity;
        
        if ($available_quantity > 0) {
            $slot['available_quantity'] = $available_quantity;
            $result[] = $slot;
        }
    }
    
    return $result;
}

/**
 * Format booking date
 *
 * @param string $date Date string
 * @param string $format Date format
 * @return string Formatted date
 */
function aqualuxe_bookings_format_date($date, $format = '') {
    if (!$date) {
        return '';
    }
    
    if (!$format) {
        $format = get_option('date_format');
    }
    
    return date_i18n($format, strtotime($date));
}

/**
 * Format booking time
 *
 * @param string $time Time string
 * @param string $format Time format
 * @return string Formatted time
 */
function aqualuxe_bookings_format_time($time, $format = '') {
    if (!$time) {
        return '';
    }
    
    if (!$format) {
        $format = get_option('time_format');
    }
    
    return date_i18n($format, strtotime('2000-01-01 ' . $time));
}

/**
 * Format booking datetime
 *
 * @param string $datetime Datetime string
 * @param string $format Datetime format
 * @return string Formatted datetime
 */
function aqualuxe_bookings_format_datetime($datetime, $format = '') {
    if (!$datetime) {
        return '';
    }
    
    if (!$format) {
        $format = get_option('date_format') . ' ' . get_option('time_format');
    }
    
    return date_i18n($format, strtotime($datetime));
}

/**
 * Get booking archive URL
 *
 * @return string Booking archive URL
 */
function aqualuxe_bookings_get_archive_url() {
    $archive_page_id = aqualuxe_get_module_option('bookings', 'archive_page', 0);
    
    if ($archive_page_id) {
        return get_permalink($archive_page_id);
    }
    
    return get_post_type_archive_link('product') . '?product_type=booking';
}

/**
 * Get my bookings URL
 *
 * @return string My bookings URL
 */
function aqualuxe_bookings_get_my_bookings_url() {
    $my_bookings_page_id = aqualuxe_get_module_option('bookings', 'my_bookings_page', 0);
    
    if ($my_bookings_page_id) {
        return get_permalink($my_bookings_page_id);
    }
    
    return wc_get_account_endpoint_url('bookings');
}

/**
 * Get booking dashboard URL
 *
 * @return string Booking dashboard URL
 */
function aqualuxe_bookings_get_dashboard_url() {
    $dashboard_page_id = aqualuxe_get_module_option('bookings', 'dashboard_page', 0);
    
    if ($dashboard_page_id) {
        return get_permalink($dashboard_page_id);
    }
    
    return home_url('/my-account/');
}

/**
 * Create booking tables
 */
function aqualuxe_bookings_create_tables() {
    global $wpdb;
    
    $charset_collate = $wpdb->get_charset_collate();
    
    // Create bookings table
    $bookings_table = $wpdb->prefix . 'aqualuxe_bookings';
    
    $sql = "CREATE TABLE {$bookings_table} (
        id bigint(20) NOT NULL AUTO_INCREMENT,
        product_id bigint(20) NOT NULL,
        user_id bigint(20) NOT NULL,
        order_id bigint(20) NOT NULL DEFAULT 0,
        start_date datetime NOT NULL,
        end_date datetime NOT NULL,
        status varchar(20) NOT NULL DEFAULT 'pending',
        quantity int(11) NOT NULL DEFAULT 1,
        cost decimal(19,4) NOT NULL DEFAULT 0,
        date_created datetime NOT NULL,
        meta longtext,
        PRIMARY KEY  (id),
        KEY product_id (product_id),
        KEY user_id (user_id),
        KEY order_id (order_id),
        KEY status (status),
        KEY start_date (start_date),
        KEY end_date (end_date)
    ) {$charset_collate};";
    
    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta($sql);
}