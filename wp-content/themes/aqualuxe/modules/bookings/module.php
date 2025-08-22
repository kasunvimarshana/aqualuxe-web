<?php
/**
 * Bookings module
 *
 * @package AquaLuxe
 * @subpackage Modules
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Initialize module
 */
function aqualuxe_bookings_init() {
    // Check if WooCommerce is active
    if (!class_exists('WooCommerce')) {
        return;
    }
    
    // Load module files
    require_once dirname(__FILE__) . '/inc/functions.php';
    require_once dirname(__FILE__) . '/inc/hooks.php';
    require_once dirname(__FILE__) . '/inc/settings.php';
    require_once dirname(__FILE__) . '/inc/product-type.php';
    require_once dirname(__FILE__) . '/inc/calendar.php';
    require_once dirname(__FILE__) . '/inc/availability.php';
    require_once dirname(__FILE__) . '/inc/dashboard.php';
    require_once dirname(__FILE__) . '/inc/emails.php';
    
    // Register module assets
    add_action('wp_enqueue_scripts', 'aqualuxe_bookings_enqueue_assets');
    add_action('admin_enqueue_scripts', 'aqualuxe_bookings_admin_enqueue_assets');
    
    // Register module settings
    add_action('admin_init', 'aqualuxe_bookings_register_settings');
    
    // Register module admin page
    aqualuxe_register_module_admin_page('bookings', array(
        'page_title' => __('Booking Settings', 'aqualuxe'),
        'menu_title' => __('Bookings', 'aqualuxe'),
    ));
    
    // Initialize bookings functionality
    aqualuxe_bookings_initialize();
}
add_action('aqualuxe_modules_loaded', 'aqualuxe_bookings_init');

/**
 * Enqueue module assets
 */
function aqualuxe_bookings_enqueue_assets() {
    // Only enqueue assets for booking pages or products
    if (!aqualuxe_bookings_is_booking_page() && !aqualuxe_bookings_is_booking_product()) {
        return;
    }
    
    // Register and enqueue styles
    wp_enqueue_style(
        'aqualuxe-bookings',
        get_template_directory_uri() . '/modules/bookings/assets/css/bookings.css',
        array(),
        AQUALUXE_VERSION
    );
    
    // Register and enqueue scripts
    wp_enqueue_script(
        'aqualuxe-bookings',
        get_template_directory_uri() . '/modules/bookings/assets/js/bookings.js',
        array('jquery', 'jquery-ui-datepicker'),
        AQUALUXE_VERSION,
        true
    );
    
    // Localize script
    wp_localize_script(
        'aqualuxe-bookings',
        'aqualuxeBookings',
        array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('aqualuxe_bookings_nonce'),
            'i18n' => array(
                'select_date' => __('Please select a date', 'aqualuxe'),
                'select_time' => __('Please select a time', 'aqualuxe'),
                'booking_success' => __('Your booking has been placed successfully!', 'aqualuxe'),
                'booking_error' => __('There was an error placing your booking. Please try again.', 'aqualuxe'),
                'date_format' => _x('MM dd, yy', 'jQuery UI datepicker date format', 'aqualuxe'),
                'days' => array(
                    __('Sunday', 'aqualuxe'),
                    __('Monday', 'aqualuxe'),
                    __('Tuesday', 'aqualuxe'),
                    __('Wednesday', 'aqualuxe'),
                    __('Thursday', 'aqualuxe'),
                    __('Friday', 'aqualuxe'),
                    __('Saturday', 'aqualuxe'),
                ),
                'days_short' => array(
                    __('Sun', 'aqualuxe'),
                    __('Mon', 'aqualuxe'),
                    __('Tue', 'aqualuxe'),
                    __('Wed', 'aqualuxe'),
                    __('Thu', 'aqualuxe'),
                    __('Fri', 'aqualuxe'),
                    __('Sat', 'aqualuxe'),
                ),
                'months' => array(
                    __('January', 'aqualuxe'),
                    __('February', 'aqualuxe'),
                    __('March', 'aqualuxe'),
                    __('April', 'aqualuxe'),
                    __('May', 'aqualuxe'),
                    __('June', 'aqualuxe'),
                    __('July', 'aqualuxe'),
                    __('August', 'aqualuxe'),
                    __('September', 'aqualuxe'),
                    __('October', 'aqualuxe'),
                    __('November', 'aqualuxe'),
                    __('December', 'aqualuxe'),
                ),
                'months_short' => array(
                    __('Jan', 'aqualuxe'),
                    __('Feb', 'aqualuxe'),
                    __('Mar', 'aqualuxe'),
                    __('Apr', 'aqualuxe'),
                    __('May', 'aqualuxe'),
                    __('Jun', 'aqualuxe'),
                    __('Jul', 'aqualuxe'),
                    __('Aug', 'aqualuxe'),
                    __('Sep', 'aqualuxe'),
                    __('Oct', 'aqualuxe'),
                    __('Nov', 'aqualuxe'),
                    __('Dec', 'aqualuxe'),
                ),
                'today' => __('Today', 'aqualuxe'),
                'clear' => __('Clear', 'aqualuxe'),
                'close' => __('Close', 'aqualuxe'),
                'next' => __('Next', 'aqualuxe'),
                'prev' => __('Prev', 'aqualuxe'),
            ),
        )
    );
}

/**
 * Enqueue admin assets
 */
function aqualuxe_bookings_admin_enqueue_assets() {
    $screen = get_current_screen();
    
    // Only enqueue on booking settings page or product edit page
    if (!$screen || (strpos($screen->id, 'aqualuxe-bookings') === false && $screen->id !== 'product')) {
        return;
    }
    
    // Register and enqueue admin styles
    wp_enqueue_style(
        'aqualuxe-bookings-admin',
        get_template_directory_uri() . '/modules/bookings/assets/css/bookings-admin.css',
        array(),
        AQUALUXE_VERSION
    );
    
    // Register and enqueue admin scripts
    wp_enqueue_script(
        'aqualuxe-bookings-admin',
        get_template_directory_uri() . '/modules/bookings/assets/js/bookings-admin.js',
        array('jquery', 'jquery-ui-datepicker', 'jquery-ui-sortable'),
        AQUALUXE_VERSION,
        true
    );
    
    // Localize script
    wp_localize_script(
        'aqualuxe-bookings-admin',
        'aqualuxeBookingsAdmin',
        array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('aqualuxe_bookings_admin_nonce'),
            'i18n' => array(
                'date_format' => _x('MM dd, yy', 'jQuery UI datepicker date format', 'aqualuxe'),
                'time_format' => _x('HH:mm', 'jQuery UI datepicker time format', 'aqualuxe'),
                'confirm_delete' => __('Are you sure you want to delete this booking?', 'aqualuxe'),
                'confirm_cancel' => __('Are you sure you want to cancel this booking?', 'aqualuxe'),
                'add_time_slot' => __('Add Time Slot', 'aqualuxe'),
                'remove' => __('Remove', 'aqualuxe'),
                'days' => array(
                    __('Sunday', 'aqualuxe'),
                    __('Monday', 'aqualuxe'),
                    __('Tuesday', 'aqualuxe'),
                    __('Wednesday', 'aqualuxe'),
                    __('Thursday', 'aqualuxe'),
                    __('Friday', 'aqualuxe'),
                    __('Saturday', 'aqualuxe'),
                ),
            ),
        )
    );
}

/**
 * Initialize bookings functionality
 */
function aqualuxe_bookings_initialize() {
    // Register booking product type
    aqualuxe_bookings_register_product_type();
    
    // Initialize booking calendar
    aqualuxe_bookings_initialize_calendar();
    
    // Initialize booking availability
    aqualuxe_bookings_initialize_availability();
    
    // Initialize booking dashboard
    aqualuxe_bookings_initialize_dashboard();
    
    // Initialize booking emails
    aqualuxe_bookings_initialize_emails();
    
    // Schedule booking events
    aqualuxe_bookings_schedule_events();
}

/**
 * Schedule booking events
 */
function aqualuxe_bookings_schedule_events() {
    // Schedule event to check for upcoming bookings
    if (!wp_next_scheduled('aqualuxe_check_upcoming_bookings')) {
        wp_schedule_event(time(), 'hourly', 'aqualuxe_check_upcoming_bookings');
    }
    
    // Schedule event to clean up expired bookings
    if (!wp_next_scheduled('aqualuxe_cleanup_expired_bookings')) {
        wp_schedule_event(time(), 'daily', 'aqualuxe_cleanup_expired_bookings');
    }
}

/**
 * Register module with theme
 */
function aqualuxe_bookings_register_module() {
    return array(
        'id' => 'bookings',
        'name' => __('Bookings', 'aqualuxe'),
        'description' => __('Adds booking functionality to the store.', 'aqualuxe'),
        'default' => false,
    );
}
add_filter('aqualuxe_available_modules', 'aqualuxe_bookings_register_module');