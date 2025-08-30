<?php
namespace AquaLuxe\Modules\Multilingual;

const COOKIE = 'aqualuxe_lang';

\add_filter('determine_locale', function($locale){
    if (!\is_admin()) {
        $sel = isset($_GET['lang']) ? \sanitize_text_field($_GET['lang']) : ($_COOKIE[COOKIE] ?? '');
        if ($sel && in_array($sel, \get_available_languages(\get_template_directory() . '/languages'))) {
            if (!headers_sent()) setcookie(COOKIE, $sel, time()+30*\DAY_IN_SECONDS, \COOKIEPATH, \COOKIE_DOMAIN);
            return $sel;
        }
    }
    return $locale;
});

\add_action('aqualuxe/header/actions', function(){
    $langs = \get_available_languages(\get_template_directory() . '/languages');
    array_unshift($langs, 'en_US');
    $langs = array_unique($langs);
    echo '<form method="get" class="text-sm">';
    echo '<label class="sr-only" for="lang">' . \esc_html__('Language', 'aqualuxe') . '</label>';
    echo '<select id="lang" name="lang" class="bg-transparent border rounded px-2 py-1" onchange="this.form.submit()">';
    $current = \determine_locale();
    foreach ($langs as $l) {
        printf('<option value="%1$s" %3$s>%2$s</option>', \esc_attr($l), \esc_html($l), selected($current, $l, false));
    }
    echo '</select></form>';
});
