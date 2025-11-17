<?php
/**
 * Multilingual Module
 *
 * Provides multilingual functionality for the theme
 *
 * @package AquaLuxe\Modules\Multilingual
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class AquaLuxe_Multilingual_Module
 *
 * Handles multilingual support
 */
class AquaLuxe_Multilingual_Module {
    
    /**
     * Single instance of the class
     *
     * @var AquaLuxe_Multilingual_Module
     */
    private static $instance = null;

    /**
     * Supported languages
     *
     * @var array
     */
    private $supported_languages = array();

    /**
     * Current language
     *
     * @var string
     */
    private $current_language = 'en';

    /**
     * Get instance
     *
     * @return AquaLuxe_Multilingual_Module
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    private function __construct() {
        $this->init_supported_languages();
        $this->init_hooks();
        $this->detect_current_language();
    }

    /**
     * Initialize supported languages
     */
    private function init_supported_languages() {
        $this->supported_languages = array(
            'en' => array(
                'name' => 'English',
                'native_name' => 'English',
                'flag' => '🇺🇸',
                'locale' => 'en_US',
                'direction' => 'ltr',
            ),
            'es' => array(
                'name' => 'Spanish',
                'native_name' => 'Español',
                'flag' => '🇪🇸',
                'locale' => 'es_ES',
                'direction' => 'ltr',
            ),
            'fr' => array(
                'name' => 'French',
                'native_name' => 'Français',
                'flag' => '🇫🇷',
                'locale' => 'fr_FR',
                'direction' => 'ltr',
            ),
            'de' => array(
                'name' => 'German',
                'native_name' => 'Deutsch',
                'flag' => '🇩🇪',
                'locale' => 'de_DE',
                'direction' => 'ltr',
            ),
            'zh' => array(
                'name' => 'Chinese',
                'native_name' => '中文',
                'flag' => '🇨🇳',
                'locale' => 'zh_CN',
                'direction' => 'ltr',
            ),
            'ja' => array(
                'name' => 'Japanese',
                'native_name' => '日本語',
                'flag' => '🇯🇵',
                'locale' => 'ja',
                'direction' => 'ltr',
            ),
            'ar' => array(
                'name' => 'Arabic',
                'native_name' => 'العربية',
                'flag' => '🇸🇦',
                'locale' => 'ar',
                'direction' => 'rtl',
            ),
        );

        // Allow filtering of supported languages
        $this->supported_languages = apply_filters('aqualuxe_supported_languages', $this->supported_languages);
    }

    /**
     * Initialize hooks
     */
    private function init_hooks() {
        add_action('init', array($this, 'load_text_domain'));
        add_action('wp_head', array($this, 'add_language_meta'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('wp_ajax_aqualuxe_switch_language', array($this, 'handle_language_switch'));
        add_action('wp_ajax_nopriv_aqualuxe_switch_language', array($this, 'handle_language_switch'));
        add_filter('body_class', array($this, 'add_language_body_class'));
        add_action('wp_footer', array($this, 'render_language_switcher'));
        
        // Admin hooks
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('customize_register', array($this, 'customize_register'));
    }

    /**
     * Load text domain
     */
    public function load_text_domain() {
        $locale = $this->get_current_locale();
        
        // Load WordPress core translations
        load_default_textdomain($locale);
        
        // Load theme translations
        load_theme_textdomain('aqualuxe', get_template_directory() . '/languages');
        
        // Switch locale if needed
        if ($locale !== get_locale()) {
            switch_to_locale($locale);
        }
    }

    /**
     * Detect current language
     */
    private function detect_current_language() {
        // Check URL parameter
        if (isset($_GET['lang']) && array_key_exists($_GET['lang'], $this->supported_languages)) {
            $this->current_language = sanitize_text_field($_GET['lang']);
            $this->save_language_preference($this->current_language);
            return;
        }

        // Check cookie
        if (isset($_COOKIE['aqualuxe_language']) && array_key_exists($_COOKIE['aqualuxe_language'], $this->supported_languages)) {
            $this->current_language = $_COOKIE['aqualuxe_language'];
            return;
        }

        // Auto-detect from browser
        if (function_exists('locale_accept_from_http') && isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            $browser_language = locale_accept_from_http($_SERVER['HTTP_ACCEPT_LANGUAGE']);
            $lang_code = substr($browser_language, 0, 2);
            
            if (array_key_exists($lang_code, $this->supported_languages)) {
                $this->current_language = $lang_code;
                return;
            }
        }

        // Default to site locale
        $site_locale = get_locale();
        $site_lang = substr($site_locale, 0, 2);
        
        if (array_key_exists($site_lang, $this->supported_languages)) {
            $this->current_language = $site_lang;
        }
    }

    /**
     * Save language preference
     *
     * @param string $language Language code
     */
    private function save_language_preference($language) {
        setcookie('aqualuxe_language', $language, time() + (365 * DAY_IN_SECONDS), '/');
    }

    /**
     * Get current language
     *
     * @return string
     */
    public function get_current_language() {
        return $this->current_language;
    }

    /**
     * Get current locale
     *
     * @return string
     */
    public function get_current_locale() {
        $language = $this->get_current_language();
        return isset($this->supported_languages[$language]['locale']) 
            ? $this->supported_languages[$language]['locale'] 
            : get_locale();
    }

    /**
     * Get supported languages
     *
     * @return array
     */
    public function get_supported_languages() {
        return $this->supported_languages;
    }

    /**
     * Add language meta tags
     */
    public function add_language_meta() {
        $language = $this->get_current_language();
        $direction = $this->supported_languages[$language]['direction'] ?? 'ltr';
        
        echo '<html lang="' . esc_attr($language) . '" dir="' . esc_attr($direction) . '">' . "\n";
        echo '<meta name="language" content="' . esc_attr($language) . '">' . "\n";
        
        // Add alternate language links
        foreach ($this->supported_languages as $lang_code => $lang_data) {
            if ($lang_code !== $language) {
                $alternate_url = add_query_arg('lang', $lang_code, get_permalink());
                echo '<link rel="alternate" hreflang="' . esc_attr($lang_code) . '" href="' . esc_url($alternate_url) . '">' . "\n";
            }
        }
    }

    /**
     * Enqueue scripts and styles
     */
    public function enqueue_scripts() {
        // Enqueue language switcher styles
        wp_enqueue_style(
            'aqualuxe-language-switcher',
            AQUALUXE_ASSETS_URI . '/css/components/language-switcher.css',
            array(),
            AQUALUXE_VERSION
        );
        
        wp_enqueue_script(
            'aqualuxe-multilingual',
            AQUALUXE_ASSETS_URI . '/js/modules/multilingual.js',
            array('jquery'),
            AQUALUXE_VERSION,
            true
        );

        wp_localize_script('aqualuxe-multilingual', 'aqualuxeMultilingual', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('aqualuxe_multilingual'),
            'current_language' => $this->get_current_language(),
            'languages' => $this->supported_languages,
        ));
    }

    /**
     * Handle language switch AJAX
     */
    public function handle_language_switch() {
        if (!wp_verify_nonce($_POST['nonce'], 'aqualuxe_multilingual')) {
            wp_die('Security check failed');
        }

        $language = sanitize_text_field($_POST['language']);
        
        if (!array_key_exists($language, $this->supported_languages)) {
            wp_send_json_error('Invalid language');
        }

        $this->current_language = $language;
        $this->save_language_preference($language);

        wp_send_json_success(array(
            'language' => $language,
            'redirect_url' => add_query_arg('lang', $language, wp_get_referer()),
        ));
    }

    /**
     * Add language body class
     *
     * @param array $classes Body classes
     * @return array
     */
    public function add_language_body_class($classes) {
        $language = $this->get_current_language();
        $direction = $this->supported_languages[$language]['direction'] ?? 'ltr';
        
        $classes[] = 'lang-' . $language;
        $classes[] = 'dir-' . $direction;
        
        return $classes;
    }

    /**
     * Render language switcher
     */
    public function render_language_switcher() {
        if (!get_theme_mod('aqualuxe_show_language_switcher', true)) {
            return;
        }

        $current_language = $this->get_current_language();
        ?>
        <div class="aqualuxe-language-switcher" id="aqualuxe-language-switcher">
            <button class="language-toggle" aria-label="<?php esc_attr_e('Switch Language', 'aqualuxe'); ?>">
                <span class="current-language">
                    <?php echo esc_html($this->supported_languages[$current_language]['flag']); ?>
                    <span class="language-code"><?php echo esc_html(strtoupper($current_language)); ?></span>
                </span>
                <svg class="dropdown-icon" width="12" height="12" viewBox="0 0 12 12" fill="currentColor">
                    <path d="M6 9L1 4h10L6 9z"/>
                </svg>
            </button>
            
            <div class="language-dropdown">
                <?php foreach ($this->supported_languages as $lang_code => $lang_data): ?>
                    <?php if ($lang_code !== $current_language): ?>
                        <a href="#" class="language-option" data-language="<?php echo esc_attr($lang_code); ?>">
                            <span class="flag"><?php echo esc_html($lang_data['flag']); ?></span>
                            <span class="name"><?php echo esc_html($lang_data['native_name']); ?></span>
                        </a>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
    }

    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        add_theme_page(
            __('Multilingual Settings', 'aqualuxe'),
            __('Languages', 'aqualuxe'),
            'manage_options',
            'aqualuxe-multilingual',
            array($this, 'render_admin_page')
        );
    }

    /**
     * Render admin page
     */
    public function render_admin_page() {
        ?>
        <div class="wrap">
            <h1><?php esc_html_e('Multilingual Settings', 'aqualuxe'); ?></h1>
            
            <div class="card">
                <h2><?php esc_html_e('Supported Languages', 'aqualuxe'); ?></h2>
                <table class="wp-list-table widefat fixed striped">
                    <thead>
                        <tr>
                            <th><?php esc_html_e('Flag', 'aqualuxe'); ?></th>
                            <th><?php esc_html_e('Language', 'aqualuxe'); ?></th>
                            <th><?php esc_html_e('Native Name', 'aqualuxe'); ?></th>
                            <th><?php esc_html_e('Locale', 'aqualuxe'); ?></th>
                            <th><?php esc_html_e('Direction', 'aqualuxe'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($this->supported_languages as $code => $language): ?>
                            <tr>
                                <td><?php echo esc_html($language['flag']); ?></td>
                                <td><?php echo esc_html($language['name']); ?></td>
                                <td><?php echo esc_html($language['native_name']); ?></td>
                                <td><?php echo esc_html($language['locale']); ?></td>
                                <td><?php echo esc_html(strtoupper($language['direction'])); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <div class="card">
                <h2><?php esc_html_e('Current Settings', 'aqualuxe'); ?></h2>
                <p>
                    <strong><?php esc_html_e('Current Language:', 'aqualuxe'); ?></strong>
                    <?php
                    $current = $this->get_current_language();
                    echo esc_html($this->supported_languages[$current]['name']);
                    ?>
                </p>
                <p>
                    <strong><?php esc_html_e('Current Locale:', 'aqualuxe'); ?></strong>
                    <?php echo esc_html($this->get_current_locale()); ?>
                </p>
            </div>
        </div>
        <?php
    }

    /**
     * Register customizer settings
     *
     * @param WP_Customize_Manager $wp_customize Customizer manager
     */
    public function customize_register($wp_customize) {
        // Add multilingual section
        $wp_customize->add_section('aqualuxe_multilingual', array(
            'title' => __('Multilingual', 'aqualuxe'),
            'priority' => 120,
        ));

        // Show language switcher setting
        $wp_customize->add_setting('aqualuxe_show_language_switcher', array(
            'default' => true,
            'sanitize_callback' => 'wp_validate_boolean',
        ));

        $wp_customize->add_control('aqualuxe_show_language_switcher', array(
            'label' => __('Show Language Switcher', 'aqualuxe'),
            'description' => __('Display the language switcher on the frontend', 'aqualuxe'),
            'section' => 'aqualuxe_multilingual',
            'type' => 'checkbox',
        ));
    }
}

// Initialize the multilingual module
AquaLuxe_Multilingual_Module::get_instance();