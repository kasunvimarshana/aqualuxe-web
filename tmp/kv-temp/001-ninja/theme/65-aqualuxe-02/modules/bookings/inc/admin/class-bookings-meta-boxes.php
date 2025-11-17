<?php
/**
 * Bookings Meta Boxes
 *
 * Handles meta boxes for the bookings module.
 *
 * @package AquaLuxe
 * @subpackage Bookings
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Bookings Meta Boxes Class
 */
class AquaLuxe_Bookings_Meta_Boxes {
    /**
     * Constructor
     */
    public function __construct() {
        // Initialize hooks
        add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
        add_action('save_post', array($this, 'save_meta_boxes'), 10, 2);
        
        // Enqueue scripts
        add_action('admin_enqueue_scripts', array($this, 'admin_scripts'));
    }

    /**
     * Add meta boxes
     */
    public function add_meta_boxes() {
        // Add meta boxes for bookable_service post type
        add_meta_box(
            'aqualuxe_bookings_service_details',
            __('Service Details', 'aqualuxe'),
            array($this, 'render_service_details_meta_box'),
            'bookable_service',
            'normal',
            'high'
        );
        
        add_meta_box(
            'aqualuxe_bookings_service_availability',
            __('Service Availability', 'aqualuxe'),
            array($this, 'render_service_availability_meta_box'),
            'bookable_service',
            'normal',
            'high'
        );
        
        add_meta_box(
            'aqualuxe_bookings_service_pricing',
            __('Service Pricing', 'aqualuxe'),
            array($this, 'render_service_pricing_meta_box'),
            'bookable_service',
            'side',
            'default'
        );
        
        // Add meta boxes for booking post type
        add_meta_box(
            'aqualuxe_bookings_booking_details',
            __('Booking Details', 'aqualuxe'),
            array($this, 'render_booking_details_meta_box'),
            'booking',
            'normal',
            'high'
        );
        
        add_meta_box(
            'aqualuxe_bookings_customer_details',
            __('Customer Details', 'aqualuxe'),
            array($this, 'render_customer_details_meta_box'),
            'booking',
            'normal',
            'high'
        );
        
        add_meta_box(
            'aqualuxe_bookings_booking_actions',
            __('Booking Actions', 'aqualuxe'),
            array($this, 'render_booking_actions_meta_box'),
            'booking',
            'side',
            'default'
        );
    }

    /**
     * Enqueue admin scripts
     *
     * @param string $hook Current admin page
     */
    public function admin_scripts($hook) {
        global $post_type;
        
        // Only load on post edit screens for our post types
        if (!in_array($hook, array('post.php', 'post-new.php'))) {
            return;
        }
        
        if (!in_array($post_type, array('bookable_service', 'booking'))) {
            return;
        }
        
        // Enqueue jQuery UI
        wp_enqueue_script('jquery-ui-datepicker');
        wp_enqueue_script('jquery-ui-slider');
        wp_enqueue_style('jquery-ui', AQUALUXE_BOOKINGS_URL . 'assets/vendor/jquery-ui/jquery-ui.min.css', array(), '1.12.1');
        
        // Enqueue meta box scripts
        wp_enqueue_script(
            'aqualuxe-bookings-meta-boxes',
            AQUALUXE_BOOKINGS_URL . 'assets/js/admin-meta-boxes.js',
            array('jquery', 'jquery-ui-datepicker', 'jquery-ui-slider'),
            AQUALUXE_BOOKINGS_VERSION,
            true
        );
        
        // Localize script
        wp_localize_script('aqualuxe-bookings-meta-boxes', 'aqualuxe_bookings_meta_boxes', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('aqualuxe-bookings-meta-boxes'),
            'i18n' => array(
                'date_format' => get_option('date_format'),
                'time_format' => get_option('time_format'),
                'confirm_delete' => __('Are you sure you want to delete this item?', 'aqualuxe'),
                'add_rule' => __('Add Rule', 'aqualuxe'),
                'update_rule' => __('Update Rule', 'aqualuxe'),
                'delete_rule' => __('Delete Rule', 'aqualuxe'),
                'cancel' => __('Cancel', 'aqualuxe'),
                'select_date' => __('Select Date', 'aqualuxe'),
                'select_time' => __('Select Time', 'aqualuxe'),
                'select_service' => __('Select Service', 'aqualuxe'),
                'select_customer' => __('Select Customer', 'aqualuxe'),
                'select_status' => __('Select Status', 'aqualuxe'),
                'select_option' => __('Select an option', 'aqualuxe'),
                'monday' => __('Monday', 'aqualuxe'),
                'tuesday' => __('Tuesday', 'aqualuxe'),
                'wednesday' => __('Wednesday', 'aqualuxe'),
                'thursday' => __('Thursday', 'aqualuxe'),
                'friday' => __('Friday', 'aqualuxe'),
                'saturday' => __('Saturday', 'aqualuxe'),
                'sunday' => __('Sunday', 'aqualuxe'),
            ),
        ));
    }

    /**
     * Save meta boxes
     *
     * @param int $post_id Post ID
     * @param WP_Post $post Post object
     */
    public function save_meta_boxes($post_id, $post) {
        // Check if our nonce is set
        if (!isset($_POST['aqualuxe_bookings_meta_box_nonce'])) {
            return;
        }
        
        // Verify that the nonce is valid
        if (!wp_verify_nonce($_POST['aqualuxe_bookings_meta_box_nonce'], 'aqualuxe_bookings_save_meta_box')) {
            return;
        }
        
        // If this is an autosave, our form has not been submitted, so we don't want to do anything
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        
        // Check the user's permissions
        if ('bookable_service' === $post->post_type) {
            if (!current_user_can('edit_post', $post_id)) {
                return;
            }
            
            // Save service meta
            $this->save_service_meta($post_id);
        } elseif ('booking' === $post->post_type) {
            if (!current_user_can('edit_post', $post_id)) {
                return;
            }
            
            // Save booking meta
            $this->save_booking_meta($post_id);
        }
    }

    /**
     * Render service details meta box
     *
     * @param WP_Post $post Post object
     */
    public function render_service_details_meta_box($post) {
        // Add a nonce field
        wp_nonce_field('aqualuxe_bookings_save_meta_box', 'aqualuxe_bookings_meta_box_nonce');
        
        // Get meta values
        $duration = get_post_meta($post->ID, '_service_duration', true);
        $capacity = get_post_meta($post->ID, '_service_capacity', true);
        $buffer_time = get_post_meta($post->ID, '_service_buffer_time', true);
        $min_duration = get_post_meta($post->ID, '_service_min_duration', true);
        $max_duration = get_post_meta($post->ID, '_service_max_duration', true);
        $allow_multiple = get_post_meta($post->ID, '_service_allow_multiple', true);
        $max_bookings = get_post_meta($post->ID, '_service_max_bookings', true);
        $description = get_post_meta($post->ID, '_service_description', true);
        
        // Set default values
        $duration = !empty($duration) ? $duration : 60;
        $capacity = !empty($capacity) ? $capacity : 1;
        $buffer_time = !empty($buffer_time) ? $buffer_time : get_option('aqualuxe_bookings_buffer_time', 30);
        $min_duration = !empty($min_duration) ? $min_duration : get_option('aqualuxe_bookings_min_booking_time', 60);
        $max_duration = !empty($max_duration) ? $max_duration : get_option('aqualuxe_bookings_max_booking_time', 480);
        $allow_multiple = !empty($allow_multiple) ? $allow_multiple : 'no';
        $max_bookings = !empty($max_bookings) ? $max_bookings : 0;
        
        // Include template
        include AQUALUXE_BOOKINGS_PATH . 'templates/admin/meta-boxes/service-details.php';
    }

    /**
     * Render service availability meta box
     *
     * @param WP_Post $post Post object
     */
    public function render_service_availability_meta_box($post) {
        // Get meta values
        $availability = get_post_meta($post->ID, '_service_availability', true);
        
        if (empty($availability) || !is_array($availability)) {
            $availability = array();
        }
        
        // Get business hours
        $business_hours = get_option('aqualuxe_bookings_business_hours', array(
            0 => array('open' => '', 'close' => ''), // Sunday (closed)
            1 => array('open' => '09:00', 'close' => '17:00'), // Monday
            2 => array('open' => '09:00', 'close' => '17:00'), // Tuesday
            3 => array('open' => '09:00', 'close' => '17:00'), // Wednesday
            4 => array('open' => '09:00', 'close' => '17:00'), // Thursday
            5 => array('open' => '09:00', 'close' => '17:00'), // Friday
            6 => array('open' => '', 'close' => ''), // Saturday (closed)
        ));
        
        // Include template
        include AQUALUXE_BOOKINGS_PATH . 'templates/admin/meta-boxes/service-availability.php';
    }

    /**
     * Render service pricing meta box
     *
     * @param WP_Post $post Post object
     */
    public function render_service_pricing_meta_box($post) {
        // Get meta values
        $price = get_post_meta($post->ID, '_service_price', true);
        $sale_price = get_post_meta($post->ID, '_service_sale_price', true);
        $product_id = get_post_meta($post->ID, '_product_id', true);
        
        // Set default values
        $price = !empty($price) ? $price : '';
        $sale_price = !empty($sale_price) ? $sale_price : '';
        $product_id = !empty($product_id) ? $product_id : '';
        
        // Include template
        include AQUALUXE_BOOKINGS_PATH . 'templates/admin/meta-boxes/service-pricing.php';
    }

    /**
     * Render booking details meta box
     *
     * @param WP_Post $post Post object
     */
    public function render_booking_details_meta_box($post) {
        // Add a nonce field
        wp_nonce_field('aqualuxe_bookings_save_meta_box', 'aqualuxe_bookings_meta_box_nonce');
        
        // Get meta values
        $booking_id = get_post_meta($post->ID, '_booking_id', true);
        $service_id = get_post_meta($post->ID, '_service_id', true);
        $start_date = get_post_meta($post->ID, '_booking_start', true);
        $end_date = get_post_meta($post->ID, '_booking_end', true);
        $all_day = get_post_meta($post->ID, '_booking_all_day', true);
        $quantity = get_post_meta($post->ID, '_booking_quantity', true);
        $total = get_post_meta($post->ID, '_booking_total', true);
        $order_id = get_post_meta($post->ID, '_order_id', true);
        $notes = get_post_meta($post->ID, '_booking_notes', true);
        
        // Set default values
        $booking_id = !empty($booking_id) ? $booking_id : '';
        $service_id = !empty($service_id) ? $service_id : 0;
        $start_date = !empty($start_date) ? $start_date : '';
        $end_date = !empty($end_date) ? $end_date : '';
        $all_day = !empty($all_day) ? $all_day : false;
        $quantity = !empty($quantity) ? $quantity : 1;
        $total = !empty($total) ? $total : 0;
        $order_id = !empty($order_id) ? $order_id : 0;
        
        // Format dates
        $formatted_start_date = !empty($start_date) ? date_i18n(get_option('date_format'), strtotime($start_date)) : '';
        $formatted_start_time = !empty($start_date) ? date_i18n(get_option('time_format'), strtotime($start_date)) : '';
        $formatted_end_date = !empty($end_date) ? date_i18n(get_option('date_format'), strtotime($end_date)) : '';
        $formatted_end_time = !empty($end_date) ? date_i18n(get_option('time_format'), strtotime($end_date)) : '';
        
        // Get services
        $services = get_posts(array(
            'post_type' => 'bookable_service',
            'posts_per_page' => -1,
            'orderby' => 'title',
            'order' => 'ASC',
        ));
        
        // Include template
        include AQUALUXE_BOOKINGS_PATH . 'templates/admin/meta-boxes/booking-details.php';
    }

    /**
     * Render customer details meta box
     *
     * @param WP_Post $post Post object
     */
    public function render_customer_details_meta_box($post) {
        // Get meta values
        $customer_id = get_post_meta($post->ID, '_customer_id', true);
        $customer_name = get_post_meta($post->ID, '_customer_name', true);
        $customer_email = get_post_meta($post->ID, '_customer_email', true);
        $customer_phone = get_post_meta($post->ID, '_customer_phone', true);
        $customer_notes = get_post_meta($post->ID, '_customer_notes', true);
        
        // Set default values
        $customer_id = !empty($customer_id) ? $customer_id : 0;
        $customer_name = !empty($customer_name) ? $customer_name : '';
        $customer_email = !empty($customer_email) ? $customer_email : '';
        $customer_phone = !empty($customer_phone) ? $customer_phone : '';
        $customer_notes = !empty($customer_notes) ? $customer_notes : '';
        
        // Get customer data
        $customer = null;
        
        if ($customer_id > 0) {
            $customer = get_user_by('id', $customer_id);
        }
        
        // Include template
        include AQUALUXE_BOOKINGS_PATH . 'templates/admin/meta-boxes/customer-details.php';
    }

    /**
     * Render booking actions meta box
     *
     * @param WP_Post $post Post object
     */
    public function render_booking_actions_meta_box($post) {
        // Get booking status
        $status = $post->post_status;
        
        // Include template
        include AQUALUXE_BOOKINGS_PATH . 'templates/admin/meta-boxes/booking-actions.php';
    }

    /**
     * Save service meta
     *
     * @param int $post_id Post ID
     */
    public function save_service_meta($post_id) {
        // Service details
        if (isset($_POST['_service_duration'])) {
            update_post_meta($post_id, '_service_duration', absint($_POST['_service_duration']));
        }
        
        if (isset($_POST['_service_capacity'])) {
            update_post_meta($post_id, '_service_capacity', absint($_POST['_service_capacity']));
        }
        
        if (isset($_POST['_service_buffer_time'])) {
            update_post_meta($post_id, '_service_buffer_time', absint($_POST['_service_buffer_time']));
        }
        
        if (isset($_POST['_service_min_duration'])) {
            update_post_meta($post_id, '_service_min_duration', absint($_POST['_service_min_duration']));
        }
        
        if (isset($_POST['_service_max_duration'])) {
            update_post_meta($post_id, '_service_max_duration', absint($_POST['_service_max_duration']));
        }
        
        if (isset($_POST['_service_allow_multiple'])) {
            update_post_meta($post_id, '_service_allow_multiple', sanitize_text_field($_POST['_service_allow_multiple']));
        } else {
            update_post_meta($post_id, '_service_allow_multiple', 'no');
        }
        
        if (isset($_POST['_service_max_bookings'])) {
            update_post_meta($post_id, '_service_max_bookings', absint($_POST['_service_max_bookings']));
        }
        
        if (isset($_POST['_service_description'])) {
            update_post_meta($post_id, '_service_description', sanitize_textarea_field($_POST['_service_description']));
        }
        
        // Service availability
        if (isset($_POST['_service_availability']) && is_array($_POST['_service_availability'])) {
            $availability = array();
            
            foreach ($_POST['_service_availability'] as $rule) {
                if (empty($rule['type']) || empty($rule['from_date'])) {
                    continue;
                }
                
                $availability[] = array(
                    'type' => sanitize_text_field($rule['type']),
                    'from_date' => sanitize_text_field($rule['from_date']),
                    'to_date' => !empty($rule['to_date']) ? sanitize_text_field($rule['to_date']) : '',
                    'from_time' => !empty($rule['from_time']) ? sanitize_text_field($rule['from_time']) : '',
                    'to_time' => !empty($rule['to_time']) ? sanitize_text_field($rule['to_time']) : '',
                    'days' => !empty($rule['days']) && is_array($rule['days']) ? array_map('sanitize_text_field', $rule['days']) : array(),
                );
            }
            
            update_post_meta($post_id, '_service_availability', $availability);
        }
        
        // Service pricing
        if (isset($_POST['_service_price'])) {
            update_post_meta($post_id, '_service_price', wc_format_decimal($_POST['_service_price']));
        }
        
        if (isset($_POST['_service_sale_price'])) {
            update_post_meta($post_id, '_service_sale_price', wc_format_decimal($_POST['_service_sale_price']));
        }
        
        if (isset($_POST['_product_id'])) {
            update_post_meta($post_id, '_product_id', absint($_POST['_product_id']));
        }
    }

    /**
     * Save booking meta
     *
     * @param int $post_id Post ID
     */
    public function save_booking_meta($post_id) {
        // Booking details
        if (isset($_POST['_booking_id'])) {
            update_post_meta($post_id, '_booking_id', sanitize_text_field($_POST['_booking_id']));
        }
        
        if (isset($_POST['_service_id'])) {
            update_post_meta($post_id, '_service_id', absint($_POST['_service_id']));
        }
        
        if (isset($_POST['_booking_start_date']) && isset($_POST['_booking_start_time'])) {
            $start_date = sanitize_text_field($_POST['_booking_start_date']);
            $start_time = sanitize_text_field($_POST['_booking_start_time']);
            $start_datetime = date('Y-m-d H:i:s', strtotime($start_date . ' ' . $start_time));
            
            update_post_meta($post_id, '_booking_start', $start_datetime);
        }
        
        if (isset($_POST['_booking_end_date']) && isset($_POST['_booking_end_time'])) {
            $end_date = sanitize_text_field($_POST['_booking_end_date']);
            $end_time = sanitize_text_field($_POST['_booking_end_time']);
            $end_datetime = date('Y-m-d H:i:s', strtotime($end_date . ' ' . $end_time));
            
            update_post_meta($post_id, '_booking_end', $end_datetime);
        }
        
        if (isset($_POST['_booking_all_day'])) {
            update_post_meta($post_id, '_booking_all_day', true);
        } else {
            update_post_meta($post_id, '_booking_all_day', false);
        }
        
        if (isset($_POST['_booking_quantity'])) {
            update_post_meta($post_id, '_booking_quantity', absint($_POST['_booking_quantity']));
        }
        
        if (isset($_POST['_booking_total'])) {
            update_post_meta($post_id, '_booking_total', wc_format_decimal($_POST['_booking_total']));
        }
        
        if (isset($_POST['_order_id'])) {
            update_post_meta($post_id, '_order_id', absint($_POST['_order_id']));
        }
        
        if (isset($_POST['_booking_notes'])) {
            update_post_meta($post_id, '_booking_notes', sanitize_textarea_field($_POST['_booking_notes']));
        }
        
        // Customer details
        if (isset($_POST['_customer_id'])) {
            update_post_meta($post_id, '_customer_id', absint($_POST['_customer_id']));
        }
        
        if (isset($_POST['_customer_name'])) {
            update_post_meta($post_id, '_customer_name', sanitize_text_field($_POST['_customer_name']));
        }
        
        if (isset($_POST['_customer_email'])) {
            update_post_meta($post_id, '_customer_email', sanitize_email($_POST['_customer_email']));
        }
        
        if (isset($_POST['_customer_phone'])) {
            update_post_meta($post_id, '_customer_phone', sanitize_text_field($_POST['_customer_phone']));
        }
        
        if (isset($_POST['_customer_notes'])) {
            update_post_meta($post_id, '_customer_notes', sanitize_textarea_field($_POST['_customer_notes']));
        }
    }
}