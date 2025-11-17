<?php
/**
 * AquaLuxe Core Functions
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Check if WooCommerce is active
 *
 * @return bool
 */
function aqualuxe_is_woocommerce_active() {
    return class_exists('WooCommerce');
}

/**
 * Get theme settings
 *
 * @return array
 */
function aqualuxe_get_theme_settings() {
    $settings = get_theme_mod('aqualuxe_settings', []);
    
    $defaults = [
        'color_scheme' => 'light',
        'primary_color' => '#0077b6',
        'secondary_color' => '#00b4d8',
        'accent_color' => '#90e0ef',
        'text_color' => '#333333',
        'heading_font' => 'Montserrat, sans-serif',
        'body_font' => 'Open Sans, sans-serif',
        'container_width' => '1200px',
        'enable_dark_mode' => true,
        'logo_height' => '80px',
        'header_layout' => 'standard',
        'footer_layout' => 'four-column',
        'blog_layout' => 'grid',
        'shop_layout' => 'grid',
        'product_layout' => 'standard',
    ];
    
    return wp_parse_args($settings, $defaults);
}

/**
 * Get editor color palette
 *
 * @return array
 */
function aqualuxe_get_color_palette() {
    $settings = aqualuxe_get_theme_settings();
    
    return [
        [
            'name'  => esc_html__('Primary', 'aqualuxe'),
            'slug'  => 'primary',
            'color' => $settings['primary_color'],
        ],
        [
            'name'  => esc_html__('Secondary', 'aqualuxe'),
            'slug'  => 'secondary',
            'color' => $settings['secondary_color'],
        ],
        [
            'name'  => esc_html__('Accent', 'aqualuxe'),
            'slug'  => 'accent',
            'color' => $settings['accent_color'],
        ],
        [
            'name'  => esc_html__('Text', 'aqualuxe'),
            'slug'  => 'text',
            'color' => $settings['text_color'],
        ],
        [
            'name'  => esc_html__('Light', 'aqualuxe'),
            'slug'  => 'light',
            'color' => '#ffffff',
        ],
        [
            'name'  => esc_html__('Dark', 'aqualuxe'),
            'slug'  => 'dark',
            'color' => '#000000',
        ],
    ];
}

/**
 * Get current color scheme
 *
 * @return string
 */
function aqualuxe_get_color_scheme() {
    $settings = aqualuxe_get_theme_settings();
    $user_preference = isset($_COOKIE['aqualuxe_color_scheme']) ? sanitize_text_field($_COOKIE['aqualuxe_color_scheme']) : '';
    
    // If dark mode is disabled, always return light
    if (!$settings['enable_dark_mode']) {
        return 'light';
    }
    
    // If user has a preference, use that
    if (in_array($user_preference, ['light', 'dark'])) {
        return $user_preference;
    }
    
    // Otherwise, use the theme default
    return $settings['color_scheme'];
}

/**
 * Get body classes
 *
 * @param array $classes Existing body classes
 * @return array
 */
function aqualuxe_body_classes($classes) {
    $settings = aqualuxe_get_theme_settings();
    $color_scheme = aqualuxe_get_color_scheme();
    
    // Add color scheme class
    $classes[] = 'aqualuxe-' . $color_scheme . '-mode';
    
    // Add header layout class
    $classes[] = 'aqualuxe-header-' . $settings['header_layout'];
    
    // Add footer layout class
    $classes[] = 'aqualuxe-footer-' . $settings['footer_layout'];
    
    // Add WooCommerce status class
    if (aqualuxe_is_woocommerce_active()) {
        $classes[] = 'aqualuxe-woocommerce-active';
    } else {
        $classes[] = 'aqualuxe-woocommerce-inactive';
    }
    
    // Add page template class
    if (is_page_template()) {
        $template = get_page_template_slug();
        $template = str_replace('.php', '', $template);
        $template = str_replace('templates/', '', $template);
        $classes[] = 'aqualuxe-template-' . $template;
    }
    
    return $classes;
}
add_filter('body_class', 'aqualuxe_body_classes');

/**
 * Get module status
 *
 * @param string $module Module name
 * @return bool
 */
function aqualuxe_is_module_active($module) {
    $active_modules = apply_filters('aqualuxe_active_modules', []);
    return in_array($module, $active_modules);
}

/**
 * Register module
 *
 * @param string $module Module name
 * @param array $args Module arguments
 * @return void
 */
function aqualuxe_register_module($module, $args = []) {
    $defaults = [
        'title' => ucfirst($module),
        'description' => '',
        'version' => '1.0.0',
        'author' => 'AquaLuxe',
        'requires' => [],
        'settings' => [],
    ];
    
    $args = wp_parse_args($args, $defaults);
    
    // Store module info
    $modules = get_option('aqualuxe_modules', []);
    $modules[$module] = $args;
    update_option('aqualuxe_modules', $modules);
}

/**
 * Get module info
 *
 * @param string $module Module name
 * @return array|false
 */
function aqualuxe_get_module_info($module) {
    $modules = get_option('aqualuxe_modules', []);
    return isset($modules[$module]) ? $modules[$module] : false;
}

/**
 * Get all modules
 *
 * @return array
 */
function aqualuxe_get_all_modules() {
    return get_option('aqualuxe_modules', []);
}

/**
 * Get module setting
 *
 * @param string $module Module name
 * @param string $key Setting key
 * @param mixed $default Default value
 * @return mixed
 */
function aqualuxe_get_module_setting($module, $key, $default = null) {
    $settings = get_option('aqualuxe_module_settings', []);
    
    if (!isset($settings[$module])) {
        return $default;
    }
    
    return isset($settings[$module][$key]) ? $settings[$module][$key] : $default;
}

/**
 * Update module setting
 *
 * @param string $module Module name
 * @param string $key Setting key
 * @param mixed $value Setting value
 * @return bool
 */
function aqualuxe_update_module_setting($module, $key, $value) {
    $settings = get_option('aqualuxe_module_settings', []);
    
    if (!isset($settings[$module])) {
        $settings[$module] = [];
    }
    
    $settings[$module][$key] = $value;
    
    return update_option('aqualuxe_module_settings', $settings);
}