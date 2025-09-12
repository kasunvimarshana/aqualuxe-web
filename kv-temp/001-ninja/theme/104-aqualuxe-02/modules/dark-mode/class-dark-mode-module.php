<?php
/**
 * Dark Mode Module
 * 
 * Handles dark mode functionality and user preferences
 * 
 * @package AquaLuxe
 */

if (!defined('ABSPATH')) {
    exit;
}

class AquaLuxe_Dark_Mode_Module {
    
    /**
     * Constructor
     */
    public function __construct() {
        $this->init();
    }
    
    /**
     * Initialize module
     */
    private function init() {
        // Check if module is enabled
        if (!aqualuxe_get_option('enable_dark_mode', true)) {
            return;
        }
        
        // Hooks
        add_action('wp_ajax_aqualuxe_toggle_dark_mode', [$this, 'ajax_toggle_dark_mode']);
        add_action('wp_ajax_nopriv_aqualuxe_toggle_dark_mode', [$this, 'ajax_toggle_dark_mode']);
        add_action('wp_head', [$this, 'add_dark_mode_script'], 1);
        add_filter('body_class', [$this, 'add_body_classes']);
    }
    
    /**
     * Add dark mode initialization script in head
     */
    public function add_dark_mode_script() {
        ?>
        <script>
        (function() {
            // Immediately apply dark mode to prevent flash
            const isDarkMode = localStorage.getItem('aqualuxe_dark_mode') === 'true' || 
                              (localStorage.getItem('aqualuxe_dark_mode') === null && 
                               window.matchMedia('(prefers-color-scheme: dark)').matches);
            
            if (isDarkMode) {
                document.documentElement.classList.add('dark');
                document.body.classList.add('dark-mode');
            }
        })();
        </script>
        <?php
    }
    
    /**
     * Add body classes for dark mode
     */
    public function add_body_classes($classes) {
        $classes[] = 'dark-mode-enabled';
        return $classes;
    }
    
    /**
     * AJAX handler for dark mode toggle
     */
    public function ajax_toggle_dark_mode() {
        // Verify nonce
        if (!wp_verify_nonce($_POST['nonce'] ?? '', 'aqualuxe_nonce')) {
            wp_send_json_error('Invalid nonce');
        }
        
        $is_dark = filter_var($_POST['is_dark'] ?? false, FILTER_VALIDATE_BOOLEAN);
        
        // Store user preference (optional, for logged-in users)
        if (is_user_logged_in()) {
            update_user_meta(get_current_user_id(), 'aqualuxe_dark_mode_preference', $is_dark);
        }
        
        wp_send_json_success([
            'is_dark' => $is_dark,
            'message' => $is_dark ? 
                esc_html__('Dark mode enabled', 'aqualuxe') : 
                esc_html__('Light mode enabled', 'aqualuxe')
        ]);
    }
    
    /**
     * Check if dark mode is enabled
     */
    public function is_enabled() {
        return aqualuxe_get_option('enable_dark_mode', true);
    }
}