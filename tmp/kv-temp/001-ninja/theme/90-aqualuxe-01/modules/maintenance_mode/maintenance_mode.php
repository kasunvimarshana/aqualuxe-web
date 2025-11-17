<?php
/**
 * Maintenance Mode Module
 *
 * @package AquaLuxe
 */

// Add settings to the customizer
function aqualuxe_maintenance_mode_customize_register( $wp_customize ) {
    $wp_customize->add_section( 'aqualuxe_maintenance_mode_section', array(
        'title'      => __( 'Maintenance Mode', 'aqualuxe' ),
        'priority'   => 160,
    ) );

    $wp_customize->add_setting( 'aqualuxe_maintenance_mode_enabled', array(
        'default'           => false,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ) );

    $wp_customize->add_control( 'aqualuxe_maintenance_mode_enabled', array(
        'type'      => 'checkbox',
        'label'     => __( 'Enable Maintenance Mode', 'aqualuxe' ),
        'section'   => 'aqualuxe_maintenance_mode_section',
    ) );

    $wp_customize->add_setting( 'aqualuxe_maintenance_mode_text', array(
        'default'           => __( 'Our website is currently undergoing scheduled maintenance. Please check back soon.', 'aqualuxe' ),
        'sanitize_callback' => 'sanitize_textarea_field',
    ) );

    $wp_customize->add_control( 'aqualuxe_maintenance_mode_text', array(
        'type'      => 'textarea',
        'label'     => __( 'Maintenance Mode Message', 'aqualuxe' ),
        'section'   => 'aqualuxe_maintenance_mode_section',
    ) );
}
add_action( 'customize_register', 'aqualuxe_maintenance_mode_customize_register' );

// Sanitize checkbox
function aqualuxe_sanitize_checkbox( $checked ) {
    return ( ( isset( $checked ) && true === $checked ) ? true : false );
}

// Display maintenance page
function aqualuxe_maintenance_mode_page() {
    if ( get_theme_mod( 'aqualuxe_maintenance_mode_enabled' ) && ! current_user_can( 'edit_themes' ) && ! is_user_logged_in() ) {
        wp_die(
            '<h1>' . get_theme_mod( 'aqualuxe_maintenance_mode_text' ) . '</h1>',
            'Maintenance Mode',
            array( 'response' => 503 )
        );
    }
}
add_action( 'template_redirect', 'aqualuxe_maintenance_mode_page' );
