<?php
namespace AquaLuxe\Core\Services;

/**
 * Service class to abstract interactions with multilingual plugins.
 *
 * Detects active plugins (Polylang, WPML) and provides a unified API
 * to retrieve available languages and the current language. This decouples
 * the theme from any specific plugin's functions.
 */
class LanguageService
{
    private ?string $active_plugin = null;

    public function __construct()
    {
        if (function_exists('pll_the_languages')) {
            $this->active_plugin = 'polylang';
        } elseif (function_exists('icl_get_languages')) {
            $this->active_plugin = 'wpml';
        }
    }

    /**
     * Check if a supported multilingual plugin is active.
     */
    public function is_active(): bool
    {
        return $this->active_plugin !== null;
    }

    /**
     * Get the list of available languages in a standardized format.
     *
     * @return array An array of language arrays, each containing 'url', 'name', 'slug', 'flag', 'is_current'.
     */
    public function get_languages(): array
    {
        if (!$this->is_active()) {
            return [];
        }

        switch ($this->active_plugin) {
            case 'polylang':
                return $this->get_polylang_languages();
            case 'wpml':
                return $this->get_wpml_languages();
            default:
                return [];
        }
    }

    /**
     * Get languages from Polylang.
     */
    private function get_polylang_languages(): array
    {
        $languages = pll_the_languages(['raw' => 1]);
        if (!is_array($languages)) {
            return [];
        }

        $output = [];
        foreach ($languages as $lang) {
            $output[] = [
                'url' => $lang['url'],
                'name' => $lang['name'],
                'slug' => $lang['slug'],
                'flag' => $lang['flag'],
                'is_current' => (bool) $lang['current_lang'],
            ];
        }
        return $output;
    }

    /**
     * Get languages from WPML.
     */
    private function get_wpml_languages(): array
    {
        $languages = apply_filters('wpml_active_languages', null, ['skip_missing' => 0]);
        if (!is_array($languages)) {
            return [];
        }

        $output = [];
        foreach ($languages as $lang) {
            $output[] = [
                'url' => $lang['url'],
                'name' => $lang['native_name'],
                'slug' => $lang['language_code'],
                'flag' => $lang['country_flag_url'],
                'is_current' => (bool) $lang['active'],
            ];
        }
        return $output;
    }
}
