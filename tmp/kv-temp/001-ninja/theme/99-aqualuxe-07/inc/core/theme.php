<?php
namespace AquaLuxe\Core;

// Load stubs for static analysis when WordPress isn't bootstrapped.
if (! function_exists('add_action')) { require __DIR__ . '/../compat/wp_stubs.php'; }

use function add_action;
use function add_filter;
use function __;
use function esc_attr;
use function get_theme_mod;
use function get_bloginfo;
use function get_locale;
use function sanitize_hex_color;
use function sanitize_text_field;
use \WP_Customize_Manager;
use \WP_Customize_Color_Control;

/**
 * Theme core: setup, customizer, and hooks.
 */
class Theme
{
    public function boot(): void
    {
    \add_action('customize_register', [$this, 'customizer']);
    \add_filter('body_class', [$this, 'body_classes']);
    \add_action('wp_head', [$this, 'meta_tags'], 1);
    }

    public function customizer(WP_Customize_Manager $wp_customize): void
    {
        // Colors.
        $wp_customize->add_section('aqualuxe_colors', [
            'title' => \__('AquaLuxe Colors', 'aqualuxe'),
        ]);
        $wp_customize->add_setting('aqualuxe_primary_color', [
            'default'           => '#0ea5e9',
            'sanitize_callback' => '\sanitize_hex_color',
        ]);
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_primary_color', [
            'label'   => \__('Primary Color', 'aqualuxe'),
            'section' => 'aqualuxe_colors',
        ]));

        // Typography (simplified example).
        $wp_customize->add_section('aqualuxe_typography', [
            'title' => \__('AquaLuxe Typography', 'aqualuxe'),
        ]);
        $wp_customize->add_setting('aqualuxe_font_base', [
            'default'           => 'ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto',
            'sanitize_callback' => '\sanitize_text_field',
        ]);
        $wp_customize->add_control('aqualuxe_font_base', [
            'label'   => \__('Base Font Stack', 'aqualuxe'),
            'section' => 'aqualuxe_typography',
            'type'    => 'text',
        ]);
    }

    public function body_classes(array $classes): array
    {
        $classes[] = 'aqualuxe';
        if (isset($_COOKIE['aqlx_dark']) && '1' === $_COOKIE['aqlx_dark']) {
            $classes[] = 'theme-dark';
        }
        return $classes;
    }

    public function meta_tags(): void
    {
        // Basic SEO & Open Graph tags (can be extended or replaced by SEO plugins).
    echo "\n<meta name=\"theme-color\" content=\"" . \esc_attr(\get_theme_mod('aqualuxe_primary_color', '#0ea5e9')) . "\">\n";
    echo "<meta property=\"og:site_name\" content=\"" . \esc_attr(\get_bloginfo('name')) . "\">\n";
    echo "<meta property=\"og:locale\" content=\"" . \esc_attr(\get_locale()) . "\">\n";
    }
}
