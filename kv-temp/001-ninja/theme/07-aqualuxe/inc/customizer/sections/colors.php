<?php
/**
 * Colors Customizer Settings
 *
 * @package AquaLuxe
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Add colors settings to the Customizer
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_register_colors($wp_customize) {
    // Add Colors section
    $wp_customize->add_section('aqualuxe_colors', array(
        'title' => esc_html__('Colors', 'aqualuxe'),
        'priority' => 80,
    ));

    // Brand Colors
    $wp_customize->add_setting('aqualuxe_colors_brand_heading', array(
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Heading($wp_customize, 'aqualuxe_colors_brand_heading', array(
        'label' => esc_html__('Brand Colors', 'aqualuxe'),
        'section' => 'aqualuxe_colors',
        'priority' => 10,
    )));

    // Primary Color
    $wp_customize->add_setting('aqualuxe_primary_color', array(
        'default' => '#0ea5e9',
        'transport' => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_primary_color', array(
        'label' => esc_html__('Primary Color', 'aqualuxe'),
        'description' => esc_html__('The main brand color used throughout the site.', 'aqualuxe'),
        'section' => 'aqualuxe_colors',
        'priority' => 20,
    )));

    // Secondary Color
    $wp_customize->add_setting('aqualuxe_secondary_color', array(
        'default' => '#8b5cf6',
        'transport' => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_secondary_color', array(
        'label' => esc_html__('Secondary Color', 'aqualuxe'),
        'description' => esc_html__('The secondary brand color used for accents and highlights.', 'aqualuxe'),
        'section' => 'aqualuxe_colors',
        'priority' => 30,
    )));

    // Accent Color
    $wp_customize->add_setting('aqualuxe_accent_color', array(
        'default' => '#eab308',
        'transport' => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_accent_color', array(
        'label' => esc_html__('Accent Color', 'aqualuxe'),
        'description' => esc_html__('The accent color used for special elements and highlights.', 'aqualuxe'),
        'section' => 'aqualuxe_colors',
        'priority' => 40,
    )));

    // Text Colors
    $wp_customize->add_setting('aqualuxe_colors_text_heading', array(
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Heading($wp_customize, 'aqualuxe_colors_text_heading', array(
        'label' => esc_html__('Text Colors', 'aqualuxe'),
        'section' => 'aqualuxe_colors',
        'priority' => 50,
    )));

    // Text Color
    $wp_customize->add_setting('aqualuxe_text_color', array(
        'default' => '#1f2937',
        'transport' => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_text_color', array(
        'label' => esc_html__('Text Color', 'aqualuxe'),
        'description' => esc_html__('The main text color used throughout the site.', 'aqualuxe'),
        'section' => 'aqualuxe_colors',
        'priority' => 60,
    )));

    // Heading Color
    $wp_customize->add_setting('aqualuxe_heading_color', array(
        'default' => '#111827',
        'transport' => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_heading_color', array(
        'label' => esc_html__('Heading Color', 'aqualuxe'),
        'description' => esc_html__('The color used for headings.', 'aqualuxe'),
        'section' => 'aqualuxe_colors',
        'priority' => 70,
    )));

    // Link Color
    $wp_customize->add_setting('aqualuxe_link_color', array(
        'default' => '#0ea5e9',
        'transport' => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_link_color', array(
        'label' => esc_html__('Link Color', 'aqualuxe'),
        'description' => esc_html__('The color used for links.', 'aqualuxe'),
        'section' => 'aqualuxe_colors',
        'priority' => 80,
    )));

    // Link Hover Color
    $wp_customize->add_setting('aqualuxe_link_hover_color', array(
        'default' => '#0369a1',
        'transport' => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_link_hover_color', array(
        'label' => esc_html__('Link Hover Color', 'aqualuxe'),
        'description' => esc_html__('The color used for links when hovered.', 'aqualuxe'),
        'section' => 'aqualuxe_colors',
        'priority' => 90,
    )));

    // Background Colors
    $wp_customize->add_setting('aqualuxe_colors_background_heading', array(
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Heading($wp_customize, 'aqualuxe_colors_background_heading', array(
        'label' => esc_html__('Background Colors', 'aqualuxe'),
        'section' => 'aqualuxe_colors',
        'priority' => 100,
    )));

    // Background Color
    $wp_customize->add_setting('aqualuxe_background_color', array(
        'default' => '#ffffff',
        'transport' => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_background_color', array(
        'label' => esc_html__('Background Color', 'aqualuxe'),
        'description' => esc_html__('The main background color used throughout the site.', 'aqualuxe'),
        'section' => 'aqualuxe_colors',
        'priority' => 110,
    )));

    // Secondary Background Color
    $wp_customize->add_setting('aqualuxe_secondary_background_color', array(
        'default' => '#f3f4f6',
        'transport' => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_secondary_background_color', array(
        'label' => esc_html__('Secondary Background Color', 'aqualuxe'),
        'description' => esc_html__('The secondary background color used for alternating sections.', 'aqualuxe'),
        'section' => 'aqualuxe_colors',
        'priority' => 120,
    )));

    // Button Colors
    $wp_customize->add_setting('aqualuxe_colors_button_heading', array(
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Heading($wp_customize, 'aqualuxe_colors_button_heading', array(
        'label' => esc_html__('Button Colors', 'aqualuxe'),
        'section' => 'aqualuxe_colors',
        'priority' => 130,
    )));

    // Primary Button Background
    $wp_customize->add_setting('aqualuxe_button_background_color', array(
        'default' => '#0ea5e9',
        'transport' => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_button_background_color', array(
        'label' => esc_html__('Button Background Color', 'aqualuxe'),
        'section' => 'aqualuxe_colors',
        'priority' => 140,
    )));

    // Primary Button Text
    $wp_customize->add_setting('aqualuxe_button_text_color', array(
        'default' => '#ffffff',
        'transport' => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_button_text_color', array(
        'label' => esc_html__('Button Text Color', 'aqualuxe'),
        'section' => 'aqualuxe_colors',
        'priority' => 150,
    )));

    // Primary Button Hover Background
    $wp_customize->add_setting('aqualuxe_button_hover_background_color', array(
        'default' => '#0369a1',
        'transport' => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_button_hover_background_color', array(
        'label' => esc_html__('Button Hover Background Color', 'aqualuxe'),
        'section' => 'aqualuxe_colors',
        'priority' => 160,
    )));

    // Primary Button Hover Text
    $wp_customize->add_setting('aqualuxe_button_hover_text_color', array(
        'default' => '#ffffff',
        'transport' => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_button_hover_text_color', array(
        'label' => esc_html__('Button Hover Text Color', 'aqualuxe'),
        'section' => 'aqualuxe_colors',
        'priority' => 170,
    )));

    // Secondary Button Background
    $wp_customize->add_setting('aqualuxe_secondary_button_background_color', array(
        'default' => '#8b5cf6',
        'transport' => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_secondary_button_background_color', array(
        'label' => esc_html__('Secondary Button Background Color', 'aqualuxe'),
        'section' => 'aqualuxe_colors',
        'priority' => 180,
    )));

    // Secondary Button Text
    $wp_customize->add_setting('aqualuxe_secondary_button_text_color', array(
        'default' => '#ffffff',
        'transport' => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_secondary_button_text_color', array(
        'label' => esc_html__('Secondary Button Text Color', 'aqualuxe'),
        'section' => 'aqualuxe_colors',
        'priority' => 190,
    )));

    // Form Colors
    $wp_customize->add_setting('aqualuxe_colors_form_heading', array(
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Heading($wp_customize, 'aqualuxe_colors_form_heading', array(
        'label' => esc_html__('Form Colors', 'aqualuxe'),
        'section' => 'aqualuxe_colors',
        'priority' => 200,
    )));

    // Form Input Background
    $wp_customize->add_setting('aqualuxe_form_background_color', array(
        'default' => '#ffffff',
        'transport' => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_form_background_color', array(
        'label' => esc_html__('Form Input Background Color', 'aqualuxe'),
        'section' => 'aqualuxe_colors',
        'priority' => 210,
    )));

    // Form Input Text
    $wp_customize->add_setting('aqualuxe_form_text_color', array(
        'default' => '#1f2937',
        'transport' => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_form_text_color', array(
        'label' => esc_html__('Form Input Text Color', 'aqualuxe'),
        'section' => 'aqualuxe_colors',
        'priority' => 220,
    )));

    // Form Input Border
    $wp_customize->add_setting('aqualuxe_form_border_color', array(
        'default' => '#d1d5db',
        'transport' => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_form_border_color', array(
        'label' => esc_html__('Form Input Border Color', 'aqualuxe'),
        'section' => 'aqualuxe_colors',
        'priority' => 230,
    )));

    // Form Input Focus Border
    $wp_customize->add_setting('aqualuxe_form_focus_border_color', array(
        'default' => '#0ea5e9',
        'transport' => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_form_focus_border_color', array(
        'label' => esc_html__('Form Input Focus Border Color', 'aqualuxe'),
        'section' => 'aqualuxe_colors',
        'priority' => 240,
    )));

    // Dark Mode Colors
    $wp_customize->add_setting('aqualuxe_colors_dark_mode_heading', array(
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Heading($wp_customize, 'aqualuxe_colors_dark_mode_heading', array(
        'label' => esc_html__('Dark Mode Colors', 'aqualuxe'),
        'section' => 'aqualuxe_colors',
        'priority' => 250,
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_dark_mode', true);
        },
    )));

    // Dark Mode Background
    $wp_customize->add_setting('aqualuxe_dark_mode_background_color', array(
        'default' => '#111827',
        'transport' => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_dark_mode_background_color', array(
        'label' => esc_html__('Dark Mode Background Color', 'aqualuxe'),
        'section' => 'aqualuxe_colors',
        'priority' => 260,
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_dark_mode', true);
        },
    )));

    // Dark Mode Secondary Background
    $wp_customize->add_setting('aqualuxe_dark_mode_secondary_background_color', array(
        'default' => '#1f2937',
        'transport' => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_dark_mode_secondary_background_color', array(
        'label' => esc_html__('Dark Mode Secondary Background Color', 'aqualuxe'),
        'section' => 'aqualuxe_colors',
        'priority' => 270,
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_dark_mode', true);
        },
    )));

    // Dark Mode Text
    $wp_customize->add_setting('aqualuxe_dark_mode_text_color', array(
        'default' => '#f9fafb',
        'transport' => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_dark_mode_text_color', array(
        'label' => esc_html__('Dark Mode Text Color', 'aqualuxe'),
        'section' => 'aqualuxe_colors',
        'priority' => 280,
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_dark_mode', true);
        },
    )));

    // Dark Mode Heading
    $wp_customize->add_setting('aqualuxe_dark_mode_heading_color', array(
        'default' => '#ffffff',
        'transport' => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_dark_mode_heading_color', array(
        'label' => esc_html__('Dark Mode Heading Color', 'aqualuxe'),
        'section' => 'aqualuxe_colors',
        'priority' => 290,
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_dark_mode', true);
        },
    )));

    // Dark Mode Link
    $wp_customize->add_setting('aqualuxe_dark_mode_link_color', array(
        'default' => '#38bdf8',
        'transport' => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_dark_mode_link_color', array(
        'label' => esc_html__('Dark Mode Link Color', 'aqualuxe'),
        'section' => 'aqualuxe_colors',
        'priority' => 300,
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_dark_mode', true);
        },
    )));

    // Dark Mode Border
    $wp_customize->add_setting('aqualuxe_dark_mode_border_color', array(
        'default' => '#374151',
        'transport' => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_dark_mode_border_color', array(
        'label' => esc_html__('Dark Mode Border Color', 'aqualuxe'),
        'section' => 'aqualuxe_colors',
        'priority' => 310,
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_dark_mode', true);
        },
    )));
}
add_action('customize_register', 'aqualuxe_customize_register_colors');