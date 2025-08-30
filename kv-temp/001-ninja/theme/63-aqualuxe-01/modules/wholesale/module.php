<?php
/**
 * Wholesale/B2B module
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
function aqualuxe_wholesale_init() {
    // Check if WooCommerce is active
    if (!class_exists('WooCommerce')) {
        return;
    }
    
    // Load module files
    require_once dirname(__FILE__) . '/inc/functions.php';
    require_once dirname(__FILE__) . '/inc/hooks.php';
    require_once dirname(__FILE__) . '/inc/settings.php';
    require_once dirname(__FILE__) . '/inc/user-roles.php';
    require_once dirname(__FILE__) . '/inc/pricing.php';
    require_once dirname(__FILE__) . '/inc/registration.php';
    require_once dirname(__FILE__) . '/inc/order-requirements.php';
    
    // Register module assets
    add_action('wp_enqueue_scripts', 'aqualuxe_wholesale_enqueue_assets');
    add_action('admin_enqueue_scripts', 'aqualuxe_wholesale_admin_enqueue_assets');
    
    // Register module settings
    add_action('admin_init', 'aqualuxe_wholesale_register_settings');
    
    // Register module admin page
    aqualuxe_register_module_admin_page('wholesale', array(
        'page_title' => __('Wholesale Settings', 'aqualuxe'),
        'menu_title' => __('Wholesale', 'aqualuxe'),
    ));
    
    // Initialize wholesale functionality
    aqualuxe_wholesale_initialize();
}
add_action('aqualuxe_modules_loaded', 'aqualuxe_wholesale_init');

/**
 * Enqueue module assets
 */
function aqualuxe_wholesale_enqueue_assets() {
    // Only enqueue assets for wholesale users or wholesale pages
    if (!aqualuxe_wholesale_is_wholesale_user() && !aqualuxe_wholesale_is_wholesale_page()) {
        return;
    }
    
    // Register and enqueue styles
    wp_enqueue_style(
        'aqualuxe-wholesale',
        get_template_directory_uri() . '/modules/wholesale/assets/css/wholesale.css',
        array(),
        AQUALUXE_VERSION
    );
    
    // Register and enqueue scripts
    wp_enqueue_script(
        'aqualuxe-wholesale',
        get_template_directory_uri() . '/modules/wholesale/assets/js/wholesale.js',
        array('jquery'),
        AQUALUXE_VERSION,
        true
    );
    
    // Localize script
    wp_localize_script(
        'aqualuxe-wholesale',
        'aqualuxeWholesale',
        array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('aqualuxe_wholesale_nonce'),
            'isWholesaleUser' => aqualuxe_wholesale_is_wholesale_user(),
            'minimumOrderAmount' => aqualuxe_wholesale_get_minimum_order_amount(),
            'currentOrderAmount' => WC()->cart ? WC()->cart->get_subtotal() : 0,
        )
    );
}

/**
 * Enqueue admin assets
 */
function aqualuxe_wholesale_admin_enqueue_assets() {
    $screen = get_current_screen();
    
    // Only enqueue on wholesale settings page
    if (!$screen || strpos($screen->id, 'aqualuxe-wholesale') === false) {
        return;
    }
    
    // Register and enqueue admin styles
    wp_enqueue_style(
        'aqualuxe-wholesale-admin',
        get_template_directory_uri() . '/modules/wholesale/assets/css/wholesale-admin.css',
        array(),
        AQUALUXE_VERSION
    );
    
    // Register and enqueue admin scripts
    wp_enqueue_script(
        'aqualuxe-wholesale-admin',
        get_template_directory_uri() . '/modules/wholesale/assets/js/wholesale-admin.js',
        array('jquery'),
        AQUALUXE_VERSION,
        true
    );
    
    // Localize script
    wp_localize_script(
        'aqualuxe-wholesale-admin',
        'aqualuxeWholesaleAdmin',
        array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('aqualuxe_wholesale_admin_nonce'),
        )
    );
}

/**
 * Initialize wholesale functionality
 */
function aqualuxe_wholesale_initialize() {
    // Register wholesale user roles
    aqualuxe_wholesale_register_user_roles();
    
    // Initialize wholesale pricing
    aqualuxe_wholesale_initialize_pricing();
    
    // Initialize wholesale registration
    aqualuxe_wholesale_initialize_registration();
    
    // Initialize order requirements
    aqualuxe_wholesale_initialize_order_requirements();
}

/**
 * Register module with theme
 */
function aqualuxe_wholesale_register_module() {
    return array(
        'id' => 'wholesale',
        'name' => __('Wholesale/B2B', 'aqualuxe'),
        'description' => __('Adds wholesale/B2B functionality to the store.', 'aqualuxe'),
        'default' => true,
    );
}
add_filter('aqualuxe_available_modules', 'aqualuxe_wholesale_register_module');