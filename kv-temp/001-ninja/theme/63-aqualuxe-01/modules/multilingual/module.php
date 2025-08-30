<?php
/**
 * Multilingual module
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
function aqualuxe_multilingual_init() {
    // Load module files
    require_once dirname(__FILE__) . '/inc/functions.php';
    require_once dirname(__FILE__) . '/inc/hooks.php';
    require_once dirname(__FILE__) . '/inc/settings.php';
    
    // Register module assets
    add_action('wp_enqueue_scripts', 'aqualuxe_multilingual_enqueue_assets');
    
    // Register module settings
    add_action('admin_init', 'aqualuxe_multilingual_register_settings');
    
    // Register module admin page
    aqualuxe_register_module_admin_page('multilingual', array(
        'page_title' => __('Multilingual Settings', 'aqualuxe'),
        'menu_title' => __('Multilingual', 'aqualuxe'),
    ));
}
add_action('aqualuxe_modules_loaded', 'aqualuxe_multilingual_init');

/**
 * Enqueue module assets
 */
function aqualuxe_multilingual_enqueue_assets() {
    // Register and enqueue styles
    wp_enqueue_style(
        'aqualuxe-multilingual',
        get_template_directory_uri() . '/modules/multilingual/assets/css/multilingual.css',
        array(),
        AQUALUXE_VERSION
    );
    
    // Register and enqueue scripts
    wp_enqueue_script(
        'aqualuxe-multilingual',
        get_template_directory_uri() . '/modules/multilingual/assets/js/multilingual.js',
        array('jquery'),
        AQUALUXE_VERSION,
        true
    );
    
    // Localize script
    wp_localize_script(
        'aqualuxe-multilingual',
        'aqualuxeMultilingual',
        array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('aqualuxe_multilingual_nonce'),
            'currentLanguage' => aqualuxe_multilingual_get_current_language(),
            'defaultLanguage' => aqualuxe_multilingual_get_default_language(),
            'languages' => aqualuxe_multilingual_get_languages(),
        )
    );
}

/**
 * Register module with theme
 */
function aqualuxe_multilingual_register_module() {
    return array(
        'id' => 'multilingual',
        'name' => __('Multilingual', 'aqualuxe'),
        'description' => __('Adds multilingual support to the theme.', 'aqualuxe'),
        'default' => true,
    );
}
add_filter('aqualuxe_available_modules', 'aqualuxe_multilingual_register_module');