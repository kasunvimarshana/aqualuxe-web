<?php
namespace AquaLuxe\Modules\Multilingual;

if (!defined('ABSPATH')) exit;

class Module {
    public static function boot(): void {
        add_action('init', [__CLASS__, 'register_locale_switcher']);
        add_filter('locale', [__CLASS__, 'filter_locale']);
        add_action('wp_footer', [__CLASS__, 'switcher_markup']);
    }

    public static function register_locale_switcher(): void {
        // Minimal, theme-level locale switch via query var `lang`.
        add_rewrite_tag('%lang%', '([a-zA-Z\-]+)');
    }

    public static function filter_locale(string $locale): string {
        if (is_admin()) return $locale;
        $req = isset($_GET['lang']) ? sanitize_text_field($_GET['lang']) : '';
        if ($req) {
            return $req;
        }
        return $locale;
    }

    public static function switcher_markup(): void {
        $langs = apply_filters('aqualuxe/available_languages', ['en_US' => 'English']);
        echo '<div class="aqlx-lang-switcher fixed bottom-4 left-4 bg-white/90 dark:bg-slate-900/90 p-2 rounded shadow">';
        echo '<form method="get">';
        echo '<label class="sr-only" for="aqlx-lang">' . esc_html__('Language', 'aqualuxe') . '</label>';
        echo '<select id="aqlx-lang" name="lang" class="min-w-[140px]">';
        foreach ($langs as $code => $label) {
            $sel = selected(get_locale(), $code, false);
            echo '<option value="' . esc_attr($code) . '" ' . $sel . '>' . esc_html($label) . '</option>';
        }
        echo '</select>';
        echo '<button type="submit" class="ml-2 px-3 py-1 bg-sky-600 text-white rounded">' . esc_html__('Apply', 'aqualuxe') . '</button>';
        echo '</form>';
        echo '</div>';
    }
}
