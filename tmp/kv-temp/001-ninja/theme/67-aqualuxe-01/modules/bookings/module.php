<?php
/**
 * Bookings Module
 *
 * @package AquaLuxe
 * @subpackage Modules\Bookings
 * @since 1.0.0
 */

namespace AquaLuxe\Modules\Bookings;

/**
 * Bookings Module Class
 * 
 * This class handles the bookings and scheduling functionality for the theme.
 */
class Module {
    /**
     * Instance of this class
     *
     * @var Module
     */
    private static $instance = null;

    /**
     * Module ID
     *
     * @var string
     */
    private $module_id = 'bookings';

    /**
     * Module name
     *
     * @var string
     */
    private $module_name = 'Bookings & Scheduling';

    /**
     * Module description
     *
     * @var string
     */
    private $module_description = 'Allows customers to book services, consultations, and maintenance appointments.';

    /**
     * Module version
     *
     * @var string
     */
    private $module_version = '1.0.0';

    /**
     * Module path
     *
     * @var string
     */
    private $module_path;

    /**
     * Module URL
     *
     * @var string
     */
    private $module_url;

    /**
     * Get the singleton instance
     *
     * @return Module
     */
    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    private function __construct() {
        $this->module_path = AQUALUXE_MODULES_DIR . $this->module_id . '/';
        $this->module_url = AQUALUXE_URI . 'modules/' . $this->module_id . '/';
        
        $this->init_hooks();
        $this->load_dependencies();
    }

    /**
     * Initialize hooks
     *
     * @return void
     */
    private function init_hooks() {
        // Register post type
        add_action( 'init', [ $this, 'register_post_types' ] );
        
        // Register taxonomies
        add_action( 'init', [ $this, 'register_taxonomies' ] );
        
        // Enqueue scripts and styles
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
        
        // Add admin menu
        add_action( 'admin_menu', [ $this, 'add_admin_menu' ] );
        
        // Register shortcodes
        add_action( 'init', [ $this, 'register_shortcodes' ] );
        
        // Register widgets
        add_action( 'widgets_init', [ $this, 'register_widgets' ] );
        
        // Register REST API endpoints
        add_action( 'rest_api_init', [ $this, 'register_rest_routes' ] );
        
        // Add meta boxes
        add_action( 'add_meta_boxes', [ $this, 'add_meta_boxes' ] );
        
        // Save meta boxes
        add_action( 'save_post', [ $this, 'save_meta_boxes' ] );
        
        // Add settings page
        add_action( 'admin_init', [ $this, 'register_settings' ] );
        
        // Add dashboard widgets
        add_action( 'wp_dashboard_setup', [ $this, 'add_dashboard_widgets' ] );
        
        // Add custom columns to admin list
        add_filter( 'manage_aqualuxe_booking_posts_columns', [ $this, 'add_custom_columns' ] );
        add_action( 'manage_aqualuxe_booking_posts_custom_column', [ $this, 'custom_column_content' ], 10, 2 );
        
        // Add custom filters to admin list
        add_action( 'restrict_manage_posts', [ $this, 'add_custom_filters' ] );
        
        // Add custom bulk actions
        add_filter( 'bulk_actions-edit-aqualuxe_booking', [ $this, 'add_custom_bulk_actions' ] );
        add_filter( 'handle_bulk_actions-edit-aqualuxe_booking', [ $this, 'handle_custom_bulk_actions' ], 10, 3 );
        
        // Add custom admin notices
        add_action( 'admin_notices', [ $this, 'admin_notices' ] );
        
        // Add WooCommerce integration
        if ( class_exists( 'WooCommerce' ) ) {
            $this->init_woocommerce_integration();
        }
    }

    /**
     * Load dependencies
     *
     * @return void
     */
    private function load_dependencies() {
        // Include required files
        require_once $this->module_path . 'inc/class-booking.php';
        require_once $this->module_path . 'inc/class-service.php';
        require_once $this->module_path . 'inc/class-calendar.php';
        require_once $this->module_path . 'inc/class-availability.php';
        require_once $this->module_path . 'inc/class-notifications.php';
        require_once $this->module_path . 'inc/class-settings.php';
        require_once $this->module_path . 'inc/class-shortcodes.php';
        require_once $this->module_path . 'inc/class-widgets.php';
        require_once $this->module_path . 'inc/class-rest-api.php';
        require_once $this->module_path . 'inc/class-woocommerce.php';
        require_once $this->module_path . 'inc/functions.php';
    }

    /**
     * Register post types
     *
     * @return void
     */
    public function register_post_types() {
        // Register booking post type
        register_post_type(
            'aqualuxe_booking',
            [
                'labels' => [
                    'name'               => esc_html__( 'Bookings', 'aqualuxe' ),
                    'singular_name'      => esc_html__( 'Booking', 'aqualuxe' ),
                    'add_new'            => esc_html__( 'Add New', 'aqualuxe' ),
                    'add_new_item'       => esc_html__( 'Add New Booking', 'aqualuxe' ),
                    'edit_item'          => esc_html__( 'Edit Booking', 'aqualuxe' ),
                    'new_item'           => esc_html__( 'New Booking', 'aqualuxe' ),
                    'view_item'          => esc_html__( 'View Booking', 'aqualuxe' ),
                    'search_items'       => esc_html__( 'Search Bookings', 'aqualuxe' ),
                    'not_found'          => esc_html__( 'No bookings found', 'aqualuxe' ),
                    'not_found_in_trash' => esc_html__( 'No bookings found in trash', 'aqualuxe' ),
                    'parent_item_colon'  => esc_html__( 'Parent Booking:', 'aqualuxe' ),
                    'menu_name'          => esc_html__( 'Bookings', 'aqualuxe' ),
                ],
                'public'              => true,
                'hierarchical'        => false,
                'show_ui'             => true,
                'show_in_nav_menus'   => false,
                'supports'            => [ 'title', 'custom-fields' ],
                'has_archive'         => false,
                'rewrite'             => [ 'slug' => 'bookings' ],
                'query_var'           => true,
                'menu_icon'           => 'dashicons-calendar-alt',
                'show_in_rest'        => true,
                'rest_base'           => 'bookings',
                'rest_controller_class' => 'WP_REST_Posts_Controller',
                'capability_type'     => 'post',
                'map_meta_cap'        => true,
            ]
        );

        // Register service post type
        register_post_type(
            'aqualuxe_service',
            [
                'labels' => [
                    'name'               => esc_html__( 'Services', 'aqualuxe' ),
                    'singular_name'      => esc_html__( 'Service', 'aqualuxe' ),
                    'add_new'            => esc_html__( 'Add New', 'aqualuxe' ),
                    'add_new_item'       => esc_html__( 'Add New Service', 'aqualuxe' ),
                    'edit_item'          => esc_html__( 'Edit Service', 'aqualuxe' ),
                    'new_item'           => esc_html__( 'New Service', 'aqualuxe' ),
                    'view_item'          => esc_html__( 'View Service', 'aqualuxe' ),
                    'search_items'       => esc_html__( 'Search Services', 'aqualuxe' ),
                    'not_found'          => esc_html__( 'No services found', 'aqualuxe' ),
                    'not_found_in_trash' => esc_html__( 'No services found in trash', 'aqualuxe' ),
                    'parent_item_colon'  => esc_html__( 'Parent Service:', 'aqualuxe' ),
                    'menu_name'          => esc_html__( 'Services', 'aqualuxe' ),
                ],
                'public'              => true,
                'hierarchical'        => false,
                'show_ui'             => true,
                'show_in_nav_menus'   => true,
                'supports'            => [ 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ],
                'has_archive'         => true,
                'rewrite'             => [ 'slug' => 'services' ],
                'query_var'           => true,
                'menu_icon'           => 'dashicons-admin-tools',
                'show_in_rest'        => true,
                'rest_base'           => 'services',
                'rest_controller_class' => 'WP_REST_Posts_Controller',
                'capability_type'     => 'post',
                'map_meta_cap'        => true,
            ]
        );
    }

    /**
     * Register taxonomies
     *
     * @return void
     */
    public function register_taxonomies() {
        // Register service category taxonomy
        register_taxonomy(
            'aqualuxe_service_category',
            'aqualuxe_service',
            [
                'labels' => [
                    'name'              => esc_html__( 'Service Categories', 'aqualuxe' ),
                    'singular_name'     => esc_html__( 'Service Category', 'aqualuxe' ),
                    'search_items'      => esc_html__( 'Search Service Categories', 'aqualuxe' ),
                    'all_items'         => esc_html__( 'All Service Categories', 'aqualuxe' ),
                    'parent_item'       => esc_html__( 'Parent Service Category', 'aqualuxe' ),
                    'parent_item_colon' => esc_html__( 'Parent Service Category:', 'aqualuxe' ),
                    'edit_item'         => esc_html__( 'Edit Service Category', 'aqualuxe' ),
                    'update_item'       => esc_html__( 'Update Service Category', 'aqualuxe' ),
                    'add_new_item'      => esc_html__( 'Add New Service Category', 'aqualuxe' ),
                    'new_item_name'     => esc_html__( 'New Service Category Name', 'aqualuxe' ),
                    'menu_name'         => esc_html__( 'Categories', 'aqualuxe' ),
                ],
                'hierarchical'      => true,
                'show_ui'           => true,
                'show_admin_column' => true,
                'query_var'         => true,
                'rewrite'           => [ 'slug' => 'service-category' ],
                'show_in_rest'      => true,
                'rest_base'         => 'service-categories',
                'rest_controller_class' => 'WP_REST_Terms_Controller',
            ]
        );

        // Register booking status taxonomy
        register_taxonomy(
            'aqualuxe_booking_status',
            'aqualuxe_booking',
            [
                'labels' => [
                    'name'              => esc_html__( 'Booking Statuses', 'aqualuxe' ),
                    'singular_name'     => esc_html__( 'Booking Status', 'aqualuxe' ),
                    'search_items'      => esc_html__( 'Search Booking Statuses', 'aqualuxe' ),
                    'all_items'         => esc_html__( 'All Booking Statuses', 'aqualuxe' ),
                    'parent_item'       => esc_html__( 'Parent Booking Status', 'aqualuxe' ),
                    'parent_item_colon' => esc_html__( 'Parent Booking Status:', 'aqualuxe' ),
                    'edit_item'         => esc_html__( 'Edit Booking Status', 'aqualuxe' ),
                    'update_item'       => esc_html__( 'Update Booking Status', 'aqualuxe' ),
                    'add_new_item'      => esc_html__( 'Add New Booking Status', 'aqualuxe' ),
                    'new_item_name'     => esc_html__( 'New Booking Status Name', 'aqualuxe' ),
                    'menu_name'         => esc_html__( 'Statuses', 'aqualuxe' ),
                ],
                'hierarchical'      => false,
                'show_ui'           => true,
                'show_admin_column' => true,
                'query_var'         => true,
                'rewrite'           => [ 'slug' => 'booking-status' ],
                'show_in_rest'      => true,
                'rest_base'         => 'booking-statuses',
                'rest_controller_class' => 'WP_REST_Terms_Controller',
            ]
        );

        // Add default booking statuses
        $statuses = [
            'pending'    => esc_html__( 'Pending', 'aqualuxe' ),
            'confirmed'  => esc_html__( 'Confirmed', 'aqualuxe' ),
            'completed'  => esc_html__( 'Completed', 'aqualuxe' ),
            'cancelled'  => esc_html__( 'Cancelled', 'aqualuxe' ),
            'rescheduled' => esc_html__( 'Rescheduled', 'aqualuxe' ),
        ];

        foreach ( $statuses as $slug => $name ) {
            if ( ! term_exists( $slug, 'aqualuxe_booking_status' ) ) {
                wp_insert_term( $name, 'aqualuxe_booking_status', [ 'slug' => $slug ] );
            }
        }
    }

    /**
     * Enqueue scripts and styles
     *
     * @return void
     */
    public function enqueue_scripts() {
        // Enqueue frontend styles
        wp_enqueue_style(
            'aqualuxe-bookings',
            $this->module_url . 'assets/css/bookings.css',
            [],
            $this->module_version
        );

        // Enqueue frontend scripts
        wp_enqueue_script(
            'aqualuxe-bookings',
            $this->module_url . 'assets/js/bookings.js',
            [ 'jquery' ],
            $this->module_version,
            true
        );

        // Localize script
        wp_localize_script(
            'aqualuxe-bookings',
            'aqualuxeBookings',
            [
                'ajaxUrl'   => admin_url( 'admin-ajax.php' ),
                'nonce'     => wp_create_nonce( 'aqualuxe-bookings' ),
                'i18n'      => [
                    'selectDate'        => esc_html__( 'Select Date', 'aqualuxe' ),
                    'selectTime'        => esc_html__( 'Select Time', 'aqualuxe' ),
                    'selectService'     => esc_html__( 'Select Service', 'aqualuxe' ),
                    'bookNow'           => esc_html__( 'Book Now', 'aqualuxe' ),
                    'booking'           => esc_html__( 'Booking...', 'aqualuxe' ),
                    'booked'            => esc_html__( 'Booked!', 'aqualuxe' ),
                    'error'             => esc_html__( 'Error', 'aqualuxe' ),
                    'noTimesAvailable'  => esc_html__( 'No times available for this date', 'aqualuxe' ),
                    'noServicesAvailable' => esc_html__( 'No services available', 'aqualuxe' ),
                    'requiredFields'    => esc_html__( 'Please fill in all required fields', 'aqualuxe' ),
                    'invalidEmail'      => esc_html__( 'Please enter a valid email address', 'aqualuxe' ),
                    'invalidPhone'      => esc_html__( 'Please enter a valid phone number', 'aqualuxe' ),
                    'minimumNotice'     => esc_html__( 'Bookings require at least 24 hours notice', 'aqualuxe' ),
                    'dateInPast'        => esc_html__( 'Please select a future date', 'aqualuxe' ),
                    'dateUnavailable'   => esc_html__( 'This date is unavailable', 'aqualuxe' ),
                    'timeUnavailable'   => esc_html__( 'This time is unavailable', 'aqualuxe' ),
                    'serviceUnavailable' => esc_html__( 'This service is unavailable', 'aqualuxe' ),
                    'bookingSuccess'    => esc_html__( 'Your booking has been confirmed!', 'aqualuxe' ),
                    'bookingError'      => esc_html__( 'There was an error processing your booking. Please try again.', 'aqualuxe' ),
                    'bookingPending'    => esc_html__( 'Your booking has been received and is pending confirmation.', 'aqualuxe' ),
                    'bookingConfirmed'  => esc_html__( 'Your booking has been confirmed!', 'aqualuxe' ),
                    'bookingCancelled'  => esc_html__( 'Your booking has been cancelled.', 'aqualuxe' ),
                    'bookingRescheduled' => esc_html__( 'Your booking has been rescheduled.', 'aqualuxe' ),
                    'bookingCompleted'  => esc_html__( 'Your booking has been completed.', 'aqualuxe' ),
                ],
            ]
        );
    }

    /**
     * Add admin menu
     *
     * @return void
     */
    public function add_admin_menu() {
        // Add bookings menu
        add_menu_page(
            esc_html__( 'Bookings', 'aqualuxe' ),
            esc_html__( 'Bookings', 'aqualuxe' ),
            'manage_options',
            'aqualuxe-bookings',
            [ $this, 'admin_page' ],
            'dashicons-calendar-alt',
            30
        );

        // Add bookings submenu
        add_submenu_page(
            'aqualuxe-bookings',
            esc_html__( 'All Bookings', 'aqualuxe' ),
            esc_html__( 'All Bookings', 'aqualuxe' ),
            'manage_options',
            'edit.php?post_type=aqualuxe_booking',
            null
        );

        // Add services submenu
        add_submenu_page(
            'aqualuxe-bookings',
            esc_html__( 'Services', 'aqualuxe' ),
            esc_html__( 'Services', 'aqualuxe' ),
            'manage_options',
            'edit.php?post_type=aqualuxe_service',
            null
        );

        // Add calendar submenu
        add_submenu_page(
            'aqualuxe-bookings',
            esc_html__( 'Calendar', 'aqualuxe' ),
            esc_html__( 'Calendar', 'aqualuxe' ),
            'manage_options',
            'aqualuxe-bookings-calendar',
            [ $this, 'calendar_page' ]
        );

        // Add settings submenu
        add_submenu_page(
            'aqualuxe-bookings',
            esc_html__( 'Settings', 'aqualuxe' ),
            esc_html__( 'Settings', 'aqualuxe' ),
            'manage_options',
            'aqualuxe-bookings-settings',
            [ $this, 'settings_page' ]
        );
    }

    /**
     * Admin page
     *
     * @return void
     */
    public function admin_page() {
        // Include admin page template
        include $this->module_path . 'templates/admin/dashboard.php';
    }

    /**
     * Calendar page
     *
     * @return void
     */
    public function calendar_page() {
        // Include calendar page template
        include $this->module_path . 'templates/admin/calendar.php';
    }

    /**
     * Settings page
     *
     * @return void
     */
    public function settings_page() {
        // Include settings page template
        include $this->module_path . 'templates/admin/settings.php';
    }

    /**
     * Register shortcodes
     *
     * @return void
     */
    public function register_shortcodes() {
        // Register booking form shortcode
        add_shortcode( 'aqualuxe_booking_form', [ $this, 'booking_form_shortcode' ] );
        
        // Register services list shortcode
        add_shortcode( 'aqualuxe_services', [ $this, 'services_shortcode' ] );
        
        // Register calendar shortcode
        add_shortcode( 'aqualuxe_calendar', [ $this, 'calendar_shortcode' ] );
        
        // Register availability shortcode
        add_shortcode( 'aqualuxe_availability', [ $this, 'availability_shortcode' ] );
    }

    /**
     * Booking form shortcode
     *
     * @param array $atts Shortcode attributes.
     * @return string
     */
    public function booking_form_shortcode( $atts ) {
        // Extract shortcode attributes
        $atts = shortcode_atts(
            [
                'service_id' => 0,
                'date'       => '',
                'time'       => '',
                'title'      => esc_html__( 'Book an Appointment', 'aqualuxe' ),
                'button'     => esc_html__( 'Book Now', 'aqualuxe' ),
                'class'      => '',
            ],
            $atts,
            'aqualuxe_booking_form'
        );

        // Start output buffering
        ob_start();

        // Include booking form template
        include $this->module_path . 'templates/booking-form.php';

        // Return buffered output
        return ob_get_clean();
    }

    /**
     * Services shortcode
     *
     * @param array $atts Shortcode attributes.
     * @return string
     */
    public function services_shortcode( $atts ) {
        // Extract shortcode attributes
        $atts = shortcode_atts(
            [
                'category'   => '',
                'limit'      => -1,
                'orderby'    => 'title',
                'order'      => 'ASC',
                'columns'    => 3,
                'show_image' => true,
                'show_price' => true,
                'show_desc'  => true,
                'show_button' => true,
                'button_text' => esc_html__( 'Book Now', 'aqualuxe' ),
                'class'      => '',
            ],
            $atts,
            'aqualuxe_services'
        );

        // Convert string boolean values to actual booleans
        $atts['show_image'] = filter_var( $atts['show_image'], FILTER_VALIDATE_BOOLEAN );
        $atts['show_price'] = filter_var( $atts['show_price'], FILTER_VALIDATE_BOOLEAN );
        $atts['show_desc'] = filter_var( $atts['show_desc'], FILTER_VALIDATE_BOOLEAN );
        $atts['show_button'] = filter_var( $atts['show_button'], FILTER_VALIDATE_BOOLEAN );

        // Query args
        $args = [
            'post_type'      => 'aqualuxe_service',
            'posts_per_page' => $atts['limit'],
            'orderby'        => $atts['orderby'],
            'order'          => $atts['order'],
        ];

        // Add category if specified
        if ( ! empty( $atts['category'] ) ) {
            $args['tax_query'] = [
                [
                    'taxonomy' => 'aqualuxe_service_category',
                    'field'    => 'slug',
                    'terms'    => explode( ',', $atts['category'] ),
                ],
            ];
        }

        // Get services
        $services = new \WP_Query( $args );

        // Start output buffering
        ob_start();

        // Include services template
        include $this->module_path . 'templates/services.php';

        // Return buffered output
        return ob_get_clean();
    }

    /**
     * Calendar shortcode
     *
     * @param array $atts Shortcode attributes.
     * @return string
     */
    public function calendar_shortcode( $atts ) {
        // Extract shortcode attributes
        $atts = shortcode_atts(
            [
                'service_id' => 0,
                'month'      => date( 'n' ),
                'year'       => date( 'Y' ),
                'class'      => '',
            ],
            $atts,
            'aqualuxe_calendar'
        );

        // Start output buffering
        ob_start();

        // Include calendar template
        include $this->module_path . 'templates/calendar.php';

        // Return buffered output
        return ob_get_clean();
    }

    /**
     * Availability shortcode
     *
     * @param array $atts Shortcode attributes.
     * @return string
     */
    public function availability_shortcode( $atts ) {
        // Extract shortcode attributes
        $atts = shortcode_atts(
            [
                'service_id' => 0,
                'date'       => date( 'Y-m-d' ),
                'class'      => '',
            ],
            $atts,
            'aqualuxe_availability'
        );

        // Start output buffering
        ob_start();

        // Include availability template
        include $this->module_path . 'templates/availability.php';

        // Return buffered output
        return ob_get_clean();
    }

    /**
     * Register widgets
     *
     * @return void
     */
    public function register_widgets() {
        // Register booking form widget
        register_widget( 'AquaLuxe\\Modules\\Bookings\\Widgets\\Booking_Form' );
        
        // Register services widget
        register_widget( 'AquaLuxe\\Modules\\Bookings\\Widgets\\Services' );
        
        // Register calendar widget
        register_widget( 'AquaLuxe\\Modules\\Bookings\\Widgets\\Calendar' );
        
        // Register availability widget
        register_widget( 'AquaLuxe\\Modules\\Bookings\\Widgets\\Availability' );
    }

    /**
     * Register REST API endpoints
     *
     * @return void
     */
    public function register_rest_routes() {
        // Register REST API endpoints
        $rest_api = new REST_API();
        $rest_api->register_routes();
    }

    /**
     * Add meta boxes
     *
     * @return void
     */
    public function add_meta_boxes() {
        // Add booking details meta box
        add_meta_box(
            'aqualuxe_booking_details',
            esc_html__( 'Booking Details', 'aqualuxe' ),
            [ $this, 'booking_details_meta_box' ],
            'aqualuxe_booking',
            'normal',
            'high'
        );

        // Add booking customer meta box
        add_meta_box(
            'aqualuxe_booking_customer',
            esc_html__( 'Customer Details', 'aqualuxe' ),
            [ $this, 'booking_customer_meta_box' ],
            'aqualuxe_booking',
            'normal',
            'high'
        );

        // Add booking notes meta box
        add_meta_box(
            'aqualuxe_booking_notes',
            esc_html__( 'Booking Notes', 'aqualuxe' ),
            [ $this, 'booking_notes_meta_box' ],
            'aqualuxe_booking',
            'normal',
            'high'
        );

        // Add service details meta box
        add_meta_box(
            'aqualuxe_service_details',
            esc_html__( 'Service Details', 'aqualuxe' ),
            [ $this, 'service_details_meta_box' ],
            'aqualuxe_service',
            'normal',
            'high'
        );

        // Add service availability meta box
        add_meta_box(
            'aqualuxe_service_availability',
            esc_html__( 'Service Availability', 'aqualuxe' ),
            [ $this, 'service_availability_meta_box' ],
            'aqualuxe_service',
            'normal',
            'high'
        );

        // Add service pricing meta box
        add_meta_box(
            'aqualuxe_service_pricing',
            esc_html__( 'Service Pricing', 'aqualuxe' ),
            [ $this, 'service_pricing_meta_box' ],
            'aqualuxe_service',
            'normal',
            'high'
        );
    }

    /**
     * Booking details meta box
     *
     * @param \WP_Post $post Post object.
     * @return void
     */
    public function booking_details_meta_box( $post ) {
        // Get booking details
        $service_id = get_post_meta( $post->ID, '_service_id', true );
        $date = get_post_meta( $post->ID, '_date', true );
        $time = get_post_meta( $post->ID, '_time', true );
        $duration = get_post_meta( $post->ID, '_duration', true );
        $status = wp_get_post_terms( $post->ID, 'aqualuxe_booking_status', [ 'fields' => 'slugs' ] );
        $status = ! empty( $status ) ? $status[0] : 'pending';

        // Include booking details template
        include $this->module_path . 'templates/admin/meta-boxes/booking-details.php';
    }

    /**
     * Booking customer meta box
     *
     * @param \WP_Post $post Post object.
     * @return void
     */
    public function booking_customer_meta_box( $post ) {
        // Get customer details
        $customer_name = get_post_meta( $post->ID, '_customer_name', true );
        $customer_email = get_post_meta( $post->ID, '_customer_email', true );
        $customer_phone = get_post_meta( $post->ID, '_customer_phone', true );
        $customer_address = get_post_meta( $post->ID, '_customer_address', true );
        $customer_city = get_post_meta( $post->ID, '_customer_city', true );
        $customer_state = get_post_meta( $post->ID, '_customer_state', true );
        $customer_zip = get_post_meta( $post->ID, '_customer_zip', true );
        $customer_country = get_post_meta( $post->ID, '_customer_country', true );
        $customer_notes = get_post_meta( $post->ID, '_customer_notes', true );

        // Include customer details template
        include $this->module_path . 'templates/admin/meta-boxes/booking-customer.php';
    }

    /**
     * Booking notes meta box
     *
     * @param \WP_Post $post Post object.
     * @return void
     */
    public function booking_notes_meta_box( $post ) {
        // Get booking notes
        $notes = get_post_meta( $post->ID, '_booking_notes', true );

        // Include booking notes template
        include $this->module_path . 'templates/admin/meta-boxes/booking-notes.php';
    }

    /**
     * Service details meta box
     *
     * @param \WP_Post $post Post object.
     * @return void
     */
    public function service_details_meta_box( $post ) {
        // Get service details
        $duration = get_post_meta( $post->ID, '_duration', true );
        $buffer_before = get_post_meta( $post->ID, '_buffer_before', true );
        $buffer_after = get_post_meta( $post->ID, '_buffer_after', true );
        $capacity = get_post_meta( $post->ID, '_capacity', true );
        $location = get_post_meta( $post->ID, '_location', true );

        // Include service details template
        include $this->module_path . 'templates/admin/meta-boxes/service-details.php';
    }

    /**
     * Service availability meta box
     *
     * @param \WP_Post $post Post object.
     * @return void
     */
    public function service_availability_meta_box( $post ) {
        // Get service availability
        $availability = get_post_meta( $post->ID, '_availability', true );
        $availability = ! empty( $availability ) ? $availability : [
            'monday'    => [ 'enabled' => true, 'slots' => [ [ 'start' => '09:00', 'end' => '17:00' ] ] ],
            'tuesday'   => [ 'enabled' => true, 'slots' => [ [ 'start' => '09:00', 'end' => '17:00' ] ] ],
            'wednesday' => [ 'enabled' => true, 'slots' => [ [ 'start' => '09:00', 'end' => '17:00' ] ] ],
            'thursday'  => [ 'enabled' => true, 'slots' => [ [ 'start' => '09:00', 'end' => '17:00' ] ] ],
            'friday'    => [ 'enabled' => true, 'slots' => [ [ 'start' => '09:00', 'end' => '17:00' ] ] ],
            'saturday'  => [ 'enabled' => false, 'slots' => [] ],
            'sunday'    => [ 'enabled' => false, 'slots' => [] ],
        ];

        // Include service availability template
        include $this->module_path . 'templates/admin/meta-boxes/service-availability.php';
    }

    /**
     * Service pricing meta box
     *
     * @param \WP_Post $post Post object.
     * @return void
     */
    public function service_pricing_meta_box( $post ) {
        // Get service pricing
        $price = get_post_meta( $post->ID, '_price', true );
        $sale_price = get_post_meta( $post->ID, '_sale_price', true );
        $deposit = get_post_meta( $post->ID, '_deposit', true );
        $deposit_type = get_post_meta( $post->ID, '_deposit_type', true );
        $tax_class = get_post_meta( $post->ID, '_tax_class', true );
        $wc_product_id = get_post_meta( $post->ID, '_wc_product_id', true );

        // Include service pricing template
        include $this->module_path . 'templates/admin/meta-boxes/service-pricing.php';
    }

    /**
     * Save meta boxes
     *
     * @param int $post_id Post ID.
     * @return void
     */
    public function save_meta_boxes( $post_id ) {
        // Check if our nonce is set
        if ( ! isset( $_POST['aqualuxe_bookings_nonce'] ) ) {
            return;
        }

        // Verify that the nonce is valid
        if ( ! wp_verify_nonce( $_POST['aqualuxe_bookings_nonce'], 'aqualuxe_bookings' ) ) {
            return;
        }

        // If this is an autosave, our form has not been submitted, so we don't want to do anything
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }

        // Check the user's permissions
        if ( isset( $_POST['post_type'] ) && 'aqualuxe_booking' === $_POST['post_type'] ) {
            if ( ! current_user_can( 'edit_post', $post_id ) ) {
                return;
            }
        } elseif ( isset( $_POST['post_type'] ) && 'aqualuxe_service' === $_POST['post_type'] ) {
            if ( ! current_user_can( 'edit_post', $post_id ) ) {
                return;
            }
        } else {
            return;
        }

        // Save booking details
        if ( isset( $_POST['post_type'] ) && 'aqualuxe_booking' === $_POST['post_type'] ) {
            // Save booking details
            if ( isset( $_POST['_service_id'] ) ) {
                update_post_meta( $post_id, '_service_id', sanitize_text_field( $_POST['_service_id'] ) );
            }

            if ( isset( $_POST['_date'] ) ) {
                update_post_meta( $post_id, '_date', sanitize_text_field( $_POST['_date'] ) );
            }

            if ( isset( $_POST['_time'] ) ) {
                update_post_meta( $post_id, '_time', sanitize_text_field( $_POST['_time'] ) );
            }

            if ( isset( $_POST['_duration'] ) ) {
                update_post_meta( $post_id, '_duration', sanitize_text_field( $_POST['_duration'] ) );
            }

            if ( isset( $_POST['_status'] ) ) {
                wp_set_object_terms( $post_id, sanitize_text_field( $_POST['_status'] ), 'aqualuxe_booking_status' );
            }

            // Save customer details
            if ( isset( $_POST['_customer_name'] ) ) {
                update_post_meta( $post_id, '_customer_name', sanitize_text_field( $_POST['_customer_name'] ) );
            }

            if ( isset( $_POST['_customer_email'] ) ) {
                update_post_meta( $post_id, '_customer_email', sanitize_email( $_POST['_customer_email'] ) );
            }

            if ( isset( $_POST['_customer_phone'] ) ) {
                update_post_meta( $post_id, '_customer_phone', sanitize_text_field( $_POST['_customer_phone'] ) );
            }

            if ( isset( $_POST['_customer_address'] ) ) {
                update_post_meta( $post_id, '_customer_address', sanitize_text_field( $_POST['_customer_address'] ) );
            }

            if ( isset( $_POST['_customer_city'] ) ) {
                update_post_meta( $post_id, '_customer_city', sanitize_text_field( $_POST['_customer_city'] ) );
            }

            if ( isset( $_POST['_customer_state'] ) ) {
                update_post_meta( $post_id, '_customer_state', sanitize_text_field( $_POST['_customer_state'] ) );
            }

            if ( isset( $_POST['_customer_zip'] ) ) {
                update_post_meta( $post_id, '_customer_zip', sanitize_text_field( $_POST['_customer_zip'] ) );
            }

            if ( isset( $_POST['_customer_country'] ) ) {
                update_post_meta( $post_id, '_customer_country', sanitize_text_field( $_POST['_customer_country'] ) );
            }

            if ( isset( $_POST['_customer_notes'] ) ) {
                update_post_meta( $post_id, '_customer_notes', sanitize_textarea_field( $_POST['_customer_notes'] ) );
            }

            // Save booking notes
            if ( isset( $_POST['_booking_notes'] ) ) {
                update_post_meta( $post_id, '_booking_notes', sanitize_textarea_field( $_POST['_booking_notes'] ) );
            }
        }

        // Save service details
        if ( isset( $_POST['post_type'] ) && 'aqualuxe_service' === $_POST['post_type'] ) {
            // Save service details
            if ( isset( $_POST['_duration'] ) ) {
                update_post_meta( $post_id, '_duration', sanitize_text_field( $_POST['_duration'] ) );
            }

            if ( isset( $_POST['_buffer_before'] ) ) {
                update_post_meta( $post_id, '_buffer_before', sanitize_text_field( $_POST['_buffer_before'] ) );
            }

            if ( isset( $_POST['_buffer_after'] ) ) {
                update_post_meta( $post_id, '_buffer_after', sanitize_text_field( $_POST['_buffer_after'] ) );
            }

            if ( isset( $_POST['_capacity'] ) ) {
                update_post_meta( $post_id, '_capacity', sanitize_text_field( $_POST['_capacity'] ) );
            }

            if ( isset( $_POST['_location'] ) ) {
                update_post_meta( $post_id, '_location', sanitize_text_field( $_POST['_location'] ) );
            }

            // Save service availability
            if ( isset( $_POST['_availability'] ) ) {
                update_post_meta( $post_id, '_availability', $_POST['_availability'] );
            }

            // Save service pricing
            if ( isset( $_POST['_price'] ) ) {
                update_post_meta( $post_id, '_price', sanitize_text_field( $_POST['_price'] ) );
            }

            if ( isset( $_POST['_sale_price'] ) ) {
                update_post_meta( $post_id, '_sale_price', sanitize_text_field( $_POST['_sale_price'] ) );
            }

            if ( isset( $_POST['_deposit'] ) ) {
                update_post_meta( $post_id, '_deposit', sanitize_text_field( $_POST['_deposit'] ) );
            }

            if ( isset( $_POST['_deposit_type'] ) ) {
                update_post_meta( $post_id, '_deposit_type', sanitize_text_field( $_POST['_deposit_type'] ) );
            }

            if ( isset( $_POST['_tax_class'] ) ) {
                update_post_meta( $post_id, '_tax_class', sanitize_text_field( $_POST['_tax_class'] ) );
            }

            if ( isset( $_POST['_wc_product_id'] ) ) {
                update_post_meta( $post_id, '_wc_product_id', sanitize_text_field( $_POST['_wc_product_id'] ) );
            }
        }
    }

    /**
     * Register settings
     *
     * @return void
     */
    public function register_settings() {
        // Register settings
        register_setting( 'aqualuxe_bookings', 'aqualuxe_bookings_settings' );

        // Add settings sections
        add_settings_section(
            'aqualuxe_bookings_general',
            esc_html__( 'General Settings', 'aqualuxe' ),
            [ $this, 'general_settings_section' ],
            'aqualuxe_bookings'
        );

        add_settings_section(
            'aqualuxe_bookings_availability',
            esc_html__( 'Availability Settings', 'aqualuxe' ),
            [ $this, 'availability_settings_section' ],
            'aqualuxe_bookings'
        );

        add_settings_section(
            'aqualuxe_bookings_notifications',
            esc_html__( 'Notification Settings', 'aqualuxe' ),
            [ $this, 'notification_settings_section' ],
            'aqualuxe_bookings'
        );

        add_settings_section(
            'aqualuxe_bookings_woocommerce',
            esc_html__( 'WooCommerce Settings', 'aqualuxe' ),
            [ $this, 'woocommerce_settings_section' ],
            'aqualuxe_bookings'
        );

        // Add settings fields
        add_settings_field(
            'booking_page',
            esc_html__( 'Booking Page', 'aqualuxe' ),
            [ $this, 'booking_page_field' ],
            'aqualuxe_bookings',
            'aqualuxe_bookings_general'
        );

        add_settings_field(
            'services_page',
            esc_html__( 'Services Page', 'aqualuxe' ),
            [ $this, 'services_page_field' ],
            'aqualuxe_bookings',
            'aqualuxe_bookings_general'
        );

        add_settings_field(
            'calendar_page',
            esc_html__( 'Calendar Page', 'aqualuxe' ),
            [ $this, 'calendar_page_field' ],
            'aqualuxe_bookings',
            'aqualuxe_bookings_general'
        );

        add_settings_field(
            'minimum_notice',
            esc_html__( 'Minimum Notice', 'aqualuxe' ),
            [ $this, 'minimum_notice_field' ],
            'aqualuxe_bookings',
            'aqualuxe_bookings_availability'
        );

        add_settings_field(
            'maximum_notice',
            esc_html__( 'Maximum Notice', 'aqualuxe' ),
            [ $this, 'maximum_notice_field' ],
            'aqualuxe_bookings',
            'aqualuxe_bookings_availability'
        );

        add_settings_field(
            'time_format',
            esc_html__( 'Time Format', 'aqualuxe' ),
            [ $this, 'time_format_field' ],
            'aqualuxe_bookings',
            'aqualuxe_bookings_availability'
        );

        add_settings_field(
            'date_format',
            esc_html__( 'Date Format', 'aqualuxe' ),
            [ $this, 'date_format_field' ],
            'aqualuxe_bookings',
            'aqualuxe_bookings_availability'
        );

        add_settings_field(
            'admin_notification',
            esc_html__( 'Admin Notification', 'aqualuxe' ),
            [ $this, 'admin_notification_field' ],
            'aqualuxe_bookings',
            'aqualuxe_bookings_notifications'
        );

        add_settings_field(
            'customer_notification',
            esc_html__( 'Customer Notification', 'aqualuxe' ),
            [ $this, 'customer_notification_field' ],
            'aqualuxe_bookings',
            'aqualuxe_bookings_notifications'
        );

        add_settings_field(
            'reminder_notification',
            esc_html__( 'Reminder Notification', 'aqualuxe' ),
            [ $this, 'reminder_notification_field' ],
            'aqualuxe_bookings',
            'aqualuxe_bookings_notifications'
        );

        add_settings_field(
            'woocommerce_integration',
            esc_html__( 'WooCommerce Integration', 'aqualuxe' ),
            [ $this, 'woocommerce_integration_field' ],
            'aqualuxe_bookings',
            'aqualuxe_bookings_woocommerce'
        );

        add_settings_field(
            'woocommerce_product_type',
            esc_html__( 'WooCommerce Product Type', 'aqualuxe' ),
            [ $this, 'woocommerce_product_type_field' ],
            'aqualuxe_bookings',
            'aqualuxe_bookings_woocommerce'
        );

        add_settings_field(
            'woocommerce_checkout',
            esc_html__( 'WooCommerce Checkout', 'aqualuxe' ),
            [ $this, 'woocommerce_checkout_field' ],
            'aqualuxe_bookings',
            'aqualuxe_bookings_woocommerce'
        );
    }

    /**
     * General settings section
     *
     * @return void
     */
    public function general_settings_section() {
        echo '<p>' . esc_html__( 'General settings for the bookings module.', 'aqualuxe' ) . '</p>';
    }

    /**
     * Availability settings section
     *
     * @return void
     */
    public function availability_settings_section() {
        echo '<p>' . esc_html__( 'Settings for service availability and booking rules.', 'aqualuxe' ) . '</p>';
    }

    /**
     * Notification settings section
     *
     * @return void
     */
    public function notification_settings_section() {
        echo '<p>' . esc_html__( 'Settings for booking notifications and reminders.', 'aqualuxe' ) . '</p>';
    }

    /**
     * WooCommerce settings section
     *
     * @return void
     */
    public function woocommerce_settings_section() {
        echo '<p>' . esc_html__( 'Settings for WooCommerce integration.', 'aqualuxe' ) . '</p>';
    }

    /**
     * Booking page field
     *
     * @return void
     */
    public function booking_page_field() {
        $settings = get_option( 'aqualuxe_bookings_settings', [] );
        $booking_page = isset( $settings['booking_page'] ) ? $settings['booking_page'] : '';

        wp_dropdown_pages(
            [
                'name'              => 'aqualuxe_bookings_settings[booking_page]',
                'selected'          => $booking_page,
                'show_option_none'  => esc_html__( 'Select a page', 'aqualuxe' ),
                'option_none_value' => '',
            ]
        );

        echo '<p class="description">' . esc_html__( 'Select the page where the booking form will be displayed.', 'aqualuxe' ) . '</p>';
    }

    /**
     * Services page field
     *
     * @return void
     */
    public function services_page_field() {
        $settings = get_option( 'aqualuxe_bookings_settings', [] );
        $services_page = isset( $settings['services_page'] ) ? $settings['services_page'] : '';

        wp_dropdown_pages(
            [
                'name'              => 'aqualuxe_bookings_settings[services_page]',
                'selected'          => $services_page,
                'show_option_none'  => esc_html__( 'Select a page', 'aqualuxe' ),
                'option_none_value' => '',
            ]
        );

        echo '<p class="description">' . esc_html__( 'Select the page where the services will be displayed.', 'aqualuxe' ) . '</p>';
    }

    /**
     * Calendar page field
     *
     * @return void
     */
    public function calendar_page_field() {
        $settings = get_option( 'aqualuxe_bookings_settings', [] );
        $calendar_page = isset( $settings['calendar_page'] ) ? $settings['calendar_page'] : '';

        wp_dropdown_pages(
            [
                'name'              => 'aqualuxe_bookings_settings[calendar_page]',
                'selected'          => $calendar_page,
                'show_option_none'  => esc_html__( 'Select a page', 'aqualuxe' ),
                'option_none_value' => '',
            ]
        );

        echo '<p class="description">' . esc_html__( 'Select the page where the calendar will be displayed.', 'aqualuxe' ) . '</p>';
    }

    /**
     * Minimum notice field
     *
     * @return void
     */
    public function minimum_notice_field() {
        $settings = get_option( 'aqualuxe_bookings_settings', [] );
        $minimum_notice = isset( $settings['minimum_notice'] ) ? $settings['minimum_notice'] : 24;

        echo '<input type="number" name="aqualuxe_bookings_settings[minimum_notice]" value="' . esc_attr( $minimum_notice ) . '" class="small-text" /> ' . esc_html__( 'hours', 'aqualuxe' );
        echo '<p class="description">' . esc_html__( 'Minimum notice required for bookings (in hours).', 'aqualuxe' ) . '</p>';
    }

    /**
     * Maximum notice field
     *
     * @return void
     */
    public function maximum_notice_field() {
        $settings = get_option( 'aqualuxe_bookings_settings', [] );
        $maximum_notice = isset( $settings['maximum_notice'] ) ? $settings['maximum_notice'] : 90;

        echo '<input type="number" name="aqualuxe_bookings_settings[maximum_notice]" value="' . esc_attr( $maximum_notice ) . '" class="small-text" /> ' . esc_html__( 'days', 'aqualuxe' );
        echo '<p class="description">' . esc_html__( 'Maximum notice allowed for bookings (in days).', 'aqualuxe' ) . '</p>';
    }

    /**
     * Time format field
     *
     * @return void
     */
    public function time_format_field() {
        $settings = get_option( 'aqualuxe_bookings_settings', [] );
        $time_format = isset( $settings['time_format'] ) ? $settings['time_format'] : '12';

        echo '<select name="aqualuxe_bookings_settings[time_format]">';
        echo '<option value="12" ' . selected( $time_format, '12', false ) . '>' . esc_html__( '12-hour format (e.g., 1:00 PM)', 'aqualuxe' ) . '</option>';
        echo '<option value="24" ' . selected( $time_format, '24', false ) . '>' . esc_html__( '24-hour format (e.g., 13:00)', 'aqualuxe' ) . '</option>';
        echo '</select>';
        echo '<p class="description">' . esc_html__( 'Select the time format for displaying booking times.', 'aqualuxe' ) . '</p>';
    }

    /**
     * Date format field
     *
     * @return void
     */
    public function date_format_field() {
        $settings = get_option( 'aqualuxe_bookings_settings', [] );
        $date_format = isset( $settings['date_format'] ) ? $settings['date_format'] : 'mm/dd/yyyy';

        echo '<select name="aqualuxe_bookings_settings[date_format]">';
        echo '<option value="mm/dd/yyyy" ' . selected( $date_format, 'mm/dd/yyyy', false ) . '>' . esc_html__( 'MM/DD/YYYY (e.g., 12/31/2023)', 'aqualuxe' ) . '</option>';
        echo '<option value="dd/mm/yyyy" ' . selected( $date_format, 'dd/mm/yyyy', false ) . '>' . esc_html__( 'DD/MM/YYYY (e.g., 31/12/2023)', 'aqualuxe' ) . '</option>';
        echo '<option value="yyyy-mm-dd" ' . selected( $date_format, 'yyyy-mm-dd', false ) . '>' . esc_html__( 'YYYY-MM-DD (e.g., 2023-12-31)', 'aqualuxe' ) . '</option>';
        echo '</select>';
        echo '<p class="description">' . esc_html__( 'Select the date format for displaying booking dates.', 'aqualuxe' ) . '</p>';
    }

    /**
     * Admin notification field
     *
     * @return void
     */
    public function admin_notification_field() {
        $settings = get_option( 'aqualuxe_bookings_settings', [] );
        $admin_notification = isset( $settings['admin_notification'] ) ? $settings['admin_notification'] : true;

        echo '<input type="checkbox" name="aqualuxe_bookings_settings[admin_notification]" value="1" ' . checked( $admin_notification, true, false ) . ' />';
        echo '<p class="description">' . esc_html__( 'Send email notifications to admin when a new booking is made.', 'aqualuxe' ) . '</p>';
    }

    /**
     * Customer notification field
     *
     * @return void
     */
    public function customer_notification_field() {
        $settings = get_option( 'aqualuxe_bookings_settings', [] );
        $customer_notification = isset( $settings['customer_notification'] ) ? $settings['customer_notification'] : true;

        echo '<input type="checkbox" name="aqualuxe_bookings_settings[customer_notification]" value="1" ' . checked( $customer_notification, true, false ) . ' />';
        echo '<p class="description">' . esc_html__( 'Send email notifications to customers when a booking is made or updated.', 'aqualuxe' ) . '</p>';
    }

    /**
     * Reminder notification field
     *
     * @return void
     */
    public function reminder_notification_field() {
        $settings = get_option( 'aqualuxe_bookings_settings', [] );
        $reminder_notification = isset( $settings['reminder_notification'] ) ? $settings['reminder_notification'] : true;
        $reminder_time = isset( $settings['reminder_time'] ) ? $settings['reminder_time'] : 24;

        echo '<input type="checkbox" name="aqualuxe_bookings_settings[reminder_notification]" value="1" ' . checked( $reminder_notification, true, false ) . ' />';
        echo ' <input type="number" name="aqualuxe_bookings_settings[reminder_time]" value="' . esc_attr( $reminder_time ) . '" class="small-text" /> ' . esc_html__( 'hours before appointment', 'aqualuxe' );
        echo '<p class="description">' . esc_html__( 'Send reminder email notifications to customers before their appointment.', 'aqualuxe' ) . '</p>';
    }

    /**
     * WooCommerce integration field
     *
     * @return void
     */
    public function woocommerce_integration_field() {
        $settings = get_option( 'aqualuxe_bookings_settings', [] );
        $woocommerce_integration = isset( $settings['woocommerce_integration'] ) ? $settings['woocommerce_integration'] : false;

        echo '<input type="checkbox" name="aqualuxe_bookings_settings[woocommerce_integration]" value="1" ' . checked( $woocommerce_integration, true, false ) . ' />';
        echo '<p class="description">' . esc_html__( 'Enable WooCommerce integration for bookings.', 'aqualuxe' ) . '</p>';
    }

    /**
     * WooCommerce product type field
     *
     * @return void
     */
    public function woocommerce_product_type_field() {
        $settings = get_option( 'aqualuxe_bookings_settings', [] );
        $woocommerce_product_type = isset( $settings['woocommerce_product_type'] ) ? $settings['woocommerce_product_type'] : 'simple';

        echo '<select name="aqualuxe_bookings_settings[woocommerce_product_type]">';
        echo '<option value="simple" ' . selected( $woocommerce_product_type, 'simple', false ) . '>' . esc_html__( 'Simple Product', 'aqualuxe' ) . '</option>';
        echo '<option value="variable" ' . selected( $woocommerce_product_type, 'variable', false ) . '>' . esc_html__( 'Variable Product', 'aqualuxe' ) . '</option>';
        echo '</select>';
        echo '<p class="description">' . esc_html__( 'Select the WooCommerce product type for services.', 'aqualuxe' ) . '</p>';
    }

    /**
     * WooCommerce checkout field
     *
     * @return void
     */
    public function woocommerce_checkout_field() {
        $settings = get_option( 'aqualuxe_bookings_settings', [] );
        $woocommerce_checkout = isset( $settings['woocommerce_checkout'] ) ? $settings['woocommerce_checkout'] : 'standard';

        echo '<select name="aqualuxe_bookings_settings[woocommerce_checkout]">';
        echo '<option value="standard" ' . selected( $woocommerce_checkout, 'standard', false ) . '>' . esc_html__( 'Standard Checkout', 'aqualuxe' ) . '</option>';
        echo '<option value="custom" ' . selected( $woocommerce_checkout, 'custom', false ) . '>' . esc_html__( 'Custom Booking Checkout', 'aqualuxe' ) . '</option>';
        echo '</select>';
        echo '<p class="description">' . esc_html__( 'Select the checkout process for bookings with WooCommerce.', 'aqualuxe' ) . '</p>';
    }

    /**
     * Add dashboard widgets
     *
     * @return void
     */
    public function add_dashboard_widgets() {
        // Add upcoming bookings widget
        wp_add_dashboard_widget(
            'aqualuxe_upcoming_bookings',
            esc_html__( 'Upcoming Bookings', 'aqualuxe' ),
            [ $this, 'upcoming_bookings_widget' ]
        );

        // Add booking statistics widget
        wp_add_dashboard_widget(
            'aqualuxe_booking_statistics',
            esc_html__( 'Booking Statistics', 'aqualuxe' ),
            [ $this, 'booking_statistics_widget' ]
        );
    }

    /**
     * Upcoming bookings widget
     *
     * @return void
     */
    public function upcoming_bookings_widget() {
        // Get upcoming bookings
        $args = [
            'post_type'      => 'aqualuxe_booking',
            'posts_per_page' => 5,
            'meta_key'       => '_date',
            'orderby'        => 'meta_value',
            'order'          => 'ASC',
            'meta_query'     => [
                [
                    'key'     => '_date',
                    'value'   => date( 'Y-m-d' ),
                    'compare' => '>=',
                    'type'    => 'DATE',
                ],
            ],
            'tax_query'      => [
                [
                    'taxonomy' => 'aqualuxe_booking_status',
                    'field'    => 'slug',
                    'terms'    => [ 'confirmed' ],
                ],
            ],
        ];

        $bookings = new \WP_Query( $args );

        // Display upcoming bookings
        if ( $bookings->have_posts() ) {
            echo '<table class="wp-list-table widefat fixed striped">';
            echo '<thead><tr>';
            echo '<th>' . esc_html__( 'Date', 'aqualuxe' ) . '</th>';
            echo '<th>' . esc_html__( 'Time', 'aqualuxe' ) . '</th>';
            echo '<th>' . esc_html__( 'Service', 'aqualuxe' ) . '</th>';
            echo '<th>' . esc_html__( 'Customer', 'aqualuxe' ) . '</th>';
            echo '</tr></thead>';
            echo '<tbody>';

            while ( $bookings->have_posts() ) {
                $bookings->the_post();
                $booking_id = get_the_ID();
                $date = get_post_meta( $booking_id, '_date', true );
                $time = get_post_meta( $booking_id, '_time', true );
                $service_id = get_post_meta( $booking_id, '_service_id', true );
                $service_title = get_the_title( $service_id );
                $customer_name = get_post_meta( $booking_id, '_customer_name', true );

                echo '<tr>';
                echo '<td>' . esc_html( $date ) . '</td>';
                echo '<td>' . esc_html( $time ) . '</td>';
                echo '<td>' . esc_html( $service_title ) . '</td>';
                echo '<td>' . esc_html( $customer_name ) . '</td>';
                echo '</tr>';
            }

            echo '</tbody></table>';
            echo '<p><a href="' . esc_url( admin_url( 'edit.php?post_type=aqualuxe_booking' ) ) . '">' . esc_html__( 'View All Bookings', 'aqualuxe' ) . '</a></p>';
        } else {
            echo '<p>' . esc_html__( 'No upcoming bookings.', 'aqualuxe' ) . '</p>';
        }

        wp_reset_postdata();
    }

    /**
     * Booking statistics widget
     *
     * @return void
     */
    public function booking_statistics_widget() {
        // Get booking statistics
        $total_bookings = wp_count_posts( 'aqualuxe_booking' )->publish;
        $pending_bookings = $this->count_bookings_by_status( 'pending' );
        $confirmed_bookings = $this->count_bookings_by_status( 'confirmed' );
        $completed_bookings = $this->count_bookings_by_status( 'completed' );
        $cancelled_bookings = $this->count_bookings_by_status( 'cancelled' );

        // Display booking statistics
        echo '<div class="booking-statistics">';
        echo '<div class="stat-item"><span class="stat-label">' . esc_html__( 'Total Bookings', 'aqualuxe' ) . ':</span> <span class="stat-value">' . esc_html( $total_bookings ) . '</span></div>';
        echo '<div class="stat-item"><span class="stat-label">' . esc_html__( 'Pending Bookings', 'aqualuxe' ) . ':</span> <span class="stat-value">' . esc_html( $pending_bookings ) . '</span></div>';
        echo '<div class="stat-item"><span class="stat-label">' . esc_html__( 'Confirmed Bookings', 'aqualuxe' ) . ':</span> <span class="stat-value">' . esc_html( $confirmed_bookings ) . '</span></div>';
        echo '<div class="stat-item"><span class="stat-label">' . esc_html__( 'Completed Bookings', 'aqualuxe' ) . ':</span> <span class="stat-value">' . esc_html( $completed_bookings ) . '</span></div>';
        echo '<div class="stat-item"><span class="stat-label">' . esc_html__( 'Cancelled Bookings', 'aqualuxe' ) . ':</span> <span class="stat-value">' . esc_html( $cancelled_bookings ) . '</span></div>';
        echo '</div>';
    }

    /**
     * Count bookings by status
     *
     * @param string $status Booking status.
     * @return int
     */
    private function count_bookings_by_status( $status ) {
        $args = [
            'post_type'      => 'aqualuxe_booking',
            'posts_per_page' => -1,
            'tax_query'      => [
                [
                    'taxonomy' => 'aqualuxe_booking_status',
                    'field'    => 'slug',
                    'terms'    => $status,
                ],
            ],
        ];

        $bookings = new \WP_Query( $args );
        return $bookings->found_posts;
    }

    /**
     * Add custom columns to admin list
     *
     * @param array $columns Admin columns.
     * @return array
     */
    public function add_custom_columns( $columns ) {
        $new_columns = [];

        foreach ( $columns as $key => $value ) {
            if ( $key === 'title' ) {
                $new_columns[ $key ] = $value;
                $new_columns['service'] = esc_html__( 'Service', 'aqualuxe' );
                $new_columns['date_time'] = esc_html__( 'Date & Time', 'aqualuxe' );
                $new_columns['customer'] = esc_html__( 'Customer', 'aqualuxe' );
                $new_columns['status'] = esc_html__( 'Status', 'aqualuxe' );
            } else {
                $new_columns[ $key ] = $value;
            }
        }

        return $new_columns;
    }

    /**
     * Custom column content
     *
     * @param string $column Column name.
     * @param int    $post_id Post ID.
     * @return void
     */
    public function custom_column_content( $column, $post_id ) {
        switch ( $column ) {
            case 'service':
                $service_id = get_post_meta( $post_id, '_service_id', true );
                $service_title = get_the_title( $service_id );
                echo esc_html( $service_title );
                break;

            case 'date_time':
                $date = get_post_meta( $post_id, '_date', true );
                $time = get_post_meta( $post_id, '_time', true );
                echo esc_html( $date ) . ' ' . esc_html( $time );
                break;

            case 'customer':
                $customer_name = get_post_meta( $post_id, '_customer_name', true );
                $customer_email = get_post_meta( $post_id, '_customer_email', true );
                echo esc_html( $customer_name ) . '<br>' . esc_html( $customer_email );
                break;

            case 'status':
                $status = wp_get_post_terms( $post_id, 'aqualuxe_booking_status', [ 'fields' => 'slugs' ] );
                $status = ! empty( $status ) ? $status[0] : 'pending';
                $status_label = ucfirst( $status );
                $status_class = 'status-' . $status;
                echo '<span class="booking-status ' . esc_attr( $status_class ) . '">' . esc_html( $status_label ) . '</span>';
                break;
        }
    }

    /**
     * Add custom filters to admin list
     *
     * @param string $post_type Post type.
     * @return void
     */
    public function add_custom_filters( $post_type ) {
        if ( 'aqualuxe_booking' !== $post_type ) {
            return;
        }

        // Add service filter
        $services = get_posts(
            [
                'post_type'      => 'aqualuxe_service',
                'posts_per_page' => -1,
                'orderby'        => 'title',
                'order'          => 'ASC',
            ]
        );

        $current_service = isset( $_GET['service_id'] ) ? absint( $_GET['service_id'] ) : 0;

        echo '<select name="service_id">';
        echo '<option value="0">' . esc_html__( 'All Services', 'aqualuxe' ) . '</option>';

        foreach ( $services as $service ) {
            echo '<option value="' . esc_attr( $service->ID ) . '" ' . selected( $current_service, $service->ID, false ) . '>' . esc_html( $service->post_title ) . '</option>';
        }

        echo '</select>';

        // Add date filter
        $current_date = isset( $_GET['booking_date'] ) ? sanitize_text_field( $_GET['booking_date'] ) : '';

        echo '<input type="date" name="booking_date" value="' . esc_attr( $current_date ) . '" placeholder="' . esc_attr__( 'Filter by date', 'aqualuxe' ) . '" />';
    }

    /**
     * Add custom bulk actions
     *
     * @param array $actions Bulk actions.
     * @return array
     */
    public function add_custom_bulk_actions( $actions ) {
        $actions['confirm'] = esc_html__( 'Confirm Bookings', 'aqualuxe' );
        $actions['cancel'] = esc_html__( 'Cancel Bookings', 'aqualuxe' );
        $actions['complete'] = esc_html__( 'Complete Bookings', 'aqualuxe' );
        return $actions;
    }

    /**
     * Handle custom bulk actions
     *
     * @param string $redirect_to Redirect URL.
     * @param string $action Action.
     * @param array  $post_ids Post IDs.
     * @return string
     */
    public function handle_custom_bulk_actions( $redirect_to, $action, $post_ids ) {
        if ( 'confirm' === $action ) {
            foreach ( $post_ids as $post_id ) {
                wp_set_object_terms( $post_id, 'confirmed', 'aqualuxe_booking_status' );
            }
            $redirect_to = add_query_arg( 'bulk_confirmed', count( $post_ids ), $redirect_to );
        } elseif ( 'cancel' === $action ) {
            foreach ( $post_ids as $post_id ) {
                wp_set_object_terms( $post_id, 'cancelled', 'aqualuxe_booking_status' );
            }
            $redirect_to = add_query_arg( 'bulk_cancelled', count( $post_ids ), $redirect_to );
        } elseif ( 'complete' === $action ) {
            foreach ( $post_ids as $post_id ) {
                wp_set_object_terms( $post_id, 'completed', 'aqualuxe_booking_status' );
            }
            $redirect_to = add_query_arg( 'bulk_completed', count( $post_ids ), $redirect_to );
        }

        return $redirect_to;
    }

    /**
     * Admin notices
     *
     * @return void
     */
    public function admin_notices() {
        if ( ! empty( $_REQUEST['bulk_confirmed'] ) ) {
            $count = intval( $_REQUEST['bulk_confirmed'] );
            printf(
                '<div class="updated notice is-dismissible"><p>' .
                _n(
                    '%s booking confirmed.',
                    '%s bookings confirmed.',
                    $count,
                    'aqualuxe'
                ) . '</p></div>',
                esc_html( $count )
            );
        }

        if ( ! empty( $_REQUEST['bulk_cancelled'] ) ) {
            $count = intval( $_REQUEST['bulk_cancelled'] );
            printf(
                '<div class="updated notice is-dismissible"><p>' .
                _n(
                    '%s booking cancelled.',
                    '%s bookings cancelled.',
                    $count,
                    'aqualuxe'
                ) . '</p></div>',
                esc_html( $count )
            );
        }

        if ( ! empty( $_REQUEST['bulk_completed'] ) ) {
            $count = intval( $_REQUEST['bulk_completed'] );
            printf(
                '<div class="updated notice is-dismissible"><p>' .
                _n(
                    '%s booking completed.',
                    '%s bookings completed.',
                    $count,
                    'aqualuxe'
                ) . '</p></div>',
                esc_html( $count )
            );
        }
    }

    /**
     * Initialize WooCommerce integration
     *
     * @return void
     */
    private function init_woocommerce_integration() {
        // Check if WooCommerce integration is enabled
        $settings = get_option( 'aqualuxe_bookings_settings', [] );
        $woocommerce_integration = isset( $settings['woocommerce_integration'] ) ? $settings['woocommerce_integration'] : false;

        if ( ! $woocommerce_integration ) {
            return;
        }

        // Initialize WooCommerce integration
        $woocommerce = new WooCommerce();
    }

    /**
     * Get module ID
     *
     * @return string
     */
    public function get_module_id() {
        return $this->module_id;
    }

    /**
     * Get module name
     *
     * @return string
     */
    public function get_module_name() {
        return $this->module_name;
    }

    /**
     * Get module description
     *
     * @return string
     */
    public function get_module_description() {
        return $this->module_description;
    }

    /**
     * Get module version
     *
     * @return string
     */
    public function get_module_version() {
        return $this->module_version;
    }

    /**
     * Get module path
     *
     * @return string
     */
    public function get_module_path() {
        return $this->module_path;
    }

    /**
     * Get module URL
     *
     * @return string
     */
    public function get_module_url() {
        return $this->module_url;
    }
}