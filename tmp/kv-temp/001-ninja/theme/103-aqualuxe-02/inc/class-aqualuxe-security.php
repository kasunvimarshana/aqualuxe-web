<?php
/**
 * AquaLuxe Security Class
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
 * Security Class
 *
 * @class AquaLuxe_Security
 */
class AquaLuxe_Security {

    /**
     * Single instance of the class
     *
     * @var AquaLuxe_Security
     */
    protected static $_instance = null;

    /**
     * Main AquaLuxe_Security Instance
     *
     * @static
     * @return AquaLuxe_Security - Main instance
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
        // Security headers
        add_action('send_headers', array($this, 'send_security_headers'));
        
        // Remove WordPress version
        add_filter('the_generator', '__return_empty_string');
        remove_action('wp_head', 'wp_generator');
        
        // Remove Windows Live Writer manifest
        remove_action('wp_head', 'wlwmanifest_link');
        
        // Remove RSD link
        remove_action('wp_head', 'rsd_link');
        
        // Remove shortlink
        remove_action('wp_head', 'wp_shortlink_wp_head');
        
        // Disable XML-RPC if not needed
        if (get_theme_mod('aqualuxe_disable_xmlrpc', true)) {
            add_filter('xmlrpc_enabled', '__return_false');
            add_filter('xmlrpc_methods', array($this, 'disable_xmlrpc_methods'));
        }
        
        // Hide login errors
        add_filter('login_errors', array($this, 'hide_login_errors'));
        
        // Limit login attempts (basic implementation)
        add_action('wp_login_failed', array($this, 'track_failed_login'));
        add_filter('authenticate', array($this, 'check_failed_logins'), 30, 3);
        
        // Disable file editing in admin
        if (!defined('DISALLOW_FILE_EDIT')) {
            define('DISALLOW_FILE_EDIT', true);
        }
        
        // Remove WordPress version from CSS and JS
        add_filter('style_loader_src', array($this, 'remove_version_from_assets'), 15, 1);
        add_filter('script_loader_src', array($this, 'remove_version_from_assets'), 15, 1);
        
        // Content Security Policy
        add_action('wp_head', array($this, 'add_csp_meta'), 1);
        
        // Sanitize file uploads
        add_filter('wp_handle_upload_prefilter', array($this, 'sanitize_file_upload'));
        
        // Block suspicious user agents
        add_action('init', array($this, 'block_suspicious_user_agents'));
        
        // Protect against SQL injection in URLs
        add_action('init', array($this, 'protect_against_sql_injection'));
    }

    /**
     * Send security headers
     */
    public function send_security_headers() {
        if (!is_admin()) {
            // X-Content-Type-Options
            header('X-Content-Type-Options: nosniff');
            
            // X-Frame-Options
            header('X-Frame-Options: SAMEORIGIN');
            
            // X-XSS-Protection
            header('X-XSS-Protection: 1; mode=block');
            
            // Referrer Policy
            header('Referrer-Policy: strict-origin-when-cross-origin');
            
            // Permissions Policy
            header('Permissions-Policy: geolocation=(), microphone=(), camera=()');
            
            // HSTS (only if HTTPS)
            if (is_ssl()) {
                header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
            }
        }
    }

    /**
     * Disable XML-RPC methods
     */
    public function disable_xmlrpc_methods($methods) {
        return array();
    }

    /**
     * Hide login errors
     */
    public function hide_login_errors() {
        return esc_html__('Invalid credentials.', 'aqualuxe');
    }

    /**
     * Track failed login attempts
     */
    public function track_failed_login($username) {
        $ip = $this->get_user_ip();
        $transient_key = 'aqualuxe_failed_login_' . md5($ip);
        
        $failed_attempts = get_transient($transient_key);
        $failed_attempts = $failed_attempts ? $failed_attempts : 0;
        $failed_attempts++;
        
        set_transient($transient_key, $failed_attempts, 15 * MINUTE_IN_SECONDS);
        
        // Log the attempt
        if (function_exists('error_log')) {
            error_log('AquaLuxe Security: Failed login attempt for username "' . $username . '" from IP: ' . $ip);
        }
    }

    /**
     * Check failed logins and block if necessary
     */
    public function check_failed_logins($user, $username, $password) {
        if (is_wp_error($user)) {
            return $user;
        }
        
        $ip = $this->get_user_ip();
        $transient_key = 'aqualuxe_failed_login_' . md5($ip);
        $failed_attempts = get_transient($transient_key);
        
        if ($failed_attempts && $failed_attempts >= 5) {
            return new WP_Error('too_many_attempts', esc_html__('Too many failed login attempts. Please try again later.', 'aqualuxe'));
        }
        
        return $user;
    }

    /**
     * Get user IP address
     */
    private function get_user_ip() {
        $ip_keys = array('HTTP_CF_CONNECTING_IP', 'HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR');
        
        foreach ($ip_keys as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (array_map('trim', explode(',', $_SERVER[$key])) as $ip) {
                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
                        return $ip;
                    }
                }
            }
        }
        
        return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    }

    /**
     * Remove version from CSS and JS assets
     */
    public function remove_version_from_assets($src) {
        if (strpos($src, 'ver=')) {
            $src = remove_query_arg('ver', $src);
        }
        return $src;
    }

    /**
     * Add Content Security Policy meta tag
     */
    public function add_csp_meta() {
        $csp = get_theme_mod('aqualuxe_csp_policy', '');
        
        if (empty($csp)) {
            $csp = "default-src 'self'; " .
                   "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://fonts.googleapis.com https://fonts.gstatic.com; " .
                   "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; " .
                   "font-src 'self' https://fonts.gstatic.com; " .
                   "img-src 'self' data: https:; " .
                   "connect-src 'self';";
        }
        
        echo '<meta http-equiv="Content-Security-Policy" content="' . esc_attr($csp) . '">' . "\n";
    }

    /**
     * Sanitize file uploads
     */
    public function sanitize_file_upload($file) {
        $allowed_types = array(
            'jpg', 'jpeg', 'png', 'gif', 'webp', 'svg',
            'pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx',
            'mp4', 'mp3', 'wav', 'avi', 'mov',
            'zip', 'rar', '7z'
        );
        
        $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        
        if (!in_array(strtolower($file_extension), $allowed_types)) {
            $file['error'] = esc_html__('File type not allowed.', 'aqualuxe');
        }
        
        // Check file size (default 10MB)
        $max_size = get_theme_mod('aqualuxe_max_upload_size', 10) * 1024 * 1024;
        if ($file['size'] > $max_size) {
            $file['error'] = esc_html__('File size too large.', 'aqualuxe');
        }
        
        return $file;
    }

    /**
     * Block suspicious user agents
     */
    public function block_suspicious_user_agents() {
        $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        
        $blocked_agents = array(
            'wget', 'curl', 'python', 'libwww', 'bot', 'crawler', 'scraper'
        );
        
        foreach ($blocked_agents as $agent) {
            if (stripos($user_agent, $agent) !== false) {
                wp_die(esc_html__('Access denied.', 'aqualuxe'), 403);
            }
        }
    }

    /**
     * Protect against SQL injection in URLs
     */
    public function protect_against_sql_injection() {
        $suspicious_patterns = array(
            'union', 'select', 'insert', 'update', 'delete', 'drop', 'create', 'alter',
            'exec', 'execute', 'script', 'onload', 'onerror', 'javascript:'
        );
        
        $query_string = $_SERVER['QUERY_STRING'] ?? '';
        $request_uri = $_SERVER['REQUEST_URI'] ?? '';
        
        foreach ($suspicious_patterns as $pattern) {
            if (stripos($query_string, $pattern) !== false || stripos($request_uri, $pattern) !== false) {
                wp_die(esc_html__('Suspicious activity detected.', 'aqualuxe'), 403);
            }
        }
    }

    /**
     * Sanitize input data
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
            default:
                return sanitize_text_field($input);
        }
    }

    /**
     * Verify nonce
     */
    public static function verify_nonce($nonce, $action = 'aqualuxe_nonce') {
        return wp_verify_nonce($nonce, $action);
    }

    /**
     * Check user capabilities
     */
    public static function check_capability($capability = 'manage_options') {
        return current_user_can($capability);
    }
}