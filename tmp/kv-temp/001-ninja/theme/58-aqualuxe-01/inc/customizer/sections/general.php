<?php
/**
 * General settings section
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add general settings section
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_register_general($wp_customize) {
    // Add panel
    $wp_customize->add_panel('aqualuxe_theme_options', array(
        'title'       => __('AquaLuxe Theme Options', 'aqualuxe'),
        'description' => __('Customize your AquaLuxe theme settings', 'aqualuxe'),
        'priority'    => 130,
    ));

    // Add section
    $wp_customize->add_section('aqualuxe_general_settings', array(
        'title'       => __('General Settings', 'aqualuxe'),
        'description' => __('General theme settings', 'aqualuxe'),
        'panel'       => 'aqualuxe_theme_options',
        'priority'    => 10,
    ));

    // Add settings
    $wp_customize->add_setting('aqualuxe_options[container_width]', array(
        'default'           => '1280px',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control('aqualuxe_options[container_width]', array(
        'label'       => __('Container Width', 'aqualuxe'),
        'description' => __('Set the maximum width of the content container (e.g., 1280px)', 'aqualuxe'),
        'section'     => 'aqualuxe_general_settings',
        'type'        => 'text',
    ));

    // Preloader
    $wp_customize->add_setting('aqualuxe_options[enable_preloader]', array(
        'default'           => true,
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('aqualuxe_options[enable_preloader]', array(
        'label'       => __('Enable Preloader', 'aqualuxe'),
        'description' => __('Show a loading animation while the page loads', 'aqualuxe'),
        'section'     => 'aqualuxe_general_settings',
        'type'        => 'checkbox',
    ));

    // Back to top button
    $wp_customize->add_setting('aqualuxe_options[enable_back_to_top]', array(
        'default'           => true,
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('aqualuxe_options[enable_back_to_top]', array(
        'label'       => __('Enable Back to Top Button', 'aqualuxe'),
        'description' => __('Show a button to scroll back to the top of the page', 'aqualuxe'),
        'section'     => 'aqualuxe_general_settings',
        'type'        => 'checkbox',
    ));

    // Smooth scroll
    $wp_customize->add_setting('aqualuxe_options[enable_smooth_scroll]', array(
        'default'           => true,
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('aqualuxe_options[enable_smooth_scroll]', array(
        'label'       => __('Enable Smooth Scrolling', 'aqualuxe'),
        'description' => __('Enable smooth scrolling effect on the page', 'aqualuxe'),
        'section'     => 'aqualuxe_general_settings',
        'type'        => 'checkbox',
    ));

    // Breadcrumbs
    $wp_customize->add_setting('aqualuxe_options[enable_breadcrumbs]', array(
        'default'           => true,
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('aqualuxe_options[enable_breadcrumbs]', array(
        'label'       => __('Enable Breadcrumbs', 'aqualuxe'),
        'description' => __('Show breadcrumb navigation on pages', 'aqualuxe'),
        'section'     => 'aqualuxe_general_settings',
        'type'        => 'checkbox',
    ));
    
    // Favicon
    $wp_customize->add_setting('aqualuxe_options[favicon]', array(
        'default'           => '',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'aqualuxe_options[favicon]', array(
        'label'       => __('Favicon', 'aqualuxe'),
        'description' => __('Upload a favicon for your site (recommended size: 32x32px)', 'aqualuxe'),
        'section'     => 'aqualuxe_general_settings',
    )));
    
    // Site loading animation
    $wp_customize->add_setting('aqualuxe_options[loading_animation]', array(
        'default'           => 'fade',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));

    $wp_customize->add_control('aqualuxe_options[loading_animation]', array(
        'label'       => __('Page Loading Animation', 'aqualuxe'),
        'description' => __('Select the animation style when loading pages', 'aqualuxe'),
        'section'     => 'aqualuxe_general_settings',
        'type'        => 'select',
        'choices'     => array(
            'none'     => __('None', 'aqualuxe'),
            'fade'     => __('Fade', 'aqualuxe'),
            'slide-up' => __('Slide Up', 'aqualuxe'),
            'zoom-in'  => __('Zoom In', 'aqualuxe'),
        ),
    ));
    
    // Page transition
    $wp_customize->add_setting('aqualuxe_options[page_transition]', array(
        'default'           => 'fade',
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));

    $wp_customize->add_control('aqualuxe_options[page_transition]', array(
        'label'       => __('Page Transition Effect', 'aqualuxe'),
        'description' => __('Select the transition effect between pages', 'aqualuxe'),
        'section'     => 'aqualuxe_general_settings',
        'type'        => 'select',
        'choices'     => array(
            'none'     => __('None', 'aqualuxe'),
            'fade'     => __('Fade', 'aqualuxe'),
            'slide'    => __('Slide', 'aqualuxe'),
            'zoom'     => __('Zoom', 'aqualuxe'),
        ),
    ));
    
    // Lazy loading
    $wp_customize->add_setting('aqualuxe_options[enable_lazy_loading]', array(
        'default'           => true,
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('aqualuxe_options[enable_lazy_loading]', array(
        'label'       => __('Enable Lazy Loading', 'aqualuxe'),
        'description' => __('Load images only when they enter the viewport', 'aqualuxe'),
        'section'     => 'aqualuxe_general_settings',
        'type'        => 'checkbox',
    ));
    
    // Image lightbox
    $wp_customize->add_setting('aqualuxe_options[enable_image_lightbox]', array(
        'default'           => true,
        'type'              => 'option',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('aqualuxe_options[enable_image_lightbox]', array(
        'label'       => __('Enable Image Lightbox', 'aqualuxe'),
        'description' => __('Open images in a lightbox when clicked', 'aqualuxe'),
        'section'     => 'aqualuxe_general_settings',
        'type'        => 'checkbox',
    ));
}
add_action('customize_register', 'aqualuxe_customize_register_general');