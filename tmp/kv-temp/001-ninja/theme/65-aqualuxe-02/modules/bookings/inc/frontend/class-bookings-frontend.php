<?php
/**
 * Bookings Frontend
 *
 * Handles frontend functionality for the bookings module.
 *
 * @package AquaLuxe
 * @subpackage Bookings
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Bookings Frontend Class
 */
class AquaLuxe_Bookings_Frontend {
    /**
     * Constructor
     */
    public function __construct() {
        // Initialize hooks
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('wp_head', array($this, 'add_custom_styles'));
        add_action('template_redirect', array($this, 'template_redirect'));
        
        // Add body classes
        add_filter('body_class', array($this, 'body_class'));
        
        // Add shortcodes
        add_shortcode('aqualuxe_booking_form', array($this, 'booking_form_shortcode'));
        add_shortcode('aqualuxe_booking_calendar', array($this, 'booking_calendar_shortcode'));
        add_shortcode('aqualuxe_booking_services', array($this, 'booking_services_shortcode'));
        add_shortcode('aqualuxe_booking_confirmation', array($this, 'booking_confirmation_shortcode'));
        add_shortcode('aqualuxe_my_bookings', array($this, 'my_bookings_shortcode'));
        
        // Add widgets
        add_action('widgets_init', array($this, 'register_widgets'));
        
        // Add AJAX handlers
        add_action('wp_ajax_get_available_dates', array($this, 'get_available_dates'));
        add_action('wp_ajax_nopriv_get_available_dates', array($this, 'get_available_dates'));
        
        add_action('wp_ajax_get_available_times', array($this, 'get_available_times'));
        add_action('wp_ajax_nopriv_get_available_times', array($this, 'get_available_times'));
        
        add_action('wp_ajax_calculate_booking_price', array($this, 'calculate_booking_price'));
        add_action('wp_ajax_nopriv_calculate_booking_price', array($this, 'calculate_booking_price'));
        
        add_action('wp_ajax_validate_booking_form', array($this, 'validate_booking_form'));
        add_action('wp_ajax_nopriv_validate_booking_form', array($this, 'validate_booking_form'));
        
        add_action('wp_ajax_cancel_booking', array($this, 'cancel_booking'));
        add_action('wp_ajax_nopriv_cancel_booking', array($this, 'cancel_booking'));
    }

    /**
     * Enqueue scripts
     */
    public function enqueue_scripts() {
        // Register styles
        wp_register_style(
            'aqualuxe-bookings',
            AQUALUXE_BOOKINGS_URL . 'assets/dist/css/bookings.css',
            array(),
            AQUALUXE_BOOKINGS_VERSION
        );
        
        // Register scripts
        wp_register_script(
            'aqualuxe-bookings',
            AQUALUXE_BOOKINGS_URL . 'assets/dist/js/bookings.js',
            array('jquery', 'jquery-ui-datepicker', 'jquery-ui-slider'),
            AQUALUXE_BOOKINGS_VERSION,
            true
        );
        
        // Localize script
        wp_localize_script('aqualuxe-bookings', 'aqualuxe_bookings_params', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('aqualuxe-bookings'),
            'i18n' => array(
                'select_date' => __('Please select a date', 'aqualuxe'),
                'select_time' => __('Please select a time', 'aqualuxe'),
                'select_service' => __('Please select a service', 'aqualuxe'),
                'minimum_duration' => __('Minimum booking duration is %s', 'aqualuxe'),
                'maximum_duration' => __('Maximum booking duration is %s', 'aqualuxe'),
                'date_format' => get_option('date_format'),
                'time_format' => get_option('aqualuxe_bookings_time_format', '12h'),
                'currency_symbol' => function_exists('get_woocommerce_currency_symbol') ? get_woocommerce_currency_symbol() : '$',
                'currency_position' => function_exists('get_option') ? get_option('woocommerce_currency_pos', 'left') : 'left',
                'decimal_separator' => function_exists('get_option') ? get_option('woocommerce_price_decimal_sep', '.') : '.',
                'thousand_separator' => function_exists('get_option') ? get_option('woocommerce_price_thousand_sep', ',') : ',',
                'decimals' => function_exists('get_option') ? get_option('woocommerce_price_num_decimals', 2) : 2,
                'confirm_cancel' => __('Are you sure you want to cancel this booking?', 'aqualuxe'),
            ),
            'settings' => array(
                'buffer_time' => get_option('aqualuxe_bookings_buffer_time', 30),
                'min_booking_time' => get_option('aqualuxe_bookings_min_booking_time', 60),
                'max_booking_time' => get_option('aqualuxe_bookings_max_booking_time', 480),
                'calendar_first_day' => get_option('aqualuxe_bookings_calendar_first_day', 0),
                'color_scheme' => get_option('aqualuxe_bookings_color_scheme', '#0073aa'),
                'form_style' => get_option('aqualuxe_bookings_form_style', 'default'),
                'calendar_style' => get_option('aqualuxe_bookings_calendar_style', 'default'),
            ),
        ));
        
        // Enqueue on specific pages
        if (is_singular('bookable_service') || is_post_type_archive('bookable_service') || is_tax('service_category') || is_tax('service_tag')) {
            wp_enqueue_style('aqualuxe-bookings');
            wp_enqueue_script('aqualuxe-bookings');
        }
        
        // Enqueue on booking page
        $booking_page_id = get_option('aqualuxe_bookings_page_id');
        
        if ($booking_page_id && is_page($booking_page_id)) {
            wp_enqueue_style('aqualuxe-bookings');
            wp_enqueue_script('aqualuxe-bookings');
        }
        
        // Enqueue on confirmation page
        $confirmation_page_id = get_option('aqualuxe_bookings_confirmation_page_id');
        
        if ($confirmation_page_id && is_page($confirmation_page_id)) {
            wp_enqueue_style('aqualuxe-bookings');
            wp_enqueue_script('aqualuxe-bookings');
        }
        
        // Enqueue if shortcode is used
        global $post;
        
        if (is_a($post, 'WP_Post') && (
            has_shortcode($post->post_content, 'aqualuxe_booking_form') ||
            has_shortcode($post->post_content, 'aqualuxe_booking_calendar') ||
            has_shortcode($post->post_content, 'aqualuxe_booking_services') ||
            has_shortcode($post->post_content, 'aqualuxe_booking_confirmation') ||
            has_shortcode($post->post_content, 'aqualuxe_my_bookings')
        )) {
            wp_enqueue_style('aqualuxe-bookings');
            wp_enqueue_script('aqualuxe-bookings');
        }
    }

    /**
     * Add custom styles
     */
    public function add_custom_styles() {
        $color_scheme = get_option('aqualuxe_bookings_color_scheme', '#0073aa');
        
        echo '<style>
            :root {
                --aqualuxe-bookings-primary-color: ' . esc_attr($color_scheme) . ';
                --aqualuxe-bookings-primary-color-dark: ' . esc_attr($this->adjust_brightness($color_scheme, -20)) . ';
                --aqualuxe-bookings-primary-color-light: ' . esc_attr($this->adjust_brightness($color_scheme, 20)) . ';
                --aqualuxe-bookings-text-on-primary: ' . esc_attr($this->get_contrast_color($color_scheme)) . ';
            }
        </style>';
    }

    /**
     * Template redirect
     */
    public function template_redirect() {
        // Check if we're on a booking page
        $booking_page_id = get_option('aqualuxe_bookings_page_id');
        
        if ($booking_page_id && is_page($booking_page_id)) {
            // Check if user is logged in if required
            $require_account = get_option('aqualuxe_bookings_require_account', 'no');
            $allow_guest_bookings = get_option('aqualuxe_bookings_allow_guest_bookings', 'yes');
            
            if ('yes' === $require_account && !is_user_logged_in() && 'yes' !== $allow_guest_bookings) {
                // Redirect to login page
                wp_redirect(wp_login_url(get_permalink($booking_page_id)));
                exit;
            }
        }
        
        // Check if we're on a confirmation page
        $confirmation_page_id = get_option('aqualuxe_bookings_confirmation_page_id');
        
        if ($confirmation_page_id && is_page($confirmation_page_id)) {
            // Check if booking ID is provided
            $booking_id = isset($_GET['booking_id']) ? absint($_GET['booking_id']) : 0;
            
            if (empty($booking_id) && !isset($_GET['booking_confirmed'])) {
                // Redirect to booking page
                wp_redirect(get_permalink($booking_page_id));
                exit;
            }
        }
    }

    /**
     * Add body classes
     *
     * @param array $classes Body classes
     * @return array Modified body classes
     */
    public function body_class($classes) {
        // Add class for bookable service pages
        if (is_singular('bookable_service')) {
            $classes[] = 'aqualuxe-bookings';
            $classes[] = 'aqualuxe-bookable-service';
        } elseif (is_post_type_archive('bookable_service') || is_tax('service_category') || is_tax('service_tag')) {
            $classes[] = 'aqualuxe-bookings';
            $classes[] = 'aqualuxe-bookable-service-archive';
        }
        
        // Add class for booking page
        $booking_page_id = get_option('aqualuxe_bookings_page_id');
        
        if ($booking_page_id && is_page($booking_page_id)) {
            $classes[] = 'aqualuxe-bookings';
            $classes[] = 'aqualuxe-booking-page';
        }
        
        // Add class for confirmation page
        $confirmation_page_id = get_option('aqualuxe_bookings_confirmation_page_id');
        
        if ($confirmation_page_id && is_page($confirmation_page_id)) {
            $classes[] = 'aqualuxe-bookings';
            $classes[] = 'aqualuxe-booking-confirmation-page';
        }
        
        // Add class for form style
        $form_style = get_option('aqualuxe_bookings_form_style', 'default');
        
        if (!empty($form_style)) {
            $classes[] = 'aqualuxe-bookings-form-style-' . $form_style;
        }
        
        // Add class for calendar style
        $calendar_style = get_option('aqualuxe_bookings_calendar_style', 'default');
        
        if (!empty($calendar_style)) {
            $classes[] = 'aqualuxe-bookings-calendar-style-' . $calendar_style;
        }
        
        return $classes;
    }

    /**
     * Register widgets
     */
    public function register_widgets() {
        register_widget('AquaLuxe_Bookings_Services_Widget');
        register_widget('AquaLuxe_Bookings_Calendar_Widget');
    }

    /**
     * Booking form shortcode
     *
     * @param array $atts Shortcode attributes
     * @return string Shortcode output
     */
    public function booking_form_shortcode($atts) {
        $atts = shortcode_atts(array(
            'service_id' => 0,
            'show_title' => 'yes',
            'show_description' => 'yes',
            'show_price' => 'yes',
            'show_image' => 'yes',
            'redirect_url' => '',
        ), $atts, 'aqualuxe_booking_form');

        // Get service ID
        $service_id = absint($atts['service_id']);
        
        // If no service ID is provided, check if we're on a service page
        if (empty($service_id) && is_singular('bookable_service')) {
            $service_id = get_the_ID();
        }

        // Start output buffering
        ob_start();

        // Get template
        aqualuxe_bookings_get_template('booking-form.php', array(
            'service_id' => $service_id,
            'show_title' => 'yes' === $atts['show_title'],
            'show_description' => 'yes' === $atts['show_description'],
            'show_price' => 'yes' === $atts['show_price'],
            'show_image' => 'yes' === $atts['show_image'],
            'redirect_url' => $atts['redirect_url'],
        ));

        // Return the buffered content
        return ob_get_clean();
    }

    /**
     * Booking calendar shortcode
     *
     * @param array $atts Shortcode attributes
     * @return string Shortcode output
     */
    public function booking_calendar_shortcode($atts) {
        $atts = shortcode_atts(array(
            'service_id' => 0,
            'show_title' => 'yes',
            'show_legend' => 'yes',
            'months' => 1,
        ), $atts, 'aqualuxe_booking_calendar');

        // Get service ID
        $service_id = absint($atts['service_id']);
        
        // If no service ID is provided, check if we're on a service page
        if (empty($service_id) && is_singular('bookable_service')) {
            $service_id = get_the_ID();
        }

        // Start output buffering
        ob_start();

        // Get template
        aqualuxe_bookings_get_template('booking-calendar.php', array(
            'service_id' => $service_id,
            'show_title' => 'yes' === $atts['show_title'],
            'show_legend' => 'yes' === $atts['show_legend'],
            'months' => absint($atts['months']),
        ));

        // Return the buffered content
        return ob_get_clean();
    }

    /**
     * Booking services shortcode
     *
     * @param array $atts Shortcode attributes
     * @return string Shortcode output
     */
    public function booking_services_shortcode($atts) {
        $atts = shortcode_atts(array(
            'category' => '',
            'tag' => '',
            'columns' => 3,
            'orderby' => 'title',
            'order' => 'ASC',
            'limit' => -1,
            'show_image' => 'yes',
            'show_price' => 'yes',
            'show_description' => 'yes',
            'show_button' => 'yes',
            'button_text' => __('Book Now', 'aqualuxe'),
        ), $atts, 'aqualuxe_booking_services');

        // Start output buffering
        ob_start();

        // Build query args
        $args = array(
            'post_type' => 'bookable_service',
            'posts_per_page' => $atts['limit'],
            'orderby' => $atts['orderby'],
            'order' => $atts['order'],
        );

        // Filter by category
        if (!empty($atts['category'])) {
            $args['tax_query'][] = array(
                'taxonomy' => 'service_category',
                'field' => 'slug',
                'terms' => explode(',', $atts['category']),
            );
        }

        // Filter by tag
        if (!empty($atts['tag'])) {
            $args['tax_query'][] = array(
                'taxonomy' => 'service_tag',
                'field' => 'slug',
                'terms' => explode(',', $atts['tag']),
            );
        }

        // Get services
        $services = new WP_Query($args);

        // Get template
        aqualuxe_bookings_get_template('booking-services.php', array(
            'services' => $services,
            'columns' => absint($atts['columns']),
            'show_image' => 'yes' === $atts['show_image'],
            'show_price' => 'yes' === $atts['show_price'],
            'show_description' => 'yes' === $atts['show_description'],
            'show_button' => 'yes' === $atts['show_button'],
            'button_text' => $atts['button_text'],
        ));

        // Return the buffered content
        return ob_get_clean();
    }

    /**
     * Booking confirmation shortcode
     *
     * @param array $atts Shortcode attributes
     * @return string Shortcode output
     */
    public function booking_confirmation_shortcode($atts) {
        $atts = shortcode_atts(array(
            'show_details' => 'yes',
            'show_message' => 'yes',
            'message' => __('Thank you for your booking. We have received your request and will contact you shortly.', 'aqualuxe'),
        ), $atts, 'aqualuxe_booking_confirmation');

        // Start output buffering
        ob_start();

        // Get booking ID from query string
        $booking_id = isset($_GET['booking_id']) ? absint($_GET['booking_id']) : 0;

        // If no booking ID is provided, check for booking_confirmed parameter
        if (empty($booking_id) && isset($_GET['booking_confirmed'])) {
            $booking_id = absint($_GET['booking_confirmed']);
        }

        // Get booking data
        $booking = null;
        
        if ($booking_id > 0) {
            $bookings_data = new AquaLuxe_Bookings_Data();
            $booking = $bookings_data->get_booking($booking_id);
        }

        // Get template
        aqualuxe_bookings_get_template('booking-confirmation.php', array(
            'booking' => $booking,
            'show_details' => 'yes' === $atts['show_details'],
            'show_message' => 'yes' === $atts['show_message'],
            'message' => $atts['message'],
        ));

        // Return the buffered content
        return ob_get_clean();
    }

    /**
     * My bookings shortcode
     *
     * @param array $atts Shortcode attributes
     * @return string Shortcode output
     */
    public function my_bookings_shortcode($atts) {
        $atts = shortcode_atts(array(
            'show_title' => 'yes',
            'show_status' => 'yes',
            'show_actions' => 'yes',
            'limit' => 10,
        ), $atts, 'aqualuxe_my_bookings');

        // Start output buffering
        ob_start();

        // Check if user is logged in
        if (!is_user_logged_in()) {
            // Show login form
            aqualuxe_bookings_get_template('booking-login.php');
            return ob_get_clean();
        }

        // Get current user ID
        $user_id = get_current_user_id();

        // Get bookings
        $bookings_data = new AquaLuxe_Bookings_Data();
        $bookings = $bookings_data->get_bookings(array(
            'customer_id' => $user_id,
            'limit' => absint($atts['limit']),
        ));

        // Get template
        aqualuxe_bookings_get_template('my-bookings.php', array(
            'bookings' => $bookings,
            'show_title' => 'yes' === $atts['show_title'],
            'show_status' => 'yes' === $atts['show_status'],
            'show_actions' => 'yes' === $atts['show_actions'],
        ));

        // Return the buffered content
        return ob_get_clean();
    }

    /**
     * Get available dates
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
        $ajax = new AquaLuxe_Bookings_AJAX();
        $available_dates = $ajax->get_service_available_dates($service_id, $first_day, $last_day);

        wp_send_json_success(array(
            'dates' => $available_dates,
            'month' => $month,
            'year' => $year,
        ));
    }

    /**
     * Get available times
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
        $ajax = new AquaLuxe_Bookings_AJAX();
        $time_slots = $ajax->get_service_available_times($service_id, $date);

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
     * Cancel booking
     */
    public function cancel_booking() {
        // Check nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'aqualuxe-bookings')) {
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

        // Check if user is authorized to cancel this booking
        $user_id = get_current_user_id();
        
        if ($user_id !== intval($booking['customer_id']) && !current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('You are not authorized to cancel this booking', 'aqualuxe')));
        }

        // Update booking status
        $result = $bookings_data->update_booking($booking_id, array('status' => 'aqualuxe-cancelled'));
        
        if (is_wp_error($result)) {
            wp_send_json_error(array('message' => $result->get_error_message()));
        }

        // Send cancellation notification
        $this->send_cancellation_notification($booking);

        wp_send_json_success(array(
            'message' => __('Booking cancelled successfully', 'aqualuxe'),
        ));
    }

    /**
     * Send cancellation notification
     *
     * @param array $booking Booking data
     */
    private function send_cancellation_notification($booking) {
        // Check if notifications are enabled
        if ('yes' !== get_option('aqualuxe_bookings_notification_emails', 'yes')) {
            return;
        }

        // Send admin notification
        $admin_email = get_option('aqualuxe_bookings_admin_notification_email', get_option('admin_email'));
        
        if (!empty($admin_email)) {
            // Format date and time
            $date_format = get_option('date_format');
            $time_format = get_option('time_format');
            
            $booking_date = date_i18n($date_format, strtotime($booking['start_date']));
            $booking_time = date_i18n($time_format, strtotime($booking['start_date'])) . ' - ' . date_i18n($time_format, strtotime($booking['end_date']));
            
            // Build email content
            $subject = sprintf(__('Booking Cancelled: %s', 'aqualuxe'), $booking['booking_id']);
            
            $message = sprintf(__('A booking has been cancelled on your website.', 'aqualuxe')) . "\n\n";
            $message .= sprintf(__('Booking ID: %s', 'aqualuxe'), $booking['booking_id']) . "\n";
            $message .= sprintf(__('Service: %s', 'aqualuxe'), $booking['service_name']) . "\n";
            $message .= sprintf(__('Date: %s', 'aqualuxe'), $booking_date) . "\n";
            $message .= sprintf(__('Time: %s', 'aqualuxe'), $booking_time) . "\n";
            $message .= sprintf(__('Customer: %s', 'aqualuxe'), $booking['customer_name']) . "\n";
            $message .= sprintf(__('Email: %s', 'aqualuxe'), $booking['customer_email']) . "\n";
            $message .= sprintf(__('Phone: %s', 'aqualuxe'), $booking['customer_phone']) . "\n";
            
            // Send email
            wp_mail($admin_email, $subject, $message);
        }

        // Send customer notification
        if ('yes' === get_option('aqualuxe_bookings_customer_notification_email', 'yes') && !empty($booking['customer_email'])) {
            // Format date and time
            $date_format = get_option('date_format');
            $time_format = get_option('time_format');
            
            $booking_date = date_i18n($date_format, strtotime($booking['start_date']));
            $booking_time = date_i18n($time_format, strtotime($booking['start_date'])) . ' - ' . date_i18n($time_format, strtotime($booking['end_date']));
            
            // Build email content
            $subject = sprintf(__('Your Booking Has Been Cancelled: %s', 'aqualuxe'), $booking['booking_id']);
            
            $message = sprintf(__('Your booking with %s has been cancelled.', 'aqualuxe'), get_bloginfo('name')) . "\n\n";
            $message .= sprintf(__('Booking ID: %s', 'aqualuxe'), $booking['booking_id']) . "\n";
            $message .= sprintf(__('Service: %s', 'aqualuxe'), $booking['service_name']) . "\n";
            $message .= sprintf(__('Date: %s', 'aqualuxe'), $booking_date) . "\n";
            $message .= sprintf(__('Time: %s', 'aqualuxe'), $booking_time) . "\n\n";
            
            $message .= sprintf(__('If you have any questions, please contact us at %s.', 'aqualuxe'), get_option('admin_email')) . "\n\n";
            $message .= __('Thank you for choosing us!', 'aqualuxe') . "\n";
            $message .= get_bloginfo('name');
            
            // Send email
            wp_mail($booking['customer_email'], $subject, $message);
        }
    }

    /**
     * Adjust color brightness
     *
     * @param string $hex Hex color
     * @param int $steps Steps to adjust brightness (-255 to 255)
     * @return string Adjusted hex color
     */
    private function adjust_brightness($hex, $steps) {
        // Remove # if present
        $hex = ltrim($hex, '#');
        
        // Convert to RGB
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
        
        // Adjust brightness
        $r = max(0, min(255, $r + $steps));
        $g = max(0, min(255, $g + $steps));
        $b = max(0, min(255, $b + $steps));
        
        // Convert back to hex
        return '#' . sprintf('%02x%02x%02x', $r, $g, $b);
    }

    /**
     * Get contrast color
     *
     * @param string $hex Hex color
     * @return string Contrast color (#ffffff or #000000)
     */
    private function get_contrast_color($hex) {
        // Remove # if present
        $hex = ltrim($hex, '#');
        
        // Convert to RGB
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
        
        // Calculate luminance
        $luminance = (0.299 * $r + 0.587 * $g + 0.114 * $b) / 255;
        
        // Return black or white based on luminance
        return $luminance > 0.5 ? '#000000' : '#ffffff';
    }
}