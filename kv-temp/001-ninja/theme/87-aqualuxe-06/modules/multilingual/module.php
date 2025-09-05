<?php
// Multilingual compatibility module. Works with Polylang or WPML if active.

// Language switcher shortcode [aqualuxe_language_switcher]
if (!\function_exists('shortcode_exists') || !\call_user_func('shortcode_exists', 'aqualuxe_language_switcher')) {
    \add_shortcode('aqualuxe_language_switcher', function () {
        // Polylang (raw array)
        if (\function_exists('pll_the_languages')) {
            $langs = \call_user_func('pll_the_languages', ['raw' => 1]);
            if (is_array($langs) && !empty($langs)) {
                ob_start();
                echo '<nav aria-label="Language" class="language-switcher">';
                echo '<ul class="flex items-center gap-3">';
                foreach ($langs as $code => $l) {
                    $active = !empty($l['current_lang']);
                    $class = $active ? 'font-semibold underline' : 'opacity-80 hover:opacity-100 underline';
                    $classEsc = (\function_exists('esc_attr') ? \call_user_func('esc_attr', $class) : $class);
                    $codeEsc  = (\function_exists('esc_attr') ? \call_user_func('esc_attr', $code) : $code);
                    $urlEsc   = (\function_exists('esc_url') ? \call_user_func('esc_url', $l['url']) : $l['url']);
                    $nameEsc  = (\function_exists('esc_html') ? \call_user_func('esc_html', $l['name']) : $l['name']);
                    echo '<li><a class="' . $classEsc . '" hreflang="' . $codeEsc . '" href="' . $urlEsc . '">' . $nameEsc . '</a></li>';
                }
                echo '</ul></nav>';
                return (string) ob_get_clean();
            }
        }
        // WPML
        if (\function_exists('icl_get_languages')) {
            $langs = \call_user_func('icl_get_languages', 'skip_missing=0');
            if (is_array($langs) && !empty($langs)) {
                ob_start();
                echo '<nav aria-label="Language" class="language-switcher">';
                echo '<ul class="flex items-center gap-3">';
                foreach ($langs as $l) {
                    $active = !empty($l['active']);
                    $class = $active ? 'font-semibold underline' : 'opacity-80 hover:opacity-100 underline';
                    $classEsc = (\function_exists('esc_attr') ? \call_user_func('esc_attr', $class) : $class);
                    $hlang    = $l['language_code'];
                    $hlangEsc = (\function_exists('esc_attr') ? \call_user_func('esc_attr', $hlang) : $hlang);
                    $urlEsc   = (\function_exists('esc_url') ? \call_user_func('esc_url', $l['url']) : $l['url']);
                    $label    = $l['native_name'] ?? $l['translated_name'] ?? $l['language_code'];
                    $labelEsc = (\function_exists('esc_html') ? \call_user_func('esc_html', $label) : $label);
                    echo '<li><a class="' . $classEsc . '" hreflang="' . $hlangEsc . '" href="' . $urlEsc . '">' . $labelEsc . '</a></li>';
                }
                echo '</ul></nav>';
                return (string) ob_get_clean();
            }
        }
        // Fallback to site language display only
        $locale = (\function_exists('get_locale') ? \call_user_func('get_locale') : 'en_US');
        $short = strtoupper(explode('_', $locale)[0] ?? $locale);
        $shortEsc = (\function_exists('esc_html') ? \call_user_func('esc_html', $short) : $short);
        $label = (\function_exists('esc_attr__') ? \call_user_func('esc_attr__', 'Language', 'aqualuxe') : 'Language');
        return '<span class="language-switcher text-sm opacity-80" aria-label="' . $label . '">' . $shortEsc . '</span>';
    });
}
