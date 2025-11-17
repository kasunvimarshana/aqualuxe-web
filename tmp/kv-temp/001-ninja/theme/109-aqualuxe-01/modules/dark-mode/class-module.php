<?php
/**
 * Dark Mode Module
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

namespace AquaLuxe\Modules\Dark_Mode;

use AquaLuxe\Core\Abstracts\Abstract_Module;

defined('ABSPATH') || exit;

/**
 * Dark Mode Module Class
 */
class Module extends Abstract_Module {

    /**
     * Module name
     *
     * @var string
     */
    protected $name = 'Dark Mode';

    /**
     * Instance
     *
     * @var Module
     */
    private static $instance = null;

    /**
     * Get instance
     *
     * @return Module
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Initialize module
     */
    public function init() {
        add_action('wp_enqueue_scripts', array($this, 'enqueue_assets'));
        add_action('customize_register', array($this, 'customize_register'));
        add_action('wp_head', array($this, 'inline_styles'));
        add_filter('body_class', array($this, 'body_class'));
        add_action('wp_ajax_aqualuxe_toggle_dark_mode', array($this, 'ajax_toggle_dark_mode'));
        add_action('wp_ajax_nopriv_aqualuxe_toggle_dark_mode', array($this, 'ajax_toggle_dark_mode'));
    }

    /**
     * Enqueue assets
     */
    public function enqueue_assets() {
        // Dark mode is handled via CSS classes and JavaScript already included in main theme files
        wp_localize_script('aqualuxe-script', 'aqualuxe_dark_mode', array(
            'enabled' => get_theme_mod('aqualuxe_enable_dark_mode', true),
            'default' => get_theme_mod('aqualuxe_default_dark_mode', 'light'),
            'persist' => get_theme_mod('aqualuxe_persist_dark_mode', true),
        ));
    }

    /**
     * Add customizer controls
     *
     * @param WP_Customize_Manager $wp_customize Customizer manager
     */
    public function customize_register($wp_customize) {
        // Dark mode section
        $wp_customize->add_section('aqualuxe_dark_mode', array(
            'title'    => __('Dark Mode Settings', 'aqualuxe'),
            'priority' => 150,
        ));

        // Enable dark mode
        $wp_customize->add_setting('aqualuxe_enable_dark_mode', array(
            'default'           => true,
            'sanitize_callback' => 'wp_validate_boolean',
        ));

        $wp_customize->add_control('aqualuxe_enable_dark_mode', array(
            'label'       => __('Enable Dark Mode', 'aqualuxe'),
            'description' => __('Allow users to toggle between light and dark themes.', 'aqualuxe'),
            'section'     => 'aqualuxe_dark_mode',
            'type'        => 'checkbox',
        ));

        // Default mode
        $wp_customize->add_setting('aqualuxe_default_dark_mode', array(
            'default'           => 'light',
            'sanitize_callback' => 'sanitize_text_field',
        ));

        $wp_customize->add_control('aqualuxe_default_dark_mode', array(
            'label'   => __('Default Mode', 'aqualuxe'),
            'section' => 'aqualuxe_dark_mode',
            'type'    => 'select',
            'choices' => array(
                'light' => __('Light Mode', 'aqualuxe'),
                'dark'  => __('Dark Mode', 'aqualuxe'),
                'auto'  => __('System Preference', 'aqualuxe'),
            ),
        ));

        // Persist user preference
        $wp_customize->add_setting('aqualuxe_persist_dark_mode', array(
            'default'           => true,
            'sanitize_callback' => 'wp_validate_boolean',
        ));

        $wp_customize->add_control('aqualuxe_persist_dark_mode', array(
            'label'       => __('Remember User Preference', 'aqualuxe'),
            'description' => __('Save user\'s dark mode preference in local storage.', 'aqualuxe'),
            'section'     => 'aqualuxe_dark_mode',
            'type'        => 'checkbox',
        ));

        // Show toggle in header
        $wp_customize->add_setting('aqualuxe_dark_mode_header_toggle', array(
            'default'           => true,
            'sanitize_callback' => 'wp_validate_boolean',
        ));

        $wp_customize->add_control('aqualuxe_dark_mode_header_toggle', array(
            'label'   => __('Show Toggle in Header', 'aqualuxe'),
            'section' => 'aqualuxe_dark_mode',
            'type'    => 'checkbox',
        ));

        // Show toggle in footer
        $wp_customize->add_setting('aqualuxe_dark_mode_footer_toggle', array(
            'default'           => false,
            'sanitize_callback' => 'wp_validate_boolean',
        ));

        $wp_customize->add_control('aqualuxe_dark_mode_footer_toggle', array(
            'label'   => __('Show Toggle in Footer', 'aqualuxe'),
            'section' => 'aqualuxe_dark_mode',
            'type'    => 'checkbox',
        ));
    }

    /**
     * Add inline styles for dark mode
     */
    public function inline_styles() {
        if (!get_theme_mod('aqualuxe_enable_dark_mode', true)) {
            return;
        }

        $custom_css = "
        :root {
            --color-primary-50: #ecfeff;
            --color-primary-100: #cffafe;
            --color-primary-200: #a5f3fc;
            --color-primary-300: #67e8f9;
            --color-primary-400: #22d3ee;
            --color-primary-500: #06b6d4;
            --color-primary-600: #0891b2;
            --color-primary-700: #0e7490;
            --color-primary-800: #155e75;
            --color-primary-900: #164e63;
        }

        .dark {
            --color-bg: #1a202c;
            --color-surface: #2d3748;
            --color-surface-alt: #4a5568;
            --color-text: #f7fafc;
            --color-text-muted: #cbd5e0;
            --color-border: #4a5568;
        }

        html.dark {
            background-color: var(--color-bg);
            color: var(--color-text);
        }

        .dark .bg-white {
            background-color: var(--color-surface);
        }

        .dark .bg-gray-50 {
            background-color: var(--color-bg);
        }

        .dark .bg-gray-100 {
            background-color: var(--color-surface);
        }

        .dark .text-gray-900 {
            color: var(--color-text);
        }

        .dark .text-gray-700 {
            color: var(--color-text-muted);
        }

        .dark .border-gray-200 {
            border-color: var(--color-border);
        }

        .dark .hover\\:bg-gray-50:hover {
            background-color: var(--color-surface-alt);
        }

        .dark-mode-toggle {
            position: relative;
            display: inline-flex;
            align-items: center;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 0.375rem;
            transition: all 0.2s;
        }

        .dark-mode-toggle:hover {
            background-color: rgba(0, 0, 0, 0.05);
        }

        .dark .dark-mode-toggle:hover {
            background-color: rgba(255, 255, 255, 0.05);
        }

        .dark-mode-toggle svg {
            width: 1.25rem;
            height: 1.25rem;
        }

        .dark-mode-toggle .sun-icon {
            display: block;
        }

        .dark-mode-toggle .moon-icon {
            display: none;
        }

        .dark .dark-mode-toggle .sun-icon {
            display: none;
        }

        .dark .dark-mode-toggle .moon-icon {
            display: block;
        }

        @media (prefers-color-scheme: dark) {
            .auto-dark {
                --color-bg: #1a202c;
                --color-surface: #2d3748;
                --color-surface-alt: #4a5568;
                --color-text: #f7fafc;
                --color-text-muted: #cbd5e0;
                --color-border: #4a5568;
            }
        }
        ";

        wp_add_inline_style('aqualuxe-style', $custom_css);
    }

    /**
     * Add body class for dark mode
     *
     * @param array $classes Body classes
     * @return array
     */
    public function body_class($classes) {
        if (!get_theme_mod('aqualuxe_enable_dark_mode', true)) {
            return $classes;
        }

        $default_mode = get_theme_mod('aqualuxe_default_dark_mode', 'light');
        
        if ('dark' === $default_mode) {
            $classes[] = 'default-dark-mode';
        } elseif ('auto' === $default_mode) {
            $classes[] = 'auto-dark-mode';
        }

        $classes[] = 'dark-mode-enabled';

        return $classes;
    }

    /**
     * AJAX handler for dark mode toggle
     */
    public function ajax_toggle_dark_mode() {
        check_ajax_referer('aqualuxe_ajax_nonce', 'nonce');

        $mode = sanitize_text_field($_POST['mode']);
        
        if (!in_array($mode, array('light', 'dark'))) {
            wp_die('Invalid mode');
        }

        // You could save user preference to database here if needed
        // For now, we rely on localStorage in JavaScript

        wp_send_json_success(array(
            'mode' => $mode,
            'message' => sprintf(__('Switched to %s mode', 'aqualuxe'), $mode),
        ));
    }

    /**
     * Get dark mode toggle HTML
     *
     * @return string
     */
    public static function get_toggle_html() {
        if (!get_theme_mod('aqualuxe_enable_dark_mode', true)) {
            return '';
        }

        $output = '<button class="dark-mode-toggle" id="dark-mode-toggle" aria-label="' . esc_attr__('Toggle dark mode', 'aqualuxe') . '">';
        
        // Sun icon (light mode)
        $output .= '<svg class="sun-icon" fill="currentColor" viewBox="0 0 20 20">';
        $output .= '<path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd"></path>';
        $output .= '</svg>';
        
        // Moon icon (dark mode)
        $output .= '<svg class="moon-icon" fill="currentColor" viewBox="0 0 20 20">';
        $output .= '<path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>';
        $output .= '</svg>';
        
        $output .= '</button>';

        return $output;
    }
}