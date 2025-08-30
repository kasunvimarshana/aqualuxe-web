<?php
/**
 * Bookings Shortcodes
 *
 * Registers shortcodes for the bookings module.
 *
 * @package AquaLuxe
 * @subpackage Bookings
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Bookings Shortcodes Class
 */
class AquaLuxe_Bookings_Shortcodes {
    /**
     * Constructor
     */
    public function __construct() {
        // Register shortcodes
        add_shortcode('aqualuxe_booking_form', array($this, 'booking_form_shortcode'));
        add_shortcode('aqualuxe_booking_calendar', array($this, 'booking_calendar_shortcode'));
        add_shortcode('aqualuxe_booking_services', array($this, 'booking_services_shortcode'));
        add_shortcode('aqualuxe_booking_confirmation', array($this, 'booking_confirmation_shortcode'));
        add_shortcode('aqualuxe_my_bookings', array($this, 'my_bookings_shortcode'));
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
}