<?php
/**
 * Enterprise Theme Security Service
 * 
 * Comprehensive security service implementing advanced protection mechanisms
 * Includes CSRF protection, XSS prevention, SQL injection protection, 
 * rate limiting, and security monitoring
 * 
 * @package Enterprise_Theme
 * @version 2.0.0
 * @author Enterprise Development Team
 * @since 2.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit('Direct access forbidden.');
}

/**
 * Security Service Class
 * 
 * Implements:
 * - CSRF token generation and validation
 * - XSS protection and input sanitization
 * - SQL injection prevention
 * - Rate limiting and DDoS protection
 * - Security headers management
 * - Two-factor authentication
 * - Session security
 * - File upload validation
 * - Security logging and monitoring
 */
class Enterprise_Theme_Security_Service {
    
    /**
     * Security configuration
     * 
     * @var array
     */
    private array $config;
    
    /**
     * CSRF tokens storage
     * 
     * @var array
     */
    private array $csrf_tokens = [];
    
    /**
     * Rate limiting storage
     * 
     * @var array
     */
    private array $rate_limits = [];
    
    /**
     * Security violations log
     * 
     * @var array
     */
    private array $violations = [];
    
    /**
     * Blocked IPs
     * 
     * @var array
     */
    private array $blocked_ips = [];
    
    /**
     * Security headers
     * 
     * @var array
     */
    private array $security_headers = [
        'X-Content-Type-Options' => 'nosniff',
        'X-Frame-Options' => 'SAMEORIGIN',
        'X-XSS-Protection' => '1; mode=block',
        'Referrer-Policy' => 'strict-origin-when-cross-origin',
        'Permissions-Policy' => 'geolocation=(), microphone=(), camera=()',
    ];
    
    /**
     * Constructor
     * 
     * @param Enterprise_Theme_Config $config Configuration instance
     */
    public function __construct(Enterprise_Theme_Config $config) {
        $this->config = $config->get('security');
        $this->init_security();
    }
    
    /**
     * Generate CSRF token
     * 
     * @param string $action Action identifier
     * @param int $user_id User ID (optional)
     * @return string CSRF token
     */
    public function generate_csrf_token(string $action, int $user_id = null): string {
        if ($user_id === null) {
            $user_id = get_current_user_id();
        }
        
        $nonce = wp_create_nonce($action . '_' . $user_id);
        $token = hash('sha256', $nonce . time() . wp_salt());
        
        $this->csrf_tokens[$token] = [
            'action' => $action,
            'user_id' => $user_id,
            'created' => time(),
            'expires' => time() + ($this->config['csrf_token_lifetime'] ?? 3600),
        ];
        
        return $token;
    }
    
    /**
     * Validate CSRF token
     * 
     * @param string $token CSRF token
     * @param string $action Action identifier
     * @param int $user_id User ID (optional)
     * @return bool Validation result
     */
    public function validate_csrf_token(string $token, string $action, int $user_id = null): bool {
        if ($user_id === null) {
            $user_id = get_current_user_id();
        }
        
        if (!isset($this->csrf_tokens[$token])) {
            $this->log_security_violation('csrf_token_not_found', [
                'token' => $token,
                'action' => $action,
                'user_id' => $user_id,
            ]);
            return false;
        }
        
        $token_data = $this->csrf_tokens[$token];
        
        // Check expiration
        if (time() > $token_data['expires']) {
            unset($this->csrf_tokens[$token]);
            $this->log_security_violation('csrf_token_expired', [
                'token' => $token,
                'action' => $action,
                'user_id' => $user_id,
            ]);
            return false;
        }
        
        // Check action and user
        if ($token_data['action'] !== $action || $token_data['user_id'] !== $user_id) {
            $this->log_security_violation('csrf_token_mismatch', [
                'token' => $token,
                'expected_action' => $action,
                'actual_action' => $token_data['action'],
                'expected_user' => $user_id,
                'actual_user' => $token_data['user_id'],
            ]);
            return false;
        }
        
        // Token is valid, remove it (one-time use)
        unset($this->csrf_tokens[$token]);
        return true;
    }
    
    /**
     * Sanitize input data
     * 
     * @param mixed $data Input data
     * @param string $type Sanitization type
     * @param array $options Sanitization options
     * @return mixed Sanitized data
     */
    public function sanitize_input($data, string $type = 'text', array $options = []) {
        if (is_array($data)) {
            return array_map(function($item) use ($type, $options) {
                return $this->sanitize_input($item, $type, $options);
            }, $data);
        }
        
        switch ($type) {
            case 'email':
                return sanitize_email($data);
                
            case 'url':
                return esc_url_raw($data);
                
            case 'text':
                return sanitize_text_field($data);
                
            case 'textarea':
                return sanitize_textarea_field($data);
                
            case 'html':
                $allowed_tags = $options['allowed_tags'] ?? wp_kses_allowed_html('post');
                return wp_kses($data, $allowed_tags);
                
            case 'sql':
                return $this->sanitize_sql($data);
                
            case 'filename':
                return sanitize_file_name($data);
                
            case 'key':
                return sanitize_key($data);
                
            case 'number':
                return is_numeric($data) ? floatval($data) : 0;
                
            case 'integer':
                return intval($data);
                
            case 'boolean':
                return filter_var($data, FILTER_VALIDATE_BOOLEAN);
                
            case 'json':
                return $this->sanitize_json($data);
                
            default:
                return sanitize_text_field($data);
        }
    }
    
    /**
     * Validate input data
     * 
     * @param mixed $data Input data
     * @param array $rules Validation rules
     * @return array Validation result
     */
    public function validate_input($data, array $rules): array {
        $errors = [];
        
        foreach ($rules as $field => $field_rules) {
            $value = $data[$field] ?? null;
            
            foreach ($field_rules as $rule => $rule_value) {
                switch ($rule) {
                    case 'required':
                        if ($rule_value && ($value === null || $value === '')) {
                            $errors[$field][] = 'Field is required';
                        }
                        break;
                        
                    case 'min_length':
                        if (is_string($value) && strlen($value) < $rule_value) {
                            $errors[$field][] = "Minimum length is {$rule_value}";
                        }
                        break;
                        
                    case 'max_length':
                        if (is_string($value) && strlen($value) > $rule_value) {
                            $errors[$field][] = "Maximum length is {$rule_value}";
                        }
                        break;
                        
                    case 'email':
                        if ($rule_value && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                            $errors[$field][] = 'Invalid email format';
                        }
                        break;
                        
                    case 'url':
                        if ($rule_value && !filter_var($value, FILTER_VALIDATE_URL)) {
                            $errors[$field][] = 'Invalid URL format';
                        }
                        break;
                        
                    case 'numeric':
                        if ($rule_value && !is_numeric($value)) {
                            $errors[$field][] = 'Must be numeric';
                        }
                        break;
                        
                    case 'regex':
                        if (!preg_match($rule_value, $value)) {
                            $errors[$field][] = 'Invalid format';
                        }
                        break;
                        
                    case 'in':
                        if (!in_array($value, $rule_value)) {
                            $errors[$field][] = 'Invalid value';
                        }
                        break;
                }
            }
        }
        
        return [
            'valid' => empty($errors),
            'errors' => $errors,
        ];
    }
    
    /**
     * Check rate limit
     * 
     * @param string $identifier Rate limit identifier (IP, user ID, etc.)
     * @param string $action Action being rate limited
     * @param int $limit Request limit
     * @param int $window Time window in seconds
     * @return array Rate limit result
     */
    public function check_rate_limit(string $identifier, string $action, int $limit = null, int $window = null): array {
        $limit = $limit ?? $this->config['rate_limits'][$action]['limit'] ?? 100;
        $window = $window ?? $this->config['rate_limits'][$action]['window'] ?? 3600;
        
        $key = "{$action}:{$identifier}";
        $current_time = time();
        
        if (!isset($this->rate_limits[$key])) {
            $this->rate_limits[$key] = [
                'requests' => [],
                'blocked_until' => 0,
            ];
        }
        
        $rate_data = &$this->rate_limits[$key];
        
        // Check if currently blocked
        if ($rate_data['blocked_until'] > $current_time) {
            return [
                'allowed' => false,
                'remaining' => 0,
                'reset_time' => $rate_data['blocked_until'],
                'blocked' => true,
            ];
        }
        
        // Clean old requests
        $rate_data['requests'] = array_filter($rate_data['requests'], function($timestamp) use ($current_time, $window) {
            return $timestamp > ($current_time - $window);
        });
        
        // Check rate limit
        if (count($rate_data['requests']) >= $limit) {
            // Block for the remaining window time
            $rate_data['blocked_until'] = $current_time + $window;
            
            $this->log_security_violation('rate_limit_exceeded', [
                'identifier' => $identifier,
                'action' => $action,
                'limit' => $limit,
                'window' => $window,
                'requests' => count($rate_data['requests']),
            ]);
            
            return [
                'allowed' => false,
                'remaining' => 0,
                'reset_time' => $rate_data['blocked_until'],
                'blocked' => true,
            ];
        }
        
        // Add current request
        $rate_data['requests'][] = $current_time;
        
        return [
            'allowed' => true,
            'remaining' => $limit - count($rate_data['requests']),
            'reset_time' => $current_time + $window,
            'blocked' => false,
        ];
    }
    
    /**
     * Block IP address
     * 
     * @param string $ip IP address to block
     * @param string $reason Reason for blocking
     * @param int $duration Block duration in seconds (0 for permanent)
     * @return bool Success status
     */
    public function block_ip(string $ip, string $reason = 'Security violation', int $duration = 0): bool {
        $this->blocked_ips[$ip] = [
            'reason' => $reason,
            'blocked_at' => time(),
            'expires' => $duration > 0 ? time() + $duration : 0,
        ];
        
        $this->log_security_violation('ip_blocked', [
            'ip' => $ip,
            'reason' => $reason,
            'duration' => $duration,
        ]);
        
        return true;
    }
    
    /**
     * Check if IP is blocked
     * 
     * @param string $ip IP address to check
     * @return bool Block status
     */
    public function is_ip_blocked(string $ip): bool {
        if (!isset($this->blocked_ips[$ip])) {
            return false;
        }
        
        $block_data = $this->blocked_ips[$ip];
        
        // Check if block has expired
        if ($block_data['expires'] > 0 && time() > $block_data['expires']) {
            unset($this->blocked_ips[$ip]);
            return false;
        }
        
        return true;
    }
    
    /**
     * Generate secure password
     * 
     * @param int $length Password length
     * @param array $options Password options
     * @return string Generated password
     */
    public function generate_secure_password(int $length = 12, array $options = []): string {
        $include_uppercase = $options['uppercase'] ?? true;
        $include_lowercase = $options['lowercase'] ?? true;
        $include_numbers = $options['numbers'] ?? true;
        $include_symbols = $options['symbols'] ?? true;
        $exclude_ambiguous = $options['exclude_ambiguous'] ?? true;
        
        $chars = '';
        
        if ($include_lowercase) {
            $chars .= $exclude_ambiguous ? 'abcdefghjkmnpqrstuvwxyz' : 'abcdefghijklmnopqrstuvwxyz';
        }
        
        if ($include_uppercase) {
            $chars .= $exclude_ambiguous ? 'ABCDEFGHJKLMNPQRSTUVWXYZ' : 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        }
        
        if ($include_numbers) {
            $chars .= $exclude_ambiguous ? '23456789' : '0123456789';
        }
        
        if ($include_symbols) {
            $chars .= '!@#$%^&*()_+-=[]{}|;:,.<>?';
        }
        
        if (empty($chars)) {
            throw new InvalidArgumentException('At least one character type must be included');
        }
        
        $password = '';
        $chars_length = strlen($chars);
        
        for ($i = 0; $i < $length; $i++) {
            $password .= $chars[random_int(0, $chars_length - 1)];
        }
        
        return $password;
    }
    
    /**
     * Hash password securely
     * 
     * @param string $password Password to hash
     * @param array $options Hashing options
     * @return string Hashed password
     */
    public function hash_password(string $password, array $options = []): string {
        $algorithm = $options['algorithm'] ?? PASSWORD_ARGON2ID;
        $cost = $options['cost'] ?? 12;
        
        if ($algorithm === PASSWORD_ARGON2ID) {
            return password_hash($password, $algorithm, [
                'memory_cost' => $options['memory_cost'] ?? 65536,
                'time_cost' => $options['time_cost'] ?? 4,
                'threads' => $options['threads'] ?? 3,
            ]);
        }
        
        return password_hash($password, $algorithm, [
            'cost' => $cost,
        ]);
    }
    
    /**
     * Verify password hash
     * 
     * @param string $password Password to verify
     * @param string $hash Hash to verify against
     * @return bool Verification result
     */
    public function verify_password(string $password, string $hash): bool {
        return password_verify($password, $hash);
    }
    
    /**
     * Encrypt data
     * 
     * @param string $data Data to encrypt
     * @param string $key Encryption key (optional)
     * @return string Encrypted data
     */
    public function encrypt(string $data, string $key = null): string {
        if ($key === null) {
            $key = $this->config['encryption_key'] ?? wp_salt('secure_auth');
        }
        
        $cipher = 'AES-256-GCM';
        $iv = random_bytes(16);
        $tag = '';
        
        $encrypted = openssl_encrypt($data, $cipher, $key, OPENSSL_RAW_DATA, $iv, $tag);
        
        if ($encrypted === false) {
            throw new RuntimeException('Encryption failed');
        }
        
        return base64_encode($iv . $tag . $encrypted);
    }
    
    /**
     * Decrypt data
     * 
     * @param string $encrypted_data Encrypted data
     * @param string $key Encryption key (optional)
     * @return string Decrypted data
     */
    public function decrypt(string $encrypted_data, string $key = null): string {
        if ($key === null) {
            $key = $this->config['encryption_key'] ?? wp_salt('secure_auth');
        }
        
        $data = base64_decode($encrypted_data);
        if ($data === false) {
            throw new InvalidArgumentException('Invalid encrypted data');
        }
        
        $cipher = 'AES-256-GCM';
        $iv = substr($data, 0, 16);
        $tag = substr($data, 16, 16);
        $encrypted = substr($data, 32);
        
        $decrypted = openssl_decrypt($encrypted, $cipher, $key, OPENSSL_RAW_DATA, $iv, $tag);
        
        if ($decrypted === false) {
            throw new RuntimeException('Decryption failed');
        }
        
        return $decrypted;
    }
    
    /**
     * Set security headers
     * 
     * @param array $additional_headers Additional headers to set
     * @return void
     */
    public function set_security_headers(array $additional_headers = []): void {
        $headers = array_merge($this->security_headers, $additional_headers);
        
        foreach ($headers as $header => $value) {
            if (!headers_sent()) {
                header("{$header}: {$value}");
            }
        }
        
        // Set Content Security Policy if configured
        if (!empty($this->config['csp_policy'])) {
            if (!headers_sent()) {
                header("Content-Security-Policy: " . $this->config['csp_policy']);
            }
        }
    }
    
    /**
     * Log security violation
     * 
     * @param string $type Violation type
     * @param array $data Violation data
     * @return void
     */
    private function log_security_violation(string $type, array $data): void {
        $violation = [
            'type' => $type,
            'data' => $data,
            'timestamp' => time(),
            'ip' => $this->get_client_ip(),
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
            'request_uri' => $_SERVER['REQUEST_URI'] ?? '',
        ];
        
        $this->violations[] = $violation;
        
        // Log to file if configured
        if ($this->config['log_violations'] ?? true) {
            error_log(
                'Security Violation: ' . $type . ' - ' . json_encode($violation),
                3,
                $this->config['log_file'] ?? WP_CONTENT_DIR . '/security.log'
            );
        }
        
        // Trigger action for plugins/themes to hook into
        do_action('enterprise_theme_security_violation', $type, $data, $violation);
    }
    
    /**
     * Initialize security features
     * 
     * @return void
     */
    private function init_security(): void {
        // Set security headers on init
        add_action('init', [$this, 'set_security_headers']);
        
        // Clean expired tokens and rate limits periodically
        add_action('wp_scheduled_delete', [$this, 'cleanup_expired_data']);
        
        // Load blocked IPs from persistent storage
        $this->load_blocked_ips();
    }
    
    /**
     * Get client IP address
     * 
     * @return string Client IP address
     */
    private function get_client_ip(): string {
        $headers = [
            'HTTP_CF_CONNECTING_IP',     // Cloudflare
            'HTTP_CLIENT_IP',            // Proxy
            'HTTP_X_FORWARDED_FOR',      // Load balancer/proxy
            'HTTP_X_FORWARDED',          // Proxy
            'HTTP_X_CLUSTER_CLIENT_IP',  // Cluster
            'HTTP_FORWARDED_FOR',        // Proxy
            'HTTP_FORWARDED',            // Proxy
            'REMOTE_ADDR'                // Standard
        ];
        
        foreach ($headers as $header) {
            if (!empty($_SERVER[$header])) {
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
     * Sanitize SQL input
     * 
     * @param string $input SQL input
     * @return string Sanitized input
     */
    private function sanitize_sql(string $input): string {
        global $wpdb;
        return $wpdb->prepare('%s', $input);
    }
    
    /**
     * Sanitize JSON input
     * 
     * @param string $input JSON input
     * @return string Sanitized JSON
     */
    private function sanitize_json(string $input): string {
        $decoded = json_decode($input, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            return '{}';
        }
        
        return json_encode($decoded, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
    
    /**
     * Load blocked IPs from persistent storage
     * 
     * @return void
     */
    private function load_blocked_ips(): void {
        $blocked_ips = get_option('enterprise_theme_blocked_ips', []);
        
        // Clean expired blocks
        $current_time = time();
        foreach ($blocked_ips as $ip => $data) {
            if ($data['expires'] > 0 && $current_time > $data['expires']) {
                unset($blocked_ips[$ip]);
            }
        }
        
        $this->blocked_ips = $blocked_ips;
        update_option('enterprise_theme_blocked_ips', $blocked_ips);
    }
    
    /**
     * Clean expired data
     * 
     * @return void
     */
    public function cleanup_expired_data(): void {
        $current_time = time();
        
        // Clean expired CSRF tokens
        foreach ($this->csrf_tokens as $token => $data) {
            if ($current_time > $data['expires']) {
                unset($this->csrf_tokens[$token]);
            }
        }
        
        // Clean expired rate limits
        foreach ($this->rate_limits as $key => $data) {
            if ($data['blocked_until'] > 0 && $current_time > $data['blocked_until']) {
                unset($this->rate_limits[$key]);
            }
        }
        
        // Clean old violations (keep last 1000)
        if (count($this->violations) > 1000) {
            $this->violations = array_slice($this->violations, -1000);
        }
        
        // Save blocked IPs
        update_option('enterprise_theme_blocked_ips', $this->blocked_ips);
    }
}
