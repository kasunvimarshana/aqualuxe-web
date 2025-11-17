<?php
namespace AquaLuxe\Core;
if (!defined('ABSPATH')) exit;
class DynamicStyles {
  public static function boot(): void { add_action('wp_head', [__CLASS__, 'css'], 20); }
  public static function css(): void {
    $primary = get_theme_mod('aqualuxe_color_primary', '#0ea5e9');
    $font = get_theme_mod('aqualuxe_body_font', 'ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial');
    echo '<style id="aqlx-vars">:root{--aqlx-primary:' . esc_attr($primary) . ';--aqlx-body-font:' . esc_attr($font) . ';} body{font-family:var(--aqlx-body-font);} .text-primary{color:var(--aqlx-primary);} .bg-primary{background-color:var(--aqlx-primary);}</style>';
  }
}
