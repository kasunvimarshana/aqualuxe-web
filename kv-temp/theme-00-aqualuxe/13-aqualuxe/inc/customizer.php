<?php
/**
 * Theme Customizer - Luxury Ornamental Fish Theme
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
            'description' => esc_html__('Customize your AquaLuxe luxury theme settings', 'aqualuxe'),
            'priority' => 160,
        ));
        
        // Add color scheme section
        $wp_customize->add_section('aqualuxe_colors', array(
            'title' => esc_html__('Luxury Color Scheme', 'aqualuxe'),
            'panel' => 'aqualuxe_options',
            'priority' => 10,
            'description' => esc_html__('Customize the luxury color scheme of your theme', 'aqualuxe'),
        ));
        
        // Add primary color setting
        $wp_customize->add_setting('aqualuxe_primary_color', array(
            'default' => '#00a896',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'postMessage',
            'description' => esc_html__('Primary color for buttons, links, and accents', 'aqualuxe'),
        ));
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_primary_color', array(
            'label' => esc_html__('Primary Color', 'aqualuxe'),
            'section' => 'aqualuxe_colors',
            'settings' => 'aqualuxe_primary_color',
        )));
        
        // Add secondary color setting
        $wp_customize->add_setting('aqualuxe_secondary_color', array(
            'default' => '#025951',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'postMessage',
            'description' => esc_html__('Secondary color for gradients and backgrounds', 'aqualuxe'),
        ));
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_secondary_color', array(
            'label' => esc_html__('Secondary Color', 'aqualuxe'),
            'section' => 'aqualuxe_colors',
            'settings' => 'aqualuxe_secondary_color',
        )));
        
        // Add accent color setting
        $wp_customize->add_setting('aqualuxe_accent_color', array(
            'default' => '#f0c808',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'postMessage',
            'description' => esc_html__('Accent color for highlights and special elements', 'aqualuxe'),
        ));
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_accent_color', array(
            'label' => esc_html__('Accent Color', 'aqualuxe'),
            'section' => 'aqualuxe_colors',
            'settings' => 'aqualuxe_accent_color',
        )));
        
        // Add header section
        $wp_customize->add_section('aqualuxe_header', array(
            'title' => esc_html__('Header Options', 'aqualuxe'),
            'panel' => 'aqualuxe_options',
            'priority' => 20,
            'description' => esc_html__('Customize the header of your luxury theme', 'aqualuxe'),
        ));
        
        // Add sticky header option
        $wp_customize->add_setting('aqualuxe_sticky_header', array(
            'default' => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'description' => esc_html__('Enable sticky header that stays at the top when scrolling', 'aqualuxe'),
        ));
        
        $wp_customize->add_control('aqualuxe_sticky_header', array(
            'label' => esc_html__('Enable Sticky Header', 'aqualuxe'),
            'section' => 'aqualuxe_header',
            'type' => 'checkbox',
        ));
        
        // Add header background option
        $wp_customize->add_setting('aqualuxe_header_background', array(
            'default' => 'gradient',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
            'description' => esc_html__('Choose header background style', 'aqualuxe'),
        ));
        
        $wp_customize->add_control('aqualuxe_header_background', array(
            'label' => esc_html__('Header Background', 'aqualuxe'),
            'section' => 'aqualuxe_header',
            'type' => 'select',
            'choices' => array(
                'gradient' => esc_html__('Gradient', 'aqualuxe'),
                'solid' => esc_html__('Solid Color', 'aqualuxe'),
                'transparent' => esc_html__('Transparent', 'aqualuxe'),
            ),
        ));
        
        // Add typography section
        $wp_customize->add_section('aqualuxe_typography', array(
            'title' => esc_html__('Luxury Typography', 'aqualuxe'),
            'panel' => 'aqualuxe_options',
            'priority' => 30,
            'description' => esc_html__('Customize the typography of your luxury theme', 'aqualuxe'),
        ));
        
        // Add body font setting
        $wp_customize->add_setting('aqualuxe_body_font', array(
            'default' => '\'Playfair Display\', Georgia, serif',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
            'description' => esc_html__('Font for body text', 'aqualuxe'),
        ));
        
        $wp_customize->add_control('aqualuxe_body_font', array(
            'label' => esc_html__('Body Font Family', 'aqualuxe'),
            'section' => 'aqualuxe_typography',
            'type' => 'text',
        ));
        
        // Add heading font setting
        $wp_customize->add_setting('aqualuxe_heading_font', array(
            'default' => '\'Cormorant Garamond\', \'Playfair Display\', serif',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
            'description' => esc_html__('Font for headings', 'aqualuxe'),
        ));
        
        $wp_customize->add_control('aqualuxe_heading_font', array(
            'label' => esc_html__('Heading Font Family', 'aqualuxe'),
            'section' => 'aqualuxe_typography',
            'type' => 'text',
        ));
        
        // Add font size setting
        $wp_customize->add_setting('aqualuxe_base_font_size', array(
            'default' => '16px',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
            'description' => esc_html__('Base font size for body text', 'aqualuxe'),
        ));
        
        $wp_customize->add_control('aqualuxe_base_font_size', array(
            'label' => esc_html__('Base Font Size', 'aqualuxe'),
            'section' => 'aqualuxe_typography',
            'type' => 'text',
        ));
        
        // Add layout section
        $wp_customize->add_section('aqualuxe_layout', array(
            'title' => esc_html__('Layout Options', 'aqualuxe'),
            'panel' => 'aqualuxe_options',
            'priority' => 40,
            'description' => esc_html__('Customize the layout of your luxury theme', 'aqualuxe'),
        ));
        
        // Add container width setting
        $wp_customize->add_setting('aqualuxe_container_width', array(
            'default' => '1200px',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
            'description' => esc_html__('Maximum width of the content container', 'aqualuxe'),
        ));
        
        $wp_customize->add_control('aqualuxe_container_width', array(
            'label' => esc_html__('Container Width', 'aqualuxe'),
            'section' => 'aqualuxe_layout',
            'type' => 'text',
        ));
        
        // Add spacing setting
        $wp_customize->add_setting('aqualuxe_base_spacing', array(
            'default' => '2rem',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
            'description' => esc_html__('Base spacing unit for margins and padding', 'aqualuxe'),
        ));
        
        $wp_customize->add_control('aqualuxe_base_spacing', array(
            'label' => esc_html__('Base Spacing', 'aqualuxe'),
            'section' => 'aqualuxe_layout',
            'type' => 'text',
        ));
        
        // Add WooCommerce section
        $wp_customize->add_section('aqualuxe_woocommerce', array(
            'title' => esc_html__('WooCommerce Options', 'aqualuxe'),
            'panel' => 'aqualuxe_options',
            'priority' => 50,
            'description' => esc_html__('Customize the WooCommerce features of your luxury theme', 'aqualuxe'),
        ));
        
        // Add product columns setting
        $wp_customize->add_setting('aqualuxe_product_columns', array(
            'default' => '3',
            'sanitize_callback' => 'absint',
            'description' => esc_html__('Number of products per row in the shop', 'aqualuxe'),
        ));
        
        $wp_customize->add_control('aqualuxe_product_columns', array(
            'label' => esc_html__('Product Columns', 'aqualuxe'),
            'section' => 'aqualuxe_woocommerce',
            'type' => 'select',
            'choices' => array(
                '2' => esc_html__('2 Columns', 'aqualuxe'),
                '3' => esc_html__('3 Columns', 'aqualuxe'),
                '4' => esc_html__('4 Columns', 'aqualuxe'),
            ),
        ));
        
        // Add product hover effect setting
        $wp_customize->add_setting('aqualuxe_product_hover_effect', array(
            'default' => 'luxury',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
            'description' => esc_html__('Choose product hover effect', 'aqualuxe'),
        ));
        
        $wp_customize->add_control('aqualuxe_product_hover_effect', array(
            'label' => esc_html__('Product Hover Effect', 'aqualuxe'),
            'section' => 'aqualuxe_woocommerce',
            'type' => 'select',
            'choices' => array(
                'luxury' => esc_html__('Luxury Lift', 'aqualuxe'),
                'zoom' => esc_html__('Image Zoom', 'aqualuxe'),
                'none' => esc_html__('None', 'aqualuxe'),
            ),
        ));
        
        // Add quick view setting
        $wp_customize->add_setting('aqualuxe_quick_view', array(
            'default' => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'description' => esc_html__('Enable product quick view feature', 'aqualuxe'),
        ));
        
        $wp_customize->add_control('aqualuxe_quick_view', array(
            'label' => esc_html__('Enable Quick View', 'aqualuxe'),
            'section' => 'aqualuxe_woocommerce',
            'type' => 'checkbox',
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
 * Sanitize select
 *
 * @param string $input The select value.
 * @param WP_Customize_Setting $setting The setting object.
 * @return string The sanitized value.
 */
function aqualuxe_sanitize_select($input, $setting) {
    // Ensure input is a string
    $input = (string) $input;
    
    // Get list of choices from the control associated with the setting
    $choices = $setting->manager->get_control($setting->id)->choices;
    
    // Return input if it's a valid choice, otherwise return the default
    return (array_key_exists($input, $choices) ? $input : $setting->default);
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