<?php

declare(strict_types=1);

namespace AquaLuxe\Core;

class Customizer
{
    public static function register($wp_customize): void
    {
        // Colors.
        $wp_customize->add_section('aqualuxe_colors', [
            'title'    => __('AquaLuxe Colors', 'aqualuxe'),
            'priority' => 30,
        ]);

        $wp_customize->add_setting('aqualuxe_primary_color', [
            'default'           => '#0ea5e9',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage',
        ]);
        if (\class_exists('WP_Customize_Color_Control')) {
            $klass = 'WP_Customize_Color_Control';
            $wp_customize->add_control(new $klass($wp_customize, 'aqualuxe_primary_color', [
                'label'   => __('Primary Color', 'aqualuxe'),
                'section' => 'aqualuxe_colors',
            ]));
        }

        // Typography.
        $wp_customize->add_section('aqualuxe_typography', [
            'title'    => __('AquaLuxe Typography', 'aqualuxe'),
            'priority' => 31,
        ]);
        $wp_customize->add_setting('aqualuxe_body_font', [
            'default'           => 'system-ui, -apple-system, Segoe UI, Roboto, Ubuntu, Cantarell, Noto Sans, Helvetica Neue, Arial, "Apple Color Emoji", "Segoe UI Emoji"',
            'sanitize_callback' => 'sanitize_text_field',
        ]);
        $wp_customize->add_control('aqualuxe_body_font', [
            'label'   => __('Body Font Stack', 'aqualuxe'),
            'section' => 'aqualuxe_typography',
            'type'    => 'text',
        ]);
    }
}
