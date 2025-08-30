<?php
/**
 * Bookings Module
 *
 * @package AquaLuxe
 * @subpackage Modules
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * AquaLuxe Bookings Module Class
 */
class AquaLuxe_Bookings_Module extends AquaLuxe_Module {
    /**
     * Module ID
     *
     * @var string
     */
    protected $id = 'bookings';

    /**
     * Module name
     *
     * @var string
     */
    protected $name = 'Bookings';

    /**
     * Module description
     *
     * @var string
     */
    protected $description = 'Adds booking functionality with calendar integration and management.';

    /**
     * Module version
     *
     * @var string
     */
    protected $version = '1.0.0';

    /**
     * Module dependencies
     *
     * @var array
     */
    protected $dependencies = ['woocommerce'];

    /**
     * Initialize module
     */
    public function init() {
        // Call parent init
        parent::init();
        
        // Register post type
        add_action('init', [$this, 'register_post_types']);
        
        // Register taxonomies
        add_action('init', [$this, 'register_taxonomies']);
        
        // Add shortcodes
        add_shortcode('aqualuxe_booking_form', [$this, 'booking_form_shortcode']);
        add_shortcode('aqualuxe_booking_calendar', [$this, 'booking_calendar_shortcode']);
        
        // Add AJAX handlers
        add_action('wp_ajax_aqualuxe_check_availability', [$this, 'ajax_check_availability']);
        add_action('wp_ajax_nopriv_aqualuxe_check_availability', [$this, 'ajax_check_availability']);
        add_action('wp_ajax_aqualuxe_create_booking', [$this, 'ajax_create_booking']);
        add_action('wp_ajax_nopriv_aqualuxe_create_booking', [$this, 'ajax_create_booking']);
        
        // Add admin menu
        add_action('admin_menu', [$this, 'add_admin_menu']);
        
        // Add meta boxes
        add_action('add_meta_boxes', [$this, 'add_meta_boxes']);
        
        // Save post meta
        add_action('save_post', [$this, 'save_meta_box_data']);
        
        // Add customizer settings
        add_action('customize_register', [$this, 'register_customizer_settings']);
        
        // Add booking data to WooCommerce orders
        add_action('woocommerce_checkout_create_order_line_item', [$this, 'add_booking_data_to_order_item'], 10, 4);
        
        // Process booking after order completion
        add_action('woocommerce_order_status_completed', [$this, 'process_booking_from_order']);
        
        // Add booking details to order emails
        add_action('woocommerce_email_after_order_table', [$this, 'add_booking_details_to_emails'], 10, 4);
    }

    /**
     * Register assets
     */
    public function register_assets() {
        // Register booking script
        wp_register_script(
            'aqualuxe-bookings',
            $this->uri . 'assets/js/bookings.js',
            ['jquery', 'jquery-ui-datepicker', 'wp-util'],
            $this->version,
            true
        );
        
        // Localize booking script
        wp_localize_script('aqualuxe-bookings', 'aqualuxeBookings', [
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('aqualuxe-bookings-nonce'),
            'i18n' => [
                'selectDate' => __('Please select a date', 'aqualuxe'),
                'selectTime' => __('Please select a time', 'aqualuxe'),
                'selectService' => __('Please select a service', 'aqualuxe'),
                'bookingSuccess' => __('Your booking has been created successfully!', 'aqualuxe'),
                'bookingError' => __('There was an error creating your booking. Please try again.', 'aqualuxe'),
                'dateFormat' => get_option('date_format'),
                'timeFormat' => get_option('time_format'),
            ],
        ]);
        
        // Enqueue booking script
        wp_enqueue_script('aqualuxe-bookings');
        
        // Register booking styles
        wp_register_style(
            'aqualuxe-bookings',
            $this->uri . 'assets/css/bookings.css',
            [],
            $this->version
        );
        
        // Enqueue booking styles
        wp_enqueue_style('aqualuxe-bookings');
        
        // Admin scripts
        if (is_admin()) {
            wp_register_script(
                'aqualuxe-bookings-admin',
                $this->uri . 'assets/js/bookings-admin.js',
                ['jquery', 'jquery-ui-datepicker', 'wp-util'],
                $this->version,
                true
            );
            
            wp_localize_script('aqualuxe-bookings-admin', 'aqualuxeBookingsAdmin', [
                'ajaxUrl' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('aqualuxe-bookings-admin-nonce'),
            ]);
            
            wp_enqueue_script('aqualuxe-bookings-admin');
            
            wp_register_style(
                'aqualuxe-bookings-admin',
                $this->uri . 'assets/css/bookings-admin.css',
                [],
                $this->version
            );
            
            wp_enqueue_style('aqualuxe-bookings-admin');
        }
    }

    /**
     * Register post types
     */
    public function register_post_types() {
        // Register booking post type
        register_post_type('aqualuxe_booking', [
            'labels' => [
                'name' => __('Bookings', 'aqualuxe'),
                'singular_name' => __('Booking', 'aqualuxe'),
                'add_new' => __('Add New', 'aqualuxe'),
                'add_new_item' => __('Add New Booking', 'aqualuxe'),
                'edit_item' => __('Edit Booking', 'aqualuxe'),
                'new_item' => __('New Booking', 'aqualuxe'),
                'view_item' => __('View Booking', 'aqualuxe'),
                'search_items' => __('Search Bookings', 'aqualuxe'),
                'not_found' => __('No bookings found', 'aqualuxe'),
                'not_found_in_trash' => __('No bookings found in trash', 'aqualuxe'),
                'all_items' => __('All Bookings', 'aqualuxe'),
                'menu_name' => __('Bookings', 'aqualuxe'),
            ],
            'public' => false,
            'show_ui' => true,
            'show_in_menu' => true,
            'menu_icon' => 'dashicons-calendar-alt',
            'capability_type' => 'post',
            'supports' => ['title', 'author'],
            'has_archive' => false,
            'rewrite' => false,
            'query_var' => false,
        ]);
        
        // Register bookable service post type
        register_post_type('aqualuxe_service', [
            'labels' => [
                'name' => __('Bookable Services', 'aqualuxe'),
                'singular_name' => __('Bookable Service', 'aqualuxe'),
                'add_new' => __('Add New', 'aqualuxe'),
                'add_new_item' => __('Add New Service', 'aqualuxe'),
                'edit_item' => __('Edit Service', 'aqualuxe'),
                'new_item' => __('New Service', 'aqualuxe'),
                'view_item' => __('View Service', 'aqualuxe'),
                'search_items' => __('Search Services', 'aqualuxe'),
                'not_found' => __('No services found', 'aqualuxe'),
                'not_found_in_trash' => __('No services found in trash', 'aqualuxe'),
                'all_items' => __('All Services', 'aqualuxe'),
                'menu_name' => __('Services', 'aqualuxe'),
            ],
            'public' => true,
            'show_ui' => true,
            'show_in_menu' => 'edit.php?post_type=aqualuxe_booking',
            'menu_position' => 5,
            'capability_type' => 'post',
            'supports' => ['title', 'editor', 'thumbnail', 'excerpt'],
            'has_archive' => true,
            'rewrite' => [
                'slug' => 'services',
                'with_front' => false,
            ],
            'show_in_rest' => true,
        ]);
    }

    /**
     * Register taxonomies
     */
    public function register_taxonomies() {
        // Register service category taxonomy
        register_taxonomy('aqualuxe_service_cat', ['aqualuxe_service'], [
            'labels' => [
                'name' => __('Service Categories', 'aqualuxe'),
                'singular_name' => __('Service Category', 'aqualuxe'),
                'search_items' => __('Search Service Categories', 'aqualuxe'),
                'all_items' => __('All Service Categories', 'aqualuxe'),
                'parent_item' => __('Parent Service Category', 'aqualuxe'),
                'parent_item_colon' => __('Parent Service Category:', 'aqualuxe'),
                'edit_item' => __('Edit Service Category', 'aqualuxe'),
                'update_item' => __('Update Service Category', 'aqualuxe'),
                'add_new_item' => __('Add New Service Category', 'aqualuxe'),
                'new_item_name' => __('New Service Category Name', 'aqualuxe'),
                'menu_name' => __('Categories', 'aqualuxe'),
            ],
            'hierarchical' => true,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => [
                'slug' => 'service-category',
                'with_front' => false,
            ],
            'show_in_rest' => true,
        ]);
        
        // Register booking status taxonomy
        register_taxonomy('aqualuxe_booking_status', ['aqualuxe_booking'], [
            'labels' => [
                'name' => __('Booking Statuses', 'aqualuxe'),
                'singular_name' => __('Booking Status', 'aqualuxe'),
                'search_items' => __('Search Booking Statuses', 'aqualuxe'),
                'all_items' => __('All Booking Statuses', 'aqualuxe'),
                'edit_item' => __('Edit Booking Status', 'aqualuxe'),
                'update_item' => __('Update Booking Status', 'aqualuxe'),
                'add_new_item' => __('Add New Booking Status', 'aqualuxe'),
                'new_item_name' => __('New Booking Status Name', 'aqualuxe'),
                'menu_name' => __('Statuses', 'aqualuxe'),
            ],
            'hierarchical' => true,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => false,
            'show_in_rest' => true,
        ]);
        
        // Add default booking statuses
        $statuses = [
            'pending' => __('Pending', 'aqualuxe'),
            'confirmed' => __('Confirmed', 'aqualuxe'),
            'completed' => __('Completed', 'aqualuxe'),
            'cancelled' => __('Cancelled', 'aqualuxe'),
        ];
        
        foreach ($statuses as $slug => $name) {
            if (!term_exists($slug, 'aqualuxe_booking_status')) {
                wp_insert_term($name, 'aqualuxe_booking_status', [
                    'slug' => $slug,
                ]);
            }
        }
    }

    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        // Add calendar page
        add_submenu_page(
            'edit.php?post_type=aqualuxe_booking',
            __('Booking Calendar', 'aqualuxe'),
            __('Calendar', 'aqualuxe'),
            'edit_posts',
            'aqualuxe-booking-calendar',
            [$this, 'render_calendar_page']
        );
        
        // Add settings page
        add_submenu_page(
            'edit.php?post_type=aqualuxe_booking',
            __('Booking Settings', 'aqualuxe'),
            __('Settings', 'aqualuxe'),
            'manage_options',
            'aqualuxe-booking-settings',
            [$this, 'render_settings_page']
        );
    }

    /**
     * Render calendar page
     */
    public function render_calendar_page() {
        // Get current date
        $current_date = current_time('Y-m-d');
        
        // Get month and year
        $month = isset($_GET['month']) ? intval($_GET['month']) : date('n', strtotime($current_date));
        $year = isset($_GET['year']) ? intval($_GET['year']) : date('Y', strtotime($current_date));
        
        // Get bookings for the month
        $bookings = $this->get_bookings_for_month($month, $year);
        
        // Include calendar template
        include $this->path . 'templates/admin/calendar.php';
    }

    /**
     * Render settings page
     */
    public function render_settings_page() {
        // Get settings
        $settings = $this->get_settings();
        
        // Include settings template
        include $this->path . 'templates/admin/settings.php';
    }

    /**
     * Add meta boxes
     */
    public function add_meta_boxes() {
        // Add booking details meta box
        add_meta_box(
            'aqualuxe_booking_details',
            __('Booking Details', 'aqualuxe'),
            [$this, 'render_booking_details_meta_box'],
            'aqualuxe_booking',
            'normal',
            'high'
        );
        
        // Add service details meta box
        add_meta_box(
            'aqualuxe_service_details',
            __('Service Details', 'aqualuxe'),
            [$this, 'render_service_details_meta_box'],
            'aqualuxe_service',
            'normal',
            'high'
        );
        
        // Add service availability meta box
        add_meta_box(
            'aqualuxe_service_availability',
            __('Service Availability', 'aqualuxe'),
            [$this, 'render_service_availability_meta_box'],
            'aqualuxe_service',
            'normal',
            'high'
        );
    }

    /**
     * Render booking details meta box
     *
     * @param WP_Post $post Post object
     */
    public function render_booking_details_meta_box($post) {
        // Add nonce for security
        wp_nonce_field('aqualuxe_booking_details', 'aqualuxe_booking_details_nonce');
        
        // Get booking data
        $booking_data = $this->get_booking_data($post->ID);
        
        // Include meta box template
        include $this->path . 'templates/admin/booking-details.php';
    }

    /**
     * Render service details meta box
     *
     * @param WP_Post $post Post object
     */
    public function render_service_details_meta_box($post) {
        // Add nonce for security
        wp_nonce_field('aqualuxe_service_details', 'aqualuxe_service_details_nonce');
        
        // Get service data
        $service_data = $this->get_service_data($post->ID);
        
        // Include meta box template
        include $this->path . 'templates/admin/service-details.php';
    }

    /**
     * Render service availability meta box
     *
     * @param WP_Post $post Post object
     */
    public function render_service_availability_meta_box($post) {
        // Add nonce for security
        wp_nonce_field('aqualuxe_service_availability', 'aqualuxe_service_availability_nonce');
        
        // Get service availability
        $availability = $this->get_service_availability($post->ID);
        
        // Include meta box template
        include $this->path . 'templates/admin/service-availability.php';
    }

    /**
     * Save meta box data
     *
     * @param int $post_id Post ID
     */
    public function save_meta_box_data($post_id) {
        // Check if this is an autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        
        // Check post type
        if (get_post_type($post_id) === 'aqualuxe_booking') {
            // Check nonce
            if (!isset($_POST['aqualuxe_booking_details_nonce']) || !wp_verify_nonce($_POST['aqualuxe_booking_details_nonce'], 'aqualuxe_booking_details')) {
                return;
            }
            
            // Check permissions
            if (!current_user_can('edit_post', $post_id)) {
                return;
            }
            
            // Save booking data
            $this->save_booking_data($post_id, $_POST);
        } elseif (get_post_type($post_id) === 'aqualuxe_service') {
            // Check service details nonce
            if (isset($_POST['aqualuxe_service_details_nonce']) && wp_verify_nonce($_POST['aqualuxe_service_details_nonce'], 'aqualuxe_service_details')) {
                // Check permissions
                if (current_user_can('edit_post', $post_id)) {
                    // Save service data
                    $this->save_service_data($post_id, $_POST);
                }
            }
            
            // Check service availability nonce
            if (isset($_POST['aqualuxe_service_availability_nonce']) && wp_verify_nonce($_POST['aqualuxe_service_availability_nonce'], 'aqualuxe_service_availability')) {
                // Check permissions
                if (current_user_can('edit_post', $post_id)) {
                    // Save service availability
                    $this->save_service_availability($post_id, $_POST);
                }
            }
        }
    }

    /**
     * Register customizer settings
     *
     * @param WP_Customize_Manager $wp_customize Customizer manager
     */
    public function register_customizer_settings($wp_customize) {
        // Add bookings section
        $wp_customize->add_section('aqualuxe_bookings', [
            'title'    => __('Bookings', 'aqualuxe'),
            'priority' => 30,
        ]);
        
        // Add booking page setting
        $wp_customize->add_setting('aqualuxe_booking_page', [
            'default'           => 0,
            'sanitize_callback' => 'absint',
            'transport'         => 'refresh',
        ]);
        
        // Add booking page control
        $wp_customize->add_control('aqualuxe_booking_page', [
            'label'    => __('Booking Page', 'aqualuxe'),
            'section'  => 'aqualuxe_bookings',
            'type'     => 'dropdown-pages',
            'priority' => 10,
        ]);
        
        // Add booking confirmation page setting
        $wp_customize->add_setting('aqualuxe_booking_confirmation_page', [
            'default'           => 0,
            'sanitize_callback' => 'absint',
            'transport'         => 'refresh',
        ]);
        
        // Add booking confirmation page control
        $wp_customize->add_control('aqualuxe_booking_confirmation_page', [
            'label'    => __('Booking Confirmation Page', 'aqualuxe'),
            'section'  => 'aqualuxe_bookings',
            'type'     => 'dropdown-pages',
            'priority' => 20,
        ]);
        
        // Add booking form style setting
        $wp_customize->add_setting('aqualuxe_booking_form_style', [
            'default'           => 'standard',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'refresh',
        ]);
        
        // Add booking form style control
        $wp_customize->add_control('aqualuxe_booking_form_style', [
            'label'    => __('Booking Form Style', 'aqualuxe'),
            'section'  => 'aqualuxe_bookings',
            'type'     => 'select',
            'choices'  => [
                'standard' => __('Standard', 'aqualuxe'),
                'compact'  => __('Compact', 'aqualuxe'),
                'stepped'  => __('Stepped', 'aqualuxe'),
            ],
            'priority' => 30,
        ]);
        
        // Add calendar style setting
        $wp_customize->add_setting('aqualuxe_booking_calendar_style', [
            'default'           => 'standard',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'refresh',
        ]);
        
        // Add calendar style control
        $wp_customize->add_control('aqualuxe_booking_calendar_style', [
            'label'    => __('Calendar Style', 'aqualuxe'),
            'section'  => 'aqualuxe_bookings',
            'type'     => 'select',
            'choices'  => [
                'standard' => __('Standard', 'aqualuxe'),
                'modern'   => __('Modern', 'aqualuxe'),
                'minimal'  => __('Minimal', 'aqualuxe'),
            ],
            'priority' => 40,
        ]);
    }

    /**
     * Booking form shortcode
     *
     * @param array $atts Shortcode attributes
     * @return string Shortcode output
     */
    public function booking_form_shortcode($atts) {
        // Parse attributes
        $atts = shortcode_atts([
            'service_id' => 0,
            'category' => '',
            'style' => get_theme_mod('aqualuxe_booking_form_style', 'standard'),
        ], $atts);
        
        // Start output buffer
        ob_start();
        
        // Get services
        $services = $this->get_services($atts['service_id'], $atts['category']);
        
        // Include booking form template
        include $this->path . 'templates/booking-form.php';
        
        // Return output
        return ob_get_clean();
    }

    /**
     * Booking calendar shortcode
     *
     * @param array $atts Shortcode attributes
     * @return string Shortcode output
     */
    public function booking_calendar_shortcode($atts) {
        // Parse attributes
        $atts = shortcode_atts([
            'service_id' => 0,
            'category' => '',
            'style' => get_theme_mod('aqualuxe_booking_calendar_style', 'standard'),
        ], $atts);
        
        // Start output buffer
        ob_start();
        
        // Get services
        $services = $this->get_services($atts['service_id'], $atts['category']);
        
        // Get current date
        $current_date = current_time('Y-m-d');
        
        // Get month and year
        $month = isset($_GET['month']) ? intval($_GET['month']) : date('n', strtotime($current_date));
        $year = isset($_GET['year']) ? intval($_GET['year']) : date('Y', strtotime($current_date));
        
        // Include booking calendar template
        include $this->path . 'templates/booking-calendar.php';
        
        // Return output
        return ob_get_clean();
    }

    /**
     * AJAX check availability
     */
    public function ajax_check_availability() {
        // Check nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'aqualuxe-bookings-nonce')) {
            wp_send_json_error(['message' => __('Invalid security token.', 'aqualuxe')]);
        }
        
        // Get service ID
        $service_id = isset($_POST['service_id']) ? intval($_POST['service_id']) : 0;
        
        // Get date
        $date = isset($_POST['date']) ? sanitize_text_field($_POST['date']) : '';
        
        // Check if service exists
        if (!$service_id || get_post_type($service_id) !== 'aqualuxe_service') {
            wp_send_json_error(['message' => __('Invalid service.', 'aqualuxe')]);
        }
        
        // Check if date is valid
        if (!$date || !strtotime($date)) {
            wp_send_json_error(['message' => __('Invalid date.', 'aqualuxe')]);
        }
        
        // Get available time slots
        $time_slots = $this->get_available_time_slots($service_id, $date);
        
        // Send response
        wp_send_json_success([
            'time_slots' => $time_slots,
        ]);
    }

    /**
     * AJAX create booking
     */
    public function ajax_create_booking() {
        // Check nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'aqualuxe-bookings-nonce')) {
            wp_send_json_error(['message' => __('Invalid security token.', 'aqualuxe')]);
        }
        
        // Get service ID
        $service_id = isset($_POST['service_id']) ? intval($_POST['service_id']) : 0;
        
        // Get date
        $date = isset($_POST['date']) ? sanitize_text_field($_POST['date']) : '';
        
        // Get time
        $time = isset($_POST['time']) ? sanitize_text_field($_POST['time']) : '';
        
        // Get customer data
        $customer_data = isset($_POST['customer']) ? $_POST['customer'] : [];
        
        // Check if service exists
        if (!$service_id || get_post_type($service_id) !== 'aqualuxe_service') {
            wp_send_json_error(['message' => __('Invalid service.', 'aqualuxe')]);
        }
        
        // Check if date is valid
        if (!$date || !strtotime($date)) {
            wp_send_json_error(['message' => __('Invalid date.', 'aqualuxe')]);
        }
        
        // Check if time is valid
        if (!$time) {
            wp_send_json_error(['message' => __('Invalid time.', 'aqualuxe')]);
        }
        
        // Check if time slot is available
        if (!$this->is_time_slot_available($service_id, $date, $time)) {
            wp_send_json_error(['message' => __('This time slot is no longer available.', 'aqualuxe')]);
        }
        
        // Create booking
        $booking_id = $this->create_booking($service_id, $date, $time, $customer_data);
        
        // Check if booking was created
        if (!$booking_id) {
            wp_send_json_error(['message' => __('There was an error creating your booking. Please try again.', 'aqualuxe')]);
        }
        
        // Get booking data
        $booking_data = $this->get_booking_data($booking_id);
        
        // Send response
        wp_send_json_success([
            'booking_id' => $booking_id,
            'booking_data' => $booking_data,
            'redirect_url' => $this->get_booking_confirmation_url($booking_id),
        ]);
    }

    /**
     * Add booking data to order item
     *
     * @param WC_Order_Item_Product $item Order item
     * @param string $cart_item_key Cart item key
     * @param array $values Cart item values
     * @param WC_Order $order Order object
     */
    public function add_booking_data_to_order_item($item, $cart_item_key, $values, $order) {
        // Check if item has booking data
        if (isset($values['aqualuxe_booking_data'])) {
            // Add booking data to order item
            $item->add_meta_data('_aqualuxe_booking_data', $values['aqualuxe_booking_data']);
        }
    }

    /**
     * Process booking from order
     *
     * @param int $order_id Order ID
     */
    public function process_booking_from_order($order_id) {
        // Get order
        $order = wc_get_order($order_id);
        
        // Check if order exists
        if (!$order) {
            return;
        }
        
        // Loop through order items
        foreach ($order->get_items() as $item) {
            // Get booking data
            $booking_data = $item->get_meta('_aqualuxe_booking_data');
            
            // Check if item has booking data
            if ($booking_data) {
                // Create booking
                $booking_id = $this->create_booking(
                    $booking_data['service_id'],
                    $booking_data['date'],
                    $booking_data['time'],
                    [
                        'name' => $order->get_billing_first_name() . ' ' . $order->get_billing_last_name(),
                        'email' => $order->get_billing_email(),
                        'phone' => $order->get_billing_phone(),
                    ],
                    $order_id
                );
                
                // Add booking ID to order item
                if ($booking_id) {
                    $item->add_meta_data('_aqualuxe_booking_id', $booking_id);
                    $item->save();
                }
            }
        }
    }

    /**
     * Add booking details to order emails
     *
     * @param WC_Order $order Order object
     * @param bool $sent_to_admin Whether email is sent to admin
     * @param bool $plain_text Whether email is plain text
     * @param WC_Email $email Email object
     */
    public function add_booking_details_to_emails($order, $sent_to_admin, $plain_text, $email) {
        // Loop through order items
        foreach ($order->get_items() as $item) {
            // Get booking data
            $booking_data = $item->get_meta('_aqualuxe_booking_data');
            
            // Check if item has booking data
            if ($booking_data) {
                // Get booking ID
                $booking_id = $item->get_meta('_aqualuxe_booking_id');
                
                // Include booking details template
                if ($plain_text) {
                    include $this->path . 'templates/emails/plain/booking-details.php';
                } else {
                    include $this->path . 'templates/emails/booking-details.php';
                }
            }
        }
    }

    /**
     * Get booking data
     *
     * @param int $booking_id Booking ID
     * @return array Booking data
     */
    public function get_booking_data($booking_id) {
        // Get booking post
        $booking = get_post($booking_id);
        
        // Check if booking exists
        if (!$booking || $booking->post_type !== 'aqualuxe_booking') {
            return [];
        }
        
        // Get service ID
        $service_id = get_post_meta($booking_id, '_aqualuxe_service_id', true);
        
        // Get service
        $service = get_post($service_id);
        
        // Get booking date
        $date = get_post_meta($booking_id, '_aqualuxe_booking_date', true);
        
        // Get booking time
        $time = get_post_meta($booking_id, '_aqualuxe_booking_time', true);
        
        // Get booking status
        $status_terms = wp_get_post_terms($booking_id, 'aqualuxe_booking_status');
        $status = !empty($status_terms) ? $status_terms[0]->slug : 'pending';
        
        // Get customer data
        $customer_name = get_post_meta($booking_id, '_aqualuxe_customer_name', true);
        $customer_email = get_post_meta($booking_id, '_aqualuxe_customer_email', true);
        $customer_phone = get_post_meta($booking_id, '_aqualuxe_customer_phone', true);
        
        // Get order ID
        $order_id = get_post_meta($booking_id, '_aqualuxe_order_id', true);
        
        // Return booking data
        return [
            'id' => $booking_id,
            'service_id' => $service_id,
            'service_name' => $service ? $service->post_title : '',
            'date' => $date,
            'time' => $time,
            'status' => $status,
            'customer' => [
                'name' => $customer_name,
                'email' => $customer_email,
                'phone' => $customer_phone,
            ],
            'order_id' => $order_id,
        ];
    }

    /**
     * Get service data
     *
     * @param int $service_id Service ID
     * @return array Service data
     */
    public function get_service_data($service_id) {
        // Get service post
        $service = get_post($service_id);
        
        // Check if service exists
        if (!$service || $service->post_type !== 'aqualuxe_service') {
            return [];
        }
        
        // Get service duration
        $duration = get_post_meta($service_id, '_aqualuxe_service_duration', true);
        
        // Get service price
        $price = get_post_meta($service_id, '_aqualuxe_service_price', true);
        
        // Get service capacity
        $capacity = get_post_meta($service_id, '_aqualuxe_service_capacity', true);
        
        // Get service buffer time
        $buffer_time = get_post_meta($service_id, '_aqualuxe_service_buffer_time', true);
        
        // Get service categories
        $categories = wp_get_post_terms($service_id, 'aqualuxe_service_cat', ['fields' => 'all']);
        
        // Return service data
        return [
            'id' => $service_id,
            'name' => $service->post_title,
            'description' => $service->post_content,
            'excerpt' => $service->post_excerpt,
            'duration' => $duration ? $duration : 60,
            'price' => $price ? $price : 0,
            'capacity' => $capacity ? $capacity : 1,
            'buffer_time' => $buffer_time ? $buffer_time : 0,
            'categories' => $categories,
        ];
    }

    /**
     * Get service availability
     *
     * @param int $service_id Service ID
     * @return array Service availability
     */
    public function get_service_availability($service_id) {
        // Get service post
        $service = get_post($service_id);
        
        // Check if service exists
        if (!$service || $service->post_type !== 'aqualuxe_service') {
            return [];
        }
        
        // Get availability rules
        $rules = get_post_meta($service_id, '_aqualuxe_service_availability', true);
        
        // Check if rules exist
        if (!$rules) {
            // Create default rules
            $rules = [
                'monday' => [
                    'enabled' => true,
                    'start_time' => '09:00',
                    'end_time' => '17:00',
                ],
                'tuesday' => [
                    'enabled' => true,
                    'start_time' => '09:00',
                    'end_time' => '17:00',
                ],
                'wednesday' => [
                    'enabled' => true,
                    'start_time' => '09:00',
                    'end_time' => '17:00',
                ],
                'thursday' => [
                    'enabled' => true,
                    'start_time' => '09:00',
                    'end_time' => '17:00',
                ],
                'friday' => [
                    'enabled' => true,
                    'start_time' => '09:00',
                    'end_time' => '17:00',
                ],
                'saturday' => [
                    'enabled' => false,
                    'start_time' => '09:00',
                    'end_time' => '17:00',
                ],
                'sunday' => [
                    'enabled' => false,
                    'start_time' => '09:00',
                    'end_time' => '17:00',
                ],
            ];
        }
        
        // Get blocked dates
        $blocked_dates = get_post_meta($service_id, '_aqualuxe_service_blocked_dates', true);
        
        // Return availability
        return [
            'rules' => $rules,
            'blocked_dates' => $blocked_dates ? $blocked_dates : [],
        ];
    }

    /**
     * Get services
     *
     * @param int $service_id Service ID
     * @param string $category Category slug
     * @return array Services
     */
    public function get_services($service_id = 0, $category = '') {
        // Set up query args
        $args = [
            'post_type' => 'aqualuxe_service',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'orderby' => 'title',
            'order' => 'ASC',
        ];
        
        // Add service ID if specified
        if ($service_id) {
            $args['p'] = $service_id;
        }
        
        // Add category if specified
        if ($category) {
            $args['tax_query'] = [
                [
                    'taxonomy' => 'aqualuxe_service_cat',
                    'field' => 'slug',
                    'terms' => $category,
                ],
            ];
        }
        
        // Get services
        $services_query = new WP_Query($args);
        
        // Initialize services array
        $services = [];
        
        // Loop through services
        if ($services_query->have_posts()) {
            while ($services_query->have_posts()) {
                $services_query->the_post();
                
                // Get service data
                $services[] = $this->get_service_data(get_the_ID());
            }
            
            // Reset post data
            wp_reset_postdata();
        }
        
        // Return services
        return $services;
    }

    /**
     * Get bookings for month
     *
     * @param int $month Month number
     * @param int $year Year
     * @return array Bookings
     */
    public function get_bookings_for_month($month, $year) {
        // Set up query args
        $args = [
            'post_type' => 'aqualuxe_booking',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'meta_query' => [
                [
                    'key' => '_aqualuxe_booking_date',
                    'value' => [
                        date('Y-m-d', strtotime("$year-$month-01")),
                        date('Y-m-d', strtotime("$year-$month-" . date('t', strtotime("$year-$month-01")))),
                    ],
                    'compare' => 'BETWEEN',
                    'type' => 'DATE',
                ],
            ],
        ];
        
        // Get bookings
        $bookings_query = new WP_Query($args);
        
        // Initialize bookings array
        $bookings = [];
        
        // Loop through bookings
        if ($bookings_query->have_posts()) {
            while ($bookings_query->have_posts()) {
                $bookings_query->the_post();
                
                // Get booking data
                $bookings[] = $this->get_booking_data(get_the_ID());
            }
            
            // Reset post data
            wp_reset_postdata();
        }
        
        // Return bookings
        return $bookings;
    }

    /**
     * Get available time slots
     *
     * @param int $service_id Service ID
     * @param string $date Date
     * @return array Time slots
     */
    public function get_available_time_slots($service_id, $date) {
        // Get service data
        $service_data = $this->get_service_data($service_id);
        
        // Get service availability
        $availability = $this->get_service_availability($service_id);
        
        // Check if date is blocked
        if (in_array($date, $availability['blocked_dates'])) {
            return [];
        }
        
        // Get day of week
        $day_of_week = strtolower(date('l', strtotime($date)));
        
        // Check if day is enabled
        if (!isset($availability['rules'][$day_of_week]) || !$availability['rules'][$day_of_week]['enabled']) {
            return [];
        }
        
        // Get start and end times
        $start_time = $availability['rules'][$day_of_week]['start_time'];
        $end_time = $availability['rules'][$day_of_week]['end_time'];
        
        // Get service duration
        $duration = $service_data['duration'];
        
        // Get service buffer time
        $buffer_time = $service_data['buffer_time'];
        
        // Calculate time slots
        $time_slots = [];
        $current_time = strtotime($start_time);
        $end_time = strtotime($end_time);
        
        while ($current_time + ($duration * 60) <= $end_time) {
            // Format time
            $time_slot = date('H:i', $current_time);
            
            // Check if time slot is available
            if ($this->is_time_slot_available($service_id, $date, $time_slot)) {
                $time_slots[] = $time_slot;
            }
            
            // Move to next time slot
            $current_time += ($duration + $buffer_time) * 60;
        }
        
        // Return time slots
        return $time_slots;
    }

    /**
     * Check if time slot is available
     *
     * @param int $service_id Service ID
     * @param string $date Date
     * @param string $time Time
     * @return bool Whether time slot is available
     */
    public function is_time_slot_available($service_id, $date, $time) {
        // Get service data
        $service_data = $this->get_service_data($service_id);
        
        // Get service capacity
        $capacity = $service_data['capacity'];
        
        // Get service duration
        $duration = $service_data['duration'];
        
        // Calculate start and end times
        $start_time = strtotime("$date $time");
        $end_time = $start_time + ($duration * 60);
        
        // Set up query args
        $args = [
            'post_type' => 'aqualuxe_booking',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'meta_query' => [
                [
                    'key' => '_aqualuxe_service_id',
                    'value' => $service_id,
                ],
                [
                    'key' => '_aqualuxe_booking_date',
                    'value' => $date,
                    'compare' => '=',
                ],
            ],
            'tax_query' => [
                [
                    'taxonomy' => 'aqualuxe_booking_status',
                    'field' => 'slug',
                    'terms' => ['confirmed', 'pending'],
                    'operator' => 'IN',
                ],
            ],
        ];
        
        // Get bookings
        $bookings_query = new WP_Query($args);
        
        // Count bookings in time slot
        $bookings_in_slot = 0;
        
        // Loop through bookings
        if ($bookings_query->have_posts()) {
            while ($bookings_query->have_posts()) {
                $bookings_query->the_post();
                
                // Get booking time
                $booking_time = get_post_meta(get_the_ID(), '_aqualuxe_booking_time', true);
                
                // Calculate booking start and end times
                $booking_start_time = strtotime("$date $booking_time");
                $booking_end_time = $booking_start_time + ($duration * 60);
                
                // Check if booking overlaps with time slot
                if (
                    ($booking_start_time >= $start_time && $booking_start_time < $end_time) ||
                    ($booking_end_time > $start_time && $booking_end_time <= $end_time) ||
                    ($booking_start_time <= $start_time && $booking_end_time >= $end_time)
                ) {
                    $bookings_in_slot++;
                }
            }
            
            // Reset post data
            wp_reset_postdata();
        }
        
        // Check if time slot is available
        return $bookings_in_slot < $capacity;
    }

    /**
     * Create booking
     *
     * @param int $service_id Service ID
     * @param string $date Date
     * @param string $time Time
     * @param array $customer_data Customer data
     * @param int $order_id Order ID
     * @return int|bool Booking ID or false on failure
     */
    public function create_booking($service_id, $date, $time, $customer_data, $order_id = 0) {
        // Get service
        $service = get_post($service_id);
        
        // Check if service exists
        if (!$service || $service->post_type !== 'aqualuxe_service') {
            return false;
        }
        
        // Check if time slot is available
        if (!$this->is_time_slot_available($service_id, $date, $time)) {
            return false;
        }
        
        // Create booking post
        $booking_id = wp_insert_post([
            'post_title' => sprintf(
                __('Booking for %s on %s at %s', 'aqualuxe'),
                $service->post_title,
                date_i18n(get_option('date_format'), strtotime($date)),
                date_i18n(get_option('time_format'), strtotime($time))
            ),
            'post_type' => 'aqualuxe_booking',
            'post_status' => 'publish',
        ]);
        
        // Check if booking was created
        if (!$booking_id) {
            return false;
        }
        
        // Set booking status
        wp_set_object_terms($booking_id, $order_id ? 'confirmed' : 'pending', 'aqualuxe_booking_status');
        
        // Save booking meta
        update_post_meta($booking_id, '_aqualuxe_service_id', $service_id);
        update_post_meta($booking_id, '_aqualuxe_booking_date', $date);
        update_post_meta($booking_id, '_aqualuxe_booking_time', $time);
        
        // Save customer data
        if (isset($customer_data['name'])) {
            update_post_meta($booking_id, '_aqualuxe_customer_name', sanitize_text_field($customer_data['name']));
        }
        
        if (isset($customer_data['email'])) {
            update_post_meta($booking_id, '_aqualuxe_customer_email', sanitize_email($customer_data['email']));
        }
        
        if (isset($customer_data['phone'])) {
            update_post_meta($booking_id, '_aqualuxe_customer_phone', sanitize_text_field($customer_data['phone']));
        }
        
        // Save order ID
        if ($order_id) {
            update_post_meta($booking_id, '_aqualuxe_order_id', $order_id);
        }
        
        // Send booking notification
        $this->send_booking_notification($booking_id);
        
        // Return booking ID
        return $booking_id;
    }

    /**
     * Send booking notification
     *
     * @param int $booking_id Booking ID
     */
    public function send_booking_notification($booking_id) {
        // Get booking data
        $booking_data = $this->get_booking_data($booking_id);
        
        // Check if booking exists
        if (!$booking_data) {
            return;
        }
        
        // Get admin email
        $admin_email = get_option('admin_email');
        
        // Get customer email
        $customer_email = $booking_data['customer']['email'];
        
        // Get site name
        $site_name = get_bloginfo('name');
        
        // Get booking date and time
        $booking_date = date_i18n(get_option('date_format'), strtotime($booking_data['date']));
        $booking_time = date_i18n(get_option('time_format'), strtotime($booking_data['time']));
        
        // Send admin notification
        wp_mail(
            $admin_email,
            sprintf(__('New Booking: %s', 'aqualuxe'), $booking_data['service_name']),
            sprintf(
                __("A new booking has been created.\n\nService: %s\nDate: %s\nTime: %s\n\nCustomer: %s\nEmail: %s\nPhone: %s\n\nView Booking: %s", 'aqualuxe'),
                $booking_data['service_name'],
                $booking_date,
                $booking_time,
                $booking_data['customer']['name'],
                $booking_data['customer']['email'],
                $booking_data['customer']['phone'],
                admin_url('post.php?post=' . $booking_id . '&action=edit')
            )
        );
        
        // Send customer notification
        if ($customer_email) {
            wp_mail(
                $customer_email,
                sprintf(__('Your Booking at %s', 'aqualuxe'), $site_name),
                sprintf(
                    __("Thank you for your booking!\n\nService: %s\nDate: %s\nTime: %s\n\nWe look forward to seeing you.\n\n%s", 'aqualuxe'),
                    $booking_data['service_name'],
                    $booking_date,
                    $booking_time,
                    $site_name
                )
            );
        }
    }

    /**
     * Get booking confirmation URL
     *
     * @param int $booking_id Booking ID
     * @return string Confirmation URL
     */
    public function get_booking_confirmation_url($booking_id) {
        // Get confirmation page ID
        $confirmation_page_id = get_theme_mod('aqualuxe_booking_confirmation_page', 0);
        
        // Check if confirmation page exists
        if ($confirmation_page_id) {
            // Get confirmation page URL
            $confirmation_url = get_permalink($confirmation_page_id);
            
            // Add booking ID
            $confirmation_url = add_query_arg('booking_id', $booking_id, $confirmation_url);
            
            // Return confirmation URL
            return $confirmation_url;
        }
        
        // Return home URL
        return home_url();
    }

    /**
     * Get settings
     *
     * @return array Settings
     */
    public function get_settings() {
        // Get settings from options
        $settings = get_option('aqualuxe_bookings_settings', []);
        
        // Merge with default settings
        $settings = wp_parse_args($settings, $this->get_default_settings());
        
        // Return settings
        return $settings;
    }

    /**
     * Get default settings
     *
     * @return array Default settings
     */
    protected function get_default_settings() {
        return [
            'enabled' => true,
            'booking_page' => 0,
            'confirmation_page' => 0,
            'form_style' => 'standard',
            'calendar_style' => 'standard',
            'email_notifications' => true,
            'admin_email' => get_option('admin_email'),
            'confirmation_email_subject' => __('Your Booking Confirmation', 'aqualuxe'),
            'confirmation_email_message' => __("Thank you for your booking!\n\nService: {service}\nDate: {date}\nTime: {time}\n\nWe look forward to seeing you.\n\n{site_name}", 'aqualuxe'),
        ];
    }
}