<?php
namespace AquaLuxe\Modules\I18n;

if (!defined('ABSPATH')) { exit; }

class I18n {
    public static function init(): void {
        \add_shortcode('al_language_switcher', [__CLASS__, 'language_switcher']);
    }

    public static function language_switcher(): string {
        // If Polylang, use its API
        if (\function_exists('pll_the_languages')) {
            ob_start();
            \pll_the_languages(['show_flags'=>1,'show_names'=>1]);
            return ob_get_clean();
        }
        // Fallback: Single-language placeholder
        return '<span class="al_lang">' . esc_html(\get_locale()) . '</span>';
    }
}
