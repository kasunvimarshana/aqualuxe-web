<?php
/**
 * Dark Mode Module - Standardized Version
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

use AquaLuxe\Core\BaseModule;
use AquaLuxe\Core\AssetManager;

/**
 * Dark Mode Module Class
 */
class AquaLuxe_DarkMode_Module extends BaseModule {
    
    /**
     * Set module properties
     */
    protected function set_properties() {
        $this->name = 'dark-mode';
        $this->version = '1.0.0';
        
        $this->config = array(
            'enable_system_detection' => true,
            'enable_auto_toggle' => true,
            'enable_schedule' => false,
            'schedule_start' => '18:00',
            'schedule_end' => '06:00',
        );

        $this->assets = array(
            'js' => array(
                'dark-mode' => 'js/modules/dark-mode.js'
            ),
            'css' => array(
                'dark-mode' => 'css/modules/dark-mode.css'
            )
        );

        $this->admin_settings = array(
            'enable_system_detection' => array(
                'label' => __('Enable System Detection', 'aqualuxe'),
                'description' => __('Automatically detect user\'s system dark mode preference', 'aqualuxe'),
                'type' => 'checkbox',
                'default' => true,
                'sanitize' => 'aqualuxe_sanitize_checkbox'
            ),
            'enable_auto_toggle' => array(
                'label' => __('Enable Auto Toggle', 'aqualuxe'),
                'description' => __('Allow users to toggle dark mode manually', 'aqualuxe'),
                'type' => 'checkbox',
                'default' => true,
                'sanitize' => 'aqualuxe_sanitize_checkbox'
            ),
            'enable_schedule' => array(
                'label' => __('Enable Schedule', 'aqualuxe'),
                'description' => __('Automatically switch to dark mode based on time schedule', 'aqualuxe'),
                'type' => 'checkbox',
                'default' => false,
                'sanitize' => 'aqualuxe_sanitize_checkbox'
            ),
            'schedule_start' => array(
                'label' => __('Schedule Start Time', 'aqualuxe'),
                'description' => __('Time to automatically enable dark mode (24-hour format)', 'aqualuxe'),
                'type' => 'time',
                'default' => '18:00',
                'sanitize' => 'sanitize_text_field'
            ),
            'schedule_end' => array(
                'label' => __('Schedule End Time', 'aqualuxe'),
                'description' => __('Time to automatically disable dark mode (24-hour format)', 'aqualuxe'),
                'type' => 'time',
                'default' => '06:00',
                'sanitize' => 'sanitize_text_field'
            )
        );
    }

    /**
     * Initialize module hooks
     */
    protected function init_hooks() {
        add_action('wp_head', array($this, 'add_critical_dark_mode_script'), 1);
        add_filter('body_class', array($this, 'add_dark_mode_body_class'));
        add_action('wp_footer', array($this, 'add_dark_mode_toggle_html'));
        
        // Register AJAX handlers
        $this->register_ajax_handler('toggle', array($this, 'ajax_toggle_dark_mode'), false, true);
        $this->register_ajax_handler('get_status', array($this, 'ajax_get_dark_mode_status'), false, true);
    }

    /**
     * Check if assets should be loaded on current page
     */
    protected function should_load_assets() {
        // Load dark mode assets on all frontend pages
        return !is_admin();
    }

    /**
     * Enqueue frontend assets
     */
    public function enqueue_frontend_assets() {
        if (!$this->should_load_assets()) {
            return;
        }

        // Enqueue dark mode script
        AssetManager::enqueue_script(
            'aqualuxe-dark-mode',
            'js/modules/dark-mode.js',
            array('jquery'),
            $this->version,
            true
        );

        // Localize script
        wp_localize_script('aqualuxe-dark-mode', 'aqualuxeDarkMode', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => aqualuxe_generate_ajax_nonce('aqualuxe_dark-mode_toggle'),
            'isEnabled' => $this->is_dark_mode_enabled(),
            'systemDetection' => $this->get_setting('enable_system_detection', true),
            'autoToggle' => $this->get_setting('enable_auto_toggle', true),
            'schedule' => array(
                'enabled' => $this->get_setting('enable_schedule', false),
                'start' => $this->get_setting('schedule_start', '18:00'),
                'end' => $this->get_setting('schedule_end', '06:00')
            ),
            'strings' => array(
                'enableDarkMode' => __('Enable Dark Mode', 'aqualuxe'),
                'disableDarkMode' => __('Disable Dark Mode', 'aqualuxe'),
                'toggleFailed' => __('Failed to toggle dark mode. Please try again.', 'aqualuxe')
            )
        ));
    }

    /**
     * Add critical dark mode detection script to head
     * This prevents flashing and must be inline for performance
     */
    public function add_critical_dark_mode_script() {
        $system_detection = $this->get_setting('enable_system_detection', true);
        $schedule_enabled = $this->get_setting('enable_schedule', false);
        $schedule_start = $this->get_setting('schedule_start', '18:00');
        $schedule_end = $this->get_setting('schedule_end', '06:00');
        ?>
        <script>
        (function() {
            'use strict';
            
            // Prevent FOUC (Flash of Unstyled Content)
            var darkModeEnabled = false;
            var cookieValue = document.cookie.replace(/(?:(?:^|.*;\s*)aqualuxe_dark_mode\s*\=\s*([^;]*).*$)|^.*$/, "$1");
            
            // Check cookie first
            if (cookieValue === 'enabled') {
                darkModeEnabled = true;
            } else if (cookieValue === 'disabled') {
                darkModeEnabled = false;
            } else {
                // No cookie set, check system preference and schedule
                <?php if ($system_detection): ?>
                if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                    darkModeEnabled = true;
                }
                <?php endif; ?>
                
                <?php if ($schedule_enabled): ?>
                var now = new Date();
                var currentTime = now.getHours() * 60 + now.getMinutes();
                var scheduleStart = <?php echo intval(explode(':', $schedule_start)[0]) * 60 + intval(explode(':', $schedule_start)[1]); ?>;
                var scheduleEnd = <?php echo intval(explode(':', $schedule_end)[0]) * 60 + intval(explode(':', $schedule_end)[1]); ?>;
                
                if (scheduleStart > scheduleEnd) {
                    // Schedule crosses midnight
                    if (currentTime >= scheduleStart || currentTime < scheduleEnd) {
                        darkModeEnabled = true;
                    }
                } else {
                    // Schedule within same day
                    if (currentTime >= scheduleStart && currentTime < scheduleEnd) {
                        darkModeEnabled = true;
                    }
                }
                <?php endif; ?>
            }
            
            // Apply dark mode class immediately
            if (darkModeEnabled) {
                document.documentElement.classList.add('dark');
                if (document.body) {
                    document.body.classList.add('dark-mode-enabled');
                } else {
                    document.addEventListener('DOMContentLoaded', function() {
                        document.body.classList.add('dark-mode-enabled');
                    });
                }
            }
            
            // Store initial state for JavaScript access
            window.aqualuxeDarkModeInitial = darkModeEnabled;
            
        })();
        </script>
        <?php
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
     * Add dark mode toggle HTML to footer
     */
    public function add_dark_mode_toggle_html() {
        if (!$this->get_setting('enable_auto_toggle', true)) {
            return;
        }
        ?>
        <div id="aqualuxe-dark-mode-toggle" class="fixed bottom-4 right-4 z-50 lg:bottom-8 lg:right-8">
            <button
                type="button"
                class="dark-mode-toggle bg-white dark:bg-gray-800 text-gray-800 dark:text-white rounded-full p-3 shadow-lg transition-all duration-300 hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-primary-500"
                aria-label="<?php esc_attr_e('Toggle Dark Mode', 'aqualuxe'); ?>"
                title="<?php esc_attr_e('Toggle Dark Mode', 'aqualuxe'); ?>"
            >
                <svg class="w-6 h-6 dark-mode-icon-light" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                    <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd"></path>
                </svg>
                <svg class="w-6 h-6 dark-mode-icon-dark hidden" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                    <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                </svg>
            </button>
        </div>
        <?php
    }

    /**
     * Check if dark mode is enabled
     */
    public function is_dark_mode_enabled() {
        // Check cookie first
        if (isset($_COOKIE['aqualuxe_dark_mode'])) {
            return $_COOKIE['aqualuxe_dark_mode'] === 'enabled';
        }
        
        // No cookie set, use system detection or schedule as fallback
        return false; // Default to false, JavaScript will handle detection
    }

    /**
     * Handle AJAX dark mode toggle
     */
    public function ajax_toggle_dark_mode() {
        if (!$this->verify_ajax_request('aqualuxe_dark-mode_toggle')) {
            $this->json_response(__('Security check failed.', 'aqualuxe'), false, 403);
            return;
        }

        $enabled = isset($_POST['enabled']) && $_POST['enabled'] === 'true';
        
        // Set cookie for 365 days (1 year)
        $cookie_value = $enabled ? 'enabled' : 'disabled';
        $expire = time() + (365 * 24 * 60 * 60);
        $secure = is_ssl();
        $httponly = false; // JavaScript needs to read this cookie
        
        setcookie(
            'aqualuxe_dark_mode',
            $cookie_value,
            $expire,
            COOKIEPATH,
            COOKIE_DOMAIN,
            $secure,
            $httponly
        );

        $this->json_response(array(
            'enabled' => $enabled,
            'message' => $enabled 
                ? __('Dark mode enabled.', 'aqualuxe')
                : __('Dark mode disabled.', 'aqualuxe')
        ), true);
    }

    /**
     * Handle AJAX get dark mode status
     */
    public function ajax_get_dark_mode_status() {
        if (!$this->verify_ajax_request('aqualuxe_dark-mode_get_status')) {
            $this->json_response(__('Security check failed.', 'aqualuxe'), false, 403);
            return;
        }

        $this->json_response(array(
            'enabled' => $this->is_dark_mode_enabled(),
            'system_detection' => $this->get_setting('enable_system_detection', true),
            'auto_toggle' => $this->get_setting('enable_auto_toggle', true),
            'schedule' => array(
                'enabled' => $this->get_setting('enable_schedule', false),
                'start' => $this->get_setting('schedule_start', '18:00'),
                'end' => $this->get_setting('schedule_end', '06:00')
            )
        ), true);
    }
}

// Helper function for checkbox sanitization
if (!function_exists('aqualuxe_sanitize_checkbox')) {
    function aqualuxe_sanitize_checkbox($checked) {
        return (bool) $checked;
    }
}

// Helper function to check if dark mode is available
if (!function_exists('aqualuxe_is_dark_mode')) {
    function aqualuxe_is_dark_mode() {
        static $dark_mode_module = null;
        
        if ($dark_mode_module === null) {
            global $aqualuxe_dark_mode_instance;
            $dark_mode_module = isset($aqualuxe_dark_mode_instance) ? $aqualuxe_dark_mode_instance : false;
        }
        
        return $dark_mode_module ? $dark_mode_module->is_dark_mode_enabled() : false;
    }
}

// Initialize the module
global $aqualuxe_dark_mode_instance;
$aqualuxe_dark_mode_instance = new AquaLuxe_DarkMode_Module();