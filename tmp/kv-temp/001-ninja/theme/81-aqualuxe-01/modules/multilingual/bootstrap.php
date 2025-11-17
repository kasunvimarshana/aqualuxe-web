<?php
/** Multilingual readiness (basic i18n switcher; compatible with Polylang/WPML if present) */
if (!defined('ABSPATH')) { exit; }

add_shortcode('ax_language_switcher', function(){
    // If Polylang exists, use its switcher
    if (function_exists('pll_the_languages') && is_callable('pll_the_languages')) {
        ob_start();
        call_user_func('pll_the_languages', ['dropdown'=>0]);
        return ob_get_clean();
    }
    // Fallback basic switcher (single locale)
    return '<span class="text-sm opacity-70">' . esc_html(get_locale()) . '</span>';
});
