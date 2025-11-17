<?php
namespace AquaLuxe\Core;

class Customizer {
    public static function register(\WP_Customize_Manager $wp_customize) : void {
        // Colors
        $wp_customize->add_section('aqlx_colors', [
            'title' => __('AquaLuxe Colors', 'aqualuxe'),
            'priority' => 30,
        ]);

        $wp_customize->add_setting('aqlx_primary_color', [
            'default' => '#0ea5e9',
            'sanitize_callback' => 'sanitize_hex_color',
        ]);
        $wp_customize->add_control(new \WP_Customize_Color_Control($wp_customize, 'aqlx_primary_color', [
            'label' => __('Primary Color', 'aqualuxe'),
            'section' => 'aqlx_colors',
        ]));

        $wp_customize->add_setting('aqlx_accent_color', [
            'default' => '#22d3ee',
            'sanitize_callback' => 'sanitize_hex_color',
        ]);
        $wp_customize->add_control(new \WP_Customize_Color_Control($wp_customize, 'aqlx_accent_color', [
            'label' => __('Accent Color', 'aqualuxe'),
            'section' => 'aqlx_colors',
        ]));

        // Typography
        $wp_customize->add_section('aqlx_typography', [
            'title' => __('AquaLuxe Typography', 'aqualuxe'),
            'priority' => 31,
        ]);
        $wp_customize->add_setting('aqlx_font_family', [
            'default' => 'ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial, Noto Sans, \"Apple Color Emoji\", \"Segoe UI Emoji\"',
            'sanitize_callback' => [__CLASS__, 'sanitize_font_stack'],
        ]);
        $wp_customize->add_control('aqlx_font_family', [
            'label' => __('Font Stack', 'aqualuxe'),
            'type' => 'text',
            'section' => 'aqlx_typography',
        ]);

        // Layout width
        $wp_customize->add_section('aqlx_layout', [
            'title' => __('Layout', 'aqualuxe'),
            'priority' => 32,
        ]);
        $wp_customize->add_setting('aqlx_container_max', [
            'default' => 1280,
            'sanitize_callback' => 'absint',
        ]);
        $wp_customize->add_control('aqlx_container_max', [
            'label' => __('Container max width (px)', 'aqualuxe'),
            'type' => 'number',
            'section' => 'aqlx_layout',
        ]);

        add_action('wp_head', [__CLASS__, 'output_css_vars']);
    }

    public static function sanitize_font_stack($val) : string {
        return sanitize_text_field($val);
    }

    public static function output_css_vars() : void {
        $primary = get_theme_mod('aqlx_primary_color', '#0ea5e9');
        $accent  = get_theme_mod('aqlx_accent_color', '#22d3ee');
        $font    = get_theme_mod('aqlx_font_family', 'ui-sans-serif, system-ui');
        $maxw    = (int) get_theme_mod('aqlx_container_max', 1280);
        echo '<style id="aqlx-css-vars">:root{--aqlx-primary:' . esc_attr($primary) . ';--aqlx-accent:' . esc_attr($accent) . ';--aqlx-font:' . esc_attr($font) . ';--aqlx-maxw:' . esc_attr($maxw) . 'px}</style>';
    }
}
