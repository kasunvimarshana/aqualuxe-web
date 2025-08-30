<?php
namespace AquaLuxe;

class Assets {
    public static function enqueue(): void {
        $deps = [];
    \wp_enqueue_style('aqualuxe-app', mix('/css/app.css'), [], AQUALUXE_VERSION);
    // Inline CSS variables from Customizer (brand color, typography)
    $primary = \get_theme_mod('aqualuxe_color_primary', '#14a5d1');
    $body_font = \get_theme_mod('aqualuxe_typography_body', 'system-ui');
    $css = ':root{--brand-aqua:' . \esc_attr($primary) . ';} body{font-family:' . \esc_attr($body_font) . ',system-ui,-apple-system,Segoe UI,Roboto,Ubuntu,"Helvetica Neue",Arial,"Noto Sans",sans-serif;}';
    \wp_add_inline_style('aqualuxe-app', $css);
        \wp_enqueue_script('aqualuxe-app', mix('/js/app.js'), $deps, AQUALUXE_VERSION, true);
        if (is_wc_active()) {
            \wp_enqueue_script('aqualuxe-woo', mix('/js/woo.js'), ['jquery'], AQUALUXE_VERSION, true);
        }
    }
}
