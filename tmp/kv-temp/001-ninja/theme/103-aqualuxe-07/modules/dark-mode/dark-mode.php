<?php
/**
 * Dark Mode Module
 * 
 * @package AquaLuxe
 * @version 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * AquaLuxe Dark Mode Module
 */
class AquaLuxe_Dark_Mode {
    
    /**
     * Constructor
     */
    public function __construct() {
        $this->init_hooks();
    }
    
    /**
     * Initialize hooks
     */
    private function init_hooks() {
        add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);
        add_action('wp_head', [$this, 'add_dark_mode_script'], 1);
        add_action('customize_register', [$this, 'add_customizer_settings']);
        add_action('wp_ajax_aqualuxe_toggle_dark_mode', [$this, 'ajax_toggle_dark_mode']);
        add_action('wp_ajax_nopriv_aqualuxe_toggle_dark_mode', [$this, 'ajax_toggle_dark_mode']);
        
        // Add body class for dark mode
        add_filter('body_class', [$this, 'add_body_class']);
    }
    
    /**
     * Enqueue dark mode scripts
     */
    public function enqueue_scripts() {
        // Dark mode toggle script is included in main.js
        // Additional styles are handled via Tailwind CSS classes
    }
    
    /**
     * Add dark mode initialization script to head
     */
    public function add_dark_mode_script() {
        $default_dark = get_theme_mod('aqualuxe_dark_mode_default', false);
        $auto_switch = get_theme_mod('aqualuxe_dark_mode_auto_switch', false);
        ?>
        <script>
        (function() {
            // Check for saved theme preference or default to light mode
            const savedTheme = localStorage.getItem('aqualuxe-theme');
            const defaultDark = <?php echo $default_dark ? 'true' : 'false'; ?>;
            const autoSwitch = <?php echo $auto_switch ? 'true' : 'false'; ?>;
            
            let theme = savedTheme;
            
            // If no saved preference, use default or auto-detect
            if (!theme) {
                if (autoSwitch && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                    theme = 'dark';
                } else if (defaultDark) {
                    theme = 'dark';
                } else {
                    theme = 'light';
                }
            }
            
            // Apply theme immediately to prevent flash
            if (theme === 'dark') {
                document.documentElement.classList.add('dark');
            }
            
            // Listen for system theme changes if auto-switch is enabled
            if (autoSwitch && !savedTheme) {
                const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
                mediaQuery.addListener(function(e) {
                    const newTheme = e.matches ? 'dark' : 'light';
                    if (newTheme === 'dark') {
                        document.documentElement.classList.add('dark');
                    } else {
                        document.documentElement.classList.remove('dark');
                    }
                    localStorage.setItem('aqualuxe-theme', newTheme);
                });
            }
        })();
        </script>
        <?php
    }
    
    /**
     * Add customizer settings for dark mode
     */
    public function add_customizer_settings($wp_customize) {
        // Add dark mode section
        $wp_customize->add_section('aqualuxe_dark_mode', [
            'title'       => esc_html__('Dark Mode', 'aqualuxe'),
            'description' => esc_html__('Configure dark mode settings for your theme.', 'aqualuxe'),
            'priority'    => 40,
        ]);
        
        // Enable dark mode
        $wp_customize->add_setting('aqualuxe_enable_dark_mode', [
            'default'           => true,
            'sanitize_callback' => 'rest_sanitize_boolean',
            'transport'         => 'refresh',
        ]);
        
        $wp_customize->add_control('aqualuxe_enable_dark_mode', [
            'label'       => esc_html__('Enable Dark Mode', 'aqualuxe'),
            'description' => esc_html__('Allow users to toggle between light and dark themes.', 'aqualuxe'),
            'section'     => 'aqualuxe_dark_mode',
            'type'        => 'checkbox',
        ]);
        
        // Default to dark mode
        $wp_customize->add_setting('aqualuxe_dark_mode_default', [
            'default'           => false,
            'sanitize_callback' => 'rest_sanitize_boolean',
            'transport'         => 'refresh',
        ]);
        
        $wp_customize->add_control('aqualuxe_dark_mode_default', [
            'label'       => esc_html__('Default to Dark Mode', 'aqualuxe'),
            'description' => esc_html__('Set dark mode as the default theme for new visitors.', 'aqualuxe'),
            'section'     => 'aqualuxe_dark_mode',
            'type'        => 'checkbox',
        ]);
        
        // Auto-switch based on system preference
        $wp_customize->add_setting('aqualuxe_dark_mode_auto_switch', [
            'default'           => true,
            'sanitize_callback' => 'rest_sanitize_boolean',
            'transport'         => 'refresh',
        ]);
        
        $wp_customize->add_control('aqualuxe_dark_mode_auto_switch', [
            'label'       => esc_html__('Auto-detect System Preference', 'aqualuxe'),
            'description' => esc_html__('Automatically switch to dark mode based on user\'s system preference.', 'aqualuxe'),
            'section'     => 'aqualuxe_dark_mode',
            'type'        => 'checkbox',
        ]);
    }
    
    /**
     * AJAX handler for dark mode toggle
     */
    public function ajax_toggle_dark_mode() {
        // Verify nonce
        if (!wp_verify_nonce($_POST['nonce'] ?? '', 'aqualuxe_nonce')) {
            wp_die('Security check failed');
        }
        
        $user_id = get_current_user_id();
        $theme = sanitize_text_field($_POST['theme'] ?? '');
        
        if (!in_array($theme, ['light', 'dark'])) {
            wp_send_json_error(['message' => 'Invalid theme']);
        }
        
        // Save preference for logged-in users
        if ($user_id) {
            update_user_meta($user_id, 'aqualuxe_theme_preference', $theme);
        }
        
        wp_send_json_success([
            'theme' => $theme,
            'message' => sprintf(
                esc_html__('Switched to %s mode', 'aqualuxe'),
                $theme
            )
        ]);
    }
    
    /**
     * Add body class for dark mode
     */
    public function add_body_class($classes) {
        if (get_theme_mod('aqualuxe_enable_dark_mode', true)) {
            $classes[] = 'dark-mode-enabled';
            
            // Check if user prefers dark mode
            $user_id = get_current_user_id();
            if ($user_id) {
                $preference = get_user_meta($user_id, 'aqualuxe_theme_preference', true);
                if ($preference === 'dark') {
                    $classes[] = 'dark-mode-active';
                }
            } elseif (get_theme_mod('aqualuxe_dark_mode_default', false)) {
                $classes[] = 'dark-mode-default';
            }
        }
        
        return $classes;
    }
    
    /**
     * Get dark mode status
     */
    public static function is_enabled() {
        return get_theme_mod('aqualuxe_enable_dark_mode', true);
    }
    
    /**
     * Render dark mode toggle button
     */
    public static function render_toggle_button($echo = true) {
        if (!self::is_enabled()) {
            return '';
        }
        
        $button = '<button type="button" class="dark-mode-toggle p-2 text-neutral-600 hover:text-primary-600 transition-all duration-200 dark:text-neutral-300 dark:hover:text-primary-400" data-dark-mode-toggle aria-label="' . esc_attr__('Toggle Dark Mode', 'aqualuxe') . '">';
        $button .= '<svg class="w-5 h-5 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">';
        $button .= '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>';
        $button .= '</svg>';
        $button .= '</button>';
        
        if ($echo) {
            echo $button;
        } else {
            return $button;
        }
    }
}

// Initialize dark mode module
if (get_theme_mod('aqualuxe_enable_dark_mode', true)) {
    new AquaLuxe_Dark_Mode();
}