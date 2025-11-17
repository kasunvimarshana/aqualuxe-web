<?php
/**
 * Dark Mode Module
 *
 * Handles dark mode functionality with persistent user preference
 *
 * @package AquaLuxe\Modules\DarkMode
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Dark Mode Module Class
 */
class AquaLuxe_Dark_Mode_Module {
    
    /**
     * Initialize the module
     */
    public function __construct() {
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('wp_head', array($this, 'add_dark_mode_script'), 1);
        add_filter('body_class', array($this, 'add_dark_mode_body_class'));
        
        // Register secure AJAX handler
        aqualuxe_secure_ajax_handler('toggle_dark_mode', array($this, 'ajax_toggle_dark_mode'), false, true);
    }

    /**
     * Enqueue dark mode scripts
     */
    public function enqueue_scripts() {
        wp_enqueue_script(
            'aqualuxe-dark-mode',
            AQUALUXE_ASSETS_URI . '/js/modules/dark-mode.js',
            array('jquery'),
            AQUALUXE_VERSION,
            true
        );

        wp_localize_script('aqualuxe-dark-mode', 'aqualuxe_dark_mode', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => aqualuxe_create_nonce('toggle_dark_mode'),
            'is_enabled' => $this->is_dark_mode_enabled(),
        ));
    }

    /**
     * Add critical dark mode detection script to head
     * This must be inline for performance (prevents flashing)
     */
    public function add_dark_mode_script() {
        $critical_js_path = AQUALUXE_THEME_DIR . '/assets/src/js/dark-mode-critical.js';
        
        if (file_exists($critical_js_path)) {
            echo '<script>';
            include $critical_js_path;
            echo '</script>';
        }
    }

    /**
     * Add dark mode body class
     */
    public function add_dark_mode_body_class($classes) {
        if ($this->is_dark_mode_enabled()) {
            $classes[] = 'dark-mode-enabled';
        }
        return $classes;
    }

    /**
     * Check if dark mode is enabled
     */
    public function is_dark_mode_enabled() {
        // Check cookie
        if (isset($_COOKIE['aqualuxe_dark_mode'])) {
            return $_COOKIE['aqualuxe_dark_mode'] === 'enabled';
        }
        
        // Default to false, will be handled by JavaScript
        return false;
    }

    /**
     * Handle AJAX dark mode toggle
     */
    public function ajax_toggle_dark_mode($data) {
        $enabled = isset($data['enabled']) && $data['enabled'] === 'true';
        
        // Set cookie for 30 days
        $cookie_value = $enabled ? 'enabled' : 'disabled';
        
        // Set secure cookie if on HTTPS
        $secure = is_ssl();
        $httponly = true;
        
        setcookie(
            'aqualuxe_dark_mode', 
            $cookie_value, 
            time() + (30 * DAY_IN_SECONDS), 
            '/', 
            '', 
            $secure, 
            $httponly
        );

        wp_send_json_success(array(
            'dark_mode' => $enabled,
            'message' => $enabled ? __('Dark mode enabled', 'aqualuxe') : __('Dark mode disabled', 'aqualuxe')
        ));
    }

    /**
     * Get dark mode toggle button HTML
     */
    public static function get_toggle_button($classes = '') {
        $default_classes = 'dark-mode-toggle inline-flex items-center justify-center p-2 rounded-md transition-colors duration-200';
        $button_classes = $classes ? $default_classes . ' ' . $classes : $default_classes;
        
        ob_start();
        ?>
        <button type="button" 
                class="<?php echo esc_attr($button_classes); ?>"
                data-dark-mode-toggle
                aria-label="<?php esc_attr_e('Toggle dark mode', 'aqualuxe'); ?>">
            <svg class="w-5 h-5 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
            </svg>
            <svg class="w-5 h-5 dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
            </svg>
        </button>
        <?php
        return ob_get_clean();
    }
}

// Initialize the module
new AquaLuxe_Dark_Mode_Module();

/**
 * Helper function to display dark mode toggle
 */
function aqualuxe_dark_mode_toggle($classes = '') {
    echo AquaLuxe_Dark_Mode_Module::get_toggle_button($classes);
}