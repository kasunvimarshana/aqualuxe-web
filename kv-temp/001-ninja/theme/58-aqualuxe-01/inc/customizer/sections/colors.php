<?php
/**
 * Colors settings section
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add color settings section
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_register_colors($wp_customize) {
    // Add section
    $wp_customize->add_section('aqualuxe_color_settings', array(
        'title'       => __('Color Settings', 'aqualuxe'),
        'description' => __('Customize the theme colors', 'aqualuxe'),
        'panel'       => 'aqualuxe_theme_options',
        'priority'    => 50,
    ));

    // Primary color
    $wp_customize->add_setting('aqualuxe_options[primary_color]', array(
        'default'           => '#0077b6',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_options[primary_color]', array(
        'label'       => __('Primary Color', 'aqualuxe'),
        'description' => __('Set the primary theme color', 'aqualuxe'),
        'section'     => 'aqualuxe_color_settings',
    )));

    // Secondary color
    $wp_customize->add_setting('aqualuxe_options[secondary_color]', array(
        'default'           => '#00b4d8',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_options[secondary_color]', array(
        'label'       => __('Secondary Color', 'aqualuxe'),
        'description' => __('Set the secondary theme color', 'aqualuxe'),
        'section'     => 'aqualuxe_color_settings',
    )));

    // Accent color
    $wp_customize->add_setting('aqualuxe_options[accent_color]', array(
        'default'           => '#90e0ef',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_options[accent_color]', array(
        'label'       => __('Accent Color', 'aqualuxe'),
        'description' => __('Set the accent theme color', 'aqualuxe'),
        'section'     => 'aqualuxe_color_settings',
    )));

    // Text color
    $wp_customize->add_setting('aqualuxe_options[text_color]', array(
        'default'           => '#333333',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_options[text_color]', array(
        'label'       => __('Text Color', 'aqualuxe'),
        'description' => __('Set the main text color', 'aqualuxe'),
        'section'     => 'aqualuxe_color_settings',
    )));

    // Heading color
    $wp_customize->add_setting('aqualuxe_options[heading_color]', array(
        'default'           => '#222222',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_options[heading_color]', array(
        'label'       => __('Heading Color', 'aqualuxe'),
        'description' => __('Set the color for headings', 'aqualuxe'),
        'section'     => 'aqualuxe_color_settings',
    )));

    // Background color
    $wp_customize->add_setting('aqualuxe_options[background_color]', array(
        'default'           => '#ffffff',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_options[background_color]', array(
        'label'       => __('Background Color', 'aqualuxe'),
        'description' => __('Set the main background color', 'aqualuxe'),
        'section'     => 'aqualuxe_color_settings',
    )));

    // Dark mode colors
    $wp_customize->add_setting('aqualuxe_options[dark_background_color]', array(
        'default'           => '#121212',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_options[dark_background_color]', array(
        'label'       => __('Dark Mode Background Color', 'aqualuxe'),
        'description' => __('Set the background color for dark mode', 'aqualuxe'),
        'section'     => 'aqualuxe_color_settings',
    )));

    $wp_customize->add_setting('aqualuxe_options[dark_text_color]', array(
        'default'           => '#e0e0e0',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_options[dark_text_color]', array(
        'label'       => __('Dark Mode Text Color', 'aqualuxe'),
        'description' => __('Set the text color for dark mode', 'aqualuxe'),
        'section'     => 'aqualuxe_color_settings',
    )));
    
    // Link color
    $wp_customize->add_setting('aqualuxe_options[link_color]', array(
        'default'           => '#0077b6',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_options[link_color]', array(
        'label'       => __('Link Color', 'aqualuxe'),
        'description' => __('Set the color for links', 'aqualuxe'),
        'section'     => 'aqualuxe_color_settings',
    )));
    
    // Link hover color
    $wp_customize->add_setting('aqualuxe_options[link_hover_color]', array(
        'default'           => '#005b8a',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_options[link_hover_color]', array(
        'label'       => __('Link Hover Color', 'aqualuxe'),
        'description' => __('Set the color for links on hover', 'aqualuxe'),
        'section'     => 'aqualuxe_color_settings',
    )));
    
    // Button text color
    $wp_customize->add_setting('aqualuxe_options[button_text_color]', array(
        'default'           => '#ffffff',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_options[button_text_color]', array(
        'label'       => __('Button Text Color', 'aqualuxe'),
        'description' => __('Set the text color for buttons', 'aqualuxe'),
        'section'     => 'aqualuxe_color_settings',
    )));
    
    // Button hover text color
    $wp_customize->add_setting('aqualuxe_options[button_hover_text_color]', array(
        'default'           => '#ffffff',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_options[button_hover_text_color]', array(
        'label'       => __('Button Hover Text Color', 'aqualuxe'),
        'description' => __('Set the text color for buttons on hover', 'aqualuxe'),
        'section'     => 'aqualuxe_color_settings',
    )));
    
    // Border color
    $wp_customize->add_setting('aqualuxe_options[border_color]', array(
        'default'           => '#eeeeee',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_options[border_color]', array(
        'label'       => __('Border Color', 'aqualuxe'),
        'description' => __('Set the color for borders', 'aqualuxe'),
        'section'     => 'aqualuxe_color_settings',
    )));
    
    // Input background color
    $wp_customize->add_setting('aqualuxe_options[input_background_color]', array(
        'default'           => '#ffffff',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_options[input_background_color]', array(
        'label'       => __('Input Background Color', 'aqualuxe'),
        'description' => __('Set the background color for form inputs', 'aqualuxe'),
        'section'     => 'aqualuxe_color_settings',
    )));
    
    // Input text color
    $wp_customize->add_setting('aqualuxe_options[input_text_color]', array(
        'default'           => '#333333',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_options[input_text_color]', array(
        'label'       => __('Input Text Color', 'aqualuxe'),
        'description' => __('Set the text color for form inputs', 'aqualuxe'),
        'section'     => 'aqualuxe_color_settings',
    )));
    
    // Input border color
    $wp_customize->add_setting('aqualuxe_options[input_border_color]', array(
        'default'           => '#dddddd',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_options[input_border_color]', array(
        'label'       => __('Input Border Color', 'aqualuxe'),
        'description' => __('Set the border color for form inputs', 'aqualuxe'),
        'section'     => 'aqualuxe_color_settings',
    )));
    
    // Input focus border color
    $wp_customize->add_setting('aqualuxe_options[input_focus_border_color]', array(
        'default'           => '#0077b6',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_options[input_focus_border_color]', array(
        'label'       => __('Input Focus Border Color', 'aqualuxe'),
        'description' => __('Set the border color for form inputs when focused', 'aqualuxe'),
        'section'     => 'aqualuxe_color_settings',
    )));
    
    // Success color
    $wp_customize->add_setting('aqualuxe_options[success_color]', array(
        'default'           => '#4CAF50',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_options[success_color]', array(
        'label'       => __('Success Color', 'aqualuxe'),
        'description' => __('Set the color for success messages and indicators', 'aqualuxe'),
        'section'     => 'aqualuxe_color_settings',
    )));
    
    // Warning color
    $wp_customize->add_setting('aqualuxe_options[warning_color]', array(
        'default'           => '#FFC107',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_options[warning_color]', array(
        'label'       => __('Warning Color', 'aqualuxe'),
        'description' => __('Set the color for warning messages and indicators', 'aqualuxe'),
        'section'     => 'aqualuxe_color_settings',
    )));
    
    // Error color
    $wp_customize->add_setting('aqualuxe_options[error_color]', array(
        'default'           => '#F44336',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_options[error_color]', array(
        'label'       => __('Error Color', 'aqualuxe'),
        'description' => __('Set the color for error messages and indicators', 'aqualuxe'),
        'section'     => 'aqualuxe_color_settings',
    )));
    
    // Info color
    $wp_customize->add_setting('aqualuxe_options[info_color]', array(
        'default'           => '#2196F3',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_options[info_color]', array(
        'label'       => __('Info Color', 'aqualuxe'),
        'description' => __('Set the color for info messages and indicators', 'aqualuxe'),
        'section'     => 'aqualuxe_color_settings',
    )));
}
add_action('customize_register', 'aqualuxe_customize_register_colors');