<?php
namespace AquaLuxe\Core;

if (!defined('ABSPATH')) exit;

class Customizer {
    public static function boot(): void {
        add_action('customize_register', [__CLASS__, 'register']);
    }

    public static function register(\WP_Customize_Manager $wp_customize): void {
        // Colors.
        $wp_customize->add_section('aqualuxe_colors', [
            'title' => __('AquaLuxe Colors', 'aqualuxe'),
            'priority' => 30,
        ]);
        $wp_customize->add_setting('aqualuxe_color_primary', [
            'default' => '#0ea5e9',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'postMessage',
        ]);
        $wp_customize->add_control(new \WP_Customize_Color_Control($wp_customize, 'aqualuxe_color_primary', [
            'label' => __('Primary Color', 'aqualuxe'),
            'section' => 'aqualuxe_colors',
        ]));

        $wp_customize->add_setting('aqualuxe_dark_mode_default', [
            'default' => false,
            'sanitize_callback' => [__CLASS__, 'sanitize_checkbox'],
        ]);
        $wp_customize->add_control('aqualuxe_dark_mode_default', [
            'type' => 'checkbox',
            'label' => __('Enable dark mode by default', 'aqualuxe'),
            'section' => 'aqualuxe_colors',
        ]);

        // Typography.
        $wp_customize->add_section('aqualuxe_typography', [
            'title' => __('AquaLuxe Typography', 'aqualuxe'),
            'priority' => 31,
        ]);
        $wp_customize->add_setting('aqualuxe_body_font', [
            'default' => 'ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ]);
        $wp_customize->add_control('aqualuxe_body_font', [
            'label' => __('Body Font Stack', 'aqualuxe'),
            'type' => 'text',
            'section' => 'aqualuxe_typography',
        ]);
    }

    public static function sanitize_checkbox($checked) {
        return (isset($checked) && true == $checked) ? true : false;
    }
}
