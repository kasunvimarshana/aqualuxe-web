<?php
/**
 * Dark Mode Module
 *
 * Implements dark mode functionality with persistent user preference
 *
 * @package AquaLuxe\Modules\DarkMode
 * @version 1.0.0
 */

namespace AquaLuxe\Modules\DarkMode;

use AquaLuxe\Core\Abstracts\AbstractModule;

/**
 * Dark Mode Module Class
 */
class DarkMode extends AbstractModule {
    
    /**
     * Module version
     *
     * @var string
     */
    protected $version = '1.0.0';
    
    /**
     * Initialize the module
     *
     * @return void
     */
    public function init(): void {
        if (!$this->isEnabled()) {
            return;
        }
        
        $this->register_hooks();
        $this->on_activate();
    }
    
    /**
     * Register module hooks
     *
     * @return void
     */
    protected function register_hooks(): void {
        // Enqueue dark mode scripts and styles
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
        
        // Add dark mode body class
        add_filter('body_class', [$this, 'add_body_class']);
        
        // Add customizer settings
        add_action('customize_register', [$this, 'add_customizer_settings']);
        
        // Add AJAX handlers
        add_action('wp_ajax_toggle_dark_mode', [$this, 'ajax_toggle_dark_mode']);
        add_action('wp_ajax_nopriv_toggle_dark_mode', [$this, 'ajax_toggle_dark_mode']);
        
        // Add admin bar item
        add_action('wp_before_admin_bar_render', [$this, 'add_admin_bar_item']);
        
        // Add theme customizer live preview
        add_action('customize_preview_init', [$this, 'customizer_preview']);
    }
    
    /**
     * Enqueue dark mode assets
     *
     * @return void
     */
    public function enqueue_assets(): void {
        // Enqueue dark mode CSS
        wp_enqueue_style(
            'aqualuxe-dark-mode',
            aqualuxe_asset('/css/dark-mode.css'),
            ['aqualuxe-style'],
            AQUALUXE_VERSION
        );
        
        // Enqueue dark mode JavaScript
        wp_enqueue_script(
            'aqualuxe-dark-mode',
            aqualuxe_asset('/js/dark-mode.js'),
            ['aqualuxe-script'],
            AQUALUXE_VERSION,
            true
        );
        
        // Localize dark mode script
        wp_localize_script('aqualuxe-dark-mode', 'aqualuxeDarkMode', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('dark_mode_nonce'),
            'enabled' => $this->is_dark_mode_enabled(),
            'user_preference' => $this->get_user_preference(),
            'system_preference' => $this->get_system_preference(),
            'strings' => [
                'light_mode' => __('Switch to Light Mode', 'aqualuxe'),
                'dark_mode' => __('Switch to Dark Mode', 'aqualuxe'),
                'auto_mode' => __('Auto (System)', 'aqualuxe'),
            ],
        ]);
    }
    
    /**
     * Add dark mode body class
     *
     * @param array $classes
     * @return array
     */
    public function add_body_class($classes): array {
        if ($this->should_enable_dark_mode()) {
            $classes[] = 'dark-mode';
        }
        
        $classes[] = 'dark-mode-available';
        
        return $classes;
    }
    
    /**
     * Add customizer settings
     *
     * @param \WP_Customize_Manager $wp_customize
     * @return void
     */
    public function add_customizer_settings($wp_customize): void {
        // Dark Mode Section
        $wp_customize->add_section('aqualuxe_dark_mode_module', [
            'title' => __('Dark Mode Settings', 'aqualuxe'),
            'panel' => 'aqualuxe_theme_options',
            'priority' => 35,
        ]);
        
        // Enable Dark Mode Toggle
        $wp_customize->add_setting('dark_mode_toggle_enabled', [
            'default' => true,
            'sanitize_callback' => 'wp_validate_boolean',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control('dark_mode_toggle_enabled', [
            'label' => __('Enable Dark Mode Toggle', 'aqualuxe'),
            'description' => __('Show dark mode toggle button to users', 'aqualuxe'),
            'section' => 'aqualuxe_dark_mode_module',
            'type' => 'checkbox',
        ]);
        
        // Default Mode
        $wp_customize->add_setting('dark_mode_default', [
            'default' => 'auto',
            'sanitize_callback' => [$this, 'sanitize_mode_choice'],
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control('dark_mode_default', [
            'label' => __('Default Mode', 'aqualuxe'),
            'description' => __('Default theme mode for new visitors', 'aqualuxe'),
            'section' => 'aqualuxe_dark_mode_module',
            'type' => 'select',
            'choices' => [
                'light' => __('Light Mode', 'aqualuxe'),
                'dark' => __('Dark Mode', 'aqualuxe'),
                'auto' => __('Auto (System Preference)', 'aqualuxe'),
            ],
        ]);
        
        // Toggle Position
        $wp_customize->add_setting('dark_mode_toggle_position', [
            'default' => 'header',
            'sanitize_callback' => [$this, 'sanitize_position_choice'],
        ]);
        
        $wp_customize->add_control('dark_mode_toggle_position', [
            'label' => __('Toggle Position', 'aqualuxe'),
            'section' => 'aqualuxe_dark_mode_module',
            'type' => 'select',
            'choices' => [
                'header' => __('Header', 'aqualuxe'),
                'footer' => __('Footer', 'aqualuxe'),
                'floating' => __('Floating Button', 'aqualuxe'),
                'admin_bar' => __('Admin Bar Only', 'aqualuxe'),
            ],
        ]);
        
        // Transition Duration
        $wp_customize->add_setting('dark_mode_transition_duration', [
            'default' => 300,
            'sanitize_callback' => 'absint',
        ]);
        
        $wp_customize->add_control('dark_mode_transition_duration', [
            'label' => __('Transition Duration (ms)', 'aqualuxe'),
            'section' => 'aqualuxe_dark_mode_module',
            'type' => 'range',
            'input_attrs' => [
                'min' => 0,
                'max' => 1000,
                'step' => 50,
            ],
        ]);
    }
    
    /**
     * AJAX handler for dark mode toggle
     *
     * @return void
     */
    public function ajax_toggle_dark_mode(): void {
        // Verify nonce
        if (!wp_verify_nonce($_POST['nonce'] ?? '', 'dark_mode_nonce')) {
            wp_die(__('Security check failed', 'aqualuxe'));
        }
        
        $mode = sanitize_text_field($_POST['mode'] ?? '');
        
        if (!in_array($mode, ['light', 'dark', 'auto'])) {
            wp_send_json_error(__('Invalid mode', 'aqualuxe'));
        }
        
        // Save user preference
        $this->save_user_preference($mode);
        
        wp_send_json_success([
            'mode' => $mode,
            'message' => sprintf(__('Switched to %s mode', 'aqualuxe'), $mode),
        ]);
    }
    
    /**
     * Add admin bar item
     *
     * @return void
     */
    public function add_admin_bar_item(): void {
        if (!is_admin_bar_showing() || !get_theme_mod('dark_mode_toggle_enabled', true)) {
            return;
        }
        
        global $wp_admin_bar;
        
        $current_mode = $this->get_current_mode();
        $next_mode = $current_mode === 'dark' ? 'light' : 'dark';
        
        $wp_admin_bar->add_menu([
            'id' => 'aqualuxe-dark-mode',
            'title' => sprintf(
                '<span class="ab-icon dashicons dashicons-%s"></span><span class="ab-label">%s</span>',
                $current_mode === 'dark' ? 'lightbulb' : 'moon',
                $current_mode === 'dark' ? __('Light Mode', 'aqualuxe') : __('Dark Mode', 'aqualuxe')
            ),
            'href' => '#',
            'meta' => [
                'class' => 'aqualuxe-dark-mode-toggle',
                'data-mode' => $next_mode,
            ],
        ]);
    }
    
    /**
     * Customizer preview scripts
     *
     * @return void
     */
    public function customizer_preview(): void {
        wp_enqueue_script(
            'aqualuxe-dark-mode-preview',
            aqualuxe_asset('/js/dark-mode-preview.js'),
            ['customize-preview'],
            AQUALUXE_VERSION,
            true
        );
    }
    
    /**
     * Check if dark mode should be enabled
     *
     * @return bool
     */
    private function should_enable_dark_mode(): bool {
        $mode = $this->get_current_mode();
        
        if ($mode === 'dark') {
            return true;
        }
        
        if ($mode === 'auto') {
            return $this->get_system_preference() === 'dark';
        }
        
        return false;
    }
    
    /**
     * Get current mode
     *
     * @return string
     */
    private function get_current_mode(): string {
        $user_preference = $this->get_user_preference();
        
        if ($user_preference) {
            return $user_preference;
        }
        
        return get_theme_mod('dark_mode_default', 'auto');
    }
    
    /**
     * Get user preference from cookie or user meta
     *
     * @return string|null
     */
    private function get_user_preference(): ?string {
        // For logged-in users, check user meta
        if (is_user_logged_in()) {
            $preference = get_user_meta(get_current_user_id(), 'aqualuxe_dark_mode_preference', true);
            if ($preference) {
                return $preference;
            }
        }
        
        // For guests, check cookie
        if (isset($_COOKIE['aqualuxe_dark_mode'])) {
            return sanitize_text_field($_COOKIE['aqualuxe_dark_mode']);
        }
        
        return null;
    }
    
    /**
     * Save user preference
     *
     * @param string $mode
     * @return void
     */
    private function save_user_preference($mode): void {
        // For logged-in users, save to user meta
        if (is_user_logged_in()) {
            update_user_meta(get_current_user_id(), 'aqualuxe_dark_mode_preference', $mode);
        }
        
        // Always set cookie for cross-session persistence
        setcookie(
            'aqualuxe_dark_mode',
            $mode,
            time() + (365 * 24 * 60 * 60), // 1 year
            COOKIEPATH,
            COOKIE_DOMAIN,
            is_ssl(),
            true // HttpOnly
        );
    }
    
    /**
     * Get system preference (from user agent or default)
     *
     * @return string
     */
    private function get_system_preference(): string {
        // This would typically be handled by JavaScript
        // For server-side fallback, we can check Accept header or use default
        return 'light';
    }
    
    /**
     * Check if dark mode is enabled in theme settings
     *
     * @return bool
     */
    private function is_dark_mode_enabled(): bool {
        return get_theme_mod('dark_mode_toggle_enabled', true);
    }
    
    /**
     * Sanitize mode choice
     *
     * @param string $input
     * @return string
     */
    public function sanitize_mode_choice($input): string {
        $valid_choices = ['light', 'dark', 'auto'];
        return in_array($input, $valid_choices) ? $input : 'auto';
    }
    
    /**
     * Sanitize position choice
     *
     * @param string $input
     * @return string
     */
    public function sanitize_position_choice($input): string {
        $valid_positions = ['header', 'footer', 'floating', 'admin_bar'];
        return in_array($input, $valid_positions) ? $input : 'header';
    }
    
    /**
     * Module activation
     *
     * @return void
     */
    protected function on_activate(): void {
        // Set default options
        add_option('dark_mode_toggle_enabled', true);
        add_option('dark_mode_default', 'auto');
        add_option('dark_mode_toggle_position', 'header');
        add_option('dark_mode_transition_duration', 300);
    }
    
    /**
     * Module deactivation
     *
     * @return void
     */
    protected function on_deactivate(): void {
        // Clean up options if needed
        // Note: We might want to keep user preferences
    }
}