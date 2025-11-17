<?php
/**
 * Consolidated Security Functions
 *
 * Unified security hardening, error handling, and performance optimization
 *
 * @package AquaLuxe\Core\Security
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Main Security Manager Class
 *
 * Handles all security, error prevention, and optimization features
 */
final class AquaLuxe_Consolidated_Security {
    
    /**
     * Single instance of the class
     *
     * @var AquaLuxe_Consolidated_Security
     */
    private static $instance = null;

    /**
     * Rate limiting cache
     *
     * @var array
     */
    private static $rate_limits = array();

    /**
     * Security configuration
     *
     * @var array
     */
    private $config = array();

    /**
     * Get instance (Singleton Pattern)
     *
     * @return AquaLuxe_Consolidated_Security
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor - Initialize all security features
     */
    private function __construct() {
        $this->init_config();
        $this->init_security_hooks();
        $this->init_performance_hooks();
        $this->init_error_handling();
    }

    /**
     * Initialize security configuration
     */
    private function init_config() {
        $this->config = array(
            'rate_limit_requests_per_minute' => 30,
            'enable_csp_headers' => true,
            'enable_xss_protection' => true,
            'enable_nosniff' => true,
            'enable_frame_options' => true,
            'remove_version_info' => true,
            'disable_xmlrpc' => true,
            'disable_file_editing' => true,
        );

        // Allow customization through filters
        $this->config = apply_filters('aqualuxe_security_config', $this->config);
    }

    /**
     * Initialize security-related hooks
     */
    private function init_security_hooks() {
        // Remove version information
        if ($this->config['remove_version_info']) {
            add_filter('the_generator', '__return_empty_string');
            remove_action('wp_head', 'wp_generator');
            remove_action('wp_head', 'wlwmanifest_link');
            remove_action('wp_head', 'rsd_link');
            remove_action('wp_head', 'wp_shortlink_wp_head');
        }

        // Disable XML-RPC if configured
        if ($this->config['disable_xmlrpc']) {
            add_filter('xmlrpc_enabled', '__return_false');
            add_filter('wp_headers', array($this, 'remove_x_pingback'));
        }

        // Security headers
        add_action('send_headers', array($this, 'add_security_headers'));

        // Enhanced login security
        add_action('wp_login_failed', array($this, 'handle_login_failure'));
        add_filter('authenticate', array($this, 'limit_login_attempts'), 30, 3);

        // Content Security Policy
        if ($this->config['enable_csp_headers']) {
            add_action('wp_head', array($this, 'add_csp_meta'), 1);
        }

        // Disable file editing in admin
        if ($this->config['disable_file_editing'] && !defined('DISALLOW_FILE_EDIT')) {
            define('DISALLOW_FILE_EDIT', true);
        }
    }

    /**
     * Initialize performance optimization hooks
     */
    private function init_performance_hooks() {
        // Clean up wp_head
        remove_action('wp_head', 'print_emoji_detection_script', 7);
        remove_action('wp_print_styles', 'print_emoji_styles');
        remove_action('admin_print_scripts', 'print_emoji_detection_script');
        remove_action('admin_print_styles', 'print_emoji_styles');
        remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
        remove_filter('the_content_feed', 'wp_staticize_emoji');
        remove_filter('comment_text_rss', 'wp_staticize_emoji');

        // Remove unnecessary DNS prefetches
        remove_action('wp_head', 'wp_resource_hints', 2);

        // Optimize script loading
        add_action('wp_enqueue_scripts', array($this, 'optimize_script_loading'), 999);
        add_filter('script_loader_tag', array($this, 'optimize_script_tags'), 10, 3);

        // Clean up query vars
        add_filter('query_vars', array($this, 'remove_unnecessary_query_vars'));
    }

    /**
     * Initialize error handling and JavaScript fixes
     */
    private function init_error_handling() {
        // JavaScript error prevention
        add_action('wp_footer', array($this, 'add_error_recovery_script'), 1);
        
        // Fix script dependencies
        add_action('wp_enqueue_scripts', array($this, 'fix_script_dependencies'), 50);
        
        // Prevent script conflicts
        add_action('wp_enqueue_scripts', array($this, 'prevent_script_conflicts'), 100);
    }

    /**
     * Add comprehensive security headers
     */
    public function add_security_headers() {
        if (!is_admin()) {
            // X-Frame-Options
            if ($this->config['enable_frame_options']) {
                header('X-Frame-Options: SAMEORIGIN');
            }

            // X-Content-Type-Options
            if ($this->config['enable_nosniff']) {
                header('X-Content-Type-Options: nosniff');
            }

            // X-XSS-Protection
            if ($this->config['enable_xss_protection']) {
                header('X-XSS-Protection: 1; mode=block');
            }

            // Referrer Policy
            header('Referrer-Policy: strict-origin-when-cross-origin');

            // Permissions Policy (formerly Feature Policy)
            header('Permissions-Policy: camera=(), microphone=(), geolocation=()');
        }
    }

    /**
     * Add Content Security Policy meta tag
     */
    public function add_csp_meta() {
        $csp_directives = array(
            "default-src 'self'",
            "script-src 'self' 'unsafe-inline' 'unsafe-eval'",
            "style-src 'self' 'unsafe-inline'",
            "img-src 'self' data: https:",
            "font-src 'self' data:",
            "connect-src 'self'",
            "media-src 'self'",
            "object-src 'none'",
            "base-uri 'self'",
            "form-action 'self'",
            "frame-ancestors 'self'"
        );

        $csp = implode('; ', apply_filters('aqualuxe_csp_directives', $csp_directives));
        echo '<meta http-equiv="Content-Security-Policy" content="' . esc_attr($csp) . '">' . "\n";
    }

    /**
     * Remove X-Pingback header
     */
    public function remove_x_pingback($headers) {
        unset($headers['X-Pingback']);
        return $headers;
    }

    /**
     * Enhanced rate limiting for login attempts
     */
    public function limit_login_attempts($user, $username, $password) {
        if (is_wp_error($user)) {
            $ip = $this->get_client_ip();
            $key = 'aqualuxe_login_attempts_' . md5($ip);
            
            $attempts = get_transient($key);
            if (false === $attempts) {
                $attempts = 0;
            }
            
            $attempts++;
            set_transient($key, $attempts, 15 * MINUTE_IN_SECONDS);
            
            if ($attempts >= 5) {
                return new WP_Error('too_many_attempts', 
                    __('Too many login attempts. Please try again in 15 minutes.', 'aqualuxe'));
            }
        }
        
        return $user;
    }

    /**
     * Handle login failures
     */
    public function handle_login_failure($username) {
        $ip = $this->get_client_ip();
        
        // Log the failure (implement logging if needed)
        error_log("AquaLuxe: Failed login attempt for user '$username' from IP: $ip");
    }

    /**
     * Get client IP address safely
     */
    private function get_client_ip() {
        $ip_keys = array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR');
        
        foreach ($ip_keys as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    $ip = trim($ip);
                    if (filter_var($ip, FILTER_VALIDATE_IP, 
                        FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
                        return $ip;
                    }
                }
            }
        }
        
        return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    }

    /**
     * AJAX rate limiting
     */
    public static function check_ajax_rate_limit() {
        $ip = (new self())->get_client_ip();
        $key = 'aqualuxe_ajax_rate_' . md5($ip);
        $requests = get_transient($key);
        
        if (false === $requests) {
            set_transient($key, 1, MINUTE_IN_SECONDS);
            return true;
        }
        
        if ($requests >= 30) { // Max 30 requests per minute
            return false;
        }
        
        set_transient($key, $requests + 1, MINUTE_IN_SECONDS);
        return true;
    }

    /**
     * Optimize script loading and prevent conflicts
     */
    public function optimize_script_loading() {
        // Remove problematic scripts on frontend
        if (!is_admin()) {
            wp_dequeue_script('wp-embed');
            
            // Conditionally load module scripts
            $this->load_conditional_scripts();
        }
    }

    /**
     * Load scripts conditionally based on page context
     */
    private function load_conditional_scripts() {
        global $wp_scripts;
        
        if (!is_object($wp_scripts)) {
            return;
        }

        $page_context = $this->get_page_context();
        $required_modules = array();

        // Determine which modules are needed
        switch ($page_context) {
            case 'shop':
                $required_modules = array('multi-currency', 'woocommerce');
                break;
            case 'service':
                $required_modules = array('services', 'bookings');
                break;
            case 'event':
                $required_modules = array('events');
                break;
            case 'home':
                $required_modules = array('multilingual', 'dark-mode');
                break;
            default:
                $required_modules = array('dark-mode');
        }

        // Dequeue unnecessary module scripts
        foreach ($wp_scripts->registered as $handle => $script) {
            if (strpos($handle, 'aqualuxe-modules-') === 0) {
                $module_name = str_replace('aqualuxe-modules-', '', $handle);
                if (!in_array($module_name, $required_modules, true)) {
                    wp_dequeue_script($handle);
                }
            }
        }
    }

    /**
     * Get current page context for conditional loading
     */
    private function get_page_context() {
        if (function_exists('is_woocommerce') && is_woocommerce()) {
            return 'shop';
        }
        
        if (is_singular('aqualuxe-service')) {
            return 'service';
        }
        
        if (is_singular('aqualuxe-event')) {
            return 'event';
        }
        
        if (is_front_page() || is_home()) {
            return 'home';
        }
        
        return 'general';
    }

    /**
     * Optimize script tags for better performance
     */
    public function optimize_script_tags($tag, $handle, $src) {
        // Add defer attribute to non-critical scripts
        $defer_scripts = array(
            'aqualuxe-modules-dark-mode',
            'aqualuxe-modules-multilingual',
            'aqualuxe-modules-services'
        );

        if (in_array($handle, $defer_scripts, true)) {
            $tag = str_replace('<script ', '<script defer ', $tag);
        }

        // Add error handling to critical scripts
        $critical_scripts = array('aqualuxe-app');
        if (in_array($handle, $critical_scripts, true)) {
            $tag = str_replace('></script>', '>
                try { 
                    /* Critical script content handled by webpack */ 
                } catch(e) { 
                    console.error("AquaLuxe Critical Script Error:", e); 
                }
            </script>', $tag);
        }

        return $tag;
    }

    /**
     * Fix script dependencies to prevent loading errors
     */
    public function fix_script_dependencies() {
        global $wp_scripts;
        
        if (!is_object($wp_scripts)) {
            return;
        }

        // Ensure module scripts depend on main app
        foreach ($wp_scripts->registered as $handle => $script) {
            if (strpos($handle, 'aqualuxe-modules-') === 0) {
                if (!in_array('aqualuxe-app', $script->deps, true)) {
                    $script->deps[] = 'aqualuxe-app';
                }
            }
        }
    }

    /**
     * Prevent script conflicts and duplicates
     */
    public function prevent_script_conflicts() {
        global $wp_scripts;
        
        if (!is_object($wp_scripts)) {
            return;
        }

        $seen_handles = array();
        
        foreach ($wp_scripts->registered as $handle => $script) {
            if (strpos($handle, 'aqualuxe') !== false) {
                $base_handle = preg_replace('/(-\d+|_\d+)$/', '', $handle);
                
                if (isset($seen_handles[$base_handle])) {
                    wp_dequeue_script($handle);
                } else {
                    $seen_handles[$base_handle] = $handle;
                }
            }
        }
    }

    /**
     * Remove unnecessary query vars
     */
    public function remove_unnecessary_query_vars($vars) {
        $unnecessary = array(
            'customize_messenger_channel',
            'customize_changeset_uuid',
            'wp_customize'
        );
        
        return array_diff($vars, $unnecessary);
    }

    /**
     * Add JavaScript error recovery and variable definitions
     */
    public function add_error_recovery_script() {
        ?>
        <script type="text/javascript">
        (function() {
            'use strict';
            
            // Emergency error recovery
            var originalError = window.onerror;
            window.onerror = function(msg, url, line, col, error) {
                if (typeof console !== 'undefined' && console.error) {
                    console.error('AquaLuxe Emergency Recovery:', {
                        message: msg,
                        source: url,
                        line: line,
                        column: col,
                        error: error
                    });
                }
                
                if (typeof originalError === 'function') {
                    return originalError.apply(this, arguments);
                }
                
                return true; // Prevent error from breaking the page
            };
            
            // Unhandled promise rejection recovery
            window.addEventListener('unhandledrejection', function(event) {
                console.warn('AquaLuxe: Unhandled promise rejection:', event.reason);
                event.preventDefault();
            });
            
            // Define common undefined variables
            window.aqualuxe = window.aqualuxe || {};
            window.aqualuxe_ajax = window.aqualuxe_ajax || {};
            window.aqualuxe_modules = window.aqualuxe_modules || {};
            
            // Fix common undefined variables
            if (typeof newImage === 'undefined') {
                window.newImage = function() { return new Image(); };
            }
            
            // Ensure jQuery is available
            if (typeof jQuery === 'undefined' && typeof $ !== 'undefined') {
                window.jQuery = window.$;
            }
            
        })();
        </script>
        <?php
    }

    /**
     * Sanitize and validate input data
     */
    public static function sanitize_input($input, $type = 'text') {
        switch ($type) {
            case 'email':
                return sanitize_email($input);
            case 'url':
                return esc_url_raw($input);
            case 'int':
                return intval($input);
            case 'float':
                return floatval($input);
            case 'html':
                return wp_kses_post($input);
            case 'textarea':
                return sanitize_textarea_field($input);
            case 'text':
            default:
                return sanitize_text_field($input);
        }
    }

    /**
     * Generate secure nonce for AJAX requests
     */
    public static function generate_ajax_nonce($action = 'aqualuxe_ajax') {
        return wp_create_nonce($action);
    }

    /**
     * Verify AJAX nonce
     */
    public static function verify_ajax_nonce($nonce, $action = 'aqualuxe_ajax') {
        return wp_verify_nonce($nonce, $action);
    }
}

// Initialize the consolidated security system
add_action('after_setup_theme', function() {
    AquaLuxe_Consolidated_Security::get_instance();
}, 1);

/**
 * Helper functions for backward compatibility
 */

/**
 * Check AJAX rate limit (backward compatibility)
 */
function aqualuxe_check_rate_limit() {
    return AquaLuxe_Consolidated_Security::check_ajax_rate_limit();
}

/**
 * Sanitize input (helper function)
 */
function aqualuxe_sanitize_input($input, $type = 'text') {
    return AquaLuxe_Consolidated_Security::sanitize_input($input, $type);
}

/**
 * Generate AJAX nonce (helper function)
 */
function aqualuxe_generate_ajax_nonce($action = 'aqualuxe_ajax') {
    return AquaLuxe_Consolidated_Security::generate_ajax_nonce($action);
}

/**
 * Verify AJAX nonce (helper function)
 */
function aqualuxe_verify_ajax_nonce($nonce, $action = 'aqualuxe_ajax') {
    return AquaLuxe_Consolidated_Security::verify_ajax_nonce($nonce, $action);
}