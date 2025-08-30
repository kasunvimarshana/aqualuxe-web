<?php
/**
 * Dark Mode Module Customizer Settings
 *
 * @package AquaLuxe
 * @subpackage Modules/DarkMode
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Add Dark Mode customizer settings
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_dark_mode_customizer($wp_customize) {
    // Add Dark Mode section
    $wp_customize->add_section('aqualuxe_dark_mode', array(
        'title'    => __('Dark Mode', 'aqualuxe'),
        'priority' => 90,
        'panel'    => 'aqualuxe_theme_options',
    ));
    
    // Default Mode
    $wp_customize->add_setting('aqualuxe_dark_mode_default', array(
        'default'           => 'light',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
        'transport'         => 'refresh',
    ));
    
    $wp_customize->add_control('aqualuxe_dark_mode_default', array(
        'label'    => __('Default Mode', 'aqualuxe'),
        'section'  => 'aqualuxe_dark_mode',
        'type'     => 'select',
        'choices'  => array(
            'light' => __('Light', 'aqualuxe'),
            'dark'  => __('Dark', 'aqualuxe'),
        ),
    ));
    
    // Respect System Preference
    $wp_customize->add_setting('aqualuxe_dark_mode_respect_system', array(
        'default'           => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        'transport'         => 'refresh',
    ));
    
    $wp_customize->add_control('aqualuxe_dark_mode_respect_system', array(
        'label'    => __('Respect System Preference', 'aqualuxe'),
        'description' => __('Use the visitor\'s system preference (light/dark) as the default mode when available.', 'aqualuxe'),
        'section'  => 'aqualuxe_dark_mode',
        'type'     => 'checkbox',
    ));
    
    // Light Mode Background Color
    $wp_customize->add_setting('aqualuxe_dark_mode_light_bg', array(
        'default'           => '#ffffff',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'refresh',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_dark_mode_light_bg', array(
        'label'    => __('Light Mode Background', 'aqualuxe'),
        'section'  => 'aqualuxe_dark_mode',
    )));
    
    // Light Mode Text Color
    $wp_customize->add_setting('aqualuxe_dark_mode_light_text', array(
        'default'           => '#333333',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'refresh',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_dark_mode_light_text', array(
        'label'    => __('Light Mode Text', 'aqualuxe'),
        'section'  => 'aqualuxe_dark_mode',
    )));
    
    // Dark Mode Background Color
    $wp_customize->add_setting('aqualuxe_dark_mode_dark_bg', array(
        'default'           => '#222222',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'refresh',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_dark_mode_dark_bg', array(
        'label'    => __('Dark Mode Background', 'aqualuxe'),
        'section'  => 'aqualuxe_dark_mode',
    )));
    
    // Dark Mode Text Color
    $wp_customize->add_setting('aqualuxe_dark_mode_dark_text', array(
        'default'           => '#f0f0f0',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'refresh',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_dark_mode_dark_text', array(
        'label'    => __('Dark Mode Text', 'aqualuxe'),
        'section'  => 'aqualuxe_dark_mode',
    )));
    
    // Accent Color
    $wp_customize->add_setting('aqualuxe_dark_mode_accent_color', array(
        'default'           => '#0073aa',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'refresh',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_dark_mode_accent_color', array(
        'label'    => __('Accent Color', 'aqualuxe'),
        'section'  => 'aqualuxe_dark_mode',
    )));
    
    // Toggle Position
    $wp_customize->add_setting('aqualuxe_dark_mode_toggle_position', array(
        'default'           => 'bottom-right',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
        'transport'         => 'refresh',
    ));
    
    $wp_customize->add_control('aqualuxe_dark_mode_toggle_position', array(
        'label'    => __('Toggle Button Position', 'aqualuxe'),
        'section'  => 'aqualuxe_dark_mode',
        'type'     => 'select',
        'choices'  => array(
            'bottom-right' => __('Bottom Right', 'aqualuxe'),
            'bottom-left'  => __('Bottom Left', 'aqualuxe'),
            'top-right'    => __('Top Right', 'aqualuxe'),
            'top-left'     => __('Top Left', 'aqualuxe'),
        ),
    ));
    
    // Show Text
    $wp_customize->add_setting('aqualuxe_dark_mode_toggle_show_text', array(
        'default'           => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        'transport'         => 'refresh',
    ));
    
    $wp_customize->add_control('aqualuxe_dark_mode_toggle_show_text', array(
        'label'    => __('Show Text Label', 'aqualuxe'),
        'section'  => 'aqualuxe_dark_mode',
        'type'     => 'checkbox',
    ));
    
    // Light Mode Text
    $wp_customize->add_setting('aqualuxe_dark_mode_toggle_light_text', array(
        'default'           => __('Light Mode', 'aqualuxe'),
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ));
    
    $wp_customize->add_control('aqualuxe_dark_mode_toggle_light_text', array(
        'label'    => __('Light Mode Text', 'aqualuxe'),
        'section'  => 'aqualuxe_dark_mode',
        'type'     => 'text',
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_dark_mode_toggle_show_text', true);
        },
    ));
    
    // Dark Mode Text
    $wp_customize->add_setting('aqualuxe_dark_mode_toggle_dark_text', array(
        'default'           => __('Dark Mode', 'aqualuxe'),
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ));
    
    $wp_customize->add_control('aqualuxe_dark_mode_toggle_dark_text', array(
        'label'    => __('Dark Mode Text', 'aqualuxe'),
        'section'  => 'aqualuxe_dark_mode',
        'type'     => 'text',
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_dark_mode_toggle_show_text', true);
        },
    ));
}