<?php
namespace AquaLuxe;

class Assets {
    public static function enqueue(): void {
        $deps = [];
        $css_src = mix('/css/app.css');
        if ($css_src) { \wp_enqueue_style('aqualuxe-app', $css_src, [], AQUALUXE_VERSION); }
        // Inline CSS variables from Customizer (brand color, typography)
        $primary = \get_theme_mod('aqualuxe_color_primary', '#14a5d1');
        $body_font = \get_theme_mod('aqualuxe_typography_body', 'system-ui');
        $css = ':root{--brand-aqua:' . \esc_attr($primary) . ';} body{font-family:' . \esc_attr($body_font) . ',system-ui,-apple-system,Segoe UI,Roboto,Ubuntu,"Helvetica Neue",Arial,"Noto Sans",sans-serif;}';
        if ($css_src) { \wp_add_inline_style('aqualuxe-app', $css); }
        $app_js = mix('/js/app.js');
        if ($app_js) { \wp_enqueue_script('aqualuxe-app', $app_js, $deps, AQUALUXE_VERSION, true); }
        if (is_wc_active()) {
            $woo_js = mix('/js/woo.js');
            if ($woo_js) { \wp_enqueue_script('aqualuxe-woo', $woo_js, ['jquery'], AQUALUXE_VERSION, true); }
        }
    }

    public static function enqueue_editor(): void {
    $css_src = mix('/css/app.css');
    if ($css_src) { \wp_enqueue_style('aqualuxe-editor', $css_src, [], AQUALUXE_VERSION); }
        $primary = \get_theme_mod('aqualuxe_color_primary', '#14a5d1');
        $body_font = \get_theme_mod('aqualuxe_typography_body', 'system-ui');
        $css = ':root{--brand-aqua:' . \esc_attr($primary) . ';} body{font-family:' . \esc_attr($body_font) . ',system-ui,-apple-system,Segoe UI,Roboto,Ubuntu,"Helvetica Neue",Arial,"Noto Sans",sans-serif;}';
    if ($css_src) { \wp_add_inline_style('aqualuxe-editor', $css); }
    }
}
