<?php
/**
 * Header Variations
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Get header layout.
 *
 * @return string
 */
function aqualuxe_get_header_layout() {
    $layout = get_theme_mod( 'aqualuxe_header_layout', 'default' );
    return apply_filters( 'aqualuxe_header_layout', $layout );
}

/**
 * Get header class.
 *
 * @return string
 */
function aqualuxe_get_header_class() {
    $layout = aqualuxe_get_header_layout();
    $classes = array(
        'site-header',
        'header-layout-' . $layout,
    );

    // Add sticky class if enabled.
    if ( get_theme_mod( 'aqualuxe_sticky_header', true ) ) {
        $classes[] = 'sticky-header';
    }

    // Add transparent class if enabled.
    if ( get_theme_mod( 'aqualuxe_transparent_header', false ) ) {
        $classes[] = 'transparent-header';
    }

    return implode( ' ', apply_filters( 'aqualuxe_header_class', $classes ) );
}

/**
 * Load header template based on layout.
 *
 * @return void
 */
function aqualuxe_get_header_template() {
    $layout = aqualuxe_get_header_layout();
    $template = 'templates/header/header-' . $layout . '.php';
    $default_template = 'templates/header/header-default.php';

    if ( file_exists( get_template_directory() . '/' . $template ) ) {
        get_template_part( 'templates/header/header', $layout );
    } else {
        get_template_part( 'templates/header/header', 'default' );
    }
}

/**
 * Add header layout options to customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 * @return void
 */
function aqualuxe_header_layout_customizer( $wp_customize ) {
    // Header Layout
    $wp_customize->add_setting(
        'aqualuxe_header_layout',
        array(
            'default'           => 'default',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_header_layout',
        array(
            'label'    => __( 'Header Layout', 'aqualuxe' ),
            'section'  => 'aqualuxe_header_options',
            'type'     => 'select',
            'choices'  => array(
                'default'  => __( 'Default', 'aqualuxe' ),
                'centered' => __( 'Centered', 'aqualuxe' ),
                'split'    => __( 'Split', 'aqualuxe' ),
            ),
            'priority' => 10,
        )
    );

    // Sticky Header
    $wp_customize->add_setting(
        'aqualuxe_sticky_header',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_sticky_header',
        array(
            'label'    => __( 'Sticky Header', 'aqualuxe' ),
            'section'  => 'aqualuxe_header_options',
            'type'     => 'checkbox',
            'priority' => 20,
        )
    );

    // Transparent Header
    $wp_customize->add_setting(
        'aqualuxe_transparent_header',
        array(
            'default'           => false,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_transparent_header',
        array(
            'label'    => __( 'Transparent Header on Homepage', 'aqualuxe' ),
            'section'  => 'aqualuxe_header_options',
            'type'     => 'checkbox',
            'priority' => 30,
        )
    );
}
add_action( 'customize_register', 'aqualuxe_header_layout_customizer' );