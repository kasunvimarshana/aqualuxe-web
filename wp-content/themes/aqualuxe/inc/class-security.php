<?php
/**
 * AquaLuxe Security Class
 * 
 * Handles theme security implementations and hardening.
 * 
 * @package AquaLuxe
 * @version 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class AquaLuxe_Security {
    
    /**
     * Initialize security features
     */
    public static function init() {
        add_action('init', [__CLASS__, 'setup_security_headers']);
        add_action('wp_loaded', [__CLASS__, 'sanitize_global_arrays']);
        add_filter('wp_mail_from', [__CLASS__, 'secure_mail_from']);
        add_filter('wp_mail_from_name', [__CLASS__, 'secure_mail_from_name']);
        
        // AJAX security
        add_action('wp_ajax_aqualuxe_secure_action', [__CLASS__, 'verify_ajax_nonce']);
        add_action('wp_ajax_nopriv_aqualuxe_secure_action', [__CLASS__, 'verify_ajax_nonce']);
        
        // Form security
        add_action('wp_head', [__CLASS__, 'add_csrf_meta']);
        
        // Content security
        add_filter('the_content', [__CLASS__, 'secure_content']);
        add_filter('comment_text', [__CLASS__, 'secure_content']);
        
        // File upload security
        add_filter('wp_handle_upload_prefilter', [__CLASS__, 'secure_file_uploads']);
        
        // Login security
        add_action('wp_login_failed', [__CLASS__, 'log_failed_login']);
        add_filter('authenticate', [__CLASS__, 'limit_login_attempts'], 30, 3);
    }
    
    /**
     * Setup security headers
     */
    public static function setup_security_headers() {
        if (!headers_sent()) {
            // X-Frame-Options header
            header('X-Frame-Options: SAMEORIGIN');
            
            // X-Content-Type-Options header
            header('X-Content-Type-Options: nosniff');
            
            // X-XSS-Protection header
            header('X-XSS-Protection: 1; mode=block');
            
            // Referrer Policy
            header('Referrer-Policy: strict-origin-when-cross-origin');
            
            // Content Security Policy (basic)
            $csp = "default-src 'self'; ";
            $csp .= "script-src 'self' 'unsafe-inline' 'unsafe-eval' *.google.com *.googleapis.com; ";
            $csp .= "style-src 'self' 'unsafe-inline' *.googleapis.com; ";
            $csp .= "img-src 'self' data: *.gravatar.com *.wp.com; ";
            $csp .= "font-src 'self' *.googleapis.com *.gstatic.com; ";
            $csp .= "connect-src 'self'; ";
            $csp .= "frame-src 'self' *.youtube.com *.vimeo.com;";
            
            header("Content-Security-Policy: $csp");
        }
    }
    
    /**
     * Sanitize global arrays
     */
    public static function sanitize_global_arrays() {
        // Sanitize $_GET
        if (!empty($_GET)) {
            foreach ($_GET as $key => $value) {
                $_GET[$key] = self::deep_sanitize($value);
            }
        }
        
        // Sanitize $_POST
        if (!empty($_POST)) {
            foreach ($_POST as $key => $value) {
                $_POST[$key] = self::deep_sanitize($value);
            }
        }
        
        // Sanitize $_REQUEST
        if (!empty($_REQUEST)) {
            foreach ($_REQUEST as $key => $value) {
                $_REQUEST[$key] = self::deep_sanitize($value);
            }
        }
    }
    
    /**
     * Deep sanitize data recursively
     */
    private static function deep_sanitize($data) {
        if (is_array($data)) {
            return array_map([__CLASS__, 'deep_sanitize'], $data);
        }
        
        if (is_string($data)) {
            // Remove null bytes
            $data = str_replace(chr(0), '', $data);
            
            // Basic sanitization
            $data = trim($data);
            
            // Remove potentially dangerous patterns
            $dangerous_patterns = [
                '/\b(union|select|insert|update|delete|drop|create|alter|exec|execute)\b/i',
                '/<script[^>]*>.*?<\/script>/is',
                '/javascript:/i',
                '/on\w+\s*=/i'
            ];
            
            foreach ($dangerous_patterns as $pattern) {
                $data = preg_replace($pattern, '', $data);
            }
        }
        
        return $data;
    }
    
    /**
     * Secure mail from address
     */
    public static function secure_mail_from($email) {
        $domain = parse_url(home_url(), PHP_URL_HOST);
        return 'noreply@' . $domain;
    }
    
    /**
     * Secure mail from name
     */
    public static function secure_mail_from_name($name) {
        return get_bloginfo('name');
    }
    
    /**
     * Verify AJAX nonce
     */
    public static function verify_ajax_nonce() {
        if (!wp_verify_nonce($_POST['nonce'], 'aqualuxe_nonce')) {
            wp_die('Security check failed');
        }
        
        // Continue with AJAX action
        do_action('aqualuxe_verified_ajax_action');
    }
    
    /**
     * Add CSRF meta tag
     */
    public static function add_csrf_meta() {
        echo '<meta name="csrf-token" content="' . esc_attr(wp_create_nonce('aqualuxe_csrf')) . '">' . "\n";
    }
    
    /**
     * Secure content output
     */
    public static function secure_content($content) {
        // Remove potentially harmful content
        $content = preg_replace('/<script[^>]*>.*?<\/script>/is', '', $content);
        $content = preg_replace('/javascript:/i', '', $content);
        $content = preg_replace('/on\w+\s*=\s*["\'][^"\']*["\']/i', '', $content);
        
        return $content;
    }
    
    /**
     * Secure file uploads
     */
    public static function secure_file_uploads($file) {
        // Check file extension
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'pdf', 'doc', 'docx'];
        $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        
        if (!in_array($file_extension, $allowed_extensions)) {
            $file['error'] = __('File type not allowed', 'aqualuxe');
            return $file;
        }
        
        // Check file size (10MB limit)
        if ($file['size'] > 10 * 1024 * 1024) {
            $file['error'] = __('File size too large', 'aqualuxe');
            return $file;
        }
        
        // Check MIME type
        $allowed_mimes = [
            'image/jpeg',
            'image/png',
            'image/gif',
            'image/webp',
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
        ];
        
        $file_info = finfo_open(FILEINFO_MIME_TYPE);
        $mime_type = finfo_file($file_info, $file['tmp_name']);
        finfo_close($file_info);
        
        if (!in_array($mime_type, $allowed_mimes)) {
            $file['error'] = __('Invalid file type', 'aqualuxe');
            return $file;
        }
        
        return $file;
    }
    
    /**
     * Log failed login attempts
     */
    public static function log_failed_login($username) {
        $ip = self::get_client_ip();
        $attempts = get_transient('aqualuxe_failed_login_' . $ip);
        $attempts = $attempts ? $attempts + 1 : 1;
        
        set_transient('aqualuxe_failed_login_' . $ip, $attempts, HOUR_IN_SECONDS);
        
        // Log to error log if available
        if (function_exists('error_log')) {
            error_log("AquaLuxe: Failed login attempt for user '{$username}' from IP {$ip}. Attempt #{$attempts}");
        }
    }
    
    /**
     * Limit login attempts
     */
    public static function limit_login_attempts($user, $username, $password) {
        $ip = self::get_client_ip();
        $attempts = get_transient('aqualuxe_failed_login_' . $ip);
        
        // Block after 5 failed attempts
        if ($attempts >= 5) {
            return new WP_Error('too_many_attempts', 
                __('Too many failed login attempts. Please try again later.', 'aqualuxe'));
        }
        
        return $user;
    }
    
    /**
     * Get client IP address
     */
    private static function get_client_ip() {
        $ip_keys = ['HTTP_CF_CONNECTING_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 
                   'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 
                   'REMOTE_ADDR'];
        
        foreach ($ip_keys as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                $ip = $_SERVER[$key];
                if (strpos($ip, ',') !== false) {
                    $ip = explode(',', $ip)[0];
                }
                $ip = trim($ip);
                if (filter_var($ip, FILTER_VALIDATE_IP, 
                    FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
                    return $ip;
                }
            }
        }
        
        return isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0';
    }
    
    /**
     * Generate secure random token
     */
    public static function generate_secure_token($length = 32) {
        if (function_exists('random_bytes')) {
            return bin2hex(random_bytes($length / 2));
        } elseif (function_exists('openssl_random_pseudo_bytes')) {
            return bin2hex(openssl_random_pseudo_bytes($length / 2));
        } else {
            return wp_generate_password($length, false);
        }
    }
    
    /**
     * Validate and sanitize email
     */
    public static function validate_email($email) {
        $email = sanitize_email($email);
        return is_email($email) ? $email : false;
    }
    
    /**
     * Validate and sanitize URL
     */
    public static function validate_url($url) {
        $url = esc_url_raw($url);
        return filter_var($url, FILTER_VALIDATE_URL) ? $url : false;
    }
    
    /**
     * Escape and sanitize output for HTML
     */
    public static function escape_html($string) {
        return esc_html($string);
    }
    
    /**
     * Escape and sanitize output for HTML attributes
     */
    public static function escape_attr($string) {
        return esc_attr($string);
    }
    
    /**
     * Escape and sanitize output for JavaScript
     */
    public static function escape_js($string) {
        return esc_js($string);
    }
}
