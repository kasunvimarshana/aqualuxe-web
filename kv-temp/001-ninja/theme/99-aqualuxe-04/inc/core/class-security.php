<?php
/**
 * Security Hardening Class
 *
 * @package AquaLuxe\Core
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Security hardening features
 */
class AquaLuxe_Security {
    
    /**
     * Constructor
     */
    public function __construct() {
        add_action('init', array($this, 'init_security_features'));
        add_action('wp_head', array($this, 'add_security_headers'));
        add_filter('wp_headers', array($this, 'add_http_security_headers'));
        
        // Input sanitization and validation
        add_action('wp_loaded', array($this, 'sanitize_global_inputs'));
        
        // Disable file editing
        add_action('init', array($this, 'disable_file_editing'));
        
        // Hide sensitive information
        add_filter('the_generator', '__return_empty_string');
        remove_action('wp_head', 'wp_generator');
        
        // Secure login
        add_action('wp_login_failed', array($this, 'log_failed_login'));
        add_filter('authenticate', array($this, 'check_login_attempts'), 30, 3);
    }
    
    /**
     * Initialize security features
     */
    public function init_security_features() {
        // Remove version numbers from scripts and styles
        add_filter('style_loader_src', array($this, 'remove_version_strings'), 9999);
        add_filter('script_loader_src', array($this, 'remove_version_strings'), 9999);
        
        // Disable XML-RPC if not needed
        if (!aqualuxe_get_option('enable_xmlrpc', false)) {
            add_filter('xmlrpc_enabled', '__return_false');
            add_filter('wp_headers', array($this, 'remove_x_pingback'));
        }
        
        // Disable user enumeration
        add_action('wp', array($this, 'disable_user_enumeration'));
        
        // Remove WordPress version from RSS feeds
        add_filter('the_generator', '__return_empty_string');
        
        // Hide login errors
        add_filter('login_errors', array($this, 'hide_login_errors'));
        
        // Disable directory browsing
        add_action('init', array($this, 'disable_directory_browsing'));
    }
    
    /**
     * Add security headers to HTML head
     */
    public function add_security_headers() {
        // Content Security Policy
        $csp_policy = $this->get_csp_policy();
        if ($csp_policy) {
            echo '<meta http-equiv="Content-Security-Policy" content="' . esc_attr($csp_policy) . '">' . "\n";
        }
        
        // X-Frame-Options
        echo '<meta http-equiv="X-Frame-Options" content="SAMEORIGIN">' . "\n";
        
        // X-Content-Type-Options
        echo '<meta http-equiv="X-Content-Type-Options" content="nosniff">' . "\n";
        
        // Referrer Policy
        echo '<meta name="referrer" content="strict-origin-when-cross-origin">' . "\n";
    }
    
    /**
     * Add HTTP security headers
     */
    public function add_http_security_headers($headers) {
        // X-Frame-Options
        $headers['X-Frame-Options'] = 'SAMEORIGIN';
        
        // X-Content-Type-Options
        $headers['X-Content-Type-Options'] = 'nosniff';
        
        // X-XSS-Protection
        $headers['X-XSS-Protection'] = '1; mode=block';
        
        // Strict-Transport-Security (HTTPS only)
        if (is_ssl()) {
            $headers['Strict-Transport-Security'] = 'max-age=31536000; includeSubDomains';
        }
        
        // Referrer-Policy
        $headers['Referrer-Policy'] = 'strict-origin-when-cross-origin';
        
        // Permissions-Policy
        $headers['Permissions-Policy'] = 'camera=(), microphone=(), geolocation=()';
        
        return $headers;
    }
    
    /**
     * Get Content Security Policy
     */
    private function get_csp_policy() {
        $policy_parts = array(
            "default-src 'self'",
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' *.googleapis.com *.gstatic.com",
            "style-src 'self' 'unsafe-inline' *.googleapis.com *.gstatic.com",
            "font-src 'self' *.googleapis.com *.gstatic.com",
            "img-src 'self' data: *.wp.com *.gravatar.com",
            "connect-src 'self'",
            "frame-src 'self' *.youtube.com *.vimeo.com",
            "object-src 'none'",
            "base-uri 'self'"
        );
        
        return implode('; ', apply_filters('aqualuxe_csp_policy', $policy_parts));
    }
    
    /**
     * Remove version strings from assets
     */
    public function remove_version_strings($src) {
        if (strpos($src, 'ver=')) {
            $src = remove_query_arg('ver', $src);
        }
        return $src;
    }
    
    /**
     * Remove X-Pingback header
     */
    public function remove_x_pingback($headers) {
        unset($headers['X-Pingback']);
        return $headers;
    }
    
    /**
     * Disable user enumeration
     */
    public function disable_user_enumeration() {
        if (!is_admin() && isset($_GET['author'])) {
            wp_redirect(home_url(), 301);
            exit;
        }
        
        // Block REST API user endpoints for non-authenticated users
        add_filter('rest_endpoints', array($this, 'disable_rest_user_endpoints'));
    }
    
    /**
     * Disable REST API user endpoints
     */
    public function disable_rest_user_endpoints($endpoints) {
        if (!is_user_logged_in()) {
            if (isset($endpoints['/wp/v2/users'])) {
                unset($endpoints['/wp/v2/users']);
            }
            if (isset($endpoints['/wp/v2/users/(?P<id>[\d]+)'])) {
                unset($endpoints['/wp/v2/users/(?P<id>[\d]+)']);
            }
        }
        return $endpoints;
    }
    
    /**
     * Hide login errors
     */
    public function hide_login_errors($error) {
        return __('Invalid login credentials.', 'aqualuxe');
    }
    
    /**
     * Disable file editing in WordPress admin
     */
    public function disable_file_editing() {
        if (!defined('DISALLOW_FILE_EDIT')) {
            define('DISALLOW_FILE_EDIT', true);
        }
    }
    
    /**
     * Disable directory browsing
     */
    public function disable_directory_browsing() {
        $htaccess_file = ABSPATH . '.htaccess';
        if (is_writable($htaccess_file)) {
            $rules = PHP_EOL . "# Disable directory browsing" . PHP_EOL . "Options -Indexes" . PHP_EOL;
            
            if (!strpos(file_get_contents($htaccess_file), 'Options -Indexes')) {
                file_put_contents($htaccess_file, $rules, FILE_APPEND | LOCK_EX);
            }
        }
    }
    
    /**
     * Sanitize global inputs
     */
    public function sanitize_global_inputs() {
        // Sanitize $_GET
        foreach ($_GET as $key => $value) {
            $_GET[$key] = $this->deep_sanitize($value);
        }
        
        // Sanitize $_POST (except for admin)
        if (!is_admin()) {
            foreach ($_POST as $key => $value) {
                $_POST[$key] = $this->deep_sanitize($value);
            }
        }
        
        // Sanitize $_REQUEST
        foreach ($_REQUEST as $key => $value) {
            $_REQUEST[$key] = $this->deep_sanitize($value);
        }
    }
    
    /**
     * Deep sanitize function
     */
    private function deep_sanitize($data) {
        if (is_array($data)) {
            return array_map(array($this, 'deep_sanitize'), $data);
        }
        
        return sanitize_text_field($data);
    }
    
    /**
     * Log failed login attempts
     */
    public function log_failed_login($username) {
        $ip = $this->get_user_ip();
        $attempts = get_transient('aqualuxe_login_attempts_' . $ip) ?: 0;
        $attempts++;
        
        // Store attempt count for 15 minutes
        set_transient('aqualuxe_login_attempts_' . $ip, $attempts, 15 * MINUTE_IN_SECONDS);
        
        // Log to file if enabled
        if (aqualuxe_get_option('log_failed_logins', true)) {
            $log_message = sprintf(
                '[%s] Failed login attempt for user "%s" from IP %s (attempt #%d)',
                current_time('mysql'),
                sanitize_text_field($username),
                $ip,
                $attempts
            );
            
            error_log($log_message, 3, WP_CONTENT_DIR . '/aqualuxe-security.log');
        }
    }
    
    /**
     * Check login attempts and block if necessary
     */
    public function check_login_attempts($user, $username, $password) {
        $ip = $this->get_user_ip();
        $attempts = get_transient('aqualuxe_login_attempts_' . $ip) ?: 0;
        $max_attempts = aqualuxe_get_option('max_login_attempts', 5);
        
        if ($attempts >= $max_attempts) {
            $blocked_until = get_transient('aqualuxe_login_blocked_' . $ip);
            if (!$blocked_until) {
                // Block for 30 minutes
                set_transient('aqualuxe_login_blocked_' . $ip, time() + (30 * MINUTE_IN_SECONDS), 30 * MINUTE_IN_SECONDS);
            }
            
            return new WP_Error('too_many_attempts', __('Too many failed login attempts. Please try again later.', 'aqualuxe'));
        }
        
        return $user;
    }
    
    /**
     * Get user IP address
     */
    private function get_user_ip() {
        $ip_keys = array('HTTP_CF_CONNECTING_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_CLIENT_IP', 'REMOTE_ADDR');
        
        foreach ($ip_keys as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    $ip = trim($ip);
                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
                        return $ip;
                    }
                }
            }
        }
        
        return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    }
    
    /**
     * Validate nonce for AJAX requests
     */
    public static function verify_ajax_nonce($action = 'aqualuxe_ajax_nonce') {
        if (!wp_verify_nonce($_POST['nonce'] ?? '', $action)) {
            wp_die(__('Security check failed.', 'aqualuxe'), __('Nonce verification failed', 'aqualuxe'), array('response' => 403));
        }
    }
    
    /**
     * Sanitize and validate input data
     */
    public static function sanitize_input($data, $type = 'text') {
        switch ($type) {
            case 'email':
                return sanitize_email($data);
            case 'url':
                return esc_url_raw($data);
            case 'int':
                return intval($data);
            case 'float':
                return floatval($data);
            case 'html':
                return wp_kses_post($data);
            case 'textarea':
                return sanitize_textarea_field($data);
            default:
                return sanitize_text_field($data);
        }
    }
    
    /**
     * Escape output data
     */
    public static function escape_output($data, $type = 'text') {
        switch ($type) {
            case 'url':
                return esc_url($data);
            case 'attr':
                return esc_attr($data);
            case 'html':
                return wp_kses_post($data);
            case 'js':
                return esc_js($data);
            default:
                return esc_html($data);
        }
    }
}

// Initialize security features
new AquaLuxe_Security();