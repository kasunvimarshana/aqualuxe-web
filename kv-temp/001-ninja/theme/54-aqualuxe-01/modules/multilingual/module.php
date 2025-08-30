<?php
/**
 * AquaLuxe Multilingual Module
 *
 * @package AquaLuxe
 * @subpackage Multilingual
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * AquaLuxe Multilingual Module Class
 * 
 * Handles multilingual functionality for the theme
 */
class AquaLuxe_Multilingual_Module {
    /**
     * Instance of this class
     *
     * @var AquaLuxe_Multilingual_Module
     */
    private static $instance = null;

    /**
     * Module settings
     *
     * @var array
     */
    private $settings = [];

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
     * Default language
     *
     * @var string
     */
    private $default_language = '';

    /**
     * Get the singleton instance of this class
     *
     * @return AquaLuxe_Multilingual_Module
     */
    public static function instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    private function __construct() {
        // Load module settings
        $this->load_settings();
        
        // Initialize module
        $this->init();
    }

    /**
     * Load module settings
     */
    private function load_settings() {
        $this->settings = apply_filters('aqualuxe_multilingual_settings', [
            'enabled' => true,
            'default_language' => 'en_US',
            'languages' => [
                'en_US' => [
                    'name' => 'English (US)',
                    'flag' => 'us.png',
                    'locale' => 'en_US',
                ],
                'es_ES' => [
                    'name' => 'Español',
                    'flag' => 'es.png',
                    'locale' => 'es_ES',
                ],
                'fr_FR' => [
                    'name' => 'Français',
                    'flag' => 'fr.png',
                    'locale' => 'fr_FR',
                ],
                'de_DE' => [
                    'name' => 'Deutsch',
                    'flag' => 'de.png',
                    'locale' => 'de_DE',
                ],
            ],
            'show_language_switcher' => true,
            'switcher_style' => 'dropdown', // dropdown, flags, text
            'switcher_position' => 'top-bar', // top-bar, header, footer, menu
            'translate_slugs' => true,
            'auto_detect_language' => true,
        ]);

        // Set languages
        $this->languages = $this->settings['languages'];
        
        // Set default language
        $this->default_language = $this->settings['default_language'];
        
        // Set current language
        $this->current_language = $this->get_current_language();
    }

    /**
     * Initialize module
     */
    private function init() {
        // Check if module is enabled
        if (!$this->settings['enabled']) {
            return;
        }

        // Register hooks
        $this->register_hooks();
        
        // Load compatibility classes
        $this->load_compatibility();
    }

    /**
     * Register hooks
     */
    private function register_hooks() {
        // Add language switcher
        if ($this->settings['show_language_switcher']) {
            switch ($this->settings['switcher_position']) {
                case 'top-bar':
                    add_action('aqualuxe_top_bar', [$this, 'language_switcher']);
                    break;
                case 'header':
                    add_action('aqualuxe_header', [$this, 'language_switcher']);
                    break;
                case 'footer':
                    add_action('aqualuxe_footer', [$this, 'language_switcher']);
                    break;
                case 'menu':
                    add_filter('wp_nav_menu_items', [$this, 'add_language_switcher_to_menu'], 10, 2);
                    break;
            }
        }
        
        // Enqueue scripts and styles
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
        
        // Add language to body class
        add_filter('body_class', [$this, 'add_language_body_class']);
        
        // Add language meta tags
        add_action('wp_head', [$this, 'add_language_meta_tags']);
        
        // Add hreflang links
        add_action('wp_head', [$this, 'add_hreflang_links']);
        
        // Add admin settings
        add_action('customize_register', [$this, 'customize_register']);
        
        // Add language switcher widget
        add_action('widgets_init', [$this, 'register_widgets']);
    }

    /**
     * Load compatibility classes
     */
    private function load_compatibility() {
        // Check for WPML
        if (defined('ICL_SITEPRESS_VERSION')) {
            require_once dirname(__FILE__) . '/classes/class-aqualuxe-multilingual-wpml.php';
            new AquaLuxe_Multilingual_WPML();
        }
        
        // Check for Polylang
        if (defined('POLYLANG_VERSION')) {
            require_once dirname(__FILE__) . '/classes/class-aqualuxe-multilingual-polylang.php';
            new AquaLuxe_Multilingual_Polylang();
        }
        
        // If no plugin is active, use our basic implementation
        if (!defined('ICL_SITEPRESS_VERSION') && !defined('POLYLANG_VERSION')) {
            require_once dirname(__FILE__) . '/classes/class-aqualuxe-multilingual-basic.php';
            new AquaLuxe_Multilingual_Basic();
        }
    }

    /**
     * Get current language
     *
     * @return string
     */
    public function get_current_language() {
        // If already set, return it
        if (!empty($this->current_language)) {
            return $this->current_language;
        }
        
        $language = $this->default_language;
        
        // Check for WPML
        if (defined('ICL_SITEPRESS_VERSION')) {
            $language = apply_filters('wpml_current_language', $this->default_language);
        }
        
        // Check for Polylang
        if (defined('POLYLANG_VERSION') && function_exists('pll_current_language')) {
            $current = pll_current_language('locale');
            if ($current) {
                $language = $current;
            }
        }
        
        // Check for language cookie
        if (isset($_COOKIE['aqualuxe_language'])) {
            $cookie_language = sanitize_text_field($_COOKIE['aqualuxe_language']);
            
            // Verify it's a valid language
            if (isset($this->languages[$cookie_language])) {
                $language = $cookie_language;
            }
        }
        
        // Auto detect language if enabled
        if ($this->settings['auto_detect_language'] && empty($_COOKIE['aqualuxe_language'])) {
            $browser_language = $this->get_browser_language();
            
            if ($browser_language && isset($this->languages[$browser_language])) {
                $language = $browser_language;
            }
        }
        
        return $language;
    }

    /**
     * Get browser language
     *
     * @return string|null
     */
    private function get_browser_language() {
        if (!isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            return null;
        }
        
        $browser_languages = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
        
        foreach ($browser_languages as $browser_language) {
            $browser_language = substr($browser_language, 0, 2);
            
            // Find matching language
            foreach ($this->languages as $code => $language) {
                if (strpos($code, $browser_language) === 0) {
                    return $code;
                }
            }
        }
        
        return null;
    }

    /**
     * Display language switcher
     */
    public function language_switcher() {
        // Get current language
        $current_language = $this->get_current_language();
        
        // Get switcher style
        $style = $this->settings['switcher_style'];
        
        // Start output
        echo '<div class="aqualuxe-language-switcher aqualuxe-language-switcher-' . esc_attr($style) . '">';
        
        switch ($style) {
            case 'dropdown':
                $this->dropdown_language_switcher($current_language);
                break;
            case 'flags':
                $this->flags_language_switcher($current_language);
                break;
            case 'text':
                $this->text_language_switcher($current_language);
                break;
        }
        
        echo '</div>';
    }

    /**
     * Display dropdown language switcher
     *
     * @param string $current_language Current language code
     */
    private function dropdown_language_switcher($current_language) {
        echo '<select class="aqualuxe-language-select">';
        
        foreach ($this->languages as $code => $language) {
            $selected = $code === $current_language ? ' selected' : '';
            $url = $this->get_language_url($code);
            
            echo '<option value="' . esc_attr($code) . '" data-url="' . esc_url($url) . '"' . $selected . '>';
            echo esc_html($language['name']);
            echo '</option>';
        }
        
        echo '</select>';
        
        // Add JavaScript to handle language switching
        ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var select = document.querySelector('.aqualuxe-language-select');
                
                if (select) {
                    select.addEventListener('change', function() {
                        var option = this.options[this.selectedIndex];
                        var url = option.getAttribute('data-url');
                        
                        if (url) {
                            window.location.href = url;
                        }
                    });
                }
            });
        </script>
        <?php
    }

    /**
     * Display flags language switcher
     *
     * @param string $current_language Current language code
     */
    private function flags_language_switcher($current_language) {
        echo '<ul class="aqualuxe-language-flags">';
        
        foreach ($this->languages as $code => $language) {
            $active = $code === $current_language ? ' aqualuxe-language-active' : '';
            $url = $this->get_language_url($code);
            $flag_url = $this->get_flag_url($language['flag']);
            
            echo '<li class="aqualuxe-language-flag-item' . $active . '">';
            echo '<a href="' . esc_url($url) . '" title="' . esc_attr($language['name']) . '">';
            echo '<img src="' . esc_url($flag_url) . '" alt="' . esc_attr($language['name']) . '">';
            echo '</a>';
            echo '</li>';
        }
        
        echo '</ul>';
    }

    /**
     * Display text language switcher
     *
     * @param string $current_language Current language code
     */
    private function text_language_switcher($current_language) {
        echo '<ul class="aqualuxe-language-text">';
        
        foreach ($this->languages as $code => $language) {
            $active = $code === $current_language ? ' aqualuxe-language-active' : '';
            $url = $this->get_language_url($code);
            
            echo '<li class="aqualuxe-language-text-item' . $active . '">';
            echo '<a href="' . esc_url($url) . '">';
            echo esc_html($language['name']);
            echo '</a>';
            echo '</li>';
        }
        
        echo '</ul>';
    }

    /**
     * Add language switcher to menu
     *
     * @param string $items Menu items HTML
     * @param object $args Menu arguments
     * @return string
     */
    public function add_language_switcher_to_menu($items, $args) {
        // Only add to primary menu
        if ($args->theme_location !== 'primary') {
            return $items;
        }
        
        // Get current language
        $current_language = $this->get_current_language();
        
        // Start output
        $output = '<li class="menu-item menu-item-has-children aqualuxe-language-menu-item">';
        
        // Current language
        $current = $this->languages[$current_language];
        $flag_url = $this->get_flag_url($current['flag']);
        
        $output .= '<a href="#">';
        $output .= '<img src="' . esc_url($flag_url) . '" alt="' . esc_attr($current['name']) . '" class="aqualuxe-language-flag">';
        $output .= esc_html($current['name']);
        $output .= '</a>';
        
        // Sub-menu with languages
        $output .= '<ul class="sub-menu">';
        
        foreach ($this->languages as $code => $language) {
            if ($code === $current_language) {
                continue;
            }
            
            $url = $this->get_language_url($code);
            $flag_url = $this->get_flag_url($language['flag']);
            
            $output .= '<li class="menu-item">';
            $output .= '<a href="' . esc_url($url) . '">';
            $output .= '<img src="' . esc_url($flag_url) . '" alt="' . esc_attr($language['name']) . '" class="aqualuxe-language-flag">';
            $output .= esc_html($language['name']);
            $output .= '</a>';
            $output .= '</li>';
        }
        
        $output .= '</ul>';
        $output .= '</li>';
        
        return $items . $output;
    }

    /**
     * Get language URL
     *
     * @param string $language_code Language code
     * @return string
     */
    public function get_language_url($language_code) {
        $url = '';
        
        // Check for WPML
        if (defined('ICL_SITEPRESS_VERSION')) {
            $url = apply_filters('wpml_permalink', get_permalink(), $language_code);
        }
        
        // Check for Polylang
        if (defined('POLYLANG_VERSION') && function_exists('pll_home_url')) {
            if (is_home() || is_front_page()) {
                $url = pll_home_url($language_code);
            } else {
                $translation_id = pll_get_post(get_the_ID(), $language_code);
                
                if ($translation_id) {
                    $url = get_permalink($translation_id);
                } else {
                    $url = pll_home_url($language_code);
                }
            }
        }
        
        // If no plugin is active or URL not found, use basic implementation
        if (empty($url)) {
            $url = add_query_arg('lang', $language_code, get_permalink());
        }
        
        return $url;
    }

    /**
     * Get flag URL
     *
     * @param string $flag Flag filename
     * @return string
     */
    public function get_flag_url($flag) {
        return AQUALUXE_URI . 'modules/multilingual/assets/flags/' . $flag;
    }

    /**
     * Enqueue assets
     */
    public function enqueue_assets() {
        // Enqueue styles
        wp_enqueue_style(
            'aqualuxe-multilingual',
            AQUALUXE_URI . 'modules/multilingual/assets/css/multilingual.css',
            [],
            AQUALUXE_VERSION
        );
        
        // Enqueue scripts
        wp_enqueue_script(
            'aqualuxe-multilingual',
            AQUALUXE_URI . 'modules/multilingual/assets/js/multilingual.js',
            ['jquery'],
            AQUALUXE_VERSION,
            true
        );
        
        // Localize script
        wp_localize_script('aqualuxe-multilingual', 'aqualuxeMultilingual', [
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('aqualuxe-multilingual-nonce'),
            'currentLanguage' => $this->current_language,
            'defaultLanguage' => $this->default_language,
        ]);
    }

    /**
     * Add language body class
     *
     * @param array $classes Body classes
     * @return array
     */
    public function add_language_body_class($classes) {
        $classes[] = 'aqualuxe-lang-' . str_replace('_', '-', $this->current_language);
        return $classes;
    }

    /**
     * Add language meta tags
     */
    public function add_language_meta_tags() {
        echo '<meta name="language" content="' . esc_attr($this->current_language) . '">' . "\n";
        echo '<meta http-equiv="content-language" content="' . esc_attr($this->current_language) . '">' . "\n";
    }

    /**
     * Add hreflang links
     */
    public function add_hreflang_links() {
        foreach ($this->languages as $code => $language) {
            $url = $this->get_language_url($code);
            $hreflang = str_replace('_', '-', $code);
            
            echo '<link rel="alternate" hreflang="' . esc_attr($hreflang) . '" href="' . esc_url($url) . '">' . "\n";
        }
    }

    /**
     * Register customizer settings
     *
     * @param WP_Customize_Manager $wp_customize Customizer object
     */
    public function customize_register($wp_customize) {
        // Add section
        $wp_customize->add_section('aqualuxe_multilingual', [
            'title' => __('Multilingual Settings', 'aqualuxe'),
            'priority' => 120,
        ]);
        
        // Add settings
        $wp_customize->add_setting('aqualuxe_multilingual_enabled', [
            'default' => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        ]);
        
        $wp_customize->add_setting('aqualuxe_multilingual_default_language', [
            'default' => 'en_US',
            'sanitize_callback' => 'sanitize_text_field',
        ]);
        
        $wp_customize->add_setting('aqualuxe_multilingual_show_switcher', [
            'default' => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        ]);
        
        $wp_customize->add_setting('aqualuxe_multilingual_switcher_style', [
            'default' => 'dropdown',
            'sanitize_callback' => 'sanitize_text_field',
        ]);
        
        $wp_customize->add_setting('aqualuxe_multilingual_switcher_position', [
            'default' => 'top-bar',
            'sanitize_callback' => 'sanitize_text_field',
        ]);
        
        $wp_customize->add_setting('aqualuxe_multilingual_translate_slugs', [
            'default' => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        ]);
        
        $wp_customize->add_setting('aqualuxe_multilingual_auto_detect', [
            'default' => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        ]);
        
        // Add controls
        $wp_customize->add_control('aqualuxe_multilingual_enabled', [
            'label' => __('Enable Multilingual Support', 'aqualuxe'),
            'section' => 'aqualuxe_multilingual',
            'type' => 'checkbox',
        ]);
        
        $wp_customize->add_control('aqualuxe_multilingual_default_language', [
            'label' => __('Default Language', 'aqualuxe'),
            'section' => 'aqualuxe_multilingual',
            'type' => 'select',
            'choices' => $this->get_language_choices(),
        ]);
        
        $wp_customize->add_control('aqualuxe_multilingual_show_switcher', [
            'label' => __('Show Language Switcher', 'aqualuxe'),
            'section' => 'aqualuxe_multilingual',
            'type' => 'checkbox',
        ]);
        
        $wp_customize->add_control('aqualuxe_multilingual_switcher_style', [
            'label' => __('Language Switcher Style', 'aqualuxe'),
            'section' => 'aqualuxe_multilingual',
            'type' => 'select',
            'choices' => [
                'dropdown' => __('Dropdown', 'aqualuxe'),
                'flags' => __('Flags', 'aqualuxe'),
                'text' => __('Text', 'aqualuxe'),
            ],
        ]);
        
        $wp_customize->add_control('aqualuxe_multilingual_switcher_position', [
            'label' => __('Language Switcher Position', 'aqualuxe'),
            'section' => 'aqualuxe_multilingual',
            'type' => 'select',
            'choices' => [
                'top-bar' => __('Top Bar', 'aqualuxe'),
                'header' => __('Header', 'aqualuxe'),
                'footer' => __('Footer', 'aqualuxe'),
                'menu' => __('Primary Menu', 'aqualuxe'),
            ],
        ]);
        
        $wp_customize->add_control('aqualuxe_multilingual_translate_slugs', [
            'label' => __('Translate URL Slugs', 'aqualuxe'),
            'section' => 'aqualuxe_multilingual',
            'type' => 'checkbox',
        ]);
        
        $wp_customize->add_control('aqualuxe_multilingual_auto_detect', [
            'label' => __('Auto-detect Browser Language', 'aqualuxe'),
            'section' => 'aqualuxe_multilingual',
            'type' => 'checkbox',
        ]);
    }

    /**
     * Get language choices for customizer
     *
     * @return array
     */
    private function get_language_choices() {
        $choices = [];
        
        foreach ($this->languages as $code => $language) {
            $choices[$code] = $language['name'];
        }
        
        return $choices;
    }

    /**
     * Register widgets
     */
    public function register_widgets() {
        register_widget('AquaLuxe_Multilingual_Widget');
    }
}

/**
 * AquaLuxe Multilingual Widget
 */
class AquaLuxe_Multilingual_Widget extends WP_Widget {
    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct(
            'aqualuxe_multilingual_widget',
            __('AquaLuxe Language Switcher', 'aqualuxe'),
            [
                'description' => __('Displays a language switcher for your site.', 'aqualuxe'),
            ]
        );
    }

    /**
     * Widget output
     *
     * @param array $args Widget arguments
     * @param array $instance Widget instance
     */
    public function widget($args, $instance) {
        echo $args['before_widget'];
        
        if (!empty($instance['title'])) {
            echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];
        }
        
        // Get style
        $style = !empty($instance['style']) ? $instance['style'] : 'dropdown';
        
        // Get multilingual module
        $multilingual = AquaLuxe_Multilingual_Module::instance();
        
        // Override style
        add_filter('aqualuxe_multilingual_settings', function($settings) use ($style) {
            $settings['switcher_style'] = $style;
            return $settings;
        });
        
        // Display language switcher
        $multilingual->language_switcher();
        
        echo $args['after_widget'];
    }

    /**
     * Widget form
     *
     * @param array $instance Widget instance
     * @return void
     */
    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : '';
        $style = !empty($instance['style']) ? $instance['style'] : 'dropdown';
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>">
                <?php esc_html_e('Title:', 'aqualuxe'); ?>
            </label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('style')); ?>">
                <?php esc_html_e('Style:', 'aqualuxe'); ?>
            </label>
            <select class="widefat" id="<?php echo esc_attr($this->get_field_id('style')); ?>" name="<?php echo esc_attr($this->get_field_name('style')); ?>">
                <option value="dropdown" <?php selected($style, 'dropdown'); ?>><?php esc_html_e('Dropdown', 'aqualuxe'); ?></option>
                <option value="flags" <?php selected($style, 'flags'); ?>><?php esc_html_e('Flags', 'aqualuxe'); ?></option>
                <option value="text" <?php selected($style, 'text'); ?>><?php esc_html_e('Text', 'aqualuxe'); ?></option>
            </select>
        </p>
        <?php
    }

    /**
     * Update widget
     *
     * @param array $new_instance New instance
     * @param array $old_instance Old instance
     * @return array
     */
    public function update($new_instance, $old_instance) {
        $instance = [];
        $instance['title'] = (!empty($new_instance['title'])) ? sanitize_text_field($new_instance['title']) : '';
        $instance['style'] = (!empty($new_instance['style'])) ? sanitize_text_field($new_instance['style']) : 'dropdown';
        
        return $instance;
    }
}

// Initialize the module
AquaLuxe_Multilingual_Module::instance();