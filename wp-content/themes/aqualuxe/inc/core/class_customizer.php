<?php
namespace AquaLuxe\Core;

if (!defined('ABSPATH')) { exit; }

class Customizer {
    public static function init(): void {
        \add_action('customize_register', [__CLASS__, 'register']);
    }

    public static function register(\WP_Customize_Manager $wp_customize): void {
        $wp_customize->add_section('aqualuxe_branding', [
            'title' => __('AquaLuxe Branding', AQUALUXE_TEXT_DOMAIN),
            'priority' => 30,
        ]);

        // Colors
        $wp_customize->add_setting('aqualuxe_primary_color', [
            'default' => '#00a3b4',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'postMessage',
        ]);
        $wp_customize->add_control(new \WP_Customize_Color_Control($wp_customize, 'aqualuxe_primary_color', [
            'label' => __('Primary Color', AQUALUXE_TEXT_DOMAIN),
            'section' => 'colors',
        ]));

        $wp_customize->add_setting('aqualuxe_accent_color', [
            'default' => '#0ea5e9',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'postMessage',
        ]);
        $wp_customize->add_control(new \WP_Customize_Color_Control($wp_customize, 'aqualuxe_accent_color', [
            'label' => __('Accent Color', AQUALUXE_TEXT_DOMAIN),
            'section' => 'colors',
        ]));

        // Layout width
        $wp_customize->add_setting('aqualuxe_container_max', [
            'default' => 1200,
            'sanitize_callback' => 'absint',
            'transport' => 'postMessage',
        ]);
        $wp_customize->add_control('aqualuxe_container_max', [
            'label' => __('Container Max Width (px)', AQUALUXE_TEXT_DOMAIN),
            'type' => 'number',
            'section' => 'aqualuxe_branding',
            'input_attrs' => ['min' => 960, 'max' => 1600, 'step' => 20],
        ]);

        // Typography: system vs serif
        $wp_customize->add_setting('aqualuxe_font_family', [
            'default' => 'system',
            'sanitize_callback' => function ($v) { return in_array($v, ['system','serif'], true) ? $v : 'system'; },
        ]);
        $wp_customize->add_control('aqualuxe_font_family', [
            'label' => __('Font Family', AQUALUXE_TEXT_DOMAIN),
            'type' => 'select',
            'choices' => [
                'system' => __('System UI', AQUALUXE_TEXT_DOMAIN),
                'serif' => __('Elegant Serif', AQUALUXE_TEXT_DOMAIN),
            ],
            'section' => 'aqualuxe_branding',
        ]);

        \add_action('wp_head', [__CLASS__, 'output_custom_properties']);
    }

    public static function output_custom_properties(): void {
        $primary = get_theme_mod('aqualuxe_primary_color', '#00a3b4');
        $accent = get_theme_mod('aqualuxe_accent_color', '#0ea5e9');
        $container = (int) get_theme_mod('aqualuxe_container_max', 1200);
        $font = get_theme_mod('aqualuxe_font_family', 'system');
        $font_stack = $font === 'serif' ? "'Georgia', 'Times New Roman', serif" : "-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, 'Noto Sans', 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol', sans-serif";
        echo '<style id="aqualuxe-custom-properties">:root{--al-primary:' . esc_attr($primary) . ';--al-accent:' . esc_attr($accent) . ';--al-container:' . esc_attr($container) . 'px;--al-font:' . esc_attr($font_stack) . ';}</style>';
    }
}
