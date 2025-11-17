<?php
/**
 * Security Class
 * 
 * Handles theme security features
 * 
 * @package AquaLuxe
 */

if (!defined('ABSPATH')) {
    exit;
}

class AquaLuxe_Security {
    
    /**
     * Constructor
     */
    public function __construct() {
        $this->init();
    }
    
    /**
     * Initialize security features
     */
    private function init() {
        // Remove WordPress version info
        add_filter('the_generator', '__return_empty_string');
        remove_action('wp_head', 'wp_generator');
        
        // Disable XML-RPC
        add_filter('xmlrpc_enabled', '__return_false');
        
        // Remove unnecessary meta tags
        remove_action('wp_head', 'wlwmanifest_link');
        remove_action('wp_head', 'rsd_link');
        remove_action('wp_head', 'wp_shortlink_wp_head');
        
        // Security headers
        add_action('send_headers', [$this, 'add_security_headers']);
        
        // Sanitize uploads
        add_filter('wp_handle_upload_prefilter', [$this, 'validate_upload']);
        
        // Hide login errors
        add_filter('login_errors', [$this, 'hide_login_errors']);
        
        // Rate limiting for login attempts
        add_action('wp_login_failed', [$this, 'track_failed_login']);
        add_filter('authenticate', [$this, 'check_failed_attempts'], 30, 3);
        
        // CSRF protection for forms
        add_action('wp_ajax_nopriv_aqualuxe_contact_form', [$this, 'verify_nonce_contact']);
        add_action('wp_ajax_aqualuxe_contact_form', [$this, 'verify_nonce_contact']);
        
        // Sanitize contact form data
        add_action('wp_ajax_nopriv_aqualuxe_contact_form', [$this, 'process_contact_form']);
        add_action('wp_ajax_aqualuxe_contact_form', [$this, 'process_contact_form']);
    }
    
    /**
     * Add security headers
     */
    public function add_security_headers() {
        if (!is_admin()) {
            header('X-Content-Type-Options: nosniff');
            header('X-Frame-Options: DENY');
            header('X-XSS-Protection: 1; mode=block');
            header('Referrer-Policy: strict-origin-when-cross-origin');
            
            // Content Security Policy
            $csp = "default-src 'self'; ";
            $csp .= "script-src 'self' 'unsafe-inline' 'unsafe-eval' *.googleapis.com *.gstatic.com; ";
            $csp .= "style-src 'self' 'unsafe-inline' *.googleapis.com *.gstatic.com; ";
            $csp .= "img-src 'self' data: *.googleapis.com *.gstatic.com; ";
            $csp .= "font-src 'self' *.googleapis.com *.gstatic.com; ";
            $csp .= "connect-src 'self'; ";
            $csp .= "media-src 'self'; ";
            $csp .= "object-src 'none'; ";
            $csp .= "base-uri 'self';";
            
            header("Content-Security-Policy: {$csp}");
        }
    }
    
    /**
     * Validate file uploads
     */
    public function validate_upload($file) {
        // Check file size (max 10MB)
        if ($file['size'] > 10 * 1024 * 1024) {
            $file['error'] = 'File size exceeds limit.';
            return $file;
        }
        
        // Allowed file types
        $allowed_types = [
            'image/jpeg',
            'image/png',
            'image/gif',
            'image/webp',
            'application/pdf',
            'text/plain'
        ];
        
        if (!in_array($file['type'], $allowed_types)) {
            $file['error'] = 'File type not allowed.';
            return $file;
        }
        
        // Scan filename for suspicious patterns
        $filename = sanitize_file_name($file['name']);
        if ($filename !== $file['name']) {
            $file['error'] = 'Invalid filename.';
            return $file;
        }
        
        return $file;
    }
    
    /**
     * Hide login error details
     */
    public function hide_login_errors($error) {
        return 'Invalid login credentials.';
    }
    
    /**
     * Track failed login attempts
     */
    public function track_failed_login($username) {
        $ip = $this->get_client_ip();
        $attempts = get_transient('failed_login_' . $ip) ?: 0;
        $attempts++;
        
        set_transient('failed_login_' . $ip, $attempts, 15 * MINUTE_IN_SECONDS);
        
        // Lock after 5 attempts
        if ($attempts >= 5) {
            set_transient('login_locked_' . $ip, true, 30 * MINUTE_IN_SECONDS);
        }
    }
    
    /**
     * Check for too many failed attempts
     */
    public function check_failed_attempts($user, $username, $password) {
        $ip = $this->get_client_ip();
        
        if (get_transient('login_locked_' . $ip)) {
            return new WP_Error('login_locked', 'Too many failed attempts. Please try again later.');
        }
        
        return $user;
    }
    
    /**
     * Verify nonce for contact form
     */
    public function verify_nonce_contact() {
        if (!wp_verify_nonce($_POST['nonce'] ?? '', 'aqualuxe_nonce')) {
            wp_die('Security check failed.');
        }
    }
    
    /**
     * Process contact form with sanitization
     */
    public function process_contact_form() {
        // Verify nonce
        if (!wp_verify_nonce($_POST['nonce'] ?? '', 'aqualuxe_nonce')) {
            wp_send_json_error('Security check failed.');
        }
        
        // Rate limiting
        $ip = $this->get_client_ip();
        if (get_transient('contact_form_' . $ip)) {
            wp_send_json_error('Please wait before submitting another message.');
        }
        
        // Sanitize input
        $name = sanitize_text_field($_POST['name'] ?? '');
        $email = sanitize_email($_POST['email'] ?? '');
        $subject = sanitize_text_field($_POST['subject'] ?? '');
        $message = sanitize_textarea_field($_POST['message'] ?? '');
        
        // Validate required fields
        if (empty($name) || empty($email) || empty($message)) {
            wp_send_json_error('Please fill in all required fields.');
        }
        
        // Validate email
        if (!is_email($email)) {
            wp_send_json_error('Please enter a valid email address.');
        }
        
        // Basic spam filtering
        if ($this->is_spam($name, $email, $message)) {
            wp_send_json_error('Message detected as spam.');
        }
        
        // Set rate limit
        set_transient('contact_form_' . $ip, true, 5 * MINUTE_IN_SECONDS);
        
        // Send email
        $admin_email = get_option('admin_email');
        $site_name = get_bloginfo('name');
        
        $email_subject = "[{$site_name}] Contact Form: {$subject}";
        $email_message = "Name: {$name}\n";
        $email_message .= "Email: {$email}\n";
        $email_message .= "Subject: {$subject}\n\n";
        $email_message .= "Message:\n{$message}\n\n";
        $email_message .= "---\n";
        $email_message .= "Sent from: " . home_url() . "\n";
        $email_message .= "IP: {$ip}\n";
        $email_message .= "User Agent: " . ($_SERVER['HTTP_USER_AGENT'] ?? 'Unknown');
        
        $headers = [
            'Reply-To: ' . $name . ' <' . $email . '>',
            'Content-Type: text/plain; charset=UTF-8'
        ];
        
        if (wp_mail($admin_email, $email_subject, $email_message, $headers)) {
            wp_send_json_success('Message sent successfully!');
        } else {
            wp_send_json_error('Failed to send message. Please try again.');
        }
    }
    
    /**
     * Basic spam detection
     */
    private function is_spam($name, $email, $message) {
        // Check for common spam patterns
        $spam_patterns = [
            '/\b(viagra|cialis|pharmacy|casino|poker|loan|mortgage)\b/i',
            '/\b(click here|visit our|check out our|limited time)\b/i',
            '/\b(make money|earn money|work from home)\b/i',
            '/http[s]?:\/\/[^\s]{3,}/i' // Multiple URLs
        ];
        
        $content = $name . ' ' . $email . ' ' . $message;
        
        foreach ($spam_patterns as $pattern) {
            if (preg_match($pattern, $content)) {
                return true;
            }
        }
        
        // Check for excessive repeated characters
        if (preg_match('/(.)\1{10,}/', $content)) {
            return true;
        }
        
        return false;
    }
    
    /**
     * Get client IP address
     */
    private function get_client_ip() {
        $ip_keys = ['HTTP_X_FORWARDED_FOR', 'HTTP_X_REAL_IP', 'HTTP_CLIENT_IP', 'REMOTE_ADDR'];
        
        foreach ($ip_keys as $key) {
            if (!empty($_SERVER[$key])) {
                $ip = $_SERVER[$key];
                if (strpos($ip, ',') !== false) {
                    $ip = explode(',', $ip)[0];
                }
                $ip = trim($ip);
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                    return $ip;
                }
            }
        }
        
        return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    }
    
    /**
     * Sanitize string for output
     */
    public static function sanitize_output($string) {
        return wp_kses_post($string);
    }
    
    /**
     * Escape attribute for output
     */
    public static function escape_attr($string) {
        return esc_attr($string);
    }
    
    /**
     * Escape URL for output
     */
    public static function escape_url($url) {
        return esc_url($url);
    }
}