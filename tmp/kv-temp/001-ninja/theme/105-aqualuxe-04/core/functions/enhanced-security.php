<?php
/**
 * Enhanced Security Functions
 *
 * Advanced security hardening and protection for AquaLuxe theme
 *
 * @package AquaLuxe\Security
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class AquaLuxe_Security_Manager
 *
 * Manages comprehensive security features
 */
class AquaLuxe_Security_Manager {
    
    /**
     * Single instance of the class
     *
     * @var AquaLuxe_Security_Manager
     */
    private static $instance = null;

    /**
     * Rate limiting data
     *
     * @var array
     */
    private static $rate_limits = array();

    /**
     * Security config
     *
     * @var array
     */
    private $config = array();

    /**
     * Get instance
     *
     * @return AquaLuxe_Security_Manager
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    private function __construct() {
        $this->load_config();
        $this->init_hooks();
    }

    /**
     * Load security configuration
     */
    private function load_config() {
        $this->config = array(
            'rate_limiting' => array(
                'enabled' => true,
                'login_attempts' => 5,      // attempts per timeframe
                'login_timeframe' => 900,   // 15 minutes
                'api_requests' => 100,      // requests per timeframe
                'api_timeframe' => 3600,    // 1 hour
                'contact_form' => 3,        // submissions per timeframe
                'contact_timeframe' => 3600, // 1 hour
            ),
            'input_validation' => array(
                'enabled' => true,
                'max_input_length' => 10000,
                'allowed_html_tags' => array('p', 'br', 'strong', 'em', 'ul', 'ol', 'li', 'a'),
                'blocked_patterns' => array(
                    'script',
                    'javascript:',
                    'vbscript:',
                    'onload',
                    'onerror',
                    'eval(',
                    'expression(',
                ),
            ),
            'headers' => array(
                'enabled' => true,
                'x_frame_options' => 'SAMEORIGIN',
                'x_content_type_options' => 'nosniff',
                'x_xss_protection' => '1; mode=block',
                'referrer_policy' => 'strict-origin-when-cross-origin',
                'permissions_policy' => 'camera=(), microphone=(), geolocation=()',
            ),
            'csrf_protection' => array(
                'enabled' => true,
                'token_lifetime' => 3600,   // 1 hour
                'require_referer' => true,
            ),
            'file_upload' => array(
                'enabled' => true,
                'allowed_types' => array('jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx'),
                'max_file_size' => 5242880, // 5MB
                'scan_uploads' => true,
            ),
        );
    }

    /**
     * Initialize hooks
     */
    private function init_hooks() {
        // Security headers
        add_action('send_headers', array($this, 'add_security_headers'));
        
        // Input validation and sanitization
        add_action('init', array($this, 'init_input_validation'));
        
        // Rate limiting
        add_action('wp_login_failed', array($this, 'handle_failed_login'));
        add_action('wp_authenticate_user', array($this, 'check_login_rate_limit'), 10, 2);
        
        // CSRF protection
        add_action('init', array($this, 'init_csrf_protection'));
        
        // File upload security
        add_filter('wp_handle_upload_prefilter', array($this, 'secure_file_upload'));
        add_filter('upload_mimes', array($this, 'filter_upload_mimes'));
        
        // Hide sensitive information
        add_filter('the_generator', '__return_empty_string');
        remove_action('wp_head', 'wp_generator');
        
        // Disable XML-RPC if not needed
        add_filter('xmlrpc_enabled', '__return_false');
        
        // Prevent enumeration
        add_action('init', array($this, 'prevent_user_enumeration'));
        
        // Security logging
        add_action('wp_login_failed', array($this, 'log_security_event'));
        add_action('wp_login', array($this, 'log_successful_login'), 10, 2);
    }

    /**
     * Add security headers
     */
    public function add_security_headers() {
        if (!$this->config['headers']['enabled']) {
            return;
        }

        $headers = array(
            'X-Frame-Options' => $this->config['headers']['x_frame_options'],
            'X-Content-Type-Options' => $this->config['headers']['x_content_type_options'],
            'X-XSS-Protection' => $this->config['headers']['x_xss_protection'],
            'Referrer-Policy' => $this->config['headers']['referrer_policy'],
            'Permissions-Policy' => $this->config['headers']['permissions_policy'],
        );

        foreach ($headers as $header => $value) {
            if (!headers_sent()) {
                header($header . ': ' . $value);
            }
        }
    }

    /**
     * Initialize input validation
     */
    public function init_input_validation() {
        if (!$this->config['input_validation']['enabled']) {
            return;
        }

        // Sanitize all inputs
        add_action('sanitize_comment_cookies', array($this, 'sanitize_global_inputs'));
        add_filter('pre_comment_content', array($this, 'validate_comment_content'));
    }

    /**
     * Sanitize global inputs
     */
    public function sanitize_global_inputs() {
        $_GET = $this->deep_sanitize($_GET);
        $_POST = $this->deep_sanitize($_POST);
        $_REQUEST = $this->deep_sanitize($_REQUEST);
    }

    /**
     * Deep sanitize array data
     */
    private function deep_sanitize($data) {
        if (is_array($data)) {
            return array_map(array($this, 'deep_sanitize'), $data);
        }

        if (is_string($data)) {
            return $this->sanitize_input($data);
        }

        return $data;
    }

    /**
     * Sanitize individual input
     */
    private function sanitize_input($input) {
        // Check input length
        if (strlen($input) > $this->config['input_validation']['max_input_length']) {
            return substr($input, 0, $this->config['input_validation']['max_input_length']);
        }

        // Check for blocked patterns
        foreach ($this->config['input_validation']['blocked_patterns'] as $pattern) {
            if (stripos($input, $pattern) !== false) {
                $this->log_security_event('blocked_input_pattern', array(
                    'pattern' => $pattern,
                    'input' => substr($input, 0, 100),
                ));
                return '';
            }
        }

        return sanitize_text_field($input);
    }

    /**
     * Validate comment content
     */
    public function validate_comment_content($content) {
        // Additional validation for comments
        $blocked_patterns = array('http://', 'https://', 'www.', '.com', '.org', '.net');
        
        foreach ($blocked_patterns as $pattern) {
            if (substr_count(strtolower($content), $pattern) > 2) {
                wp_die(__('Comment contains too many links', 'aqualuxe'));
            }
        }

        return $content;
    }

    /**
     * Handle failed login attempts
     */
    public function handle_failed_login($username) {
        $ip = $this->get_client_ip();
        $key = 'login_attempts_' . md5($ip);
        
        $attempts = get_transient($key);
        $attempts = $attempts ? $attempts + 1 : 1;
        
        set_transient($key, $attempts, $this->config['rate_limiting']['login_timeframe']);
        
        $this->log_security_event('failed_login', array(
            'username' => $username,
            'ip' => $ip,
            'attempts' => $attempts,
        ));
    }

    /**
     * Check login rate limit
     */
    public function check_login_rate_limit($user, $password) {
        if (!$this->config['rate_limiting']['enabled']) {
            return $user;
        }

        $ip = $this->get_client_ip();
        $key = 'login_attempts_' . md5($ip);
        $attempts = get_transient($key);

        if ($attempts >= $this->config['rate_limiting']['login_attempts']) {
            $this->log_security_event('rate_limit_exceeded', array(
                'type' => 'login',
                'ip' => $ip,
                'attempts' => $attempts,
            ));

            wp_die(
                sprintf(
                    __('Too many login attempts from your IP address. Please try again in %d minutes.', 'aqualuxe'),
                    $this->config['rate_limiting']['login_timeframe'] / 60
                )
            );
        }

        return $user;
    }

    /**
     * Initialize CSRF protection
     */
    public function init_csrf_protection() {
        if (!$this->config['csrf_protection']['enabled']) {
            return;
        }

        // Add CSRF tokens to forms
        add_action('wp_footer', array($this, 'add_csrf_token_script'));
        
        // Verify CSRF tokens on form submissions
        add_action('init', array($this, 'verify_csrf_token'));
    }

    /**
     * Add CSRF token script
     */
    public function add_csrf_token_script() {
        $token = $this->generate_csrf_token();
        ?>
        <script>
        (function() {
            // Add CSRF token to all forms
            const forms = document.querySelectorAll('form[method="post"]');
            forms.forEach(function(form) {
                if (!form.querySelector('input[name="aqualuxe_csrf_token"]')) {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'aqualuxe_csrf_token';
                    input.value = '<?php echo esc_js($token); ?>';
                    form.appendChild(input);
                }
            });
        })();
        </script>
        <?php
    }

    /**
     * Generate CSRF token
     */
    private function generate_csrf_token() {
        $session_id = session_id() ?: wp_create_nonce('aqualuxe_session');
        $token = hash('sha256', $session_id . wp_salt());
        
        set_transient('aqualuxe_csrf_' . $session_id, $token, $this->config['csrf_protection']['token_lifetime']);
        
        return $token;
    }

    /**
     * Verify CSRF token
     */
    public function verify_csrf_token() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        // Skip verification for WordPress admin and AJAX requests
        if (is_admin() || (defined('DOING_AJAX') && DOING_AJAX)) {
            return;
        }

        $token = $_POST['aqualuxe_csrf_token'] ?? '';
        $session_id = session_id() ?: wp_create_nonce('aqualuxe_session');
        $stored_token = get_transient('aqualuxe_csrf_' . $session_id);

        if (!$token || !$stored_token || !hash_equals($stored_token, $token)) {
            $this->log_security_event('csrf_token_mismatch', array(
                'ip' => $this->get_client_ip(),
                'referer' => $_SERVER['HTTP_REFERER'] ?? '',
            ));

            wp_die(__('Security token mismatch. Please refresh the page and try again.', 'aqualuxe'));
        }

        // Verify referer if enabled
        if ($this->config['csrf_protection']['require_referer']) {
            $referer = $_SERVER['HTTP_REFERER'] ?? '';
            $site_url = home_url();
            
            if (!$referer || strpos($referer, $site_url) !== 0) {
                wp_die(__('Invalid referer. Please try again.', 'aqualuxe'));
            }
        }
    }

    /**
     * Secure file upload
     */
    public function secure_file_upload($file) {
        if (!$this->config['file_upload']['enabled']) {
            return $file;
        }

        // Check file size
        if ($file['size'] > $this->config['file_upload']['max_file_size']) {
            $file['error'] = sprintf(
                __('File size exceeds maximum allowed size of %s MB', 'aqualuxe'),
                $this->config['file_upload']['max_file_size'] / 1048576
            );
            return $file;
        }

        // Check file type
        $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($file_extension, $this->config['file_upload']['allowed_types'])) {
            $file['error'] = __('File type not allowed', 'aqualuxe');
            return $file;
        }

        // Scan file content for malicious code
        if ($this->config['file_upload']['scan_uploads']) {
            $file_content = file_get_contents($file['tmp_name']);
            $malicious_patterns = array(
                '<?php',
                '<?=',
                '<script',
                'javascript:',
                'eval(',
                'base64_decode(',
                'exec(',
                'shell_exec(',
                'system(',
            );

            foreach ($malicious_patterns as $pattern) {
                if (stripos($file_content, $pattern) !== false) {
                    $this->log_security_event('malicious_file_upload', array(
                        'filename' => $file['name'],
                        'pattern' => $pattern,
                        'ip' => $this->get_client_ip(),
                    ));

                    $file['error'] = __('File contains potentially malicious content', 'aqualuxe');
                    return $file;
                }
            }
        }

        return $file;
    }

    /**
     * Filter upload MIME types
     */
    public function filter_upload_mimes($mimes) {
        // Remove potentially dangerous file types
        $dangerous_types = array(
            'exe', 'bat', 'cmd', 'scr', 'com', 'pif', 'vbs', 'js', 'jar',
            'php', 'php3', 'php4', 'php5', 'phtml', 'asp', 'aspx', 'jsp'
        );

        foreach ($dangerous_types as $type) {
            unset($mimes[$type]);
        }

        return $mimes;
    }

    /**
     * Prevent user enumeration
     */
    public function prevent_user_enumeration() {
        // Block user enumeration via REST API
        add_filter('rest_endpoints', function($endpoints) {
            if (isset($endpoints['/wp/v2/users'])) {
                unset($endpoints['/wp/v2/users']);
            }
            if (isset($endpoints['/wp/v2/users/(?P<id>[\d]+)'])) {
                unset($endpoints['/wp/v2/users/(?P<id>[\d]+)']);
            }
            return $endpoints;
        });

        // Block user enumeration via author archives
        if (!is_admin() && isset($_GET['author'])) {
            wp_redirect(home_url(), 301);
            exit;
        }
    }

    /**
     * Rate limit API requests
     */
    public function check_api_rate_limit($endpoint) {
        if (!$this->config['rate_limiting']['enabled']) {
            return true;
        }

        $ip = $this->get_client_ip();
        $key = 'api_requests_' . md5($ip . $endpoint);
        
        $requests = get_transient($key);
        $requests = $requests ? $requests + 1 : 1;
        
        set_transient($key, $requests, $this->config['rate_limiting']['api_timeframe']);

        if ($requests > $this->config['rate_limiting']['api_requests']) {
            $this->log_security_event('api_rate_limit_exceeded', array(
                'ip' => $ip,
                'endpoint' => $endpoint,
                'requests' => $requests,
            ));

            wp_die(__('API rate limit exceeded', 'aqualuxe'), 429);
        }

        return true;
    }

    /**
     * Log security events
     */
    public function log_security_event($event_type, $data = array()) {
        $log_entry = array(
            'timestamp' => current_time('timestamp'),
            'event_type' => $event_type,
            'ip' => $this->get_client_ip(),
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
            'data' => $data,
        );

        // Store in options table with automatic cleanup
        $logs = get_option('aqualuxe_security_logs', array());
        
        // Keep only last 1000 entries
        if (count($logs) >= 1000) {
            $logs = array_slice($logs, -500, 500, true);
        }
        
        $logs[] = $log_entry;
        update_option('aqualuxe_security_logs', $logs, false);
    }

    /**
     * Log successful login
     */
    public function log_successful_login($user_login, $user) {
        // Clear failed login attempts on successful login
        $ip = $this->get_client_ip();
        $key = 'login_attempts_' . md5($ip);
        delete_transient($key);

        $this->log_security_event('successful_login', array(
            'username' => $user_login,
            'user_id' => $user->ID,
            'ip' => $ip,
        ));
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
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    $ip = trim($ip);
                    
                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
                        return $ip;
                    }
                }
            }
        }

        return $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    }

    /**
     * Get security configuration
     */
    public function get_config() {
        return $this->config;
    }

    /**
     * Update security configuration
     */
    public function update_config($new_config) {
        $this->config = array_merge($this->config, $new_config);
        update_option('aqualuxe_security_config', $this->config);
    }

    /**
     * Get security logs
     */
    public function get_security_logs($limit = 100) {
        $logs = get_option('aqualuxe_security_logs', array());
        return array_slice(array_reverse($logs), 0, $limit);
    }

    /**
     * Clear security logs
     */
    public function clear_security_logs() {
        delete_option('aqualuxe_security_logs');
    }
}

// Initialize security manager
AquaLuxe_Security_Manager::get_instance();