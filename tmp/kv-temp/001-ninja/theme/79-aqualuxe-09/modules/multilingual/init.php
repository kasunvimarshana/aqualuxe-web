<?php
declare(strict_types=1);

// Provide a simple language switcher placeholder; real integrations defer to plugins (Polylang/WPML).
add_shortcode('aqlx_lang_switcher', static function () {
    if (function_exists('pll_the_languages')) {
        return do_shortcode('[pll_language_switcher]');
    }
    return '<span>' . esc_html__('English', 'aqualuxe') . '</span>';
});
