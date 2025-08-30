<?php
/**
 * Dark Mode Module
 *
 * @package AquaLuxe
 * @subpackage Modules\Dark_Mode
 */

namespace AquaLuxe\Modules\Dark_Mode;

use AquaLuxe\Core\Module_Base;

/**
 * Dark Mode Module class
 */
class Module extends Module_Base {
    /**
     * Module ID
     *
     * @var string
     */
    protected $module_id = 'dark-mode';

    /**
     * Module name
     *
     * @var string
     */
    protected $module_name = 'Dark Mode';

    /**
     * Module description
     *
     * @var string
     */
    protected $module_description = 'Adds dark mode support to the theme.';

    /**
     * Module dependencies
     *
     * @var array
     */
    protected $module_dependencies = [];

    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct();

        // Register hooks
        add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);
        add_action('customize_register', [$this, 'customize_register']);
        add_action('wp_head', [$this, 'add_inline_styles']);
        add_filter('body_class', [$this, 'body_classes']);
    }

    /**
     * Enqueue scripts
     *
     * @return void
     */
    public function enqueue_scripts() {
        wp_enqueue_script(
            'aqualuxe-dark-mode',
            AQUALUXE_MODULES_DIR . 'dark-mode/assets/js/dark-mode.js',
            ['jquery'],
            AQUALUXE_VERSION,
            true
        );

        wp_localize_script(
            'aqualuxe-dark-mode',
            'aqualuxeDarkMode',
            [
                'auto' => get_theme_mod('dark_mode_auto', false),
                'defaultDark' => get_theme_mod('dark_mode_default', false),
                'saveInCookies' => get_theme_mod('dark_mode_cookies', true),
                'cookieName' => 'aqualuxe_dark_mode',
                'cookieExpiration' => 30, // days
            ]
        );

        wp_enqueue_style(
            'aqualuxe-dark-mode',
            AQUALUXE_MODULES_DIR . 'dark-mode/assets/css/dark-mode.css',
            [],
            AQUALUXE_VERSION
        );
    }

    /**
     * Register customizer settings
     *
     * @param WP_Customize_Manager $wp_customize Customizer manager
     * @return void
     */
    public function customize_register($wp_customize) {
        // Dark Mode Section
        $wp_customize->add_section(
            'dark_mode_section',
            [
                'title' => __('Dark Mode', 'aqualuxe'),
                'priority' => 30,
                'panel' => 'aqualuxe_theme_options',
            ]
        );

        // Dark Mode Default
        $wp_customize->add_setting(
            'dark_mode_default',
            [
                'default' => false,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            ]
        );

        $wp_customize->add_control(
            'dark_mode_default',
            [
                'label' => __('Default to Dark Mode', 'aqualuxe'),
                'description' => __('Enable dark mode by default.', 'aqualuxe'),
                'section' => 'dark_mode_section',
                'type' => 'checkbox',
            ]
        );

        // Dark Mode Auto
        $wp_customize->add_setting(
            'dark_mode_auto',
            [
                'default' => false,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            ]
        );

        $wp_customize->add_control(
            'dark_mode_auto',
            [
                'label' => __('Auto Dark Mode', 'aqualuxe'),
                'description' => __('Automatically switch to dark mode based on user\'s system preferences.', 'aqualuxe'),
                'section' => 'dark_mode_section',
                'type' => 'checkbox',
            ]
        );

        // Dark Mode Cookies
        $wp_customize->add_setting(
            'dark_mode_cookies',
            [
                'default' => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            ]
        );

        $wp_customize->add_control(
            'dark_mode_cookies',
            [
                'label' => __('Save Dark Mode Preference', 'aqualuxe'),
                'description' => __('Save user\'s dark mode preference in cookies.', 'aqualuxe'),
                'section' => 'dark_mode_section',
                'type' => 'checkbox',
            ]
        );

        // Dark Mode Colors
        $wp_customize->add_setting(
            'dark_mode_background',
            [
                'default' => '#121212',
                'sanitize_callback' => 'sanitize_hex_color',
            ]
        );

        $wp_customize->add_control(
            new \WP_Customize_Color_Control(
                $wp_customize,
                'dark_mode_background',
                [
                    'label' => __('Dark Mode Background', 'aqualuxe'),
                    'section' => 'dark_mode_section',
                ]
            )
        );

        $wp_customize->add_setting(
            'dark_mode_text',
            [
                'default' => '#e0e0e0',
                'sanitize_callback' => 'sanitize_hex_color',
            ]
        );

        $wp_customize->add_control(
            new \WP_Customize_Color_Control(
                $wp_customize,
                'dark_mode_text',
                [
                    'label' => __('Dark Mode Text', 'aqualuxe'),
                    'section' => 'dark_mode_section',
                ]
            )
        );
    }

    /**
     * Add inline styles
     *
     * @return void
     */
    public function add_inline_styles() {
        $background = get_theme_mod('dark_mode_background', '#121212');
        $text = get_theme_mod('dark_mode_text', '#e0e0e0');

        $css = "
            :root {
                --dark-mode-bg: {$background};
                --dark-mode-text: {$text};
            }
            
            [data-theme='dark'] {
                --background-color: var(--dark-mode-bg);
                --text-color: var(--dark-mode-text);
                --border-color: #333;
                --link-color: #80cbc4;
                --link-hover-color: #4db6ac;
                --input-background: #333;
                --input-text: #e0e0e0;
                --button-background: #455a64;
                --button-text: #ffffff;
            }
        ";

        echo '<style id="aqualuxe-dark-mode-inline-css">' . wp_strip_all_tags($css) . '</style>';
    }

    /**
     * Add body classes
     *
     * @param array $classes Body classes
     * @return array
     */
    public function body_classes($classes) {
        $default_dark = get_theme_mod('dark_mode_default', false);

        if ($default_dark) {
            $classes[] = 'dark-mode-default';
        }

        $auto_dark = get_theme_mod('dark_mode_auto', false);

        if ($auto_dark) {
            $classes[] = 'dark-mode-auto';
        }

        return $classes;
    }
}

// Initialize the module
new Module();