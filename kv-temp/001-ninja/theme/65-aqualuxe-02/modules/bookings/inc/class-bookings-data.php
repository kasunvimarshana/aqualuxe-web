<?php
/**
 * Bookings Data
 *
 * Handles data storage and retrieval for the bookings module.
 *
 * @package AquaLuxe
 * @subpackage Bookings
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Bookings Data Class
 */
class AquaLuxe_Bookings_Data {
    /**
     * Constructor
     */
    public function __construct() {
        // Initialize hooks
        add_action('init', array($this, 'init'));
    }

    /**
     * Initialize
     */
    public function init() {
        // Register custom post meta
        $this->register_post_meta();
    }

    /**
     * Register custom post meta
     */
    private function register_post_meta() {
        // Register meta for bookable_service post type
        register_post_meta('bookable_service', '_service_price', array(
            'type' => 'number',
            'description' => __('Service price', 'aqualuxe'),
            'single' => true,
            'show_in_rest' => true,
        ));

        register_post_meta('bookable_service', '_service_duration', array(
            'type' => 'integer',
            'description' => __('Service duration in minutes', 'aqualuxe'),
            'single' => true,
            'show_in_rest' => true,
        ));

        register_post_meta('bookable_service', '_service_capacity', array(
            'type' => 'integer',
            'description' => __('Service capacity', 'aqualuxe'),
            'single' => true,
            'show_in_rest' => true,
        ));

        register_post_meta('bookable_service', '_service_buffer_time', array(
            'type' => 'integer',
            'description' => __('Buffer time in minutes', 'aqualuxe'),
            'single' => true,
            'show_in_rest' => true,
        ));

        register_post_meta('bookable_service', '_service_min_duration', array(
            'type' => 'integer',
            'description' => __('Minimum booking duration in minutes', 'aqualuxe'),
            'single' => true,
            'show_in_rest' => true,
        ));

        register_post_meta('bookable_service', '_service_max_duration', array(
            'type' => 'integer',
            'description' => __('Maximum booking duration in minutes', 'aqualuxe'),
            'single' => true,
            'show_in_rest' => true,
        ));

        register_post_meta('bookable_service', '_service_availability', array(
            'type' => 'string',
            'description' => __('Service availability', 'aqualuxe'),
            'single' => true,
            'show_in_rest' => true,
        ));

        // Register meta for booking post type
        register_post_meta('booking', '_booking_id', array(
            'type' => 'string',
            'description' => __('Booking ID', 'aqualuxe'),
            'single' => true,
            'show_in_rest' => true,
        ));

        register_post_meta('booking', '_service_id', array(
            'type' => 'integer',
            'description' => __('Service ID', 'aqualuxe'),
            'single' => true,
            'show_in_rest' => true,
        ));

        register_post_meta('booking', '_customer_id', array(
            'type' => 'integer',
            'description' => __('Customer ID', 'aqualuxe'),
            'single' => true,
            'show_in_rest' => true,
        ));

        register_post_meta('booking', '_customer_name', array(
            'type' => 'string',
            'description' => __('Customer name', 'aqualuxe'),
            'single' => true,
            'show_in_rest' => true,
        ));

        register_post_meta('booking', '_customer_email', array(
            'type' => 'string',
            'description' => __('Customer email', 'aqualuxe'),
            'single' => true,
            'show_in_rest' => true,
        ));

        register_post_meta('booking', '_customer_phone', array(
            'type' => 'string',
            'description' => __('Customer phone', 'aqualuxe'),
            'single' => true,
            'show_in_rest' => true,
        ));

        register_post_meta('booking', '_booking_start', array(
            'type' => 'string',
            'description' => __('Booking start date/time', 'aqualuxe'),
            'single' => true,
            'show_in_rest' => true,
        ));

        register_post_meta('booking', '_booking_end', array(
            'type' => 'string',
            'description' => __('Booking end date/time', 'aqualuxe'),
            'single' => true,
            'show_in_rest' => true,
        ));

        register_post_meta('booking', '_booking_all_day', array(
            'type' => 'boolean',
            'description' => __('All day booking', 'aqualuxe'),
            'single' => true,
            'show_in_rest' => true,
        ));

        register_post_meta('booking', '_booking_quantity', array(
            'type' => 'integer',
            'description' => __('Booking quantity', 'aqualuxe'),
            'single' => true,
            'show_in_rest' => true,
        ));

        register_post_meta('booking', '_booking_total', array(
            'type' => 'number',
            'description' => __('Booking total', 'aqualuxe'),
            'single' => true,
            'show_in_rest' => true,
        ));

        register_post_meta('booking', '_order_id', array(
            'type' => 'integer',
            'description' => __('WooCommerce order ID', 'aqualuxe'),
            'single' => true,
            'show_in_rest' => true,
        ));
    }

    /**
     * Create a new booking
     *
     * @param array $args Booking data
     * @return int|WP_Error Booking ID or error
     */
    public function create_booking($args = array()) {
        $defaults = array(
            'service_id'     => 0,
            'customer_id'    => 0,
            'customer_name'  => '',
            'customer_email' => '',
            'customer_phone' => '',
            'start_date'     => '',
            'end_date'       => '',
            'all_day'        => false,
            'quantity'       => 1,
            'total'          => 0,
            'status'         => 'aqualuxe-pending',
            'order_id'       => 0,
        );

        $args = wp_parse_args($args, $defaults);

        // Validate required fields
        if (empty($args['service_id'])) {
            return new WP_Error('missing_service', __('Service ID is required', 'aqualuxe'));
        }

        if (empty($args['start_date'])) {
            return new WP_Error('missing_start_date', __('Start date is required', 'aqualuxe'));
        }

        if (empty($args['end_date'])) {
            return new WP_Error('missing_end_date', __('End date is required', 'aqualuxe'));
        }

        // Check if service exists
        $service = get_post($args['service_id']);
        if (!$service || 'bookable_service' !== $service->post_type) {
            return new WP_Error('invalid_service', __('Invalid service', 'aqualuxe'));
        }

        // Generate booking ID
        $booking_id = $this->generate_booking_id();

        // Create booking post
        $post_data = array(
            'post_title'   => $booking_id,
            'post_status'  => $args['status'],
            'post_type'    => 'booking',
            'post_author'  => $args['customer_id'] > 0 ? $args['customer_id'] : 1,
        );

        $post_id = wp_insert_post($post_data);

        if (is_wp_error($post_id)) {
            return $post_id;
        }

        // Save booking meta
        update_post_meta($post_id, '_booking_id', $booking_id);
        update_post_meta($post_id, '_service_id', $args['service_id']);
        update_post_meta($post_id, '_customer_id', $args['customer_id']);
        update_post_meta($post_id, '_customer_name', $args['customer_name']);
        update_post_meta($post_id, '_customer_email', $args['customer_email']);
        update_post_meta($post_id, '_customer_phone', $args['customer_phone']);
        update_post_meta($post_id, '_booking_start', $args['start_date']);
        update_post_meta($post_id, '_booking_end', $args['end_date']);
        update_post_meta($post_id, '_booking_all_day', $args['all_day']);
        update_post_meta($post_id, '_booking_quantity', $args['quantity']);
        update_post_meta($post_id, '_booking_total', $args['total']);
        update_post_meta($post_id, '_order_id', $args['order_id']);

        // Also save to custom table for faster queries
        $this->save_booking_to_table($post_id, $args);

        // Trigger action
        do_action('aqualuxe_booking_created', $post_id, $args);

        return $post_id;
    }

    /**
     * Update a booking
     *
     * @param int $booking_id Booking ID
     * @param array $args Booking data
     * @return int|WP_Error Booking ID or error
     */
    public function update_booking($booking_id, $args = array()) {
        $booking = get_post($booking_id);

        if (!$booking || 'booking' !== $booking->post_type) {
            return new WP_Error('invalid_booking', __('Invalid booking', 'aqualuxe'));
        }

        $post_data = array(
            'ID' => $booking_id,
        );

        // Update status if provided
        if (!empty($args['status'])) {
            $post_data['post_status'] = $args['status'];
        }

        // Update post
        $post_id = wp_update_post($post_data);

        if (is_wp_error($post_id)) {
            return $post_id;
        }

        // Update meta fields
        $meta_fields = array(
            'service_id'     => '_service_id',
            'customer_id'    => '_customer_id',
            'customer_name'  => '_customer_name',
            'customer_email' => '_customer_email',
            'customer_phone' => '_customer_phone',
            'start_date'     => '_booking_start',
            'end_date'       => '_booking_end',
            'all_day'        => '_booking_all_day',
            'quantity'       => '_booking_quantity',
            'total'          => '_booking_total',
            'order_id'       => '_order_id',
        );

        foreach ($meta_fields as $arg_key => $meta_key) {
            if (isset($args[$arg_key])) {
                update_post_meta($booking_id, $meta_key, $args[$arg_key]);
            }
        }

        // Update custom table
        $this->update_booking_in_table($booking_id, $args);

        // Trigger action
        do_action('aqualuxe_booking_updated', $booking_id, $args);

        return $booking_id;
    }

    /**
     * Delete a booking
     *
     * @param int $booking_id Booking ID
     * @return bool Success
     */
    public function delete_booking($booking_id) {
        $booking = get_post($booking_id);

        if (!$booking || 'booking' !== $booking->post_type) {
            return false;
        }

        // Delete from custom table
        $this->delete_booking_from_table($booking_id);

        // Delete post
        $result = wp_delete_post($booking_id, true);

        if ($result) {
            // Trigger action
            do_action('aqualuxe_booking_deleted', $booking_id);
            return true;
        }

        return false;
    }

    /**
     * Get a booking by ID
     *
     * @param int $booking_id Booking ID
     * @return array|false Booking data or false
     */
    public function get_booking($booking_id) {
        $booking = get_post($booking_id);

        if (!$booking || 'booking' !== $booking->post_type) {
            return false;
        }

        $booking_data = array(
            'id'            => $booking->ID,
            'booking_id'    => get_post_meta($booking->ID, '_booking_id', true),
            'service_id'    => get_post_meta($booking->ID, '_service_id', true),
            'service_name'  => get_the_title(get_post_meta($booking->ID, '_service_id', true)),
            'customer_id'   => get_post_meta($booking->ID, '_customer_id', true),
            'customer_name' => get_post_meta($booking->ID, '_customer_name', true),
            'customer_email' => get_post_meta($booking->ID, '_customer_email', true),
            'customer_phone' => get_post_meta($booking->ID, '_customer_phone', true),
            'start_date'    => get_post_meta($booking->ID, '_booking_start', true),
            'end_date'      => get_post_meta($booking->ID, '_booking_end', true),
            'all_day'       => (bool) get_post_meta($booking->ID, '_booking_all_day', true),
            'quantity'      => get_post_meta($booking->ID, '_booking_quantity', true),
            'total'         => get_post_meta($booking->ID, '_booking_total', true),
            'status'        => $booking->post_status,
            'order_id'      => get_post_meta($booking->ID, '_order_id', true),
            'created'       => $booking->post_date,
            'modified'      => $booking->post_modified,
        );

        return $booking_data;
    }

    /**
     * Get bookings
     *
     * @param array $args Query arguments
     * @return array Bookings
     */
    public function get_bookings($args = array()) {
        $defaults = array(
            'service_id'    => 0,
            'customer_id'   => 0,
            'status'        => '',
            'date_from'     => '',
            'date_to'       => '',
            'order_id'      => 0,
            'orderby'       => 'date',
            'order'         => 'DESC',
            'limit'         => -1,
            'offset'        => 0,
        );

        $args = wp_parse_args($args, $defaults);

        // Build query args
        $query_args = array(
            'post_type'      => 'booking',
            'posts_per_page' => $args['limit'],
            'offset'         => $args['offset'],
            'orderby'        => $args['orderby'],
            'order'          => $args['order'],
            'meta_query'     => array(),
        );

        // Filter by status
        if (!empty($args['status'])) {
            $query_args['post_status'] = $args['status'];
        }

        // Filter by service
        if (!empty($args['service_id'])) {
            $query_args['meta_query'][] = array(
                'key'   => '_service_id',
                'value' => $args['service_id'],
            );
        }

        // Filter by customer
        if (!empty($args['customer_id'])) {
            $query_args['meta_query'][] = array(
                'key'   => '_customer_id',
                'value' => $args['customer_id'],
            );
        }

        // Filter by order
        if (!empty($args['order_id'])) {
            $query_args['meta_query'][] = array(
                'key'   => '_order_id',
                'value' => $args['order_id'],
            );
        }

        // Filter by date range
        if (!empty($args['date_from']) || !empty($args['date_to'])) {
            $date_query = array();

            if (!empty($args['date_from'])) {
                $date_query['after'] = $args['date_from'];
            }

            if (!empty($args['date_to'])) {
                $date_query['before'] = $args['date_to'];
            }

            $query_args['date_query'] = array($date_query);
        }

        // Get bookings
        $bookings = array();
        $query = new WP_Query($query_args);

        if ($query->have_posts()) {
            foreach ($query->posts as $post) {
                $bookings[] = $this->get_booking($post->ID);
            }
        }

        return $bookings;
    }

    /**
     * Check if a time slot is available
     *
     * @param int $service_id Service ID
     * @param string $start_date Start date/time
     * @param string $end_date End date/time
     * @param int $exclude_booking_id Booking ID to exclude
     * @return bool Is available
     */
    public function is_time_slot_available($service_id, $start_date, $end_date, $exclude_booking_id = 0) {
        global $wpdb;

        // Check service availability rules first
        if (!$this->check_service_availability($service_id, $start_date, $end_date)) {
            return false;
        }

        // Check existing bookings
        $table_name = $wpdb->prefix . 'aqualuxe_bookings';
        
        $query = $wpdb->prepare(
            "SELECT COUNT(*) FROM $table_name 
            WHERE service_id = %d 
            AND (
                (start_date < %s AND end_date > %s) OR
                (start_date >= %s AND start_date < %s) OR
                (end_date > %s AND end_date <= %s) OR
                (start_date <= %s AND end_date >= %s)
            )",
            $service_id,
            $end_date, $start_date,
            $start_date, $end_date,
            $start_date, $end_date,
            $start_date, $end_date
        );

        if ($exclude_booking_id > 0) {
            $query .= $wpdb->prepare(" AND id != %d", $exclude_booking_id);
        }

        $count = $wpdb->get_var($query);

        // Get service capacity
        $capacity = get_post_meta($service_id, '_service_capacity', true);
        $capacity = !empty($capacity) ? intval($capacity) : 1;

        // If count is less than capacity, the slot is available
        return ($count < $capacity);
    }

    /**
     * Check service availability based on rules
     *
     * @param int $service_id Service ID
     * @param string $start_date Start date/time
     * @param string $end_date End date/time
     * @return bool Is available
     */
    private function check_service_availability($service_id, $start_date, $end_date) {
        global $wpdb;

        $table_name = $wpdb->prefix . 'aqualuxe_booking_availability';
        
        // Check if there are any availability rules that make this time slot unbookable
        $query = $wpdb->prepare(
            "SELECT COUNT(*) FROM $table_name 
            WHERE service_id = %d 
            AND date_from <= %s 
            AND date_to >= %s 
            AND bookable = 0
            ORDER BY priority DESC
            LIMIT 1",
            $service_id,
            $end_date,
            $start_date
        );

        $count = $wpdb->get_var($query);

        // If there are rules making this unbookable, return false
        if ($count > 0) {
            return false;
        }

        return true;
    }

    /**
     * Generate a unique booking ID
     *
     * @return string Booking ID
     */
    private function generate_booking_id() {
        $prefix = 'BK';
        $timestamp = time();
        $random = strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 4));
        
        return $prefix . $timestamp . $random;
    }

    /**
     * Save booking to custom table
     *
     * @param int $post_id Post ID
     * @param array $args Booking data
     */
    private function save_booking_to_table($post_id, $args) {
        global $wpdb;

        $table_name = $wpdb->prefix . 'aqualuxe_bookings';
        
        $wpdb->insert(
            $table_name,
            array(
                'booking_id'    => get_post_meta($post_id, '_booking_id', true),
                'service_id'    => $args['service_id'],
                'customer_id'   => $args['customer_id'],
                'status'        => $args['status'],
                'date_created'  => current_time('mysql'),
                'date_modified' => current_time('mysql'),
                'start_date'    => $args['start_date'],
                'end_date'      => $args['end_date'],
                'all_day'       => $args['all_day'] ? 1 : 0,
                'qty'           => $args['quantity'],
                'cost'          => $args['total'],
            ),
            array(
                '%s', '%d', '%d', '%s', '%s', '%s', '%s', '%s', '%d', '%d', '%f'
            )
        );
    }

    /**
     * Update booking in custom table
     *
     * @param int $post_id Post ID
     * @param array $args Booking data
     */
    private function update_booking_in_table($post_id, $args) {
        global $wpdb;

        $table_name = $wpdb->prefix . 'aqualuxe_bookings';
        $booking_id = get_post_meta($post_id, '_booking_id', true);
        
        $data = array(
            'date_modified' => current_time('mysql'),
        );
        
        $format = array('%s');
        
        // Add fields that need to be updated
        $field_map = array(
            'service_id'  => array('service_id', '%d'),
            'customer_id' => array('customer_id', '%d'),
            'status'      => array('status', '%s'),
            'start_date'  => array('start_date', '%s'),
            'end_date'    => array('end_date', '%s'),
            'all_day'     => array('all_day', '%d'),
            'quantity'    => array('qty', '%d'),
            'total'       => array('cost', '%f'),
        );
        
        foreach ($field_map as $arg_key => $field_info) {
            if (isset($args[$arg_key])) {
                $data[$field_info[0]] = $arg_key === 'all_day' ? ($args[$arg_key] ? 1 : 0) : $args[$arg_key];
                $format[] = $field_info[1];
            }
        }
        
        $wpdb->update(
            $table_name,
            $data,
            array('booking_id' => $booking_id),
            $format,
            array('%s')
        );
    }

    /**
     * Delete booking from custom table
     *
     * @param int $post_id Post ID
     */
    private function delete_booking_from_table($post_id) {
        global $wpdb;

        $table_name = $wpdb->prefix . 'aqualuxe_bookings';
        $booking_id = get_post_meta($post_id, '_booking_id', true);
        
        $wpdb->delete(
            $table_name,
            array('booking_id' => $booking_id),
            array('%s')
        );
        
        // Also delete from meta table
        $meta_table_name = $wpdb->prefix . 'aqualuxe_booking_meta';
        
        $wpdb->delete(
            $meta_table_name,
            array('booking_id' => $post_id),
            array('%d')
        );
    }
}