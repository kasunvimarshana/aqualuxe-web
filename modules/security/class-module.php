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
        // Basic security headers and hardening
        add_action('wp_head', array($this, 'security_headers'));
        add_action('init', array($this, 'remove_version_info'));
        add_action('init', array($this, 'disable_file_editing'));
        add_filter('xmlrpc_enabled', '__return_false');
        
        // Login security
        add_action('wp_login', array($this, 'log_user_login'), 10, 2);
        add_action('wp_login_failed', array($this, 'log_failed_login'));
        add_action('init', array($this, 'limit_login_attempts'));
        add_filter('authenticate', array($this, 'check_login_attempts'), 30, 3);
        
        // AJAX security
        add_action('wp_ajax_nopriv_aqualuxe_contact', array($this, 'verify_nonce_ajax'));
        add_action('wp_ajax_aqualuxe_contact', array($this, 'verify_nonce_ajax'));
        
        // File upload security
        add_filter('upload_mimes', array($this, 'restrict_file_uploads'));
        add_filter('wp_handle_upload_prefilter', array($this, 'secure_file_upload'));
        
        // Content Security Policy
        add_action('wp_head', array($this, 'content_security_policy'));
        
        // Enhanced security measures
        add_action('init', array($this, 'secure_wp_admin'));
        add_action('wp_loaded', array($this, 'prevent_user_enumeration'));
        add_filter('wp_headers', array($this, 'add_security_headers'));
        add_action('wp_head', array($this, 'disable_wp_embed'));
        add_filter('rest_authentication_errors', array($this, 'secure_rest_api'));
        
        // Form security
        add_action('wp_enqueue_scripts', array($this, 'enqueue_security_scripts'));
        add_filter('comment_form_default_fields', array($this, 'add_honeypot_field'));
        add_filter('preprocess_comment', array($this, 'check_honeypot'));
        
        // Database security
        add_action('wp_login', array($this, 'log_user_activity'));
        add_action('wp_logout', array($this, 'log_user_activity'));
        
        // Rate limiting
        add_action('wp_ajax_nopriv_aqualuxe_rate_limit', array($this, 'check_rate_limit'));
        add_action('wp_ajax_aqualuxe_rate_limit', array($this, 'check_rate_limit'));
    }

    /**
     * Add comprehensive security headers
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
            
            // Permissions Policy (formerly Feature Policy)
            header('Permissions-Policy: geolocation=(), microphone=(), camera=(), payment=(), usb=()');
            
            // Expect-CT header
            header('Expect-CT: max-age=86400, enforce');
            
            // Cross-Origin policies
            header('Cross-Origin-Embedder-Policy: require-corp');
            header('Cross-Origin-Opener-Policy: same-origin');
            header('Cross-Origin-Resource-Policy: same-site');
        }
    }

    /**
     * Add additional security headers via wp_headers filter
     */
    public function add_security_headers($headers) {
        // Strict Transport Security (if HTTPS is enabled)
        if (is_ssl()) {
            $headers['Strict-Transport-Security'] = 'max-age=31536000; includeSubDomains; preload';
        }
        
        // Additional security headers
        $headers['X-Permitted-Cross-Domain-Policies'] = 'none';
        $headers['X-Robots-Tag'] = 'noindex, nofollow, nosnippet, noarchive';
        
        return $headers;
    }

    /**
     * Enhanced Content Security Policy
     */
    public function content_security_policy() {
        $csp_directives = array(
            "default-src 'self'",
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' *.googleapis.com *.gstatic.com *.google.com *.cloudflare.com",
            "style-src 'self' 'unsafe-inline' *.googleapis.com *.gstatic.com fonts.googleapis.com",
            "font-src 'self' fonts.gstatic.com fonts.googleapis.com",
            "img-src 'self' data: *.unsplash.com *.pixabay.com *.pexels.com *.gravatar.com",
            "connect-src 'self' *.google-analytics.com",
            "frame-src 'self' *.youtube.com *.vimeo.com",
            "object-src 'none'",
            "base-uri 'self'",
            "form-action 'self'",
            "frame-ancestors 'self'"
        );
        
        $csp = implode('; ', $csp_directives);
        
        if (!headers_sent()) {
            header("Content-Security-Policy: $csp");
        }
        
        // Also output as meta tag for backup
        echo "<meta http-equiv=\"Content-Security-Policy\" content=\"$csp\">\n";
    }

    /**
     * Secure file uploads
     */
    public function secure_file_upload($file) {
        // Check file size (10MB limit)
        $max_size = 10 * 1024 * 1024; // 10MB
        if ($file['size'] > $max_size) {
            $file['error'] = esc_html__('File size exceeds maximum limit of 10MB.', 'aqualuxe');
            return $file;
        }
        
        // Check for malicious file names
        $filename = $file['name'];
        $dangerous_names = array('.htaccess', '.htpasswd', 'wp-config.php', 'index.php');
        
        if (in_array(strtolower($filename), $dangerous_names)) {
            $file['error'] = esc_html__('File name not allowed for security reasons.', 'aqualuxe');
            return $file;
        }
        
        // Scan file content for malicious code patterns
        if (isset($file['tmp_name']) && is_readable($file['tmp_name'])) {
            $content = file_get_contents($file['tmp_name']);
            $malicious_patterns = array(
                '/<\?php/',
                '/eval\s*\(/',
                '/base64_decode/',
                '/shell_exec/',
                '/system\s*\(/',
                '/exec\s*\(/',
                '/passthru\s*\(/',
                '/file_get_contents\s*\(/',
                '/fopen\s*\(/',
                '/fwrite\s*\(/',
                '/curl_exec\s*\(/'
            );
            
            foreach ($malicious_patterns as $pattern) {
                if (preg_match($pattern, $content)) {
                    $file['error'] = esc_html__('File contains potentially malicious code.', 'aqualuxe');
                    return $file;
                }
            }
        }
        
        return $file;
    }

    /**
     * Restrict file upload types
     */
    public function restrict_file_uploads($mimes) {
        // Remove potentially dangerous file types
        unset($mimes['exe']);
        unset($mimes['com']);
        unset($mimes['bat']);
        unset($mimes['pif']);
        unset($mimes['scr']);
        unset($mimes['vbs']);
        unset($mimes['js']);
        unset($mimes['jar']);
        
        // Add allowed types for aquarium content
        $mimes['webp'] = 'image/webp';
        $mimes['svg'] = 'image/svg+xml';
        
        return $mimes;
    }

    /**
     * Prevent user enumeration
     */
    public function prevent_user_enumeration() {
        // Block user enumeration via REST API
        if (isset($_GET['author']) || strpos($_SERVER['REQUEST_URI'], '/?author=') !== false) {
            if (!is_admin() && !current_user_can('manage_options')) {
                wp_die(esc_html__('Access denied.', 'aqualuxe'), '', array('response' => 403));
            }
        }
        
        // Block wp-json user endpoints for non-authenticated users
        if (strpos($_SERVER['REQUEST_URI'], '/wp-json/wp/v2/users') !== false) {
            if (!is_user_logged_in()) {
                wp_die(esc_html__('Access denied.', 'aqualuxe'), '', array('response' => 403));
            }
        }
    }

    /**
     * Secure REST API access
     */
    public function secure_rest_api($result) {
        // Only allow REST API access for authenticated users or specific endpoints
        if (!is_user_logged_in()) {
            $allowed_endpoints = array(
                '/wp-json/wp/v2/posts',
                '/wp-json/wp/v2/pages',
                '/wp-json/aqualuxe/v1/products' // Custom endpoint for public product data
            );
            
            $current_endpoint = $_SERVER['REQUEST_URI'];
            $is_allowed = false;
            
            foreach ($allowed_endpoints as $endpoint) {
                if (strpos($current_endpoint, $endpoint) !== false) {
                    $is_allowed = true;
                    break;
                }
            }
            
            if (!$is_allowed) {
                return new WP_Error(
                    'rest_forbidden',
                    esc_html__('You are not authorized to access this resource.', 'aqualuxe'),
                    array('status' => 403)
                );
            }
        }
        
        return $result;
    }

    /**
     * Enhanced login attempt limiting
     */
    public function limit_login_attempts() {
        $ip = $this->get_client_ip();
        $lockout_duration = 30 * MINUTE_IN_SECONDS; // 30 minutes
        $max_attempts = 5;
        
        $attempts = get_transient('login_attempts_' . $ip);
        $lockout_time = get_transient('login_lockout_' . $ip);
        
        if ($lockout_time) {
            // User is currently locked out
            wp_die(
                sprintf(
                    esc_html__('Too many failed login attempts. Please try again in %d minutes.', 'aqualuxe'),
                    ceil(($lockout_time - time()) / 60)
                ),
                esc_html__('Login Blocked', 'aqualuxe'),
                array('response' => 429)
            );
        }
    }

    /**
     * Log failed login attempts
     */
    public function log_failed_login($username) {
        $ip = $this->get_client_ip();
        $attempts = (int) get_transient('login_attempts_' . $ip);
        $attempts++;
        
        set_transient('login_attempts_' . $ip, $attempts, 30 * MINUTE_IN_SECONDS);
        
        // Lock out after 5 failed attempts
        if ($attempts >= 5) {
            set_transient('login_lockout_' . $ip, time() + (30 * MINUTE_IN_SECONDS), 30 * MINUTE_IN_SECONDS);
            
            // Log security incident
            error_log("AquaLuxe Security: IP $ip locked out after $attempts failed login attempts for user: $username");
        }
    }

    /**
     * Add honeypot field to forms
     */
    public function add_honeypot_field($fields) {
        $fields['honeypot'] = '<div style="position: absolute; left: -9999px; top: -9999px;">
            <label for="url_check">Leave this field empty:</label>
            <input type="text" name="url_check" id="url_check" tabindex="-1" autocomplete="off">
        </div>';
        
        return $fields;
    }

    /**
     * Check honeypot field
     */
    public function check_honeypot($commentdata) {
        if (!empty($_POST['url_check'])) {
            // Bot detected, reject the comment
            wp_die(esc_html__('Spam detected.', 'aqualuxe'), '', array('response' => 403));
        }
        
        return $commentdata;
    }

    /**
     * Rate limiting for AJAX requests
     */
    public function check_rate_limit() {
        $ip = $this->get_client_ip();
        $rate_limit_key = 'rate_limit_' . $ip;
        $requests = (int) get_transient($rate_limit_key);
        
        if ($requests >= 60) { // 60 requests per minute
            wp_send_json_error(array(
                'message' => esc_html__('Rate limit exceeded. Please slow down.', 'aqualuxe')
            ), 429);
        }
        
        set_transient($rate_limit_key, $requests + 1, MINUTE_IN_SECONDS);
    }

    /**
     * Get client IP address
     */
    private function get_client_ip() {
        $ip_keys = array(
            'HTTP_CF_CONNECTING_IP',     // Cloudflare
            'HTTP_CLIENT_IP',            // Proxy
            'HTTP_X_FORWARDED_FOR',      // Load balancer/proxy
            'HTTP_X_FORWARDED',          // Proxy
            'HTTP_X_CLUSTER_CLIENT_IP',  // Cluster
            'HTTP_FORWARDED_FOR',        // Proxy
            'HTTP_FORWARDED',            // Proxy
            'REMOTE_ADDR'                // Standard
        );
        
        foreach ($ip_keys as $key) {
            if (array_key_exists($key, $_SERVER) && !empty($_SERVER[$key])) {
                $ips = explode(',', $_SERVER[$key]);
                $ip = trim($ips[0]);
                
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                    return $ip;
                }
            }
        }
        
        return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    }

    /**
     * Enqueue security-related scripts
     */
    public function enqueue_security_scripts() {
        wp_enqueue_script('aqualuxe-security', get_template_directory_uri() . '/assets/dist/js/security.js', array('jquery'), AQUALUXE_VERSION, true);
        
        wp_localize_script('aqualuxe-security', 'aqualuxe_security', array(
            'nonce' => wp_create_nonce('aqualuxe_security'),
            'rate_limit_endpoint' => admin_url('admin-ajax.php?action=aqualuxe_rate_limit'),
        ));
    }

    /**
     * Log user activity for security monitoring
     */
    public function log_user_activity($user_login) {
        $user = get_user_by('login', $user_login);
        $ip = $this->get_client_ip();
        $action = current_action();
        
        $log_entry = array(
            'user_id' => $user ? $user->ID : 0,
            'user_login' => $user_login,
            'ip_address' => $ip,
            'action' => $action,
            'timestamp' => current_time('mysql'),
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? ''
        );
        
        // Store in custom table or as option for security monitoring
        $security_log = get_option('aqualuxe_security_log', array());
        $security_log[] = $log_entry;
        
        // Keep only last 1000 entries
        if (count($security_log) > 1000) {
            $security_log = array_slice($security_log, -1000);
        }
        
        update_option('aqualuxe_security_log', $security_log);
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
}
