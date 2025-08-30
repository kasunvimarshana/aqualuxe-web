<?php

/**
 * AquaLuxe Theme Customizer
 *
 * @package AquaLuxe
 */

if (! defined('ABSPATH')) {
    exit;
}

class AquaLuxe_Customizer
{

    public function __construct()
    {
        add_action('customize_register', [$this, 'register_customizer_options']);
    }

    public function register_customizer_options($wp_customize)
    {
        // Colors
        $wp_customize->add_setting('aqualuxe_primary_color', [
            'default'           => '#0a3d62',
            'sanitize_callback' => 'sanitize_hex_color',
        ]);

        $wp_customize->add_control(new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_primary_color',
            [
                'label'   => __('Primary Color', 'aqualuxe'),
                'section' => 'colors',
            ]
        ));

        // Typography
        $wp_customize->add_section('aqualuxe_typography', [
            'title'    => __('Typography', 'aqualuxe'),
            'priority' => 30,
        ]);

        $wp_customize->add_setting('aqualuxe_font_family', [
            'default'           => 'Helvetica, Arial, sans-serif',
            'sanitize_callback' => 'sanitize_text_field',
        ]);

        $wp_customize->add_control('aqualuxe_font_family', [
            'label'   => __('Font Family', 'aqualuxe'),
            'section' => 'aqualuxe_typography',
            'type'    => 'text',
        ]);
    }
}
