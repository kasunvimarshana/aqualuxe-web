<?php
declare(strict_types=1);

namespace Aqualuxe\Inc;

use WP_Customize_Manager;

add_action('customize_register', static function (WP_Customize_Manager $wp_customize): void {
    // Panel
    $wp_customize->add_panel('aqualuxe_panel', [
        'title' => __('AquaLuxe Theme Options', 'aqualuxe'),
        'priority' => 10,
    ]);

    // Colors Section
    $wp_customize->add_section('aqualuxe_colors', [
        'title' => __('Colors', 'aqualuxe'),
        'panel' => 'aqualuxe_panel',
    ]);
    $wp_customize->add_setting('aqualuxe_primary_color', [
        'default' => '#0ea5e9',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);
    $wp_customize->add_control(new \WP_Customize_Color_Control($wp_customize, 'aqualuxe_primary_color', [
        'label' => __('Primary Color', 'aqualuxe'),
        'section' => 'aqualuxe_colors',
    ]));

    // Typography
    $wp_customize->add_section('aqualuxe_typography', [
        'title' => __('Typography', 'aqualuxe'),
        'panel' => 'aqualuxe_panel',
    ]);
    $wp_customize->add_setting('aqualuxe_base_font', [
        'default' => 'ui-sans-serif',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('aqualuxe_base_font', [
        'label' => __('Base Font Stack', 'aqualuxe'),
        'type' => 'text',
        'section' => 'aqualuxe_typography',
    ]);

    // Layout
    $wp_customize->add_section('aqualuxe_layout', [
        'title' => __('Layout', 'aqualuxe'),
        'panel' => 'aqualuxe_panel',
    ]);
    $wp_customize->add_setting('aqualuxe_container_width', [
        'default' => '1280px',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('aqualuxe_container_width', [
        'label' => __('Container Max Width', 'aqualuxe'),
        'type' => 'text',
        'section' => 'aqualuxe_layout',
    ]);
});

// Output CSS from customizer values (simple example)
add_action('wp_head', static function (): void {
    $primary = get_theme_mod('aqualuxe_primary_color', '#0ea5e9');
    $font = get_theme_mod('aqualuxe_base_font', 'ui-sans-serif');
    $container = get_theme_mod('aqualuxe_container_width', '1280px');
    echo '<style id="aqualuxe-customizer">:root{--aqlx-primary:' . esc_attr($primary) . ';--aqlx-font:' . esc_attr($font) . ';--aqlx-container:' . esc_attr($container) . ';}</style>';
});
