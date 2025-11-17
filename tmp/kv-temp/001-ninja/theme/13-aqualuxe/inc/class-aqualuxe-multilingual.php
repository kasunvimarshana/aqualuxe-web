<?php
/**
 * Multilingual support for AquaLuxe theme
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * AquaLuxe Multilingual Class
 */
class AquaLuxe_Multilingual {
    /**
     * Available languages
     *
     * @var array
     */
    private $languages = array();

    /**
     * Current language
     *
     * @var string
     */
    private $current_language = '';

    /**
     * Constructor
     */
    public function __construct() {
        // Set default languages
        $this->languages = array(
            'en' => array(
                'name' => 'English',
                'flag' => 'us',
                'locale' => 'en_US',
            ),
            'es' => array(
                'name' => 'Español',
                'flag' => 'es',
                'locale' => 'es_ES',
            ),
            'fr' => array(
                'name' => 'Français',
                'flag' => 'fr',
                'locale' => 'fr_FR',
            ),
            'de' => array(
                'name' => 'Deutsch',
                'flag' => 'de',
                'locale' => 'de_DE',
            ),
            'zh' => array(
                'name' => '中文',
                'flag' => 'cn',
                'locale' => 'zh_CN',
            ),
        );

        // Set current language
        $this->current_language = $this->get_current_language();

        // Add language switcher to header
        add_action('aqualuxe_language_switcher', array($this, 'language_switcher'));

        // Filter for current language code
        add_filter('aqualuxe_current_language', array($this, 'get_current_language_code'));

        // Add language class to body
        add_filter('body_class', array($this, 'add_language_class'));

        // Add WPML/Polylang compatibility
        add_action('after_setup_theme', array($this, 'multilingual_plugins_compatibility'));
    }

    /**
     * Get current language
     *
     * @return string
     */
    public function get_current_language() {
        // Default to English
        $language = 'en';

        // Check if WPML is active
        if (defined('ICL_LANGUAGE_CODE')) {
            $language = ICL_LANGUAGE_CODE;
        }
        // Check if Polylang is active
        elseif (function_exists('pll_current_language')) {
            $language = pll_current_language();
        }
        // Check for locale
        else {
            $locale = get_locale();
            $language_code = substr($locale, 0, 2);
            if (array_key_exists($language_code, $this->languages)) {
                $language = $language_code;
            }
        }

        return $language;
    }

    /**
     * Get current language code for display
     *
     * @return string
     */
    public function get_current_language_code() {
        return strtoupper($this->current_language);
    }

    /**
     * Add language class to body
     *
     * @param array $classes Body classes.
     * @return array
     */
    public function add_language_class($classes) {
        $classes[] = 'lang-' . $this->current_language;
        return $classes;
    }

    /**
     * Language switcher
     */
    public function language_switcher() {
        // If WPML is active, use WPML language switcher
        if (function_exists('icl_get_languages')) {
            $languages = icl_get_languages('skip_missing=0');
            if (!empty($languages)) {
                foreach ($languages as $lang_code => $language) {
                    echo '<a href="' . esc_url($language['url']) . '" class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-300' . ($language['active'] ? ' font-bold' : '') . '">';
                    echo esc_html($language['native_name']);
                    echo '</a>';
                }
            }
            return;
        }

        // If Polylang is active, use Polylang language switcher
        if (function_exists('pll_the_languages')) {
            $args = array(
                'show_flags' => 0,
                'show_names' => 1,
                'echo' => 0,
                'hide_if_empty' => 0,
            );
            $languages = pll_the_languages($args);
            echo wp_kses_post($languages);
            return;
        }

        // Default language switcher
        foreach ($this->languages as $code => $language) {
            $active = ($code === $this->current_language) ? ' font-bold' : '';
            echo '<a href="#" data-lang="' . esc_attr($code) . '" class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-300' . esc_attr($active) . '">';
            echo esc_html($language['name']);
            echo '</a>';
        }
    }

    /**
     * Add compatibility with multilingual plugins
     */
    public function multilingual_plugins_compatibility() {
        // WPML compatibility
        if (defined('ICL_SITEPRESS_VERSION')) {
            // Add theme text domain to WPML string translation
            if (has_action('wpml_register_string_domain')) {
                do_action('wpml_register_string_domain', 'aqualuxe', array(
                    'admin_message' => 'This string is registered for translation',
                ));
            }
        }

        // Polylang compatibility
        if (function_exists('pll_register_string')) {
            // Register strings for translation
            pll_register_string('aqualuxe_theme_footer_copyright', get_theme_mod('copyright_text', sprintf(__('© %s AquaLuxe. All rights reserved.', 'aqualuxe'), date('Y'))), 'AquaLuxe Theme');
        }
    }

    /**
     * Get available languages
     *
     * @return array
     */
    public function get_languages() {
        return $this->languages;
    }

    /**
     * Translate string
     *
     * @param string $string String to translate.
     * @param string $domain Text domain.
     * @return string
     */
    public function translate($string, $domain = 'aqualuxe') {
        // If WPML is active
        if (function_exists('icl_t')) {
            return icl_t($domain, $string, $string);
        }

        // If Polylang is active
        if (function_exists('pll__')) {
            return pll__($string);
        }

        // Default translation
        return __($string, $domain);
    }
}

// Initialize the class
new AquaLuxe_Multilingual();