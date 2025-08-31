<?php
/** Simple multilingual module (placeholder for integration with Polylang/WPML) */

add_action('init', function(){
    // Register text domain again to ensure module strings load
    load_theme_textdomain('aqualuxe', AQUALUXE_PATH . 'languages');
});

// Language switcher shortcode
add_shortcode('aqualuxe_language_switcher', function(){
    // Fallback simple switcher (no plugin): just list available locales configured in Settings > General
    $langs = apply_filters('aqualuxe/languages', [ get_locale() ]);
    $out = '<nav class="lang-switch" aria-label="Language">';
    foreach ($langs as $code) {
        $out .= '<span class="px-2">' . esc_html($code) . '</span>';
    }
    return $out . '</nav>';
});
