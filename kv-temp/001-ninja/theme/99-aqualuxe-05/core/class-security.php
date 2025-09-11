<?php
/**
 * Security Class
 *
 * Handles security features and protections
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
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
        // Class is auto-initialized when loaded
    }

    /**
     * Initialize security features
     */
    public function init() {
        add_action( 'init', array( $this, 'security_headers' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'add_nonce_to_ajax' ) );
        add_filter( 'wp_headers', array( $this, 'add_security_headers' ) );
        add_action( 'wp_footer', array( $this, 'add_csrf_token' ) );
        
        // Remove WordPress version from various places
        remove_action( 'wp_head', 'wp_generator' );
        add_filter( 'the_generator', '__return_empty_string' );
        
        // Remove RSD link
        remove_action( 'wp_head', 'rsd_link' );
        
        // Remove Windows Live Writer manifest link
        remove_action( 'wp_head', 'wlwmanifest_link' );
        
        // Remove shortlink
        remove_action( 'wp_head', 'wp_shortlink_wp_head' );
        
        // Disable file editing
        if ( ! defined( 'DISALLOW_FILE_EDIT' ) ) {
            define( 'DISALLOW_FILE_EDIT', true );
        }
    }

    /**
     * Add security headers
     */
    public function security_headers() {
        if ( ! is_admin() ) {
            // Prevent clickjacking
            header( 'X-Frame-Options: SAMEORIGIN' );
            
            // Prevent MIME type sniffing
            header( 'X-Content-Type-Options: nosniff' );
            
            // Enable XSS protection
            header( 'X-XSS-Protection: 1; mode=block' );
            
            // Referrer policy
            header( 'Referrer-Policy: strict-origin-when-cross-origin' );
        }
    }

    /**
     * Add security headers via WordPress filter
     *
     * @param array $headers
     * @return array
     */
    public function add_security_headers( $headers ) {
        if ( ! is_admin() ) {
            $headers['X-Frame-Options'] = 'SAMEORIGIN';
            $headers['X-Content-Type-Options'] = 'nosniff';
            $headers['X-XSS-Protection'] = '1; mode=block';
            $headers['Referrer-Policy'] = 'strict-origin-when-cross-origin';
            
            // Content Security Policy (basic)
            $csp_rules = array(
                "default-src 'self'",
                "script-src 'self' 'unsafe-inline' 'unsafe-eval' *.google.com *.googleapis.com",
                "style-src 'self' 'unsafe-inline' *.googleapis.com",
                "img-src 'self' data: *.gravatar.com *.google.com",
                "font-src 'self' *.googleapis.com *.gstatic.com",
                "connect-src 'self'",
                "frame-src 'self' *.youtube.com *.vimeo.com",
            );
            
            $headers['Content-Security-Policy'] = implode( '; ', $csp_rules );
        }
        
        return $headers;
    }

    /**
     * Add nonce to AJAX requests
     */
    public function add_nonce_to_ajax() {
        wp_localize_script( 'aqualuxe-main', 'aqualuxe_security', array(
            'nonce' => wp_create_nonce( 'aqualuxe_security_nonce' ),
        ) );
    }

    /**
     * Add CSRF token to forms
     */
    public function add_csrf_token() {
        echo '<script>window.aqualuxe_csrf_token = "' . esc_js( wp_create_nonce( 'aqualuxe_csrf' ) ) . '";</script>';
    }

    /**
     * Verify nonce for AJAX requests
     *
     * @param string $nonce
     * @param string $action
     * @return bool
     */
    public static function verify_nonce( $nonce, $action = 'aqualuxe_security_nonce' ) {
        return wp_verify_nonce( $nonce, $action );
    }

    /**
     * Sanitize user input
     *
     * @param mixed  $input
     * @param string $type
     * @return mixed
     */
    public static function sanitize_input( $input, $type = 'text' ) {
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
                return intval( $input );
            
            case 'float':
                return floatval( $input );
            
            case 'bool':
                return (bool) $input;
            
            case 'array':
                return is_array( $input ) ? array_map( 'sanitize_text_field', $input ) : array();
            
            case 'html':
                return wp_kses_post( $input );
            
            case 'key':
                return sanitize_key( $input );
            
            case 'slug':
                return sanitize_title( $input );
            
            default:
                return sanitize_text_field( $input );
        }
    }

    /**
     * Escape output for safe display
     *
     * @param mixed  $output
     * @param string $context
     * @return mixed
     */
    public static function escape_output( $output, $context = 'html' ) {
        switch ( $context ) {
            case 'html':
                return esc_html( $output );
            
            case 'attr':
                return esc_attr( $output );
            
            case 'url':
                return esc_url( $output );
            
            case 'js':
                return esc_js( $output );
            
            case 'textarea':
                return esc_textarea( $output );
            
            case 'sql':
                global $wpdb;
                return $wpdb->prepare( '%s', $output );
            
            default:
                return esc_html( $output );
        }
    }

    /**
     * Check user permissions
     *
     * @param string $capability
     * @param int    $user_id
     * @return bool
     */
    public static function check_permissions( $capability, $user_id = null ) {
        if ( ! $user_id ) {
            return current_user_can( $capability );
        }
        
        $user = get_userdata( $user_id );
        return $user && $user->has_cap( $capability );
    }

    /**
     * Log security events
     *
     * @param string $event
     * @param array  $data
     */
    public static function log_security_event( $event, $data = array() ) {
        $log_data = array(
            'timestamp' => current_time( 'timestamp' ),
            'event'     => $event,
            'user_id'   => get_current_user_id(),
            'ip'        => self::get_client_ip(),
            'user_agent' => isset( $_SERVER['HTTP_USER_AGENT'] ) ? sanitize_text_field( $_SERVER['HTTP_USER_AGENT'] ) : '',
            'data'      => $data,
        );
        
        // Log to WordPress debug log if enabled
        if ( defined( 'WP_DEBUG_LOG' ) && WP_DEBUG_LOG ) {
            error_log( 'AquaLuxe Security Event: ' . wp_json_encode( $log_data ) );
        }
        
        // Store in database (optional)
        $security_logs = get_option( 'aqualuxe_security_logs', array() );
        $security_logs[] = $log_data;
        
        // Keep only last 100 entries
        if ( count( $security_logs ) > 100 ) {
            $security_logs = array_slice( $security_logs, -100 );
        }
        
        update_option( 'aqualuxe_security_logs', $security_logs );
    }

    /**
     * Get client IP address
     *
     * @return string
     */
    public static function get_client_ip() {
        $ip_keys = array(
            'HTTP_CLIENT_IP',
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_FORWARDED',
            'HTTP_X_CLUSTER_CLIENT_IP',
            'HTTP_FORWARDED_FOR',
            'HTTP_FORWARDED',
            'REMOTE_ADDR'
        );
        
        foreach ( $ip_keys as $key ) {
            if ( array_key_exists( $key, $_SERVER ) === true ) {
                $ip = sanitize_text_field( $_SERVER[ $key ] );
                
                if ( strpos( $ip, ',' ) !== false ) {
                    $ip = explode( ',', $ip )[0];
                }
                
                $ip = trim( $ip );
                
                if ( filter_var( $ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE ) ) {
                    return $ip;
                }
            }
        }
        
        return 'unknown';
    }

    /**
     * Rate limiting for login attempts
     *
     * @param string $ip
     * @return bool
     */
    public static function is_login_rate_limited( $ip ) {
        $attempts = get_transient( 'aqualuxe_login_attempts_' . md5( $ip ) );
        
        if ( $attempts && $attempts >= 5 ) {
            return true;
        }
        
        return false;
    }

    /**
     * Record login attempt
     *
     * @param string $ip
     * @param bool   $success
     */
    public static function record_login_attempt( $ip, $success = false ) {
        $ip_hash = md5( $ip );
        $attempts = get_transient( 'aqualuxe_login_attempts_' . $ip_hash );
        
        if ( $success ) {
            // Clear attempts on successful login
            delete_transient( 'aqualuxe_login_attempts_' . $ip_hash );
        } else {
            // Increment failed attempts
            $attempts = $attempts ? $attempts + 1 : 1;
            set_transient( 'aqualuxe_login_attempts_' . $ip_hash, $attempts, HOUR_IN_SECONDS );
            
            // Log failed attempt
            self::log_security_event( 'failed_login', array(
                'ip' => $ip,
                'attempts' => $attempts,
            ) );
        }
    }

    /**
     * Check if request is from bot
     *
     * @return bool
     */
    public static function is_bot() {
        if ( ! isset( $_SERVER['HTTP_USER_AGENT'] ) ) {
            return true;
        }
        
        $user_agent = strtolower( sanitize_text_field( $_SERVER['HTTP_USER_AGENT'] ) );
        $bot_agents = array(
            'bot', 'crawl', 'spider', 'walk', 'search', 'get', 'find', 'index',
            'googlebot', 'bingbot', 'slurp', 'duckduckbot', 'baiduspider'
        );
        
        foreach ( $bot_agents as $bot ) {
            if ( strpos( $user_agent, $bot ) !== false ) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Validate file upload
     *
     * @param array $file
     * @return bool|WP_Error
     */
    public static function validate_file_upload( $file ) {
        if ( ! function_exists( 'wp_handle_upload' ) ) {
            require_once( ABSPATH . 'wp-admin/includes/file.php' );
        }
        
        $allowed_types = array(
            'jpg|jpeg|jpe' => 'image/jpeg',
            'gif'          => 'image/gif',
            'png'          => 'image/png',
            'webp'         => 'image/webp',
            'pdf'          => 'application/pdf',
            'doc'          => 'application/msword',
            'docx'         => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        );
        
        $file_type = wp_check_filetype( $file['name'], $allowed_types );
        
        if ( ! $file_type['type'] ) {
            return new WP_Error( 'invalid_file_type', esc_html__( 'Invalid file type', 'aqualuxe' ) );
        }
        
        // Check file size (limit to 5MB)
        $max_size = 5 * 1024 * 1024; // 5MB in bytes
        if ( $file['size'] > $max_size ) {
            return new WP_Error( 'file_too_large', esc_html__( 'File size too large', 'aqualuxe' ) );
        }
        
        return true;
    }
}