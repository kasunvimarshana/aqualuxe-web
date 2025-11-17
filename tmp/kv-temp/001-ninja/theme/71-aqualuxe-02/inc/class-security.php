<?php
/**
 * Security Class
 * 
 * @package AquaLuxe
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Security class for theme hardening
 */
class Security {
    
    /**
     * Instance of this class
     */
    private static $instance = null;
    
    /**
     * Constructor
     */
    private function __construct() {
        $this->init_hooks();
    }
    
    /**
     * Get instance
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Initialize hooks
     */
    private function init_hooks() {
        // Remove WordPress version info
        add_filter('the_generator', '__return_empty_string');
        remove_action('wp_head', 'wp_generator');
        
        // Hide login errors
        add_filter('login_errors', [$this, 'hide_login_errors']);
        
        // Remove WordPress version from scripts and styles
        add_filter('style_loader_src', [$this, 'remove_version_from_assets'], 15, 1);
        add_filter('script_loader_src', [$this, 'remove_version_from_assets'], 15, 1);
        
        // Disable XML-RPC
        add_filter('xmlrpc_enabled', '__return_false');
        
        // Remove RSD link
        remove_action('wp_head', 'rsd_link');
        
        // Remove Windows Live Writer link
        remove_action('wp_head', 'wlwmanifest_link');
        
        // Remove shortlink
        remove_action('wp_head', 'wp_shortlink_wp_head');
        
        // Remove REST API links
        remove_action('wp_head', 'rest_output_link_wp_head');
        remove_action('wp_head', 'wp_oembed_add_discovery_links');
        
        // Remove emoji scripts
        remove_action('wp_head', 'print_emoji_detection_script', 7);
        remove_action('wp_print_styles', 'print_emoji_styles');
        
        // Disable file editing
        if (!defined('DISALLOW_FILE_EDIT')) {
            define('DISALLOW_FILE_EDIT', true);
        }
        
        // Add security headers
        add_action('wp_headers', [$this, 'add_security_headers']);
        add_action('init', [$this, 'add_security_headers']);
        
        // Protect uploads directory
        add_action('init', [$this, 'protect_uploads']);
        
        // Rate limiting for login attempts
        add_action('wp_login_failed', [$this, 'login_failed']);
        add_filter('authenticate', [$this, 'check_login_attempts'], 30, 3);
        
        // CSRF protection for forms
        add_action('wp_footer', [$this, 'add_csrf_token']);
        
        // Content Security Policy
        if (get_theme_mod('aqualuxe_enable_csp', false)) {
            add_action('wp_head', [$this, 'add_content_security_policy'], 1);
        }
        
        // Sanitize file uploads
        add_filter('wp_handle_upload_prefilter', [$this, 'sanitize_file_upload']);
        
        // Block suspicious user agents
        add_action('init', [$this, 'block_suspicious_requests']);
        
        // Log security events
        add_action('wp_login', [$this, 'log_login'], 10, 2);
        add_action('wp_login_failed', [$this, 'log_login_failed']);
        
        // Admin area security
        if (is_admin()) {
            add_action('admin_init', [$this, 'admin_security']);
        }
    }
    
    /**
     * Hide login errors
     */
    public function hide_login_errors() {
        return __('Invalid login credentials.', 'aqualuxe');
    }
    
    /**
     * Remove version from assets
     */
    public function remove_version_from_assets($src) {
        if (strpos($src, 'ver=')) {
            $src = remove_query_arg('ver', $src);
        }
        return $src;
    }
    
    /**
     * Add security headers
     */
    public function add_security_headers($headers = []) {
        // Only add headers if not already set
        if (!headers_sent()) {
            // X-Frame-Options
            if (!isset($headers['X-Frame-Options'])) {
                header('X-Frame-Options: SAMEORIGIN', true);
                $headers['X-Frame-Options'] = 'SAMEORIGIN';
            }
            
            // X-Content-Type-Options
            if (!isset($headers['X-Content-Type-Options'])) {
                header('X-Content-Type-Options: nosniff', true);
                $headers['X-Content-Type-Options'] = 'nosniff';
            }
            
            // X-XSS-Protection
            if (!isset($headers['X-XSS-Protection'])) {
                header('X-XSS-Protection: 1; mode=block', true);
                $headers['X-XSS-Protection'] = '1; mode=block';
            }
            
            // Referrer Policy
            if (!isset($headers['Referrer-Policy'])) {
                header('Referrer-Policy: strict-origin-when-cross-origin', true);
                $headers['Referrer-Policy'] = 'strict-origin-when-cross-origin';
            }
            
            // Permissions Policy
            if (!isset($headers['Permissions-Policy'])) {
                $permissions = [
                    'geolocation=()',
                    'microphone=()',
                    'camera=()',
                    'payment=(self)',
                    'usb=()',
                    'vr=()',
                    'accelerometer=()',
                    'gyroscope=()',
                    'magnetometer=()',
                ];
                header('Permissions-Policy: ' . implode(', ', $permissions), true);
                $headers['Permissions-Policy'] = implode(', ', $permissions);
            }
            
            // Strict Transport Security (HTTPS only)
            if (is_ssl() && !isset($headers['Strict-Transport-Security'])) {
                header('Strict-Transport-Security: max-age=31536000; includeSubDomains', true);
                $headers['Strict-Transport-Security'] = 'max-age=31536000; includeSubDomains';
            }
        }
        
        return $headers;
    }
    
    /**
     * Protect uploads directory
     */
    public function protect_uploads() {
        $upload_dir = wp_upload_dir();
        $htaccess_file = $upload_dir['basedir'] . '/.htaccess';
        
        if (!file_exists($htaccess_file)) {
            $rules = [
                '# Protect uploads directory',
                '<Files "*.php">',
                'Order Deny,Allow',
                'Deny from all',
                '</Files>',
                '',
                '# Prevent direct access to certain file types',
                '<FilesMatch "\.(sql|txt|log|md)$">',
                'Order Deny,Allow',
                'Deny from all',
                '</FilesMatch>',
            ];
            
            file_put_contents($htaccess_file, implode("\n", $rules));
        }
    }
    
    /**
     * Handle login failures for rate limiting
     */
    public function login_failed($username) {
        $ip = $this->get_client_ip();
        $attempts_key = 'login_attempts_' . md5($ip);
        $blocked_key = 'login_blocked_' . md5($ip);
        
        $attempts = get_transient($attempts_key) ?: 0;
        $attempts++;
        
        set_transient($attempts_key, $attempts, 3600); // 1 hour
        
        // Block after 5 failed attempts
        if ($attempts >= 5) {
            set_transient($blocked_key, true, 1800); // 30 minutes
            $this->log_security_event('login_blocked', [
                'ip' => $ip,
                'username' => $username,
                'attempts' => $attempts
            ]);
        }
    }
    
    /**
     * Check login attempts before authentication
     */
    public function check_login_attempts($user, $username, $password) {
        $ip = $this->get_client_ip();
        $blocked_key = 'login_blocked_' . md5($ip);
        
        if (get_transient($blocked_key)) {
            return new WP_Error('login_blocked', __('Too many failed login attempts. Please try again later.', 'aqualuxe'));
        }
        
        return $user;
    }
    
    /**
     * Add CSRF token to forms
     */
    public function add_csrf_token() {
        if (!is_admin() && !wp_doing_ajax()) {
            $nonce = wp_create_nonce('aqualuxe_csrf');
            echo '<script>window.aqualuxeCSRF = "' . $nonce . '";</script>';
        }
    }
    
    /**
     * Add Content Security Policy
     */
    public function add_content_security_policy() {
        $domain = parse_url(home_url(), PHP_URL_HOST);
        
        $directives = [
            "default-src 'self'",
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' " . $domain,
            "style-src 'self' 'unsafe-inline' " . $domain,
            "img-src 'self' data: " . $domain,
            "font-src 'self' " . $domain,
            "connect-src 'self' " . $domain,
            "frame-src 'self'",
            "object-src 'none'",
            "base-uri 'self'",
            "form-action 'self'",
        ];
        
        // Add external domains if needed
        $external_domains = get_theme_mod('aqualuxe_csp_external_domains', '');
        if ($external_domains) {
            $domains = array_map('trim', explode(',', $external_domains));
            foreach ($domains as $external_domain) {
                if ($external_domain) {
                    $directives = array_map(function($directive) use ($external_domain) {
                        if (strpos($directive, 'script-src') === 0 || 
                            strpos($directive, 'style-src') === 0 || 
                            strpos($directive, 'img-src') === 0) {
                            return $directive . ' ' . $external_domain;
                        }
                        return $directive;
                    }, $directives);
                }
            }
        }
        
        $csp = implode('; ', $directives);
        header("Content-Security-Policy: $csp");
    }
    
    /**
     * Sanitize file uploads
     */
    public function sanitize_file_upload($file) {
        $filename = $file['name'];
        $filetype = wp_check_filetype($filename);
        
        // Block potentially dangerous file types
        $dangerous_extensions = [
            'php', 'php3', 'php4', 'php5', 'phtml', 'asp', 'aspx', 'jsp', 'exe', 'com', 'bat', 'cmd', 'scr', 'vbs', 'js'
        ];
        
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        if (in_array($ext, $dangerous_extensions)) {
            $file['error'] = __('File type not allowed for security reasons.', 'aqualuxe');
            return $file;
        }
        
        // Check file content for PHP code
        if ($file['tmp_name'] && is_uploaded_file($file['tmp_name'])) {
            $content = file_get_contents($file['tmp_name']);
            if (preg_match('/<\?php|<\?=|<script/i', $content)) {
                $file['error'] = __('File contains potentially malicious content.', 'aqualuxe');
                return $file;
            }
        }
        
        return $file;
    }
    
    /**
     * Block suspicious requests
     */
    public function block_suspicious_requests() {
        $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        $request_uri = $_SERVER['REQUEST_URI'] ?? '';
        
        // Block common malicious user agents
        $blocked_agents = [
            'sqlmap',
            'nikto',
            'nessus',
            'masscan',
            'nmap',
            'dirbuster',
            'gobuster',
            'wpscan',
        ];
        
        foreach ($blocked_agents as $agent) {
            if (stripos($user_agent, $agent) !== false) {
                $this->block_request('Suspicious user agent: ' . $agent);
            }
        }
        
        // Block suspicious request patterns
        $suspicious_patterns = [
            '/wp-config\.php',
            '/\.env',
            '/admin\.php',
            '/phpinfo\.php',
            '/shell\.php',
            '/wp-admin/install\.php',
            '/\.\./\.\./\.\./etc/passwd',
            '/proc/self/environ',
        ];
        
        foreach ($suspicious_patterns as $pattern) {
            if (preg_match($pattern, $request_uri)) {
                $this->block_request('Suspicious request pattern: ' . $pattern);
            }
        }
        
        // Block requests with suspicious query strings
        $query_string = $_SERVER['QUERY_STRING'] ?? '';
        $suspicious_queries = [
            'union.*select',
            'concat.*\(',
            'base64_decode',
            'eval\(',
            'system\(',
            'exec\(',
            'shell_exec',
        ];
        
        foreach ($suspicious_queries as $query) {
            if (preg_match('/' . $query . '/i', $query_string)) {
                $this->block_request('Suspicious query string: ' . $query);
            }
        }
    }
    
    /**
     * Block request and log
     */
    private function block_request($reason) {
        $ip = $this->get_client_ip();
        
        $this->log_security_event('request_blocked', [
            'ip' => $ip,
            'reason' => $reason,
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
            'request_uri' => $_SERVER['REQUEST_URI'] ?? '',
        ]);
        
        // Send 403 and exit
        status_header(403);
        exit(__('Access denied for security reasons.', 'aqualuxe'));
    }
    
    /**
     * Log successful login
     */
    public function log_login($user_login, $user) {
        $this->log_security_event('login_success', [
            'user_id' => $user->ID,
            'username' => $user_login,
            'ip' => $this->get_client_ip(),
        ]);
    }
    
    /**
     * Log failed login
     */
    public function log_login_failed($username) {
        $this->log_security_event('login_failed', [
            'username' => $username,
            'ip' => $this->get_client_ip(),
        ]);
    }
    
    /**
     * Admin area security measures
     */
    public function admin_security() {
        // Force strong passwords for admin users
        if (get_theme_mod('aqualuxe_force_strong_passwords', true)) {
            add_action('user_profile_update_errors', [$this, 'validate_password_strength'], 10, 3);
        }
        
        // Hide admin bar for non-admin users
        if (!current_user_can('manage_options') && get_theme_mod('aqualuxe_hide_admin_bar', false)) {
            show_admin_bar(false);
        }
        
        // Limit login session time
        if (get_theme_mod('aqualuxe_limit_login_session', false)) {
            add_filter('auth_cookie_expiration', [$this, 'limit_login_session']);
        }
    }
    
    /**
     * Validate password strength
     */
    public function validate_password_strength($errors, $update, $user) {
        $password = $_POST['pass1'] ?? '';
        
        if ($password && $user->has_cap('manage_options')) {
            if (strlen($password) < 12) {
                $errors->add('password_too_short', __('Admin password must be at least 12 characters long.', 'aqualuxe'));
            }
            
            if (!preg_match('/[A-Z]/', $password)) {
                $errors->add('password_no_uppercase', __('Admin password must contain at least one uppercase letter.', 'aqualuxe'));
            }
            
            if (!preg_match('/[a-z]/', $password)) {
                $errors->add('password_no_lowercase', __('Admin password must contain at least one lowercase letter.', 'aqualuxe'));
            }
            
            if (!preg_match('/[0-9]/', $password)) {
                $errors->add('password_no_number', __('Admin password must contain at least one number.', 'aqualuxe'));
            }
            
            if (!preg_match('/[^A-Za-z0-9]/', $password)) {
                $errors->add('password_no_special', __('Admin password must contain at least one special character.', 'aqualuxe'));
            }
        }
    }
    
    /**
     * Limit login session time
     */
    public function limit_login_session($expiration) {
        $session_limit = get_theme_mod('aqualuxe_session_limit_hours', 24) * HOUR_IN_SECONDS;
        return min($expiration, $session_limit);
    }
    
    /**
     * Get client IP address
     */
    private function get_client_ip() {
        $ip_headers = [
            'HTTP_CF_CONNECTING_IP',     // Cloudflare
            'HTTP_CLIENT_IP',
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_FORWARDED',
            'HTTP_X_CLUSTER_CLIENT_IP',
            'HTTP_FORWARDED_FOR',
            'HTTP_FORWARDED',
            'REMOTE_ADDR'
        ];
        
        foreach ($ip_headers as $header) {
            if (isset($_SERVER[$header]) && !empty($_SERVER[$header])) {
                $ips = explode(',', $_SERVER[$header]);
                $ip = trim($ips[0]);
                
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                    return $ip;
                }
            }
        }
        
        return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    }
    
    /**
     * Log security events
     */
    private function log_security_event($event_type, $data = []) {
        if (!get_theme_mod('aqualuxe_enable_security_logging', true)) {
            return;
        }
        
        $log_entry = [
            'timestamp' => current_time('mysql'),
            'event_type' => $event_type,
            'data' => $data,
            'user_id' => get_current_user_id(),
            'ip' => $this->get_client_ip(),
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
        ];
        
        // Store in custom table or use WordPress logs
        $logs = get_option('aqualuxe_security_logs', []);
        $logs[] = $log_entry;
        
        // Keep only last 1000 entries
        if (count($logs) > 1000) {
            $logs = array_slice($logs, -1000);
        }
        
        update_option('aqualuxe_security_logs', $logs);
        
        // Also log critical events to error log
        if (in_array($event_type, ['login_blocked', 'request_blocked'])) {
            error_log("AquaLuxe Security: $event_type - " . wp_json_encode($data));
        }
    }
    
    /**
     * Get security logs (for admin interface)
     */
    public function get_security_logs($limit = 100) {
        $logs = get_option('aqualuxe_security_logs', []);
        return array_slice(array_reverse($logs), 0, $limit);
    }
    
    /**
     * Clear security logs
     */
    public function clear_security_logs() {
        delete_option('aqualuxe_security_logs');
    }
    
    /**
     * Get security status
     */
    public function get_security_status() {
        return [
            'wp_version_hidden' => !has_action('wp_head', 'wp_generator'),
            'xmlrpc_disabled' => !apply_filters('xmlrpc_enabled', true),
            'file_editing_disabled' => defined('DISALLOW_FILE_EDIT') && DISALLOW_FILE_EDIT,
            'uploads_protected' => file_exists(wp_upload_dir()['basedir'] . '/.htaccess'),
            'strong_passwords_enforced' => get_theme_mod('aqualuxe_force_strong_passwords', true),
            'security_headers_enabled' => true,
            'login_protection_enabled' => true,
            'security_logging_enabled' => get_theme_mod('aqualuxe_enable_security_logging', true),
        ];
    }
}
