<?php

/**
 * Customizer settings
 */
function aqualuxe_customize_register($wp_customize)
{
    $wp_customize->add_setting('aqualuxe_primary_color', [
        'default' => '#0077b6',
        'sanitize_callback' => 'sanitize_hex_color'
    ]);

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_primary_color', [
        'label' => __('Primary Color', 'aqualuxe'),
        'section' => 'colors',
        'settings' => 'aqualuxe_primary_color'
    ]));
}
add_action('customize_register', 'aqualuxe_customize_register');
