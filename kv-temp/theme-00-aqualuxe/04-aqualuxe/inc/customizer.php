<?php
/**
 * AquaLuxe Customizer
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add postMessage support for site title and description for the Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Customizer object.
 */
function aqualuxe_customize_register($wp_customize) {
    // Add theme options panel
    $wp_customize->add_panel('aqualuxe_options', array(
        'title' => __('AquaLuxe Options', 'aqualuxe'),
        'priority' => 160,
    ));
    
    // Add color options section
    $wp_customize->add_section('aqualuxe_colors', array(
        'title' => __('Color Options', 'aqualuxe'),
        'panel' => 'aqualuxe_options',
        'priority' => 10,
    ));
    
    // Primary color setting
    $wp_customize->add_setting('aqualuxe_primary_color', array(
        'default' => '#0073e6',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'postMessage',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_primary_color', array(
        'label' => __('Primary Color', 'aqualuxe'),
        'section' => 'aqualuxe_colors',
        'settings' => 'aqualuxe_primary_color',
    )));
    
    // Secondary color setting
    $wp_customize->add_setting('aqualuxe_secondary_color', array(
        'default' => '#005a87',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'postMessage',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_secondary_color', array(
        'label' => __('Secondary Color', 'aqualuxe'),
        'section' => 'aqualuxe_colors',
        'settings' => 'aqualuxe_secondary_color',
    )));
    
    // Add layout options section
    $wp_customize->add_section('aqualuxe_layout', array(
        'title' => __('Layout Options', 'aqualuxe'),
        'panel' => 'aqualuxe_options',
        'priority' => 20,
    ));
    
    // Product columns setting
    $wp_customize->add_setting('aqualuxe_product_columns', array(
        'default' => 4,
        'sanitize_callback' => 'absint',
    ));
    
    $wp_customize->add_control('aqualuxe_product_columns', array(
        'label' => __('Product Columns', 'aqualuxe'),
        'section' => 'aqualuxe_layout',
        'type' => 'select',
        'choices' => array(
            2 => __('2 Columns', 'aqualuxe'),
            3 => __('3 Columns', 'aqualuxe'),
            4 => __('4 Columns', 'aqualuxe'),
            5 => __('5 Columns', 'aqualuxe'),
        ),
    ));
    
    // Add typography options section
    $wp_customize->add_section('aqualuxe_typography', array(
        'title' => __('Typography', 'aqualuxe'),
        'panel' => 'aqualuxe_options',
        'priority' => 30,
    ));
    
    // Heading font setting
    $wp_customize->add_setting('aqualuxe_heading_font', array(
        'default' => 'Arial, sans-serif',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('aqualuxe_heading_font', array(
        'label' => __('Heading Font', 'aqualuxe'),
        'section' => 'aqualuxe_typography',
        'type' => 'text',
    ));
    
    // Body font setting
    $wp_customize->add_setting('aqualuxe_body_font', array(
        'default' => 'Arial, sans-serif',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('aqualuxe_body_font', array(
        'label' => __('Body Font', 'aqualuxe'),
        'section' => 'aqualuxe_typography',
        'type' => 'text',
    ));
}
add_action('customize_register', 'aqualuxe_customize_register');

/**
 * Binds JS handlers to make Customizer preview reload changes asynchronously.
 */
function aqualuxe_customize_preview_js() {
    wp_enqueue_script('aqualuxe-customizer', get_stylesheet_directory_uri() . '/assets/js/customizer.js', array('customize-preview'), '1.0.0', true);
}
add_action('customize_preview_init', 'aqualuxe_customize_preview_js');

/**
 * Output customizer styles
 */
function aqualuxe_customizer_css() {
    $primary_color = get_theme_mod('aqualuxe_primary_color', '#0073e6');
    $secondary_color = get_theme_mod('aqualuxe_secondary_color', '#005a87');
    $heading_font = get_theme_mod('aqualuxe_heading_font', 'Arial, sans-serif');
    $body_font = get_theme_mod('aqualuxe_body_font', 'Arial, sans-serif');
    
    $css = '';
    
    // Primary color
    if ($primary_color !== '#0073e6') {
        $css .= "
        :root {
            --aqualuxe-primary: {$primary_color};
        }
        a,
        .woocommerce-pagination .page-numbers li .page-numbers.current {
            color: {$primary_color};
        }
        .button,
        .woocommerce #respond input#submit,
        .woocommerce a.button,
        .woocommerce button.button,
        .woocommerce input.button {
            background-color: {$primary_color};
        }";
    }
    
    // Secondary color
    if ($secondary_color !== '#005a87') {
        $css .= "
        :root {
            --aqualuxe-secondary: {$secondary_color};
        }
        .button:hover,
        .woocommerce #respond input#submit:hover,
        .woocommerce a.button:hover,
        .woocommerce button.button:hover,
        .woocommerce input.button:hover {
            background-color: {$secondary_color};
        }";
    }
    
    // Fonts
    if ($heading_font !== 'Arial, sans-serif') {
        $css .= "
        h1, h2, h3, h4, h5, h6 {
            font-family: {$heading_font};
        }";
    }
    
    if ($body_font !== 'Arial, sans-serif') {
        $css .= "
        body {
            font-family: {$body_font};
        }";
    }
    
    if (!empty($css)) {
        echo '<style type="text/css">' . $css . '</style>';
    }
}
add_action('wp_head', 'aqualuxe_customizer_css', 100);