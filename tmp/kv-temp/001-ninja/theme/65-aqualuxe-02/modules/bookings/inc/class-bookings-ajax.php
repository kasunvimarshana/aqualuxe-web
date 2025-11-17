<?php
/**
 * Bookings AJAX Handler
 *
 * Handles AJAX requests for the bookings module.
 *
 * @package AquaLuxe
 * @subpackage Bookings
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Bookings AJAX Class
 */
class AquaLuxe_Bookings_AJAX {
    /**
     * Constructor
     */
    public function __construct() {
        // Initialize hooks
        $this->init_hooks();
    }

    /**
     * Initialize hooks
     */
    private function init_hooks() {
        // Frontend AJAX actions
        add_action('wp_ajax_get_available_dates', array($this, 'get_available_dates'));
        add_action('wp_ajax_nopriv_get_available_dates', array($this, 'get_available_dates'));
        
        add_action('wp_ajax_get_available_times', array($this, 'get_available_times'));
        add_action('wp_ajax_nopriv_get_available_times', array($this, 'get_available_times'));
        
        add_action('wp_ajax_calculate_booking_price', array($this, 'calculate_booking_price'));
        add_action('wp_ajax_nopriv_calculate_booking_price', array($this, 'calculate_booking_price'));
        
        add_action('wp_ajax_validate_booking_form', array($this, 'validate_booking_form'));
        add_action('wp_ajax_nopriv_validate_booking_form', array($this, 'validate_booking_form'));
        
        // Admin AJAX actions
        add_action('wp_ajax_get_booking_details', array($this, 'get_booking_details'));
        add_action('wp_ajax_update_booking_status', array($this, 'update_booking_status'));
        add_action('wp_ajax_delete_booking', array($this, 'delete_booking'));
        add_action('wp_ajax_get_calendar_events', array($this, 'get_calendar_events'));
        add_action('wp_ajax_save_availability_rule', array($this, 'save_availability_rule'));
        add_action('wp_ajax_delete_availability_rule', array($this, 'delete_availability_rule'));
    }

    /**
     * Get available dates for a service
     */
    public function get_available_dates() {
        // Check nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'aqualuxe-bookings')) {
            wp_send_json_error(array('message' => __('Invalid security token', 'aqualuxe')));
        }

        // Get service ID
        $service_id = isset($_POST['service_id']) ? absint($_POST['service_id']) : 0;
        
        if (empty($service_id)) {
            wp_send_json_error(array('message' => __('Invalid service', 'aqualuxe')));
        }

        // Get month and year
        $month = isset($_POST['month']) ? absint($_POST['month']) : date('n');
        $year = isset($_POST['year']) ? absint($_POST['year']) : date('Y');

        // Get first and last day of month
        $first_day = strtotime($year . '-' . $month . '-01');
        $last_day = strtotime(date('Y-m-t', $first_day));

        // Get available dates
        $available_dates = $this->get_service_available_dates($service_id, $first_day, $last_day);

        wp_send_json_success(array(
            'dates' => $available_dates,
            'month' => $month,
            'year' => $year,
        ));
    }

    /**
     * Get available times for a service on a specific date
     */
    public function get_available_times() {
        // Check nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'aqualuxe-bookings')) {
            wp_send_json_error(array('message' => __('Invalid security token', 'aqualuxe')));
        }

        // Get service ID and date
        $service_id = isset($_POST['service_id']) ? absint($_POST['service_id']) : 0;
        $date = isset($_POST['date']) ? sanitize_text_field($_POST['date']) : '';
        
        if (empty($service_id) || empty($date)) {
            wp_send_json_error(array('message' => __('Invalid service or date', 'aqualuxe')));
        }

        // Get available time slots
        $time_slots = $this->get_service_available_times($service_id, $date);

        wp_send_json_success(array(
            'times' => $time_slots,
            'date' => $date,
        ));
    }

    /**
     * Calculate booking price
     */
    public function calculate_booking_price() {
        // Check nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'aqualuxe-bookings')) {
            wp_send_json_error(array('message' => __('Invalid security token', 'aqualuxe')));
        }

        // Get form data
        $service_id = isset($_POST['service_id']) ? absint($_POST['service_id']) : 0;
        $date = isset($_POST['date']) ? sanitize_text_field($_POST['date']) : '';
        $time = isset($_POST['time']) ? sanitize_text_field($_POST['time']) : '';
        $duration = isset($_POST['duration']) ? absint($_POST['duration']) : 0;
        $quantity = isset($_POST['quantity']) ? absint($_POST['quantity']) : 1;
        
        if (empty($service_id)) {
            wp_send_json_error(array('message' => __('Invalid service', 'aqualuxe')));
        }

        // Get service price
        $price = get_post_meta($service_id, '_service_price', true);
        $price = !empty($price) ? floatval($price) : 0;
        
        // Calculate total
        $total = $price * $quantity;

        // Format price
        $formatted_price = function_exists('wc_price') ? wc_price($price) : '$' . number_format($price, 2);
        $formatted_total = function_exists('wc_price') ? wc_price($total) : '$' . number_format($total, 2);

        wp_send_json_success(array(
            'price' => $price,
            'total' => $total,
            'formatted_price' => $formatted_price,
            'formatted_total' => $formatted_total,
        ));
    }

    /**
     * Validate booking form
     */
    public function validate_booking_form() {
        // Check nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'aqualuxe-bookings')) {
            wp_send_json_error(array('message' => __('Invalid security token', 'aqualuxe')));
        }

        // Get form data
        $service_id = isset($_POST['service_id']) ? absint($_POST['service_id']) : 0;
        $date = isset($_POST['date']) ? sanitize_text_field($_POST['date']) : '';
        $time = isset($_POST['time']) ? sanitize_text_field($_POST['time']) : '';
        $duration = isset($_POST['duration']) ? absint($_POST['duration']) : 0;
        
        if (empty($service_id) || empty($date) || empty($time)) {
            wp_send_json_error(array('message' => __('Please fill in all required fields', 'aqualuxe')));
        }

        // Calculate start and end dates
        $start_date = date('Y-m-d H:i:s', strtotime($date . ' ' . $time));
        
        // If duration is not provided, get it from the service
        if (empty($duration)) {
            $duration = get_post_meta($service_id, '_service_duration', true);
            $duration = !empty($duration) ? intval($duration) : 60; // Default to 60 minutes
        }
        
        $end_date = date('Y-m-d H:i:s', strtotime($start_date . ' +' . $duration . ' minutes'));

        // Check if time slot is available
        $bookings_data = new AquaLuxe_Bookings_Data();
        
        if (!$bookings_data->is_time_slot_available($service_id, $start_date, $end_date)) {
            wp_send_json_error(array('message' => __('Sorry, this time slot is no longer available. Please select another time.', 'aqualuxe')));
        }

        wp_send_json_success(array(
            'message' => __('Time slot is available', 'aqualuxe'),
        ));
    }

    /**
     * Get booking details (admin)
     */
    public function get_booking_details() {
        // Check permissions
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('You do not have permission to do this', 'aqualuxe')));
        }

        // Check nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'aqualuxe-bookings-admin')) {
            wp_send_json_error(array('message' => __('Invalid security token', 'aqualuxe')));
        }

        // Get booking ID
        $booking_id = isset($_POST['booking_id']) ? absint($_POST['booking_id']) : 0;
        
        if (empty($booking_id)) {
            wp_send_json_error(array('message' => __('Invalid booking', 'aqualuxe')));
        }

        // Get booking data
        $bookings_data = new AquaLuxe_Bookings_Data();
        $booking = $bookings_data->get_booking($booking_id);

        if (!$booking) {
            wp_send_json_error(array('message' => __('Booking not found', 'aqualuxe')));
        }

        // Format date and time
        $date_format = get_option('date_format');
        $time_format = get_option('time_format');
        
        $booking['formatted_date'] = date_i18n($date_format, strtotime($booking['start_date']));
        $booking['formatted_time'] = date_i18n($time_format, strtotime($booking['start_date'])) . ' - ' . date_i18n($time_format, strtotime($booking['end_date']));
        $booking['formatted_total'] = function_exists('wc_price') ? wc_price($booking['total']) : '$' . number_format($booking['total'], 2);

        // Get customer notes
        $booking['customer_notes'] = get_post_meta($booking_id, '_customer_notes', true);

        wp_send_json_success(array(
            'booking' => $booking,
        ));
    }

    /**
     * Update booking status (admin)
     */
    public function update_booking_status() {
        // Check permissions
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('You do not have permission to do this', 'aqualuxe')));
        }

        // Check nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'aqualuxe-bookings-admin')) {
            wp_send_json_error(array('message' => __('Invalid security token', 'aqualuxe')));
        }

        // Get booking ID and status
        $booking_id = isset($_POST['booking_id']) ? absint($_POST['booking_id']) : 0;
        $status = isset($_POST['status']) ? sanitize_text_field($_POST['status']) : '';
        
        if (empty($booking_id) || empty($status)) {
            wp_send_json_error(array('message' => __('Invalid booking or status', 'aqualuxe')));
        }

        // Update booking status
        $bookings_data = new AquaLuxe_Bookings_Data();
        $result = $bookings_data->update_booking($booking_id, array('status' => $status));

        if (is_wp_error($result)) {
            wp_send_json_error(array('message' => $result->get_error_message()));
        }

        // Send notification if status changed to confirmed
        if ('aqualuxe-confirmed' === $status) {
            $booking = $bookings_data->get_booking($booking_id);
            
            if ($booking && !empty($booking['customer_email'])) {
                $this->send_status_notification($booking);
            }
        }

        wp_send_json_success(array(
            'message' => __('Booking status updated successfully', 'aqualuxe'),
        ));
    }

    /**
     * Delete booking (admin)
     */
    public function delete_booking() {
        // Check permissions
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('You do not have permission to do this', 'aqualuxe')));
        }

        // Check nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'aqualuxe-bookings-admin')) {
            wp_send_json_error(array('message' => __('Invalid security token', 'aqualuxe')));
        }

        // Get booking ID
        $booking_id = isset($_POST['booking_id']) ? absint($_POST['booking_id']) : 0;
        
        if (empty($booking_id)) {
            wp_send_json_error(array('message' => __('Invalid booking', 'aqualuxe')));
        }

        // Delete booking
        $bookings_data = new AquaLuxe_Bookings_Data();
        $result = $bookings_data->delete_booking($booking_id);

        if (!$result) {
            wp_send_json_error(array('message' => __('Error deleting booking', 'aqualuxe')));
        }

        wp_send_json_success(array(
            'message' => __('Booking deleted successfully', 'aqualuxe'),
        ));
    }

    /**
     * Get calendar events (admin)
     */
    public function get_calendar_events() {
        // Check permissions
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('You do not have permission to do this', 'aqualuxe')));
        }

        // Check nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'aqualuxe-bookings-admin')) {
            wp_send_json_error(array('message' => __('Invalid security token', 'aqualuxe')));
        }

        // Get date range
        $start = isset($_POST['start']) ? sanitize_text_field($_POST['start']) : '';
        $end = isset($_POST['end']) ? sanitize_text_field($_POST['end']) : '';
        $service_id = isset($_POST['service_id']) ? absint($_POST['service_id']) : 0;
        
        if (empty($start) || empty($end)) {
            wp_send_json_error(array('message' => __('Invalid date range', 'aqualuxe')));
        }

        // Get bookings
        $bookings_data = new AquaLuxe_Bookings_Data();
        $args = array(
            'date_from' => $start,
            'date_to' => $end,
        );
        
        if (!empty($service_id)) {
            $args['service_id'] = $service_id;
        }
        
        $bookings = $bookings_data->get_bookings($args);

        // Format bookings as calendar events
        $events = array();
        
        foreach ($bookings as $booking) {
            // Set color based on status
            $color = '#3788d8'; // Default blue
            
            switch ($booking['status']) {
                case 'aqualuxe-pending':
                    $color = '#f8dda7'; // Yellow
                    break;
                case 'aqualuxe-confirmed':
                    $color = '#c6e1c6'; // Green
                    break;
                case 'aqualuxe-completed':
                    $color = '#c8d7e1'; // Blue
                    break;
                case 'aqualuxe-cancelled':
                    $color = '#eba3a3'; // Red
                    break;
            }
            
            $events[] = array(
                'id' => $booking['id'],
                'title' => $booking['customer_name'] . ' - ' . $booking['service_name'],
                'start' => $booking['start_date'],
                'end' => $booking['end_date'],
                'allDay' => (bool) $booking['all_day'],
                'backgroundColor' => $color,
                'borderColor' => $color,
                'extendedProps' => array(
                    'booking_id' => $booking['booking_id'],
                    'service_id' => $booking['service_id'],
                    'service_name' => $booking['service_name'],
                    'customer_name' => $booking['customer_name'],
                    'customer_email' => $booking['customer_email'],
                    'customer_phone' => $booking['customer_phone'],
                    'status' => $booking['status'],
                    'quantity' => $booking['quantity'],
                    'total' => $booking['total'],
                ),
            );
        }

        // Get availability rules
        $availability_rules = $this->get_availability_rules($service_id, $start, $end);
        
        // Add availability rules to events
        foreach ($availability_rules as $rule) {
            $events[] = array(
                'id' => 'rule_' . $rule['id'],
                'title' => 'Unavailable',
                'start' => $rule['date_from'],
                'end' => $rule['date_to'],
                'allDay' => true,
                'backgroundColor' => '#f8d7da',
                'borderColor' => '#f5c6cb',
                'rendering' => 'background',
                'extendedProps' => array(
                    'rule_id' => $rule['id'],
                    'type' => 'availability_rule',
                ),
            );
        }

        wp_send_json_success(array(
            'events' => $events,
        ));
    }

    /**
     * Save availability rule (admin)
     */
    public function save_availability_rule() {
        // Check permissions
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('You do not have permission to do this', 'aqualuxe')));
        }

        // Check nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'aqualuxe-bookings-admin')) {
            wp_send_json_error(array('message' => __('Invalid security token', 'aqualuxe')));
        }

        // Get rule data
        $rule_id = isset($_POST['rule_id']) ? absint($_POST['rule_id']) : 0;
        $service_id = isset($_POST['service_id']) ? absint($_POST['service_id']) : 0;
        $date_from = isset($_POST['date_from']) ? sanitize_text_field($_POST['date_from']) : '';
        $date_to = isset($_POST['date_to']) ? sanitize_text_field($_POST['date_to']) : '';
        $bookable = isset($_POST['bookable']) ? (bool) $_POST['bookable'] : false;
        
        if (empty($service_id) || empty($date_from) || empty($date_to)) {
            wp_send_json_error(array('message' => __('Please fill in all required fields', 'aqualuxe')));
        }

        global $wpdb;
        $table_name = $wpdb->prefix . 'aqualuxe_booking_availability';

        // Format dates
        $date_from = date('Y-m-d H:i:s', strtotime($date_from));
        $date_to = date('Y-m-d H:i:s', strtotime($date_to));

        // Check if rule exists
        if ($rule_id > 0) {
            // Update rule
            $result = $wpdb->update(
                $table_name,
                array(
                    'service_id' => $service_id,
                    'date_from' => $date_from,
                    'date_to' => $date_to,
                    'bookable' => $bookable ? 1 : 0,
                ),
                array('id' => $rule_id),
                array('%d', '%s', '%s', '%d'),
                array('%d')
            );
        } else {
            // Insert rule
            $result = $wpdb->insert(
                $table_name,
                array(
                    'service_id' => $service_id,
                    'date_from' => $date_from,
                    'date_to' => $date_to,
                    'type' => 'custom',
                    'bookable' => $bookable ? 1 : 0,
                    'priority' => 10,
                ),
                array('%d', '%s', '%s', '%s', '%d', '%d')
            );
            
            $rule_id = $wpdb->insert_id;
        }

        if (false === $result) {
            wp_send_json_error(array('message' => __('Error saving availability rule', 'aqualuxe')));
        }

        wp_send_json_success(array(
            'message' => __('Availability rule saved successfully', 'aqualuxe'),
            'rule_id' => $rule_id,
        ));
    }

    /**
     * Delete availability rule (admin)
     */
    public function delete_availability_rule() {
        // Check permissions
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('You do not have permission to do this', 'aqualuxe')));
        }

        // Check nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'aqualuxe-bookings-admin')) {
            wp_send_json_error(array('message' => __('Invalid security token', 'aqualuxe')));
        }

        // Get rule ID
        $rule_id = isset($_POST['rule_id']) ? absint($_POST['rule_id']) : 0;
        
        if (empty($rule_id)) {
            wp_send_json_error(array('message' => __('Invalid rule', 'aqualuxe')));
        }

        global $wpdb;
        $table_name = $wpdb->prefix . 'aqualuxe_booking_availability';

        // Delete rule
        $result = $wpdb->delete(
            $table_name,
            array('id' => $rule_id),
            array('%d')
        );

        if (false === $result) {
            wp_send_json_error(array('message' => __('Error deleting availability rule', 'aqualuxe')));
        }

        wp_send_json_success(array(
            'message' => __('Availability rule deleted successfully', 'aqualuxe'),
        ));
    }

    /**
     * Get service available dates
     *
     * @param int $service_id Service ID
     * @param int $start_timestamp Start timestamp
     * @param int $end_timestamp End timestamp
     * @return array Available dates
     */
    private function get_service_available_dates($service_id, $start_timestamp, $end_timestamp) {
        global $wpdb;
        $available_dates = array();
        
        // Get service availability rules
        $table_name = $wpdb->prefix . 'aqualuxe_booking_availability';
        
        $unavailable_dates = $wpdb->get_results($wpdb->prepare(
            "SELECT DATE(date_from) as date_from, DATE(date_to) as date_to 
            FROM $table_name 
            WHERE service_id = %d 
            AND bookable = 0 
            AND (
                (date_from <= %s AND date_to >= %s) OR
                (date_from >= %s AND date_from <= %s) OR
                (date_to >= %s AND date_to <= %s)
            )",
            $service_id,
            date('Y-m-d H:i:s', $end_timestamp),
            date('Y-m-d H:i:s', $start_timestamp),
            date('Y-m-d H:i:s', $start_timestamp),
            date('Y-m-d H:i:s', $end_timestamp),
            date('Y-m-d H:i:s', $start_timestamp),
            date('Y-m-d H:i:s', $end_timestamp)
        ), ARRAY_A);
        
        // Convert to array of unavailable dates
        $unavailable = array();
        
        foreach ($unavailable_dates as $rule) {
            $from = strtotime($rule['date_from']);
            $to = strtotime($rule['date_to']);
            
            for ($day = $from; $day <= $to; $day += 86400) {
                $unavailable[date('Y-m-d', $day)] = true;
            }
        }
        
        // Get all dates in range
        for ($day = $start_timestamp; $day <= $end_timestamp; $day += 86400) {
            $date = date('Y-m-d', $day);
            
            // Check if date is unavailable
            if (isset($unavailable[$date])) {
                continue;
            }
            
            // Check if date is in the past
            if ($day < strtotime('today')) {
                continue;
            }
            
            // Check if there are any available time slots on this date
            $has_available_slots = $this->check_date_has_available_slots($service_id, $date);
            
            if ($has_available_slots) {
                $available_dates[] = $date;
            }
        }
        
        return $available_dates;
    }

    /**
     * Check if a date has any available time slots
     *
     * @param int $service_id Service ID
     * @param string $date Date (Y-m-d)
     * @return bool Has available slots
     */
    private function check_date_has_available_slots($service_id, $date) {
        // Get service capacity
        $capacity = get_post_meta($service_id, '_service_capacity', true);
        $capacity = !empty($capacity) ? intval($capacity) : 1;
        
        // Get service duration
        $duration = get_post_meta($service_id, '_service_duration', true);
        $duration = !empty($duration) ? intval($duration) : 60; // Default to 60 minutes
        
        // Get service buffer time
        $buffer_time = get_post_meta($service_id, '_service_buffer_time', true);
        $buffer_time = !empty($buffer_time) ? intval($buffer_time) : 0;
        
        // Get business hours
        $business_hours = $this->get_business_hours();
        
        // Get day of week (0 = Sunday, 6 = Saturday)
        $day_of_week = date('w', strtotime($date));
        
        // Check if business is closed on this day
        if (!isset($business_hours[$day_of_week]) || empty($business_hours[$day_of_week]['open']) || empty($business_hours[$day_of_week]['close'])) {
            return false;
        }
        
        // Get opening and closing times
        $opening_time = $business_hours[$day_of_week]['open'];
        $closing_time = $business_hours[$day_of_week]['close'];
        
        // Convert to timestamps
        $opening_timestamp = strtotime($date . ' ' . $opening_time);
        $closing_timestamp = strtotime($date . ' ' . $closing_time);
        
        // Get existing bookings for this date
        global $wpdb;
        $table_name = $wpdb->prefix . 'aqualuxe_bookings';
        
        $bookings = $wpdb->get_results($wpdb->prepare(
            "SELECT start_date, end_date, qty 
            FROM $table_name 
            WHERE service_id = %d 
            AND DATE(start_date) = %s",
            $service_id,
            $date
        ), ARRAY_A);
        
        // Check if there are any available time slots
        $current_time = $opening_timestamp;
        
        while ($current_time + ($duration * 60) <= $closing_timestamp) {
            $end_time = $current_time + ($duration * 60);
            $is_available = true;
            $booked_quantity = 0;
            
            // Check if time slot overlaps with existing bookings
            foreach ($bookings as $booking) {
                $booking_start = strtotime($booking['start_date']);
                $booking_end = strtotime($booking['end_date']);
                
                // Check for overlap
                if (
                    ($current_time < $booking_end && $end_time > $booking_start) ||
                    ($current_time === $booking_start && $end_time === $booking_end)
                ) {
                    $booked_quantity += intval($booking['qty']);
                    
                    // If fully booked, mark as unavailable
                    if ($booked_quantity >= $capacity) {
                        $is_available = false;
                        break;
                    }
                }
            }
            
            // If at least one time slot is available, return true
            if ($is_available) {
                return true;
            }
            
            // Move to next time slot (add buffer time)
            $current_time += (($duration + $buffer_time) * 60);
        }
        
        // No available time slots found
        return false;
    }

    /**
     * Get service available times
     *
     * @param int $service_id Service ID
     * @param string $date Date (Y-m-d)
     * @return array Available time slots
     */
    private function get_service_available_times($service_id, $date) {
        // Get service capacity
        $capacity = get_post_meta($service_id, '_service_capacity', true);
        $capacity = !empty($capacity) ? intval($capacity) : 1;
        
        // Get service duration
        $duration = get_post_meta($service_id, '_service_duration', true);
        $duration = !empty($duration) ? intval($duration) : 60; // Default to 60 minutes
        
        // Get service buffer time
        $buffer_time = get_post_meta($service_id, '_service_buffer_time', true);
        $buffer_time = !empty($buffer_time) ? intval($buffer_time) : 0;
        
        // Get business hours
        $business_hours = $this->get_business_hours();
        
        // Get day of week (0 = Sunday, 6 = Saturday)
        $day_of_week = date('w', strtotime($date));
        
        // Check if business is closed on this day
        if (!isset($business_hours[$day_of_week]) || empty($business_hours[$day_of_week]['open']) || empty($business_hours[$day_of_week]['close'])) {
            return array();
        }
        
        // Get opening and closing times
        $opening_time = $business_hours[$day_of_week]['open'];
        $closing_time = $business_hours[$day_of_week]['close'];
        
        // Convert to timestamps
        $opening_timestamp = strtotime($date . ' ' . $opening_time);
        $closing_timestamp = strtotime($date . ' ' . $closing_time);
        
        // Get existing bookings for this date
        global $wpdb;
        $table_name = $wpdb->prefix . 'aqualuxe_bookings';
        
        $bookings = $wpdb->get_results($wpdb->prepare(
            "SELECT start_date, end_date, qty 
            FROM $table_name 
            WHERE service_id = %d 
            AND DATE(start_date) = %s",
            $service_id,
            $date
        ), ARRAY_A);
        
        // Generate available time slots
        $time_slots = array();
        $current_time = $opening_timestamp;
        
        // Get time format
        $time_format = get_option('aqualuxe_bookings_time_format', '12h');
        $format = '12h' === $time_format ? 'g:i A' : 'H:i';
        
        while ($current_time + ($duration * 60) <= $closing_timestamp) {
            $end_time = $current_time + ($duration * 60);
            $is_available = true;
            $booked_quantity = 0;
            
            // Check if time slot is in the past
            if ($current_time < time() && date('Y-m-d') === $date) {
                $current_time += (($duration + $buffer_time) * 60);
                continue;
            }
            
            // Check if time slot overlaps with existing bookings
            foreach ($bookings as $booking) {
                $booking_start = strtotime($booking['start_date']);
                $booking_end = strtotime($booking['end_date']);
                
                // Check for overlap
                if (
                    ($current_time < $booking_end && $end_time > $booking_start) ||
                    ($current_time === $booking_start && $end_time === $booking_end)
                ) {
                    $booked_quantity += intval($booking['qty']);
                    
                    // If fully booked, mark as unavailable
                    if ($booked_quantity >= $capacity) {
                        $is_available = false;
                        break;
                    }
                }
            }
            
            // Add time slot if available
            if ($is_available) {
                $time_slots[] = array(
                    'time' => date($format, $current_time),
                    'timestamp' => $current_time,
                    'available' => $capacity - $booked_quantity,
                );
            }
            
            // Move to next time slot (add buffer time)
            $current_time += (($duration + $buffer_time) * 60);
        }
        
        return $time_slots;
    }

    /**
     * Get business hours
     *
     * @return array Business hours
     */
    private function get_business_hours() {
        // Default business hours (9 AM - 5 PM, Monday to Friday)
        $default_hours = array(
            0 => array('open' => '', 'close' => ''), // Sunday (closed)
            1 => array('open' => '09:00', 'close' => '17:00'), // Monday
            2 => array('open' => '09:00', 'close' => '17:00'), // Tuesday
            3 => array('open' => '09:00', 'close' => '17:00'), // Wednesday
            4 => array('open' => '09:00', 'close' => '17:00'), // Thursday
            5 => array('open' => '09:00', 'close' => '17:00'), // Friday
            6 => array('open' => '', 'close' => ''), // Saturday (closed)
        );
        
        // Get business hours from options
        $business_hours = get_option('aqualuxe_bookings_business_hours', $default_hours);
        
        return $business_hours;
    }

    /**
     * Get availability rules
     *
     * @param int $service_id Service ID
     * @param string $start_date Start date
     * @param string $end_date End date
     * @return array Availability rules
     */
    private function get_availability_rules($service_id, $start_date, $end_date) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'aqualuxe_booking_availability';
        
        $query = "SELECT * FROM $table_name WHERE ";
        
        if ($service_id > 0) {
            $query .= $wpdb->prepare("service_id = %d AND ", $service_id);
        }
        
        $query .= $wpdb->prepare(
            "(date_from <= %s AND date_to >= %s) OR
            (date_from >= %s AND date_from <= %s) OR
            (date_to >= %s AND date_to <= %s)",
            $end_date, $start_date,
            $start_date, $end_date,
            $start_date, $end_date
        );
        
        $rules = $wpdb->get_results($query, ARRAY_A);
        
        return $rules;
    }

    /**
     * Send status notification
     *
     * @param array $booking Booking data
     */
    private function send_status_notification($booking) {
        // Format date and time
        $date_format = get_option('date_format');
        $time_format = get_option('time_format');
        
        $booking_date = date_i18n($date_format, strtotime($booking['start_date']));
        $booking_time = date_i18n($time_format, strtotime($booking['start_date'])) . ' - ' . date_i18n($time_format, strtotime($booking['end_date']));

        // Build email content
        $subject = sprintf(__('Your Booking Has Been Confirmed: %s', 'aqualuxe'), $booking['booking_id']);
        
        $message = sprintf(__('Your booking with %s has been confirmed.', 'aqualuxe'), get_bloginfo('name')) . "\n\n";
        $message .= sprintf(__('Booking ID: %s', 'aqualuxe'), $booking['booking_id']) . "\n";
        $message .= sprintf(__('Service: %s', 'aqualuxe'), $booking['service_name']) . "\n";
        $message .= sprintf(__('Date: %s', 'aqualuxe'), $booking_date) . "\n";
        $message .= sprintf(__('Time: %s', 'aqualuxe'), $booking_time) . "\n";
        $message .= sprintf(__('Quantity: %s', 'aqualuxe'), $booking['quantity']) . "\n";
        $message .= sprintf(__('Total: %s', 'aqualuxe'), function_exists('wc_price') ? wc_price($booking['total']) : '$' . number_format($booking['total'], 2)) . "\n\n";
        
        $message .= __('We look forward to seeing you!', 'aqualuxe') . "\n\n";
        
        // Add cancellation policy if set
        $cancellation_policy = get_option('aqualuxe_bookings_cancellation_policy', '');
        
        if (!empty($cancellation_policy)) {
            $message .= __('Cancellation Policy:', 'aqualuxe') . "\n";
            $message .= $cancellation_policy . "\n\n";
        }
        
        $message .= sprintf(__('If you have any questions, please contact us at %s.', 'aqualuxe'), get_option('admin_email')) . "\n\n";
        $message .= __('Thank you for choosing us!', 'aqualuxe') . "\n";
        $message .= get_bloginfo('name');

        // Send email
        wp_mail($booking['customer_email'], $subject, $message);
    }
}