<?php
/**
 * Multilingual Support Module
 * 
 * Provides comprehensive multilingual functionality with language switching,
 * content translation support, and integration with popular translation plugins
 * 
 * @package AquaLuxe
 * @since 1.0.0
 */

namespace AquaLuxe\Modules;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Multilingual Support
 */
class Multilingual {
    
    /**
     * Supported languages
     */
    private $supported_languages = [
        'en_US' => [
            'name' => 'English',
            'native_name' => 'English',
            'flag' => '🇺🇸',
            'direction' => 'ltr',
            'locale' => 'en_US'
        ],
        'es_ES' => [
            'name' => 'Spanish',
            'native_name' => 'Español',
            'flag' => '🇪🇸',
            'direction' => 'ltr',
            'locale' => 'es_ES'
        ],
        'fr_FR' => [
            'name' => 'French',
            'native_name' => 'Français',
            'flag' => '🇫🇷',
            'direction' => 'ltr',
            'locale' => 'fr_FR'
        ],
        'de_DE' => [
            'name' => 'German',
            'native_name' => 'Deutsch',
            'flag' => '🇩🇪',
            'direction' => 'ltr',
            'locale' => 'de_DE'
        ],
        'ar' => [
            'name' => 'Arabic',
            'native_name' => 'العربية',
            'flag' => '🇸🇦',
            'direction' => 'rtl',
            'locale' => 'ar'
        ],
    ];
    
    /**
     * Current language
     */
    private $current_language;
    
    /**
     * Constructor
     */
    public function __construct() {
        $this->current_language = $this->get_current_language();
        $this->init_hooks();
    }
    
    /**
     * Initialize hooks
     */
    private function init_hooks() {
        add_action('init', [$this, 'init_multilingual']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);
        add_action('customize_register', [$this, 'add_customizer_controls']);
        
        // Language switcher
        add_action('wp_head', [$this, 'add_language_meta']);
        add_filter('wp_nav_menu_items', [$this, 'add_language_switcher_to_menu'], 10, 2);
        add_shortcode('aqualuxe_language_switcher', [$this, 'render_language_switcher_shortcode']);
        
        // AJAX handlers
        add_action('wp_ajax_aqualuxe_switch_language', [$this, 'handle_switch_language']);
        add_action('wp_ajax_nopriv_aqualuxe_switch_language', [$this, 'handle_switch_language']);
        
        // Content filters
        add_filter('locale', [$this, 'override_locale']);
        
        // Admin functionality
        if (is_admin()) {
            add_action('admin_menu', [$this, 'add_admin_menu']);
        }
    }
    
    /**
     * Initialize multilingual functionality
     */
    public function init_multilingual() {
        // Load text domain for current language
        $this->load_theme_textdomain();
        
        // Handle RTL languages
        if ($this->is_rtl_language()) {
            add_action('wp_head', [$this, 'add_rtl_styles']);
        }
    }
    
    /**
     * Enqueue scripts
     */
    public function enqueue_scripts() {
        // Add multilingual data to main script
        wp_localize_script('aqualuxe-main', 'aqualuxe_multilingual', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('aqualuxe_multilingual_nonce'),
            'current_language' => $this->current_language,
            'languages' => $this->get_enabled_languages(),
        ]);
    }
    
    /**
     * Add language meta tags
     */
    public function add_language_meta() {
        $current_lang = $this->current_language;
        $language_info = $this->supported_languages[$current_lang] ?? $this->supported_languages['en_US'];
        
        // Hreflang tags for SEO
        $enabled_languages = $this->get_enabled_languages();
        foreach ($enabled_languages as $lang_code => $lang_data) {
            $url = $this->get_language_url($lang_code);
            echo '<link rel="alternate" hreflang="' . esc_attr($lang_code) . '" href="' . esc_url($url) . '">' . "\n";
        }
    }
    
    /**
     * Add language switcher to navigation menu
     */
    public function add_language_switcher_to_menu($items, $args) {
        // Only add to primary menu if enabled
        if ($args->theme_location !== 'primary' || !get_theme_mod('aqualuxe_show_language_switcher_in_menu', true)) {
            return $items;
        }
        
        $language_switcher = $this->render_language_switcher([
            'style' => 'dropdown',
            'show_flags' => true,
            'show_names' => false,
        ]);
        
        // Add switcher as last menu item
        $items .= '<li class="menu-item menu-item-language-switcher">' . $language_switcher . '</li>';
        
        return $items;
    }
    
    /**
     * Render language switcher
     */
    public function render_language_switcher($args = []) {
        $defaults = [
            'style' => 'dropdown',
            'show_flags' => true,
            'show_names' => true,
            'class' => 'aqualuxe-language-switcher',
        ];
        
        $args = wp_parse_args($args, $defaults);
        $enabled_languages = $this->get_enabled_languages();
        $current_lang = $this->current_language;
        
        if (count($enabled_languages) <= 1) {
            return '';
        }
        
        ob_start();
        
        $current_lang_info = $enabled_languages[$current_lang] ?? $enabled_languages['en_US'];
        ?>
        <div class="<?php echo esc_attr($args['class']); ?> relative">
            <button 
                type="button" 
                class="language-switcher-toggle flex items-center space-x-2 px-3 py-2 text-sm bg-white dark:bg-neutral-800 border border-neutral-300 dark:border-neutral-600 rounded-md hover:bg-neutral-50 dark:hover:bg-neutral-700 focus:outline-none focus:ring-2 focus:ring-primary-500 transition-colors"
                aria-label="<?php esc_attr_e('Switch Language', 'aqualuxe'); ?>"
            >
                <?php if ($args['show_flags']) : ?>
                    <span class="language-flag text-lg"><?php echo esc_html($current_lang_info['flag']); ?></span>
                <?php endif; ?>
                
                <?php if ($args['show_names']) : ?>
                    <span class="language-name">
                        <?php echo esc_html($current_lang_info['name']); ?>
                    </span>
                <?php endif; ?>
            </button>
        </div>
        <?php
        
        return ob_get_clean();
    }
    
    /**
     * Language switcher shortcode
     */
    public function render_language_switcher_shortcode($atts) {
        $atts = shortcode_atts([
            'style' => 'dropdown',
            'show_flags' => 'true',
            'show_names' => 'true',
            'class' => 'aqualuxe-language-switcher',
        ], $atts, 'aqualuxe_language_switcher');
        
        // Convert string booleans
        $atts['show_flags'] = filter_var($atts['show_flags'], FILTER_VALIDATE_BOOLEAN);
        $atts['show_names'] = filter_var($atts['show_names'], FILTER_VALIDATE_BOOLEAN);
        
        return $this->render_language_switcher($atts);
    }
    
    /**
     * Handle language switching AJAX
     */
    public function handle_switch_language() {
        // Verify nonce
        if (!wp_verify_nonce($_POST['nonce'] ?? '', 'aqualuxe_multilingual_nonce')) {
            wp_send_json_error(['message' => esc_html__('Security check failed', 'aqualuxe')]);
        }
        
        $language = sanitize_text_field($_POST['language'] ?? '');
        
        if (!isset($this->supported_languages[$language])) {
            wp_send_json_error(['message' => esc_html__('Invalid language', 'aqualuxe')]);
        }
        
        // Save user preference
        if (is_user_logged_in()) {
            update_user_meta(get_current_user_id(), 'aqualuxe_language_preference', $language);
        }
        
        // Set cookie for non-logged-in users
        setcookie('aqualuxe_language', $language, time() + (86400 * 30), '/'); // 30 days
        
        wp_send_json_success([
            'language' => $language,
            'message' => sprintf(
                /* translators: %s: language name */
                esc_html__('Language switched to %s', 'aqualuxe'),
                $this->supported_languages[$language]['name']
            )
        ]);
    }
    
    /**
     * Get current language
     */
    private function get_current_language() {
        // Check URL parameter
        if (isset($_GET['lang']) && isset($this->supported_languages[$_GET['lang']])) {
            return sanitize_text_field($_GET['lang']);
        }
        
        // Check user preference (if logged in)
        if (is_user_logged_in()) {
            $user_preference = get_user_meta(get_current_user_id(), 'aqualuxe_language_preference', true);
            if ($user_preference && isset($this->supported_languages[$user_preference])) {
                return $user_preference;
            }
        }
        
        // Check cookie
        if (isset($_COOKIE['aqualuxe_language']) && isset($this->supported_languages[$_COOKIE['aqualuxe_language']])) {
            return sanitize_text_field($_COOKIE['aqualuxe_language']);
        }
        
        // Default to site language or English
        $site_language = get_locale();
        return isset($this->supported_languages[$site_language]) ? $site_language : 'en_US';
    }
    
    /**
     * Get enabled languages
     */
    private function get_enabled_languages() {
        $enabled = get_theme_mod('aqualuxe_enabled_languages', ['en_US']);
        $languages = [];
        
        foreach ($enabled as $lang_code) {
            if (isset($this->supported_languages[$lang_code])) {
                $languages[$lang_code] = $this->supported_languages[$lang_code];
            }
        }
        
        return $languages;
    }
    
    /**
     * Get language URL
     */
    private function get_language_url($language) {
        $current_url = home_url($_SERVER['REQUEST_URI'] ?? '');
        
        // Remove existing language parameter
        $current_url = remove_query_arg('lang', $current_url);
        
        // Add new language parameter
        return add_query_arg('lang', $language, $current_url);
    }
    
    /**
     * Check if current language is RTL
     */
    private function is_rtl_language() {
        $language_info = $this->supported_languages[$this->current_language] ?? null;
        return $language_info && $language_info['direction'] === 'rtl';
    }
    
    /**
     * Add RTL styles
     */
    public function add_rtl_styles() {
        echo '<link rel="stylesheet" href="' . esc_url(get_template_directory_uri() . '/assets/dist/css/rtl.css') . '" type="text/css" media="all">';
    }
    
    /**
     * Load theme text domain
     */
    private function load_theme_textdomain() {
        $locale = $this->supported_languages[$this->current_language]['locale'] ?? 'en_US';
        
        load_theme_textdomain('aqualuxe', get_template_directory() . '/languages');
    }
    
    /**
     * Override locale filter
     */
    public function override_locale($locale) {
        return $this->supported_languages[$this->current_language]['locale'] ?? $locale;
    }
    
    /**
     * Add customizer controls
     */
    public function add_customizer_controls($wp_customize) {
        // Add multilingual section
        $wp_customize->add_section('aqualuxe_multilingual', [
            'title' => esc_html__('Multilingual', 'aqualuxe'),
            'description' => esc_html__('Configure multilingual settings for your theme.', 'aqualuxe'),
            'priority' => 140,
        ]);
        
        // Enable multilingual
        $wp_customize->add_setting('aqualuxe_enable_multilingual', [
            'default' => false,
            'transport' => 'refresh',
            'sanitize_callback' => 'wp_validate_boolean',
        ]);
        
        $wp_customize->add_control('aqualuxe_enable_multilingual', [
            'label' => esc_html__('Enable Multilingual Support', 'aqualuxe'),
            'description' => esc_html__('Enable built-in multilingual functionality.', 'aqualuxe'),
            'section' => 'aqualuxe_multilingual',
            'type' => 'checkbox',
        ]);
        
        // Show language switcher in menu
        $wp_customize->add_setting('aqualuxe_show_language_switcher_in_menu', [
            'default' => true,
            'transport' => 'refresh',
            'sanitize_callback' => 'wp_validate_boolean',
        ]);
        
        $wp_customize->add_control('aqualuxe_show_language_switcher_in_menu', [
            'label' => esc_html__('Show Language Switcher in Menu', 'aqualuxe'),
            'section' => 'aqualuxe_multilingual',
            'type' => 'checkbox',
            'active_callback' => function() {
                return get_theme_mod('aqualuxe_enable_multilingual', false);
            },
        ]);
    }
    
    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        add_submenu_page(
            'themes.php',
            esc_html__('Multilingual Settings', 'aqualuxe'),
            esc_html__('Multilingual', 'aqualuxe'),
            'manage_options',
            'aqualuxe-multilingual',
            [$this, 'render_admin_page']
        );
    }
    
    /**
     * Render admin page
     */
    public function render_admin_page() {
        ?>
        <div class="wrap">
            <h1><?php esc_html_e('AquaLuxe Multilingual Settings', 'aqualuxe'); ?></h1>
            <p><?php esc_html_e('Configure multilingual settings for your theme.', 'aqualuxe'); ?></p>
            
            <div class="notice notice-info">
                <p><?php esc_html_e('For advanced multilingual functionality, consider using plugins like WPML, Polylang, or TranslatePress.', 'aqualuxe'); ?></p>
            </div>
        </div>
        <?php
    }
    
    /**
     * Get current language
     */
    public function get_language() {
        return $this->current_language;
    }
    
    /**
     * Get all supported languages
     */
    public function get_supported_languages() {
        return $this->supported_languages;
    }
    
    /**
     * Check if multilingual is enabled
     */
    public static function is_enabled() {
        return get_theme_mod('aqualuxe_enable_multilingual', false);
    }
}

// Initialize multilingual module if enabled
if (get_theme_mod('aqualuxe_enable_multilingual', false)) {
    new Multilingual();
}