<?php
/**
 * Security Service
 * 
 * Handles security hardening, input sanitization, and protection
 * following SOLID principles and WordPress security best practices.
 *
 * @package AquaLuxe
 * @subpackage Services
 * @since 1.0.0
 * @author AquaLuxe Development Team
 */

namespace AquaLuxe\Services;

use AquaLuxe\Core\Base_Service;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Security Service Class
 *
 * Responsible for:
 * - Input sanitization and validation
 * - CSRF protection
 * - XSS prevention
 * - SQL injection prevention
 * - Rate limiting
 * - Security headers
 * - File upload security
 *
 * @since 1.0.0
 */
class Security extends Base_Service {

    /**
     * Rate limiting data
     *
     * @var array
     */
    private $rate_limits = array();

    /**
     * Security configuration
     *
     * @var array
     */
    private $config = array();

    /**
     * Initialize the service.
     *
     * @return void
     */
    public function init(): void {
        $this->setup_config();
        $this->setup_hooks();
        $this->setup_security_headers();
    }

    /**
     * Setup security configuration.
     *
     * @return void
     */
    private function setup_config(): void {
        $this->config = array(
            'rate_limit' => array(
                'enabled' => true,
                'requests_per_minute' => 60,
                'lockout_duration' => 300, // 5 minutes
            ),
            'csrf_protection' => array(
                'enabled' => true,
                'nonce_lifetime' => DAY_IN_SECONDS,
            ),
            'file_upload' => array(
                'allowed_types' => array( 'jpg', 'jpeg', 'png', 'gif', 'webp', 'pdf', 'doc', 'docx' ),
                'max_file_size' => 5 * MB_IN_BYTES,
            ),
            'headers' => array(
                'x_frame_options' => 'SAMEORIGIN',
                'x_content_type_options' => 'nosniff',
                'x_xss_protection' => '1; mode=block',
                'referrer_policy' => 'strict-origin-when-cross-origin',
            ),
        );

        $this->config = apply_filters( 'aqualuxe_security_config', $this->config );
    }

    /**
     * Setup WordPress hooks.
     *
     * @return void
     */
    private function setup_hooks(): void {
        // Input sanitization
        add_filter( 'aqualuxe_sanitize_input', array( $this, 'sanitize_input' ), 10, 2 );
        add_filter( 'aqualuxe_validate_email', array( $this, 'validate_email' ) );
        add_filter( 'aqualuxe_validate_url', array( $this, 'validate_url' ) );

        // CSRF protection
        add_action( 'wp_ajax_nopriv_aqualuxe_verify_nonce', array( $this, 'verify_nonce_ajax' ) );
        add_action( 'wp_ajax_aqualuxe_verify_nonce', array( $this, 'verify_nonce_ajax' ) );

        // Rate limiting
        if ( $this->config['rate_limit']['enabled'] ) {
            add_action( 'wp_loaded', array( $this, 'check_rate_limit' ) );
        }

        // File upload security
        add_filter( 'wp_handle_upload_prefilter', array( $this, 'secure_file_upload' ) );
        add_filter( 'upload_mimes', array( $this, 'filter_upload_mimes' ) );

        // Security headers
        add_action( 'send_headers', array( $this, 'send_security_headers' ) );

        // Login security
        add_action( 'wp_login_failed', array( $this, 'log_failed_login' ) );
        add_filter( 'authenticate', array( $this, 'check_login_attempts' ), 30, 3 );

        // Remove version info
        remove_action( 'wp_head', 'wp_generator' );
        add_filter( 'the_generator', '__return_empty_string' );
    }

    /**
     * Sanitize input based on type.
     *
     * @param mixed  $input Input value.
     * @param string $type Input type.
     * @return mixed Sanitized input.
     */
    public function sanitize_input( $input, string $type = 'text' ) {
        switch ( $type ) {
            case 'text':
                return sanitize_text_field( $input );
            
            case 'textarea':
                return sanitize_textarea_field( $input );
            
            case 'email':
                return sanitize_email( $input );
            
            case 'url':
                return esc_url_raw( $input );
            
            case 'int':
                return absint( $input );
            
            case 'float':
                return floatval( $input );
            
            case 'bool':
                return (bool) $input;
            
            case 'html':
                return wp_kses_post( $input );
            
            case 'slug':
                return sanitize_title( $input );
            
            case 'key':
                return sanitize_key( $input );
            
            case 'filename':
                return sanitize_file_name( $input );
            
            case 'array':
                if ( is_array( $input ) ) {
                    return array_map( array( $this, 'sanitize_input' ), $input );
                }
                return array();
            
            default:
                return sanitize_text_field( $input );
        }
    }

    /**
     * Validate email address.
     *
     * @param string $email Email address.
     * @return string|false Valid email or false.
     */
    public function validate_email( string $email ) {
        $sanitized = sanitize_email( $email );
        return is_email( $sanitized ) ? $sanitized : false;
    }

    /**
     * Validate URL.
     *
     * @param string $url URL to validate.
     * @return string|false Valid URL or false.
     */
    public function validate_url( string $url ) {
        $sanitized = esc_url_raw( $url );
        return wp_http_validate_url( $sanitized ) ? $sanitized : false;
    }

    /**
     * Verify nonce via AJAX.
     *
     * @return void
     */
    public function verify_nonce_ajax(): void {
        $nonce = sanitize_text_field( $_POST['nonce'] ?? '' );
        $action = sanitize_text_field( $_POST['action_name'] ?? 'aqualuxe_nonce' );

        if ( wp_verify_nonce( $nonce, $action ) ) {
            wp_send_json_success( array( 'message' => 'Nonce verified' ) );
        } else {
            wp_send_json_error( array( 'message' => 'Invalid nonce' ) );
        }
    }

    /**
     * Generate secure nonce.
     *
     * @param string $action Nonce action.
     * @return string Nonce value.
     */
    public function create_nonce( string $action = 'aqualuxe_nonce' ): string {
        return wp_create_nonce( $action );
    }

    /**
     * Verify nonce.
     *
     * @param string $nonce Nonce value.
     * @param string $action Nonce action.
     * @return bool True if valid.
     */
    public function verify_nonce( string $nonce, string $action = 'aqualuxe_nonce' ): bool {
        return wp_verify_nonce( $nonce, $action ) !== false;
    }

    /**
     * Check rate limiting.
     *
     * @return void
     */
    public function check_rate_limit(): void {
        if ( is_admin() || wp_doing_cron() ) {
            return;
        }

        $ip = $this->get_client_ip();
        $current_time = time();
        $rate_key = 'rate_limit_' . md5( $ip );

        // Get current request count
        $requests = get_transient( $rate_key );
        if ( ! $requests ) {
            $requests = array();
        }

        // Clean old requests (older than 1 minute)
        $requests = array_filter( $requests, function( $timestamp ) use ( $current_time ) {
            return ( $current_time - $timestamp ) <= 60;
        });

        // Check if rate limit exceeded
        if ( count( $requests ) >= $this->config['rate_limit']['requests_per_minute'] ) {
            $this->trigger_rate_limit_exceeded( $ip );
            return;
        }

        // Add current request
        $requests[] = $current_time;
        set_transient( $rate_key, $requests, 300 );
    }

    /**
     * Trigger rate limit exceeded response.
     *
     * @param string $ip Client IP address.
     * @return void
     */
    private function trigger_rate_limit_exceeded( string $ip ): void {
        $this->log( "Rate limit exceeded for IP: {$ip}", 'warning' );

        // Set lockout
        $lockout_key = 'lockout_' . md5( $ip );
        set_transient( $lockout_key, true, $this->config['rate_limit']['lockout_duration'] );

        // Send 429 status
        status_header( 429 );
        wp_die( 
            esc_html__( 'Too many requests. Please try again later.', 'aqualuxe' ),
            esc_html__( 'Rate Limit Exceeded', 'aqualuxe' ),
            array( 'response' => 429 )
        );
    }

    /**
     * Secure file upload.
     *
     * @param array $file File data.
     * @return array Modified file data.
     */
    public function secure_file_upload( array $file ): array {
        // Check file size
        if ( $file['size'] > $this->config['file_upload']['max_file_size'] ) {
            $file['error'] = sprintf(
                esc_html__( 'File size exceeds maximum allowed size of %s.', 'aqualuxe' ),
                size_format( $this->config['file_upload']['max_file_size'] )
            );
            return $file;
        }

        // Check file extension
        $file_ext = strtolower( pathinfo( $file['name'], PATHINFO_EXTENSION ) );
        if ( ! in_array( $file_ext, $this->config['file_upload']['allowed_types'], true ) ) {
            $file['error'] = esc_html__( 'File type not allowed.', 'aqualuxe' );
            return $file;
        }

        // Check for malicious content
        if ( $this->is_malicious_file( $file['tmp_name'] ) ) {
            $file['error'] = esc_html__( 'File contains potentially malicious content.', 'aqualuxe' );
            return $file;
        }

        return $file;
    }

    /**
     * Filter allowed upload MIME types.
     *
     * @param array $mimes Allowed MIME types.
     * @return array Filtered MIME types.
     */
    public function filter_upload_mimes( array $mimes ): array {
        // Remove potentially dangerous file types
        $dangerous_types = array( 'php', 'php3', 'php4', 'php5', 'phtml', 'js', 'exe', 'bat' );
        
        foreach ( $dangerous_types as $type ) {
            unset( $mimes[ $type ] );
        }

        return $mimes;
    }

    /**
     * Check if file is malicious.
     *
     * @param string $file_path File path.
     * @return bool True if malicious.
     */
    private function is_malicious_file( string $file_path ): bool {
        if ( ! file_exists( $file_path ) ) {
            return false;
        }

        // Check for PHP code in non-PHP files
        $content = file_get_contents( $file_path );
        $malicious_patterns = array(
            '/<\?php/',
            '/<\?=/',
            '/eval\s*\(/',
            '/base64_decode\s*\(/',
            '/system\s*\(/',
            '/exec\s*\(/',
            '/shell_exec\s*\(/',
        );

        foreach ( $malicious_patterns as $pattern ) {
            if ( preg_match( $pattern, $content ) ) {
                return true;
            }
        }

        return false;
    }

    /**
     * Send security headers.
     *
     * @return void
     */
    public function send_security_headers(): void {
        if ( headers_sent() ) {
            return;
        }

        $headers = $this->config['headers'];

        header( 'X-Frame-Options: ' . $headers['x_frame_options'] );
        header( 'X-Content-Type-Options: ' . $headers['x_content_type_options'] );
        header( 'X-XSS-Protection: ' . $headers['x_xss_protection'] );
        header( 'Referrer-Policy: ' . $headers['referrer_policy'] );

        // Only send HSTS on HTTPS
        if ( is_ssl() ) {
            header( 'Strict-Transport-Security: max-age=31536000; includeSubDomains' );
        }
    }

    /**
     * Setup Content Security Policy.
     *
     * @return void
     */
    private function setup_security_headers(): void {
        if ( ! is_admin() ) {
            add_action( 'wp_head', array( $this, 'add_csp_meta' ), 1 );
        }
    }

    /**
     * Add CSP meta tag.
     *
     * @return void
     */
    public function add_csp_meta(): void {
        $csp_policy = "default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval'; style-src 'self' 'unsafe-inline'; img-src 'self' data: https:; font-src 'self' data:; connect-src 'self';";
        $csp_policy = apply_filters( 'aqualuxe_csp_policy', $csp_policy );
        
        echo '<meta http-equiv="Content-Security-Policy" content="' . esc_attr( $csp_policy ) . '">' . "\n";
    }

    /**
     * Log failed login attempt.
     *
     * @param string $username Username.
     * @return void
     */
    public function log_failed_login( string $username ): void {
        $ip = $this->get_client_ip();
        $attempts_key = 'login_attempts_' . md5( $ip );
        
        $attempts = (int) get_transient( $attempts_key );
        $attempts++;
        
        set_transient( $attempts_key, $attempts, HOUR_IN_SECONDS );
        
        $this->log( "Failed login attempt for user '{$username}' from IP: {$ip} (Attempt #{$attempts})", 'warning' );
    }

    /**
     * Check login attempts and block if necessary.
     *
     * @param \WP_User|\WP_Error|null $user User object or error.
     * @param string                  $username Username.
     * @param string                  $password Password.
     * @return \WP_User|\WP_Error|null
     */
    public function check_login_attempts( $user, string $username, string $password ) {
        if ( empty( $username ) || empty( $password ) ) {
            return $user;
        }

        $ip = $this->get_client_ip();
        $attempts_key = 'login_attempts_' . md5( $ip );
        $lockout_key = 'login_lockout_' . md5( $ip );
        
        // Check if IP is locked out
        if ( get_transient( $lockout_key ) ) {
            return new \WP_Error( 'too_many_attempts', 
                esc_html__( 'Too many failed login attempts. Please try again later.', 'aqualuxe' ) 
            );
        }

        // Check attempt count
        $attempts = (int) get_transient( $attempts_key );
        if ( $attempts >= 5 ) {
            // Lock out for 30 minutes
            set_transient( $lockout_key, true, 30 * MINUTE_IN_SECONDS );
            delete_transient( $attempts_key );
            
            $this->log( "Login lockout triggered for IP: {$ip}", 'warning' );
            
            return new \WP_Error( 'too_many_attempts', 
                esc_html__( 'Too many failed login attempts. Account locked for 30 minutes.', 'aqualuxe' ) 
            );
        }

        return $user;
    }

    /**
     * Get client IP address.
     *
     * @return string IP address.
     */
    private function get_client_ip(): string {
        $ip_keys = array( 'HTTP_X_FORWARDED_FOR', 'HTTP_X_REAL_IP', 'HTTP_CLIENT_IP', 'REMOTE_ADDR' );
        
        foreach ( $ip_keys as $key ) {
            if ( ! empty( $_SERVER[ $key ] ) ) {
                $ip = sanitize_text_field( $_SERVER[ $key ] );
                // Handle comma-separated IPs
                if ( strpos( $ip, ',' ) !== false ) {
                    $ip = trim( explode( ',', $ip )[0] );
                }
                if ( filter_var( $ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE ) ) {
                    return $ip;
                }
            }
        }
        
        return '127.0.0.1';
    }

    /**
     * Get service name for dependency injection.
     *
     * @return string Service name.
     */
    public function get_service_name(): string {
        return 'security';
    }
}