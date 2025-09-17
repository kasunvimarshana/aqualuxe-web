<?php
/**
 * Security Module
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

namespace AquaLuxe\Modules\Security;

use AquaLuxe\Core\Abstracts\Abstract_Module;

defined('ABSPATH') || exit;

/**
 * Security Module Class
 */
class Module extends Abstract_Module {

    /**
     * Module name
     *
     * @var string
     */
    protected $name = 'Security';

    /**
     * Instance
     *
     * @var Module
     */
    private static $instance = null;

    /**
     * Get instance
     *
     * @return Module
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Initialize module
     */
    public function init() {
        add_action('wp_head', array($this, 'security_headers'));
        add_action('init', array($this, 'remove_version_info'));
        add_action('init', array($this, 'disable_file_editing'));
        add_filter('xmlrpc_enabled', '__return_false');
        add_action('wp_login', array($this, 'log_user_login'), 10, 2);
        add_action('wp_login_failed', array($this, 'log_failed_login'));
        add_action('init', array($this, 'limit_login_attempts'));
        add_filter('authenticate', array($this, 'check_login_attempts'), 30, 3);
        add_action('wp_ajax_nopriv_aqualuxe_contact', array($this, 'verify_nonce_ajax'));
        add_action('wp_ajax_aqualuxe_contact', array($this, 'verify_nonce_ajax'));
        add_filter('upload_mimes', array($this, 'restrict_file_uploads'));
        add_action('wp_head', array($this, 'content_security_policy'));
    }

    /**
     * Add security headers
     */
    public function security_headers() {
        if (!headers_sent()) {
            // Prevent clickjacking
            header('X-Frame-Options: SAMEORIGIN');
            
            // Prevent MIME type sniffing
            header('X-Content-Type-Options: nosniff');
            
            // XSS Protection
            header('X-XSS-Protection: 1; mode=block');
            
            // Referrer Policy
            header('Referrer-Policy: strict-origin-when-cross-origin');
            
            // Permissions Policy
            header('Permissions-Policy: geolocation=(), microphone=(), camera=()');
        }
    }

    /**
     * Remove WordPress version information
     */
    public function remove_version_info() {
        // Remove version from head
        remove_action('wp_head', 'wp_generator');
        
        // Remove version from RSS feeds
        add_filter('the_generator', '__return_empty_string');
        
        // Remove version from scripts and styles
        add_filter('style_loader_src', array($this, 'remove_version_query_string'), 9999);
        add_filter('script_loader_src', array($this, 'remove_version_query_string'), 9999);
    }

    /**
     * Remove version query string
     *
     * @param string $src Source URL
     * @return string
     */
    public function remove_version_query_string($src) {
        if (strpos($src, 'ver=')) {
            $src = remove_query_arg('ver', $src);
        }
        return $src;
    }

    /**
     * Disable file editing
     */
    public function disable_file_editing() {
        if (!defined('DISALLOW_FILE_EDIT')) {
            define('DISALLOW_FILE_EDIT', true);
        }
    }

    /**
     * Log user login
     *
     * @param string  $user_login Username
     * @param WP_User $user       User object
     */
    public function log_user_login($user_login, $user) {
        $this->log_security_event('login_success', array(
            'user_login' => $user_login,
            'user_id'    => $user->ID,
            'ip_address' => $this->get_user_ip(),
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
        ));
    }

    /**
     * Log failed login attempt
     *
     * @param string $username Username
     */
    public function log_failed_login($username) {
        $this->log_security_event('login_failed', array(
            'username'   => $username,
            'ip_address' => $this->get_user_ip(),
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
        ));
        
        $this->record_failed_attempt();
    }

    /**
     * Limit login attempts
     */
    public function limit_login_attempts() {
        if (is_admin() || !isset($_POST['log'])) {
            return;
        }
        
        $ip = $this->get_user_ip();
        $attempts = get_transient('aqualuxe_login_attempts_' . md5($ip));
        
        if ($attempts && $attempts >= 5) {
            wp_die(__('Too many failed login attempts. Please try again later.', 'aqualuxe'), __('Login Blocked', 'aqualuxe'), array('response' => 429));
        }
    }

    /**
     * Check login attempts before authentication
     *
     * @param null|WP_User|WP_Error $user     User object or error
     * @param string                $username Username
     * @param string                $password Password
     * @return null|WP_User|WP_Error
     */
    public function check_login_attempts($user, $username, $password) {
        if (empty($username) || empty($password)) {
            return $user;
        }
        
        $ip = $this->get_user_ip();
        $attempts = get_transient('aqualuxe_login_attempts_' . md5($ip));
        
        if ($attempts && $attempts >= 5) {
            return new \WP_Error('too_many_attempts', __('Too many failed login attempts. Please try again later.', 'aqualuxe'));
        }
        
        return $user;
    }

    /**
     * Record failed login attempt
     */
    private function record_failed_attempt() {
        $ip = $this->get_user_ip();
        $key = 'aqualuxe_login_attempts_' . md5($ip);
        $attempts = get_transient($key) ?: 0;
        $attempts++;
        
        set_transient($key, $attempts, HOUR_IN_SECONDS);
    }

    /**
     * Verify nonce for AJAX requests
     */
    public function verify_nonce_ajax() {
        if (!wp_verify_nonce($_POST['nonce'] ?? '', 'aqualuxe_ajax_nonce')) {
            wp_die(__('Security check failed.', 'aqualuxe'), __('Security Error', 'aqualuxe'), array('response' => 403));
        }
    }

    /**
     * Restrict file uploads
     *
     * @param array $mimes Allowed MIME types
     * @return array
     */
    public function restrict_file_uploads($mimes) {
        // Remove potentially dangerous file types
        unset($mimes['exe']);
        unset($mimes['bat']);
        unset($mimes['cmd']);
        unset($mimes['com']);
        unset($mimes['pif']);
        unset($mimes['scr']);
        unset($mimes['vbs']);
        unset($mimes['js']);
        
        return $mimes;
    }

    /**
     * Content Security Policy
     */
    public function content_security_policy() {
        if (!headers_sent()) {
            $csp = "default-src 'self'; ";
            $csp .= "script-src 'self' 'unsafe-inline' 'unsafe-eval' *.googleapis.com *.gstatic.com *.google.com *.doubleclick.net; ";
            $csp .= "style-src 'self' 'unsafe-inline' *.googleapis.com *.gstatic.com; ";
            $csp .= "font-src 'self' *.googleapis.com *.gstatic.com data:; ";
            $csp .= "img-src 'self' data: *.wp.com *.gravatar.com *.unsplash.com *.pixabay.com; ";
            $csp .= "connect-src 'self' *.doubleclick.net; ";
            $csp .= "frame-src 'self' *.google.com *.youtube.com *.vimeo.com; ";
            $csp .= "object-src 'none'; ";
            $csp .= "base-uri 'self';";
            
            header("Content-Security-Policy: " . $csp);
        }
    }

    /**
     * Get user IP address
     *
     * @return string
     */
    private function get_user_ip() {
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && !empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            return trim($ips[0]);
        } elseif (isset($_SERVER['HTTP_X_REAL_IP']) && !empty($_SERVER['HTTP_X_REAL_IP'])) {
            return $_SERVER['HTTP_X_REAL_IP'];
        } elseif (isset($_SERVER['REMOTE_ADDR'])) {
            return $_SERVER['REMOTE_ADDR'];
        }
        
        return '0.0.0.0';
    }

    /**
     * Log security event
     *
     * @param string $event Event type
     * @param array  $data  Event data
     */
    private function log_security_event($event, $data) {
        $log_entry = array(
            'timestamp' => current_time('mysql'),
            'event'     => $event,
            'data'      => $data,
        );
        
        // Store in database or log file
        $logs = get_option('aqualuxe_security_logs', array());
        $logs[] = $log_entry;
        
        // Keep only last 1000 entries
        if (count($logs) > 1000) {
            $logs = array_slice($logs, -1000);
        }
        
        update_option('aqualuxe_security_logs', $logs);
    }

    /**
     * Sanitize input data
     *
     * @param mixed $data Input data
     * @return mixed
     */
    public static function sanitize_input($data) {
        if (is_array($data)) {
            return array_map(array(__CLASS__, 'sanitize_input'), $data);
        } elseif (is_string($data)) {
            return sanitize_text_field($data);
        }
        
        return $data;
    }

    /**
     * Validate email
     *
     * @param string $email Email address
     * @return bool
     */
    public static function validate_email($email) {
        return is_email($email) && !empty($email);
    }

    /**
     * Generate secure nonce
     *
     * @param string $action Action name
     * @return string
     */
    public static function generate_nonce($action) {
        return wp_create_nonce($action);
    }

    /**
     * Verify nonce
     *
     * @param string $nonce  Nonce value
     * @param string $action Action name
     * @return bool
     */
    public static function verify_nonce($nonce, $action) {
        return wp_verify_nonce($nonce, $action);
    }
}