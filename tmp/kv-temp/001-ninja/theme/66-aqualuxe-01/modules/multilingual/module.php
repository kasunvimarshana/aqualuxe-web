<?php
/**
 * Multilingual Module
 *
 * @package AquaLuxe\Modules\Multilingual
 */

namespace AquaLuxe\Modules\Multilingual;

/**
 * Multilingual Module Class
 */
class Module extends \AquaLuxe\Module {
    /**
     * Module ID
     *
     * @var string
     */
    protected $id = 'multilingual';

    /**
     * Module name
     *
     * @var string
     */
    protected $name = 'Multilingual';

    /**
     * Module description
     *
     * @var string
     */
    protected $description = 'Adds multilingual support to the theme with language switcher and RTL support.';

    /**
     * Module version
     *
     * @var string
     */
    protected $version = '1.0.0';

    /**
     * Module dependencies
     *
     * @var array
     */
    protected $dependencies = [];

    /**
     * Available languages
     *
     * @var array
     */
    private $languages = [];

    /**
     * Current language
     *
     * @var string
     */
    private $current_language = '';

    /**
     * Initialize module
     */
    public function init() {
        // Load module files
        $this->load_files();

        // Register hooks
        $this->register_hooks();

        // Set up available languages
        $this->setup_languages();

        // Set current language
        $this->set_current_language();

        // Register settings
        $this->register_module_settings();
    }

    /**
     * Load module files
     */
    private function load_files() {
        // Load classes
        require_once $this->path . 'classes/Language.php';
        require_once $this->path . 'classes/Switcher.php';
        require_once $this->path . 'classes/RTL.php';
    }

    /**
     * Register hooks
     */
    private function register_hooks() {
        // Add language switcher to header
        add_action('aqualuxe_header_extras', [$this, 'render_language_switcher']);

        // Add language switcher widget
        add_action('widgets_init', [$this, 'register_widgets']);

        // Add language switcher shortcode
        add_shortcode('aqualuxe_language_switcher', [$this, 'language_switcher_shortcode']);

        // Add language switcher to menu
        add_filter('wp_nav_menu_items', [$this, 'add_language_switcher_to_menu'], 10, 2);

        // Add RTL support
        add_filter('body_class', [$this, 'add_rtl_body_class']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_rtl_styles']);

        // Add language to AJAX requests
        add_filter('aqualuxe_localize_script', [$this, 'add_language_to_localize_script']);

        // Add language to URLs
        add_filter('home_url', [$this, 'add_language_to_url'], 10, 2);

        // Add language to admin bar
        add_action('admin_bar_menu', [$this, 'add_language_to_admin_bar'], 100);

        // Add language to customizer
        add_action('customize_register', [$this, 'add_language_to_customizer']);
    }

    /**
     * Set up available languages
     */
    private function setup_languages() {
        // Get languages from settings
        $languages = $this->get_setting('languages', [
            'en' => [
                'name' => 'English',
                'flag' => 'gb',
                'locale' => 'en_US',
                'rtl' => false,
            ],
            'es' => [
                'name' => 'Español',
                'flag' => 'es',
                'locale' => 'es_ES',
                'rtl' => false,
            ],
            'fr' => [
                'name' => 'Français',
                'flag' => 'fr',
                'locale' => 'fr_FR',
                'rtl' => false,
            ],
            'ar' => [
                'name' => 'العربية',
                'flag' => 'sa',
                'locale' => 'ar',
                'rtl' => true,
            ],
        ]);

        // Create language objects
        foreach ($languages as $code => $language) {
            $this->languages[$code] = new Language($code, $language);
        }
    }

    /**
     * Set current language
     */
    private function set_current_language() {
        // Get language from cookie or URL
        $language = isset($_GET['lang']) ? sanitize_key($_GET['lang']) : '';

        // If no language is set in URL, check cookie
        if (empty($language) && isset($_COOKIE['aqualuxe_language'])) {
            $language = sanitize_key($_COOKIE['aqualuxe_language']);
        }

        // If language is not valid, use default
        if (empty($language) || !isset($this->languages[$language])) {
            $language = $this->get_setting('default_language', 'en');
        }

        // Set current language
        $this->current_language = $language;

        // Set cookie
        if (!isset($_COOKIE['aqualuxe_language']) || $_COOKIE['aqualuxe_language'] !== $language) {
            setcookie('aqualuxe_language', $language, time() + YEAR_IN_SECONDS, '/');
        }

        // Set locale
        add_filter('locale', [$this, 'set_locale']);
    }

    /**
     * Set locale
     *
     * @param string $locale
     * @return string
     */
    public function set_locale($locale) {
        if (isset($this->languages[$this->current_language])) {
            return $this->languages[$this->current_language]->get_locale();
        }

        return $locale;
    }

    /**
     * Register module settings
     */
    private function register_module_settings() {
        $this->register_settings([
            [
                'option_name' => 'languages',
                'args' => [
                    'type' => 'array',
                    'default' => [
                        'en' => [
                            'name' => 'English',
                            'flag' => 'gb',
                            'locale' => 'en_US',
                            'rtl' => false,
                        ],
                        'es' => [
                            'name' => 'Español',
                            'flag' => 'es',
                            'locale' => 'es_ES',
                            'rtl' => false,
                        ],
                        'fr' => [
                            'name' => 'Français',
                            'flag' => 'fr',
                            'locale' => 'fr_FR',
                            'rtl' => false,
                        ],
                        'ar' => [
                            'name' => 'العربية',
                            'flag' => 'sa',
                            'locale' => 'ar',
                            'rtl' => true,
                        ],
                    ],
                    'sanitize_callback' => [$this, 'sanitize_languages'],
                ],
            ],
            [
                'option_name' => 'default_language',
                'args' => [
                    'type' => 'string',
                    'default' => 'en',
                    'sanitize_callback' => 'sanitize_key',
                ],
            ],
            [
                'option_name' => 'switcher_style',
                'args' => [
                    'type' => 'string',
                    'default' => 'dropdown',
                    'sanitize_callback' => 'sanitize_key',
                ],
            ],
            [
                'option_name' => 'show_flags',
                'args' => [
                    'type' => 'boolean',
                    'default' => true,
                    'sanitize_callback' => 'rest_sanitize_boolean',
                ],
            ],
            [
                'option_name' => 'show_names',
                'args' => [
                    'type' => 'boolean',
                    'default' => true,
                    'sanitize_callback' => 'rest_sanitize_boolean',
                ],
            ],
        ]);
    }

    /**
     * Sanitize languages
     *
     * @param array $languages
     * @return array
     */
    public function sanitize_languages($languages) {
        $sanitized = [];

        foreach ($languages as $code => $language) {
            $sanitized[sanitize_key($code)] = [
                'name' => sanitize_text_field($language['name']),
                'flag' => sanitize_key($language['flag']),
                'locale' => sanitize_text_field($language['locale']),
                'rtl' => (bool) $language['rtl'],
            ];
        }

        return $sanitized;
    }

    /**
     * Render language switcher
     */
    public function render_language_switcher() {
        $switcher = new Switcher($this->languages, $this->current_language);
        $style = $this->get_setting('switcher_style', 'dropdown');
        $show_flags = $this->get_setting('show_flags', true);
        $show_names = $this->get_setting('show_names', true);

        $switcher->render($style, $show_flags, $show_names);
    }

    /**
     * Register widgets
     */
    public function register_widgets() {
        register_widget('\AquaLuxe\Modules\Multilingual\Widget');
    }

    /**
     * Language switcher shortcode
     *
     * @param array $atts
     * @return string
     */
    public function language_switcher_shortcode($atts) {
        $atts = shortcode_atts([
            'style' => $this->get_setting('switcher_style', 'dropdown'),
            'show_flags' => $this->get_setting('show_flags', true),
            'show_names' => $this->get_setting('show_names', true),
        ], $atts);

        $switcher = new Switcher($this->languages, $this->current_language);
        
        ob_start();
        $switcher->render($atts['style'], $atts['show_flags'], $atts['show_names']);
        return ob_get_clean();
    }

    /**
     * Add language switcher to menu
     *
     * @param string $items
     * @param object $args
     * @return string
     */
    public function add_language_switcher_to_menu($items, $args) {
        // Only add to primary menu
        if ($args->theme_location !== 'primary') {
            return $items;
        }

        // Check if language switcher should be added to menu
        if (!$this->get_setting('add_to_menu', false)) {
            return $items;
        }

        $switcher = new Switcher($this->languages, $this->current_language);
        
        ob_start();
        $switcher->render('menu', true, true);
        $language_switcher = ob_get_clean();

        return $items . '<li class="menu-item menu-item-language-switcher">' . $language_switcher . '</li>';
    }

    /**
     * Add RTL body class
     *
     * @param array $classes
     * @return array
     */
    public function add_rtl_body_class($classes) {
        if (isset($this->languages[$this->current_language]) && $this->languages[$this->current_language]->is_rtl()) {
            $classes[] = 'rtl';
        }

        return $classes;
    }

    /**
     * Enqueue RTL styles
     */
    public function enqueue_rtl_styles() {
        if (isset($this->languages[$this->current_language]) && $this->languages[$this->current_language]->is_rtl()) {
            $this->enqueue_style('aqualuxe-rtl', 'assets/css/rtl.css');
        }
    }

    /**
     * Add language to localize script
     *
     * @param array $data
     * @return array
     */
    public function add_language_to_localize_script($data) {
        $data['language'] = $this->current_language;
        $data['languages'] = [];

        foreach ($this->languages as $code => $language) {
            $data['languages'][$code] = [
                'name' => $language->get_name(),
                'flag' => $language->get_flag(),
                'locale' => $language->get_locale(),
                'rtl' => $language->is_rtl(),
            ];
        }

        return $data;
    }

    /**
     * Add language to URL
     *
     * @param string $url
     * @param string $path
     * @return string
     */
    public function add_language_to_url($url, $path) {
        // Don't add language to admin URLs
        if (is_admin()) {
            return $url;
        }

        // Don't add language to AJAX URLs
        if (defined('DOING_AJAX') && DOING_AJAX) {
            return $url;
        }

        // Don't add language to REST API URLs
        if (defined('REST_REQUEST') && REST_REQUEST) {
            return $url;
        }

        // Don't add language if URL already has language
        if (strpos($url, '?lang=') !== false || strpos($url, '&lang=') !== false) {
            return $url;
        }

        // Add language to URL
        $separator = (strpos($url, '?') !== false) ? '&' : '?';
        return $url . $separator . 'lang=' . $this->current_language;
    }

    /**
     * Add language to admin bar
     *
     * @param \WP_Admin_Bar $wp_admin_bar
     */
    public function add_language_to_admin_bar($wp_admin_bar) {
        // Only add to admin bar if user is logged in
        if (!is_user_logged_in()) {
            return;
        }

        // Add language switcher to admin bar
        $wp_admin_bar->add_node([
            'id' => 'aqualuxe-language-switcher',
            'title' => $this->languages[$this->current_language]->get_name(),
            'href' => '#',
        ]);

        // Add languages as sub-menu items
        foreach ($this->languages as $code => $language) {
            $wp_admin_bar->add_node([
                'id' => 'aqualuxe-language-' . $code,
                'title' => $language->get_name(),
                'href' => add_query_arg('lang', $code),
                'parent' => 'aqualuxe-language-switcher',
            ]);
        }
    }

    /**
     * Add language to customizer
     *
     * @param \WP_Customize_Manager $wp_customize
     */
    public function add_language_to_customizer($wp_customize) {
        // Add language section
        $wp_customize->add_section('aqualuxe_language', [
            'title' => __('Language Settings', 'aqualuxe'),
            'priority' => 120,
        ]);

        // Add default language setting
        $wp_customize->add_setting('aqualuxe_module_multilingual_default_language', [
            'default' => 'en',
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_key',
        ]);

        // Add default language control
        $wp_customize->add_control('aqualuxe_module_multilingual_default_language', [
            'label' => __('Default Language', 'aqualuxe'),
            'section' => 'aqualuxe_language',
            'type' => 'select',
            'choices' => $this->get_language_choices(),
        ]);

        // Add switcher style setting
        $wp_customize->add_setting('aqualuxe_module_multilingual_switcher_style', [
            'default' => 'dropdown',
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'sanitize_key',
        ]);

        // Add switcher style control
        $wp_customize->add_control('aqualuxe_module_multilingual_switcher_style', [
            'label' => __('Switcher Style', 'aqualuxe'),
            'section' => 'aqualuxe_language',
            'type' => 'select',
            'choices' => [
                'dropdown' => __('Dropdown', 'aqualuxe'),
                'list' => __('List', 'aqualuxe'),
                'buttons' => __('Buttons', 'aqualuxe'),
            ],
        ]);

        // Add show flags setting
        $wp_customize->add_setting('aqualuxe_module_multilingual_show_flags', [
            'default' => true,
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'rest_sanitize_boolean',
        ]);

        // Add show flags control
        $wp_customize->add_control('aqualuxe_module_multilingual_show_flags', [
            'label' => __('Show Flags', 'aqualuxe'),
            'section' => 'aqualuxe_language',
            'type' => 'checkbox',
        ]);

        // Add show names setting
        $wp_customize->add_setting('aqualuxe_module_multilingual_show_names', [
            'default' => true,
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'rest_sanitize_boolean',
        ]);

        // Add show names control
        $wp_customize->add_control('aqualuxe_module_multilingual_show_names', [
            'label' => __('Show Names', 'aqualuxe'),
            'section' => 'aqualuxe_language',
            'type' => 'checkbox',
        ]);

        // Add to menu setting
        $wp_customize->add_setting('aqualuxe_module_multilingual_add_to_menu', [
            'default' => false,
            'type' => 'option',
            'capability' => 'manage_options',
            'sanitize_callback' => 'rest_sanitize_boolean',
        ]);

        // Add to menu control
        $wp_customize->add_control('aqualuxe_module_multilingual_add_to_menu', [
            'label' => __('Add to Primary Menu', 'aqualuxe'),
            'section' => 'aqualuxe_language',
            'type' => 'checkbox',
        ]);
    }

    /**
     * Get language choices
     *
     * @return array
     */
    private function get_language_choices() {
        $choices = [];

        foreach ($this->languages as $code => $language) {
            $choices[$code] = $language->get_name();
        }

        return $choices;
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
     * Get languages
     *
     * @return array
     */
    public function get_languages() {
        return $this->languages;
    }

    /**
     * Get language
     *
     * @param string $code
     * @return Language|null
     */
    public function get_language($code) {
        return isset($this->languages[$code]) ? $this->languages[$code] : null;
    }

    /**
     * Get language URL
     *
     * @param string $code
     * @return string
     */
    public function get_language_url($code) {
        $url = add_query_arg('lang', $code);
        return $url;
    }

    /**
     * Check if language is active
     *
     * @param string $code
     * @return bool
     */
    public function is_language_active($code) {
        return $this->current_language === $code;
    }

    /**
     * Check if current language is RTL
     *
     * @return bool
     */
    public function is_rtl() {
        return isset($this->languages[$this->current_language]) && $this->languages[$this->current_language]->is_rtl();
    }
}

// Register module
return new Module();