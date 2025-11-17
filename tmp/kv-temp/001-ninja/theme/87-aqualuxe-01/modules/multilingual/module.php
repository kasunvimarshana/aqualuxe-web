<?php
// Multilingual compatibility module. Works with Polylang or WPML if active.

// Language switcher shortcode [aqualuxe_language_switcher]
if (!shortcode_exists('aqualuxe_language_switcher')) {
\add_shortcode('aqualuxe_language_switcher', function () {
    // Polylang
    if (function_exists('pll_the_languages')) {
        ob_start();
        pll_the_languages(['show_flags' => 1, 'show_names' => 1]);
        return ob_get_clean();
    }
    // WPML
    if (function_exists('icl_get_languages')) {
        $langs = icl_get_languages('skip_missing=0');
        if (!empty($langs)) {
            $out = '<ul class="lang-switcher">';
            foreach ($langs as $lang) {
                $out .= '<li><a href="' . esc_url($lang['url']) . '">' . esc_html($lang['translated_name']) . '</a></li>';
            }
            $out .= '</ul>';
            return $out;
        }
    }
    return '';
});
}
