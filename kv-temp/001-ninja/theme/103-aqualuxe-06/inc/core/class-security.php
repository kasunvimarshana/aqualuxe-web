<?php
/**
 * Theme Security Class
 * 
 * @package AquaLuxe
 * @version 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * AquaLuxe Security Class
 */
class AquaLuxe_Security {
    
    /**
     * Constructor
     */
    public function __construct() {
        $this->init_hooks();
    }
    
    /**
     * Initialize hooks
     */
    private function init_hooks() {
        // Security headers
        add_action('send_headers', [$this, 'add_security_headers']);
        
        // Remove WordPress version from head
        remove_action('wp_head', 'wp_generator');
        
        // Remove version from scripts and styles
        add_filter('style_loader_src', [$this, 'remove_version_strings'], 15);
        add_filter('script_loader_src', [$this, 'remove_version_strings'], 15);
        
        // Disable XML-RPC
        add_filter('xmlrpc_enabled', '__return_false');
        
        // Remove Windows Live Writer manifest link
        remove_action('wp_head', 'wlwmanifest_link');
        
        // Remove RSD link
        remove_action('wp_head', 'rsd_link');
        
        // Remove WordPress shortlink
        remove_action('wp_head', 'wp_shortlink_wp_head');
        
        // Disable file editing in admin
        if (!defined('DISALLOW_FILE_EDIT')) {
            define('DISALLOW_FILE_EDIT', true);
        }
        
        // Sanitize file uploads
        add_filter('wp_handle_upload_prefilter', [$this, 'sanitize_file_uploads']);
        
        // Limit login attempts (basic implementation)
        add_action('wp_login_failed', [$this, 'limit_login_attempts']);
        add_filter('authenticate', [$this, 'check_login_attempts'], 30, 3);
    }
    
    /**
     * Add security headers
     */
    public function add_security_headers() {
        if (!headers_sent()) {
            // X-Frame-Options
            header('X-Frame-Options: SAMEORIGIN');
            
            // X-Content-Type-Options
            header('X-Content-Type-Options: nosniff');
            
            // X-XSS-Protection
            header('X-XSS-Protection: 1; mode=block');
            
            // Referrer Policy
            header('Referrer-Policy: strict-origin-when-cross-origin');
            
            // Content Security Policy (basic)
            $csp = "default-src 'self'; ";
            $csp .= "script-src 'self' 'unsafe-inline' 'unsafe-eval' *.google.com *.googleapis.com *.gstatic.com; ";
            $csp .= "style-src 'self' 'unsafe-inline' *.google.com *.googleapis.com; ";
            $csp .= "font-src 'self' *.google.com *.googleapis.com *.gstatic.com; ";
            $csp .= "img-src 'self' data: *.gravatar.com *.wp.com; ";
            $csp .= "frame-src 'self' *.youtube.com *.vimeo.com;";
            
            header("Content-Security-Policy: $csp");
        }
    }
    
    /**
     * Remove version strings from enqueued scripts and styles
     */
    public function remove_version_strings($src) {
        if (strpos($src, 'ver=')) {
            $src = remove_query_arg('ver', $src);
        }
        return $src;
    }
    
    /**
     * Sanitize file uploads
     */
    public function sanitize_file_uploads($file) {
        // Get file extension
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        
        // Allowed file types
        $allowed_types = [
            'jpg', 'jpeg', 'png', 'gif', 'webp', 'svg',
            'pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx',
            'zip', 'rar', 'tar', 'gz',
            'mp3', 'wav', 'ogg',
            'mp4', 'avi', 'mov', 'wmv'
        ];
        
        // Check if file type is allowed
        if (!in_array($extension, $allowed_types)) {
            $file['error'] = __('File type not allowed for security reasons.', 'aqualuxe');
            return $file;
        }
        
        // Sanitize filename
        $file['name'] = sanitize_file_name($file['name']);
        
        // Check file size (max 10MB)
        $max_size = 10 * 1024 * 1024; // 10MB in bytes
        if ($file['size'] > $max_size) {
            $file['error'] = __('File size too large. Maximum size is 10MB.', 'aqualuxe');
            return $file;
        }
        
        return $file;
    }
    
    /**
     * Limit login attempts - basic implementation
     */
    public function limit_login_attempts($username) {
        $ip = $this->get_client_ip();
        $attempts = get_transient('aqualuxe_login_attempts_' . $ip);
        
        if (!$attempts) {
            $attempts = 1;
        } else {
            $attempts++;
        }
        
        set_transient('aqualuxe_login_attempts_' . $ip, $attempts, 15 * MINUTE_IN_SECONDS);
        
        // Log failed attempt
        error_log("Failed login attempt from IP: $ip, Username: $username, Attempts: $attempts");
    }
    
    /**
     * Check login attempts before authentication
     */
    public function check_login_attempts($user, $username, $password) {
        $ip = $this->get_client_ip();
        $attempts = get_transient('aqualuxe_login_attempts_' . $ip);
        
        // Block after 5 failed attempts
        if ($attempts && $attempts >= 5) {
            return new WP_Error(
                'too_many_attempts',
                __('Too many failed login attempts. Please try again in 15 minutes.', 'aqualuxe')
            );
        }
        
        return $user;
    }
    
    /**
     * Get client IP address
     */
    private function get_client_ip() {
        $ip_keys = ['HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR'];
        
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
     * Sanitize and escape functions
     */
    
    /**
     * Sanitize text input
     */
    public static function sanitize_text($input) {
        return sanitize_text_field(trim($input));
    }
    
    /**
     * Sanitize textarea input
     */
    public static function sanitize_textarea($input) {
        return sanitize_textarea_field(trim($input));
    }
    
    /**
     * Sanitize email
     */
    public static function sanitize_email($email) {
        return sanitize_email(trim($email));
    }
    
    /**
     * Sanitize URL
     */
    public static function sanitize_url($url) {
        return esc_url_raw(trim($url));
    }
    
    /**
     * Sanitize HTML
     */
    public static function sanitize_html($html) {
        $allowed_html = [
            'a' => [
                'href' => [],
                'title' => [],
                'target' => [],
                'rel' => [],
            ],
            'b' => [],
            'strong' => [],
            'i' => [],
            'em' => [],
            'u' => [],
            'br' => [],
            'p' => [
                'class' => [],
            ],
            'span' => [
                'class' => [],
            ],
            'div' => [
                'class' => [],
            ],
            'h1' => [],
            'h2' => [],
            'h3' => [],
            'h4' => [],
            'h5' => [],
            'h6' => [],
            'ul' => [
                'class' => [],
            ],
            'ol' => [
                'class' => [],
            ],
            'li' => [],
            'blockquote' => [],
            'code' => [],
            'pre' => [],
        ];
        
        return wp_kses($html, $allowed_html);
    }
    
    /**
     * Create and verify nonce
     */
    public static function create_nonce($action = 'aqualuxe_nonce') {
        return wp_create_nonce($action);
    }
    
    /**
     * Verify nonce
     */
    public static function verify_nonce($nonce, $action = 'aqualuxe_nonce') {
        return wp_verify_nonce($nonce, $action);
    }
    
    /**
     * Check if user has capability
     */
    public static function check_capability($capability = 'manage_options') {
        return current_user_can($capability);
    }
    
    /**
     * Escape attributes for output
     */
    public static function esc_attr($text) {
        return esc_attr($text);
    }
    
    /**
     * Escape HTML for output
     */
    public static function esc_html($text) {
        return esc_html($text);
    }
    
    /**
     * Escape URL for output
     */
    public static function esc_url($url) {
        return esc_url($url);
    }
    
    /**
     * Escape JavaScript
     */
    public static function esc_js($text) {
        return esc_js($text);
    }
}

// Initialize security
new AquaLuxe_Security();