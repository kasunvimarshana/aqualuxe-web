<?php
/**
 * AquaLuxe Customizer Typography Section
 *
 * @package AquaLuxe
 * @subpackage Modules/Customizer
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Register typography section
 *
 * @param WP_Customize_Manager $wp_customize Customizer manager
 * @return void
 */
function aqualuxe_customizer_register_typography_section( $wp_customize ) {
    // Add section
    $wp_customize->add_section( 'aqualuxe_typography', array(
        'title' => __( 'Typography Settings', 'aqualuxe' ),
        'description' => __( 'Customize the typography.', 'aqualuxe' ),
        'panel' => 'aqualuxe_theme_options',
        'priority' => 40,
    ) );
    
    // Google Fonts
    $wp_customize->add_setting( 'aqualuxe_google_fonts', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_google_fonts', array(
        'label' => __( 'Load Google Fonts', 'aqualuxe' ),
        'section' => 'aqualuxe_typography',
        'type' => 'checkbox',
    ) );
    
    // Body font family
    $wp_customize->add_setting( 'aqualuxe_body_font_family', array(
        'default' => 'inherit',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_body_font_family', array(
        'label' => __( 'Body Font Family', 'aqualuxe' ),
        'section' => 'aqualuxe_typography',
        'type' => 'select',
        'choices' => aqualuxe_get_google_fonts(),
    ) );
    
    // Body font size
    $wp_customize->add_setting( 'aqualuxe_body_font_size', array(
        'default' => '16',
        'sanitize_callback' => 'aqualuxe_sanitize_number',
        'transport' => 'postMessage',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_body_font_size', array(
        'label' => __( 'Body Font Size (px)', 'aqualuxe' ),
        'section' => 'aqualuxe_typography',
        'type' => 'number',
        'input_attrs' => array(
            'min' => 12,
            'max' => 24,
            'step' => 1,
        ),
    ) );
    
    // Body font weight
    $wp_customize->add_setting( 'aqualuxe_body_font_weight', array(
        'default' => '400',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
        'transport' => 'postMessage',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_body_font_weight', array(
        'label' => __( 'Body Font Weight', 'aqualuxe' ),
        'section' => 'aqualuxe_typography',
        'type' => 'select',
        'choices' => aqualuxe_get_font_weights(),
    ) );
    
    // Body line height
    $wp_customize->add_setting( 'aqualuxe_body_line_height', array(
        'default' => '1.6',
        'sanitize_callback' => 'aqualuxe_sanitize_text',
        'transport' => 'postMessage',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_body_line_height', array(
        'label' => __( 'Body Line Height', 'aqualuxe' ),
        'section' => 'aqualuxe_typography',
        'type' => 'number',
        'input_attrs' => array(
            'min' => 1,
            'max' => 2,
            'step' => 0.1,
        ),
    ) );
    
    // Body text transform
    $wp_customize->add_setting( 'aqualuxe_body_text_transform', array(
        'default' => 'none',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
        'transport' => 'postMessage',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_body_text_transform', array(
        'label' => __( 'Body Text Transform', 'aqualuxe' ),
        'section' => 'aqualuxe_typography',
        'type' => 'select',
        'choices' => aqualuxe_get_text_transforms(),
    ) );
    
    // Headings font family
    $wp_customize->add_setting( 'aqualuxe_headings_font_family', array(
        'default' => 'inherit',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_headings_font_family', array(
        'label' => __( 'Headings Font Family', 'aqualuxe' ),
        'section' => 'aqualuxe_typography',
        'type' => 'select',
        'choices' => aqualuxe_get_google_fonts(),
    ) );
    
    // Headings font weight
    $wp_customize->add_setting( 'aqualuxe_headings_font_weight', array(
        'default' => '700',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
        'transport' => 'postMessage',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_headings_font_weight', array(
        'label' => __( 'Headings Font Weight', 'aqualuxe' ),
        'section' => 'aqualuxe_typography',
        'type' => 'select',
        'choices' => aqualuxe_get_font_weights(),
    ) );
    
    // Headings line height
    $wp_customize->add_setting( 'aqualuxe_headings_line_height', array(
        'default' => '1.2',
        'sanitize_callback' => 'aqualuxe_sanitize_text',
        'transport' => 'postMessage',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_headings_line_height', array(
        'label' => __( 'Headings Line Height', 'aqualuxe' ),
        'section' => 'aqualuxe_typography',
        'type' => 'number',
        'input_attrs' => array(
            'min' => 1,
            'max' => 2,
            'step' => 0.1,
        ),
    ) );
    
    // Headings text transform
    $wp_customize->add_setting( 'aqualuxe_headings_text_transform', array(
        'default' => 'none',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
        'transport' => 'postMessage',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_headings_text_transform', array(
        'label' => __( 'Headings Text Transform', 'aqualuxe' ),
        'section' => 'aqualuxe_typography',
        'type' => 'select',
        'choices' => aqualuxe_get_text_transforms(),
    ) );
    
    // H1 font size
    $wp_customize->add_setting( 'aqualuxe_h1_font_size', array(
        'default' => '36',
        'sanitize_callback' => 'aqualuxe_sanitize_number',
        'transport' => 'postMessage',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_h1_font_size', array(
        'label' => __( 'H1 Font Size (px)', 'aqualuxe' ),
        'section' => 'aqualuxe_typography',
        'type' => 'number',
        'input_attrs' => array(
            'min' => 20,
            'max' => 60,
            'step' => 1,
        ),
    ) );
    
    // H2 font size
    $wp_customize->add_setting( 'aqualuxe_h2_font_size', array(
        'default' => '30',
        'sanitize_callback' => 'aqualuxe_sanitize_number',
        'transport' => 'postMessage',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_h2_font_size', array(
        'label' => __( 'H2 Font Size (px)', 'aqualuxe' ),
        'section' => 'aqualuxe_typography',
        'type' => 'number',
        'input_attrs' => array(
            'min' => 18,
            'max' => 50,
            'step' => 1,
        ),
    ) );
    
    // H3 font size
    $wp_customize->add_setting( 'aqualuxe_h3_font_size', array(
        'default' => '24',
        'sanitize_callback' => 'aqualuxe_sanitize_number',
        'transport' => 'postMessage',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_h3_font_size', array(
        'label' => __( 'H3 Font Size (px)', 'aqualuxe' ),
        'section' => 'aqualuxe_typography',
        'type' => 'number',
        'input_attrs' => array(
            'min' => 16,
            'max' => 40,
            'step' => 1,
        ),
    ) );
    
    // H4 font size
    $wp_customize->add_setting( 'aqualuxe_h4_font_size', array(
        'default' => '20',
        'sanitize_callback' => 'aqualuxe_sanitize_number',
        'transport' => 'postMessage',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_h4_font_size', array(
        'label' => __( 'H4 Font Size (px)', 'aqualuxe' ),
        'section' => 'aqualuxe_typography',
        'type' => 'number',
        'input_attrs' => array(
            'min' => 14,
            'max' => 30,
            'step' => 1,
        ),
    ) );
    
    // H5 font size
    $wp_customize->add_setting( 'aqualuxe_h5_font_size', array(
        'default' => '18',
        'sanitize_callback' => 'aqualuxe_sanitize_number',
        'transport' => 'postMessage',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_h5_font_size', array(
        'label' => __( 'H5 Font Size (px)', 'aqualuxe' ),
        'section' => 'aqualuxe_typography',
        'type' => 'number',
        'input_attrs' => array(
            'min' => 12,
            'max' => 24,
            'step' => 1,
        ),
    ) );
    
    // H6 font size
    $wp_customize->add_setting( 'aqualuxe_h6_font_size', array(
        'default' => '16',
        'sanitize_callback' => 'aqualuxe_sanitize_number',
        'transport' => 'postMessage',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_h6_font_size', array(
        'label' => __( 'H6 Font Size (px)', 'aqualuxe' ),
        'section' => 'aqualuxe_typography',
        'type' => 'number',
        'input_attrs' => array(
            'min' => 10,
            'max' => 20,
            'step' => 1,
        ),
    ) );
    
    // Menu font family
    $wp_customize->add_setting( 'aqualuxe_menu_font_family', array(
        'default' => 'inherit',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_menu_font_family', array(
        'label' => __( 'Menu Font Family', 'aqualuxe' ),
        'section' => 'aqualuxe_typography',
        'type' => 'select',
        'choices' => aqualuxe_get_google_fonts(),
    ) );
    
    // Menu font size
    $wp_customize->add_setting( 'aqualuxe_menu_font_size', array(
        'default' => '16',
        'sanitize_callback' => 'aqualuxe_sanitize_number',
        'transport' => 'postMessage',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_menu_font_size', array(
        'label' => __( 'Menu Font Size (px)', 'aqualuxe' ),
        'section' => 'aqualuxe_typography',
        'type' => 'number',
        'input_attrs' => array(
            'min' => 12,
            'max' => 24,
            'step' => 1,
        ),
    ) );
    
    // Menu font weight
    $wp_customize->add_setting( 'aqualuxe_menu_font_weight', array(
        'default' => '400',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
        'transport' => 'postMessage',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_menu_font_weight', array(
        'label' => __( 'Menu Font Weight', 'aqualuxe' ),
        'section' => 'aqualuxe_typography',
        'type' => 'select',
        'choices' => aqualuxe_get_font_weights(),
    ) );
    
    // Menu text transform
    $wp_customize->add_setting( 'aqualuxe_menu_text_transform', array(
        'default' => 'none',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
        'transport' => 'postMessage',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_menu_text_transform', array(
        'label' => __( 'Menu Text Transform', 'aqualuxe' ),
        'section' => 'aqualuxe_typography',
        'type' => 'select',
        'choices' => aqualuxe_get_text_transforms(),
    ) );
    
    // Button font family
    $wp_customize->add_setting( 'aqualuxe_button_font_family', array(
        'default' => 'inherit',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_button_font_family', array(
        'label' => __( 'Button Font Family', 'aqualuxe' ),
        'section' => 'aqualuxe_typography',
        'type' => 'select',
        'choices' => aqualuxe_get_google_fonts(),
    ) );
    
    // Button font size
    $wp_customize->add_setting( 'aqualuxe_button_font_size', array(
        'default' => '16',
        'sanitize_callback' => 'aqualuxe_sanitize_number',
        'transport' => 'postMessage',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_button_font_size', array(
        'label' => __( 'Button Font Size (px)', 'aqualuxe' ),
        'section' => 'aqualuxe_typography',
        'type' => 'number',
        'input_attrs' => array(
            'min' => 12,
            'max' => 24,
            'step' => 1,
        ),
    ) );
    
    // Button font weight
    $wp_customize->add_setting( 'aqualuxe_button_font_weight', array(
        'default' => '400',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
        'transport' => 'postMessage',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_button_font_weight', array(
        'label' => __( 'Button Font Weight', 'aqualuxe' ),
        'section' => 'aqualuxe_typography',
        'type' => 'select',
        'choices' => aqualuxe_get_font_weights(),
    ) );
    
    // Button text transform
    $wp_customize->add_setting( 'aqualuxe_button_text_transform', array(
        'default' => 'none',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
        'transport' => 'postMessage',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_button_text_transform', array(
        'label' => __( 'Button Text Transform', 'aqualuxe' ),
        'section' => 'aqualuxe_typography',
        'type' => 'select',
        'choices' => aqualuxe_get_text_transforms(),
    ) );
    
    // Font subsets
    $wp_customize->add_setting( 'aqualuxe_font_subsets', array(
        'default' => array( 'latin', 'latin-ext' ),
        'sanitize_callback' => 'aqualuxe_sanitize_sortable',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_font_subsets', array(
        'label' => __( 'Font Subsets', 'aqualuxe' ),
        'section' => 'aqualuxe_typography',
        'type' => 'select',
        'choices' => array(
            'latin' => __( 'Latin', 'aqualuxe' ),
            'latin-ext' => __( 'Latin Extended', 'aqualuxe' ),
            'cyrillic' => __( 'Cyrillic', 'aqualuxe' ),
            'cyrillic-ext' => __( 'Cyrillic Extended', 'aqualuxe' ),
            'greek' => __( 'Greek', 'aqualuxe' ),
            'greek-ext' => __( 'Greek Extended', 'aqualuxe' ),
            'vietnamese' => __( 'Vietnamese', 'aqualuxe' ),
        ),
        'multiple' => true,
    ) );
}