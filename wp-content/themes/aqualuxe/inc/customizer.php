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

if (!function_exists('aqualuxe_customize_register')) {
    /**
     * Add postMessage support for site title and description for the Theme Customizer.
     *
     * @param WP_Customize_Manager $wp_customize Theme Customizer object.
     */
    function aqualuxe_customize_register($wp_customize) {
        // Add theme options panel
        $wp_customize->add_panel('aqualuxe_options', array(
            'title'       => __('Theme Options', 'aqualuxe'),
            'description' => __('Customize various aspects of the theme.', 'aqualuxe'),
            'priority'    => 160,
        ));

        // Add header section
        $wp_customize->add_section('aqualuxe_header', array(
            'title'       => __('Header', 'aqualuxe'),
            'description' => __('Customize the header area.', 'aqualuxe'),
            'panel'       => 'aqualuxe_options',
            'priority'    => 10,
        ));

        // Add sticky header setting
        $wp_customize->add_setting('aqualuxe_sticky_header', array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        ));

        $wp_customize->add_control('aqualuxe_sticky_header', array(
            'label'       => __('Enable Sticky Header', 'aqualuxe'),
            'description' => __('Make the header stick to the top of the page when scrolling.', 'aqualuxe'),
            'section'     => 'aqualuxe_header',
            'type'        => 'checkbox',
            'priority'    => 10,
        ));

        // Add WooCommerce section
        $wp_customize->add_section('aqualuxe_woocommerce', array(
            'title'       => __('WooCommerce', 'aqualuxe'),
            'description' => __('Customize WooCommerce settings.', 'aqualuxe'),
            'panel'       => 'aqualuxe_options',
            'priority'    => 20,
        ));

        // Add quick view setting
        $wp_customize->add_setting('aqualuxe_quick_view', array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        ));

        $wp_customize->add_control('aqualuxe_quick_view', array(
            'label'       => __('Enable Quick View', 'aqualuxe'),
            'description' => __('Enable quick view functionality for products.', 'aqualuxe'),
            'section'     => 'aqualuxe_woocommerce',
            'type'        => 'checkbox',
            'priority'    => 10,
        ));

        // Add product hover effect setting
        $wp_customize->add_setting('aqualuxe_product_hover_effect', array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        ));

        $wp_customize->add_control('aqualuxe_product_hover_effect', array(
            'label'       => __('Enable Product Hover Effect', 'aqualuxe'),
            'description' => __('Enable hover effect for product images.', 'aqualuxe'),
            'section'     => 'aqualuxe_woocommerce',
            'type'        => 'checkbox',
            'priority'    => 20,
        ));

        // Add footer section
        $wp_customize->add_section('aqualuxe_footer', array(
            'title'       => __('Footer', 'aqualuxe'),
            'description' => __('Customize the footer area.', 'aqualuxe'),
            'panel'       => 'aqualuxe_options',
            'priority'    => 30,
        ));

        // Add copyright text setting
        $wp_customize->add_setting('aqualuxe_copyright_text', array(
            'default'           => __('Copyright &copy; [year] [sitename]. All rights reserved.', 'aqualuxe'),
            'sanitize_callback' => 'aqualuxe_sanitize_text',
        ));

        $wp_customize->add_control('aqualuxe_copyright_text', array(
            'label'       => __('Copyright Text', 'aqualuxe'),
            'description' => __('Enter the copyright text to display in the footer. Use [year] for current year and [sitename] for site name.', 'aqualuxe'),
            'section'     => 'aqualuxe_footer',
            'type'        => 'textarea',
            'priority'    => 10,
        ));

        // Add color section
        $wp_customize->add_section('aqualuxe_colors', array(
            'title'       => __('Colors', 'aqualuxe'),
            'description' => __('Customize the color scheme.', 'aqualuxe'),
            'panel'       => 'aqualuxe_options',
            'priority'    => 40,
        ));

        // Add primary color setting
        $wp_customize->add_setting('aqualuxe_primary_color', array(
            'default'           => '#00a896',
            'sanitize_callback' => 'sanitize_hex_color',
        ));

        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_primary_color', array(
            'label'       => __('Primary Color', 'aqualuxe'),
            'description' => __('Choose the primary color for the theme.', 'aqualuxe'),
            'section'     => 'aqualuxe_colors',
            'priority'    => 10,
        )));

        // Add secondary color setting
        $wp_customize->add_setting('aqualuxe_secondary_color', array(
            'default'           => '#025951',
            'sanitize_callback' => 'sanitize_hex_color',
        ));

        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_secondary_color', array(
            'label'       => __('Secondary Color', 'aqualuxe'),
            'description' => __('Choose the secondary color for the theme.', 'aqualuxe'),
            'section'     => 'aqualuxe_colors',
            'priority'    => 20,
        )));

        // Add accent color setting
        $wp_customize->add_setting('aqualuxe_accent_color', array(
            'default'           => '#f0c808',
            'sanitize_callback' => 'sanitize_hex_color',
        ));

        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_accent_color', array(
            'label'       => __('Accent Color', 'aqualuxe'),
            'description' => __('Choose the accent color for the theme.', 'aqualuxe'),
            'section'     => 'aqualuxe_colors',
            'priority'    => 30,
        )));
    }
}
add_action('customize_register', 'aqualuxe_customize_register');

if (!function_exists('aqualuxe_sanitize_checkbox')) {
    /**
     * Sanitize checkbox input.
     *
     * @param bool $input Checkbox input.
     * @return bool Sanitized input.
     */
    function aqualuxe_sanitize_checkbox($input) {
        return (bool) $input;
    }
}

if (!function_exists('aqualuxe_sanitize_text')) {
    /**
     * Sanitize text input.
     *
     * @param string $input Text input.
     * @return string Sanitized input.
     */
    function aqualuxe_sanitize_text($input) {
        return sanitize_text_field($input);
    }
}

if (!function_exists('aqualuxe_customize_preview_js')) {
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
}
add_action('customize_preview_init', 'aqualuxe_customize_preview_js');