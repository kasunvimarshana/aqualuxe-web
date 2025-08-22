<?php
/**
 * Booking calendar functionality
 *
 * @package AquaLuxe
 * @subpackage Modules
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Initialize booking calendar
 */
function aqualuxe_bookings_initialize_calendar() {
    // Register AJAX actions
    add_action('wp_ajax_aqualuxe_get_booking_calendar', 'aqualuxe_bookings_ajax_get_calendar');
    add_action('wp_ajax_nopriv_aqualuxe_get_booking_calendar', 'aqualuxe_bookings_ajax_get_calendar');
    
    add_action('wp_ajax_aqualuxe_get_booking_time_slots', 'aqualuxe_bookings_ajax_get_time_slots');
    add_action('wp_ajax_nopriv_aqualuxe_get_booking_time_slots', 'aqualuxe_bookings_ajax_get_time_slots');
    
    // Add calendar to single product page
    add_action('woocommerce_before_add_to_cart_button', 'aqualuxe_bookings_calendar_form', 10);
    
    // Add booking data to cart item
    add_filter('woocommerce_add_cart_item_data', 'aqualuxe_bookings_add_cart_item_data', 10, 3);
    add_filter('woocommerce_get_item_data', 'aqualuxe_bookings_get_item_data', 10, 2);
    add_filter('woocommerce_get_cart_item_from_session', 'aqualuxe_bookings_get_cart_item_from_session', 10, 2);
    
    // Validate add to cart
    add_filter('woocommerce_add_to_cart_validation', 'aqualuxe_bookings_validate_add_to_cart', 10, 3);
    
    // Create booking on order creation
    add_action('woocommerce_checkout_create_order_line_item', 'aqualuxe_bookings_checkout_create_order_line_item', 10, 4);
    add_action('woocommerce_order_status_changed', 'aqualuxe_bookings_order_status_changed', 10, 3);
}

/**
 * AJAX handler for getting booking calendar
 */
function aqualuxe_bookings_ajax_get_calendar() {
    // Check nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'aqualuxe_bookings_nonce')) {
        wp_send_json_error(array(
            'message' => __('Security check failed.', 'aqualuxe'),
        ));
    }
    
    // Check product ID
    if (!isset($_POST['product_id'])) {
        wp_send_json_error(array(
            'message' => __('Invalid product ID.', 'aqualuxe'),
        ));
    }
    
    $product_id = absint($_POST['product_id']);
    $product = wc_get_product($product_id);
    
    if (!$product || !aqualuxe_bookings_is_booking_product($product)) {
        wp_send_json_error(array(
            'message' => __('Invalid booking product.', 'aqualuxe'),
        ));
    }
    
    // Get month and year
    $month = isset($_POST['month']) ? absint($_POST['month']) : date('n');
    $year = isset($_POST['year']) ? absint($_POST['year']) : date('Y');
    
    // Get calendar data
    $calendar_data = aqualuxe_bookings_get_calendar_data($product_id, $month, $year);
    
    wp_send_json_success($calendar_data);
}

/**
 * AJAX handler for getting booking time slots
 */
function aqualuxe_bookings_ajax_get_time_slots() {
    // Check nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'aqualuxe_bookings_nonce')) {
        wp_send_json_error(array(
            'message' => __('Security check failed.', 'aqualuxe'),
        ));
    }
    
    // Check product ID and date
    if (!isset($_POST['product_id']) || !isset($_POST['date'])) {
        wp_send_json_error(array(
            'message' => __('Invalid product ID or date.', 'aqualuxe'),
        ));
    }
    
    $product_id = absint($_POST['product_id']);
    $date = sanitize_text_field($_POST['date']);
    
    $product = wc_get_product($product_id);
    
    if (!$product || !aqualuxe_bookings_is_booking_product($product)) {
        wp_send_json_error(array(
            'message' => __('Invalid booking product.', 'aqualuxe'),
        ));
    }
    
    // Get time slots
    $time_slots = aqualuxe_bookings_get_available_time_slots($product_id, $date);
    
    wp_send_json_success(array(
        'time_slots' => $time_slots,
    ));
}

/**
 * Get booking calendar data
 *
 * @param int $product_id Product ID
 * @param int $month Month
 * @param int $year Year
 * @return array Calendar data
 */
function aqualuxe_bookings_get_calendar_data($product_id, $month, $year) {
    $product = wc_get_product($product_id);
    
    if (!$product || !aqualuxe_bookings_is_booking_product($product)) {
        return array();
    }
    
    // Get booking type
    $booking_type = get_post_meta($product_id, '_booking_type', true);
    
    // Get booking range
    $booking_range_start = get_post_meta($product_id, '_booking_range_start', true);
    $booking_range_end = get_post_meta($product_id, '_booking_range_end', true);
    
    // Get booking notice
    $booking_min_date = (int) get_post_meta($product_id, '_booking_min_date', true);
    $booking_min_date_unit = get_post_meta($product_id, '_booking_min_date_unit', true);
    $booking_max_date = (int) get_post_meta($product_id, '_booking_max_date', true);
    $booking_max_date_unit = get_post_meta($product_id, '_booking_max_date_unit', true);
    
    // Get available days
    $available_days = get_post_meta($product_id, '_booking_available_days', true);
    
    if (!is_array($available_days)) {
        $available_days = array(0, 1, 2, 3, 4, 5, 6); // Default to all days
    }
    
    // Get blocked dates
    $blocked_dates = get_post_meta($product_id, '_booking_blocked_dates', true);
    $blocked_dates = $blocked_dates ? explode(',', $blocked_dates) : array();
    
    // Calculate min and max dates
    $min_date = current_time('timestamp');
    
    if ($booking_min_date > 0) {
        if ($booking_min_date_unit === 'hour') {
            $min_date += $booking_min_date * HOUR_IN_SECONDS;
        } else {
            $min_date += $booking_min_date * DAY_IN_SECONDS;
        }
    }
    
    $max_date = 0;
    
    if ($booking_max_date > 0) {
        if ($booking_max_date_unit === 'day') {
            $max_date = $min_date + $booking_max_date * DAY_IN_SECONDS;
        } elseif ($booking_max_date_unit === 'week') {
            $max_date = $min_date + $booking_max_date * WEEK_IN_SECONDS;
        } elseif ($booking_max_date_unit === 'month') {
            $max_date = strtotime('+' . $booking_max_date . ' months', $min_date);
        }
    }
    
    // Override with booking range if set
    if ($booking_range_start) {
        $range_start_timestamp = strtotime($booking_range_start);
        
        if ($range_start_timestamp > $min_date) {
            $min_date = $range_start_timestamp;
        }
    }
    
    if ($booking_range_end) {
        $range_end_timestamp = strtotime($booking_range_end);
        
        if ($max_date === 0 || $range_end_timestamp < $max_date) {
            $max_date = $range_end_timestamp;
        }
    }
    
    // Get existing bookings
    $existing_bookings = aqualuxe_bookings_get_product_bookings($product_id, 'confirmed');
    
    // Calculate calendar data
    $first_day = mktime(0, 0, 0, $month, 1, $year);
    $days_in_month = date('t', $first_day);
    $first_day_of_week = date('w', $first_day);
    
    $prev_month = $month - 1;
    $prev_year = $year;
    
    if ($prev_month < 1) {
        $prev_month = 12;
        $prev_year--;
    }
    
    $next_month = $month + 1;
    $next_year = $year;
    
    if ($next_month > 12) {
        $next_month = 1;
        $next_year++;
    }
    
    $calendar_data = array(
        'month' => $month,
        'year' => $year,
        'days_in_month' => $days_in_month,
        'first_day_of_week' => $first_day_of_week,
        'prev_month' => $prev_month,
        'prev_year' => $prev_year,
        'next_month' => $next_month,
        'next_year' => $next_year,
        'days' => array(),
    );
    
    // Calculate days
    for ($day = 1; $day <= $days_in_month; $day++) {
        $date = sprintf('%04d-%02d-%02d', $year, $month, $day);
        $timestamp = strtotime($date);
        $day_of_week = date('w', $timestamp);
        
        $day_data = array(
            'date' => $date,
            'day' => $day,
            'available' => true,
            'past' => $timestamp < $min_date,
            'future' => $max_date > 0 && $timestamp > $max_date,
            'today' => date('Y-m-d', $timestamp) === date('Y-m-d', current_time('timestamp')),
            'day_of_week' => $day_of_week,
            'available_day' => in_array($day_of_week, $available_days),
            'blocked' => in_array($date, $blocked_dates),
            'fully_booked' => false,
        );
        
        // Check if day is available
        if ($day_data['past'] || $day_data['future'] || !$day_data['available_day'] || $day_data['blocked']) {
            $day_data['available'] = false;
        }
        
        // Check if day is fully booked
        if ($day_data['available']) {
            $max_bookings = (int) get_post_meta($product_id, '_booking_max_bookings', true);
            
            if ($max_bookings <= 0) {
                $max_bookings = 1;
            }
            
            $booked_count = 0;
            
            foreach ($existing_bookings as $booking) {
                $booking_start = strtotime($booking['start_date']);
                $booking_end = strtotime($booking['end_date']);
                
                if ($timestamp >= $booking_start && $timestamp <= $booking_end) {
                    $booked_count += (int) $booking['quantity'];
                }
            }
            
            if ($booked_count >= $max_bookings) {
                $day_data['fully_booked'] = true;
                $day_data['available'] = false;
            }
        }
        
        $calendar_data['days'][$day] = $day_data;
    }
    
    return $calendar_data;
}

/**
 * Display booking calendar form
 */
function aqualuxe_bookings_calendar_form() {
    global $product;
    
    if (!$product || !aqualuxe_bookings_is_booking_product($product)) {
        return;
    }
    
    // Get booking type
    $booking_type = get_post_meta($product->get_id(), '_booking_type', true);
    
    // Get booking duration
    $booking_duration = (int) get_post_meta($product->get_id(), '_booking_duration', true);
    $booking_duration_unit = get_post_meta($product->get_id(), '_booking_duration_unit', true);
    $booking_min_duration = (int) get_post_meta($product->get_id(), '_booking_min_duration', true);
    $booking_max_duration = (int) get_post_meta($product->get_id(), '_booking_max_duration', true);
    
    // Get calendar data
    $calendar_data = aqualuxe_bookings_get_calendar_data($product->get_id(), date('n'), date('Y'));
    
    ?>
    <div class="booking-form">
        <h3><?php esc_html_e('Book Now', 'aqualuxe'); ?></h3>
        
        <div class="booking-calendar-container">
            <div class="booking-calendar-header">
                <button type="button" class="booking-calendar-prev">&lt;</button>
                <div class="booking-calendar-title">
                    <span class="booking-calendar-month"><?php echo esc_html(date_i18n('F', mktime(0, 0, 0, $calendar_data['month'], 1, $calendar_data['year']))); ?></span>
                    <span class="booking-calendar-year"><?php echo esc_html($calendar_data['year']); ?></span>
                </div>
                <button type="button" class="booking-calendar-next">&gt;</button>
            </div>
            
            <div class="booking-calendar">
                <div class="booking-calendar-weekdays">
                    <?php for ($i = 0; $i < 7; $i++) : ?>
                        <div class="booking-calendar-weekday">
                            <?php echo esc_html(date_i18n('D', strtotime("Sunday +{$i} days"))); ?>
                        </div>
                    <?php endfor; ?>
                </div>
                
                <div class="booking-calendar-days">
                    <?php
                    // Add empty cells for days before the first day of the month
                    for ($i = 0; $i < $calendar_data['first_day_of_week']; $i++) {
                        echo '<div class="booking-calendar-day empty"></div>';
                    }
                    
                    // Add days of the month
                    for ($day = 1; $day <= $calendar_data['days_in_month']; $day++) {
                        $day_data = $calendar_data['days'][$day];
                        
                        $classes = array('booking-calendar-day');
                        
                        if ($day_data['past']) {
                            $classes[] = 'past';
                        }
                        
                        if ($day_data['future']) {
                            $classes[] = 'future';
                        }
                        
                        if ($day_data['today']) {
                            $classes[] = 'today';
                        }
                        
                        if (!$day_data['available_day']) {
                            $classes[] = 'unavailable-day';
                        }
                        
                        if ($day_data['blocked']) {
                            $classes[] = 'blocked';
                        }
                        
                        if ($day_data['fully_booked']) {
                            $classes[] = 'fully-booked';
                        }
                        
                        if ($day_data['available']) {
                            $classes[] = 'available';
                        } else {
                            $classes[] = 'unavailable';
                        }
                        
                        echo '<div class="' . esc_attr(implode(' ', $classes)) . '" data-date="' . esc_attr($day_data['date']) . '">' . esc_html($day) . '</div>';
                    }
                    
                    // Add empty cells for days after the last day of the month
                    $cells_count = $calendar_data['first_day_of_week'] + $calendar_data['days_in_month'];
                    $empty_cells_after = 7 - ($cells_count % 7);
                    
                    if ($empty_cells_after < 7) {
                        for ($i = 0; $i < $empty_cells_after; $i++) {
                            echo '<div class="booking-calendar-day empty"></div>';
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
        
        <?php if ($booking_type === 'date_time' || $booking_type === 'fixed_time') : ?>
            <div class="booking-time-container" style="display: none;">
                <h4><?php esc_html_e('Select Time', 'aqualuxe'); ?></h4>
                
                <div class="booking-time-slots">
                    <p class="booking-no-time-slots" style="display: none;"><?php esc_html_e('No time slots available for the selected date.', 'aqualuxe'); ?></p>
                </div>
            </div>
        <?php endif; ?>
        
        <?php if ($booking_type === 'duration') : ?>
            <div class="booking-duration-container" style="display: none;">
                <h4><?php esc_html_e('Select Duration', 'aqualuxe'); ?></h4>
                
                <div class="booking-duration">
                    <label for="booking_duration"><?php esc_html_e('Duration', 'aqualuxe'); ?></label>
                    <input type="number" name="booking_duration" id="booking_duration" value="<?php echo esc_attr($booking_duration); ?>" min="<?php echo esc_attr($booking_min_duration); ?>" max="<?php echo esc_attr($booking_max_duration); ?>" step="1" />
                    <span class="booking-duration-unit">
                        <?php
                        switch ($booking_duration_unit) {
                            case 'hour':
                                esc_html_e('hour(s)', 'aqualuxe');
                                break;
                            case 'day':
                                esc_html_e('day(s)', 'aqualuxe');
                                break;
                            case 'week':
                                esc_html_e('week(s)', 'aqualuxe');
                                break;
                            case 'month':
                                esc_html_e('month(s)', 'aqualuxe');
                                break;
                        }
                        ?>
                    </span>
                </div>
            </div>
        <?php endif; ?>
        
        <div class="booking-quantity-container" style="display: none;">
            <h4><?php esc_html_e('Quantity', 'aqualuxe'); ?></h4>
            
            <div class="booking-quantity">
                <label for="booking_quantity"><?php esc_html_e('Quantity', 'aqualuxe'); ?></label>
                <input type="number" name="booking_quantity" id="booking_quantity" value="1" min="1" step="1" />
            </div>
        </div>
        
        <div class="booking-summary" style="display: none;">
            <h4><?php esc_html_e('Booking Summary', 'aqualuxe'); ?></h4>
            
            <div class="booking-summary-date">
                <span class="label"><?php esc_html_e('Date:', 'aqualuxe'); ?></span>
                <span class="value"></span>
            </div>
            
            <?php if ($booking_type === 'date_time' || $booking_type === 'fixed_time') : ?>
                <div class="booking-summary-time">
                    <span class="label"><?php esc_html_e('Time:', 'aqualuxe'); ?></span>
                    <span class="value"></span>
                </div>
            <?php endif; ?>
            
            <?php if ($booking_type === 'duration') : ?>
                <div class="booking-summary-duration">
                    <span class="label"><?php esc_html_e('Duration:', 'aqualuxe'); ?></span>
                    <span class="value"></span>
                </div>
            <?php endif; ?>
            
            <div class="booking-summary-quantity">
                <span class="label"><?php esc_html_e('Quantity:', 'aqualuxe'); ?></span>
                <span class="value"></span>
            </div>
            
            <div class="booking-summary-price">
                <span class="label"><?php esc_html_e('Price:', 'aqualuxe'); ?></span>
                <span class="value"></span>
            </div>
        </div>
        
        <input type="hidden" name="booking_date" id="booking_date" value="" />
        <input type="hidden" name="booking_start_time" id="booking_start_time" value="" />
        <input type="hidden" name="booking_end_time" id="booking_end_time" value="" />
        <input type="hidden" name="booking_product_id" id="booking_product_id" value="<?php echo esc_attr($product->get_id()); ?>" />
    </div>
    <?php
}

/**
 * Add booking data to cart item
 *
 * @param array $cart_item_data Cart item data
 * @param int $product_id Product ID
 * @param int $variation_id Variation ID
 * @return array
 */
function aqualuxe_bookings_add_cart_item_data($cart_item_data, $product_id, $variation_id) {
    $product = wc_get_product($product_id);
    
    if (!$product || !aqualuxe_bookings_is_booking_product($product)) {
        return $cart_item_data;
    }
    
    // Get booking data from POST
    $booking_date = isset($_POST['booking_date']) ? sanitize_text_field($_POST['booking_date']) : '';
    $booking_start_time = isset($_POST['booking_start_time']) ? sanitize_text_field($_POST['booking_start_time']) : '';
    $booking_end_time = isset($_POST['booking_end_time']) ? sanitize_text_field($_POST['booking_end_time']) : '';
    $booking_duration = isset($_POST['booking_duration']) ? absint($_POST['booking_duration']) : 0;
    $booking_quantity = isset($_POST['booking_quantity']) ? absint($_POST['booking_quantity']) : 1;
    
    // Get booking type
    $booking_type = get_post_meta($product_id, '_booking_type', true);
    
    // Prepare booking data
    $booking_data = array(
        'booking_date' => $booking_date,
        'booking_type' => $booking_type,
        'booking_quantity' => $booking_quantity,
    );
    
    // Add type-specific data
    if ($booking_type === 'date_time' || $booking_type === 'fixed_time') {
        $booking_data['booking_start_time'] = $booking_start_time;
        $booking_data['booking_end_time'] = $booking_end_time;
    } elseif ($booking_type === 'duration') {
        $booking_data['booking_duration'] = $booking_duration;
        $booking_data['booking_duration_unit'] = get_post_meta($product_id, '_booking_duration_unit', true);
    }
    
    // Add booking data to cart item
    $cart_item_data['booking_data'] = $booking_data;
    
    return $cart_item_data;
}

/**
 * Display booking data in cart
 *
 * @param array $item_data Item data
 * @param array $cart_item Cart item
 * @return array
 */
function aqualuxe_bookings_get_item_data($item_data, $cart_item) {
    if (isset($cart_item['booking_data'])) {
        $booking_data = $cart_item['booking_data'];
        
        // Add date
        $item_data[] = array(
            'key' => __('Date', 'aqualuxe'),
            'value' => aqualuxe_bookings_format_date($booking_data['booking_date']),
        );
        
        // Add time
        if (isset($booking_data['booking_start_time']) && isset($booking_data['booking_end_time'])) {
            $item_data[] = array(
                'key' => __('Time', 'aqualuxe'),
                'value' => aqualuxe_bookings_format_time($booking_data['booking_start_time']) . ' - ' . aqualuxe_bookings_format_time($booking_data['booking_end_time']),
            );
        }
        
        // Add duration
        if (isset($booking_data['booking_duration']) && isset($booking_data['booking_duration_unit'])) {
            $duration_unit = '';
            
            switch ($booking_data['booking_duration_unit']) {
                case 'hour':
                    $duration_unit = _n('hour', 'hours', $booking_data['booking_duration'], 'aqualuxe');
                    break;
                case 'day':
                    $duration_unit = _n('day', 'days', $booking_data['booking_duration'], 'aqualuxe');
                    break;
                case 'week':
                    $duration_unit = _n('week', 'weeks', $booking_data['booking_duration'], 'aqualuxe');
                    break;
                case 'month':
                    $duration_unit = _n('month', 'months', $booking_data['booking_duration'], 'aqualuxe');
                    break;
            }
            
            $item_data[] = array(
                'key' => __('Duration', 'aqualuxe'),
                'value' => $booking_data['booking_duration'] . ' ' . $duration_unit,
            );
        }
    }
    
    return $item_data;
}

/**
 * Get booking cart item from session
 *
 * @param array $cart_item Cart item
 * @param array $values Cart item values
 * @return array
 */
function aqualuxe_bookings_get_cart_item_from_session($cart_item, $values) {
    if (isset($values['booking_data'])) {
        $cart_item['booking_data'] = $values['booking_data'];
    }
    
    return $cart_item;
}

/**
 * Validate add to cart
 *
 * @param bool $passed Whether validation passed
 * @param int $product_id Product ID
 * @param int $quantity Quantity
 * @return bool
 */
function aqualuxe_bookings_validate_add_to_cart($passed, $product_id, $quantity) {
    $product = wc_get_product($product_id);
    
    if (!$product || !aqualuxe_bookings_is_booking_product($product)) {
        return $passed;
    }
    
    // Get booking data from POST
    $booking_date = isset($_POST['booking_date']) ? sanitize_text_field($_POST['booking_date']) : '';
    $booking_start_time = isset($_POST['booking_start_time']) ? sanitize_text_field($_POST['booking_start_time']) : '';
    $booking_end_time = isset($_POST['booking_end_time']) ? sanitize_text_field($_POST['booking_end_time']) : '';
    $booking_duration = isset($_POST['booking_duration']) ? absint($_POST['booking_duration']) : 0;
    $booking_quantity = isset($_POST['booking_quantity']) ? absint($_POST['booking_quantity']) : 1;
    
    // Get booking type
    $booking_type = get_post_meta($product_id, '_booking_type', true);
    
    // Validate date
    if (empty($booking_date)) {
        wc_add_notice(__('Please select a booking date.', 'aqualuxe'), 'error');
        return false;
    }
    
    // Validate time
    if (($booking_type === 'date_time' || $booking_type === 'fixed_time') && (empty($booking_start_time) || empty($booking_end_time))) {
        wc_add_notice(__('Please select a booking time.', 'aqualuxe'), 'error');
        return false;
    }
    
    // Validate duration
    if ($booking_type === 'duration' && $booking_duration <= 0) {
        wc_add_notice(__('Please select a booking duration.', 'aqualuxe'), 'error');
        return false;
    }
    
    // Validate quantity
    if ($booking_quantity <= 0) {
        wc_add_notice(__('Please select a valid booking quantity.', 'aqualuxe'), 'error');
        return false;
    }
    
    // Prepare start and end dates
    $start_date = $booking_date;
    $end_date = $booking_date;
    
    if ($booking_type === 'date_time' || $booking_type === 'fixed_time') {
        $start_date .= ' ' . $booking_start_time;
        $end_date .= ' ' . $booking_end_time;
    } elseif ($booking_type === 'duration') {
        $duration_unit = get_post_meta($product_id, '_booking_duration_unit', true);
        
        switch ($duration_unit) {
            case 'hour':
                $end_date = date('Y-m-d H:i:s', strtotime($start_date . ' +' . $booking_duration . ' hours'));
                break;
            case 'day':
                $end_date = date('Y-m-d H:i:s', strtotime($start_date . ' +' . $booking_duration . ' days'));
                break;
            case 'week':
                $end_date = date('Y-m-d H:i:s', strtotime($start_date . ' +' . $booking_duration . ' weeks'));
                break;
            case 'month':
                $end_date = date('Y-m-d H:i:s', strtotime($start_date . ' +' . $booking_duration . ' months'));
                break;
        }
    }
    
    // Check if booking is available
    if (!aqualuxe_bookings_is_available($product_id, $start_date, $end_date, $booking_quantity)) {
        wc_add_notice(__('The selected booking is not available. Please choose a different date or time.', 'aqualuxe'), 'error');
        return false;
    }
    
    return $passed;
}

/**
 * Add booking data to order line item
 *
 * @param WC_Order_Item_Product $item Order item
 * @param string $cart_item_key Cart item key
 * @param array $values Cart item values
 * @param WC_Order $order Order object
 */
function aqualuxe_bookings_checkout_create_order_line_item($item, $cart_item_key, $values, $order) {
    if (isset($values['booking_data'])) {
        $booking_data = $values['booking_data'];
        
        // Add booking data to order item meta
        foreach ($booking_data as $key => $value) {
            $item->add_meta_data('_' . $key, $value);
        }
    }
}

/**
 * Create booking when order status changes
 *
 * @param int $order_id Order ID
 * @param string $old_status Old status
 * @param string $new_status New status
 */
function aqualuxe_bookings_order_status_changed($order_id, $old_status, $new_status) {
    // Only process on specific status changes
    $process_statuses = array('processing', 'completed');
    
    if (!in_array($new_status, $process_statuses)) {
        return;
    }
    
    $order = wc_get_order($order_id);
    
    if (!$order) {
        return;
    }
    
    // Process each order item
    foreach ($order->get_items() as $item_id => $item) {
        $product_id = $item->get_product_id();
        $product = wc_get_product($product_id);
        
        if (!$product || !aqualuxe_bookings_is_booking_product($product)) {
            continue;
        }
        
        // Get booking data from order item meta
        $booking_date = $item->get_meta('_booking_date');
        $booking_type = $item->get_meta('_booking_type');
        $booking_quantity = $item->get_meta('_booking_quantity');
        $booking_start_time = $item->get_meta('_booking_start_time');
        $booking_end_time = $item->get_meta('_booking_end_time');
        $booking_duration = $item->get_meta('_booking_duration');
        $booking_duration_unit = $item->get_meta('_booking_duration_unit');
        
        if (!$booking_date || !$booking_type) {
            continue;
        }
        
        // Prepare start and end dates
        $start_date = $booking_date;
        $end_date = $booking_date;
        
        if ($booking_type === 'date_time' || $booking_type === 'fixed_time') {
            $start_date .= ' ' . $booking_start_time;
            $end_date .= ' ' . $booking_end_time;
        } elseif ($booking_type === 'duration' && $booking_duration && $booking_duration_unit) {
            switch ($booking_duration_unit) {
                case 'hour':
                    $end_date = date('Y-m-d H:i:s', strtotime($start_date . ' +' . $booking_duration . ' hours'));
                    break;
                case 'day':
                    $end_date = date('Y-m-d H:i:s', strtotime($start_date . ' +' . $booking_duration . ' days'));
                    break;
                case 'week':
                    $end_date = date('Y-m-d H:i:s', strtotime($start_date . ' +' . $booking_duration . ' weeks'));
                    break;
                case 'month':
                    $end_date = date('Y-m-d H:i:s', strtotime($start_date . ' +' . $booking_duration . ' months'));
                    break;
            }
        }
        
        // Check if booking requires confirmation
        $requires_confirmation = get_post_meta($product_id, '_booking_requires_confirmation', true) === 'yes';
        $status = $requires_confirmation ? 'pending' : 'confirmed';
        
        // Create booking
        $booking_data = array(
            'product_id' => $product_id,
            'user_id' => $order->get_customer_id(),
            'order_id' => $order_id,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'status' => $status,
            'quantity' => $booking_quantity ? $booking_quantity : 1,
            'cost' => $item->get_total(),
            'meta' => array(
                'booking_type' => $booking_type,
                'booking_start_time' => $booking_start_time,
                'booking_end_time' => $booking_end_time,
                'booking_duration' => $booking_duration,
                'booking_duration_unit' => $booking_duration_unit,
            ),
        );
        
        $booking_id = aqualuxe_bookings_create_booking($booking_data);
        
        if ($booking_id) {
            // Add booking ID to order item meta
            $item->add_meta_data('_booking_id', $booking_id, true);
            $item->save();
        }
    }
}