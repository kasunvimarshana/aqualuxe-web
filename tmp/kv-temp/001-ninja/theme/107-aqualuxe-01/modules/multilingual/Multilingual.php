<?php
/**
 * Multilingual Module
 *
 * Provides multilingual support and language switching functionality
 *
 * @package AquaLuxe\Modules\Multilingual
 * @version 1.0.0
 */

namespace AquaLuxe\Modules\Multilingual;

use AquaLuxe\Core\Abstracts\AbstractModule;

/**
 * Multilingual Module Class
 */
class Multilingual extends AbstractModule {
    
    /**
     * Module version
     *
     * @var string
     */
    protected $version = '1.0.0';
    
    /**
     * Supported languages
     *
     * @var array
     */
    private $languages = [
        'en' => ['name' => 'English', 'flag' => '🇺🇸'],
        'es' => ['name' => 'Español', 'flag' => '🇪🇸'],
        'fr' => ['name' => 'Français', 'flag' => '🇫🇷'],
        'de' => ['name' => 'Deutsch', 'flag' => '🇩🇪'],
        'it' => ['name' => 'Italiano', 'flag' => '🇮🇹'],
        'pt' => ['name' => 'Português', 'flag' => '🇵🇹'],
        'ja' => ['name' => '日本語', 'flag' => '🇯🇵'],
        'zh' => ['name' => '中文', 'flag' => '🇨🇳'],
    ];
    
    /**
     * Initialize the module
     *
     * @return void
     */
    public function init(): void {
        if (!$this->isEnabled()) {
            return;
        }
        
        $this->register_hooks();
        $this->on_activate();
    }
    
    /**
     * Register module hooks
     *
     * @return void
     */
    protected function register_hooks(): void {
        // Load text domain
        add_action('after_setup_theme', [$this, 'load_textdomain']);
        
        // Add language switcher to header
        add_action('wp_head', [$this, 'add_language_meta']);
        
        // Language switcher widget
        add_action('widgets_init', [$this, 'register_language_widget']);
        
        // Add customizer settings
        add_action('customize_register', [$this, 'add_customizer_settings']);
        
        // AJAX language switching
        add_action('wp_ajax_switch_language', [$this, 'ajax_switch_language']);
        add_action('wp_ajax_nopriv_switch_language', [$this, 'ajax_switch_language']);
        
        // Add language switcher to header
        add_action('aqualuxe_header_actions', [$this, 'render_language_switcher']);
        
        // Add RTL support
        add_action('wp_head', [$this, 'add_rtl_support']);
        
        // Enqueue language-specific assets
        add_action('wp_enqueue_scripts', [$this, 'enqueue_language_assets']);
    }
    
    /**
     * Load theme text domain
     *
     * @return void
     */
    public function load_textdomain(): void {
        load_theme_textdomain('aqualuxe', AQUALUXE_PATH . '/languages');
        
        // Load plugin integration text domains
        $this->load_plugin_textdomains();
    }
    
    /**
     * Load plugin text domains for better integration
     *
     * @return void
     */
    private function load_plugin_textdomains(): void {
        // WooCommerce
        if (aqualuxe_is_woocommerce_active()) {
            load_plugin_textdomain('woocommerce');
        }
        
        // Common multilingual plugins
        if (class_exists('Polylang')) {
            load_plugin_textdomain('polylang');
        }
        
        if (defined('ICL_SITEPRESS_VERSION')) {
            load_plugin_textdomain('sitepress');
        }
    }
    
    /**
     * Add language meta tags
     *
     * @return void
     */
    public function add_language_meta(): void {
        $current_language = $this->get_current_language();
        
        echo '<meta name="language" content="' . esc_attr($current_language) . '">' . "\n";
        echo '<link rel="alternate" hreflang="x-default" href="' . esc_url(home_url('/')) . '">' . "\n";
        
        // Add alternate language links
        foreach ($this->get_available_languages() as $lang_code => $language) {
            $lang_url = $this->get_language_url($lang_code);
            echo '<link rel="alternate" hreflang="' . esc_attr($lang_code) . '" href="' . esc_url($lang_url) . '">' . "\n";
        }
    }
    
    /**
     * Register language switcher widget
     *
     * @return void
     */
    public function register_language_widget(): void {
        register_widget('AquaLuxe\\Modules\\Multilingual\\LanguageSwitcherWidget');
    }
    
    /**
     * Add customizer settings
     *
     * @param \WP_Customize_Manager $wp_customize
     * @return void
     */
    public function add_customizer_settings($wp_customize): void {
        // Multilingual Section
        $wp_customize->add_section('aqualuxe_multilingual', [
            'title' => __('Multilingual Settings', 'aqualuxe'),
            'panel' => 'aqualuxe_theme_options',
            'priority' => 25,
        ]);
        
        // Default Language
        $wp_customize->add_setting('multilingual_default_language', [
            'default' => 'en',
            'sanitize_callback' => [$this, 'sanitize_language_code'],
        ]);
        
        $wp_customize->add_control('multilingual_default_language', [
            'label' => __('Default Language', 'aqualuxe'),
            'section' => 'aqualuxe_multilingual',
            'type' => 'select',
            'choices' => $this->get_language_choices(),
        ]);
        
        // Available Languages
        $wp_customize->add_setting('multilingual_available_languages', [
            'default' => ['en', 'es', 'fr'],
            'sanitize_callback' => [$this, 'sanitize_language_array'],
        ]);
        
        $wp_customize->add_control(new \WP_Customize_Control($wp_customize, 'multilingual_available_languages', [
            'label' => __('Available Languages', 'aqualuxe'),
            'description' => __('Select which languages to make available on your site', 'aqualuxe'),
            'section' => 'aqualuxe_multilingual',
            'type' => 'checkbox',
            'choices' => $this->get_language_choices(),
        ]));
        
        // Language Switcher Position
        $wp_customize->add_setting('multilingual_switcher_position', [
            'default' => 'header',
            'sanitize_callback' => [$this, 'sanitize_switcher_position'],
        ]);
        
        $wp_customize->add_control('multilingual_switcher_position', [
            'label' => __('Language Switcher Position', 'aqualuxe'),
            'section' => 'aqualuxe_multilingual',
            'type' => 'select',
            'choices' => [
                'header' => __('Header', 'aqualuxe'),
                'footer' => __('Footer', 'aqualuxe'),
                'both' => __('Both Header and Footer', 'aqualuxe'),
                'widget' => __('Widget Only', 'aqualuxe'),
            ],
        ]);
        
        // Show Language Flags
        $wp_customize->add_setting('multilingual_show_flags', [
            'default' => true,
            'sanitize_callback' => 'wp_validate_boolean',
        ]);
        
        $wp_customize->add_control('multilingual_show_flags', [
            'label' => __('Show Language Flags', 'aqualuxe'),
            'section' => 'aqualuxe_multilingual',
            'type' => 'checkbox',
        ]);
    }
    
    /**
     * AJAX language switching
     *
     * @return void
     */
    public function ajax_switch_language(): void {
        if (!wp_verify_nonce($_POST['nonce'] ?? '', 'aqualuxe_multilingual_nonce')) {
            wp_die(__('Security check failed', 'aqualuxe'));
        }
        
        $language = sanitize_text_field($_POST['language'] ?? '');
        
        if (!$this->is_language_available($language)) {
            wp_send_json_error(__('Invalid language', 'aqualuxe'));
        }
        
        // Set language preference
        $this->set_user_language($language);
        
        // Get redirect URL
        $redirect_url = $this->get_language_url($language);
        
        wp_send_json_success([
            'language' => $language,
            'redirect_url' => $redirect_url,
            'message' => sprintf(__('Language switched to %s', 'aqualuxe'), $this->languages[$language]['name']),
        ]);
    }
    
    /**
     * Render language switcher
     *
     * @return void
     */
    public function render_language_switcher(): void {
        $position = get_theme_mod('multilingual_switcher_position', 'header');
        
        if ($position === 'header' || $position === 'both') {
            $this->display_language_switcher();
        }
    }
    
    /**
     * Display language switcher HTML
     *
     * @return void
     */
    public function display_language_switcher(): void {
        $available_languages = $this->get_available_languages();
        $current_language = $this->get_current_language();
        $show_flags = get_theme_mod('multilingual_show_flags', true);
        
        if (count($available_languages) <= 1) {
            return;
        }
        
        echo '<div class="language-switcher relative">';
        echo '<button class="language-trigger flex items-center space-x-2 text-sm text-gray-600 hover:text-primary-600 dark:text-gray-300 dark:hover:text-primary-400" data-toggle="language-dropdown">';
        
        if ($show_flags && isset($this->languages[$current_language]['flag'])) {
            echo '<span class="language-flag">' . $this->languages[$current_language]['flag'] . '</span>';
        }
        
        echo '<span class="language-name">' . esc_html($this->languages[$current_language]['name']) . '</span>';
        echo '<svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>';
        echo '</button>';
        
        echo '<div class="language-dropdown absolute top-full right-0 mt-2 bg-white dark:bg-gray-800 rounded-lg shadow-lg py-2 min-w-48 hidden z-50">';
        
        foreach ($available_languages as $lang_code => $language) {
            if ($lang_code === $current_language) {
                continue;
            }
            
            echo '<a href="' . esc_url($this->get_language_url($lang_code)) . '" class="language-option flex items-center space-x-3 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700" data-language="' . esc_attr($lang_code) . '">';
            
            if ($show_flags && isset($language['flag'])) {
                echo '<span class="language-flag">' . $language['flag'] . '</span>';
            }
            
            echo '<span class="language-name">' . esc_html($language['name']) . '</span>';
            echo '</a>';
        }
        
        echo '</div>';
        echo '</div>';
    }
    
    /**
     * Add RTL support
     *
     * @return void
     */
    public function add_rtl_support(): void {
        $current_language = $this->get_current_language();
        $rtl_languages = ['ar', 'he', 'fa', 'ur'];
        
        if (in_array($current_language, $rtl_languages)) {
            echo '<style>body { direction: rtl; }</style>' . "\n";
        }
    }
    
    /**
     * Enqueue language-specific assets
     *
     * @return void
     */
    public function enqueue_language_assets(): void {
        $current_language = $this->get_current_language();
        
        // Enqueue language-specific CSS if it exists
        $lang_css_path = AQUALUXE_PATH . "/assets/dist/css/lang-{$current_language}.css";
        if (file_exists($lang_css_path)) {
            wp_enqueue_style(
                "aqualuxe-lang-{$current_language}",
                aqualuxe_asset("/css/lang-{$current_language}.css"),
                ['aqualuxe-style'],
                AQUALUXE_VERSION
            );
        }
        
        // Enqueue multilingual JavaScript
        wp_enqueue_script(
            'aqualuxe-multilingual',
            aqualuxe_asset('/js/multilingual.js'),
            ['aqualuxe-script'],
            AQUALUXE_VERSION,
            true
        );
        
        wp_localize_script('aqualuxe-multilingual', 'aqualuxeMultilingual', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('aqualuxe_multilingual_nonce'),
            'current_language' => $current_language,
            'available_languages' => $this->get_available_languages(),
            'strings' => [
                'switching_language' => __('Switching language...', 'aqualuxe'),
                'language_switched' => __('Language switched successfully', 'aqualuxe'),
                'error' => __('Error switching language', 'aqualuxe'),
            ],
        ]);
    }
    
    /**
     * Get current language
     *
     * @return string
     */
    private function get_current_language(): string {
        // Check for plugin integration first
        if (function_exists('pll_current_language')) {
            return pll_current_language();
        }
        
        if (defined('ICL_LANGUAGE_CODE')) {
            return ICL_LANGUAGE_CODE;
        }
        
        // Fallback to user preference or default
        $user_language = $this->get_user_language();
        if ($user_language && $this->is_language_available($user_language)) {
            return $user_language;
        }
        
        return get_theme_mod('multilingual_default_language', 'en');
    }
    
    /**
     * Get available languages
     *
     * @return array
     */
    private function get_available_languages(): array {
        $available = get_theme_mod('multilingual_available_languages', ['en', 'es', 'fr']);
        $languages = [];
        
        foreach ($available as $lang_code) {
            if (isset($this->languages[$lang_code])) {
                $languages[$lang_code] = $this->languages[$lang_code];
            }
        }
        
        return $languages;
    }
    
    /**
     * Get language URL
     *
     * @param string $language
     * @return string
     */
    private function get_language_url($language): string {
        // Plugin integration
        if (function_exists('pll_home_url')) {
            return pll_home_url($language);
        }
        
        if (function_exists('icl_get_home_url')) {
            return icl_get_home_url();
        }
        
        // Fallback to query parameter
        return add_query_arg('lang', $language, home_url('/'));
    }
    
    /**
     * Check if language is available
     *
     * @param string $language
     * @return bool
     */
    private function is_language_available($language): bool {
        $available = $this->get_available_languages();
        return isset($available[$language]);
    }
    
    /**
     * Get user language preference
     *
     * @return string|null
     */
    private function get_user_language(): ?string {
        // Check cookie
        if (isset($_COOKIE['aqualuxe_language'])) {
            return sanitize_text_field($_COOKIE['aqualuxe_language']);
        }
        
        // Check user meta for logged-in users
        if (is_user_logged_in()) {
            return get_user_meta(get_current_user_id(), 'aqualuxe_language', true);
        }
        
        // Check browser language
        return $this->detect_browser_language();
    }
    
    /**
     * Set user language preference
     *
     * @param string $language
     * @return void
     */
    private function set_user_language($language): void {
        // Set cookie
        setcookie(
            'aqualuxe_language',
            $language,
            time() + (365 * 24 * 60 * 60), // 1 year
            COOKIEPATH,
            COOKIE_DOMAIN,
            is_ssl(),
            true
        );
        
        // Set user meta for logged-in users
        if (is_user_logged_in()) {
            update_user_meta(get_current_user_id(), 'aqualuxe_language', $language);
        }
    }
    
    /**
     * Detect browser language
     *
     * @return string|null
     */
    private function detect_browser_language(): ?string {
        if (!isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            return null;
        }
        
        $languages = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
        
        foreach ($languages as $lang) {
            $lang = strtok(trim($lang), ';');
            $lang = substr($lang, 0, 2); // Get language code only
            
            if ($this->is_language_available($lang)) {
                return $lang;
            }
        }
        
        return null;
    }
    
    /**
     * Get language choices for customizer
     *
     * @return array
     */
    private function get_language_choices(): array {
        $choices = [];
        
        foreach ($this->languages as $code => $language) {
            $choices[$code] = $language['name'];
        }
        
        return $choices;
    }
    
    /**
     * Sanitize language code
     *
     * @param string $input
     * @return string
     */
    public function sanitize_language_code($input): string {
        return isset($this->languages[$input]) ? $input : 'en';
    }
    
    /**
     * Sanitize language array
     *
     * @param array $input
     * @return array
     */
    public function sanitize_language_array($input): array {
        if (!is_array($input)) {
            return ['en'];
        }
        
        $sanitized = [];
        foreach ($input as $lang) {
            if (isset($this->languages[$lang])) {
                $sanitized[] = $lang;
            }
        }
        
        return empty($sanitized) ? ['en'] : $sanitized;
    }
    
    /**
     * Sanitize switcher position
     *
     * @param string $input
     * @return string
     */
    public function sanitize_switcher_position($input): string {
        $valid_positions = ['header', 'footer', 'both', 'widget'];
        return in_array($input, $valid_positions) ? $input : 'header';
    }
    
    /**
     * Module activation
     *
     * @return void
     */
    protected function on_activate(): void {
        // Set default options
        add_option('multilingual_default_language', 'en');
        add_option('multilingual_available_languages', ['en', 'es', 'fr']);
        add_option('multilingual_switcher_position', 'header');
        add_option('multilingual_show_flags', true);
    }
}