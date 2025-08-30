<?php
/**
 * AquaLuxe Bookings Module
 *
 * @package AquaLuxe
 * @subpackage Bookings
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Bookings Module Class
 */
class AquaLuxe_Bookings_Module {
    /**
     * Module version
     *
     * @var string
     */
    public $version = '1.0.0';

    /**
     * The single instance of the class
     *
     * @var AquaLuxe_Bookings_Module
     */
    protected static $_instance = null;

    /**
     * Main Instance
     *
     * Ensures only one instance of the module is loaded or can be loaded.
     *
     * @return AquaLuxe_Bookings_Module - Main instance
     */
    public static function instance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Constructor
     */
    public function __construct() {
        $this->define_constants();
        $this->includes();
        $this->init_hooks();
    }

    /**
     * Define module constants
     */
    private function define_constants() {
        define('AQUALUXE_BOOKINGS_VERSION', $this->version);
        define('AQUALUXE_BOOKINGS_PATH', plugin_dir_path(__FILE__));
        define('AQUALUXE_BOOKINGS_URL', plugin_dir_url(__FILE__));
        define('AQUALUXE_BOOKINGS_TEMPLATE_PATH', AQUALUXE_BOOKINGS_PATH . 'templates/');
    }

    /**
     * Include required files
     */
    private function includes() {
        // Core classes
        require_once AQUALUXE_BOOKINGS_PATH . 'inc/class-bookings-post-types.php';
        require_once AQUALUXE_BOOKINGS_PATH . 'inc/class-bookings-data.php';
        require_once AQUALUXE_BOOKINGS_PATH . 'inc/class-bookings-form-handler.php';
        require_once AQUALUXE_BOOKINGS_PATH . 'inc/class-bookings-ajax.php';
        require_once AQUALUXE_BOOKINGS_PATH . 'inc/class-bookings-shortcodes.php';
        require_once AQUALUXE_BOOKINGS_PATH . 'inc/class-bookings-template-loader.php';
        
        // Admin classes
        if (is_admin()) {
            require_once AQUALUXE_BOOKINGS_PATH . 'inc/admin/class-bookings-admin.php';
            require_once AQUALUXE_BOOKINGS_PATH . 'inc/admin/class-bookings-settings.php';
            require_once AQUALUXE_BOOKINGS_PATH . 'inc/admin/class-bookings-meta-boxes.php';
        }
        
        // Frontend classes
        if (!is_admin() || defined('DOING_AJAX')) {
            require_once AQUALUXE_BOOKINGS_PATH . 'inc/frontend/class-bookings-frontend.php';
            require_once AQUALUXE_BOOKINGS_PATH . 'inc/frontend/class-bookings-calendar.php';
        }
        
        // WooCommerce integration
        if (class_exists('WooCommerce')) {
            require_once AQUALUXE_BOOKINGS_PATH . 'inc/class-bookings-woocommerce.php';
        }
    }

    /**
     * Initialize hooks
     */
    private function init_hooks() {
        // Register activation/deactivation hooks
        register_activation_hook(__FILE__, array($this, 'activate'));
        register_deactivation_hook(__FILE__, array($this, 'deactivate'));
        
        // Register assets
        add_action('wp_enqueue_scripts', array($this, 'register_scripts'));
        add_action('admin_enqueue_scripts', array($this, 'register_admin_scripts'));
        
        // Register module with theme
        add_action('aqualuxe_register_modules', array($this, 'register_module'));
        
        // Add module settings to customizer
        add_action('customize_register', array($this, 'customize_register'));
        
        // Add module settings to theme options
        add_filter('aqualuxe_theme_options_tabs', array($this, 'add_theme_options_tab'));
        add_action('aqualuxe_theme_options_tab_content', array($this, 'theme_options_tab_content'));
    }

    /**
     * Activate module
     */
    public function activate() {
        // Create necessary database tables
        $this->create_tables();
        
        // Register post types and taxonomies
        require_once AQUALUXE_BOOKINGS_PATH . 'inc/class-bookings-post-types.php';
        $post_types = new AquaLuxe_Bookings_Post_Types();
        $post_types->register_post_types();
        $post_types->register_taxonomies();
        
        // Flush rewrite rules
        flush_rewrite_rules();
        
        // Set default options
        $this->set_default_options();
    }

    /**
     * Deactivate module
     */
    public function deactivate() {
        // Flush rewrite rules
        flush_rewrite_rules();
    }

    /**
     * Create database tables
     */
    private function create_tables() {
        global $wpdb;
        
        $wpdb->hide_errors();
        
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        
        $schema = "
        CREATE TABLE {$wpdb->prefix}aqualuxe_bookings (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            booking_id VARCHAR(50) NOT NULL,
            service_id BIGINT UNSIGNED NOT NULL,
            customer_id BIGINT UNSIGNED NOT NULL DEFAULT 0,
            status VARCHAR(20) NOT NULL DEFAULT 'pending',
            date_created DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
            date_modified DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
            start_date DATETIME NOT NULL,
            end_date DATETIME NOT NULL,
            all_day TINYINT(1) NOT NULL DEFAULT 0,
            qty INT NOT NULL DEFAULT 1,
            cost DECIMAL(19,4) NOT NULL DEFAULT 0,
            PRIMARY KEY (id),
            KEY booking_id (booking_id),
            KEY service_id (service_id),
            KEY customer_id (customer_id),
            KEY status (status),
            KEY date_created (date_created),
            KEY start_date (start_date),
            KEY end_date (end_date)
        ) {$wpdb->get_charset_collate()};
        
        CREATE TABLE {$wpdb->prefix}aqualuxe_booking_meta (
            meta_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            booking_id BIGINT UNSIGNED NOT NULL,
            meta_key VARCHAR(255) DEFAULT NULL,
            meta_value LONGTEXT,
            PRIMARY KEY (meta_id),
            KEY booking_id (booking_id),
            KEY meta_key (meta_key(191))
        ) {$wpdb->get_charset_collate()};
        
        CREATE TABLE {$wpdb->prefix}aqualuxe_booking_availability (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            service_id BIGINT UNSIGNED NOT NULL,
            date_from DATETIME NOT NULL,
            date_to DATETIME NOT NULL,
            type VARCHAR(20) NOT NULL DEFAULT 'available',
            bookable TINYINT(1) NOT NULL DEFAULT 1,
            priority INT NOT NULL DEFAULT 10,
            PRIMARY KEY (id),
            KEY service_id (service_id),
            KEY date_from (date_from),
            KEY date_to (date_to)
        ) {$wpdb->get_charset_collate()};
        ";
        
        dbDelta($schema);
    }

    /**
     * Set default options
     */
    private function set_default_options() {
        $default_options = array(
            'booking_page_id' => 0,
            'calendar_first_day' => 0, // 0 = Sunday, 1 = Monday
            'time_format' => '12h', // 12h or 24h
            'buffer_time' => 30, // minutes
            'min_booking_time' => 60, // minutes
            'max_booking_time' => 480, // minutes (8 hours)
            'booking_confirmation' => 'payment', // manual, automatic, payment
            'enable_google_calendar' => 'no',
            'google_calendar_id' => '',
            'google_calendar_api_key' => '',
            'notification_emails' => 'yes',
            'admin_notification_email' => get_option('admin_email'),
            'customer_notification_email' => 'yes',
        );
        
        foreach ($default_options as $key => $value) {
            if (get_option('aqualuxe_bookings_' . $key) === false) {
                update_option('aqualuxe_bookings_' . $key, $value);
            }
        }
    }

    /**
     * Register frontend scripts
     */
    public function register_scripts() {
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
                'currency_symbol' => get_woocommerce_currency_symbol(),
                'currency_position' => get_option('woocommerce_currency_pos'),
            ),
            'settings' => array(
                'buffer_time' => get_option('aqualuxe_bookings_buffer_time', 30),
                'min_booking_time' => get_option('aqualuxe_bookings_min_booking_time', 60),
                'max_booking_time' => get_option('aqualuxe_bookings_max_booking_time', 480),
                'calendar_first_day' => get_option('aqualuxe_bookings_calendar_first_day', 0),
            ),
        ));
    }

    /**
     * Register admin scripts
     */
    public function register_admin_scripts() {
        // Register styles
        wp_register_style(
            'aqualuxe-bookings-admin',
            AQUALUXE_BOOKINGS_URL . 'assets/dist/css/admin.css',
            array(),
            AQUALUXE_BOOKINGS_VERSION
        );
        
        // Register scripts
        wp_register_script(
            'aqualuxe-bookings-admin',
            AQUALUXE_BOOKINGS_URL . 'assets/dist/js/admin.js',
            array('jquery', 'jquery-ui-datepicker', 'jquery-ui-sortable'),
            AQUALUXE_BOOKINGS_VERSION,
            true
        );
        
        // Localize script
        wp_localize_script('aqualuxe-bookings-admin', 'aqualuxe_bookings_admin_params', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('aqualuxe-bookings-admin'),
            'i18n' => array(
                'confirm_delete' => __('Are you sure you want to delete this booking?', 'aqualuxe'),
                'confirm_cancel' => __('Are you sure you want to cancel this booking?', 'aqualuxe'),
                'date_format' => get_option('date_format'),
                'time_format' => get_option('aqualuxe_bookings_time_format', '12h'),
            ),
        ));
    }

    /**
     * Register module with theme
     */
    public function register_module($modules) {
        $modules['bookings'] = array(
            'name' => __('Bookings', 'aqualuxe'),
            'description' => __('Adds booking and scheduling functionality to your site.', 'aqualuxe'),
            'version' => $this->version,
            'author' => 'AquaLuxe',
            'icon' => 'dashicons-calendar-alt',
            'path' => AQUALUXE_BOOKINGS_PATH,
            'url' => AQUALUXE_BOOKINGS_URL,
            'class' => __CLASS__,
            'requires' => array(),
            'settings_url' => admin_url('admin.php?page=aqualuxe-bookings-settings'),
        );
        
        return $modules;
    }

    /**
     * Add module settings to customizer
     */
    public function customize_register($wp_customize) {
        // Add section
        $wp_customize->add_section('aqualuxe_bookings', array(
            'title' => __('Bookings', 'aqualuxe'),
            'description' => __('Settings for the bookings module.', 'aqualuxe'),
            'priority' => 160,
            'panel' => 'aqualuxe_theme_options',
        ));
        
        // Add settings
        $wp_customize->add_setting('aqualuxe_bookings_page_id', array(
            'default' => get_option('aqualuxe_bookings_page_id', 0),
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'absint',
        ));
        
        $wp_customize->add_setting('aqualuxe_bookings_calendar_first_day', array(
            'default' => get_option('aqualuxe_bookings_calendar_first_day', 0),
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'absint',
        ));
        
        $wp_customize->add_setting('aqualuxe_bookings_time_format', array(
            'default' => get_option('aqualuxe_bookings_time_format', '12h'),
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_text_field',
        ));
        
        // Add controls
        $wp_customize->add_control('aqualuxe_bookings_page_id', array(
            'label' => __('Booking Page', 'aqualuxe'),
            'description' => __('Select the page where the booking form will be displayed.', 'aqualuxe'),
            'section' => 'aqualuxe_bookings',
            'type' => 'dropdown-pages',
        ));
        
        $wp_customize->add_control('aqualuxe_bookings_calendar_first_day', array(
            'label' => __('First Day of Week', 'aqualuxe'),
            'description' => __('Select the first day of the week for the calendar.', 'aqualuxe'),
            'section' => 'aqualuxe_bookings',
            'type' => 'select',
            'choices' => array(
                0 => __('Sunday', 'aqualuxe'),
                1 => __('Monday', 'aqualuxe'),
                2 => __('Tuesday', 'aqualuxe'),
                3 => __('Wednesday', 'aqualuxe'),
                4 => __('Thursday', 'aqualuxe'),
                5 => __('Friday', 'aqualuxe'),
                6 => __('Saturday', 'aqualuxe'),
            ),
        ));
        
        $wp_customize->add_control('aqualuxe_bookings_time_format', array(
            'label' => __('Time Format', 'aqualuxe'),
            'description' => __('Select the time format for the booking form.', 'aqualuxe'),
            'section' => 'aqualuxe_bookings',
            'type' => 'select',
            'choices' => array(
                '12h' => __('12-hour (AM/PM)', 'aqualuxe'),
                '24h' => __('24-hour', 'aqualuxe'),
            ),
        ));
    }

    /**
     * Add module settings tab to theme options
     */
    public function add_theme_options_tab($tabs) {
        $tabs['bookings'] = array(
            'title' => __('Bookings', 'aqualuxe'),
            'icon' => 'dashicons-calendar-alt',
        );
        
        return $tabs;
    }

    /**
     * Add module settings content to theme options
     */
    public function theme_options_tab_content($current_tab) {
        if ($current_tab !== 'bookings') {
            return;
        }
        
        // Include settings template
        include AQUALUXE_BOOKINGS_PATH . 'templates/admin/settings.php';
    }

    /**
     * Get template path
     *
     * @return string
     */
    public function template_path() {
        return AQUALUXE_BOOKINGS_TEMPLATE_PATH;
    }

    /**
     * Get module URL
     *
     * @return string
     */
    public function module_url() {
        return AQUALUXE_BOOKINGS_URL;
    }
}

/**
 * Initialize the module
 */
function AquaLuxe_Bookings() {
    return AquaLuxe_Bookings_Module::instance();
}

// Start the module
AquaLuxe_Bookings();