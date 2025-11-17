<?php
/**
 * General Customizer Settings
 *
 * @package AquaLuxe
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Add general settings to the Customizer
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_register_general($wp_customize) {
    // Add General section
    $wp_customize->add_section('aqualuxe_general', array(
        'title' => esc_html__('General Settings', 'aqualuxe'),
        'priority' => 20,
    ));

    // Container Width
    $wp_customize->add_setting('aqualuxe_container_width', array(
        'default' => 1280,
        'transport' => 'postMessage',
        'sanitize_callback' => 'absint',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Slider($wp_customize, 'aqualuxe_container_width', array(
        'label' => esc_html__('Container Width', 'aqualuxe'),
        'description' => esc_html__('Set the maximum width of the content container.', 'aqualuxe'),
        'section' => 'aqualuxe_general',
        'priority' => 10,
        'min' => 960,
        'max' => 1600,
        'step' => 10,
        'unit' => 'px',
    )));

    // Button Border Radius
    $wp_customize->add_setting('aqualuxe_button_radius', array(
        'default' => 8,
        'transport' => 'postMessage',
        'sanitize_callback' => 'absint',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Slider($wp_customize, 'aqualuxe_button_radius', array(
        'label' => esc_html__('Button Border Radius', 'aqualuxe'),
        'description' => esc_html__('Set the border radius for buttons.', 'aqualuxe'),
        'section' => 'aqualuxe_general',
        'priority' => 20,
        'min' => 0,
        'max' => 50,
        'step' => 1,
        'unit' => 'px',
    )));

    // Card Border Radius
    $wp_customize->add_setting('aqualuxe_card_radius', array(
        'default' => 12,
        'transport' => 'postMessage',
        'sanitize_callback' => 'absint',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Slider($wp_customize, 'aqualuxe_card_radius', array(
        'label' => esc_html__('Card Border Radius', 'aqualuxe'),
        'description' => esc_html__('Set the border radius for cards and panels.', 'aqualuxe'),
        'section' => 'aqualuxe_general',
        'priority' => 30,
        'min' => 0,
        'max' => 50,
        'step' => 1,
        'unit' => 'px',
    )));

    // Breadcrumbs
    $wp_customize->add_setting('aqualuxe_enable_breadcrumbs', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Toggle($wp_customize, 'aqualuxe_enable_breadcrumbs', array(
        'label' => esc_html__('Enable Breadcrumbs', 'aqualuxe'),
        'description' => esc_html__('Show breadcrumb navigation on pages.', 'aqualuxe'),
        'section' => 'aqualuxe_general',
        'priority' => 40,
    )));

    // Back to Top Button
    $wp_customize->add_setting('aqualuxe_enable_back_to_top', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Toggle($wp_customize, 'aqualuxe_enable_back_to_top', array(
        'label' => esc_html__('Enable Back to Top Button', 'aqualuxe'),
        'description' => esc_html__('Show a button to scroll back to the top of the page.', 'aqualuxe'),
        'section' => 'aqualuxe_general',
        'priority' => 50,
    )));

    // Page Transitions
    $wp_customize->add_setting('aqualuxe_enable_page_transitions', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Toggle($wp_customize, 'aqualuxe_enable_page_transitions', array(
        'label' => esc_html__('Enable Page Transitions', 'aqualuxe'),
        'description' => esc_html__('Add smooth transitions between pages.', 'aqualuxe'),
        'section' => 'aqualuxe_general',
        'priority' => 60,
    )));

    // Lazy Loading
    $wp_customize->add_setting('aqualuxe_enable_lazy_loading', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Toggle($wp_customize, 'aqualuxe_enable_lazy_loading', array(
        'label' => esc_html__('Enable Lazy Loading', 'aqualuxe'),
        'description' => esc_html__('Lazy load images for better performance.', 'aqualuxe'),
        'section' => 'aqualuxe_general',
        'priority' => 70,
    )));

    // Preloader
    $wp_customize->add_setting('aqualuxe_enable_preloader', array(
        'default' => false,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Toggle($wp_customize, 'aqualuxe_enable_preloader', array(
        'label' => esc_html__('Enable Preloader', 'aqualuxe'),
        'description' => esc_html__('Show a preloader animation while the page is loading.', 'aqualuxe'),
        'section' => 'aqualuxe_general',
        'priority' => 80,
    )));

    // Preloader Style
    $wp_customize->add_setting('aqualuxe_preloader_style', array(
        'default' => 'spinner',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));

    $wp_customize->add_control('aqualuxe_preloader_style', array(
        'label' => esc_html__('Preloader Style', 'aqualuxe'),
        'description' => esc_html__('Select the preloader animation style.', 'aqualuxe'),
        'section' => 'aqualuxe_general',
        'type' => 'select',
        'choices' => array(
            'spinner' => esc_html__('Spinner', 'aqualuxe'),
            'dots' => esc_html__('Dots', 'aqualuxe'),
            'circle' => esc_html__('Circle', 'aqualuxe'),
            'logo' => esc_html__('Logo', 'aqualuxe'),
        ),
        'priority' => 90,
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_preloader', false);
        },
    ));

    // Custom CSS
    $wp_customize->add_setting('aqualuxe_custom_css', array(
        'default' => '',
        'sanitize_callback' => 'wp_strip_all_tags',
    ));

    $wp_customize->add_control('aqualuxe_custom_css', array(
        'label' => esc_html__('Custom CSS', 'aqualuxe'),
        'description' => esc_html__('Add your custom CSS here.', 'aqualuxe'),
        'section' => 'aqualuxe_general',
        'type' => 'textarea',
        'priority' => 100,
    ));
}
add_action('customize_register', 'aqualuxe_customize_register_general');