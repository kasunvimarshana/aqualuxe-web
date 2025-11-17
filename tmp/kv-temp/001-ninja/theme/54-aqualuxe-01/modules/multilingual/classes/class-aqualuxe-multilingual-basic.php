<?php
/**
 * AquaLuxe Basic Multilingual Implementation
 *
 * @package AquaLuxe
 * @subpackage Multilingual
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * AquaLuxe Basic Multilingual Class
 * 
 * Provides basic multilingual functionality when no plugin is active
 */
class AquaLuxe_Multilingual_Basic {
    /**
     * Current language
     *
     * @var string
     */
    private $current_language = '';

    /**
     * Default language
     *
     * @var string
     */
    private $default_language = '';

    /**
     * Available languages
     *
     * @var array
     */
    private $languages = [];

    /**
     * Translations
     *
     * @var array
     */
    private $translations = [];

    /**
     * Constructor
     */
    public function __construct() {
        // Get multilingual module settings
        $settings = apply_filters('aqualuxe_multilingual_settings', []);
        
        // Set languages
        $this->languages = isset($settings['languages']) ? $settings['languages'] : [];
        
        // Set default language
        $this->default_language = isset($settings['default_language']) ? $settings['default_language'] : 'en_US';
        
        // Set current language
        $this->current_language = $this->get_current_language();
        
        // Load translations
        $this->load_translations();
        
        // Register hooks
        $this->register_hooks();
    }

    /**
     * Register hooks
     */
    private function register_hooks() {
        // Filter locale
        add_filter('locale', [$this, 'filter_locale']);
        
        // Filter translated strings
        add_filter('gettext', [$this, 'translate_string'], 10, 3);
        
        // Handle language switching
        add_action('init', [$this, 'handle_language_switch']);
        
        // Add query var
        add_filter('query_vars', [$this, 'add_query_vars']);
        
        // Filter home URL
        add_filter('home_url', [$this, 'filter_home_url'], 10, 2);
        
        // Filter page link
        add_filter('page_link', [$this, 'filter_page_link'], 10, 2);
        
        // Filter post link
        add_filter('post_link', [$this, 'filter_post_link'], 10, 2);
        
        // Filter term link
        add_filter('term_link', [$this, 'filter_term_link'], 10, 3);
    }

    /**
     * Get current language
     *
     * @return string
     */
    private function get_current_language() {
        $language = $this->default_language;
        
        // Check for language query var
        if (isset($_GET['lang'])) {
            $lang = sanitize_text_field($_GET['lang']);
            
            // Verify it's a valid language
            if (isset($this->languages[$lang])) {
                $language = $lang;
                
                // Set cookie for 30 days
                setcookie('aqualuxe_language', $lang, time() + (86400 * 30), '/');
            }
        }
        // Check for language cookie
        elseif (isset($_COOKIE['aqualuxe_language'])) {
            $lang = sanitize_text_field($_COOKIE['aqualuxe_language']);
            
            // Verify it's a valid language
            if (isset($this->languages[$lang])) {
                $language = $lang;
            }
        }
        
        return $language;
    }

    /**
     * Load translations
     */
    private function load_translations() {
        // Get translation files
        $translation_files = glob(AQUALUXE_MODULES_DIR . 'multilingual/translations/*.php');
        
        if (!$translation_files) {
            return;
        }
        
        foreach ($translation_files as $file) {
            $lang = basename($file, '.php');
            
            if (isset($this->languages[$lang])) {
                $translations = include $file;
                
                if (is_array($translations)) {
                    $this->translations[$lang] = $translations;
                }
            }
        }
    }

    /**
     * Filter locale
     *
     * @param string $locale Current locale
     * @return string
     */
    public function filter_locale($locale) {
        if (isset($this->languages[$this->current_language]['locale'])) {
            return $this->languages[$this->current_language]['locale'];
        }
        
        return $locale;
    }

    /**
     * Translate string
     *
     * @param string $translation Translated text
     * @param string $text Text to translate
     * @param string $domain Text domain
     * @return string
     */
    public function translate_string($translation, $text, $domain) {
        // Skip if not our domain or no translations available
        if ($domain !== 'aqualuxe' || !isset($this->translations[$this->current_language])) {
            return $translation;
        }
        
        // Check if we have a translation
        if (isset($this->translations[$this->current_language][$text])) {
            return $this->translations[$this->current_language][$text];
        }
        
        return $translation;
    }

    /**
     * Handle language switch
     */
    public function handle_language_switch() {
        if (isset($_GET['switch_lang']) && isset($this->languages[$_GET['switch_lang']])) {
            $lang = sanitize_text_field($_GET['switch_lang']);
            
            // Set cookie
            setcookie('aqualuxe_language', $lang, time() + (86400 * 30), '/');
            
            // Redirect to same page without switch_lang parameter
            $redirect_url = remove_query_arg('switch_lang');
            $redirect_url = add_query_arg('lang', $lang, $redirect_url);
            
            wp_redirect($redirect_url);
            exit;
        }
    }

    /**
     * Add query vars
     *
     * @param array $vars Query vars
     * @return array
     */
    public function add_query_vars($vars) {
        $vars[] = 'lang';
        return $vars;
    }

    /**
     * Filter home URL
     *
     * @param string $url Home URL
     * @param string $path Path
     * @return string
     */
    public function filter_home_url($url, $path) {
        // Skip if current language is default
        if ($this->current_language === $this->default_language) {
            return $url;
        }
        
        // Add language parameter
        return add_query_arg('lang', $this->current_language, $url);
    }

    /**
     * Filter page link
     *
     * @param string $link Page link
     * @param int $post_id Post ID
     * @return string
     */
    public function filter_page_link($link, $post_id) {
        // Skip if current language is default
        if ($this->current_language === $this->default_language) {
            return $link;
        }
        
        // Add language parameter
        return add_query_arg('lang', $this->current_language, $link);
    }

    /**
     * Filter post link
     *
     * @param string $link Post link
     * @param object $post Post object
     * @return string
     */
    public function filter_post_link($link, $post) {
        // Skip if current language is default
        if ($this->current_language === $this->default_language) {
            return $link;
        }
        
        // Add language parameter
        return add_query_arg('lang', $this->current_language, $link);
    }

    /**
     * Filter term link
     *
     * @param string $link Term link
     * @param object $term Term object
     * @param string $taxonomy Taxonomy
     * @return string
     */
    public function filter_term_link($link, $term, $taxonomy) {
        // Skip if current language is default
        if ($this->current_language === $this->default_language) {
            return $link;
        }
        
        // Add language parameter
        return add_query_arg('lang', $this->current_language, $link);
    }

    /**
     * Get translation for a post
     *
     * @param int $post_id Post ID
     * @param string $lang Language code
     * @return int|null
     */
    public function get_post_translation($post_id, $lang) {
        // Get post translations
        $translations = get_post_meta($post_id, '_aqualuxe_translations', true);
        
        if (!$translations || !is_array($translations)) {
            return null;
        }
        
        return isset($translations[$lang]) ? $translations[$lang] : null;
    }

    /**
     * Set translation for a post
     *
     * @param int $post_id Post ID
     * @param string $lang Language code
     * @param int $translation_id Translation post ID
     * @return bool
     */
    public function set_post_translation($post_id, $lang, $translation_id) {
        // Get post translations
        $translations = get_post_meta($post_id, '_aqualuxe_translations', true);
        
        if (!$translations || !is_array($translations)) {
            $translations = [];
        }
        
        // Set translation
        $translations[$lang] = $translation_id;
        
        // Update post meta
        return update_post_meta($post_id, '_aqualuxe_translations', $translations);
    }

    /**
     * Get translation for a term
     *
     * @param int $term_id Term ID
     * @param string $taxonomy Taxonomy
     * @param string $lang Language code
     * @return int|null
     */
    public function get_term_translation($term_id, $taxonomy, $lang) {
        // Get term translations
        $translations = get_term_meta($term_id, '_aqualuxe_translations', true);
        
        if (!$translations || !is_array($translations)) {
            return null;
        }
        
        return isset($translations[$lang]) ? $translations[$lang] : null;
    }

    /**
     * Set translation for a term
     *
     * @param int $term_id Term ID
     * @param string $taxonomy Taxonomy
     * @param string $lang Language code
     * @param int $translation_id Translation term ID
     * @return bool
     */
    public function set_term_translation($term_id, $taxonomy, $lang, $translation_id) {
        // Get term translations
        $translations = get_term_meta($term_id, '_aqualuxe_translations', true);
        
        if (!$translations || !is_array($translations)) {
            $translations = [];
        }
        
        // Set translation
        $translations[$lang] = $translation_id;
        
        // Update term meta
        return update_term_meta($term_id, '_aqualuxe_translations', $translations);
    }
}