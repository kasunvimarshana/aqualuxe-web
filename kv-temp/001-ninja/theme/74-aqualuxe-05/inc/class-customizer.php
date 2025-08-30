<?php
namespace AquaLuxe;

class Customizer {
    public static function register($wp_customize): void {
        $wp_customize->add_section('aqualuxe_theme', [
            'title' => \__('AquaLuxe Theme', 'aqualuxe'),
            'priority' => 30,
        ]);

        $wp_customize->add_setting('aqualuxe_color_primary', [
            'default' => '#14a5d1',
            'sanitize_callback' => 'sanitize_hex_color',
        ]);
        $wp_customize->add_control(new \WP_Customize_Color_Control($wp_customize, 'aqualuxe_color_primary', [
            'label' => \__('Primary Color', 'aqualuxe'),
            'section' => 'aqualuxe_theme',
        ]));

        $wp_customize->add_setting('aqualuxe_typography_body', [
            'default' => 'system-ui',
            'sanitize_callback' => 'sanitize_text_field',
        ]);
        $wp_customize->add_control('aqualuxe_typography_body', [
            'label' => \__('Body Font', 'aqualuxe'),
            'section' => 'aqualuxe_theme',
            'type' => 'text',
        ]);

        $wp_customize->add_setting('aqualuxe_layout_container', [
            'default' => 'container',
            'sanitize_callback' => 'sanitize_text_field',
        ]);
        $wp_customize->add_control('aqualuxe_layout_container', [
            'label' => \__('Container Class', 'aqualuxe'),
            'section' => 'aqualuxe_theme',
            'type' => 'text',
        ]);

        $wp_customize->add_setting('aqualuxe_map_embed', [
            'default' => '',
            'sanitize_callback' => 'esc_url_raw',
        ]);
        $wp_customize->add_control('aqualuxe_map_embed', [
            'label' => \__('Map Embed URL (optional)', 'aqualuxe'),
            'section' => 'aqualuxe_theme',
            'type' => 'url',
            'description' => \__('Paste a Google Maps Embed URL to show a map on the contact page shortcode.', 'aqualuxe')
        ]);
    }
}

\add_action('customize_register', [Customizer::class, 'register']);
