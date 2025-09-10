<?php
/**
 * Security Manager - Comprehensive security implementation for AquaLuxe theme
 *
 * Handles all security-related functionality including:
 * - Input sanitization and validation
 * - CSRF protection
 * - XSS prevention
 * - SQL injection prevention
 * - File upload security
 * - Authentication and authorization
 * - Security headers
 * - Rate limiting
 * - Content Security Policy (CSP)
 * - Session security
 * 
 * @package AquaLuxe\Core
 * @since 2.0.0
 * @author Kasun Vimarshana
 */

namespace AquaLuxe\Core;

use AquaLuxe\Core\Interfaces\Singleton_Interface;
use AquaLuxe\Core\Traits\Singleton_Trait;

if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}

/**
 * Security Manager Class
 * 
 * Implements comprehensive security measures following OWASP guidelines
 * and WordPress security best practices.
 * 
 * @since 2.0.0
 */
class Security implements Singleton_Interface {
    use Singleton_Trait;

    /**
     * Security configuration
     *
     * @var array
     */
    private $config = [];

    /**
     * Blocked IP addresses
     *
     * @var array
     */
    private $blocked_ips = [];

    /**
     * Rate limiting data
     *
     * @var array
     */
    private $rate_limits = [];

    /**
     * Security event log
     *
     * @var array
     */
    private $security_log = [];

    /**
     * Initialize security manager
     *
     * @since 2.0.0
     */
    protected function __construct() {
        $this->load_configuration();
        $this->initialize_hooks();
    }

    /**
     * Load security configuration
     *
     * @since 2.0.0
     */
    private function load_configuration(): void {
        $this->config = [
            'csrf_protection' => true,
            'xss_protection' => true,
            'sql_injection_protection' => true,
            'file_upload_security' => true,
            'rate_limiting' => true,
            'security_headers' => true,
            'content_security_policy' => true,
            'session_security' => true,
            'login_security' => true,
            'admin_security' => true,
            'max_login_attempts' => 5,
            'lockout_duration' => 1800, // 30 minutes
            'rate_limit_requests' => 100,
            'rate_limit_window' => 3600, // 1 hour
            'allowed_file_types' => [
                'jpg', 'jpeg', 'png', 'gif', 'webp', 'svg',
                'pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx',
                'zip', 'rar', '7z', 'tar', 'gz'
            ],
            'max_file_size' => 10 * 1024 * 1024, // 10MB
        ];

        // Allow configuration override via filters (check if function exists for compatibility)
        if ( function_exists( 'apply_filters' ) ) {
            $this->config = apply_filters( 'aqualuxe_security_config', $this->config );
        }
    }

    /**
     * Initialize WordPress hooks
     *
     * @since 2.0.0
     */
    private function initialize_hooks(): void {
        // Only add hooks if WordPress functions are available
        if ( ! function_exists( 'add_action' ) || ! function_exists( 'add_filter' ) ) {
            return;
        }

        // Security headers
        add_action( 'wp_head', [ $this, 'add_security_headers' ], 1 );
        add_action( 'admin_head', [ $this, 'add_security_headers' ], 1 );
        
        // File upload security
        add_filter( 'wp_handle_upload_prefilter', [ $this, 'secure_file_upload' ] );
        add_filter( 'upload_mimes', [ $this, 'filter_upload_mimes' ] );
        
        // Content filtering
        add_filter( 'content_save_pre', [ $this, 'sanitize_content' ] );
        add_filter( 'comment_text', [ $this, 'sanitize_comment' ] );
        
        // Admin security
        add_action( 'admin_init', [ $this, 'secure_admin_area' ] );
        
        // Form security
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_csrf_script' ] );
        add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_csrf_script' ] );
    }

    /**
     * Add security headers to HTTP response
     *
     * @since 2.0.0
     */
    public function add_security_headers(): void {
        if ( ! $this->config['security_headers'] ) {
            return;
        }

        $headers = [
            'X-Content-Type-Options' => 'nosniff',
            'X-Frame-Options' => 'SAMEORIGIN',
            'X-XSS-Protection' => '1; mode=block',
            'Referrer-Policy' => 'strict-origin-when-cross-origin',
            'Permissions-Policy' => 'camera=(), microphone=(), geolocation=()',
        ];

        // Content Security Policy
        if ( $this->config['content_security_policy'] ) {
            $csp = $this->get_content_security_policy();
            if ( $csp ) {
                $headers['Content-Security-Policy'] = $csp;
            }
        }

        // Apply headers
        foreach ( $headers as $name => $value ) {
            if ( ! headers_sent() ) {
                header( sprintf( '%s: %s', $name, $value ) );
            }
        }

        // Output meta tags for CSP fallback
        echo '<meta http-equiv="X-Content-Type-Options" content="nosniff">' . PHP_EOL;
        echo '<meta http-equiv="X-Frame-Options" content="SAMEORIGIN">' . PHP_EOL;
        echo '<meta http-equiv="X-XSS-Protection" content="1; mode=block">' . PHP_EOL;
    }

    /**
     * Generate Content Security Policy header
     *
     * @since 2.0.0
     * @return string CSP directive
     */
    private function get_content_security_policy(): string {
        $directives = [
            "default-src 'self'",
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' *.google.com *.googleapis.com *.gstatic.com",
            "style-src 'self' 'unsafe-inline' *.googleapis.com *.gstatic.com",
            "img-src 'self' data: *.gravatar.com *.wp.com",
            "font-src 'self' *.googleapis.com *.gstatic.com",
            "connect-src 'self'",
            "media-src 'self'",
            "object-src 'none'",
            "base-uri 'self'",
            "form-action 'self'",
            "frame-ancestors 'self'",
            "upgrade-insecure-requests"
        ];

        $csp = implode( '; ', $directives );
        
        return apply_filters( 'aqualuxe_content_security_policy', $csp );
    }

    /**
     * Handle login failure attempts
     *
     * @since 2.0.0
     * @param string $username Failed login username
     */
    public function handle_login_failure( string $username ): void {
        if ( ! $this->config['login_security'] ) {
            return;
        }

        $ip = $this->get_client_ip();
        $attempts_key = 'aqualuxe_login_attempts_' . md5( $ip . $username );
        
        $attempts = (int) get_transient( $attempts_key );
        $attempts++;
        
        set_transient( $attempts_key, $attempts, $this->config['lockout_duration'] );
        
        // Log security event
        $this->log_security_event( 'login_failure', [
            'username' => $username,
            'ip' => $ip,
            'attempts' => $attempts
        ] );
        
        // Block IP after max attempts
        if ( $attempts >= $this->config['max_login_attempts'] ) {
            $this->block_ip( $ip, $this->config['lockout_duration'] );
            
            $this->log_security_event( 'ip_blocked', [
                'ip' => $ip,
                'reason' => 'max_login_attempts',
                'duration' => $this->config['lockout_duration']
            ] );
        }
    }

    /**
     * Check login attempts before authentication
     *
     * @since 2.0.0
     * @param WP_User|WP_Error|null $user User object or error
     * @param string $username Username
     * @param string $password Password
     * @return WP_User|WP_Error User object or error
     */
    public function check_login_attempts( $user, string $username, string $password ) {
        if ( ! $this->config['login_security'] ) {
            return $user;
        }

        $ip = $this->get_client_ip();
        
        // Check if IP is blocked
        if ( $this->is_ip_blocked( $ip ) ) {
            return new \WP_Error( 
                'login_blocked', 
                __( 'Login temporarily blocked due to security concerns.', 'aqualuxe' )
            );
        }

        $attempts_key = 'aqualuxe_login_attempts_' . md5( $ip . $username );
        $attempts = (int) get_transient( $attempts_key );
        
        if ( $attempts >= $this->config['max_login_attempts'] ) {
            return new \WP_Error( 
                'too_many_attempts', 
                sprintf( 
                    __( 'Too many login attempts. Please try again in %d minutes.', 'aqualuxe' ),
                    ceil( $this->config['lockout_duration'] / 60 )
                )
            );
        }

        return $user;
    }

    /**
     * Secure file upload validation
     *
     * @since 2.0.0
     * @param array $file Upload file data
     * @return array Modified file data
     */
    public function secure_file_upload( array $file ): array {
        if ( ! $this->config['file_upload_security'] ) {
            return $file;
        }

        // Check file size
        if ( $file['size'] > $this->config['max_file_size'] ) {
            $file['error'] = sprintf( 
                __( 'File size exceeds maximum allowed size of %s.', 'aqualuxe' ),
                size_format( $this->config['max_file_size'] )
            );
            return $file;
        }

        // Check file extension
        $file_ext = strtolower( pathinfo( $file['name'], PATHINFO_EXTENSION ) );
        if ( ! in_array( $file_ext, $this->config['allowed_file_types'], true ) ) {
            $file['error'] = sprintf( 
                __( 'File type "%s" is not allowed.', 'aqualuxe' ),
                $file_ext
            );
            return $file;
        }

        // Check MIME type
        $file_type = wp_check_filetype( $file['name'] );
        if ( ! $file_type['ext'] ) {
            $file['error'] = __( 'Invalid file type detected.', 'aqualuxe' );
            return $file;
        }

        // Scan file content for malicious code
        if ( $this->scan_file_content( $file['tmp_name'] ) ) {
            $file['error'] = __( 'File contains potentially malicious content.', 'aqualuxe' );
            return $file;
        }

        return $file;
    }

    /**
     * Filter allowed MIME types for uploads
     *
     * @since 2.0.0
     * @param array $mime_types Current MIME types
     * @return array Filtered MIME types
     */
    public function filter_upload_mimes( array $mime_types ): array {
        // Remove potentially dangerous file types
        $dangerous_types = [
            'exe' => 'application/octet-stream',
            'com' => 'application/octet-stream',
            'bat' => 'application/octet-stream',
            'cmd' => 'application/octet-stream',
            'pif' => 'application/octet-stream',
            'scr' => 'application/octet-stream',
            'vbs' => 'text/vbscript',
            'js' => 'application/javascript',
            'jar' => 'application/java-archive',
        ];

        return array_diff_key( $mime_types, $dangerous_types );
    }

    /**
     * Scan file content for malicious patterns
     *
     * @since 2.0.0
     * @param string $file_path Path to file
     * @return bool True if malicious content found
     */
    private function scan_file_content( string $file_path ): bool {
        if ( ! file_exists( $file_path ) ) {
            return false;
        }

        $content = file_get_contents( $file_path );
        if ( false === $content ) {
            return false;
        }

        // Malicious patterns to check
        $malicious_patterns = [
            '/(<\?php|<\?=|\?>)/',
            '/(eval|exec|system|shell_exec|passthru)\s*\(/i',
            '/(base64_decode|gzinflate|str_rot13)\s*\(/i',
            '/(<script[^>]*>.*?<\/script>)/is',
            '/(javascript:|vbscript:|data:)/i',
        ];

        foreach ( $malicious_patterns as $pattern ) {
            if ( preg_match( $pattern, $content ) ) {
                return true;
            }
        }

        return false;
    }

    /**
     * Enqueue CSRF protection script
     *
     * @since 2.0.0
     */
    public function enqueue_csrf_script(): void {
        if ( ! $this->config['csrf_protection'] ) {
            return;
        }

        wp_localize_script( 'jquery', 'aqualuxe_security', [
            'csrf_token' => $this->generate_csrf_token(),
            'nonce' => wp_create_nonce( 'aqualuxe_security_nonce' ),
        ] );
    }

    /**
     * Generate CSRF token
     *
     * @since 2.0.0
     * @return string CSRF token
     */
    public function generate_csrf_token(): string {
        if ( ! session_id() ) {
            session_start();
        }

        $token = wp_hash( uniqid( 'aqualuxe_csrf_', true ) . time() );
        $_SESSION['aqualuxe_csrf_token'] = $token;
        
        return $token;
    }

    /**
     * Verify CSRF token
     *
     * @since 2.0.0
     * @param string $token Token to verify
     * @return bool True if valid
     */
    public function verify_csrf_token( string $token ): bool {
        if ( ! $this->config['csrf_protection'] ) {
            return true;
        }

        if ( ! session_id() ) {
            session_start();
        }

        $stored_token = $_SESSION['aqualuxe_csrf_token'] ?? '';
        
        return ! empty( $stored_token ) && hash_equals( $stored_token, $token );
    }

    /**
     * Sanitize content before saving
     *
     * @since 2.0.0
     * @param string $content Content to sanitize
     * @return string Sanitized content
     */
    public function sanitize_content( string $content ): string {
        if ( ! $this->config['xss_protection'] ) {
            return $content;
        }

        // Remove malicious script tags and event handlers
        $content = preg_replace( '/<script[^>]*>.*?<\/script>/is', '', $content );
        $content = preg_replace( '/on\w+\s*=\s*["\'][^"\']*["\']/i', '', $content );
        $content = preg_replace( '/(javascript|vbscript|data):/i', '', $content );
        
        return $content;
    }

    /**
     * Sanitize comment content
     *
     * @since 2.0.0
     * @param string $comment Comment content
     * @return string Sanitized comment
     */
    public function sanitize_comment( string $comment ): string {
        return $this->sanitize_content( $comment );
    }

    /**
     * Secure admin area
     *
     * @since 2.0.0
     */
    public function secure_admin_area(): void {
        if ( ! $this->config['admin_security'] ) {
            return;
        }

        // Hide WordPress version in admin
        remove_action( 'wp_head', 'wp_generator' );
        
        // Remove version from scripts and styles
        add_filter( 'script_loader_src', [ $this, 'remove_version_from_assets' ] );
        add_filter( 'style_loader_src', [ $this, 'remove_version_from_assets' ] );
        
        // Disable file editor
        if ( ! defined( 'DISALLOW_FILE_EDIT' ) ) {
            define( 'DISALLOW_FILE_EDIT', true );
        }
    }

    /**
     * Remove version parameters from asset URLs
     *
     * @since 2.0.0
     * @param string $src Asset URL
     * @return string Modified URL
     */
    public function remove_version_from_assets( string $src ): string {
        if ( strpos( $src, 'ver=' ) ) {
            $src = remove_query_arg( 'ver', $src );
        }
        
        return $src;
    }

    /**
     * Check rate limits
     *
     * @since 2.0.0
     */
    public function check_rate_limits(): void {
        if ( ! $this->config['rate_limiting'] ) {
            return;
        }

        $ip = $this->get_client_ip();
        $rate_key = 'aqualuxe_rate_limit_' . md5( $ip );
        
        $requests = (array) get_transient( $rate_key );
        $current_time = time();
        
        // Clean old requests outside the window
        $requests = array_filter( $requests, function( $timestamp ) use ( $current_time ) {
            return ( $current_time - $timestamp ) < $this->config['rate_limit_window'];
        } );
        
        // Check if limit exceeded
        if ( count( $requests ) >= $this->config['rate_limit_requests'] ) {
            $this->block_ip( $ip, $this->config['rate_limit_window'] );
            
            wp_die( 
                __( 'Rate limit exceeded. Please try again later.', 'aqualuxe' ),
                __( 'Too Many Requests', 'aqualuxe' ),
                [ 'response' => 429 ]
            );
        }
        
        // Add current request
        $requests[] = $current_time;
        set_transient( $rate_key, $requests, $this->config['rate_limit_window'] );
    }

    /**
     * Block IP address
     *
     * @since 2.0.0
     * @param string $ip IP address to block
     * @param int $duration Block duration in seconds
     */
    public function block_ip( string $ip, int $duration ): void {
        $blocked_key = 'aqualuxe_blocked_ip_' . md5( $ip );
        set_transient( $blocked_key, true, $duration );
        
        $this->blocked_ips[ $ip ] = time() + $duration;
    }

    /**
     * Check if IP is blocked
     *
     * @since 2.0.0
     * @param string $ip IP address to check
     * @return bool True if blocked
     */
    public function is_ip_blocked( string $ip ): bool {
        $blocked_key = 'aqualuxe_blocked_ip_' . md5( $ip );
        return (bool) get_transient( $blocked_key );
    }

    /**
     * Get client IP address
     *
     * @since 2.0.0
     * @return string Client IP address
     */
    public function get_client_ip(): string {
        $headers = [
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_REAL_IP',
            'HTTP_CLIENT_IP',
            'HTTP_X_FORWARDED',
            'HTTP_FORWARDED_FOR',
            'HTTP_FORWARDED',
            'REMOTE_ADDR'
        ];

        foreach ( $headers as $header ) {
            if ( ! empty( $_SERVER[ $header ] ) ) {
                $ip = sanitize_text_field( wp_unslash( $_SERVER[ $header ] ) );
                
                // Handle comma-separated IPs
                if ( strpos( $ip, ',' ) !== false ) {
                    $ip = trim( explode( ',', $ip )[0] );
                }
                
                // Validate IP
                if ( filter_var( $ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE ) ) {
                    return $ip;
                }
            }
        }

        return '127.0.0.1';
    }

    /**
     * Log security event
     *
     * @since 2.0.0
     * @param string $event_type Type of security event
     * @param array $data Additional event data
     */
    public function log_security_event( string $event_type, array $data = [] ): void {
        $event = [
            'timestamp' => time(),
            'type' => $event_type,
            'ip' => $this->get_client_ip(),
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
            'data' => $data
        ];

        $this->security_log[] = $event;

        // Store in database for persistence
        $log_option = 'aqualuxe_security_log_' . gmdate( 'Y_m_d' );
        $daily_log = get_option( $log_option, [] );
        $daily_log[] = $event;
        
        // Keep only last 1000 events per day
        if ( count( $daily_log ) > 1000 ) {
            $daily_log = array_slice( $daily_log, -1000 );
        }
        
        update_option( $log_option, $daily_log );

        // Trigger action for external logging systems
        do_action( 'aqualuxe_security_event', $event_type, $event );
    }

    /**
     * Get security log
     *
     * @since 2.0.0
     * @param string $date Date to get log for (Y-m-d format)
     * @return array Security events
     */
    public function get_security_log( string $date = '' ): array {
        if ( empty( $date ) ) {
            $date = gmdate( 'Y_m_d' );
        } else {
            $date = str_replace( '-', '_', $date );
        }

        $log_option = 'aqualuxe_security_log_' . $date;
        
        return get_option( $log_option, [] );
    }

    /**
     * Clean up old security logs
     *
     * @since 2.0.0
     * @param int $days_to_keep Number of days to keep logs
     */
    public function cleanup_security_logs( int $days_to_keep = 30 ): void {
        global $wpdb;

        $cutoff_date = gmdate( 'Y_m_d', strtotime( "-{$days_to_keep} days" ) );
        
        $wpdb->query( $wpdb->prepare(
            "DELETE FROM {$wpdb->options} 
             WHERE option_name LIKE 'aqualuxe_security_log_%%' 
             AND option_name < %s",
            'aqualuxe_security_log_' . $cutoff_date
        ) );
    }

    /**
     * Get security configuration
     *
     * @since 2.0.0
     * @return array Security configuration
     */
    public function get_config(): array {
        return $this->config;
    }

    /**
     * Update security configuration
     *
     * @since 2.0.0
     * @param array $new_config New configuration values
     */
    public function update_config( array $new_config ): void {
        $this->config = array_merge( $this->config, $new_config );
        
        // Trigger configuration update action
        do_action( 'aqualuxe_security_config_updated', $this->config );
    }
}
