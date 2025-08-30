<?php
/**
 * Multilingual Module
 *
 * @package AquaLuxe
 * @subpackage Modules\Multilingual
 */

namespace AquaLuxe\Modules\Multilingual;

use AquaLuxe\Core\Module_Base;

/**
 * Multilingual Module class
 */
class Module extends Module_Base {
    /**
     * Module ID
     *
     * @var string
     */
    protected $module_id = 'multilingual';

    /**
     * Module name
     *
     * @var string
     */
    protected $module_name = 'Multilingual';

    /**
     * Module description
     *
     * @var string
     */
    protected $module_description = 'Adds multilingual support to the theme.';

    /**
     * Module dependencies
     *
     * @var array
     */
    protected $module_dependencies = [];

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
     * Constructor
     */
    public function __construct() {
        parent::__construct();

        // Initialize languages
        $this->init_languages();

        // Register hooks
        add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);
        add_action('customize_register', [$this, 'customize_register']);
        add_action('init', [$this, 'register_language_switcher_menu']);
        add_filter('body_class', [$this, 'body_classes']);
        add_filter('locale', [$this, 'set_locale']);
    }

    /**
     * Initialize languages
     *
     * @return void
     */
    private function init_languages() {
        // Default languages
        $this->languages = [
            'en_US' => [
                'name' => __('English (US)', 'aqualuxe'),
                'flag' => 'us',
                'locale' => 'en_US',
            ],
            'es_ES' => [
                'name' => __('Spanish', 'aqualuxe'),
                'flag' => 'es',
                'locale' => 'es_ES',
            ],
            'fr_FR' => [
                'name' => __('French', 'aqualuxe'),
                'flag' => 'fr',
                'locale' => 'fr_FR',
            ],
            'de_DE' => [
                'name' => __('German', 'aqualuxe'),
                'flag' => 'de',
                'locale' => 'de_DE',
            ],
        ];

        // Get enabled languages from theme mods
        $enabled_languages = get_theme_mod('enabled_languages', ['en_US']);
        
        // Filter languages
        foreach ($this->languages as $code => $language) {
            if (!in_array($code, $enabled_languages, true)) {
                unset($this->languages[$code]);
            }
        }

        // Set current language
        $this->current_language = $this->get_current_language();
    }

    /**
     * Enqueue scripts
     *
     * @return void
     */
    public function enqueue_scripts() {
        wp_enqueue_style(
            'aqualuxe-multilingual',
            AQUALUXE_MODULES_DIR . 'multilingual/assets/css/multilingual.css',
            [],
            AQUALUXE_VERSION
        );

        wp_enqueue_script(
            'aqualuxe-multilingual',
            AQUALUXE_MODULES_DIR . 'multilingual/assets/js/multilingual.js',
            ['jquery'],
            AQUALUXE_VERSION,
            true
        );

        wp_localize_script(
            'aqualuxe-multilingual',
            'aqualuxeMultilingual',
            [
                'ajaxUrl' => admin_url('admin-ajax.php'),
                'currentLanguage' => $this->current_language,
                'saveInCookies' => get_theme_mod('language_cookies', true),
                'cookieName' => 'aqualuxe_language',
                'cookieExpiration' => 30, // days
            ]
        );
    }

    /**
     * Register customizer settings
     *
     * @param WP_Customize_Manager $wp_customize Customizer manager
     * @return void
     */
    public function customize_register($wp_customize) {
        // Multilingual Section
        $wp_customize->add_section(
            'multilingual_section',
            [
                'title' => __('Multilingual', 'aqualuxe'),
                'priority' => 35,
                'panel' => 'aqualuxe_theme_options',
            ]
        );

        // Enabled Languages
        $wp_customize->add_setting(
            'enabled_languages',
            [
                'default' => ['en_US'],
                'sanitize_callback' => [$this, 'sanitize_languages'],
            ]
        );

        $wp_customize->add_control(
            new \WP_Customize_Control(
                $wp_customize,
                'enabled_languages',
                [
                    'label' => __('Enabled Languages', 'aqualuxe'),
                    'description' => __('Select languages to enable on your site.', 'aqualuxe'),
                    'section' => 'multilingual_section',
                    'type' => 'select',
                    'choices' => [
                        'en_US' => __('English (US)', 'aqualuxe'),
                        'es_ES' => __('Spanish', 'aqualuxe'),
                        'fr_FR' => __('French', 'aqualuxe'),
                        'de_DE' => __('German', 'aqualuxe'),
                    ],
                    'multiple' => true,
                ]
            )
        );

        // Default Language
        $wp_customize->add_setting(
            'default_language',
            [
                'default' => 'en_US',
                'sanitize_callback' => 'sanitize_text_field',
            ]
        );

        $wp_customize->add_control(
            'default_language',
            [
                'label' => __('Default Language', 'aqualuxe'),
                'description' => __('Select the default language for your site.', 'aqualuxe'),
                'section' => 'multilingual_section',
                'type' => 'select',
                'choices' => [
                    'en_US' => __('English (US)', 'aqualuxe'),
                    'es_ES' => __('Spanish', 'aqualuxe'),
                    'fr_FR' => __('French', 'aqualuxe'),
                    'de_DE' => __('German', 'aqualuxe'),
                ],
            ]
        );

        // Language Switcher Position
        $wp_customize->add_setting(
            'language_switcher_position',
            [
                'default' => 'header',
                'sanitize_callback' => 'sanitize_text_field',
            ]
        );

        $wp_customize->add_control(
            'language_switcher_position',
            [
                'label' => __('Language Switcher Position', 'aqualuxe'),
                'description' => __('Select where to display the language switcher.', 'aqualuxe'),
                'section' => 'multilingual_section',
                'type' => 'select',
                'choices' => [
                    'header' => __('Header', 'aqualuxe'),
                    'footer' => __('Footer', 'aqualuxe'),
                    'both' => __('Both', 'aqualuxe'),
                    'none' => __('None', 'aqualuxe'),
                ],
            ]
        );

        // Language Cookies
        $wp_customize->add_setting(
            'language_cookies',
            [
                'default' => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            ]
        );

        $wp_customize->add_control(
            'language_cookies',
            [
                'label' => __('Save Language Preference', 'aqualuxe'),
                'description' => __('Save user\'s language preference in cookies.', 'aqualuxe'),
                'section' => 'multilingual_section',
                'type' => 'checkbox',
            ]
        );

        // Language Switcher Style
        $wp_customize->add_setting(
            'language_switcher_style',
            [
                'default' => 'dropdown',
                'sanitize_callback' => 'sanitize_text_field',
            ]
        );

        $wp_customize->add_control(
            'language_switcher_style',
            [
                'label' => __('Language Switcher Style', 'aqualuxe'),
                'description' => __('Select the style for the language switcher.', 'aqualuxe'),
                'section' => 'multilingual_section',
                'type' => 'select',
                'choices' => [
                    'dropdown' => __('Dropdown', 'aqualuxe'),
                    'flags' => __('Flags', 'aqualuxe'),
                    'flags_names' => __('Flags with Names', 'aqualuxe'),
                ],
            ]
        );
    }

    /**
     * Sanitize languages
     *
     * @param array $input Languages
     * @return array
     */
    public function sanitize_languages($input) {
        $valid_languages = ['en_US', 'es_ES', 'fr_FR', 'de_DE'];
        $output = [];

        foreach ($input as $language) {
            if (in_array($language, $valid_languages, true)) {
                $output[] = $language;
            }
        }

        // Ensure at least one language is enabled
        if (empty($output)) {
            $output[] = 'en_US';
        }

        return $output;
    }

    /**
     * Register language switcher menu
     *
     * @return void
     */
    public function register_language_switcher_menu() {
        register_nav_menus(
            [
                'language_switcher' => __('Language Switcher', 'aqualuxe'),
            ]
        );
    }

    /**
     * Add body classes
     *
     * @param array $classes Body classes
     * @return array
     */
    public function body_classes($classes) {
        $classes[] = 'lang-' . $this->current_language;
        return $classes;
    }

    /**
     * Set locale
     *
     * @param string $locale Locale
     * @return string
     */
    public function set_locale($locale) {
        if (!empty($this->current_language)) {
            return $this->current_language;
        }
        return $locale;
    }

    /**
     * Get current language
     *
     * @return string
     */
    public function get_current_language() {
        // Check if language is set in URL
        if (isset($_GET['lang']) && array_key_exists($_GET['lang'], $this->languages)) {
            return sanitize_text_field($_GET['lang']);
        }

        // Check if language is set in cookie
        if (isset($_COOKIE['aqualuxe_language']) && array_key_exists($_COOKIE['aqualuxe_language'], $this->languages)) {
            return sanitize_text_field($_COOKIE['aqualuxe_language']);
        }

        // Fallback to default language
        return get_theme_mod('default_language', 'en_US');
    }

    /**
     * Render language switcher
     *
     * @return void
     */
    public function render_language_switcher() {
        $style = get_theme_mod('language_switcher_style', 'dropdown');
        $position = get_theme_mod('language_switcher_position', 'header');

        if ('none' === $position) {
            return;
        }

        if (count($this->languages) <= 1) {
            return;
        }

        echo '<div class="language-switcher language-switcher-' . esc_attr($style) . '">';

        if ('dropdown' === $style) {
            $this->render_dropdown_switcher();
        } elseif ('flags' === $style) {
            $this->render_flags_switcher();
        } elseif ('flags_names' === $style) {
            $this->render_flags_names_switcher();
        }

        echo '</div>';
    }

    /**
     * Render dropdown switcher
     *
     * @return void
     */
    private function render_dropdown_switcher() {
        echo '<select id="language-switcher-select" class="language-switcher-select">';
        
        foreach ($this->languages as $code => $language) {
            $selected = $code === $this->current_language ? ' selected' : '';
            echo '<option value="' . esc_attr($code) . '"' . $selected . '>' . esc_html($language['name']) . '</option>';
        }
        
        echo '</select>';
    }

    /**
     * Render flags switcher
     *
     * @return void
     */
    private function render_flags_switcher() {
        echo '<ul class="language-switcher-flags">';
        
        foreach ($this->languages as $code => $language) {
            $active = $code === $this->current_language ? ' class="active"' : '';
            echo '<li' . $active . '>';
            echo '<a href="?lang=' . esc_attr($code) . '" data-lang="' . esc_attr($code) . '">';
            echo '<img src="' . esc_url(AQUALUXE_MODULES_URI . 'multilingual/assets/flags/' . $language['flag'] . '.png') . '" alt="' . esc_attr($language['name']) . '">';
            echo '</a>';
            echo '</li>';
        }
        
        echo '</ul>';
    }

    /**
     * Render flags with names switcher
     *
     * @return void
     */
    private function render_flags_names_switcher() {
        echo '<ul class="language-switcher-flags-names">';
        
        foreach ($this->languages as $code => $language) {
            $active = $code === $this->current_language ? ' class="active"' : '';
            echo '<li' . $active . '>';
            echo '<a href="?lang=' . esc_attr($code) . '" data-lang="' . esc_attr($code) . '">';
            echo '<img src="' . esc_url(AQUALUXE_MODULES_URI . 'multilingual/assets/flags/' . $language['flag'] . '.png') . '" alt="' . esc_attr($language['name']) . '">';
            echo '<span>' . esc_html($language['name']) . '</span>';
            echo '</a>';
            echo '</li>';
        }
        
        echo '</ul>';
    }
}

// Initialize the module
new Module();