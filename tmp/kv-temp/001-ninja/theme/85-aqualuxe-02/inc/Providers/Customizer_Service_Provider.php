<?php
namespace Aqualuxe\Providers;

use Aqualuxe\Support\Container;

class Customizer_Service_Provider
{
    public function register(Container $c): void
    {
        add_action('customize_register', [$this, 'register_customizer']);
        add_action('wp_head', [$this, 'print_customizer_css']);
    }

    public function boot(Container $c): void {}

    public function register_customizer(\WP_Customize_Manager $wp_customize): void
    {
        // Colors
        $wp_customize->add_setting('aqualuxe_primary_color', [
            'default' => '#0a84ff',
            'transport' => 'refresh',
            'sanitize_callback' => 'sanitize_hex_color',
        ]);
        $wp_customize->add_control(new \WP_Customize_Color_Control($wp_customize, 'aqualuxe_primary_color', [
            'label' => __('Primary Color', 'aqualuxe'),
            'section' => 'colors',
        ]));

        // Typography (base font size)
        $wp_customize->add_setting('aqualuxe_base_font_size', [
            'default' => '16',
            'sanitize_callback' => function ($v) { return absint($v) ?: 16; },
        ]);
        $wp_customize->add_control('aqualuxe_base_font_size', [
            'label' => __('Base Font Size (px)', 'aqualuxe'),
            'section' => 'title_tagline',
            'type' => 'number',
            'input_attrs' => ['min' => 12, 'max' => 22],
        ]);
    }

    public function print_customizer_css(): void
    {
        $primary = get_theme_mod('aqualuxe_primary_color', '#0a84ff');
        $font = get_theme_mod('aqualuxe_base_font_size', '16');
        echo '<style>:root{--color-primary:' . esc_attr($primary) . '; font-size:' . esc_attr($font) . 'px;}</style>';
    }
}
