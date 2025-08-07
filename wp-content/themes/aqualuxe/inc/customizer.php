<?php
/**
 * Theme Customizer
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

if (!function_exists('aqualuxe_customize_register')) {
    /**
     * Add postMessage support for site title and description for the Theme Customizer.
     *
     * @param WP_Customize_Manager $wp_customize Theme Customizer object.
     */
    function aqualuxe_customize_register($wp_customize) {
        // Add theme options panel
        $wp_customize->add_panel('aqualuxe_options', array(
            'title' => esc_html__('AquaLuxe Options', 'aqualuxe'),
            'description' => esc_html__('Customize your AquaLuxe theme settings', 'aqualuxe'),
            'priority' => 160,
        ));
        
        // Add color scheme section
        $wp_customize->add_section('aqualuxe_colors', array(
            'title' => esc_html__('Color Scheme', 'aqualuxe'),
            'panel' => 'aqualuxe_options',
            'priority' => 10,
        ));
        
        // Add primary color setting
        $wp_customize->add_setting('aqualuxe_primary_color', array(
            'default' => '#007cba',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'postMessage',
        ));
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_primary_color', array(
            'label' => esc_html__('Primary Color', 'aqualuxe'),
            'section' => 'aqualuxe_colors',
            'settings' => 'aqualuxe_primary_color',
        )));
        
        // Add secondary color setting
        $wp_customize->add_setting('aqualuxe_secondary_color', array(
            'default' => '#005a87',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'postMessage',
        ));
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_secondary_color', array(
            'label' => esc_html__('Secondary Color', 'aqualuxe'),
            'section' => 'aqualuxe_colors',
            'settings' => 'aqualuxe_secondary_color',
        )));
        
        // Add header section
        $wp_customize->add_section('aqualuxe_header', array(
            'title' => esc_html__('Header Options', 'aqualuxe'),
            'panel' => 'aqualuxe_options',
            'priority' => 20,
        ));
        
        // Add sticky header option
        $wp_customize->add_setting('aqualuxe_sticky_header', array(
            'default' => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        ));
        
        $wp_customize->add_control('aqualuxe_sticky_header', array(
            'label' => esc_html__('Enable Sticky Header', 'aqualuxe'),
            'section' => 'aqualuxe_header',
            'type' => 'checkbox',
        ));
        
        // Add typography section
        $wp_customize->add_section('aqualuxe_typography', array(
            'title' => esc_html__('Typography', 'aqualuxe'),
            'panel' => 'aqualuxe_options',
            'priority' => 30,
        ));
        
        // Add body font setting
        $wp_customize->add_setting('aqualuxe_body_font', array(
            'default' => 'Arial, sans-serif',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));
        
        $wp_customize->add_control('aqualuxe_body_font', array(
            'label' => esc_html__('Body Font Family', 'aqualuxe'),
            'section' => 'aqualuxe_typography',
            'type' => 'text',
        ));
        
        // Add heading font setting
        $wp_customize->add_setting('aqualuxe_heading_font', array(
            'default' => 'Georgia, serif',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));
        
        $wp_customize->add_control('aqualuxe_heading_font', array(
            'label' => esc_html__('Heading Font Family', 'aqualuxe'),
            'section' => 'aqualuxe_typography',
            'type' => 'text',
        ));
    }
}

/**
 * Sanitize checkbox
 *
 * @param bool $checked Whether the checkbox is checked.
 * @return bool Whether the checkbox is checked.
 */
function aqualuxe_sanitize_checkbox($checked) {
    return ((isset($checked) && true == $checked) ? true : false);
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function aqualuxe_customize_preview_js() {
    wp_enqueue_script(
        'aqualuxe-customizer',
        get_stylesheet_directory_uri() . '/assets/js/customizer.js',
        array('customize-preview'),
        AQUALUXE_VERSION,
        true
    );
}
add_action('customize_preview_init', 'aqualuxe_customize_preview_js');