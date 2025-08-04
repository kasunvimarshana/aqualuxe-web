<?php
/**
 * Customizer colors
 */
add_action( 'customize_register', 'aqualuxe_customize' );

function aqualuxe_customize( $wp_customize ) {
    $wp_customize->add_setting( 'aqua_primary', [
        'default'   => '#0077b6',
        'sanitize_callback' => 'sanitize_hex_color',
    ] );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'aqua_primary', [
        'label'   => __( 'Primary Color', 'aqualuxe' ),
        'section' => 'colors',
    ] ) );
}