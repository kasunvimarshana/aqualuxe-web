<?php
/**
 * AquaLuxe Customizer Header Section
 *
 * @package AquaLuxe
 * @subpackage Modules/Customizer
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Register header section
 *
 * @param WP_Customize_Manager $wp_customize Customizer manager
 * @return void
 */
function aqualuxe_customizer_register_header_section( $wp_customize ) {
    // Add section
    $wp_customize->add_section( 'aqualuxe_header', array(
        'title' => __( 'Header Settings', 'aqualuxe' ),
        'description' => __( 'Customize the header section.', 'aqualuxe' ),
        'panel' => 'aqualuxe_theme_options',
        'priority' => 20,
    ) );
    
    // Header layout
    $wp_customize->add_setting( 'aqualuxe_header_layout', array(
        'default' => 'default',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_header_layout', array(
        'label' => __( 'Header Layout', 'aqualuxe' ),
        'section' => 'aqualuxe_header',
        'type' => 'select',
        'choices' => aqualuxe_get_header_layouts(),
    ) );
    
    // Header height
    $wp_customize->add_setting( 'aqualuxe_header_height', array(
        'default' => '80',
        'sanitize_callback' => 'aqualuxe_sanitize_number',
        'transport' => 'postMessage',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_header_height', array(
        'label' => __( 'Header Height (px)', 'aqualuxe' ),
        'section' => 'aqualuxe_header',
        'type' => 'number',
        'input_attrs' => array(
            'min' => 50,
            'max' => 200,
            'step' => 5,
        ),
    ) );
    
    // Header background color
    $wp_customize->add_setting( 'aqualuxe_header_bg_color', array(
        'default' => '#ffffff',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'postMessage',
    ) );
    
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'aqualuxe_header_bg_color', array(
        'label' => __( 'Header Background Color', 'aqualuxe' ),
        'section' => 'aqualuxe_header',
    ) ) );
    
    // Header text color
    $wp_customize->add_setting( 'aqualuxe_header_text_color', array(
        'default' => '#333333',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'postMessage',
    ) );
    
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'aqualuxe_header_text_color', array(
        'label' => __( 'Header Text Color', 'aqualuxe' ),
        'section' => 'aqualuxe_header',
    ) ) );
    
    // Header border
    $wp_customize->add_setting( 'aqualuxe_header_border', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_header_border', array(
        'label' => __( 'Show Header Border', 'aqualuxe' ),
        'section' => 'aqualuxe_header',
        'type' => 'checkbox',
    ) );
    
    // Header border color
    $wp_customize->add_setting( 'aqualuxe_header_border_color', array(
        'default' => '#eeeeee',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'postMessage',
    ) );
    
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'aqualuxe_header_border_color', array(
        'label' => __( 'Header Border Color', 'aqualuxe' ),
        'section' => 'aqualuxe_header',
    ) ) );
    
    // Sticky header
    $wp_customize->add_setting( 'aqualuxe_sticky_header', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_sticky_header', array(
        'label' => __( 'Enable Sticky Header', 'aqualuxe' ),
        'section' => 'aqualuxe_header',
        'type' => 'checkbox',
    ) );
    
    // Sticky header background color
    $wp_customize->add_setting( 'aqualuxe_sticky_header_bg_color', array(
        'default' => '#ffffff',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'postMessage',
    ) );
    
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'aqualuxe_sticky_header_bg_color', array(
        'label' => __( 'Sticky Header Background Color', 'aqualuxe' ),
        'section' => 'aqualuxe_header',
    ) ) );
    
    // Sticky header text color
    $wp_customize->add_setting( 'aqualuxe_sticky_header_text_color', array(
        'default' => '#333333',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'postMessage',
    ) );
    
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'aqualuxe_sticky_header_text_color', array(
        'label' => __( 'Sticky Header Text Color', 'aqualuxe' ),
        'section' => 'aqualuxe_header',
    ) ) );
    
    // Logo
    $wp_customize->add_setting( 'aqualuxe_logo', array(
        'default' => '',
        'sanitize_callback' => 'aqualuxe_sanitize_image',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'aqualuxe_logo', array(
        'label' => __( 'Logo', 'aqualuxe' ),
        'section' => 'aqualuxe_header',
        'settings' => 'aqualuxe_logo',
    ) ) );
    
    // Logo size
    $wp_customize->add_setting( 'aqualuxe_header_logo_size', array(
        'default' => '150',
        'sanitize_callback' => 'aqualuxe_sanitize_number',
        'transport' => 'postMessage',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_header_logo_size', array(
        'label' => __( 'Logo Max Width (px)', 'aqualuxe' ),
        'section' => 'aqualuxe_header',
        'type' => 'number',
        'input_attrs' => array(
            'min' => 50,
            'max' => 500,
            'step' => 5,
        ),
    ) );
    
    // Retina logo
    $wp_customize->add_setting( 'aqualuxe_retina_logo', array(
        'default' => '',
        'sanitize_callback' => 'aqualuxe_sanitize_image',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'aqualuxe_retina_logo', array(
        'label' => __( 'Retina Logo (2x)', 'aqualuxe' ),
        'section' => 'aqualuxe_header',
        'settings' => 'aqualuxe_retina_logo',
    ) ) );
    
    // Mobile logo
    $wp_customize->add_setting( 'aqualuxe_mobile_logo', array(
        'default' => '',
        'sanitize_callback' => 'aqualuxe_sanitize_image',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'aqualuxe_mobile_logo', array(
        'label' => __( 'Mobile Logo', 'aqualuxe' ),
        'section' => 'aqualuxe_header',
        'settings' => 'aqualuxe_mobile_logo',
    ) ) );
    
    // Top bar
    $wp_customize->add_setting( 'aqualuxe_top_bar', array(
        'default' => false,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_top_bar', array(
        'label' => __( 'Show Top Bar', 'aqualuxe' ),
        'section' => 'aqualuxe_header',
        'type' => 'checkbox',
    ) );
    
    // Top bar background color
    $wp_customize->add_setting( 'aqualuxe_top_bar_bg_color', array(
        'default' => '#f5f5f5',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'postMessage',
    ) );
    
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'aqualuxe_top_bar_bg_color', array(
        'label' => __( 'Top Bar Background Color', 'aqualuxe' ),
        'section' => 'aqualuxe_header',
    ) ) );
    
    // Top bar text color
    $wp_customize->add_setting( 'aqualuxe_top_bar_text_color', array(
        'default' => '#333333',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'postMessage',
    ) );
    
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'aqualuxe_top_bar_text_color', array(
        'label' => __( 'Top Bar Text Color', 'aqualuxe' ),
        'section' => 'aqualuxe_header',
    ) ) );
    
    // Top bar left content
    $wp_customize->add_setting( 'aqualuxe_top_bar_left', array(
        'default' => '',
        'sanitize_callback' => 'aqualuxe_sanitize_html',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_top_bar_left', array(
        'label' => __( 'Top Bar Left Content', 'aqualuxe' ),
        'section' => 'aqualuxe_header',
        'type' => 'textarea',
    ) );
    
    // Top bar right content
    $wp_customize->add_setting( 'aqualuxe_top_bar_right', array(
        'default' => '',
        'sanitize_callback' => 'aqualuxe_sanitize_html',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_top_bar_right', array(
        'label' => __( 'Top Bar Right Content', 'aqualuxe' ),
        'section' => 'aqualuxe_header',
        'type' => 'textarea',
    ) );
    
    // Header phone
    $wp_customize->add_setting( 'aqualuxe_header_phone', array(
        'default' => '',
        'sanitize_callback' => 'aqualuxe_sanitize_text',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_header_phone', array(
        'label' => __( 'Header Phone', 'aqualuxe' ),
        'section' => 'aqualuxe_header',
        'type' => 'text',
    ) );
    
    // Header email
    $wp_customize->add_setting( 'aqualuxe_header_email', array(
        'default' => '',
        'sanitize_callback' => 'aqualuxe_sanitize_email',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_header_email', array(
        'label' => __( 'Header Email', 'aqualuxe' ),
        'section' => 'aqualuxe_header',
        'type' => 'email',
    ) );
    
    // Header button text
    $wp_customize->add_setting( 'aqualuxe_header_button_text', array(
        'default' => '',
        'sanitize_callback' => 'aqualuxe_sanitize_text',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_header_button_text', array(
        'label' => __( 'Header Button Text', 'aqualuxe' ),
        'section' => 'aqualuxe_header',
        'type' => 'text',
    ) );
    
    // Header button URL
    $wp_customize->add_setting( 'aqualuxe_header_button_url', array(
        'default' => '',
        'sanitize_callback' => 'aqualuxe_sanitize_url',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_header_button_url', array(
        'label' => __( 'Header Button URL', 'aqualuxe' ),
        'section' => 'aqualuxe_header',
        'type' => 'url',
    ) );
    
    // Header button style
    $wp_customize->add_setting( 'aqualuxe_header_button_style', array(
        'default' => 'primary',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_header_button_style', array(
        'label' => __( 'Header Button Style', 'aqualuxe' ),
        'section' => 'aqualuxe_header',
        'type' => 'select',
        'choices' => array(
            'primary' => __( 'Primary', 'aqualuxe' ),
            'secondary' => __( 'Secondary', 'aqualuxe' ),
            'outline' => __( 'Outline', 'aqualuxe' ),
            'link' => __( 'Link', 'aqualuxe' ),
        ),
    ) );
    
    // Show search
    $wp_customize->add_setting( 'aqualuxe_header_search', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_header_search', array(
        'label' => __( 'Show Search', 'aqualuxe' ),
        'section' => 'aqualuxe_header',
        'type' => 'checkbox',
    ) );
    
    // Show social icons
    $wp_customize->add_setting( 'aqualuxe_header_social', array(
        'default' => false,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_header_social', array(
        'label' => __( 'Show Social Icons', 'aqualuxe' ),
        'section' => 'aqualuxe_header',
        'type' => 'checkbox',
    ) );
    
    // Mobile menu style
    $wp_customize->add_setting( 'aqualuxe_mobile_menu_style', array(
        'default' => 'slide',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_mobile_menu_style', array(
        'label' => __( 'Mobile Menu Style', 'aqualuxe' ),
        'section' => 'aqualuxe_header',
        'type' => 'select',
        'choices' => array(
            'slide' => __( 'Slide', 'aqualuxe' ),
            'dropdown' => __( 'Dropdown', 'aqualuxe' ),
            'fullscreen' => __( 'Fullscreen', 'aqualuxe' ),
        ),
    ) );
    
    // Mobile menu breakpoint
    $wp_customize->add_setting( 'aqualuxe_mobile_menu_breakpoint', array(
        'default' => '768',
        'sanitize_callback' => 'aqualuxe_sanitize_number',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_mobile_menu_breakpoint', array(
        'label' => __( 'Mobile Menu Breakpoint (px)', 'aqualuxe' ),
        'section' => 'aqualuxe_header',
        'type' => 'number',
        'input_attrs' => array(
            'min' => 480,
            'max' => 1200,
            'step' => 1,
        ),
    ) );
}