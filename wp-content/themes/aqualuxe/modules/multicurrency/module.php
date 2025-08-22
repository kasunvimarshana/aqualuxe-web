<?php
/**
 * Multicurrency module
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
function aqualuxe_multicurrency_init() {
    // Check if WooCommerce is active
    if (!class_exists('WooCommerce')) {
        return;
    }
    
    // Load module files
    require_once dirname(__FILE__) . '/inc/functions.php';
    require_once dirname(__FILE__) . '/inc/hooks.php';
    require_once dirname(__FILE__) . '/inc/settings.php';
    require_once dirname(__FILE__) . '/inc/currency-switcher.php';
    
    // Register module assets
    add_action('wp_enqueue_scripts', 'aqualuxe_multicurrency_enqueue_assets');
    
    // Register module settings
    add_action('admin_init', 'aqualuxe_multicurrency_register_settings');
    
    // Register module admin page
    aqualuxe_register_module_admin_page('multicurrency', array(
        'page_title' => __('Multicurrency Settings', 'aqualuxe'),
        'menu_title' => __('Multicurrency', 'aqualuxe'),
    ));
    
    // Initialize currency switcher
    aqualuxe_multicurrency_initialize_switcher();
}
add_action('aqualuxe_modules_loaded', 'aqualuxe_multicurrency_init');

/**
 * Enqueue module assets
 */
function aqualuxe_multicurrency_enqueue_assets() {
    // Register and enqueue styles
    wp_enqueue_style(
        'aqualuxe-multicurrency',
        get_template_directory_uri() . '/modules/multicurrency/assets/css/multicurrency.css',
        array(),
        AQUALUXE_VERSION
    );
    
    // Register and enqueue scripts
    wp_enqueue_script(
        'aqualuxe-multicurrency',
        get_template_directory_uri() . '/modules/multicurrency/assets/js/multicurrency.js',
        array('jquery'),
        AQUALUXE_VERSION,
        true
    );
    
    // Localize script
    wp_localize_script(
        'aqualuxe-multicurrency',
        'aqualuxeMulticurrency',
        array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('aqualuxe_multicurrency_nonce'),
            'currentCurrency' => aqualuxe_multicurrency_get_current_currency(),
            'defaultCurrency' => aqualuxe_multicurrency_get_default_currency(),
            'currencies' => aqualuxe_multicurrency_get_currencies(),
        )
    );
}

/**
 * Register module with theme
 */
function aqualuxe_multicurrency_register_module() {
    return array(
        'id' => 'multicurrency',
        'name' => __('Multicurrency', 'aqualuxe'),
        'description' => __('Adds multicurrency support to WooCommerce.', 'aqualuxe'),
        'default' => true,
    );
}
add_filter('aqualuxe_available_modules', 'aqualuxe_multicurrency_register_module');