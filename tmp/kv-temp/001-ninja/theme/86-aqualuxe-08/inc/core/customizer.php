<?php
namespace AquaLuxe\Core;

class Customizer
{
    public static function register($wp_customize): void
    {
        // Colors
        $wp_customize->add_section('aqualuxe_colors', [
            'title' => \__('AquaLuxe Colors', 'aqualuxe'),
            'priority' => 30,
        ]);
        $wp_customize->add_setting('aqualuxe_primary_color', [
            'default' => '#0ea5e9',
            'sanitize_callback' => 'sanitize_hex_color',
        ]);
        $wp_customize->add_control(new \WP_Customize_Color_Control($wp_customize, 'aqualuxe_primary_color', [
            'label' => \__('Primary Color', 'aqualuxe'),
            'section' => 'aqualuxe_colors',
        ]));

        // Typography (basic choice)
        $wp_customize->add_section('aqualuxe_typography', [
            'title' => \__('AquaLuxe Typography', 'aqualuxe'),
            'priority' => 31,
        ]);
        $wp_customize->add_setting('aqualuxe_font_family', [
            'default' => 'ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial, Noto Sans, "Apple Color Emoji", "Segoe UI Emoji"',
            'sanitize_callback' => [__CLASS__, 'sanitize_font_stack'],
        ]);
        $wp_customize->add_control('aqualuxe_font_family', [
            'label' => \__('Base Font Stack', 'aqualuxe'),
            'type' => 'text',
            'section' => 'aqualuxe_typography',
        ]);

        // Layout
        $wp_customize->add_section('aqualuxe_layout', [
            'title' => \__('AquaLuxe Layout', 'aqualuxe'),
            'priority' => 32,
        ]);
        $wp_customize->add_setting('aqualuxe_container_width', [
            'default' => 'container',
            'sanitize_callback' => [__CLASS__, 'sanitize_container'],
        ]);
        $wp_customize->add_control('aqualuxe_container_width', [
            'label' => \__('Container Width', 'aqualuxe'),
            'type' => 'select',
            'choices' => [
                'container' => \__('Default', 'aqualuxe'),
                'container-fluid' => \__('Full Width', 'aqualuxe'),
            ],
            'section' => 'aqualuxe_layout',
        ]);
    }

    public static function sanitize_font_stack($value): string
    {
    return \wp_strip_all_tags((string)$value);
    }

    public static function sanitize_container($value): string
    {
        $allowed = ['container', 'container-fluid'];
        return in_array($value, $allowed, true) ? $value : 'container';
    }
}
