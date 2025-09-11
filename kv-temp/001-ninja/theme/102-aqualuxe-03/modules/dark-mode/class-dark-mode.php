<?php
/**
 * Dark Mode Module
 *
 * Handles dark mode functionality with persistent user preference.
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Dark Mode Class
 */
class AquaLuxe_Dark_Mode {
    
    /**
     * Constructor
     */
    public function __construct() {
        add_action('wp_enqueue_scripts', array($this, 'enqueue_assets'));
        add_action('wp_ajax_toggle_dark_mode', array($this, 'ajax_toggle_dark_mode'));
        add_action('wp_ajax_nopriv_toggle_dark_mode', array($this, 'ajax_toggle_dark_mode'));
        add_action('wp_head', array($this, 'add_dark_mode_script'), 1);
        add_action('body_class', array($this, 'body_class'));
    }
    
    /**
     * Enqueue dark mode assets
     */
    public function enqueue_assets() {
        // Get manifest for cache busting
        $manifest_path = AQUALUXE_THEME_PATH . 'assets/dist/mix-manifest.json';
        $manifest = file_exists($manifest_path) ? json_decode(file_get_contents($manifest_path), true) : array();
        
        // Dark mode CSS
        $dark_mode_css = isset($manifest['/css/dark-mode.css']) ? $manifest['/css/dark-mode.css'] : '/css/dark-mode.css';
        wp_enqueue_style(
            'aqualuxe-dark-mode',
            AQUALUXE_ASSETS_URI . ltrim($dark_mode_css, '/'),
            array('aqualuxe-main'),
            AQUALUXE_VERSION
        );
        
        // Dark mode JavaScript
        $dark_mode_js = isset($manifest['/js/modules/dark-mode.js']) ? $manifest['/js/modules/dark-mode.js'] : '/js/modules/dark-mode.js';
        wp_enqueue_script(
            'aqualuxe-dark-mode',
            AQUALUXE_ASSETS_URI . ltrim($dark_mode_js, '/'),
            array('jquery'),
            AQUALUXE_VERSION,
            true
        );
        
        // Localize script
        wp_localize_script('aqualuxe-dark-mode', 'aqualuxe_dark_mode', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('dark_mode_nonce'),
            'storage_key' => 'aqualuxe-dark-mode',
        ));
    }
    
    /**
     * Add dark mode detection script to head
     */
    public function add_dark_mode_script() {
        ?>
        <script>
        (function() {
            // Check for saved theme preference or default to OS preference
            const savedTheme = localStorage.getItem('aqualuxe-dark-mode');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            
            if (savedTheme === 'dark' || (!savedTheme && prefersDark)) {
                document.documentElement.classList.add('dark');
            }
            
            // Prevent flash of unstyled content
            document.documentElement.style.colorScheme = 
                document.documentElement.classList.contains('dark') ? 'dark' : 'light';
        })();
        </script>
        <?php
    }
    
    /**
     * Add dark mode class to body
     */
    public function body_class($classes) {
        if ($this->is_dark_mode_enabled()) {
            $classes[] = 'dark-mode';
        }
        return $classes;
    }
    
    /**
     * Check if dark mode is enabled
     */
    public function is_dark_mode_enabled() {
        // This is primarily handled by JavaScript for instant response
        // Server-side detection is limited but can be useful for some cases
        return false; // Default to light mode for server-side rendering
    }
    
    /**
     * AJAX handler for toggling dark mode
     */
    public function ajax_toggle_dark_mode() {
        // Verify nonce
        if (!wp_verify_nonce($_POST['nonce'], 'dark_mode_nonce')) {
            wp_die('Security check failed');
        }
        
        $mode = sanitize_text_field($_POST['mode']);
        
        if (!in_array($mode, array('light', 'dark'))) {
            wp_die('Invalid mode');
        }
        
        // Store user preference (optional, for logged-in users)
        if (is_user_logged_in()) {
            update_user_meta(get_current_user_id(), 'dark_mode_preference', $mode);
        }
        
        wp_send_json_success(array(
            'mode' => $mode,
            'message' => sprintf(
                /* translators: %s: dark mode state */
                __('Dark mode %s', 'aqualuxe'),
                $mode === 'dark' ? __('enabled', 'aqualuxe') : __('disabled', 'aqualuxe')
            )
        ));
    }
    
    /**
     * Get user's dark mode preference
     */
    public function get_user_preference() {
        if (is_user_logged_in()) {
            return get_user_meta(get_current_user_id(), 'dark_mode_preference', true);
        }
        return '';
    }
    
    /**
     * Render dark mode toggle button
     */
    public static function render_toggle_button($classes = '') {
        $button_classes = 'dark-mode-toggle ' . $classes;
        ?>
        <button type="button" 
                class="<?php echo esc_attr($button_classes); ?>" 
                aria-label="<?php esc_attr_e('Toggle dark mode', 'aqualuxe'); ?>"
                data-toggle="dark-mode">
            <span class="dark-mode-icon-light" aria-hidden="true">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
            </span>
            <span class="dark-mode-icon-dark hidden" aria-hidden="true">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                </svg>
            </span>
            <span class="screen-reader-text"><?php esc_html_e('Toggle dark mode', 'aqualuxe'); ?></span>
        </button>
        <?php
    }
}

// Initialize dark mode module
new AquaLuxe_Dark_Mode();