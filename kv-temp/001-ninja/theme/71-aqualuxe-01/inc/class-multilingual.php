<?php
/**
 * Multilingual Support Class
 * 
 * @package AquaLuxe
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Multilingual support class
 */
class Multilingual {
    
    /**
     * Instance of this class
     */
    private static $instance = null;
    
    /**
     * Current language
     */
    private $current_language = 'en';
    
    /**
     * Available languages
     */
    private $languages = [];
    
    /**
     * Constructor
     */
    private function __construct() {
        $this->init_languages();
        $this->init_hooks();
    }
    
    /**
     * Get instance
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Initialize languages
     */
    private function init_languages() {
        $this->languages = [
            'en' => [
                'name' => 'English',
                'native_name' => 'English',
                'code' => 'en',
                'locale' => 'en_US',
                'flag' => '🇺🇸',
                'rtl' => false,
                'active' => true,
            ],
            'es' => [
                'name' => 'Spanish',
                'native_name' => 'Español',
                'code' => 'es',
                'locale' => 'es_ES',
                'flag' => '🇪🇸',
                'rtl' => false,
                'active' => get_theme_mod('aqualuxe_enable_spanish', false),
            ],
            'fr' => [
                'name' => 'French',
                'native_name' => 'Français',
                'code' => 'fr',
                'locale' => 'fr_FR',
                'flag' => '🇫🇷',
                'rtl' => false,
                'active' => get_theme_mod('aqualuxe_enable_french', false),
            ],
            'de' => [
                'name' => 'German',
                'native_name' => 'Deutsch',
                'code' => 'de',
                'locale' => 'de_DE',
                'flag' => '🇩🇪',
                'rtl' => false,
                'active' => get_theme_mod('aqualuxe_enable_german', false),
            ],
            'it' => [
                'name' => 'Italian',
                'native_name' => 'Italiano',
                'code' => 'it',
                'locale' => 'it_IT',
                'flag' => '🇮🇹',
                'rtl' => false,
                'active' => get_theme_mod('aqualuxe_enable_italian', false),
            ],
            'pt' => [
                'name' => 'Portuguese',
                'native_name' => 'Português',
                'code' => 'pt',
                'locale' => 'pt_BR',
                'flag' => '🇧🇷',
                'rtl' => false,
                'active' => get_theme_mod('aqualuxe_enable_portuguese', false),
            ],
            'ar' => [
                'name' => 'Arabic',
                'native_name' => 'العربية',
                'code' => 'ar',
                'locale' => 'ar',
                'flag' => '🇸🇦',
                'rtl' => true,
                'active' => get_theme_mod('aqualuxe_enable_arabic', false),
            ],
            'zh' => [
                'name' => 'Chinese',
                'native_name' => '中文',
                'code' => 'zh',
                'locale' => 'zh_CN',
                'flag' => '🇨🇳',
                'rtl' => false,
                'active' => get_theme_mod('aqualuxe_enable_chinese', false),
            ],
            'ja' => [
                'name' => 'Japanese',
                'native_name' => '日本語',
                'code' => 'ja',
                'locale' => 'ja',
                'flag' => '🇯🇵',
                'rtl' => false,
                'active' => get_theme_mod('aqualuxe_enable_japanese', false),
            ],
            'ko' => [
                'name' => 'Korean',
                'native_name' => '한국어',
                'code' => 'ko',
                'locale' => 'ko_KR',
                'flag' => '🇰🇷',
                'rtl' => false,
                'active' => get_theme_mod('aqualuxe_enable_korean', false),
            ],
        ];
        
        // Filter active languages
        $this->languages = array_filter($this->languages, function($language) {
            return $language['active'];
        });
        
        // Set current language
        $this->detect_current_language();
    }
    
    /**
     * Initialize hooks
     */
    private function init_hooks() {
        // Language detection and setup
        add_action('init', [$this, 'setup_language'], 1);
        
        // Add language switcher to navigation
        add_filter('wp_nav_menu_items', [$this, 'add_language_switcher_to_menu'], 10, 2);
        
        // Add hreflang tags
        add_action('wp_head', [$this, 'add_hreflang_tags']);
        
        // Body class for RTL languages
        add_filter('body_class', [$this, 'add_language_body_class']);
        
        // Currency switching for WooCommerce
        if (class_exists('WooCommerce')) {
            add_filter('woocommerce_currency', [$this, 'get_currency_by_language']);
            add_filter('woocommerce_currency_symbol', [$this, 'get_currency_symbol_by_language']);
        }
        
        // Ajax language switching
        add_action('wp_ajax_aqualuxe_switch_language', [$this, 'ajax_switch_language']);
        add_action('wp_ajax_nopriv_aqualuxe_switch_language', [$this, 'ajax_switch_language']);
        
        // Admin hooks
        if (is_admin()) {
            add_action('admin_menu', [$this, 'add_admin_menu']);
        }
        
        // Translation functions
        add_filter('gettext', [$this, 'translate_text'], 10, 3);
        add_filter('ngettext', [$this, 'translate_plural'], 10, 5);
        
        // URL rewriting for language prefixes
        add_filter('rewrite_rules_array', [$this, 'add_language_rewrite_rules']);
        add_filter('query_vars', [$this, 'add_language_query_vars']);
        add_action('template_redirect', [$this, 'handle_language_redirect']);
    }
    
    /**
     * Detect current language
     */
    private function detect_current_language() {
        // Check for language in URL
        $request_uri = $_SERVER['REQUEST_URI'] ?? '';
        foreach ($this->languages as $code => $language) {
            if (preg_match('/^\/(' . $code . ')\//', $request_uri, $matches)) {
                $this->current_language = $matches[1];
                return;
            }
        }
        
        // Check for language in session/cookie
        if (isset($_COOKIE['aqualuxe_language'])) {
            $cookie_lang = sanitize_text_field($_COOKIE['aqualuxe_language']);
            if (isset($this->languages[$cookie_lang])) {
                $this->current_language = $cookie_lang;
                return;
            }
        }
        
        // Auto-detect from browser
        if (get_theme_mod('aqualuxe_auto_detect_language', true)) {
            $browser_language = $this->detect_browser_language();
            if ($browser_language && isset($this->languages[$browser_language])) {
                $this->current_language = $browser_language;
                return;
            }
        }
        
        // Default to site language
        $site_locale = get_locale();
        foreach ($this->languages as $code => $language) {
            if ($language['locale'] === $site_locale) {
                $this->current_language = $code;
                return;
            }
        }
        
        // Fallback to English
        $this->current_language = 'en';
    }
    
    /**
     * Detect browser language
     */
    private function detect_browser_language() {
        if (!isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            return null;
        }
        
        $accepted_languages = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
        preg_match_all('/([a-z]{1,8}(?:-[a-z]{1,8})?)\s*(?:;\s*q\s*=\s*(1|0\.[0-9]+))?/i', $accepted_languages, $matches);
        
        if (!$matches[1]) {
            return null;
        }
        
        $languages = [];
        foreach ($matches[1] as $i => $language) {
            $quality = $matches[2][$i] ?? 1.0;
            $lang_code = strtolower(substr($language, 0, 2));
            $languages[$lang_code] = (float) $quality;
        }
        
        arsort($languages);
        
        foreach ($languages as $lang_code => $quality) {
            if (isset($this->languages[$lang_code])) {
                return $lang_code;
            }
        }
        
        return null;
    }
    
    /**
     * Setup language
     */
    public function setup_language() {
        // Switch locale
        $locale = $this->languages[$this->current_language]['locale'] ?? 'en_US';
        switch_to_locale($locale);
        
        // Load text domain for current language
        $this->load_language_files();
        
        // Set RTL if needed
        if ($this->languages[$this->current_language]['rtl'] ?? false) {
            global $wp_locale;
            $wp_locale->text_direction = 'rtl';
        }
        
        // Set language cookie
        if (!isset($_COOKIE['aqualuxe_language']) || $_COOKIE['aqualuxe_language'] !== $this->current_language) {
            setcookie('aqualuxe_language', $this->current_language, time() + (365 * 24 * 60 * 60), '/');
        }
    }
    
    /**
     * Load language files
     */
    private function load_language_files() {
        $language_dir = AQUALUXE_THEME_DIR . '/languages/';
        $locale = $this->languages[$this->current_language]['locale'] ?? 'en_US';
        
        // Load theme text domain
        load_theme_textdomain('aqualuxe', $language_dir);
        
        // Load WordPress core translations
        load_default_textdomain($locale);
        
        // Load custom translations if available
        $custom_mo_file = $language_dir . $locale . '-custom.mo';
        if (file_exists($custom_mo_file)) {
            load_textdomain('aqualuxe-custom', $custom_mo_file);
        }
    }
    
    /**
     * Add language switcher to menu
     */
    public function add_language_switcher_to_menu($items, $args) {
        if (!get_theme_mod('aqualuxe_show_language_switcher', true)) {
            return $items;
        }
        
        if ($args->theme_location === 'primary') {
            $switcher = $this->get_language_switcher_html();
            $items .= '<li class="menu-item menu-item-language-switcher">' . $switcher . '</li>';
        }
        
        return $items;
    }
    
    /**
     * Get language switcher HTML
     */
    public function get_language_switcher_html($style = 'dropdown') {
        if (count($this->languages) <= 1) {
            return '';
        }
        
        $current_lang = $this->languages[$this->current_language];
        $switcher_html = '';
        
        if ($style === 'dropdown') {
            $switcher_html .= '<div class="language-switcher dropdown relative" data-dropdown>';
            $switcher_html .= '<button class="language-toggle flex items-center gap-2 px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400" data-dropdown-toggle>';
            $switcher_html .= '<span class="flag">' . $current_lang['flag'] . '</span>';
            $switcher_html .= '<span class="language-name hidden sm:inline">' . $current_lang['native_name'] . '</span>';
            $switcher_html .= '<svg class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">';
            $switcher_html .= '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>';
            $switcher_html .= '</svg>';
            $switcher_html .= '</button>';
            
            $switcher_html .= '<div class="language-menu dropdown-menu absolute top-full right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 hidden z-50">';
            
            foreach ($this->languages as $code => $language) {
                if ($code === $this->current_language) continue;
                
                $url = $this->get_language_url($code);
                $switcher_html .= '<a href="' . esc_url($url) . '" class="language-option flex items-center gap-3 px-4 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 first:rounded-t-lg last:rounded-b-lg" data-language="' . esc_attr($code) . '">';
                $switcher_html .= '<span class="flag">' . $language['flag'] . '</span>';
                $switcher_html .= '<span class="language-name">' . $language['native_name'] . '</span>';
                $switcher_html .= '</a>';
            }
            
            $switcher_html .= '</div>';
            $switcher_html .= '</div>';
        } elseif ($style === 'flags') {
            $switcher_html .= '<div class="language-switcher flags flex items-center gap-2">';
            
            foreach ($this->languages as $code => $language) {
                $url = $this->get_language_url($code);
                $is_current = $code === $this->current_language;
                
                $switcher_html .= '<a href="' . esc_url($url) . '" class="language-flag w-8 h-8 flex items-center justify-center rounded border-2 ' . ($is_current ? 'border-primary-500 bg-primary-50 dark:bg-primary-900' : 'border-gray-200 dark:border-gray-700 hover:border-primary-300') . '" data-language="' . esc_attr($code) . '" title="' . esc_attr($language['name']) . '">';
                $switcher_html .= '<span class="text-lg">' . $language['flag'] . '</span>';
                $switcher_html .= '</a>';
            }
            
            $switcher_html .= '</div>';
        }
        
        return $switcher_html;
    }
    
    /**
     * Get URL for specific language
     */
    public function get_language_url($language_code) {
        $current_url = home_url($_SERVER['REQUEST_URI'] ?? '/');
        
        // Remove current language prefix if exists
        foreach ($this->languages as $code => $language) {
            $pattern = '/^' . preg_quote(home_url('/' . $code . '/'), '/') . '/';
            if (preg_match($pattern, $current_url)) {
                $current_url = str_replace(home_url('/' . $code . '/'), home_url('/'), $current_url);
                break;
            }
        }
        
        // Add new language prefix
        if ($language_code !== 'en') {
            $current_url = home_url('/' . $language_code . '/' . ltrim(str_replace(home_url(), '', $current_url), '/'));
        }
        
        return $current_url;
    }
    
    /**
     * Add hreflang tags
     */
    public function add_hreflang_tags() {
        if (count($this->languages) <= 1) {
            return;
        }
        
        foreach ($this->languages as $code => $language) {
            $url = $this->get_language_url($code);
            echo '<link rel="alternate" hreflang="' . esc_attr($language['locale']) . '" href="' . esc_url($url) . '">' . "\n";
        }
        
        // Add x-default
        $default_url = $this->get_language_url('en');
        echo '<link rel="alternate" hreflang="x-default" href="' . esc_url($default_url) . '">' . "\n";
    }
    
    /**
     * Add language body class
     */
    public function add_language_body_class($classes) {
        $classes[] = 'lang-' . $this->current_language;
        
        if ($this->languages[$this->current_language]['rtl'] ?? false) {
            $classes[] = 'rtl';
        }
        
        return $classes;
    }
    
    /**
     * Get currency by language
     */
    public function get_currency_by_language($currency) {
        $currency_map = [
            'en' => 'USD',
            'es' => 'EUR',
            'fr' => 'EUR',
            'de' => 'EUR',
            'it' => 'EUR',
            'pt' => 'BRL',
            'ar' => 'SAR',
            'zh' => 'CNY',
            'ja' => 'JPY',
            'ko' => 'KRW',
        ];
        
        return $currency_map[$this->current_language] ?? $currency;
    }
    
    /**
     * Get currency symbol by language
     */
    public function get_currency_symbol_by_language($symbol) {
        $symbol_map = [
            'USD' => '$',
            'EUR' => '€',
            'BRL' => 'R$',
            'SAR' => 'ر.س',
            'CNY' => '¥',
            'JPY' => '¥',
            'KRW' => '₩',
        ];
        
        $currency = $this->get_currency_by_language(get_woocommerce_currency());
        return $symbol_map[$currency] ?? $symbol;
    }
    
    /**
     * Ajax language switching
     */
    public function ajax_switch_language() {
        check_ajax_referer('aqualuxe_nonce', 'nonce');
        
        $language = sanitize_text_field($_POST['language'] ?? '');
        
        if (!isset($this->languages[$language])) {
            wp_send_json_error(['message' => __('Invalid language', 'aqualuxe')]);
        }
        
        $this->current_language = $language;
        $url = $this->get_language_url($language);
        
        // Set cookie
        setcookie('aqualuxe_language', $language, time() + (365 * 24 * 60 * 60), '/');
        
        wp_send_json_success([
            'language' => $language,
            'url' => $url,
            'message' => __('Language switched successfully', 'aqualuxe')
        ]);
    }
    
    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        add_theme_page(
            __('Multilingual Settings', 'aqualuxe'),
            __('Languages', 'aqualuxe'),
            'manage_options',
            'aqualuxe-languages',
            [$this, 'admin_page']
        );
    }
    
    /**
     * Admin page
     */
    public function admin_page() {
        if (isset($_POST['submit'])) {
            $this->save_admin_settings();
        }
        
        include AQUALUXE_THEME_DIR . '/admin/pages/languages.php';
    }
    
    /**
     * Save admin settings
     */
    private function save_admin_settings() {
        check_admin_referer('aqualuxe_languages_nonce');
        
        // Save language settings
        foreach ($this->languages as $code => $language) {
            $enabled = isset($_POST['languages'][$code]['enabled']);
            set_theme_mod('aqualuxe_enable_' . $language['name'], $enabled);
        }
        
        // Save other settings
        $auto_detect = isset($_POST['auto_detect_language']);
        set_theme_mod('aqualuxe_auto_detect_language', $auto_detect);
        
        $show_switcher = isset($_POST['show_language_switcher']);
        set_theme_mod('aqualuxe_show_language_switcher', $show_switcher);
        
        add_settings_error('aqualuxe_languages', 'settings_updated', __('Settings saved.', 'aqualuxe'), 'updated');
    }
    
    /**
     * Translate text
     */
    public function translate_text($translation, $text, $domain) {
        if ($domain !== 'aqualuxe') {
            return $translation;
        }
        
        // Get custom translations
        $translations = $this->get_custom_translations();
        
        if (isset($translations[$this->current_language][$text])) {
            return $translations[$this->current_language][$text];
        }
        
        return $translation;
    }
    
    /**
     * Translate plural text
     */
    public function translate_plural($translation, $single, $plural, $number, $domain) {
        if ($domain !== 'aqualuxe') {
            return $translation;
        }
        
        $translations = $this->get_custom_translations();
        $key = $number === 1 ? $single : $plural;
        
        if (isset($translations[$this->current_language][$key])) {
            return $translations[$this->current_language][$key];
        }
        
        return $translation;
    }
    
    /**
     * Get custom translations
     */
    private function get_custom_translations() {
        static $translations = null;
        
        if ($translations === null) {
            $translations = get_option('aqualuxe_custom_translations', []);
        }
        
        return $translations;
    }
    
    /**
     * Add language rewrite rules
     */
    public function add_language_rewrite_rules($rules) {
        $new_rules = [];
        
        foreach ($this->languages as $code => $language) {
            if ($code === 'en') continue; // English is default, no prefix needed
            
            $new_rules[$code . '/?$'] = 'index.php?lang=' . $code;
            $new_rules[$code . '/(.+)$'] = 'index.php?lang=' . $code . '&pagename=$matches[1]';
        }
        
        return $new_rules + $rules;
    }
    
    /**
     * Add language query vars
     */
    public function add_language_query_vars($vars) {
        $vars[] = 'lang';
        return $vars;
    }
    
    /**
     * Handle language redirect
     */
    public function handle_language_redirect() {
        $lang = get_query_var('lang');
        
        if ($lang && isset($this->languages[$lang])) {
            $this->current_language = $lang;
            $this->setup_language();
        }
    }
    
    /**
     * Public methods
     */
    
    /**
     * Get current language
     */
    public function get_current_language() {
        return $this->current_language;
    }
    
    /**
     * Get available languages
     */
    public function get_languages() {
        return $this->languages;
    }
    
    /**
     * Get language by code
     */
    public function get_language($code) {
        return $this->languages[$code] ?? null;
    }
    
    /**
     * Is RTL language
     */
    public function is_rtl() {
        return $this->languages[$this->current_language]['rtl'] ?? false;
    }
    
    /**
     * Translate string
     */
    public function translate($text, $language = null) {
        $language = $language ?: $this->current_language;
        $translations = $this->get_custom_translations();
        
        return $translations[$language][$text] ?? $text;
    }
    
    /**
     * Add translation
     */
    public function add_translation($text, $translation, $language = null) {
        $language = $language ?: $this->current_language;
        $translations = $this->get_custom_translations();
        
        if (!isset($translations[$language])) {
            $translations[$language] = [];
        }
        
        $translations[$language][$text] = $translation;
        update_option('aqualuxe_custom_translations', $translations);
    }
    
    /**
     * Get localized URL
     */
    public function get_localized_url($url, $language = null) {
        $language = $language ?: $this->current_language;
        return $this->get_language_url($language);
    }
    
    /**
     * Format number for current locale
     */
    public function format_number($number, $decimals = 0) {
        $locale_info = [
            'en' => ['decimal' => '.', 'thousands' => ','],
            'es' => ['decimal' => ',', 'thousands' => '.'],
            'fr' => ['decimal' => ',', 'thousands' => ' '],
            'de' => ['decimal' => ',', 'thousands' => '.'],
            'it' => ['decimal' => ',', 'thousands' => '.'],
            'pt' => ['decimal' => ',', 'thousands' => '.'],
            'ar' => ['decimal' => '.', 'thousands' => ','],
            'zh' => ['decimal' => '.', 'thousands' => ','],
            'ja' => ['decimal' => '.', 'thousands' => ','],
            'ko' => ['decimal' => '.', 'thousands' => ','],
        ];
        
        $locale = $locale_info[$this->current_language] ?? $locale_info['en'];
        
        return number_format($number, $decimals, $locale['decimal'], $locale['thousands']);
    }
    
    /**
     * Format date for current locale
     */
    public function format_date($date, $format = null) {
        if (!$format) {
            $format_map = [
                'en' => 'F j, Y',
                'es' => 'j \d\e F \d\e Y',
                'fr' => 'j F Y',
                'de' => 'j. F Y',
                'it' => 'j F Y',
                'pt' => 'j \d\e F \d\e Y',
                'ar' => 'j F Y',
                'zh' => 'Y年n月j日',
                'ja' => 'Y年n月j日',
                'ko' => 'Y년 n월 j일',
            ];
            
            $format = $format_map[$this->current_language] ?? $format_map['en'];
        }
        
        return date_i18n($format, strtotime($date));
    }
}
