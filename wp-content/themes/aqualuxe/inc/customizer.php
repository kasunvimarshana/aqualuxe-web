<?php
/**
 * Theme Customizer functionality
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Customizer setup
 */
function aqualuxe_customize_register( $wp_customize ) {
    
    // Add theme colors section
    $wp_customize->add_section( 'aqualuxe_colors', array(
        'title'      => esc_html__( 'Theme Colors', 'aqualuxe' ),
        'priority'   => 30,
    ) );

    // Primary color setting
    $wp_customize->add_setting( 'aqualuxe_primary_color', array(
        'default'           => '#0ea5e9',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );

    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'aqualuxe_primary_color', array(
        'label'    => esc_html__( 'Primary Color', 'aqualuxe' ),
        'section'  => 'aqualuxe_colors',
        'settings' => 'aqualuxe_primary_color',
    ) ) );

    // Secondary color setting
    $wp_customize->add_setting( 'aqualuxe_secondary_color', array(
        'default'           => '#64748b',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );

    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'aqualuxe_secondary_color', array(
        'label'    => esc_html__( 'Secondary Color', 'aqualuxe' ),
        'section'  => 'aqualuxe_colors',
        'settings' => 'aqualuxe_secondary_color',
    ) ) );

    // Add layout section
    $wp_customize->add_section( 'aqualuxe_layout', array(
        'title'      => esc_html__( 'Layout Options', 'aqualuxe' ),
        'priority'   => 35,
    ) );

    // Container width setting
    $wp_customize->add_setting( 'aqualuxe_container_width', array(
        'default'           => '1200',
        'sanitize_callback' => 'absint',
    ) );

    $wp_customize->add_control( 'aqualuxe_container_width', array(
        'label'       => esc_html__( 'Container Width (px)', 'aqualuxe' ),
        'section'     => 'aqualuxe_layout',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 960,
            'max'  => 1920,
            'step' => 10,
        ),
    ) );

    // Sidebar position setting
    $wp_customize->add_setting( 'aqualuxe_sidebar_position', array(
        'default'           => 'right',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ) );

    $wp_customize->add_control( 'aqualuxe_sidebar_position', array(
        'label'   => esc_html__( 'Sidebar Position', 'aqualuxe' ),
        'section' => 'aqualuxe_layout',
        'type'    => 'select',
        'choices' => array(
            'left'  => esc_html__( 'Left', 'aqualuxe' ),
            'right' => esc_html__( 'Right', 'aqualuxe' ),
            'none'  => esc_html__( 'No Sidebar', 'aqualuxe' ),
        ),
    ) );

    // Add typography section
    $wp_customize->add_section( 'aqualuxe_typography', array(
        'title'      => esc_html__( 'Typography', 'aqualuxe' ),
        'priority'   => 40,
    ) );

    // Body font setting
    $wp_customize->add_setting( 'aqualuxe_body_font', array(
        'default'           => 'Inter',
        'sanitize_callback' => 'sanitize_text_field',
    ) );

    $wp_customize->add_control( 'aqualuxe_body_font', array(
        'label'   => esc_html__( 'Body Font', 'aqualuxe' ),
        'section' => 'aqualuxe_typography',
        'type'    => 'select',
        'choices' => aqualuxe_get_google_fonts(),
    ) );

    // Heading font setting
    $wp_customize->add_setting( 'aqualuxe_heading_font', array(
        'default'           => 'Playfair Display',
        'sanitize_callback' => 'sanitize_text_field',
    ) );

    $wp_customize->add_control( 'aqualuxe_heading_font', array(
        'label'   => esc_html__( 'Heading Font', 'aqualuxe' ),
        'section' => 'aqualuxe_typography',
        'type'    => 'select',
        'choices' => aqualuxe_get_google_fonts(),
    ) );

    // Add footer section
    $wp_customize->add_section( 'aqualuxe_footer', array(
        'title'      => esc_html__( 'Footer Options', 'aqualuxe' ),
        'priority'   => 45,
    ) );

    // Footer text setting
    $wp_customize->add_setting( 'aqualuxe_footer_text', array(
        'default'           => sprintf( esc_html__( '&copy; %s %s. All rights reserved.', 'aqualuxe' ), date( 'Y' ), get_bloginfo( 'name' ) ),
        'sanitize_callback' => 'wp_kses_post',
    ) );

    $wp_customize->add_control( 'aqualuxe_footer_text', array(
        'label'   => esc_html__( 'Footer Copyright Text', 'aqualuxe' ),
        'section' => 'aqualuxe_footer',
        'type'    => 'textarea',
    ) );

    // Show footer credit setting
    $wp_customize->add_setting( 'aqualuxe_show_footer_credit', array(
        'default'           => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ) );

    $wp_customize->add_control( 'aqualuxe_show_footer_credit', array(
        'label'   => esc_html__( 'Show Theme Credit in Footer', 'aqualuxe' ),
        'section' => 'aqualuxe_footer',
        'type'    => 'checkbox',
    ) );
}
add_action( 'customize_register', 'aqualuxe_customize_register' );

/**
 * Sanitize select options
 */
function aqualuxe_sanitize_select( $input, $setting ) {
    $choices = $setting->manager->get_control( $setting->id )->choices;
    return array_key_exists( $input, $choices ) ? $input : $setting->default;
}

/**
 * Sanitize checkbox
 */
function aqualuxe_sanitize_checkbox( $checked ) {
    return ( isset( $checked ) && true === $checked ) ? true : false;
}

/**
 * Get Google Fonts list
 */
function aqualuxe_get_google_fonts() {
    return array(
        'Inter'            => 'Inter',
        'Roboto'           => 'Roboto',
        'Open Sans'        => 'Open Sans',
        'Lato'             => 'Lato',
        'Montserrat'       => 'Montserrat',
        'Playfair Display' => 'Playfair Display',
        'Merriweather'     => 'Merriweather',
        'Lora'             => 'Lora',
        'Source Sans Pro'  => 'Source Sans Pro',
        'Nunito'           => 'Nunito',
    );
}

/**
 * Output custom CSS based on customizer settings
 */
function aqualuxe_customizer_css() {
    $primary_color   = get_theme_mod( 'aqualuxe_primary_color', '#0ea5e9' );
    $secondary_color = get_theme_mod( 'aqualuxe_secondary_color', '#64748b' );
    $container_width = get_theme_mod( 'aqualuxe_container_width', 1200 );
    $body_font       = get_theme_mod( 'aqualuxe_body_font', 'Inter' );
    $heading_font    = get_theme_mod( 'aqualuxe_heading_font', 'Playfair Display' );

    $css = "
        :root {
            --primary-color: {$primary_color};
            --secondary-color: {$secondary_color};
            --container-width: {$container_width}px;
        }
        
        body {
            font-family: '{$body_font}', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }
        
        h1, h2, h3, h4, h5, h6 {
            font-family: '{$heading_font}', Georgia, serif;
        }
        
        .container {
            max-width: {$container_width}px;
        }
    ";

    return $css;
}

/**
 * Add customizer CSS to head
 */
function aqualuxe_customizer_head() {
    echo '<style type="text/css">' . aqualuxe_customizer_css() . '</style>';
}
add_action( 'wp_head', 'aqualuxe_customizer_head' );