<?php
/**
 * AquaLuxe Customizer General Section
 *
 * @package AquaLuxe
 * @subpackage Modules/Customizer
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Register general section
 *
 * @param WP_Customize_Manager $wp_customize Customizer manager
 * @return void
 */
function aqualuxe_customizer_register_general_section( $wp_customize ) {
    // Add section
    $wp_customize->add_section( 'aqualuxe_general', array(
        'title' => __( 'General Settings', 'aqualuxe' ),
        'description' => __( 'General theme settings.', 'aqualuxe' ),
        'panel' => 'aqualuxe_theme_options',
        'priority' => 10,
    ) );
    
    // Site layout
    $wp_customize->add_setting( 'aqualuxe_site_layout', array(
        'default' => 'wide',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_site_layout', array(
        'label' => __( 'Site Layout', 'aqualuxe' ),
        'section' => 'aqualuxe_general',
        'type' => 'select',
        'choices' => aqualuxe_get_site_layouts(),
    ) );
    
    // Container width
    $wp_customize->add_setting( 'aqualuxe_container_width', array(
        'default' => '1200',
        'sanitize_callback' => 'aqualuxe_sanitize_number',
        'transport' => 'postMessage',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_container_width', array(
        'label' => __( 'Container Width (px)', 'aqualuxe' ),
        'section' => 'aqualuxe_general',
        'type' => 'number',
        'input_attrs' => array(
            'min' => 800,
            'max' => 1600,
            'step' => 10,
        ),
    ) );
    
    // Content width
    $wp_customize->add_setting( 'aqualuxe_content_width', array(
        'default' => '70',
        'sanitize_callback' => 'aqualuxe_sanitize_number',
        'transport' => 'postMessage',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_content_width', array(
        'label' => __( 'Content Width (%)', 'aqualuxe' ),
        'section' => 'aqualuxe_general',
        'type' => 'number',
        'input_attrs' => array(
            'min' => 50,
            'max' => 100,
            'step' => 1,
        ),
    ) );
    
    // Sidebar position
    $wp_customize->add_setting( 'aqualuxe_sidebar_position', array(
        'default' => 'right',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_sidebar_position', array(
        'label' => __( 'Sidebar Position', 'aqualuxe' ),
        'section' => 'aqualuxe_general',
        'type' => 'select',
        'choices' => aqualuxe_get_sidebar_positions(),
    ) );
    
    // Border radius
    $wp_customize->add_setting( 'aqualuxe_border_radius', array(
        'default' => '4',
        'sanitize_callback' => 'aqualuxe_sanitize_number',
        'transport' => 'postMessage',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_border_radius', array(
        'label' => __( 'Border Radius (px)', 'aqualuxe' ),
        'section' => 'aqualuxe_general',
        'type' => 'number',
        'input_attrs' => array(
            'min' => 0,
            'max' => 20,
            'step' => 1,
        ),
    ) );
    
    // Content padding
    $wp_customize->add_setting( 'aqualuxe_content_padding', array(
        'default' => '30',
        'sanitize_callback' => 'aqualuxe_sanitize_number',
        'transport' => 'postMessage',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_content_padding', array(
        'label' => __( 'Content Padding (px)', 'aqualuxe' ),
        'section' => 'aqualuxe_general',
        'type' => 'number',
        'input_attrs' => array(
            'min' => 0,
            'max' => 100,
            'step' => 5,
        ),
    ) );
    
    // Breadcrumbs
    $wp_customize->add_setting( 'aqualuxe_breadcrumbs', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_breadcrumbs', array(
        'label' => __( 'Show Breadcrumbs', 'aqualuxe' ),
        'section' => 'aqualuxe_general',
        'type' => 'checkbox',
    ) );
    
    // Back to top button
    $wp_customize->add_setting( 'aqualuxe_back_to_top', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_back_to_top', array(
        'label' => __( 'Show Back to Top Button', 'aqualuxe' ),
        'section' => 'aqualuxe_general',
        'type' => 'checkbox',
    ) );
    
    // Preloader
    $wp_customize->add_setting( 'aqualuxe_preloader', array(
        'default' => false,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_preloader', array(
        'label' => __( 'Show Preloader', 'aqualuxe' ),
        'section' => 'aqualuxe_general',
        'type' => 'checkbox',
    ) );
    
    // Smooth scroll
    $wp_customize->add_setting( 'aqualuxe_smooth_scroll', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_smooth_scroll', array(
        'label' => __( 'Enable Smooth Scroll', 'aqualuxe' ),
        'section' => 'aqualuxe_general',
        'type' => 'checkbox',
    ) );
    
    // Custom CSS
    $wp_customize->add_setting( 'aqualuxe_custom_css', array(
        'default' => '',
        'sanitize_callback' => 'aqualuxe_sanitize_css',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_custom_css', array(
        'label' => __( 'Custom CSS', 'aqualuxe' ),
        'section' => 'aqualuxe_general',
        'type' => 'textarea',
    ) );
    
    // Custom JavaScript
    $wp_customize->add_setting( 'aqualuxe_custom_js', array(
        'default' => '',
        'sanitize_callback' => 'aqualuxe_sanitize_js',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_custom_js', array(
        'label' => __( 'Custom JavaScript', 'aqualuxe' ),
        'section' => 'aqualuxe_general',
        'type' => 'textarea',
    ) );
    
    // Google Analytics
    $wp_customize->add_setting( 'aqualuxe_google_analytics', array(
        'default' => '',
        'sanitize_callback' => 'aqualuxe_sanitize_text',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_google_analytics', array(
        'label' => __( 'Google Analytics ID', 'aqualuxe' ),
        'section' => 'aqualuxe_general',
        'type' => 'text',
        'description' => __( 'Enter your Google Analytics ID (e.g. UA-XXXXXXXX-X or G-XXXXXXXXXX)', 'aqualuxe' ),
    ) );
    
    // Favicon
    $wp_customize->add_setting( 'aqualuxe_favicon', array(
        'default' => '',
        'sanitize_callback' => 'aqualuxe_sanitize_image',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'aqualuxe_favicon', array(
        'label' => __( 'Favicon', 'aqualuxe' ),
        'section' => 'aqualuxe_general',
        'settings' => 'aqualuxe_favicon',
    ) ) );
    
    // Maintenance mode
    $wp_customize->add_setting( 'aqualuxe_maintenance_mode', array(
        'default' => false,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_maintenance_mode', array(
        'label' => __( 'Enable Maintenance Mode', 'aqualuxe' ),
        'section' => 'aqualuxe_general',
        'type' => 'checkbox',
    ) );
    
    // Maintenance mode message
    $wp_customize->add_setting( 'aqualuxe_maintenance_message', array(
        'default' => __( 'We are currently undergoing scheduled maintenance. Please check back soon.', 'aqualuxe' ),
        'sanitize_callback' => 'aqualuxe_sanitize_textarea',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_maintenance_message', array(
        'label' => __( 'Maintenance Mode Message', 'aqualuxe' ),
        'section' => 'aqualuxe_general',
        'type' => 'textarea',
    ) );
    
    // Maintenance mode background
    $wp_customize->add_setting( 'aqualuxe_maintenance_background', array(
        'default' => '',
        'sanitize_callback' => 'aqualuxe_sanitize_image',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'aqualuxe_maintenance_background', array(
        'label' => __( 'Maintenance Mode Background', 'aqualuxe' ),
        'section' => 'aqualuxe_general',
        'settings' => 'aqualuxe_maintenance_background',
    ) ) );
}