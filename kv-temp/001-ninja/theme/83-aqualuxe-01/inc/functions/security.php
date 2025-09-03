<?php
/**
 * Security Functions
 * 
 * Security hardening and protection measures
 * Implements CSRF protection, input sanitization, and secure coding practices
 * 
 * @package KV_Enterprise
 * @version 1.0.0
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit('Direct access forbidden.');
}

/**
 * Security Manager Class
 * Centralized security management following Single Responsibility Principle
 */
class KV_Security_Manager {
    
    /**
     * Initialize security measures
     * 
     * @return void
     */
    public static function init() {
        // Remove WordPress version from head
        remove_action('wp_head', 'wp_generator');
        
        // Remove version from scripts and styles
        add_filter('style_loader_src', [__CLASS__, 'remove_version_from_assets'], 9999);
        add_filter('script_loader_src', [__CLASS__, 'remove_version_from_assets'], 9999);
        
        // Hide WordPress version in RSS feeds
        add_filter('the_generator', '__return_empty_string');
        
        // Remove Windows Live Writer manifest
        remove_action('wp_head', 'wlwmanifest_link');
        
        // Remove RSD link
        remove_action('wp_head', 'rsd_link');
        
        // Remove shortlink
        remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);
        
        // Disable XML-RPC if not needed
        if (!kv_get_theme_option('enable_xmlrpc', false)) {
            add_filter('xmlrpc_enabled', '__return_false');
        }
        
        // Disable user enumeration
        add_action('init', [__CLASS__, 'disable_user_enumeration']);
        
        // Add security headers
        add_action('send_headers', [__CLASS__, 'add_security_headers']);
        
        // Protect against malicious file uploads
        add_filter('wp_check_filetype_and_ext', [__CLASS__, 'check_file_type_and_ext'], 10, 4);
        
        // Sanitize file names
        add_filter('sanitize_file_name', [__CLASS__, 'sanitize_file_name'], 10, 1);
        
        // Login security
        add_action('wp_login_failed', [__CLASS__, 'log_failed_login']);
        add_filter('authenticate', [__CLASS__, 'check_login_attempts'], 30, 3);
        
        // Admin security
        add_action('admin_init', [__CLASS__, 'admin_security_checks']);
        
        // Content filtering
        add_filter('the_content', [__CLASS__, 'filter_malicious_content']);
        add_filter('comment_text', [__CLASS__, 'filter_malicious_content']);
        
        // CSRF protection
        add_action('init', [__CLASS__, 'init_csrf_protection']);
    }
    
    /**
     * Remove version from asset URLs
     * 
     * @param string $src Asset URL
     * @return string Clean URL
     */
    public static function remove_version_from_assets($src) {
        if (strpos($src, 'ver=')) {
            $src = remove_query_arg('ver', $src);
        }
        return $src;
    }
    
    /**
     * Disable user enumeration
     * 
     * @return void
     */
    public static function disable_user_enumeration() {
        if (!is_admin() && isset($_GET['author'])) {
            wp_redirect(home_url(), 301);
            exit;
        }
        
        // Block REST API user endpoints for non-authenticated users
        add_filter('rest_endpoints', function($endpoints) {
            if (!is_user_logged_in()) {
                if (isset($endpoints['/wp/v2/users'])) {
                    unset($endpoints['/wp/v2/users']);
                }
                if (isset($endpoints['/wp/v2/users/(?P<id>[\d]+)'])) {
                    unset($endpoints['/wp/v2/users/(?P<id>[\d]+)']);
                }
            }
            return $endpoints;
        });
    }
    
    /**
     * Add security headers
     * 
     * @return void
     */
    public static function add_security_headers() {
        // Content Security Policy
        $csp_directives = [
            "default-src 'self'",
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://www.google.com https://www.gstatic.com",
            "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com",
            "font-src 'self' https://fonts.gstatic.com",
            "img-src 'self' data: https:",
            "connect-src 'self'",
            "frame-src 'self' https://www.youtube.com https://player.vimeo.com",
            "object-src 'none'",
            "base-uri 'self'",
            "form-action 'self'"
        ];
        
        $csp_directives = apply_filters('kv_csp_directives', $csp_directives);
        $csp = implode('; ', $csp_directives);
        
        header("Content-Security-Policy: {$csp}");
        
        // Other security headers
        header('X-Content-Type-Options: nosniff');
        header('X-Frame-Options: SAMEORIGIN');
        header('X-XSS-Protection: 1; mode=block');
        header('Referrer-Policy: strict-origin-when-cross-origin');
        header('Permissions-Policy: geolocation=(), microphone=(), camera=()');
        
        // HSTS for HTTPS sites
        if (is_ssl()) {
            header('Strict-Transport-Security: max-age=31536000; includeSubDomains; preload');
        }
    }
    
    /**
     * Enhanced file type checking
     * 
     * @param array  $wp_check_filetype_and_ext File type data
     * @param string $file                      File path
     * @param string $filename                  File name
     * @param array  $mimes                     Allowed mime types
     * @return array Modified file type data
     */
    public static function check_file_type_and_ext($wp_check_filetype_and_ext, $file, $filename, $mimes) {
        // Get file extension
        $filetype = wp_check_filetype($filename, $mimes);
        
        // Dangerous file extensions
        $dangerous_extensions = [
            'php', 'php3', 'php4', 'php5', 'phtml', 'pl', 'py', 'jsp', 'asp', 'sh', 'cgi',
            'exe', 'bat', 'com', 'scr', 'vbs', 'ws', 'wsc', 'wsf', 'wsh'
        ];
        
        // Block dangerous extensions
        if (in_array($filetype['ext'], $dangerous_extensions)) {
            $wp_check_filetype_and_ext['ext'] = false;
            $wp_check_filetype_and_ext['type'] = false;
        }
        
        // Additional MIME type checking
        if ($file && is_readable($file)) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $real_mime = finfo_file($finfo, $file);
            finfo_close($finfo);
            
            // Check if reported MIME matches actual MIME
            if ($real_mime && $real_mime !== $wp_check_filetype_and_ext['type']) {
                $wp_check_filetype_and_ext['ext'] = false;
                $wp_check_filetype_and_ext['type'] = false;
            }
        }
        
        return $wp_check_filetype_and_ext;
    }
    
    /**
     * Sanitize uploaded file names
     * 
     * @param string $filename File name
     * @return string Sanitized file name
     */
    public static function sanitize_file_name($filename) {
        // Remove special characters
        $filename = preg_replace('/[^a-zA-Z0-9._-]/', '', $filename);
        
        // Prevent double extensions
        $filename = preg_replace('/\.+/', '.', $filename);
        
        // Ensure filename isn't too long
        if (strlen($filename) > 100) {
            $info = pathinfo($filename);
            $name = substr($info['filename'], 0, 90);
            $filename = $name . '.' . $info['extension'];
        }
        
        return $filename;
    }
    
    /**
     * Log failed login attempts
     * 
     * @param string $username Username
     * @return void
     */
    public static function log_failed_login($username) {
        $ip = self::get_user_ip();
        $attempts = get_transient("login_attempts_{$ip}") ?: 0;
        $attempts++;
        
        // Store attempt count for 15 minutes
        set_transient("login_attempts_{$ip}", $attempts, 15 * MINUTE_IN_SECONDS);
        
        // Log the attempt
        kv_log_error("Failed login attempt for user '{$username}' from IP: {$ip}. Attempt #{$attempts}", 'warning');
        
        // Send email notification for multiple failed attempts
        if ($attempts >= 3) {
            $subject = 'Multiple Failed Login Attempts - ' . get_bloginfo('name');
            $message = "Multiple failed login attempts detected:\n\n";
            $message .= "Username: {$username}\n";
            $message .= "IP Address: {$ip}\n";
            $message .= "Attempts: {$attempts}\n";
            $message .= "Time: " . current_time('mysql') . "\n";
            
            wp_mail(get_option('admin_email'), $subject, $message);
        }
    }
    
    /**
     * Check login attempts and block if necessary
     * 
     * @param WP_User|WP_Error|null $user     User object or error
     * @param string                $username Username
     * @param string                $password Password
     * @return WP_User|WP_Error User object or error
     */
    public static function check_login_attempts($user, $username, $password) {
        if (empty($username) || empty($password)) {
            return $user;
        }
        
        $ip = self::get_user_ip();
        $attempts = get_transient("login_attempts_{$ip}") ?: 0;
        
        // Block after 5 failed attempts
        if ($attempts >= 5) {
            return new WP_Error('login_blocked', __('Too many failed login attempts. Please try again later.', KV_THEME_TEXTDOMAIN));
        }
        
        return $user;
    }
    
    /**
     * Admin security checks
     * 
     * @return void
     */
    public static function admin_security_checks() {
        // Force strong passwords for administrators
        if (current_user_can('administrator')) {
            add_action('user_profile_update_errors', [__CLASS__, 'enforce_strong_passwords'], 0, 3);
        }
        
        // Hide sensitive information from non-admin users
        if (!current_user_can('administrator')) {
            add_filter('plugin_row_meta', [__CLASS__, 'hide_plugin_details'], 10, 2);
            add_filter('theme_row_meta', [__CLASS__, 'hide_theme_details'], 10, 2);
        }
    }
    
    /**
     * Enforce strong passwords
     * 
     * @param WP_Error $errors Error object
     * @param bool     $update Whether updating existing user
     * @param WP_User  $user   User object
     * @return void
     */
    public static function enforce_strong_passwords($errors, $update, $user) {
        if (!isset($_POST['pass1']) || empty($_POST['pass1'])) {
            return;
        }
        
        $password = $_POST['pass1'];
        
        // Check password strength
        if (strlen($password) < 12) {
            $errors->add('weak_password', __('Password must be at least 12 characters long.', KV_THEME_TEXTDOMAIN));
        }
        
        if (!preg_match('/[A-Z]/', $password)) {
            $errors->add('weak_password', __('Password must contain at least one uppercase letter.', KV_THEME_TEXTDOMAIN));
        }
        
        if (!preg_match('/[a-z]/', $password)) {
            $errors->add('weak_password', __('Password must contain at least one lowercase letter.', KV_THEME_TEXTDOMAIN));
        }
        
        if (!preg_match('/[0-9]/', $password)) {
            $errors->add('weak_password', __('Password must contain at least one number.', KV_THEME_TEXTDOMAIN));
        }
        
        if (!preg_match('/[^A-Za-z0-9]/', $password)) {
            $errors->add('weak_password', __('Password must contain at least one special character.', KV_THEME_TEXTDOMAIN));
        }
    }
    
    /**
     * Hide plugin details from non-admin users
     * 
     * @param array  $meta Plugin meta
     * @param string $file Plugin file
     * @return array Modified meta
     */
    public static function hide_plugin_details($meta, $file) {
        return [];
    }
    
    /**
     * Hide theme details from non-admin users
     * 
     * @param array  $meta Theme meta
     * @param string $theme Theme name
     * @return array Modified meta
     */
    public static function hide_theme_details($meta, $theme) {
        return [];
    }
    
    /**
     * Filter malicious content
     * 
     * @param string $content Content to filter
     * @return string Filtered content
     */
    public static function filter_malicious_content($content) {
        // Remove dangerous HTML tags and attributes
        $content = wp_kses($content, wp_kses_allowed_html('post'));
        
        // Remove javascript: URLs
        $content = preg_replace('/javascript\s*:/i', '', $content);
        
        // Remove data: URLs (except images)
        $content = preg_replace('/data\s*:(?!image\/)/i', '', $content);
        
        // Remove potentially dangerous protocols
        $dangerous_protocols = ['vbscript', 'livescript', 'ms-its', 'mhtml', 'mocha'];
        foreach ($dangerous_protocols as $protocol) {
            $content = preg_replace('/' . preg_quote($protocol) . '\s*:/i', '', $content);
        }
        
        return $content;
    }
    
    /**
     * Initialize CSRF protection
     * 
     * @return void
     */
    public static function init_csrf_protection() {
        // Add CSRF token to forms
        add_action('wp_footer', [__CLASS__, 'add_csrf_token_to_forms']);
        
        // Verify CSRF token on form submissions
        add_action('init', [__CLASS__, 'verify_csrf_token']);
    }
    
    /**
     * Add CSRF token to forms
     * 
     * @return void
     */
    public static function add_csrf_token_to_forms() {
        if (!is_admin()) {
            $token = self::generate_csrf_token();
            echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    var forms = document.querySelectorAll('form');
                    forms.forEach(function(form) {
                        if (!form.querySelector('input[name=\"kv_csrf_token\"]')) {
                            var input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = 'kv_csrf_token';
                            input.value = '{$token}';
                            form.appendChild(input);
                        }
                    });
                });
            </script>";
        }
    }
    
    /**
     * Verify CSRF token
     * 
     * @return void
     */
    public static function verify_csrf_token() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !is_admin() && !kv_is_ajax()) {
            $token = $_POST['kv_csrf_token'] ?? '';
            
            if (!self::validate_csrf_token($token)) {
                wp_die('Security check failed', 'Security Error', ['response' => 403]);
            }
        }
    }
    
    /**
     * Generate CSRF token
     * 
     * @return string CSRF token
     */
    public static function generate_csrf_token() {
        if (!session_id()) {
            session_start();
        }
        
        if (!isset($_SESSION['kv_csrf_token'])) {
            $_SESSION['kv_csrf_token'] = bin2hex(random_bytes(32));
        }
        
        return $_SESSION['kv_csrf_token'];
    }
    
    /**
     * Validate CSRF token
     * 
     * @param string $token Token to validate
     * @return bool Whether token is valid
     */
    public static function validate_csrf_token($token) {
        if (!session_id()) {
            session_start();
        }
        
        $session_token = $_SESSION['kv_csrf_token'] ?? '';
        
        return !empty($session_token) && hash_equals($session_token, $token);
    }
    
    /**
     * Get user IP address
     * 
     * @return string IP address
     */
    public static function get_user_ip() {
        // Check for shared internet/proxy
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            return sanitize_text_field($_SERVER['HTTP_CLIENT_IP']);
        }
        // Check for IP behind proxy
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return sanitize_text_field($_SERVER['HTTP_X_FORWARDED_FOR']);
        }
        // Check for remote IP
        elseif (!empty($_SERVER['REMOTE_ADDR'])) {
            return sanitize_text_field($_SERVER['REMOTE_ADDR']);
        }
        
        return '0.0.0.0';
    }
    
    /**
     * Sanitize array recursively
     * 
     * @param array $array Array to sanitize
     * @return array Sanitized array
     */
    public static function sanitize_array($array) {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $array[$key] = self::sanitize_array($value);
            } else {
                $array[$key] = sanitize_text_field($value);
            }
        }
        
        return $array;
    }
    
    /**
     * Check if IP is whitelisted
     * 
     * @param string $ip IP address
     * @return bool Whether IP is whitelisted
     */
    public static function is_ip_whitelisted($ip) {
        $whitelist = kv_get_theme_option('ip_whitelist', []);
        return in_array($ip, $whitelist, true);
    }
    
    /**
     * Check if IP is blacklisted
     * 
     * @param string $ip IP address
     * @return bool Whether IP is blacklisted
     */
    public static function is_ip_blacklisted($ip) {
        $blacklist = kv_get_theme_option('ip_blacklist', []);
        return in_array($ip, $blacklist, true);
    }
    
    /**
     * Block malicious bots
     * 
     * @return void
     */
    public static function block_malicious_bots() {
        $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        
        $blocked_bots = [
            'semrush', 'ahrefs', 'mj12bot', 'dotbot', 'rogerbot', 'exabot',
            'facebot', 'ia_archiver', 'spbot', 'crawler', 'spider'
        ];
        
        foreach ($blocked_bots as $bot) {
            if (stripos($user_agent, $bot) !== false) {
                header('HTTP/1.1 403 Forbidden');
                exit('Forbidden');
            }
        }
    }
}

// Initialize security measures
add_action('init', ['KV_Security_Manager', 'init']);
add_action('init', ['KV_Security_Manager', 'block_malicious_bots'], 1);
