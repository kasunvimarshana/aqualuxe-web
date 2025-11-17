<?php
/**
 * Bookings Module
 * 
 * Handles booking and scheduling functionality
 * 
 * @package AquaLuxe
 * @subpackage Modules
 * @version 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Bookings Module Class
 */
class AquaLuxe_Bookings_Module {
    
    /**
     * Module configuration
     */
    private $config = [
        'enabled' => true,
        'booking_buffer_minutes' => 30,
        'advance_booking_days' => 30,
        'cancellation_hours' => 24,
        'require_payment' => true,
        'auto_confirm' => false,
        'time_slots' => [
            '09:00', '09:30', '10:00', '10:30', '11:00', '11:30',
            '14:00', '14:30', '15:00', '15:30', '16:00', '16:30'
        ]
    ];
    
    /**
     * Constructor
     */
    public function __construct() {
        $this->init();
    }
    
    /**
     * Initialize module
     */
    private function init() {
        if (!$this->is_enabled()) {
            return;
        }
        
        $this->setup_hooks();
        $this->register_post_types();
        $this->register_taxonomies();
    }
    
    /**
     * Check if module is enabled
     */
    private function is_enabled() {
        return $this->config['enabled'] && apply_filters('aqualuxe_bookings_enabled', true);
    }
    
    /**
     * Setup hooks
     */
    private function setup_hooks() {
        add_action('init', [$this, 'register_post_types']);
        add_action('init', [$this, 'register_taxonomies']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
        add_action('wp_ajax_create_booking', [$this, 'ajax_create_booking']);
        add_action('wp_ajax_nopriv_create_booking', [$this, 'ajax_create_booking']);
        add_action('wp_ajax_cancel_booking', [$this, 'ajax_cancel_booking']);
        add_action('wp_ajax_get_available_slots', [$this, 'ajax_get_available_slots']);
        add_action('wp_ajax_nopriv_get_available_slots', [$this, 'ajax_get_available_slots']);
        add_filter('aqualuxe_dashboard_modules', [$this, 'add_dashboard_widget']);
        add_action('admin_menu', [$this, 'add_admin_menu']);
        add_shortcode('booking_form', [$this, 'render_booking_form']);
        add_shortcode('booking_calendar', [$this, 'render_booking_calendar']);
        add_shortcode('my_bookings', [$this, 'render_my_bookings']);
        
        // Cron hooks for reminders
        add_action('aqualuxe_booking_reminder', [$this, 'send_booking_reminder']);
        add_action('aqualuxe_cleanup_expired_bookings', [$this, 'cleanup_expired_bookings']);
        
        if (!wp_next_scheduled('aqualuxe_cleanup_expired_bookings')) {
            wp_schedule_event(time(), 'daily', 'aqualuxe_cleanup_expired_bookings');
        }
    }
    
    /**
     * Register booking-related post types
     */
    public function register_post_types() {
        // Booking post type
        register_post_type('booking', [
            'labels' => [
                'name' => esc_html__('Bookings', 'aqualuxe'),
                'singular_name' => esc_html__('Booking', 'aqualuxe'),
                'add_new' => esc_html__('Add New Booking', 'aqualuxe'),
                'add_new_item' => esc_html__('Add New Booking', 'aqualuxe'),
                'edit_item' => esc_html__('Edit Booking', 'aqualuxe'),
                'new_item' => esc_html__('New Booking', 'aqualuxe'),
                'view_item' => esc_html__('View Booking', 'aqualuxe'),
                'search_items' => esc_html__('Search Bookings', 'aqualuxe'),
                'not_found' => esc_html__('No bookings found', 'aqualuxe'),
                'not_found_in_trash' => esc_html__('No bookings found in trash', 'aqualuxe'),
            ],
            'public' => false,
            'publicly_queryable' => false,
            'show_ui' => true,
            'show_in_menu' => true,
            'query_var' => false,
            'capability_type' => 'post',
            'has_archive' => false,
            'hierarchical' => false,
            'menu_position' => 32,
            'menu_icon' => 'dashicons-calendar-alt',
            'supports' => ['title', 'custom-fields'],
            'show_in_rest' => false,
        ]);
        
        // Service post type
        register_post_type('bookable_service', [
            'labels' => [
                'name' => esc_html__('Bookable Services', 'aqualuxe'),
                'singular_name' => esc_html__('Bookable Service', 'aqualuxe'),
                'add_new' => esc_html__('Add New Service', 'aqualuxe'),
                'add_new_item' => esc_html__('Add New Bookable Service', 'aqualuxe'),
                'edit_item' => esc_html__('Edit Bookable Service', 'aqualuxe'),
                'new_item' => esc_html__('New Bookable Service', 'aqualuxe'),
                'view_item' => esc_html__('View Bookable Service', 'aqualuxe'),
                'search_items' => esc_html__('Search Bookable Services', 'aqualuxe'),
                'not_found' => esc_html__('No bookable services found', 'aqualuxe'),
                'not_found_in_trash' => esc_html__('No bookable services found in trash', 'aqualuxe'),
            ],
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => 'edit.php?post_type=booking',
            'query_var' => true,
            'rewrite' => ['slug' => 'services'],
            'capability_type' => 'post',
            'has_archive' => true,
            'hierarchical' => false,
            'supports' => ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'],
            'show_in_rest' => true,
        ]);
    }
    
    /**
     * Register taxonomies
     */
    public function register_taxonomies() {
        // Service Categories
        register_taxonomy('service_category', 'bookable_service', [
            'labels' => [
                'name' => esc_html__('Service Categories', 'aqualuxe'),
                'singular_name' => esc_html__('Service Category', 'aqualuxe'),
                'search_items' => esc_html__('Search Service Categories', 'aqualuxe'),
                'all_items' => esc_html__('All Service Categories', 'aqualuxe'),
                'edit_item' => esc_html__('Edit Service Category', 'aqualuxe'),
                'update_item' => esc_html__('Update Service Category', 'aqualuxe'),
                'add_new_item' => esc_html__('Add New Service Category', 'aqualuxe'),
                'new_item_name' => esc_html__('New Service Category Name', 'aqualuxe'),
                'menu_name' => esc_html__('Categories', 'aqualuxe'),
            ],
            'hierarchical' => true,
            'public' => true,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => ['slug' => 'service-category'],
            'show_in_rest' => true,
        ]);
        
        // Booking Status
        register_taxonomy('booking_status', 'booking', [
            'labels' => [
                'name' => esc_html__('Booking Status', 'aqualuxe'),
                'singular_name' => esc_html__('Status', 'aqualuxe'),
            ],
            'hierarchical' => false,
            'public' => false,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => false,
            'show_in_rest' => false,
        ]);
    }
    
    /**
     * Enqueue module assets
     */
    public function enqueue_assets() {
        if (is_singular(['bookable_service', 'booking']) ||
            is_post_type_archive('bookable_service') ||
            has_shortcode(get_post()->post_content ?? '', 'booking_form') ||
            has_shortcode(get_post()->post_content ?? '', 'booking_calendar')) {
            
            wp_enqueue_script(
                'aqualuxe-bookings',
                aqualuxe_asset('js/modules/bookings.js'),
                ['jquery'],
                AQUALUXE_VERSION,
                true
            );
            
            wp_localize_script('aqualuxe-bookings', 'aqualuxeBookings', [
                'ajaxUrl' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('aqualuxe_bookings'),
                'messages' => [
                    'bookingCreated' => esc_html__('Booking created successfully!', 'aqualuxe'),
                    'bookingError' => esc_html__('Error creating booking. Please try again.', 'aqualuxe'),
                    'bookingCancelled' => esc_html__('Booking cancelled successfully.', 'aqualuxe'),
                    'cancellationError' => esc_html__('Error cancelling booking. Please try again.', 'aqualuxe'),
                ],
                'config' => $this->config,
            ]);
            
            // Enqueue calendar CSS if needed
            wp_enqueue_style(
                'aqualuxe-bookings-calendar',
                AQUALUXE_ASSETS_URI . '/css/booking-calendar.css',
                [],
                AQUALUXE_VERSION
            );
        }
    }
    
    /**
     * AJAX handler for creating bookings
     */
    public function ajax_create_booking() {
        check_ajax_referer('aqualuxe_bookings', 'nonce');
        
        $data = wp_unslash($_POST);
        
        // Sanitize input
        $booking_data = [
            'service_id' => intval($data['service_id']),
            'booking_date' => sanitize_text_field($data['booking_date']),
            'booking_time' => sanitize_text_field($data['booking_time']),
            'customer_name' => sanitize_text_field($data['customer_name']),
            'customer_email' => sanitize_email($data['customer_email']),
            'customer_phone' => sanitize_text_field($data['customer_phone']),
            'special_requests' => sanitize_textarea_field($data['special_requests']),
            'duration_minutes' => intval($data['duration_minutes']),
        ];
        
        // Validate service exists
        if (!get_post($booking_data['service_id'])) {
            wp_send_json_error(['message' => esc_html__('Invalid service selected.', 'aqualuxe')]);
        }
        
        // Validate date and time
        $booking_datetime = $booking_data['booking_date'] . ' ' . $booking_data['booking_time'];
        if (!$this->is_valid_booking_time($booking_datetime, $booking_data['duration_minutes'])) {
            wp_send_json_error(['message' => esc_html__('Selected time slot is not available.', 'aqualuxe')]);
        }
        
        // Create booking
        $booking_id = wp_insert_post([
            'post_title' => sprintf(
                '%s - %s %s',
                get_the_title($booking_data['service_id']),
                $booking_data['customer_name'],
                $booking_datetime
            ),
            'post_type' => 'booking',
            'post_status' => 'publish',
            'meta_input' => array_merge($booking_data, [
                'booking_datetime' => $booking_datetime,
                'booking_status' => $this->config['auto_confirm'] ? 'confirmed' : 'pending',
                'created_date' => current_time('mysql'),
                'user_id' => get_current_user_id(),
            ]),
        ]);
        
        if ($booking_id) {
            // Send confirmation emails
            $this->send_booking_confirmations($booking_id);
            
            // Schedule reminder if confirmed
            if ($this->config['auto_confirm']) {
                $this->schedule_booking_reminder($booking_id);
            }
            
            wp_send_json_success([
                'message' => $this->config['auto_confirm'] 
                    ? esc_html__('Booking confirmed! You will receive a confirmation email shortly.', 'aqualuxe')
                    : esc_html__('Booking request submitted! We will confirm your booking within 24 hours.', 'aqualuxe'),
                'booking_id' => $booking_id,
                'status' => $this->config['auto_confirm'] ? 'confirmed' : 'pending',
            ]);
        } else {
            wp_send_json_error(['message' => esc_html__('Error creating booking. Please try again.', 'aqualuxe')]);
        }
    }
    
    /**
     * AJAX handler for cancelling bookings
     */
    public function ajax_cancel_booking() {
        check_ajax_referer('aqualuxe_bookings', 'nonce');
        
        $booking_id = intval($_POST['booking_id']);
        $booking = get_post($booking_id);
        
        if (!$booking || $booking->post_type !== 'booking') {
            wp_send_json_error(['message' => esc_html__('Invalid booking.', 'aqualuxe')]);
        }
        
        // Check if user can cancel this booking
        $user_id = get_current_user_id();
        $booking_user_id = get_post_meta($booking_id, 'user_id', true);
        
        if (!current_user_can('manage_options') && $user_id != $booking_user_id) {
            wp_send_json_error(['message' => esc_html__('You do not have permission to cancel this booking.', 'aqualuxe')]);
        }
        
        // Check cancellation policy
        $booking_datetime = get_post_meta($booking_id, 'booking_datetime', true);
        $cancellation_deadline = strtotime($booking_datetime) - ($this->config['cancellation_hours'] * 3600);
        
        if (current_time('timestamp') > $cancellation_deadline && !current_user_can('manage_options')) {
            wp_send_json_error([
                'message' => sprintf(
                    esc_html__('Bookings can only be cancelled at least %d hours in advance.', 'aqualuxe'),
                    $this->config['cancellation_hours']
                )
            ]);
        }
        
        // Update booking status
        update_post_meta($booking_id, 'booking_status', 'cancelled');
        update_post_meta($booking_id, 'cancelled_date', current_time('mysql'));
        update_post_meta($booking_id, 'cancelled_by', $user_id);
        
        // Send cancellation emails
        $this->send_cancellation_notifications($booking_id);
        
        wp_send_json_success([
            'message' => esc_html__('Booking cancelled successfully.', 'aqualuxe'),
        ]);
    }
    
    /**
     * AJAX handler for getting available time slots
     */
    public function ajax_get_available_slots() {
        check_ajax_referer('aqualuxe_bookings', 'nonce');
        
        $service_id = intval($_POST['service_id']);
        $date = sanitize_text_field($_POST['date']);
        
        if (!get_post($service_id) || !$date) {
            wp_send_json_error(['message' => esc_html__('Invalid service or date.', 'aqualuxe')]);
        }
        
        $available_slots = $this->get_available_time_slots($service_id, $date);
        
        wp_send_json_success([
            'slots' => $available_slots,
        ]);
    }
    
    /**
     * Check if booking time is valid and available
     */
    private function is_valid_booking_time($datetime, $duration_minutes) {
        $timestamp = strtotime($datetime);
        
        // Check if date is in the future
        if ($timestamp <= current_time('timestamp')) {
            return false;
        }
        
        // Check if within advance booking limit
        $max_advance = current_time('timestamp') + ($this->config['advance_booking_days'] * 24 * 3600);
        if ($timestamp > $max_advance) {
            return false;
        }
        
        // Check for conflicts with existing bookings
        $conflicts = get_posts([
            'post_type' => 'booking',
            'posts_per_page' => -1,
            'meta_query' => [
                [
                    'key' => 'booking_status',
                    'value' => ['confirmed', 'pending'],
                    'compare' => 'IN',
                ],
                [
                    'key' => 'booking_datetime',
                    'value' => [
                        date('Y-m-d H:i:s', $timestamp - $this->config['booking_buffer_minutes'] * 60),
                        date('Y-m-d H:i:s', $timestamp + $duration_minutes * 60 + $this->config['booking_buffer_minutes'] * 60)
                    ],
                    'compare' => 'BETWEEN',
                    'type' => 'DATETIME',
                ],
            ],
        ]);
        
        return empty($conflicts);
    }
    
    /**
     * Get available time slots for a specific service and date
     */
    private function get_available_time_slots($service_id, $date) {
        $available_slots = [];
        $service_duration = get_post_meta($service_id, 'service_duration_minutes', true) ?: 60;
        
        foreach ($this->config['time_slots'] as $time_slot) {
            $datetime = $date . ' ' . $time_slot . ':00';
            
            if ($this->is_valid_booking_time($datetime, $service_duration)) {
                $available_slots[] = [
                    'time' => $time_slot,
                    'display' => date('g:i A', strtotime($time_slot)),
                    'available' => true,
                ];
            }
        }
        
        return $available_slots;
    }
    
    /**
     * Send booking confirmations
     */
    private function send_booking_confirmations($booking_id) {
        $booking = get_post($booking_id);
        $service_id = get_post_meta($booking_id, 'service_id', true);
        $service = get_post($service_id);
        $customer_email = get_post_meta($booking_id, 'customer_email', true);
        $customer_name = get_post_meta($booking_id, 'customer_name', true);
        $booking_datetime = get_post_meta($booking_id, 'booking_datetime', true);
        $status = get_post_meta($booking_id, 'booking_status', true);
        
        // Send customer confirmation
        $subject = $status === 'confirmed' 
            ? esc_html__('Booking Confirmed - AquaLuxe', 'aqualuxe')
            : esc_html__('Booking Request Received - AquaLuxe', 'aqualuxe');
            
        $message = sprintf(
            esc_html__('Dear %s,

%s

Service: %s
Date & Time: %s
Status: %s

%s

Best regards,
The AquaLuxe Team', 'aqualuxe'),
            $customer_name,
            $status === 'confirmed' 
                ? esc_html__('Your booking has been confirmed!', 'aqualuxe')
                : esc_html__('We have received your booking request and will confirm it within 24 hours.', 'aqualuxe'),
            $service->post_title,
            date('F j, Y \a\t g:i A', strtotime($booking_datetime)),
            ucfirst($status),
            $status === 'confirmed'
                ? esc_html__('Please arrive 10 minutes before your scheduled appointment.', 'aqualuxe')
                : esc_html__('You will receive another email once your booking is confirmed.', 'aqualuxe')
        );
        
        wp_mail($customer_email, $subject, $message);
        
        // Send admin notification
        $admin_email = get_option('admin_email');
        $admin_subject = sprintf(esc_html__('New Booking: %s', 'aqualuxe'), $service->post_title);
        
        $admin_message = sprintf(
            esc_html__('A new booking has been created:

Customer: %s (%s)
Service: %s
Date & Time: %s
Status: %s

Manage this booking in the admin dashboard.', 'aqualuxe'),
            $customer_name,
            $customer_email,
            $service->post_title,
            date('F j, Y \a\t g:i A', strtotime($booking_datetime)),
            ucfirst($status)
        );
        
        wp_mail($admin_email, $admin_subject, $admin_message);
    }
    
    /**
     * Send cancellation notifications
     */
    private function send_cancellation_notifications($booking_id) {
        $booking = get_post($booking_id);
        $service_id = get_post_meta($booking_id, 'service_id', true);
        $service = get_post($service_id);
        $customer_email = get_post_meta($booking_id, 'customer_email', true);
        $customer_name = get_post_meta($booking_id, 'customer_name', true);
        $booking_datetime = get_post_meta($booking_id, 'booking_datetime', true);
        
        // Send customer notification
        $subject = esc_html__('Booking Cancelled - AquaLuxe', 'aqualuxe');
        $message = sprintf(
            esc_html__('Dear %s,

Your booking has been cancelled:

Service: %s
Date & Time: %s

If you need to reschedule, please make a new booking through our website.

Best regards,
The AquaLuxe Team', 'aqualuxe'),
            $customer_name,
            $service->post_title,
            date('F j, Y \a\t g:i A', strtotime($booking_datetime))
        );
        
        wp_mail($customer_email, $subject, $message);
        
        // Send admin notification
        $admin_email = get_option('admin_email');
        $admin_subject = sprintf(esc_html__('Booking Cancelled: %s', 'aqualuxe'), $service->post_title);
        
        $admin_message = sprintf(
            esc_html__('A booking has been cancelled:

Customer: %s (%s)
Service: %s
Date & Time: %s

The time slot is now available for new bookings.', 'aqualuxe'),
            $customer_name,
            $customer_email,
            $service->post_title,
            date('F j, Y \a\t g:i A', strtotime($booking_datetime))
        );
        
        wp_mail($admin_email, $admin_subject, $admin_message);
    }
    
    /**
     * Schedule booking reminder
     */
    private function schedule_booking_reminder($booking_id) {
        $booking_datetime = get_post_meta($booking_id, 'booking_datetime', true);
        $reminder_time = strtotime($booking_datetime) - (24 * 3600); // 24 hours before
        
        if ($reminder_time > current_time('timestamp')) {
            wp_schedule_single_event($reminder_time, 'aqualuxe_booking_reminder', [$booking_id]);
        }
    }
    
    /**
     * Send booking reminder
     */
    public function send_booking_reminder($booking_id) {
        $booking = get_post($booking_id);
        if (!$booking) {
            return;
        }
        
        $status = get_post_meta($booking_id, 'booking_status', true);
        if ($status !== 'confirmed') {
            return;
        }
        
        $service_id = get_post_meta($booking_id, 'service_id', true);
        $service = get_post($service_id);
        $customer_email = get_post_meta($booking_id, 'customer_email', true);
        $customer_name = get_post_meta($booking_id, 'customer_name', true);
        $booking_datetime = get_post_meta($booking_id, 'booking_datetime', true);
        
        $subject = esc_html__('Booking Reminder - Tomorrow - AquaLuxe', 'aqualuxe');
        $message = sprintf(
            esc_html__('Dear %s,

This is a reminder that you have a booking scheduled for tomorrow:

Service: %s
Date & Time: %s

Please arrive 10 minutes before your scheduled appointment. If you need to cancel or reschedule, please contact us at least %d hours in advance.

Best regards,
The AquaLuxe Team', 'aqualuxe'),
            $customer_name,
            $service->post_title,
            date('F j, Y \a\t g:i A', strtotime($booking_datetime)),
            $this->config['cancellation_hours']
        );
        
        wp_mail($customer_email, $subject, $message);
    }
    
    /**
     * Cleanup expired bookings
     */
    public function cleanup_expired_bookings() {
        $expired_bookings = get_posts([
            'post_type' => 'booking',
            'posts_per_page' => -1,
            'meta_query' => [
                [
                    'key' => 'booking_datetime',
                    'value' => current_time('mysql'),
                    'compare' => '<',
                    'type' => 'DATETIME',
                ],
                [
                    'key' => 'booking_status',
                    'value' => 'pending',
                    'compare' => '=',
                ],
            ],
        ]);
        
        foreach ($expired_bookings as $booking) {
            update_post_meta($booking->ID, 'booking_status', 'expired');
        }
    }
    
    /**
     * Render booking form shortcode
     */
    public function render_booking_form($atts) {
        $atts = shortcode_atts([
            'service_id' => 0,
            'title' => esc_html__('Book a Service', 'aqualuxe'),
        ], $atts);
        
        $services = get_posts([
            'post_type' => 'bookable_service',
            'posts_per_page' => -1,
            'post_status' => 'publish',
        ]);
        
        if (empty($services)) {
            return '<p>' . esc_html__('No bookable services available at the moment.', 'aqualuxe') . '</p>';
        }
        
        ob_start();
        ?>
        <div class="booking-form-container">
            <h3><?php echo esc_html($atts['title']); ?></h3>
            
            <form id="booking-form" class="booking-form">
                <div class="form-step" id="step-1">
                    <h4><?php esc_html_e('Select Service', 'aqualuxe'); ?></h4>
                    
                    <div class="service-selection">
                        <?php if (!$atts['service_id']): ?>
                            <div class="form-field">
                                <label for="service_id"><?php esc_html_e('Service', 'aqualuxe'); ?> *</label>
                                <select name="service_id" id="service_id" required>
                                    <option value=""><?php esc_html_e('Select a service...', 'aqualuxe'); ?></option>
                                    <?php foreach ($services as $service): ?>
                                        <option value="<?php echo esc_attr($service->ID); ?>" 
                                                data-duration="<?php echo esc_attr(get_post_meta($service->ID, 'service_duration_minutes', true) ?: 60); ?>"
                                                data-price="<?php echo esc_attr(get_post_meta($service->ID, 'service_price', true) ?: 0); ?>">
                                            <?php echo esc_html($service->post_title); ?>
                                            <?php 
                                            $price = get_post_meta($service->ID, 'service_price', true);
                                            if ($price) {
                                                echo ' - $' . number_format($price, 2);
                                            }
                                            ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        <?php else: ?>
                            <input type="hidden" name="service_id" value="<?php echo esc_attr($atts['service_id']); ?>" />
                            <?php
                            $service = get_post($atts['service_id']);
                            if ($service) {
                                echo '<div class="selected-service">';
                                echo '<h5>' . esc_html($service->post_title) . '</h5>';
                                echo '<p>' . wp_kses_post($service->post_excerpt ?: wp_trim_words($service->post_content, 20)) . '</p>';
                                $price = get_post_meta($service->ID, 'service_price', true);
                                if ($price) {
                                    echo '<div class="service-price">$' . number_format($price, 2) . '</div>';
                                }
                                echo '</div>';
                            }
                            ?>
                        <?php endif; ?>
                    </div>
                    
                    <div class="step-actions">
                        <button type="button" class="btn btn-primary next-step"><?php esc_html_e('Next', 'aqualuxe'); ?></button>
                    </div>
                </div>
                
                <div class="form-step" id="step-2" style="display: none;">
                    <h4><?php esc_html_e('Select Date & Time', 'aqualuxe'); ?></h4>
                    
                    <div class="form-row">
                        <div class="form-field">
                            <label for="booking_date"><?php esc_html_e('Date', 'aqualuxe'); ?> *</label>
                            <input type="date" name="booking_date" id="booking_date" 
                                   min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>"
                                   max="<?php echo date('Y-m-d', strtotime('+' . $this->config['advance_booking_days'] . ' days')); ?>" required />
                        </div>
                        <div class="form-field">
                            <label for="booking_time"><?php esc_html_e('Time', 'aqualuxe'); ?> *</label>
                            <select name="booking_time" id="booking_time" required>
                                <option value=""><?php esc_html_e('Select a time...', 'aqualuxe'); ?></option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="step-actions">
                        <button type="button" class="btn btn-secondary prev-step"><?php esc_html_e('Previous', 'aqualuxe'); ?></button>
                        <button type="button" class="btn btn-primary next-step"><?php esc_html_e('Next', 'aqualuxe'); ?></button>
                    </div>
                </div>
                
                <div class="form-step" id="step-3" style="display: none;">
                    <h4><?php esc_html_e('Your Information', 'aqualuxe'); ?></h4>
                    
                    <div class="form-row">
                        <div class="form-field">
                            <label for="customer_name"><?php esc_html_e('Full Name', 'aqualuxe'); ?> *</label>
                            <input type="text" name="customer_name" id="customer_name" required />
                        </div>
                        <div class="form-field">
                            <label for="customer_email"><?php esc_html_e('Email Address', 'aqualuxe'); ?> *</label>
                            <input type="email" name="customer_email" id="customer_email" required />
                        </div>
                    </div>
                    
                    <div class="form-field">
                        <label for="customer_phone"><?php esc_html_e('Phone Number', 'aqualuxe'); ?> *</label>
                        <input type="tel" name="customer_phone" id="customer_phone" required />
                    </div>
                    
                    <div class="form-field">
                        <label for="special_requests"><?php esc_html_e('Special Requests', 'aqualuxe'); ?></label>
                        <textarea name="special_requests" id="special_requests" rows="3" 
                                  placeholder="<?php esc_attr_e('Any special requirements or requests...', 'aqualuxe'); ?>"></textarea>
                    </div>
                    
                    <div class="step-actions">
                        <button type="button" class="btn btn-secondary prev-step"><?php esc_html_e('Previous', 'aqualuxe'); ?></button>
                        <button type="submit" class="btn btn-primary"><?php esc_html_e('Book Now', 'aqualuxe'); ?></button>
                    </div>
                </div>
            </form>
        </div>
        <?php
        
        return ob_get_clean();
    }
    
    /**
     * Render booking calendar shortcode
     */
    public function render_booking_calendar($atts) {
        $atts = shortcode_atts([
            'service_id' => 0,
            'view' => 'month', // month, week, day
        ], $atts);
        
        ob_start();
        ?>
        <div class="booking-calendar-container">
            <div class="calendar-header">
                <button class="calendar-nav prev" data-direction="prev">&laquo;</button>
                <h3 class="calendar-title"></h3>
                <button class="calendar-nav next" data-direction="next">&raquo;</button>
            </div>
            
            <div class="calendar-view">
                <div class="calendar-grid" id="booking-calendar" 
                     data-service-id="<?php echo esc_attr($atts['service_id']); ?>"
                     data-view="<?php echo esc_attr($atts['view']); ?>">
                    <!-- Calendar will be populated by JavaScript -->
                </div>
            </div>
            
            <div class="calendar-legend">
                <div class="legend-item">
                    <span class="legend-color available"></span>
                    <span class="legend-label"><?php esc_html_e('Available', 'aqualuxe'); ?></span>
                </div>
                <div class="legend-item">
                    <span class="legend-color booked"></span>
                    <span class="legend-label"><?php esc_html_e('Booked', 'aqualuxe'); ?></span>
                </div>
                <div class="legend-item">
                    <span class="legend-color unavailable"></span>
                    <span class="legend-label"><?php esc_html_e('Unavailable', 'aqualuxe'); ?></span>
                </div>
            </div>
        </div>
        <?php
        
        return ob_get_clean();
    }
    
    /**
     * Render my bookings shortcode
     */
    public function render_my_bookings($atts) {
        if (!is_user_logged_in()) {
            return '<p>' . esc_html__('Please log in to view your bookings.', 'aqualuxe') . '</p>';
        }
        
        $user_id = get_current_user_id();
        $bookings = get_posts([
            'post_type' => 'booking',
            'posts_per_page' => -1,
            'meta_query' => [
                [
                    'key' => 'user_id',
                    'value' => $user_id,
                    'compare' => '=',
                ],
            ],
            'orderby' => 'meta_value',
            'meta_key' => 'booking_datetime',
            'order' => 'DESC',
        ]);
        
        if (empty($bookings)) {
            return '<p>' . esc_html__('You have no bookings yet.', 'aqualuxe') . '</p>';
        }
        
        ob_start();
        ?>
        <div class="my-bookings">
            <h3><?php esc_html_e('My Bookings', 'aqualuxe'); ?></h3>
            
            <div class="bookings-list">
                <?php foreach ($bookings as $booking): ?>
                    <?php
                    $service_id = get_post_meta($booking->ID, 'service_id', true);
                    $service = get_post($service_id);
                    $booking_datetime = get_post_meta($booking->ID, 'booking_datetime', true);
                    $status = get_post_meta($booking->ID, 'booking_status', true);
                    $can_cancel = strtotime($booking_datetime) > (current_time('timestamp') + $this->config['cancellation_hours'] * 3600);
                    ?>
                    <div class="booking-item status-<?php echo esc_attr($status); ?>">
                        <div class="booking-info">
                            <h4 class="service-name"><?php echo esc_html($service->post_title); ?></h4>
                            <div class="booking-details">
                                <span class="booking-date"><?php echo date('F j, Y \a\t g:i A', strtotime($booking_datetime)); ?></span>
                                <span class="booking-status status-<?php echo esc_attr($status); ?>"><?php echo esc_html(ucfirst($status)); ?></span>
                            </div>
                        </div>
                        
                        <div class="booking-actions">
                            <?php if ($status === 'confirmed' && $can_cancel): ?>
                                <button class="btn btn-secondary cancel-booking" data-booking-id="<?php echo esc_attr($booking->ID); ?>">
                                    <?php esc_html_e('Cancel', 'aqualuxe'); ?>
                                </button>
                            <?php endif; ?>
                            
                            <a href="<?php echo esc_url(get_permalink($service_id)); ?>" class="btn btn-outline">
                                <?php esc_html_e('View Service', 'aqualuxe'); ?>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
        
        return ob_get_clean();
    }
    
    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        add_submenu_page(
            'edit.php?post_type=booking',
            esc_html__('Booking Settings', 'aqualuxe'),
            esc_html__('Settings', 'aqualuxe'),
            'manage_options',
            'booking-settings',
            [$this, 'render_admin_settings']
        );
    }
    
    /**
     * Render admin settings page
     */
    public function render_admin_settings() {
        if (isset($_POST['submit'])) {
            $this->update_config([
                'booking_buffer_minutes' => intval($_POST['booking_buffer_minutes']),
                'advance_booking_days' => intval($_POST['advance_booking_days']),
                'cancellation_hours' => intval($_POST['cancellation_hours']),
                'auto_confirm' => isset($_POST['auto_confirm']),
                'require_payment' => isset($_POST['require_payment']),
                'time_slots' => array_map('sanitize_text_field', explode("\n", $_POST['time_slots'])),
            ]);
            
            echo '<div class="notice notice-success"><p>' . esc_html__('Settings updated successfully!', 'aqualuxe') . '</p></div>';
        }
        
        ?>
        <div class="wrap">
            <h1><?php esc_html_e('Booking Settings', 'aqualuxe'); ?></h1>
            
            <form method="post" action="">
                <table class="form-table">
                    <tr>
                        <th scope="row"><?php esc_html_e('Booking Buffer (minutes)', 'aqualuxe'); ?></th>
                        <td>
                            <input type="number" name="booking_buffer_minutes" value="<?php echo esc_attr($this->config['booking_buffer_minutes']); ?>" min="0" />
                            <p class="description"><?php esc_html_e('Minimum time between bookings', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Advance Booking (days)', 'aqualuxe'); ?></th>
                        <td>
                            <input type="number" name="advance_booking_days" value="<?php echo esc_attr($this->config['advance_booking_days']); ?>" min="1" />
                            <p class="description"><?php esc_html_e('How far in advance customers can book', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Cancellation Notice (hours)', 'aqualuxe'); ?></th>
                        <td>
                            <input type="number" name="cancellation_hours" value="<?php echo esc_attr($this->config['cancellation_hours']); ?>" min="1" />
                            <p class="description"><?php esc_html_e('Minimum notice required for cancellations', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Auto-confirm Bookings', 'aqualuxe'); ?></th>
                        <td>
                            <input type="checkbox" name="auto_confirm" value="1" <?php checked($this->config['auto_confirm']); ?> />
                            <p class="description"><?php esc_html_e('Automatically confirm bookings without admin approval', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Require Payment', 'aqualuxe'); ?></th>
                        <td>
                            <input type="checkbox" name="require_payment" value="1" <?php checked($this->config['require_payment']); ?> />
                            <p class="description"><?php esc_html_e('Require payment before confirming bookings', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Available Time Slots', 'aqualuxe'); ?></th>
                        <td>
                            <textarea name="time_slots" rows="10" cols="20"><?php echo esc_textarea(implode("\n", $this->config['time_slots'])); ?></textarea>
                            <p class="description"><?php esc_html_e('One time slot per line (24-hour format, e.g., 09:00)', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                </table>
                
                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }
    
    /**
     * Add dashboard widget
     */
    public function add_dashboard_widget($widgets) {
        $widgets['bookings'] = [
            'title' => esc_html__('Bookings & Scheduling', 'aqualuxe'),
            'callback' => [$this, 'render_dashboard_widget'],
            'priority' => 80,
        ];
        
        return $widgets;
    }
    
    /**
     * Render dashboard widget
     */
    public function render_dashboard_widget() {
        $today_bookings = get_posts([
            'post_type' => 'booking',
            'posts_per_page' => -1,
            'meta_query' => [
                [
                    'key' => 'booking_datetime',
                    'value' => [date('Y-m-d 00:00:00'), date('Y-m-d 23:59:59')],
                    'compare' => 'BETWEEN',
                    'type' => 'DATETIME',
                ],
                [
                    'key' => 'booking_status',
                    'value' => 'confirmed',
                    'compare' => '=',
                ],
            ],
            'fields' => 'ids',
        ]);
        
        $pending_bookings = get_posts([
            'post_type' => 'booking',
            'posts_per_page' => -1,
            'meta_query' => [
                [
                    'key' => 'booking_status',
                    'value' => 'pending',
                    'compare' => '=',
                ],
            ],
            'fields' => 'ids',
        ]);
        
        $total_services = wp_count_posts('bookable_service');
        
        echo '<div class="aqualuxe-dashboard-widget">';
        echo '<div class="stats-row">';
        echo '<div class="stat-item">';
        echo '<span class="stat-number">' . count($today_bookings) . '</span>';
        echo '<span class="stat-label">' . esc_html__('Today\'s Bookings', 'aqualuxe') . '</span>';
        echo '</div>';
        echo '<div class="stat-item">';
        echo '<span class="stat-number">' . count($pending_bookings) . '</span>';
        echo '<span class="stat-label">' . esc_html__('Pending Bookings', 'aqualuxe') . '</span>';
        echo '</div>';
        echo '<div class="stat-item">';
        echo '<span class="stat-number">' . intval($total_services->publish) . '</span>';
        echo '<span class="stat-label">' . esc_html__('Bookable Services', 'aqualuxe') . '</span>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }
    
    /**
     * Get module configuration
     */
    public function get_config() {
        return $this->config;
    }
    
    /**
     * Update module configuration
     */
    public function update_config($config) {
        $this->config = array_merge($this->config, $config);
        update_option('aqualuxe_bookings_config', $this->config);
    }
}