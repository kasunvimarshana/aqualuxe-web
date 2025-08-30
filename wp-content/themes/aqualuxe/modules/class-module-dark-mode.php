<?php
/**
 * Dark Mode Module
 * 
 * @package AquaLuxe
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Dark Mode module class
 */
class Module_Dark_Mode {
    
    /**
     * Instance of this class
     */
    private static $instance = null;
    
    /**
     * Module enabled
     */
    private $enabled = true;
    
    /**
     * Constructor
     */
    private function __construct() {
        $this->enabled = get_theme_mod('aqualuxe_enable_dark_mode', true);
        
        if ($this->enabled) {
            $this->init_hooks();
        }
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
     * Initialize hooks
     */
    private function init_hooks() {
        // Add body class for dark mode
        add_filter('body_class', [$this, 'add_body_class']);
        
        // Enqueue dark mode assets
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
        
        // Add dark mode toggle to header
        add_action('wp_head', [$this, 'add_inline_script'], 1);
        
        // Add customizer controls
        add_action('customize_register', [$this, 'add_customizer_controls']);
        
        // Ajax handlers
        add_action('wp_ajax_aqualuxe_toggle_dark_mode', [$this, 'ajax_toggle_dark_mode']);
        add_action('wp_ajax_nopriv_aqualuxe_toggle_dark_mode', [$this, 'ajax_toggle_dark_mode']);
    }
    
    /**
     * Add dark mode body class
     */
    public function add_body_class($classes) {
        $classes[] = 'has-dark-mode';
        
        // Add dark class based on user preference
        if ($this->is_dark_mode_preferred()) {
            $classes[] = 'dark';
        }
        
        return $classes;
    }
    
    /**
     * Enqueue dark mode assets
     */
    public function enqueue_assets() {
        // Dark mode CSS variables
        $dark_mode_css = "
            :root {
                --color-bg-light: #ffffff;
                --color-bg-dark: #1a1a1a;
                --color-text-light: #333333;
                --color-text-dark: #f0f0f0;
                --color-border-light: #e5e5e5;
                --color-border-dark: #404040;
            }
            
            .dark {
                --color-bg: var(--color-bg-dark);
                --color-text: var(--color-text-dark);
                --color-border: var(--color-border-dark);
            }
            
            :not(.dark) {
                --color-bg: var(--color-bg-light);
                --color-text: var(--color-text-light);
                --color-border: var(--color-border-light);
            }
            
            /* Dark mode toggle button */
            .dark-mode-toggle {
                position: relative;
                width: 44px;
                height: 24px;
                background: var(--color-border);
                border-radius: 12px;
                border: none;
                cursor: pointer;
                transition: all 0.3s ease;
                overflow: hidden;
            }
            
            .dark-mode-toggle:before {
                content: '';
                position: absolute;
                top: 2px;
                left: 2px;
                width: 20px;
                height: 20px;
                background: white;
                border-radius: 50%;
                transition: transform 0.3s ease;
                box-shadow: 0 2px 4px rgba(0,0,0,0.2);
            }
            
            .dark .dark-mode-toggle {
                background: #4ade80;
            }
            
            .dark .dark-mode-toggle:before {
                transform: translateX(20px);
            }
            
            .dark-mode-toggle-icon {
                position: absolute;
                top: 50%;
                transform: translateY(-50%);
                width: 14px;
                height: 14px;
                transition: opacity 0.3s ease;
            }
            
            .dark-mode-toggle-icon.sun {
                left: 4px;
                opacity: 1;
            }
            
            .dark-mode-toggle-icon.moon {
                right: 4px;
                opacity: 0;
            }
            
            .dark .dark-mode-toggle-icon.sun {
                opacity: 0;
            }
            
            .dark .dark-mode-toggle-icon.moon {
                opacity: 1;
            }
            
            /* Smooth transitions for dark mode */
            * {
                transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
            }
            
            /* Preserve user's color scheme preference */
            @media (prefers-color-scheme: dark) {
                :root:not([data-theme]) {
                    --color-bg: var(--color-bg-dark);
                    --color-text: var(--color-text-dark);
                    --color-border: var(--color-border-dark);
                }
            }
        ";
        
        wp_add_inline_style('aqualuxe-style', $dark_mode_css);
        
        // Dark mode JavaScript
        wp_enqueue_script(
            'aqualuxe-dark-mode',
            AQUALUXE_THEME_URL . '/modules/dark-mode/assets/dark-mode.js',
            ['jquery'],
            AQUALUXE_VERSION,
            true
        );
        
        wp_localize_script('aqualuxe-dark-mode', 'aqualuxeDarkMode', [
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('aqualuxe_dark_mode_nonce'),
            'enabled' => $this->enabled,
            'autoDetect' => get_theme_mod('aqualuxe_dark_mode_auto_detect', true),
            'persistence' => get_theme_mod('aqualuxe_dark_mode_persistence', true),
        ]);
    }
    
    /**
     * Add inline script for immediate dark mode application
     */
    public function add_inline_script() {
        if (!$this->enabled) {
            return;
        }
        
        echo '<script>
(function() {
    "use strict";
    
    // Check for saved preference or system preference
    const darkModePreference = localStorage.getItem("aqualuxe-dark-mode");
    const systemPrefersDark = window.matchMedia("(prefers-color-scheme: dark)").matches;
    const autoDetect = ' . (get_theme_mod('aqualuxe_dark_mode_auto_detect', true) ? 'true' : 'false') . ';
    
    let isDarkMode = false;
    
    if (darkModePreference !== null) {
        // User has a saved preference
        isDarkMode = darkModePreference === "true";
    } else if (autoDetect) {
        // Auto-detect based on system preference
        isDarkMode = systemPrefersDark;
    }
    
    // Apply dark mode immediately to prevent flash
    if (isDarkMode) {
        document.documentElement.classList.add("dark");
        document.documentElement.setAttribute("data-theme", "dark");
    } else {
        document.documentElement.classList.remove("dark");
        document.documentElement.setAttribute("data-theme", "light");
    }
    
    // Listen for system theme changes
    if (autoDetect && darkModePreference === null) {
        window.matchMedia("(prefers-color-scheme: dark)").addEventListener("change", function(e) {
            if (localStorage.getItem("aqualuxe-dark-mode") === null) {
                if (e.matches) {
                    document.documentElement.classList.add("dark");
                    document.documentElement.setAttribute("data-theme", "dark");
                } else {
                    document.documentElement.classList.remove("dark");
                    document.documentElement.setAttribute("data-theme", "light");
                }
            }
        });
    }
})();
</script>';
    }
    
    /**
     * Add customizer controls
     */
    public function add_customizer_controls($wp_customize) {
        // Dark Mode Section
        $wp_customize->add_section('aqualuxe_dark_mode', [
            'title' => __('Dark Mode', 'aqualuxe'),
            'description' => __('Configure dark mode settings for your theme.', 'aqualuxe'),
            'priority' => 30,
        ]);
        
        // Enable Dark Mode
        $wp_customize->add_setting('aqualuxe_enable_dark_mode', [
            'default' => true,
            'sanitize_callback' => 'wp_validate_boolean',
            'transport' => 'refresh',
        ]);
        
        $wp_customize->add_control('aqualuxe_enable_dark_mode', [
            'label' => __('Enable Dark Mode', 'aqualuxe'),
            'description' => __('Allow users to switch between light and dark themes.', 'aqualuxe'),
            'section' => 'aqualuxe_dark_mode',
            'type' => 'checkbox',
        ]);
        
        // Auto-detect system preference
        $wp_customize->add_setting('aqualuxe_dark_mode_auto_detect', [
            'default' => true,
            'sanitize_callback' => 'wp_validate_boolean',
        ]);
        
        $wp_customize->add_control('aqualuxe_dark_mode_auto_detect', [
            'label' => __('Auto-detect System Preference', 'aqualuxe'),
            'description' => __('Automatically use the user\'s system dark mode preference.', 'aqualuxe'),
            'section' => 'aqualuxe_dark_mode',
            'type' => 'checkbox',
        ]);
        
        // Remember user preference
        $wp_customize->add_setting('aqualuxe_dark_mode_persistence', [
            'default' => true,
            'sanitize_callback' => 'wp_validate_boolean',
        ]);
        
        $wp_customize->add_control('aqualuxe_dark_mode_persistence', [
            'label' => __('Remember User Preference', 'aqualuxe'),
            'description' => __('Save the user\'s dark mode preference in localStorage.', 'aqualuxe'),
            'section' => 'aqualuxe_dark_mode',
            'type' => 'checkbox',
        ]);
        
        // Default mode
        $wp_customize->add_setting('aqualuxe_dark_mode_default', [
            'default' => 'auto',
            'sanitize_callback' => 'sanitize_text_field',
        ]);
        
        $wp_customize->add_control('aqualuxe_dark_mode_default', [
            'label' => __('Default Mode', 'aqualuxe'),
            'description' => __('Choose the default theme mode for new visitors.', 'aqualuxe'),
            'section' => 'aqualuxe_dark_mode',
            'type' => 'select',
            'choices' => [
                'auto' => __('Auto (System Preference)', 'aqualuxe'),
                'light' => __('Light Mode', 'aqualuxe'),
                'dark' => __('Dark Mode', 'aqualuxe'),
            ],
        ]);
        
        // Dark mode colors
        $wp_customize->add_setting('aqualuxe_dark_bg_color', [
            'default' => '#1a1a1a',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_dark_bg_color', [
            'label' => __('Dark Background Color', 'aqualuxe'),
            'section' => 'aqualuxe_dark_mode',
        ]));
        
        $wp_customize->add_setting('aqualuxe_dark_text_color', [
            'default' => '#f0f0f0',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_dark_text_color', [
            'label' => __('Dark Text Color', 'aqualuxe'),
            'section' => 'aqualuxe_dark_mode',
        ]));
    }
    
    /**
     * Ajax toggle dark mode
     */
    public function ajax_toggle_dark_mode() {
        check_ajax_referer('aqualuxe_dark_mode_nonce', 'nonce');
        
        $is_dark = isset($_POST['is_dark']) ? wp_validate_boolean($_POST['is_dark']) : false;
        
        // Store user preference if persistence is enabled
        if (get_theme_mod('aqualuxe_dark_mode_persistence', true)) {
            // This would typically be stored in user meta or session
            // For now, we'll rely on localStorage on the frontend
        }
        
        wp_send_json_success([
            'is_dark' => $is_dark,
            'message' => $is_dark ? __('Dark mode enabled', 'aqualuxe') : __('Light mode enabled', 'aqualuxe')
        ]);
    }
    
    /**
     * Get dark mode toggle button HTML
     */
    public function get_toggle_button($args = []) {
        if (!$this->enabled) {
            return '';
        }
        
        $defaults = [
            'show_labels' => false,
            'button_class' => 'dark-mode-toggle',
            'wrapper_class' => 'dark-mode-toggle-wrapper',
            'position' => 'header',
        ];
        
        $args = wp_parse_args($args, $defaults);
        
        $output = '<div class="' . esc_attr($args['wrapper_class']) . '">';
        
        if ($args['show_labels']) {
            $output .= '<span class="dark-mode-label light-label">' . __('Light', 'aqualuxe') . '</span>';
        }
        
        $output .= '<button type="button" class="' . esc_attr($args['button_class']) . '" 
                    data-toggle="dark-mode" 
                    aria-label="' . esc_attr__('Toggle dark mode', 'aqualuxe') . '"
                    title="' . esc_attr__('Toggle dark mode', 'aqualuxe') . '">';
        
        // Sun icon
        $output .= '<svg class="dark-mode-toggle-icon sun" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd"></path>
                    </svg>';
        
        // Moon icon
        $output .= '<svg class="dark-mode-toggle-icon moon" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                    </svg>';
        
        $output .= '</button>';
        
        if ($args['show_labels']) {
            $output .= '<span class="dark-mode-label dark-label">' . __('Dark', 'aqualuxe') . '</span>';
        }
        
        $output .= '</div>';
        
        return $output;
    }
    
    /**
     * Check if user prefers dark mode
     */
    private function is_dark_mode_preferred() {
        $default_mode = get_theme_mod('aqualuxe_dark_mode_default', 'auto');
        
        if ($default_mode === 'dark') {
            return true;
        } elseif ($default_mode === 'light') {
            return false;
        }
        
        // Auto mode - this would be handled by JavaScript
        return false;
    }
    
    /**
     * Public methods
     */
    
    /**
     * Check if dark mode is enabled
     */
    public function is_enabled() {
        return $this->enabled;
    }
    
    /**
     * Get dark mode CSS custom properties
     */
    public function get_css_properties() {
        return [
            '--dark-bg-color' => get_theme_mod('aqualuxe_dark_bg_color', '#1a1a1a'),
            '--dark-text-color' => get_theme_mod('aqualuxe_dark_text_color', '#f0f0f0'),
            '--dark-border-color' => get_theme_mod('aqualuxe_dark_border_color', '#404040'),
        ];
    }
}
