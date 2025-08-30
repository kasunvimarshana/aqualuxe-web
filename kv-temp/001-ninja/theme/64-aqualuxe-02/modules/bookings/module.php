<?php
/**
 * AquaLuxe Bookings Module
 *
 * @package AquaLuxe
 * @subpackage Bookings
 * @since 1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * AquaLuxe Bookings Module Class
 */
class AquaLuxe_Module_Bookings {
    /**
     * Module version
     *
     * @var string
     */
    const VERSION = '1.0.0';

    /**
     * Module directory path
     *
     * @var string
     */
    private $dir_path;

    /**
     * Module directory URL
     *
     * @var string
     */
    private $dir_url;

    /**
     * Constructor
     */
    public function __construct() {
        $this->dir_path = trailingslashit( dirname( __FILE__ ) );
        $this->dir_url = trailingslashit( AQUALUXE_URI . 'modules/bookings' );

        // Load module files
        $this->load_files();

        // Register post types and taxonomies
        add_action( 'init', array( $this, 'register_post_types' ) );
        add_action( 'init', array( $this, 'register_taxonomies' ) );

        // Register assets
        add_action( 'wp_enqueue_scripts', array( $this, 'register_assets' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_assets' ) );

        // Add shortcodes
        add_action( 'init', array( $this, 'register_shortcodes' ) );

        // Add WooCommerce integration
        add_action( 'woocommerce_loaded', array( $this, 'woocommerce_integration' ) );

        // Add admin menu
        add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );

        // Add settings
        add_action( 'admin_init', array( $this, 'register_settings' ) );

        // Add AJAX handlers
        add_action( 'wp_ajax_aqualuxe_booking_check_availability', array( $this, 'ajax_check_availability' ) );
        add_action( 'wp_ajax_nopriv_aqualuxe_booking_check_availability', array( $this, 'ajax_check_availability' ) );
        add_action( 'wp_ajax_aqualuxe_booking_create', array( $this, 'ajax_create_booking' ) );
        add_action( 'wp_ajax_nopriv_aqualuxe_booking_create', array( $this, 'ajax_create_booking' ) );

        // Add booking data to WooCommerce order
        add_action( 'woocommerce_checkout_create_order_line_item', array( $this, 'add_booking_data_to_order_item' ), 10, 4 );

        // Add booking confirmation after order completion
        add_action( 'woocommerce_order_status_completed', array( $this, 'confirm_booking_on_order_complete' ) );
        
        // Add booking cancellation on order cancellation
        add_action( 'woocommerce_order_status_cancelled', array( $this, 'cancel_booking_on_order_cancel' ) );
    }

    /**
     * Load module files
     */
    private function load_files() {
        // Include module files
        require_once $this->dir_path . 'inc/class-aqualuxe-booking.php';
        require_once $this->dir_path . 'inc/class-aqualuxe-booking-service.php';
        require_once $this->dir_path . 'inc/class-aqualuxe-booking-resource.php';
        require_once $this->dir_path . 'inc/class-aqualuxe-booking-availability.php';
        require_once $this->dir_path . 'inc/class-aqualuxe-booking-calendar.php';
        require_once $this->dir_path . 'inc/functions-bookings.php';
        require_once $this->dir_path . 'inc/shortcodes.php';
        
        // Admin files
        if ( is_admin() ) {
            require_once $this->dir_path . 'inc/admin/class-aqualuxe-booking-admin.php';
            require_once $this->dir_path . 'inc/admin/class-aqualuxe-booking-meta-boxes.php';
            require_once $this->dir_path . 'inc/admin/class-aqualuxe-booking-settings.php';
        }
    }

    /**
     * Register post types
     */
    public function register_post_types() {
        // Register booking post type
        register_post_type( 'aqualuxe_booking', array(
            'labels' => array(
                'name'               => _x( 'Bookings', 'post type general name', 'aqualuxe' ),
                'singular_name'      => _x( 'Booking', 'post type singular name', 'aqualuxe' ),
                'menu_name'          => _x( 'Bookings', 'admin menu', 'aqualuxe' ),
                'name_admin_bar'     => _x( 'Booking', 'add new on admin bar', 'aqualuxe' ),
                'add_new'            => _x( 'Add New', 'booking', 'aqualuxe' ),
                'add_new_item'       => __( 'Add New Booking', 'aqualuxe' ),
                'new_item'           => __( 'New Booking', 'aqualuxe' ),
                'edit_item'          => __( 'Edit Booking', 'aqualuxe' ),
                'view_item'          => __( 'View Booking', 'aqualuxe' ),
                'all_items'          => __( 'All Bookings', 'aqualuxe' ),
                'search_items'       => __( 'Search Bookings', 'aqualuxe' ),
                'parent_item_colon'  => __( 'Parent Bookings:', 'aqualuxe' ),
                'not_found'          => __( 'No bookings found.', 'aqualuxe' ),
                'not_found_in_trash' => __( 'No bookings found in Trash.', 'aqualuxe' ),
            ),
            'description'         => __( 'Bookings for services and resources.', 'aqualuxe' ),
            'public'              => false,
            'publicly_queryable'  => false,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'query_var'           => true,
            'rewrite'             => array( 'slug' => 'booking' ),
            'capability_type'     => 'post',
            'has_archive'         => false,
            'hierarchical'        => false,
            'menu_position'       => null,
            'menu_icon'           => 'dashicons-calendar-alt',
            'supports'            => array( 'title', 'custom-fields' ),
        ) );

        // Register service post type
        register_post_type( 'aqualuxe_service', array(
            'labels' => array(
                'name'               => _x( 'Services', 'post type general name', 'aqualuxe' ),
                'singular_name'      => _x( 'Service', 'post type singular name', 'aqualuxe' ),
                'menu_name'          => _x( 'Services', 'admin menu', 'aqualuxe' ),
                'name_admin_bar'     => _x( 'Service', 'add new on admin bar', 'aqualuxe' ),
                'add_new'            => _x( 'Add New', 'service', 'aqualuxe' ),
                'add_new_item'       => __( 'Add New Service', 'aqualuxe' ),
                'new_item'           => __( 'New Service', 'aqualuxe' ),
                'edit_item'          => __( 'Edit Service', 'aqualuxe' ),
                'view_item'          => __( 'View Service', 'aqualuxe' ),
                'all_items'          => __( 'All Services', 'aqualuxe' ),
                'search_items'       => __( 'Search Services', 'aqualuxe' ),
                'parent_item_colon'  => __( 'Parent Services:', 'aqualuxe' ),
                'not_found'          => __( 'No services found.', 'aqualuxe' ),
                'not_found_in_trash' => __( 'No services found in Trash.', 'aqualuxe' ),
            ),
            'description'         => __( 'Bookable services.', 'aqualuxe' ),
            'public'              => true,
            'publicly_queryable'  => true,
            'show_ui'             => true,
            'show_in_menu'        => 'edit.php?post_type=aqualuxe_booking',
            'query_var'           => true,
            'rewrite'             => array( 'slug' => 'service' ),
            'capability_type'     => 'post',
            'has_archive'         => true,
            'hierarchical'        => false,
            'menu_position'       => null,
            'supports'            => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ),
        ) );

        // Register resource post type
        register_post_type( 'aqualuxe_resource', array(
            'labels' => array(
                'name'               => _x( 'Resources', 'post type general name', 'aqualuxe' ),
                'singular_name'      => _x( 'Resource', 'post type singular name', 'aqualuxe' ),
                'menu_name'          => _x( 'Resources', 'admin menu', 'aqualuxe' ),
                'name_admin_bar'     => _x( 'Resource', 'add new on admin bar', 'aqualuxe' ),
                'add_new'            => _x( 'Add New', 'resource', 'aqualuxe' ),
                'add_new_item'       => __( 'Add New Resource', 'aqualuxe' ),
                'new_item'           => __( 'New Resource', 'aqualuxe' ),
                'edit_item'          => __( 'Edit Resource', 'aqualuxe' ),
                'view_item'          => __( 'View Resource', 'aqualuxe' ),
                'all_items'          => __( 'All Resources', 'aqualuxe' ),
                'search_items'       => __( 'Search Resources', 'aqualuxe' ),
                'parent_item_colon'  => __( 'Parent Resources:', 'aqualuxe' ),
                'not_found'          => __( 'No resources found.', 'aqualuxe' ),
                'not_found_in_trash' => __( 'No resources found in Trash.', 'aqualuxe' ),
            ),
            'description'         => __( 'Bookable resources.', 'aqualuxe' ),
            'public'              => true,
            'publicly_queryable'  => true,
            'show_ui'             => true,
            'show_in_menu'        => 'edit.php?post_type=aqualuxe_booking',
            'query_var'           => true,
            'rewrite'             => array( 'slug' => 'resource' ),
            'capability_type'     => 'post',
            'has_archive'         => true,
            'hierarchical'        => false,
            'menu_position'       => null,
            'supports'            => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ),
        ) );
    }

    /**
     * Register taxonomies
     */
    public function register_taxonomies() {
        // Register service category taxonomy
        register_taxonomy( 'aqualuxe_service_cat', array( 'aqualuxe_service' ), array(
            'labels' => array(
                'name'              => _x( 'Service Categories', 'taxonomy general name', 'aqualuxe' ),
                'singular_name'     => _x( 'Service Category', 'taxonomy singular name', 'aqualuxe' ),
                'search_items'      => __( 'Search Service Categories', 'aqualuxe' ),
                'all_items'         => __( 'All Service Categories', 'aqualuxe' ),
                'parent_item'       => __( 'Parent Service Category', 'aqualuxe' ),
                'parent_item_colon' => __( 'Parent Service Category:', 'aqualuxe' ),
                'edit_item'         => __( 'Edit Service Category', 'aqualuxe' ),
                'update_item'       => __( 'Update Service Category', 'aqualuxe' ),
                'add_new_item'      => __( 'Add New Service Category', 'aqualuxe' ),
                'new_item_name'     => __( 'New Service Category Name', 'aqualuxe' ),
                'menu_name'         => __( 'Categories', 'aqualuxe' ),
            ),
            'hierarchical'      => true,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array( 'slug' => 'service-category' ),
        ) );

        // Register resource category taxonomy
        register_taxonomy( 'aqualuxe_resource_cat', array( 'aqualuxe_resource' ), array(
            'labels' => array(
                'name'              => _x( 'Resource Categories', 'taxonomy general name', 'aqualuxe' ),
                'singular_name'     => _x( 'Resource Category', 'taxonomy singular name', 'aqualuxe' ),
                'search_items'      => __( 'Search Resource Categories', 'aqualuxe' ),
                'all_items'         => __( 'All Resource Categories', 'aqualuxe' ),
                'parent_item'       => __( 'Parent Resource Category', 'aqualuxe' ),
                'parent_item_colon' => __( 'Parent Resource Category:', 'aqualuxe' ),
                'edit_item'         => __( 'Edit Resource Category', 'aqualuxe' ),
                'update_item'       => __( 'Update Resource Category', 'aqualuxe' ),
                'add_new_item'      => __( 'Add New Resource Category', 'aqualuxe' ),
                'new_item_name'     => __( 'New Resource Category Name', 'aqualuxe' ),
                'menu_name'         => __( 'Categories', 'aqualuxe' ),
            ),
            'hierarchical'      => true,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array( 'slug' => 'resource-category' ),
        ) );

        // Register booking status taxonomy
        register_taxonomy( 'aqualuxe_booking_status', array( 'aqualuxe_booking' ), array(
            'labels' => array(
                'name'              => _x( 'Booking Statuses', 'taxonomy general name', 'aqualuxe' ),
                'singular_name'     => _x( 'Booking Status', 'taxonomy singular name', 'aqualuxe' ),
                'search_items'      => __( 'Search Booking Statuses', 'aqualuxe' ),
                'all_items'         => __( 'All Booking Statuses', 'aqualuxe' ),
                'parent_item'       => __( 'Parent Booking Status', 'aqualuxe' ),
                'parent_item_colon' => __( 'Parent Booking Status:', 'aqualuxe' ),
                'edit_item'         => __( 'Edit Booking Status', 'aqualuxe' ),
                'update_item'       => __( 'Update Booking Status', 'aqualuxe' ),
                'add_new_item'      => __( 'Add New Booking Status', 'aqualuxe' ),
                'new_item_name'     => __( 'New Booking Status Name', 'aqualuxe' ),
                'menu_name'         => __( 'Statuses', 'aqualuxe' ),
            ),
            'hierarchical'      => true,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array( 'slug' => 'booking-status' ),
        ) );

        // Insert default booking statuses
        $this->insert_default_booking_statuses();
    }

    /**
     * Insert default booking statuses
     */
    private function insert_default_booking_statuses() {
        $statuses = array(
            'pending'    => __( 'Pending', 'aqualuxe' ),
            'confirmed'  => __( 'Confirmed', 'aqualuxe' ),
            'completed'  => __( 'Completed', 'aqualuxe' ),
            'cancelled'  => __( 'Cancelled', 'aqualuxe' ),
            'no-show'    => __( 'No Show', 'aqualuxe' ),
        );

        foreach ( $statuses as $slug => $name ) {
            if ( ! term_exists( $slug, 'aqualuxe_booking_status' ) ) {
                wp_insert_term( $name, 'aqualuxe_booking_status', array( 'slug' => $slug ) );
            }
        }
    }

    /**
     * Register assets
     */
    public function register_assets() {
        // Get the asset manager instance
        $assets = AquaLuxe_Assets::instance();
        
        // Register and enqueue styles
        $assets->enqueue_style( 'aqualuxe-bookings', 'modules/bookings/assets/css/bookings.css' );
        
        // Register and enqueue scripts
        $assets->enqueue_script( 'aqualuxe-bookings', 'modules/bookings/assets/js/bookings.js', array( 'jquery', 'jquery-ui-datepicker' ), true );
        
        // Localize script
        wp_localize_script( 'aqualuxe-bookings', 'aqualuxeBookings', array(
            'ajaxUrl'   => admin_url( 'admin-ajax.php' ),
            'nonce'     => wp_create_nonce( 'aqualuxe-bookings' ),
            'i18n'      => array(
                'selectDate'        => __( 'Please select a date', 'aqualuxe' ),
                'selectTime'        => __( 'Please select a time', 'aqualuxe' ),
                'selectService'     => __( 'Please select a service', 'aqualuxe' ),
                'selectResource'    => __( 'Please select a resource', 'aqualuxe' ),
                'bookingSuccess'    => __( 'Booking successful!', 'aqualuxe' ),
                'bookingError'      => __( 'Error creating booking. Please try again.', 'aqualuxe' ),
                'noAvailability'    => __( 'No availability for the selected date and time.', 'aqualuxe' ),
                'loading'           => __( 'Loading...', 'aqualuxe' ),
            ),
            'settings'  => array(
                'dateFormat'        => get_option( 'aqualuxe_bookings_date_format', 'mm/dd/yy' ),
                'timeFormat'        => get_option( 'aqualuxe_bookings_time_format', 'h:i A' ),
                'firstDay'          => get_option( 'aqualuxe_bookings_first_day', 0 ),
                'minDaysAdvance'    => get_option( 'aqualuxe_bookings_min_days_advance', 0 ),
                'maxDaysAdvance'    => get_option( 'aqualuxe_bookings_max_days_advance', 90 ),
            ),
        ) );
    }

    /**
     * Register admin assets
     */
    public function register_admin_assets() {
        // Only load on booking related pages
        $screen = get_current_screen();
        if ( ! $screen || ! in_array( $screen->post_type, array( 'aqualuxe_booking', 'aqualuxe_service', 'aqualuxe_resource' ) ) ) {
            return;
        }

        // Get the asset manager instance
        $assets = AquaLuxe_Assets::instance();
        
        // Register and enqueue styles
        $assets->enqueue_style( 'aqualuxe-bookings-admin', 'modules/bookings/assets/css/admin.css' );
        
        // Register and enqueue scripts
        $assets->enqueue_script( 'aqualuxe-bookings-admin', 'modules/bookings/assets/js/admin.js', array( 'jquery', 'jquery-ui-datepicker', 'jquery-ui-sortable' ), true );
        
        // Localize script
        wp_localize_script( 'aqualuxe-bookings-admin', 'aqualuxeBookingsAdmin', array(
            'ajaxUrl'   => admin_url( 'admin-ajax.php' ),
            'nonce'     => wp_create_nonce( 'aqualuxe-bookings-admin' ),
            'i18n'      => array(
                'confirmDelete'     => __( 'Are you sure you want to delete this item?', 'aqualuxe' ),
                'confirmCancel'     => __( 'Are you sure you want to cancel this booking?', 'aqualuxe' ),
                'confirmComplete'   => __( 'Are you sure you want to mark this booking as completed?', 'aqualuxe' ),
                'confirmNoShow'     => __( 'Are you sure you want to mark this booking as no-show?', 'aqualuxe' ),
                'saved'             => __( 'Saved successfully!', 'aqualuxe' ),
                'error'             => __( 'Error saving data. Please try again.', 'aqualuxe' ),
            ),
        ) );
    }

    /**
     * Register shortcodes
     */
    public function register_shortcodes() {
        add_shortcode( 'aqualuxe_booking_form', 'aqualuxe_booking_form_shortcode' );
        add_shortcode( 'aqualuxe_booking_calendar', 'aqualuxe_booking_calendar_shortcode' );
        add_shortcode( 'aqualuxe_services', 'aqualuxe_services_shortcode' );
        add_shortcode( 'aqualuxe_resources', 'aqualuxe_resources_shortcode' );
        add_shortcode( 'aqualuxe_my_bookings', 'aqualuxe_my_bookings_shortcode' );
    }

    /**
     * WooCommerce integration
     */
    public function woocommerce_integration() {
        // Include WooCommerce integration file
        require_once $this->dir_path . 'inc/class-aqualuxe-booking-woocommerce.php';
        
        // Initialize WooCommerce integration
        new AquaLuxe_Booking_WooCommerce();
    }

    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        // Add settings page
        add_submenu_page(
            'edit.php?post_type=aqualuxe_booking',
            __( 'Booking Settings', 'aqualuxe' ),
            __( 'Settings', 'aqualuxe' ),
            'manage_options',
            'aqualuxe-booking-settings',
            array( $this, 'render_settings_page' )
        );

        // Add calendar page
        add_submenu_page(
            'edit.php?post_type=aqualuxe_booking',
            __( 'Booking Calendar', 'aqualuxe' ),
            __( 'Calendar', 'aqualuxe' ),
            'manage_options',
            'aqualuxe-booking-calendar',
            array( $this, 'render_calendar_page' )
        );

        // Add reports page
        add_submenu_page(
            'edit.php?post_type=aqualuxe_booking',
            __( 'Booking Reports', 'aqualuxe' ),
            __( 'Reports', 'aqualuxe' ),
            'manage_options',
            'aqualuxe-booking-reports',
            array( $this, 'render_reports_page' )
        );
    }

    /**
     * Register settings
     */
    public function register_settings() {
        // Register settings
        register_setting( 'aqualuxe_bookings', 'aqualuxe_bookings_date_format', array(
            'type'              => 'string',
            'default'           => 'mm/dd/yy',
            'sanitize_callback' => 'sanitize_text_field',
        ) );
        
        register_setting( 'aqualuxe_bookings', 'aqualuxe_bookings_time_format', array(
            'type'              => 'string',
            'default'           => 'h:i A',
            'sanitize_callback' => 'sanitize_text_field',
        ) );
        
        register_setting( 'aqualuxe_bookings', 'aqualuxe_bookings_first_day', array(
            'type'              => 'integer',
            'default'           => 0,
            'sanitize_callback' => 'absint',
        ) );
        
        register_setting( 'aqualuxe_bookings', 'aqualuxe_bookings_min_days_advance', array(
            'type'              => 'integer',
            'default'           => 0,
            'sanitize_callback' => 'absint',
        ) );
        
        register_setting( 'aqualuxe_bookings', 'aqualuxe_bookings_max_days_advance', array(
            'type'              => 'integer',
            'default'           => 90,
            'sanitize_callback' => 'absint',
        ) );
        
        register_setting( 'aqualuxe_bookings', 'aqualuxe_bookings_time_slot_duration', array(
            'type'              => 'integer',
            'default'           => 30,
            'sanitize_callback' => 'absint',
        ) );
        
        register_setting( 'aqualuxe_bookings', 'aqualuxe_bookings_buffer_before', array(
            'type'              => 'integer',
            'default'           => 0,
            'sanitize_callback' => 'absint',
        ) );
        
        register_setting( 'aqualuxe_bookings', 'aqualuxe_bookings_buffer_after', array(
            'type'              => 'integer',
            'default'           => 0,
            'sanitize_callback' => 'absint',
        ) );
        
        register_setting( 'aqualuxe_bookings', 'aqualuxe_bookings_require_payment', array(
            'type'              => 'boolean',
            'default'           => true,
            'sanitize_callback' => 'rest_sanitize_boolean',
        ) );
        
        register_setting( 'aqualuxe_bookings', 'aqualuxe_bookings_confirmation_page', array(
            'type'              => 'integer',
            'default'           => 0,
            'sanitize_callback' => 'absint',
        ) );
        
        register_setting( 'aqualuxe_bookings', 'aqualuxe_bookings_confirmation_email_template', array(
            'type'              => 'string',
            'default'           => '',
            'sanitize_callback' => 'wp_kses_post',
        ) );
        
        register_setting( 'aqualuxe_bookings', 'aqualuxe_bookings_cancellation_email_template', array(
            'type'              => 'string',
            'default'           => '',
            'sanitize_callback' => 'wp_kses_post',
        ) );
        
        register_setting( 'aqualuxe_bookings', 'aqualuxe_bookings_reminder_email_template', array(
            'type'              => 'string',
            'default'           => '',
            'sanitize_callback' => 'wp_kses_post',
        ) );
        
        register_setting( 'aqualuxe_bookings', 'aqualuxe_bookings_reminder_days_before', array(
            'type'              => 'integer',
            'default'           => 1,
            'sanitize_callback' => 'absint',
        ) );
        
        register_setting( 'aqualuxe_bookings', 'aqualuxe_bookings_google_calendar_integration', array(
            'type'              => 'boolean',
            'default'           => false,
            'sanitize_callback' => 'rest_sanitize_boolean',
        ) );
        
        register_setting( 'aqualuxe_bookings', 'aqualuxe_bookings_google_calendar_client_id', array(
            'type'              => 'string',
            'default'           => '',
            'sanitize_callback' => 'sanitize_text_field',
        ) );
        
        register_setting( 'aqualuxe_bookings', 'aqualuxe_bookings_google_calendar_client_secret', array(
            'type'              => 'string',
            'default'           => '',
            'sanitize_callback' => 'sanitize_text_field',
        ) );
    }

    /**
     * Render settings page
     */
    public function render_settings_page() {
        // Check user capabilities
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }
        
        // Get the settings instance
        $settings = new AquaLuxe_Booking_Settings();
        
        // Render settings page
        $settings->render();
    }

    /**
     * Render calendar page
     */
    public function render_calendar_page() {
        // Check user capabilities
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }
        
        // Get the calendar instance
        $calendar = new AquaLuxe_Booking_Calendar();
        
        // Render calendar page
        $calendar->render();
    }

    /**
     * Render reports page
     */
    public function render_reports_page() {
        // Check user capabilities
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }
        
        // Include reports template
        include $this->dir_path . 'templates/admin/reports.php';
    }

    /**
     * AJAX check availability
     */
    public function ajax_check_availability() {
        // Check nonce
        if ( ! check_ajax_referer( 'aqualuxe-bookings', 'nonce', false ) ) {
            wp_send_json_error( array( 'message' => __( 'Invalid nonce.', 'aqualuxe' ) ) );
        }
        
        // Get parameters
        $service_id = isset( $_POST['service_id'] ) ? absint( $_POST['service_id'] ) : 0;
        $resource_id = isset( $_POST['resource_id'] ) ? absint( $_POST['resource_id'] ) : 0;
        $date = isset( $_POST['date'] ) ? sanitize_text_field( $_POST['date'] ) : '';
        
        if ( ! $service_id || ! $date ) {
            wp_send_json_error( array( 'message' => __( 'Missing required parameters.', 'aqualuxe' ) ) );
        }
        
        // Get availability
        $availability = new AquaLuxe_Booking_Availability();
        $available_slots = $availability->get_available_slots( $service_id, $resource_id, $date );
        
        wp_send_json_success( array(
            'slots' => $available_slots,
        ) );
    }

    /**
     * AJAX create booking
     */
    public function ajax_create_booking() {
        // Check nonce
        if ( ! check_ajax_referer( 'aqualuxe-bookings', 'nonce', false ) ) {
            wp_send_json_error( array( 'message' => __( 'Invalid nonce.', 'aqualuxe' ) ) );
        }
        
        // Get parameters
        $service_id = isset( $_POST['service_id'] ) ? absint( $_POST['service_id'] ) : 0;
        $resource_id = isset( $_POST['resource_id'] ) ? absint( $_POST['resource_id'] ) : 0;
        $date = isset( $_POST['date'] ) ? sanitize_text_field( $_POST['date'] ) : '';
        $time = isset( $_POST['time'] ) ? sanitize_text_field( $_POST['time'] ) : '';
        $customer_name = isset( $_POST['customer_name'] ) ? sanitize_text_field( $_POST['customer_name'] ) : '';
        $customer_email = isset( $_POST['customer_email'] ) ? sanitize_email( $_POST['customer_email'] ) : '';
        $customer_phone = isset( $_POST['customer_phone'] ) ? sanitize_text_field( $_POST['customer_phone'] ) : '';
        $notes = isset( $_POST['notes'] ) ? sanitize_textarea_field( $_POST['notes'] ) : '';
        
        if ( ! $service_id || ! $date || ! $time || ! $customer_name || ! $customer_email ) {
            wp_send_json_error( array( 'message' => __( 'Missing required parameters.', 'aqualuxe' ) ) );
        }
        
        // Check if payment is required
        $require_payment = get_option( 'aqualuxe_bookings_require_payment', true );
        
        if ( $require_payment && class_exists( 'WooCommerce' ) ) {
            // Create WooCommerce product for booking
            $service = get_post( $service_id );
            $product_id = $this->create_booking_product( $service_id );
            
            if ( ! $product_id ) {
                wp_send_json_error( array( 'message' => __( 'Error creating booking product.', 'aqualuxe' ) ) );
            }
            
            // Add to cart
            WC()->cart->add_to_cart( $product_id, 1, 0, array(), array(
                'booking_service_id'    => $service_id,
                'booking_resource_id'   => $resource_id,
                'booking_date'          => $date,
                'booking_time'          => $time,
                'booking_customer_name' => $customer_name,
                'booking_customer_email' => $customer_email,
                'booking_customer_phone' => $customer_phone,
                'booking_notes'         => $notes,
            ) );
            
            // Return checkout URL
            wp_send_json_success( array(
                'redirect' => wc_get_checkout_url(),
            ) );
        } else {
            // Create booking directly
            $booking = new AquaLuxe_Booking();
            $booking_id = $booking->create( array(
                'service_id'     => $service_id,
                'resource_id'    => $resource_id,
                'date'           => $date,
                'time'           => $time,
                'customer_name'  => $customer_name,
                'customer_email' => $customer_email,
                'customer_phone' => $customer_phone,
                'notes'          => $notes,
                'status'         => 'confirmed',
            ) );
            
            if ( ! $booking_id ) {
                wp_send_json_error( array( 'message' => __( 'Error creating booking.', 'aqualuxe' ) ) );
            }
            
            // Send confirmation email
            $booking->send_confirmation_email( $booking_id );
            
            // Return success
            wp_send_json_success( array(
                'booking_id' => $booking_id,
                'message'    => __( 'Booking created successfully!', 'aqualuxe' ),
            ) );
        }
    }

    /**
     * Create booking product
     *
     * @param int $service_id Service ID
     * @return int Product ID
     */
    private function create_booking_product( $service_id ) {
        // Check if WooCommerce is active
        if ( ! class_exists( 'WooCommerce' ) ) {
            return 0;
        }
        
        // Get service
        $service = get_post( $service_id );
        
        if ( ! $service ) {
            return 0;
        }
        
        // Get service price
        $price = get_post_meta( $service_id, '_price', true );
        
        if ( ! $price ) {
            $price = 0;
        }
        
        // Create product
        $product = new WC_Product_Simple();
        $product->set_name( $service->post_title );
        $product->set_description( $service->post_content );
        $product->set_short_description( $service->post_excerpt );
        $product->set_regular_price( $price );
        $product->set_status( 'publish' );
        $product->set_catalog_visibility( 'hidden' );
        $product->set_sold_individually( true );
        $product->set_virtual( true );
        
        // Set product image
        if ( has_post_thumbnail( $service_id ) ) {
            $product->set_image_id( get_post_thumbnail_id( $service_id ) );
        }
        
        // Save product
        $product_id = $product->save();
        
        return $product_id;
    }

    /**
     * Add booking data to order item
     *
     * @param WC_Order_Item_Product $item Order item
     * @param string $cart_item_key Cart item key
     * @param array $values Cart item values
     * @param WC_Order $order Order
     */
    public function add_booking_data_to_order_item( $item, $cart_item_key, $values, $order ) {
        // Check if this is a booking product
        if ( isset( $values['booking_service_id'] ) ) {
            // Add booking data to order item
            $item->add_meta_data( '_booking_service_id', $values['booking_service_id'] );
            $item->add_meta_data( '_booking_resource_id', $values['booking_resource_id'] );
            $item->add_meta_data( '_booking_date', $values['booking_date'] );
            $item->add_meta_data( '_booking_time', $values['booking_time'] );
            $item->add_meta_data( '_booking_customer_name', $values['booking_customer_name'] );
            $item->add_meta_data( '_booking_customer_email', $values['booking_customer_email'] );
            $item->add_meta_data( '_booking_customer_phone', $values['booking_customer_phone'] );
            $item->add_meta_data( '_booking_notes', $values['booking_notes'] );
            
            // Add visible meta data
            $service = get_post( $values['booking_service_id'] );
            $item->add_meta_data( __( 'Service', 'aqualuxe' ), $service->post_title, true );
            $item->add_meta_data( __( 'Date', 'aqualuxe' ), $values['booking_date'], true );
            $item->add_meta_data( __( 'Time', 'aqualuxe' ), $values['booking_time'], true );
            
            if ( $values['booking_resource_id'] ) {
                $resource = get_post( $values['booking_resource_id'] );
                $item->add_meta_data( __( 'Resource', 'aqualuxe' ), $resource->post_title, true );
            }
        }
    }

    /**
     * Confirm booking on order complete
     *
     * @param int $order_id Order ID
     */
    public function confirm_booking_on_order_complete( $order_id ) {
        // Get order
        $order = wc_get_order( $order_id );
        
        if ( ! $order ) {
            return;
        }
        
        // Loop through order items
        foreach ( $order->get_items() as $item_id => $item ) {
            // Check if this is a booking product
            $booking_service_id = $item->get_meta( '_booking_service_id' );
            
            if ( $booking_service_id ) {
                // Get booking data
                $booking_resource_id = $item->get_meta( '_booking_resource_id' );
                $booking_date = $item->get_meta( '_booking_date' );
                $booking_time = $item->get_meta( '_booking_time' );
                $booking_customer_name = $item->get_meta( '_booking_customer_name' );
                $booking_customer_email = $item->get_meta( '_booking_customer_email' );
                $booking_customer_phone = $item->get_meta( '_booking_customer_phone' );
                $booking_notes = $item->get_meta( '_booking_notes' );
                
                // Create booking
                $booking = new AquaLuxe_Booking();
                $booking_id = $booking->create( array(
                    'service_id'     => $booking_service_id,
                    'resource_id'    => $booking_resource_id,
                    'date'           => $booking_date,
                    'time'           => $booking_time,
                    'customer_name'  => $booking_customer_name,
                    'customer_email' => $booking_customer_email,
                    'customer_phone' => $booking_customer_phone,
                    'notes'          => $booking_notes,
                    'status'         => 'confirmed',
                    'order_id'       => $order_id,
                ) );
                
                if ( $booking_id ) {
                    // Add booking ID to order item
                    $item->add_meta_data( '_booking_id', $booking_id, true );
                    $item->save();
                    
                    // Send confirmation email
                    $booking->send_confirmation_email( $booking_id );
                }
            }
        }
    }

    /**
     * Cancel booking on order cancel
     *
     * @param int $order_id Order ID
     */
    public function cancel_booking_on_order_cancel( $order_id ) {
        // Get order
        $order = wc_get_order( $order_id );
        
        if ( ! $order ) {
            return;
        }
        
        // Loop through order items
        foreach ( $order->get_items() as $item_id => $item ) {
            // Check if this is a booking product
            $booking_id = $item->get_meta( '_booking_id' );
            
            if ( $booking_id ) {
                // Cancel booking
                $booking = new AquaLuxe_Booking();
                $booking->update_status( $booking_id, 'cancelled' );
                
                // Send cancellation email
                $booking->send_cancellation_email( $booking_id );
            }
        }
    }
}