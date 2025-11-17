<?php
/**
 * Auctions module
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
function aqualuxe_auctions_init() {
    // Check if WooCommerce is active
    if (!class_exists('WooCommerce')) {
        return;
    }
    
    // Load module files
    require_once dirname(__FILE__) . '/inc/functions.php';
    require_once dirname(__FILE__) . '/inc/hooks.php';
    require_once dirname(__FILE__) . '/inc/settings.php';
    require_once dirname(__FILE__) . '/inc/product-type.php';
    require_once dirname(__FILE__) . '/inc/bidding.php';
    require_once dirname(__FILE__) . '/inc/dashboard.php';
    require_once dirname(__FILE__) . '/inc/emails.php';
    
    // Register module assets
    add_action('wp_enqueue_scripts', 'aqualuxe_auctions_enqueue_assets');
    add_action('admin_enqueue_scripts', 'aqualuxe_auctions_admin_enqueue_assets');
    
    // Register module settings
    add_action('admin_init', 'aqualuxe_auctions_register_settings');
    
    // Register module admin page
    aqualuxe_register_module_admin_page('auctions', array(
        'page_title' => __('Auction Settings', 'aqualuxe'),
        'menu_title' => __('Auctions', 'aqualuxe'),
    ));
    
    // Initialize auctions functionality
    aqualuxe_auctions_initialize();
}
add_action('aqualuxe_modules_loaded', 'aqualuxe_auctions_init');

/**
 * Enqueue module assets
 */
function aqualuxe_auctions_enqueue_assets() {
    // Only enqueue assets for auction pages or products
    if (!aqualuxe_auctions_is_auction_page() && !aqualuxe_auctions_is_auction_product()) {
        return;
    }
    
    // Register and enqueue styles
    wp_enqueue_style(
        'aqualuxe-auctions',
        get_template_directory_uri() . '/modules/auctions/assets/css/auctions.css',
        array(),
        AQUALUXE_VERSION
    );
    
    // Register and enqueue scripts
    wp_enqueue_script(
        'aqualuxe-auctions',
        get_template_directory_uri() . '/modules/auctions/assets/js/auctions.js',
        array('jquery'),
        AQUALUXE_VERSION,
        true
    );
    
    // Localize script
    wp_localize_script(
        'aqualuxe-auctions',
        'aqualuxeAuctions',
        array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('aqualuxe_auctions_nonce'),
            'i18n' => array(
                'bid_placed' => __('Your bid has been placed successfully!', 'aqualuxe'),
                'bid_error' => __('There was an error placing your bid. Please try again.', 'aqualuxe'),
                'bid_too_low' => __('Your bid is too low. Please increase your bid amount.', 'aqualuxe'),
                'confirm_bid' => __('Are you sure you want to place this bid?', 'aqualuxe'),
                'auction_ended' => __('This auction has ended.', 'aqualuxe'),
                'time_remaining' => __('Time remaining:', 'aqualuxe'),
                'days' => __('days', 'aqualuxe'),
                'hours' => __('hours', 'aqualuxe'),
                'minutes' => __('minutes', 'aqualuxe'),
                'seconds' => __('seconds', 'aqualuxe'),
            ),
            'refreshInterval' => aqualuxe_get_module_option('auctions', 'refresh_interval', 10),
        )
    );
}

/**
 * Enqueue admin assets
 */
function aqualuxe_auctions_admin_enqueue_assets() {
    $screen = get_current_screen();
    
    // Only enqueue on auction settings page or product edit page
    if (!$screen || (strpos($screen->id, 'aqualuxe-auctions') === false && $screen->id !== 'product')) {
        return;
    }
    
    // Register and enqueue admin styles
    wp_enqueue_style(
        'aqualuxe-auctions-admin',
        get_template_directory_uri() . '/modules/auctions/assets/css/auctions-admin.css',
        array(),
        AQUALUXE_VERSION
    );
    
    // Register and enqueue admin scripts
    wp_enqueue_script(
        'aqualuxe-auctions-admin',
        get_template_directory_uri() . '/modules/auctions/assets/js/auctions-admin.js',
        array('jquery', 'jquery-ui-datepicker'),
        AQUALUXE_VERSION,
        true
    );
    
    // Localize script
    wp_localize_script(
        'aqualuxe-auctions-admin',
        'aqualuxeAuctionsAdmin',
        array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('aqualuxe_auctions_admin_nonce'),
            'i18n' => array(
                'date_format' => _x('MM dd, yy', 'jQuery UI datepicker date format', 'aqualuxe'),
                'time_format' => _x('HH:mm', 'jQuery UI datepicker time format', 'aqualuxe'),
                'confirm_delete' => __('Are you sure you want to delete this auction?', 'aqualuxe'),
                'confirm_end' => __('Are you sure you want to end this auction early?', 'aqualuxe'),
            ),
        )
    );
}

/**
 * Initialize auctions functionality
 */
function aqualuxe_auctions_initialize() {
    // Register auction product type
    aqualuxe_auctions_register_product_type();
    
    // Initialize bidding system
    aqualuxe_auctions_initialize_bidding();
    
    // Initialize auction dashboard
    aqualuxe_auctions_initialize_dashboard();
    
    // Initialize auction emails
    aqualuxe_auctions_initialize_emails();
    
    // Schedule auction events
    aqualuxe_auctions_schedule_events();
}

/**
 * Schedule auction events
 */
function aqualuxe_auctions_schedule_events() {
    // Schedule event to check for ended auctions
    if (!wp_next_scheduled('aqualuxe_check_ended_auctions')) {
        wp_schedule_event(time(), 'hourly', 'aqualuxe_check_ended_auctions');
    }
    
    // Schedule event to update auction status
    if (!wp_next_scheduled('aqualuxe_update_auction_status')) {
        wp_schedule_event(time(), 'twicedaily', 'aqualuxe_update_auction_status');
    }
}

/**
 * Register module with theme
 */
function aqualuxe_auctions_register_module() {
    return array(
        'id' => 'auctions',
        'name' => __('Auctions', 'aqualuxe'),
        'description' => __('Adds auction functionality to the store.', 'aqualuxe'),
        'default' => false,
    );
}
add_filter('aqualuxe_available_modules', 'aqualuxe_auctions_register_module');