<?php
/** Theme Customizer */

add_action('customize_register', function ($wp_customize) {
    // Colors
    $wp_customize->add_section('aqualuxe_colors', [
        'title' => __('AquaLuxe Colors', 'aqualuxe')
    ]);

    $wp_customize->add_setting('aqualuxe_primary_color', [
        'default' => '#0ea5e9',
        'sanitize_callback' => 'sanitize_hex_color'
    ]);
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_primary_color', [
        'label' => __('Primary Color', 'aqualuxe'),
        'section' => 'aqualuxe_colors'
    ]));

    // Typography
    $wp_customize->add_section('aqualuxe_typography', [
        'title' => __('Typography', 'aqualuxe')
    ]);
    $wp_customize->add_setting('aqualuxe_font_base', [
        'default' => 'Inter, ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial, sans-serif',
        'sanitize_callback' => 'sanitize_text_field'
    ]);
    $wp_customize->add_control('aqualuxe_font_base', [
        'label' => __('Base Font Stack', 'aqualuxe'),
        'section' => 'aqualuxe_typography',
        'type' => 'text'
    ]);

    // Layout
    $wp_customize->add_section('aqualuxe_layout', [
        'title' => __('Layout', 'aqualuxe')
    ]);
    $wp_customize->add_setting('aqualuxe_container_width', [
        'default' => '1280px',
        'sanitize_callback' => 'sanitize_text_field'
    ]);
    $wp_customize->add_control('aqualuxe_container_width', [
        'label' => __('Container Max Width', 'aqualuxe'),
        'section' => 'aqualuxe_layout',
        'type' => 'text'
    ]);
});
