<?php
/**
 * AquaLuxe Customizer Colors Section
 *
 * @package AquaLuxe
 * @subpackage Modules/Customizer
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Register colors section
 *
 * @param WP_Customize_Manager $wp_customize Customizer manager
 * @return void
 */
function aqualuxe_customizer_register_colors_section( $wp_customize ) {
    // Add section
    $wp_customize->add_section( 'aqualuxe_colors', array(
        'title' => __( 'Color Settings', 'aqualuxe' ),
        'description' => __( 'Customize the colors.', 'aqualuxe' ),
        'panel' => 'aqualuxe_theme_options',
        'priority' => 50,
    ) );
    
    // Primary color
    $wp_customize->add_setting( 'aqualuxe_primary_color', array(
        'default' => '#0073aa',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'postMessage',
    ) );
    
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'aqualuxe_primary_color', array(
        'label' => __( 'Primary Color', 'aqualuxe' ),
        'section' => 'aqualuxe_colors',
    ) ) );
    
    // Secondary color
    $wp_customize->add_setting( 'aqualuxe_secondary_color', array(
        'default' => '#6c757d',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'postMessage',
    ) );
    
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'aqualuxe_secondary_color', array(
        'label' => __( 'Secondary Color', 'aqualuxe' ),
        'section' => 'aqualuxe_colors',
    ) ) );
    
    // Accent color
    $wp_customize->add_setting( 'aqualuxe_accent_color', array(
        'default' => '#f0ad4e',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'postMessage',
    ) );
    
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'aqualuxe_accent_color', array(
        'label' => __( 'Accent Color', 'aqualuxe' ),
        'section' => 'aqualuxe_colors',
    ) ) );
    
    // Success color
    $wp_customize->add_setting( 'aqualuxe_success_color', array(
        'default' => '#5cb85c',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'postMessage',
    ) );
    
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'aqualuxe_success_color', array(
        'label' => __( 'Success Color', 'aqualuxe' ),
        'section' => 'aqualuxe_colors',
    ) ) );
    
    // Info color
    $wp_customize->add_setting( 'aqualuxe_info_color', array(
        'default' => '#5bc0de',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'postMessage',
    ) );
    
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'aqualuxe_info_color', array(
        'label' => __( 'Info Color', 'aqualuxe' ),
        'section' => 'aqualuxe_colors',
    ) ) );
    
    // Warning color
    $wp_customize->add_setting( 'aqualuxe_warning_color', array(
        'default' => '#f0ad4e',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'postMessage',
    ) );
    
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'aqualuxe_warning_color', array(
        'label' => __( 'Warning Color', 'aqualuxe' ),
        'section' => 'aqualuxe_colors',
    ) ) );
    
    // Danger color
    $wp_customize->add_setting( 'aqualuxe_danger_color', array(
        'default' => '#d9534f',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'postMessage',
    ) );
    
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'aqualuxe_danger_color', array(
        'label' => __( 'Danger Color', 'aqualuxe' ),
        'section' => 'aqualuxe_colors',
    ) ) );
    
    // Body background color
    $wp_customize->add_setting( 'aqualuxe_body_bg_color', array(
        'default' => '#ffffff',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'postMessage',
    ) );
    
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'aqualuxe_body_bg_color', array(
        'label' => __( 'Body Background Color', 'aqualuxe' ),
        'section' => 'aqualuxe_colors',
    ) ) );
    
    // Text color
    $wp_customize->add_setting( 'aqualuxe_text_color', array(
        'default' => '#333333',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'postMessage',
    ) );
    
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'aqualuxe_text_color', array(
        'label' => __( 'Text Color', 'aqualuxe' ),
        'section' => 'aqualuxe_colors',
    ) ) );
    
    // Heading color
    $wp_customize->add_setting( 'aqualuxe_heading_color', array(
        'default' => '#333333',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'postMessage',
    ) );
    
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'aqualuxe_heading_color', array(
        'label' => __( 'Heading Color', 'aqualuxe' ),
        'section' => 'aqualuxe_colors',
    ) ) );
    
    // Link color
    $wp_customize->add_setting( 'aqualuxe_link_color', array(
        'default' => '#0073aa',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'postMessage',
    ) );
    
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'aqualuxe_link_color', array(
        'label' => __( 'Link Color', 'aqualuxe' ),
        'section' => 'aqualuxe_colors',
    ) ) );
    
    // Link hover color
    $wp_customize->add_setting( 'aqualuxe_link_hover_color', array(
        'default' => '#00a0d2',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'postMessage',
    ) );
    
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'aqualuxe_link_hover_color', array(
        'label' => __( 'Link Hover Color', 'aqualuxe' ),
        'section' => 'aqualuxe_colors',
    ) ) );
    
    // Border color
    $wp_customize->add_setting( 'aqualuxe_border_color', array(
        'default' => '#eeeeee',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'postMessage',
    ) );
    
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'aqualuxe_border_color', array(
        'label' => __( 'Border Color', 'aqualuxe' ),
        'section' => 'aqualuxe_colors',
    ) ) );
    
    // Input background color
    $wp_customize->add_setting( 'aqualuxe_input_bg_color', array(
        'default' => '#ffffff',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'postMessage',
    ) );
    
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'aqualuxe_input_bg_color', array(
        'label' => __( 'Input Background Color', 'aqualuxe' ),
        'section' => 'aqualuxe_colors',
    ) ) );
    
    // Input text color
    $wp_customize->add_setting( 'aqualuxe_input_text_color', array(
        'default' => '#333333',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'postMessage',
    ) );
    
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'aqualuxe_input_text_color', array(
        'label' => __( 'Input Text Color', 'aqualuxe' ),
        'section' => 'aqualuxe_colors',
    ) ) );
    
    // Input border color
    $wp_customize->add_setting( 'aqualuxe_input_border_color', array(
        'default' => '#dddddd',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'postMessage',
    ) );
    
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'aqualuxe_input_border_color', array(
        'label' => __( 'Input Border Color', 'aqualuxe' ),
        'section' => 'aqualuxe_colors',
    ) ) );
    
    // Input focus border color
    $wp_customize->add_setting( 'aqualuxe_input_focus_border_color', array(
        'default' => '#0073aa',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'postMessage',
    ) );
    
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'aqualuxe_input_focus_border_color', array(
        'label' => __( 'Input Focus Border Color', 'aqualuxe' ),
        'section' => 'aqualuxe_colors',
    ) ) );
    
    // Button background color
    $wp_customize->add_setting( 'aqualuxe_button_bg_color', array(
        'default' => '#0073aa',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'postMessage',
    ) );
    
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'aqualuxe_button_bg_color', array(
        'label' => __( 'Button Background Color', 'aqualuxe' ),
        'section' => 'aqualuxe_colors',
    ) ) );
    
    // Button text color
    $wp_customize->add_setting( 'aqualuxe_button_text_color', array(
        'default' => '#ffffff',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'postMessage',
    ) );
    
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'aqualuxe_button_text_color', array(
        'label' => __( 'Button Text Color', 'aqualuxe' ),
        'section' => 'aqualuxe_colors',
    ) ) );
    
    // Button hover background color
    $wp_customize->add_setting( 'aqualuxe_button_hover_bg_color', array(
        'default' => '#00a0d2',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'postMessage',
    ) );
    
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'aqualuxe_button_hover_bg_color', array(
        'label' => __( 'Button Hover Background Color', 'aqualuxe' ),
        'section' => 'aqualuxe_colors',
    ) ) );
    
    // Button hover text color
    $wp_customize->add_setting( 'aqualuxe_button_hover_text_color', array(
        'default' => '#ffffff',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'postMessage',
    ) );
    
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'aqualuxe_button_hover_text_color', array(
        'label' => __( 'Button Hover Text Color', 'aqualuxe' ),
        'section' => 'aqualuxe_colors',
    ) ) );
    
    // Menu background color
    $wp_customize->add_setting( 'aqualuxe_menu_bg_color', array(
        'default' => '#ffffff',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'postMessage',
    ) );
    
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'aqualuxe_menu_bg_color', array(
        'label' => __( 'Menu Background Color', 'aqualuxe' ),
        'section' => 'aqualuxe_colors',
    ) ) );
    
    // Menu text color
    $wp_customize->add_setting( 'aqualuxe_menu_text_color', array(
        'default' => '#333333',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'postMessage',
    ) );
    
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'aqualuxe_menu_text_color', array(
        'label' => __( 'Menu Text Color', 'aqualuxe' ),
        'section' => 'aqualuxe_colors',
    ) ) );
    
    // Menu hover background color
    $wp_customize->add_setting( 'aqualuxe_menu_hover_bg_color', array(
        'default' => '#f5f5f5',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'postMessage',
    ) );
    
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'aqualuxe_menu_hover_bg_color', array(
        'label' => __( 'Menu Hover Background Color', 'aqualuxe' ),
        'section' => 'aqualuxe_colors',
    ) ) );
    
    // Menu hover text color
    $wp_customize->add_setting( 'aqualuxe_menu_hover_text_color', array(
        'default' => '#0073aa',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'postMessage',
    ) );
    
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'aqualuxe_menu_hover_text_color', array(
        'label' => __( 'Menu Hover Text Color', 'aqualuxe' ),
        'section' => 'aqualuxe_colors',
    ) ) );
    
    // Menu active background color
    $wp_customize->add_setting( 'aqualuxe_menu_active_bg_color', array(
        'default' => '#0073aa',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'postMessage',
    ) );
    
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'aqualuxe_menu_active_bg_color', array(
        'label' => __( 'Menu Active Background Color', 'aqualuxe' ),
        'section' => 'aqualuxe_colors',
    ) ) );
    
    // Menu active text color
    $wp_customize->add_setting( 'aqualuxe_menu_active_text_color', array(
        'default' => '#ffffff',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'postMessage',
    ) );
    
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'aqualuxe_menu_active_text_color', array(
        'label' => __( 'Menu Active Text Color', 'aqualuxe' ),
        'section' => 'aqualuxe_colors',
    ) ) );
    
    // Submenu background color
    $wp_customize->add_setting( 'aqualuxe_submenu_bg_color', array(
        'default' => '#ffffff',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'postMessage',
    ) );
    
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'aqualuxe_submenu_bg_color', array(
        'label' => __( 'Submenu Background Color', 'aqualuxe' ),
        'section' => 'aqualuxe_colors',
    ) ) );
    
    // Submenu text color
    $wp_customize->add_setting( 'aqualuxe_submenu_text_color', array(
        'default' => '#333333',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'postMessage',
    ) );
    
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'aqualuxe_submenu_text_color', array(
        'label' => __( 'Submenu Text Color', 'aqualuxe' ),
        'section' => 'aqualuxe_colors',
    ) ) );
    
    // Submenu hover background color
    $wp_customize->add_setting( 'aqualuxe_submenu_hover_bg_color', array(
        'default' => '#f5f5f5',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'postMessage',
    ) );
    
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'aqualuxe_submenu_hover_bg_color', array(
        'label' => __( 'Submenu Hover Background Color', 'aqualuxe' ),
        'section' => 'aqualuxe_colors',
    ) ) );
    
    // Submenu hover text color
    $wp_customize->add_setting( 'aqualuxe_submenu_hover_text_color', array(
        'default' => '#0073aa',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'postMessage',
    ) );
    
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'aqualuxe_submenu_hover_text_color', array(
        'label' => __( 'Submenu Hover Text Color', 'aqualuxe' ),
        'section' => 'aqualuxe_colors',
    ) ) );
    
    // Widget background color
    $wp_customize->add_setting( 'aqualuxe_widget_bg_color', array(
        'default' => '#ffffff',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'postMessage',
    ) );
    
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'aqualuxe_widget_bg_color', array(
        'label' => __( 'Widget Background Color', 'aqualuxe' ),
        'section' => 'aqualuxe_colors',
    ) ) );
    
    // Widget title color
    $wp_customize->add_setting( 'aqualuxe_widget_title_color', array(
        'default' => '#333333',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'postMessage',
    ) );
    
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'aqualuxe_widget_title_color', array(
        'label' => __( 'Widget Title Color', 'aqualuxe' ),
        'section' => 'aqualuxe_colors',
    ) ) );
    
    // Widget text color
    $wp_customize->add_setting( 'aqualuxe_widget_text_color', array(
        'default' => '#333333',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'postMessage',
    ) );
    
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'aqualuxe_widget_text_color', array(
        'label' => __( 'Widget Text Color', 'aqualuxe' ),
        'section' => 'aqualuxe_colors',
    ) ) );
    
    // Widget link color
    $wp_customize->add_setting( 'aqualuxe_widget_link_color', array(
        'default' => '#0073aa',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'postMessage',
    ) );
    
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'aqualuxe_widget_link_color', array(
        'label' => __( 'Widget Link Color', 'aqualuxe' ),
        'section' => 'aqualuxe_colors',
    ) ) );
    
    // Widget link hover color
    $wp_customize->add_setting( 'aqualuxe_widget_link_hover_color', array(
        'default' => '#00a0d2',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'postMessage',
    ) );
    
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'aqualuxe_widget_link_hover_color', array(
        'label' => __( 'Widget Link Hover Color', 'aqualuxe' ),
        'section' => 'aqualuxe_colors',
    ) ) );
}