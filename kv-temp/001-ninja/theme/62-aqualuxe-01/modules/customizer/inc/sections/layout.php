<?php
/**
 * AquaLuxe Customizer Layout Section
 *
 * @package AquaLuxe
 * @subpackage Modules/Customizer
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Register layout section
 *
 * @param WP_Customize_Manager $wp_customize Customizer manager
 * @return void
 */
function aqualuxe_customizer_register_layout_section( $wp_customize ) {
    // Add section
    $wp_customize->add_section( 'aqualuxe_layout', array(
        'title' => __( 'Layout Settings', 'aqualuxe' ),
        'description' => __( 'Customize the layout.', 'aqualuxe' ),
        'panel' => 'aqualuxe_theme_options',
        'priority' => 60,
    ) );
    
    // Global layout
    $wp_customize->add_setting( 'aqualuxe_global_layout', array(
        'default' => 'right-sidebar',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_global_layout', array(
        'label' => __( 'Global Layout', 'aqualuxe' ),
        'section' => 'aqualuxe_layout',
        'type' => 'select',
        'choices' => array(
            'right-sidebar' => __( 'Right Sidebar', 'aqualuxe' ),
            'left-sidebar' => __( 'Left Sidebar', 'aqualuxe' ),
            'no-sidebar' => __( 'No Sidebar', 'aqualuxe' ),
        ),
    ) );
    
    // Page layout
    $wp_customize->add_setting( 'aqualuxe_page_layout', array(
        'default' => 'default',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_page_layout', array(
        'label' => __( 'Page Layout', 'aqualuxe' ),
        'section' => 'aqualuxe_layout',
        'type' => 'select',
        'choices' => array(
            'default' => __( 'Default', 'aqualuxe' ),
            'right-sidebar' => __( 'Right Sidebar', 'aqualuxe' ),
            'left-sidebar' => __( 'Left Sidebar', 'aqualuxe' ),
            'no-sidebar' => __( 'No Sidebar', 'aqualuxe' ),
        ),
    ) );
    
    // Post layout
    $wp_customize->add_setting( 'aqualuxe_post_layout', array(
        'default' => 'default',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_post_layout', array(
        'label' => __( 'Post Layout', 'aqualuxe' ),
        'section' => 'aqualuxe_layout',
        'type' => 'select',
        'choices' => array(
            'default' => __( 'Default', 'aqualuxe' ),
            'right-sidebar' => __( 'Right Sidebar', 'aqualuxe' ),
            'left-sidebar' => __( 'Left Sidebar', 'aqualuxe' ),
            'no-sidebar' => __( 'No Sidebar', 'aqualuxe' ),
        ),
    ) );
    
    // Archive layout
    $wp_customize->add_setting( 'aqualuxe_archive_layout', array(
        'default' => 'default',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_archive_layout', array(
        'label' => __( 'Archive Layout', 'aqualuxe' ),
        'section' => 'aqualuxe_layout',
        'type' => 'select',
        'choices' => array(
            'default' => __( 'Default', 'aqualuxe' ),
            'right-sidebar' => __( 'Right Sidebar', 'aqualuxe' ),
            'left-sidebar' => __( 'Left Sidebar', 'aqualuxe' ),
            'no-sidebar' => __( 'No Sidebar', 'aqualuxe' ),
        ),
    ) );
    
    // Search layout
    $wp_customize->add_setting( 'aqualuxe_search_layout', array(
        'default' => 'default',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_search_layout', array(
        'label' => __( 'Search Layout', 'aqualuxe' ),
        'section' => 'aqualuxe_layout',
        'type' => 'select',
        'choices' => array(
            'default' => __( 'Default', 'aqualuxe' ),
            'right-sidebar' => __( 'Right Sidebar', 'aqualuxe' ),
            'left-sidebar' => __( 'Left Sidebar', 'aqualuxe' ),
            'no-sidebar' => __( 'No Sidebar', 'aqualuxe' ),
        ),
    ) );
    
    // 404 layout
    $wp_customize->add_setting( 'aqualuxe_404_layout', array(
        'default' => 'no-sidebar',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_404_layout', array(
        'label' => __( '404 Layout', 'aqualuxe' ),
        'section' => 'aqualuxe_layout',
        'type' => 'select',
        'choices' => array(
            'default' => __( 'Default', 'aqualuxe' ),
            'right-sidebar' => __( 'Right Sidebar', 'aqualuxe' ),
            'left-sidebar' => __( 'Left Sidebar', 'aqualuxe' ),
            'no-sidebar' => __( 'No Sidebar', 'aqualuxe' ),
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
        'section' => 'aqualuxe_layout',
        'type' => 'number',
        'input_attrs' => array(
            'min' => 50,
            'max' => 100,
            'step' => 1,
        ),
    ) );
    
    // Sidebar width
    $wp_customize->add_setting( 'aqualuxe_sidebar_width', array(
        'default' => '30',
        'sanitize_callback' => 'aqualuxe_sanitize_number',
        'transport' => 'postMessage',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_sidebar_width', array(
        'label' => __( 'Sidebar Width (%)', 'aqualuxe' ),
        'section' => 'aqualuxe_layout',
        'type' => 'number',
        'input_attrs' => array(
            'min' => 0,
            'max' => 50,
            'step' => 1,
        ),
    ) );
    
    // Container width
    $wp_customize->add_setting( 'aqualuxe_container_width', array(
        'default' => '1200',
        'sanitize_callback' => 'aqualuxe_sanitize_number',
        'transport' => 'postMessage',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_container_width', array(
        'label' => __( 'Container Width (px)', 'aqualuxe' ),
        'section' => 'aqualuxe_layout',
        'type' => 'number',
        'input_attrs' => array(
            'min' => 800,
            'max' => 1600,
            'step' => 10,
        ),
    ) );
    
    // Container padding
    $wp_customize->add_setting( 'aqualuxe_container_padding', array(
        'default' => '15',
        'sanitize_callback' => 'aqualuxe_sanitize_number',
        'transport' => 'postMessage',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_container_padding', array(
        'label' => __( 'Container Padding (px)', 'aqualuxe' ),
        'section' => 'aqualuxe_layout',
        'type' => 'number',
        'input_attrs' => array(
            'min' => 0,
            'max' => 50,
            'step' => 5,
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
        'section' => 'aqualuxe_layout',
        'type' => 'number',
        'input_attrs' => array(
            'min' => 0,
            'max' => 100,
            'step' => 5,
        ),
    ) );
    
    // Boxed layout
    $wp_customize->add_setting( 'aqualuxe_boxed_layout', array(
        'default' => false,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_boxed_layout', array(
        'label' => __( 'Enable Boxed Layout', 'aqualuxe' ),
        'section' => 'aqualuxe_layout',
        'type' => 'checkbox',
    ) );
    
    // Boxed layout width
    $wp_customize->add_setting( 'aqualuxe_boxed_width', array(
        'default' => '1200',
        'sanitize_callback' => 'aqualuxe_sanitize_number',
        'transport' => 'postMessage',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_boxed_width', array(
        'label' => __( 'Boxed Layout Width (px)', 'aqualuxe' ),
        'section' => 'aqualuxe_layout',
        'type' => 'number',
        'input_attrs' => array(
            'min' => 800,
            'max' => 1600,
            'step' => 10,
        ),
    ) );
    
    // Boxed layout background color
    $wp_customize->add_setting( 'aqualuxe_boxed_bg_color', array(
        'default' => '#f5f5f5',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'postMessage',
    ) );
    
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'aqualuxe_boxed_bg_color', array(
        'label' => __( 'Boxed Layout Background Color', 'aqualuxe' ),
        'section' => 'aqualuxe_layout',
    ) ) );
    
    // Boxed layout background image
    $wp_customize->add_setting( 'aqualuxe_boxed_bg_image', array(
        'default' => '',
        'sanitize_callback' => 'aqualuxe_sanitize_image',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'aqualuxe_boxed_bg_image', array(
        'label' => __( 'Boxed Layout Background Image', 'aqualuxe' ),
        'section' => 'aqualuxe_layout',
        'settings' => 'aqualuxe_boxed_bg_image',
    ) ) );
    
    // Boxed layout background repeat
    $wp_customize->add_setting( 'aqualuxe_boxed_bg_repeat', array(
        'default' => 'repeat',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
        'transport' => 'postMessage',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_boxed_bg_repeat', array(
        'label' => __( 'Boxed Layout Background Repeat', 'aqualuxe' ),
        'section' => 'aqualuxe_layout',
        'type' => 'select',
        'choices' => array(
            'no-repeat' => __( 'No Repeat', 'aqualuxe' ),
            'repeat' => __( 'Repeat', 'aqualuxe' ),
            'repeat-x' => __( 'Repeat X', 'aqualuxe' ),
            'repeat-y' => __( 'Repeat Y', 'aqualuxe' ),
        ),
    ) );
    
    // Boxed layout background position
    $wp_customize->add_setting( 'aqualuxe_boxed_bg_position', array(
        'default' => 'center center',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
        'transport' => 'postMessage',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_boxed_bg_position', array(
        'label' => __( 'Boxed Layout Background Position', 'aqualuxe' ),
        'section' => 'aqualuxe_layout',
        'type' => 'select',
        'choices' => array(
            'left top' => __( 'Left Top', 'aqualuxe' ),
            'left center' => __( 'Left Center', 'aqualuxe' ),
            'left bottom' => __( 'Left Bottom', 'aqualuxe' ),
            'center top' => __( 'Center Top', 'aqualuxe' ),
            'center center' => __( 'Center Center', 'aqualuxe' ),
            'center bottom' => __( 'Center Bottom', 'aqualuxe' ),
            'right top' => __( 'Right Top', 'aqualuxe' ),
            'right center' => __( 'Right Center', 'aqualuxe' ),
            'right bottom' => __( 'Right Bottom', 'aqualuxe' ),
        ),
    ) );
    
    // Boxed layout background attachment
    $wp_customize->add_setting( 'aqualuxe_boxed_bg_attachment', array(
        'default' => 'scroll',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
        'transport' => 'postMessage',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_boxed_bg_attachment', array(
        'label' => __( 'Boxed Layout Background Attachment', 'aqualuxe' ),
        'section' => 'aqualuxe_layout',
        'type' => 'select',
        'choices' => array(
            'scroll' => __( 'Scroll', 'aqualuxe' ),
            'fixed' => __( 'Fixed', 'aqualuxe' ),
        ),
    ) );
    
    // Boxed layout background size
    $wp_customize->add_setting( 'aqualuxe_boxed_bg_size', array(
        'default' => 'auto',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
        'transport' => 'postMessage',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_boxed_bg_size', array(
        'label' => __( 'Boxed Layout Background Size', 'aqualuxe' ),
        'section' => 'aqualuxe_layout',
        'type' => 'select',
        'choices' => array(
            'auto' => __( 'Auto', 'aqualuxe' ),
            'cover' => __( 'Cover', 'aqualuxe' ),
            'contain' => __( 'Contain', 'aqualuxe' ),
        ),
    ) );
    
    // Boxed layout shadow
    $wp_customize->add_setting( 'aqualuxe_boxed_shadow', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        'transport' => 'postMessage',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_boxed_shadow', array(
        'label' => __( 'Enable Boxed Layout Shadow', 'aqualuxe' ),
        'section' => 'aqualuxe_layout',
        'type' => 'checkbox',
    ) );
    
    // Boxed layout border radius
    $wp_customize->add_setting( 'aqualuxe_boxed_border_radius', array(
        'default' => '0',
        'sanitize_callback' => 'aqualuxe_sanitize_number',
        'transport' => 'postMessage',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_boxed_border_radius', array(
        'label' => __( 'Boxed Layout Border Radius (px)', 'aqualuxe' ),
        'section' => 'aqualuxe_layout',
        'type' => 'number',
        'input_attrs' => array(
            'min' => 0,
            'max' => 50,
            'step' => 1,
        ),
    ) );
}