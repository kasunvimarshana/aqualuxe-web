<?php
/**
 * Multilingual Module
 *
 * @package AquaLuxe
 * @subpackage Modules
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Multilingual Module Class
 */
class AquaLuxe_Module_Multilingual extends AquaLuxe_Module {
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
    protected $description = 'Adds multilingual support to the theme with language switcher.';

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
    protected $dependencies = [
        [
            'type' => 'plugin',
            'name' => 'polylang/polylang.php',
            'message' => 'The Multilingual module requires the Polylang plugin to be installed and activated.',
        ],
    ];

    /**
     * Initialize module
     */
    protected function init() {
        // Add language switcher to header actions
        add_action('aqualuxe_header_actions', [$this, 'language_switcher'], 45);

        // Add language switcher to footer
        add_action('aqualuxe_footer_info', [$this, 'footer_language_switcher'], 20);

        // Add language class to body
        add_filter('body_class', [$this, 'body_class']);

        // Add language attributes to html tag
        add_filter('language_attributes', [$this, 'language_attributes']);

        // Filter home URL for language
        add_filter('aqualuxe_home_url', [$this, 'home_url']);
    }

    /**
     * Register module settings in customizer
     *
     * @param WP_Customize_Manager $wp_customize
     */
    public function customize_register($wp_customize) {
        // Call parent method
        parent::customize_register($wp_customize);

        // Add language switcher position setting
        $wp_customize->add_setting('aqualuxe_module_' . $this->id . '[switcher_position]', [
            'default' => 'header',
            'type' => 'option',
            'capability' => 'edit_theme_options',
            'sanitize_callback' => [$this, 'sanitize_switcher_position'],
        ]);

        // Add language switcher position control
        $wp_customize->add_control('aqualuxe_module_' . $this->id . '_switcher_position', [
            'label' => __('Language Switcher Position', 'aqualuxe'),
            'section' => 'aqualuxe_module_' . $this->id,
            'settings' => 'aqualuxe_module_' . $this->id . '[switcher_position]',
            'type' => 'select',
            'choices' => [
                'header' => __('Header', 'aqualuxe'),
                'footer' => __('Footer', 'aqualuxe'),
                'both' => __('Both', 'aqualuxe'),
                'none' => __('None', 'aqualuxe'),
            ],
            'priority' => 20,
        ]);

        // Add language switcher style setting
        $wp_customize->add_setting('aqualuxe_module_' . $this->id . '[switcher_style]', [
            'default' => 'dropdown',
            'type' => 'option',
            'capability' => 'edit_theme_options',
            'sanitize_callback' => [$this, 'sanitize_switcher_style'],
        ]);

        // Add language switcher style control
        $wp_customize->add_control('aqualuxe_module_' . $this->id . '_switcher_style', [
            'label' => __('Language Switcher Style', 'aqualuxe'),
            'section' => 'aqualuxe_module_' . $this->id,
            'settings' => 'aqualuxe_module_' . $this->id . '[switcher_style]',
            'type' => 'select',
            'choices' => [
                'dropdown' => __('Dropdown', 'aqualuxe'),
                'list' => __('List', 'aqualuxe'),
                'flags' => __('Flags', 'aqualuxe'),
                'flags_name' => __('Flags with Names', 'aqualuxe'),
            ],
            'priority' => 30,
        ]);

        // Add show current language setting
        $wp_customize->add_setting('aqualuxe_module_' . $this->id . '[show_current]', [
            'default' => true,
            'type' => 'option',
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        ]);

        // Add show current language control
        $wp_customize->add_control('aqualuxe_module_' . $this->id . '_show_current', [
            'label' => __('Show Current Language', 'aqualuxe'),
            'section' => 'aqualuxe_module_' . $this->id,
            'settings' => 'aqualuxe_module_' . $this->id . '[show_current]',
            'type' => 'checkbox',
            'priority' => 40,
        ]);

        // Add show language names setting
        $wp_customize->add_setting('aqualuxe_module_' . $this->id . '[show_names]', [
            'default' => true,
            'type' => 'option',
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        ]);

        // Add show language names control
        $wp_customize->add_control('aqualuxe_module_' . $this->id . '_show_names', [
            'label' => __('Show Language Names', 'aqualuxe'),
            'section' => 'aqualuxe_module_' . $this->id,
            'settings' => 'aqualuxe_module_' . $this->id . '[show_names]',
            'type' => 'checkbox',
            'priority' => 50,
        ]);

        // Add show flags setting
        $wp_customize->add_setting('aqualuxe_module_' . $this->id . '[show_flags]', [
            'default' => true,
            'type' => 'option',
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        ]);

        // Add show flags control
        $wp_customize->add_control('aqualuxe_module_' . $this->id . '_show_flags', [
            'label' => __('Show Flags', 'aqualuxe'),
            'section' => 'aqualuxe_module_' . $this->id,
            'settings' => 'aqualuxe_module_' . $this->id . '[show_flags]',
            'type' => 'checkbox',
            'priority' => 60,
        ]);
    }

    /**
     * Register module assets
     */
    public function register_assets() {
        // Check if module is enabled
        if (!$this->is_enabled()) {
            return;
        }

        // Register multilingual styles
        wp_enqueue_style(
            'aqualuxe-multilingual',
            $this->url . '/assets/css/multilingual.css',
            [],
            $this->version
        );

        // Register multilingual script
        wp_enqueue_script(
            'aqualuxe-multilingual',
            $this->url . '/assets/js/multilingual.js',
            ['jquery', 'alpine'],
            $this->version,
            true
        );
    }

    /**
     * Language switcher
     */
    public function language_switcher() {
        // Check if module is enabled
        if (!$this->is_enabled() || !function_exists('pll_the_languages')) {
            return;
        }

        // Check switcher position
        $position = $this->get_option('switcher_position', 'header');
        if ($position === 'none' || ($position === 'footer' && current_action() === 'aqualuxe_header_actions')) {
            return;
        }

        // Get template
        $this->get_template_part('switcher');
    }

    /**
     * Footer language switcher
     */
    public function footer_language_switcher() {
        // Check if module is enabled
        if (!$this->is_enabled() || !function_exists('pll_the_languages')) {
            return;
        }

        // Check switcher position
        $position = $this->get_option('switcher_position', 'header');
        if ($position === 'none' || ($position === 'header' && current_action() === 'aqualuxe_footer_info')) {
            return;
        }

        // Get template
        $this->get_template_part('footer-switcher');
    }

    /**
     * Add body class
     *
     * @param array $classes
     * @return array
     */
    public function body_class($classes) {
        // Check if module is enabled
        if (!$this->is_enabled() || !function_exists('pll_current_language')) {
            return $classes;
        }

        // Add language class
        $classes[] = 'lang-' . pll_current_language('slug');

        return $classes;
    }

    /**
     * Add language attributes
     *
     * @param string $output
     * @return string
     */
    public function language_attributes($output) {
        // Check if module is enabled
        if (!$this->is_enabled() || !function_exists('pll_current_language')) {
            return $output;
        }

        // Add language attribute
        $output .= ' data-language="' . pll_current_language('slug') . '"';

        return $output;
    }

    /**
     * Filter home URL for language
     *
     * @param string $url
     * @return string
     */
    public function home_url($url) {
        // Check if module is enabled
        if (!$this->is_enabled() || !function_exists('pll_home_url')) {
            return $url;
        }

        return pll_home_url();
    }

    /**
     * Sanitize switcher position
     *
     * @param string $value
     * @return string
     */
    public function sanitize_switcher_position($value) {
        $allowed_values = ['header', 'footer', 'both', 'none'];
        return in_array($value, $allowed_values) ? $value : 'header';
    }

    /**
     * Sanitize switcher style
     *
     * @param string $value
     * @return string
     */
    public function sanitize_switcher_style($value) {
        $allowed_values = ['dropdown', 'list', 'flags', 'flags_name'];
        return in_array($value, $allowed_values) ? $value : 'dropdown';
    }

    /**
     * Get default options
     *
     * @return array
     */
    protected function get_default_options() {
        return array_merge(parent::get_default_options(), [
            'switcher_position' => 'header',
            'switcher_style' => 'dropdown',
            'show_current' => true,
            'show_names' => true,
            'show_flags' => true,
        ]);
    }
}

// Initialize module
new AquaLuxe_Module_Multilingual();