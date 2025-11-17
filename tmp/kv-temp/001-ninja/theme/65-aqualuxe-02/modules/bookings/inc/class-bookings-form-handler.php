<?php
/**
 * Bookings Form Handler
 *
 * Handles form submissions for the bookings module.
 *
 * @package AquaLuxe
 * @subpackage Bookings
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Bookings Form Handler Class
 */
class AquaLuxe_Bookings_Form_Handler {
    /**
     * Constructor
     */
    public function __construct() {
        // Initialize hooks
        add_action('init', array($this, 'init'));
        
        // Process form submissions
        add_action('wp_loaded', array($this, 'process_booking_form'), 20);
        
        // Add booking to cart (WooCommerce integration)
        add_action('wp_loaded', array($this, 'add_booking_to_cart'), 20);
    }

    /**
     * Initialize
     */
    public function init() {
        // Add shortcode
        add_shortcode('aqualuxe_booking_form', array($this, 'booking_form_shortcode'));
    }

    /**
     * Process booking form submission
     */
    public function process_booking_form() {
        if (!isset($_POST['aqualuxe_booking_form']) || !wp_verify_nonce($_POST['aqualuxe_booking_nonce'], 'aqualuxe_booking_form')) {
            return;
        }

        // Get form data
        $service_id = isset($_POST['service_id']) ? absint($_POST['service_id']) : 0;
        $date = isset($_POST['booking_date']) ? sanitize_text_field($_POST['booking_date']) : '';
        $time = isset($_POST['booking_time']) ? sanitize_text_field($_POST['booking_time']) : '';
        $duration = isset($_POST['booking_duration']) ? absint($_POST['booking_duration']) : 0;
        $quantity = isset($_POST['booking_quantity']) ? absint($_POST['booking_quantity']) : 1;
        $customer_name = isset($_POST['customer_name']) ? sanitize_text_field($_POST['customer_name']) : '';
        $customer_email = isset($_POST['customer_email']) ? sanitize_email($_POST['customer_email']) : '';
        $customer_phone = isset($_POST['customer_phone']) ? sanitize_text_field($_POST['customer_phone']) : '';
        $customer_notes = isset($_POST['customer_notes']) ? sanitize_textarea_field($_POST['customer_notes']) : '';

        // Validate required fields
        $errors = array();

        if (empty($service_id)) {
            $errors[] = __('Please select a service', 'aqualuxe');
        }

        if (empty($date)) {
            $errors[] = __('Please select a date', 'aqualuxe');
        }

        if (empty($time)) {
            $errors[] = __('Please select a time', 'aqualuxe');
        }

        if (empty($customer_name)) {
            $errors[] = __('Please enter your name', 'aqualuxe');
        }

        if (empty($customer_email)) {
            $errors[] = __('Please enter your email', 'aqualuxe');
        } elseif (!is_email($customer_email)) {
            $errors[] = __('Please enter a valid email address', 'aqualuxe');
        }

        if (empty($customer_phone)) {
            $errors[] = __('Please enter your phone number', 'aqualuxe');
        }

        // If there are errors, store them in session and redirect back to form
        if (!empty($errors)) {
            $this->store_errors($errors);
            $this->store_form_data($_POST);
            
            // Redirect back to form
            wp_redirect(wp_get_referer() ? wp_get_referer() : home_url());
            exit;
        }

        // Calculate start and end dates
        $start_date = date('Y-m-d H:i:s', strtotime($date . ' ' . $time));
        
        // If duration is not provided, get it from the service
        if (empty($duration)) {
            $duration = get_post_meta($service_id, '_service_duration', true);
            $duration = !empty($duration) ? intval($duration) : 60; // Default to 60 minutes
        }
        
        $end_date = date('Y-m-d H:i:s', strtotime($start_date . ' +' . $duration . ' minutes'));

        // Get service price
        $price = get_post_meta($service_id, '_service_price', true);
        $price = !empty($price) ? floatval($price) : 0;
        
        // Calculate total
        $total = $price * $quantity;

        // Get current user ID
        $customer_id = get_current_user_id();

        // Determine booking confirmation method
        $confirmation_method = get_option('aqualuxe_bookings_booking_confirmation', 'payment');

        // If using WooCommerce for payment, add to cart and redirect to checkout
        if ('payment' === $confirmation_method && class_exists('WooCommerce')) {
            // Store booking data in session
            WC()->session->set('aqualuxe_booking_data', array(
                'service_id'     => $service_id,
                'customer_id'    => $customer_id,
                'customer_name'  => $customer_name,
                'customer_email' => $customer_email,
                'customer_phone' => $customer_phone,
                'customer_notes' => $customer_notes,
                'start_date'     => $start_date,
                'end_date'       => $end_date,
                'quantity'       => $quantity,
                'total'          => $total,
            ));

            // Redirect to add to cart endpoint
            wp_redirect(add_query_arg('add-booking-to-cart', $service_id, wc_get_cart_url()));
            exit;
        }

        // Otherwise, create booking directly
        $booking_data = array(
            'service_id'     => $service_id,
            'customer_id'    => $customer_id,
            'customer_name'  => $customer_name,
            'customer_email' => $customer_email,
            'customer_phone' => $customer_phone,
            'start_date'     => $start_date,
            'end_date'       => $end_date,
            'all_day'        => false,
            'quantity'       => $quantity,
            'total'          => $total,
            'status'         => 'automatic' === $confirmation_method ? 'aqualuxe-confirmed' : 'aqualuxe-pending',
        );

        // Create booking
        $bookings_data = new AquaLuxe_Bookings_Data();
        $booking_id = $bookings_data->create_booking($booking_data);

        if (is_wp_error($booking_id)) {
            $this->store_errors(array($booking_id->get_error_message()));
            $this->store_form_data($_POST);
            
            // Redirect back to form
            wp_redirect(wp_get_referer() ? wp_get_referer() : home_url());
            exit;
        }

        // Store customer notes as booking meta
        if (!empty($customer_notes)) {
            update_post_meta($booking_id, '_customer_notes', $customer_notes);
        }

        // Send notifications
        $this->send_notifications($booking_id);

        // Redirect to confirmation page
        $confirmation_page_id = get_option('aqualuxe_bookings_confirmation_page_id');
        
        if ($confirmation_page_id) {
            wp_redirect(add_query_arg('booking_id', $booking_id, get_permalink($confirmation_page_id)));
        } else {
            wp_redirect(add_query_arg('booking_confirmed', $booking_id, wp_get_referer() ? wp_get_referer() : home_url()));
        }
        
        exit;
    }

    /**
     * Add booking to cart (WooCommerce integration)
     */
    public function add_booking_to_cart() {
        if (!isset($_GET['add-booking-to-cart']) || !class_exists('WooCommerce')) {
            return;
        }

        $service_id = absint($_GET['add-booking-to-cart']);
        
        // Get booking data from session
        $booking_data = WC()->session->get('aqualuxe_booking_data');
        
        if (empty($booking_data) || $booking_data['service_id'] !== $service_id) {
            wc_add_notice(__('Invalid booking data', 'aqualuxe'), 'error');
            wp_redirect(wc_get_cart_url());
            exit;
        }

        // Check if time slot is available
        $bookings_data = new AquaLuxe_Bookings_Data();
        
        if (!$bookings_data->is_time_slot_available($service_id, $booking_data['start_date'], $booking_data['end_date'])) {
            wc_add_notice(__('Sorry, this time slot is no longer available. Please select another time.', 'aqualuxe'), 'error');
            wp_redirect(wp_get_referer() ? wp_get_referer() : home_url());
            exit;
        }

        // Format date and time for display
        $date_format = get_option('date_format');
        $time_format = get_option('time_format');
        
        $start_date = date_i18n($date_format, strtotime($booking_data['start_date']));
        $start_time = date_i18n($time_format, strtotime($booking_data['start_date']));
        $end_time = date_i18n($time_format, strtotime($booking_data['end_date']));

        // Create cart item data
        $cart_item_data = array(
            'aqualuxe_booking' => array(
                'service_id'     => $service_id,
                'start_date'     => $booking_data['start_date'],
                'end_date'       => $booking_data['end_date'],
                'customer_name'  => $booking_data['customer_name'],
                'customer_email' => $booking_data['customer_email'],
                'customer_phone' => $booking_data['customer_phone'],
                'customer_notes' => isset($booking_data['customer_notes']) ? $booking_data['customer_notes'] : '',
                'display_date'   => $start_date,
                'display_time'   => $start_time . ' - ' . $end_time,
            ),
        );

        // Generate a unique cart item key
        $cart_item_data['aqualuxe_booking_key'] = md5(json_encode($cart_item_data['aqualuxe_booking']));

        // Add to cart
        $product_id = get_post_meta($service_id, '_product_id', true);
        
        if (empty($product_id)) {
            // If no product is associated, create a temporary one
            $product_id = $this->get_booking_product_id();
        }

        // Add to cart
        $added = WC()->cart->add_to_cart(
            $product_id,
            $booking_data['quantity'],
            0,
            array(),
            $cart_item_data
        );

        if ($added) {
            // Clear session data
            WC()->session->set('aqualuxe_booking_data', null);
            
            // Redirect to checkout
            wp_redirect(wc_get_checkout_url());
        } else {
            wc_add_notice(__('Error adding booking to cart', 'aqualuxe'), 'error');
            wp_redirect(wc_get_cart_url());
        }
        
        exit;
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
        ));

        // Return the buffered content
        return ob_get_clean();
    }

    /**
     * Send booking notifications
     *
     * @param int $booking_id Booking ID
     */
    private function send_notifications($booking_id) {
        // Check if notifications are enabled
        if ('yes' !== get_option('aqualuxe_bookings_notification_emails', 'yes')) {
            return;
        }

        // Get booking data
        $bookings_data = new AquaLuxe_Bookings_Data();
        $booking = $bookings_data->get_booking($booking_id);

        if (!$booking) {
            return;
        }

        // Send admin notification
        $this->send_admin_notification($booking);

        // Send customer notification
        if ('yes' === get_option('aqualuxe_bookings_customer_notification_email', 'yes')) {
            $this->send_customer_notification($booking);
        }
    }

    /**
     * Send admin notification
     *
     * @param array $booking Booking data
     */
    private function send_admin_notification($booking) {
        $admin_email = get_option('aqualuxe_bookings_admin_notification_email', get_option('admin_email'));
        
        if (empty($admin_email)) {
            return;
        }

        // Format date and time
        $date_format = get_option('date_format');
        $time_format = get_option('time_format');
        
        $booking_date = date_i18n($date_format, strtotime($booking['start_date']));
        $booking_time = date_i18n($time_format, strtotime($booking['start_date'])) . ' - ' . date_i18n($time_format, strtotime($booking['end_date']));

        // Build email content
        $subject = sprintf(__('New Booking: %s', 'aqualuxe'), $booking['booking_id']);
        
        $message = sprintf(__('A new booking has been made on your website.', 'aqualuxe')) . "\n\n";
        $message .= sprintf(__('Booking ID: %s', 'aqualuxe'), $booking['booking_id']) . "\n";
        $message .= sprintf(__('Service: %s', 'aqualuxe'), $booking['service_name']) . "\n";
        $message .= sprintf(__('Date: %s', 'aqualuxe'), $booking_date) . "\n";
        $message .= sprintf(__('Time: %s', 'aqualuxe'), $booking_time) . "\n";
        $message .= sprintf(__('Quantity: %s', 'aqualuxe'), $booking['quantity']) . "\n";
        $message .= sprintf(__('Total: %s', 'aqualuxe'), function_exists('wc_price') ? wc_price($booking['total']) : '$' . number_format($booking['total'], 2)) . "\n\n";
        
        $message .= __('Customer Details:', 'aqualuxe') . "\n";
        $message .= sprintf(__('Name: %s', 'aqualuxe'), $booking['customer_name']) . "\n";
        $message .= sprintf(__('Email: %s', 'aqualuxe'), $booking['customer_email']) . "\n";
        $message .= sprintf(__('Phone: %s', 'aqualuxe'), $booking['customer_phone']) . "\n";
        
        $customer_notes = get_post_meta($booking['id'], '_customer_notes', true);
        
        if (!empty($customer_notes)) {
            $message .= "\n" . __('Customer Notes:', 'aqualuxe') . "\n";
            $message .= $customer_notes . "\n";
        }
        
        $message .= "\n" . sprintf(__('View Booking: %s', 'aqualuxe'), admin_url('post.php?post=' . $booking['id'] . '&action=edit')) . "\n";

        // Send email
        wp_mail($admin_email, $subject, $message);
    }

    /**
     * Send customer notification
     *
     * @param array $booking Booking data
     */
    private function send_customer_notification($booking) {
        if (empty($booking['customer_email'])) {
            return;
        }

        // Format date and time
        $date_format = get_option('date_format');
        $time_format = get_option('time_format');
        
        $booking_date = date_i18n($date_format, strtotime($booking['start_date']));
        $booking_time = date_i18n($time_format, strtotime($booking['start_date'])) . ' - ' . date_i18n($time_format, strtotime($booking['end_date']));

        // Build email content
        $subject = sprintf(__('Your Booking Confirmation: %s', 'aqualuxe'), $booking['booking_id']);
        
        $message = sprintf(__('Thank you for your booking with %s.', 'aqualuxe'), get_bloginfo('name')) . "\n\n";
        $message .= sprintf(__('Booking ID: %s', 'aqualuxe'), $booking['booking_id']) . "\n";
        $message .= sprintf(__('Service: %s', 'aqualuxe'), $booking['service_name']) . "\n";
        $message .= sprintf(__('Date: %s', 'aqualuxe'), $booking_date) . "\n";
        $message .= sprintf(__('Time: %s', 'aqualuxe'), $booking_time) . "\n";
        $message .= sprintf(__('Quantity: %s', 'aqualuxe'), $booking['quantity']) . "\n";
        $message .= sprintf(__('Total: %s', 'aqualuxe'), function_exists('wc_price') ? wc_price($booking['total']) : '$' . number_format($booking['total'], 2)) . "\n\n";
        
        // Add status-specific message
        if ('aqualuxe-pending' === $booking['status']) {
            $message .= __('Your booking is pending confirmation. We will contact you shortly to confirm your booking.', 'aqualuxe') . "\n\n";
        } elseif ('aqualuxe-confirmed' === $booking['status']) {
            $message .= __('Your booking has been confirmed. We look forward to seeing you!', 'aqualuxe') . "\n\n";
        }
        
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

    /**
     * Store form errors in session
     *
     * @param array $errors Error messages
     */
    private function store_errors($errors) {
        set_transient('aqualuxe_booking_form_errors_' . md5(wp_get_referer()), $errors, 60 * 10);
    }

    /**
     * Store form data in session
     *
     * @param array $data Form data
     */
    private function store_form_data($data) {
        set_transient('aqualuxe_booking_form_data_' . md5(wp_get_referer()), $data, 60 * 10);
    }

    /**
     * Get booking product ID
     *
     * @return int Product ID
     */
    private function get_booking_product_id() {
        // Check if booking product already exists
        $product_id = get_option('aqualuxe_bookings_product_id');
        
        if ($product_id && 'product' === get_post_type($product_id)) {
            return $product_id;
        }

        // Create booking product
        $product = new WC_Product_Simple();
        $product->set_name(__('Booking', 'aqualuxe'));
        $product->set_status('private');
        $product->set_catalog_visibility('hidden');
        $product->set_price(0);
        $product->set_regular_price(0);
        $product->set_sold_individually(true);
        $product->set_virtual(true);
        $product->save();

        // Save product ID
        update_option('aqualuxe_bookings_product_id', $product->get_id());

        return $product->get_id();
    }
}