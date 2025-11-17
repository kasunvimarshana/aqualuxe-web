<?php
/**
 * AquaLuxe Dark Mode Module
 *
 * @package AquaLuxe
 * @version 1.0.0
 * @since   1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Dark Mode Module Class
 *
 * @class AquaLuxe_Dark_Mode
 */
class AquaLuxe_Dark_Mode {

    /**
     * Single instance of the class
     *
     * @var AquaLuxe_Dark_Mode
     */
    protected static $_instance = null;

    /**
     * Main AquaLuxe_Dark_Mode Instance
     *
     * @static
     * @return AquaLuxe_Dark_Mode - Main instance
     */
    public static function get_instance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Constructor
     */
    public function __construct() {
        $this->init_hooks();
    }

    /**
     * Hook into actions and filters
     */
    private function init_hooks() {
        // AJAX handlers
        add_action('wp_ajax_aqualuxe_save_dark_mode_preference', array($this, 'save_dark_mode_preference'));
        add_action('wp_ajax_nopriv_aqualuxe_save_dark_mode_preference', array($this, 'save_dark_mode_preference'));
        
        // Add dark mode class to body
        add_filter('body_class', array($this, 'add_dark_mode_body_class'));
        
        // Enqueue dark mode script
        add_action('wp_enqueue_scripts', array($this, 'enqueue_dark_mode_assets'));
        
        // Add customizer controls
        add_action('customize_register', array($this, 'add_customizer_controls'));
        
        // Add admin settings
        add_action('admin_init', array($this, 'register_admin_settings'));
        
        // Output dark mode toggle in header
        add_action('aqualuxe_header_actions', array($this, 'render_dark_mode_toggle'));
        
        // Add meta tags for dark mode
        add_action('wp_head', array($this, 'add_color_scheme_meta'), 1);
    }

    /**
     * Save dark mode preference
     */
    public function save_dark_mode_preference() {
        // Verify nonce
        if (!wp_verify_nonce($_POST['nonce'] ?? '', 'aqualuxe_nonce')) {
            wp_die(esc_html__('Security check failed.', 'aqualuxe'));
        }
        
        $enabled = isset($_POST['enabled']) && $_POST['enabled'] === 'true';
        
        // Save for logged-in users
        if (is_user_logged_in()) {
            update_user_meta(get_current_user_id(), 'aqualuxe_dark_mode', $enabled ? 'enabled' : 'disabled');
        }
        
        // Set cookie for non-logged-in users
        $cookie_value = $enabled ? 'enabled' : 'disabled';
        setcookie('aqualuxe_dark_mode', $cookie_value, time() + (365 * 24 * 60 * 60), '/');
        
        wp_send_json_success(array(
            'message' => esc_html__('Dark mode preference saved.', 'aqualuxe'),
            'enabled' => $enabled
        ));
    }

    /**
     * Get user's dark mode preference
     */
    public function get_dark_mode_preference() {
        // Check user meta for logged-in users
        if (is_user_logged_in()) {
            $user_preference = get_user_meta(get_current_user_id(), 'aqualuxe_dark_mode', true);
            if ($user_preference) {
                return $user_preference === 'enabled';
            }
        }
        
        // Check cookie
        if (isset($_COOKIE['aqualuxe_dark_mode'])) {
            return $_COOKIE['aqualuxe_dark_mode'] === 'enabled';
        }
        
        // Check theme default
        $default_mode = get_theme_mod('aqualuxe_default_dark_mode', 'auto');
        
        if ($default_mode === 'enabled') {
            return true;
        } elseif ($default_mode === 'disabled') {
            return false;
        }
        
        // Auto mode - check system preference via user agent (limited)
        return false; // Default to light mode if no preference found
    }

    /**
     * Add dark mode class to body
     */
    public function add_dark_mode_body_class($classes) {
        if ($this->get_dark_mode_preference()) {
            $classes[] = 'dark-mode';
        }
        
        return $classes;
    }

    /**
     * Enqueue dark mode assets
     */
    public function enqueue_dark_mode_assets() {
        // Dark mode JavaScript is already enqueued in main assets
        // Add any additional dark mode specific styles if needed
        
        // Localize script with current preference
        wp_localize_script('aqualuxe-dark-mode', 'aqualuxe_dark_mode', array(
            'enabled' => $this->get_dark_mode_preference(),
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('aqualuxe_nonce'),
            'strings' => array(
                'toggle_dark_mode' => esc_html__('Toggle dark mode', 'aqualuxe'),
                'dark_mode_on' => esc_html__('Dark mode enabled', 'aqualuxe'),
                'dark_mode_off' => esc_html__('Dark mode disabled', 'aqualuxe'),
            )
        ));
    }

    /**
     * Add customizer controls
     */
    public function add_customizer_controls($wp_customize) {
        // Dark Mode Section
        $wp_customize->add_section('aqualuxe_dark_mode', array(
            'title' => esc_html__('Dark Mode', 'aqualuxe'),
            'description' => esc_html__('Configure dark mode settings for your theme.', 'aqualuxe'),
            'priority' => 130,
        ));
        
        // Enable dark mode toggle
        $wp_customize->add_setting('aqualuxe_enable_dark_mode_toggle', array(
            'default' => true,
            'sanitize_callback' => 'wp_validate_boolean',
            'transport' => 'refresh',
        ));
        
        $wp_customize->add_control('aqualuxe_enable_dark_mode_toggle', array(
            'label' => esc_html__('Enable Dark Mode Toggle', 'aqualuxe'),
            'description' => esc_html__('Show dark mode toggle button in the header.', 'aqualuxe'),
            'section' => 'aqualuxe_dark_mode',
            'type' => 'checkbox',
        ));
        
        // Default dark mode
        $wp_customize->add_setting('aqualuxe_default_dark_mode', array(
            'default' => 'auto',
            'sanitize_callback' => array($this, 'sanitize_dark_mode_setting'),
            'transport' => 'refresh',
        ));
        
        $wp_customize->add_control('aqualuxe_default_dark_mode', array(
            'label' => esc_html__('Default Dark Mode', 'aqualuxe'),
            'description' => esc_html__('Set the default dark mode state for new visitors.', 'aqualuxe'),
            'section' => 'aqualuxe_dark_mode',
            'type' => 'select',
            'choices' => array(
                'auto' => esc_html__('Auto (System Preference)', 'aqualuxe'),
                'enabled' => esc_html__('Always Dark', 'aqualuxe'),
                'disabled' => esc_html__('Always Light', 'aqualuxe'),
            ),
        ));
        
        // Dark mode colors
        $wp_customize->add_setting('aqualuxe_dark_mode_bg_color', array(
            'default' => '#0f172a',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'postMessage',
        ));
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_dark_mode_bg_color', array(
            'label' => esc_html__('Dark Mode Background Color', 'aqualuxe'),
            'section' => 'aqualuxe_dark_mode',
        )));
        
        $wp_customize->add_setting('aqualuxe_dark_mode_text_color', array(
            'default' => '#f8fafc',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'postMessage',
        ));
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_dark_mode_text_color', array(
            'label' => esc_html__('Dark Mode Text Color', 'aqualuxe'),
            'section' => 'aqualuxe_dark_mode',
        )));
        
        // Auto-switch based on time
        $wp_customize->add_setting('aqualuxe_auto_dark_mode_time', array(
            'default' => false,
            'sanitize_callback' => 'wp_validate_boolean',
            'transport' => 'refresh',
        ));
        
        $wp_customize->add_control('aqualuxe_auto_dark_mode_time', array(
            'label' => esc_html__('Auto-Switch Based on Time', 'aqualuxe'),
            'description' => esc_html__('Automatically switch to dark mode during evening hours.', 'aqualuxe'),
            'section' => 'aqualuxe_dark_mode',
            'type' => 'checkbox',
        ));
        
        // Dark mode start time
        $wp_customize->add_setting('aqualuxe_dark_mode_start_time', array(
            'default' => '18:00',
            'sanitize_callback' => array($this, 'sanitize_time'),
            'transport' => 'refresh',
        ));
        
        $wp_customize->add_control('aqualuxe_dark_mode_start_time', array(
            'label' => esc_html__('Dark Mode Start Time', 'aqualuxe'),
            'section' => 'aqualuxe_dark_mode',
            'type' => 'time',
            'active_callback' => function() {
                return get_theme_mod('aqualuxe_auto_dark_mode_time', false);
            },
        ));
        
        // Dark mode end time
        $wp_customize->add_setting('aqualuxe_dark_mode_end_time', array(
            'default' => '06:00',
            'sanitize_callback' => array($this, 'sanitize_time'),
            'transport' => 'refresh',
        ));
        
        $wp_customize->add_control('aqualuxe_dark_mode_end_time', array(
            'label' => esc_html__('Dark Mode End Time', 'aqualuxe'),
            'section' => 'aqualuxe_dark_mode',
            'type' => 'time',
            'active_callback' => function() {
                return get_theme_mod('aqualuxe_auto_dark_mode_time', false);
            },
        ));
    }

    /**
     * Register admin settings
     */
    public function register_admin_settings() {
        register_setting('aqualuxe_dark_mode_settings', 'aqualuxe_dark_mode_options', array(
            'sanitize_callback' => array($this, 'sanitize_dark_mode_options')
        ));
    }

    /**
     * Render dark mode toggle button
     */
    public function render_dark_mode_toggle() {
        if (!get_theme_mod('aqualuxe_enable_dark_mode_toggle', true)) {
            return;
        }
        
        $is_enabled = $this->get_dark_mode_preference();
        $aria_pressed = $is_enabled ? 'true' : 'false';
        ?>
        <button 
            id="dark-mode-toggle" 
            class="dark-mode-toggle inline-flex items-center justify-center w-10 h-10 rounded-lg text-gray-600 hover:text-aqua-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-aqua-400 dark:hover:bg-gray-800 transition-colors duration-200" 
            aria-pressed="<?php echo esc_attr($aria_pressed); ?>"
            aria-label="<?php esc_attr_e('Toggle dark mode', 'aqualuxe'); ?>"
            title="<?php esc_attr_e('Toggle dark mode', 'aqualuxe'); ?>"
        >
            <!-- Light mode icon (sun) -->
            <svg class="w-5 h-5 <?php echo $is_enabled ? 'hidden' : ''; ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
            </svg>
            
            <!-- Dark mode icon (moon) -->
            <svg class="w-5 h-5 <?php echo !$is_enabled ? 'hidden' : ''; ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
            </svg>
        </button>
        <?php
    }

    /**
     * Add color scheme meta tags
     */
    public function add_color_scheme_meta() {
        $default_mode = get_theme_mod('aqualuxe_default_dark_mode', 'auto');
        
        if ($default_mode === 'auto') {
            echo '<meta name="color-scheme" content="light dark">' . "\n";
        } elseif ($default_mode === 'enabled') {
            echo '<meta name="color-scheme" content="dark">' . "\n";
        } else {
            echo '<meta name="color-scheme" content="light">' . "\n";
        }
        
        // Theme color meta tag
        $theme_color = $this->get_dark_mode_preference() ? '#0f172a' : '#ffffff';
        echo '<meta name="theme-color" content="' . esc_attr($theme_color) . '">' . "\n";
        
        // Add CSS custom properties for dark mode colors
        $this->output_dark_mode_css_variables();
    }

    /**
     * Output CSS custom properties for dark mode
     */
    private function output_dark_mode_css_variables() {
        $bg_color = get_theme_mod('aqualuxe_dark_mode_bg_color', '#0f172a');
        $text_color = get_theme_mod('aqualuxe_dark_mode_text_color', '#f8fafc');
        
        echo '<style id="aqualuxe-dark-mode-vars">' . "\n";
        echo ':root {' . "\n";
        echo '  --dark-mode-bg: ' . esc_attr($bg_color) . ';' . "\n";
        echo '  --dark-mode-text: ' . esc_attr($text_color) . ';' . "\n";
        echo '}' . "\n";
        echo '.dark, .dark-mode {' . "\n";
        echo '  background-color: var(--dark-mode-bg);' . "\n";
        echo '  color: var(--dark-mode-text);' . "\n";
        echo '}' . "\n";
        echo '</style>' . "\n";
    }

    /**
     * Check if it's dark mode time
     */
    public function is_dark_mode_time() {
        if (!get_theme_mod('aqualuxe_auto_dark_mode_time', false)) {
            return false;
        }
        
        $start_time = get_theme_mod('aqualuxe_dark_mode_start_time', '18:00');
        $end_time = get_theme_mod('aqualuxe_dark_mode_end_time', '06:00');
        
        $current_time = current_time('H:i');
        
        // Handle overnight periods (e.g., 18:00 to 06:00)
        if ($start_time > $end_time) {
            return ($current_time >= $start_time || $current_time <= $end_time);
        }
        
        // Handle same-day periods (e.g., 12:00 to 14:00)
        return ($current_time >= $start_time && $current_time <= $end_time);
    }

    /**
     * Get dark mode status for current user/visitor
     */
    public function is_dark_mode_active() {
        // Check user preference first
        $user_preference = $this->get_dark_mode_preference();
        if ($user_preference !== null) {
            return $user_preference;
        }
        
        // Check time-based auto-switch
        if ($this->is_dark_mode_time()) {
            return true;
        }
        
        // Default behavior
        $default_mode = get_theme_mod('aqualuxe_default_dark_mode', 'auto');
        
        if ($default_mode === 'enabled') {
            return true;
        } elseif ($default_mode === 'disabled') {
            return false;
        }
        
        // Auto mode - could implement system preference detection here
        return false;
    }

    /**
     * Sanitize dark mode setting
     */
    public function sanitize_dark_mode_setting($input) {
        $valid_values = array('auto', 'enabled', 'disabled');
        return in_array($input, $valid_values) ? $input : 'auto';
    }

    /**
     * Sanitize time input
     */
    public function sanitize_time($input) {
        if (preg_match('/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/', $input)) {
            return $input;
        }
        return '18:00';
    }

    /**
     * Sanitize dark mode options
     */
    public function sanitize_dark_mode_options($input) {
        $sanitized = array();
        
        if (isset($input['enable_toggle'])) {
            $sanitized['enable_toggle'] = wp_validate_boolean($input['enable_toggle']);
        }
        
        if (isset($input['default_mode'])) {
            $sanitized['default_mode'] = $this->sanitize_dark_mode_setting($input['default_mode']);
        }
        
        if (isset($input['auto_time'])) {
            $sanitized['auto_time'] = wp_validate_boolean($input['auto_time']);
        }
        
        if (isset($input['start_time'])) {
            $sanitized['start_time'] = $this->sanitize_time($input['start_time']);
        }
        
        if (isset($input['end_time'])) {
            $sanitized['end_time'] = $this->sanitize_time($input['end_time']);
        }
        
        return $sanitized;
    }

    /**
     * Get dark mode statistics (for admin)
     */
    public function get_dark_mode_stats() {
        if (!current_user_can('manage_options')) {
            return array();
        }
        
        global $wpdb;
        
        // Count users who prefer dark mode
        $dark_mode_users = $wpdb->get_var(
            "SELECT COUNT(*) FROM {$wpdb->usermeta} WHERE meta_key = 'aqualuxe_dark_mode' AND meta_value = 'enabled'"
        );
        
        $light_mode_users = $wpdb->get_var(
            "SELECT COUNT(*) FROM {$wpdb->usermeta} WHERE meta_key = 'aqualuxe_dark_mode' AND meta_value = 'disabled'"
        );
        
        $total_users_with_preference = $dark_mode_users + $light_mode_users;
        
        return array(
            'dark_mode_users' => (int) $dark_mode_users,
            'light_mode_users' => (int) $light_mode_users,
            'total_with_preference' => (int) $total_users_with_preference,
            'dark_mode_percentage' => $total_users_with_preference > 0 ? round(($dark_mode_users / $total_users_with_preference) * 100, 1) : 0,
        );
    }
}