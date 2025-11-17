<?php
/**
 * Security Module
 *
 * Handles theme security features and hardening
 *
 * @package AquaLuxe\Modules
 * @since 1.0.0
 */

namespace AquaLuxe\Modules;

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class Security
 *
 * Implements security best practices for the theme
 *
 * @since 1.0.0
 */
class Security {

    /**
     * Initialize the security module
     *
     * @since 1.0.0
     */
    public function init() {
        add_action( 'init', array( $this, 'setup_security_headers' ) );
        add_action( 'wp_head', array( $this, 'remove_version_info' ) );
        add_filter( 'the_generator', '__return_empty_string' );
        add_action( 'init', array( $this, 'disable_file_editing' ) );
        add_action( 'init', array( $this, 'remove_unnecessary_headers' ) );
        add_filter( 'wp_headers', array( $this, 'add_security_headers' ) );
        
        // Content Security Policy
        add_action( 'wp_head', array( $this, 'add_csp_header' ), 1 );
        
        // Sanitization filters
        add_filter( 'pre_comment_content', array( $this, 'sanitize_comment_content' ) );
        add_action( 'wp_ajax_aqualuxe_sanitize_input', array( $this, 'sanitize_ajax_input' ) );
        add_action( 'wp_ajax_nopriv_aqualuxe_sanitize_input', array( $this, 'sanitize_ajax_input' ) );
    }

    /**
     * Setup security headers
     *
     * @since 1.0.0
     */
    public function setup_security_headers() {
        if ( ! is_admin() ) {
            // Remove potentially sensitive headers
            remove_action( 'wp_head', 'wp_generator' );
            remove_action( 'wp_head', 'wlwmanifest_link' );
            remove_action( 'wp_head', 'rsd_link' );
            remove_action( 'wp_head', 'wp_shortlink_wp_head' );
            
            // Add security headers
            add_action( 'send_headers', array( $this, 'send_security_headers' ) );
        }
    }

    /**
     * Send security headers
     *
     * @since 1.0.0
     */
    public function send_security_headers() {
        header( 'X-Content-Type-Options: nosniff' );
        header( 'X-Frame-Options: SAMEORIGIN' );
        header( 'X-XSS-Protection: 1; mode=block' );
        header( 'Referrer-Policy: strict-origin-when-cross-origin' );
        header( 'Permissions-Policy: camera=(), microphone=(), geolocation=()' );
    }

    /**
     * Add security headers to response
     *
     * @since 1.0.0
     * @param array $headers Existing headers
     * @return array
     */
    public function add_security_headers( $headers ) {
        $headers['X-Content-Type-Options'] = 'nosniff';
        $headers['X-Frame-Options'] = 'SAMEORIGIN';
        $headers['X-XSS-Protection'] = '1; mode=block';
        $headers['Referrer-Policy'] = 'strict-origin-when-cross-origin';
        
        return $headers;
    }

    /**
     * Add Content Security Policy header
     *
     * @since 1.0.0
     */
    public function add_csp_header() {
        $csp_directives = array(
            "default-src 'self'",
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://fonts.googleapis.com",
            "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com",
            "img-src 'self' data: https:",
            "font-src 'self' https://fonts.gstatic.com",
            "connect-src 'self'",
            "media-src 'self'",
            "object-src 'none'",
            "child-src 'self'",
            "frame-ancestors 'self'",
            "form-action 'self'",
            "base-uri 'self'"
        );

        $csp = implode( '; ', apply_filters( 'aqualuxe_csp_directives', $csp_directives ) );
        
        echo '<meta http-equiv="Content-Security-Policy" content="' . esc_attr( $csp ) . '">' . "\n";
    }

    /**
     * Remove version information
     *
     * @since 1.0.0
     */
    public function remove_version_info() {
        remove_action( 'wp_head', 'wp_generator' );
    }

    /**
     * Disable file editing in WordPress admin
     *
     * @since 1.0.0
     */
    public function disable_file_editing() {
        if ( ! defined( 'DISALLOW_FILE_EDIT' ) ) {
            define( 'DISALLOW_FILE_EDIT', true );
        }
    }

    /**
     * Remove unnecessary headers
     *
     * @since 1.0.0
     */
    public function remove_unnecessary_headers() {
        remove_action( 'wp_head', 'wp_generator' );
        remove_action( 'wp_head', 'wlwmanifest_link' );
        remove_action( 'wp_head', 'rsd_link' );
        remove_action( 'wp_head', 'wp_shortlink_wp_head' );
        remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head' );
    }

    /**
     * Sanitize comment content
     *
     * @since 1.0.0
     * @param string $content Comment content
     * @return string
     */
    public function sanitize_comment_content( $content ) {
        // Remove potentially dangerous content
        $content = wp_kses( $content, array(
            'a' => array(
                'href' => array(),
                'title' => array()
            ),
            'em' => array(),
            'strong' => array(),
            'p' => array(),
            'br' => array(),
            'code' => array(),
            'blockquote' => array(),
        ) );

        return $content;
    }

    /**
     * Sanitize AJAX input
     *
     * @since 1.0.0
     */
    public function sanitize_ajax_input() {
        // Verify nonce
        if ( ! wp_verify_nonce( $_POST['nonce'] ?? '', 'aqualuxe_nonce' ) ) {
            wp_send_json_error( array( 'message' => esc_html__( 'Security check failed.', 'aqualuxe' ) ) );
        }

        // Sanitize all input data
        $sanitized_data = array();
        
        if ( isset( $_POST['data'] ) && is_array( $_POST['data'] ) ) {
            foreach ( $_POST['data'] as $key => $value ) {
                $sanitized_data[ sanitize_key( $key ) ] = $this->deep_sanitize( $value );
            }
        }

        wp_send_json_success( array( 'data' => $sanitized_data ) );
    }

    /**
     * Deep sanitize data recursively
     *
     * @since 1.0.0
     * @param mixed $data Data to sanitize
     * @return mixed
     */
    private function deep_sanitize( $data ) {
        if ( is_array( $data ) ) {
            return array_map( array( $this, 'deep_sanitize' ), $data );
        }

        if ( is_string( $data ) ) {
            return sanitize_text_field( $data );
        }

        return $data;
    }

    /**
     * Validate and sanitize form data
     *
     * @since 1.0.0
     * @param array $data Form data
     * @param array $rules Validation rules
     * @return array|WP_Error
     */
    public function validate_form_data( $data, $rules = array() ) {
        $sanitized = array();
        $errors = array();

        foreach ( $rules as $field => $rule ) {
            $value = $data[ $field ] ?? '';

            // Required field check
            if ( ! empty( $rule['required'] ) && empty( $value ) ) {
                $errors[] = sprintf(
                    /* translators: %s: field name */
                    esc_html__( '%s is required.', 'aqualuxe' ),
                    $rule['label'] ?? $field
                );
                continue;
            }

            // Sanitize based on type
            switch ( $rule['type'] ?? 'text' ) {
                case 'email':
                    $sanitized[ $field ] = sanitize_email( $value );
                    if ( ! is_email( $sanitized[ $field ] ) && ! empty( $value ) ) {
                        $errors[] = sprintf(
                            /* translators: %s: field name */
                            esc_html__( '%s must be a valid email address.', 'aqualuxe' ),
                            $rule['label'] ?? $field
                        );
                    }
                    break;

                case 'url':
                    $sanitized[ $field ] = esc_url_raw( $value );
                    break;

                case 'textarea':
                    $sanitized[ $field ] = sanitize_textarea_field( $value );
                    break;

                case 'number':
                    $sanitized[ $field ] = intval( $value );
                    break;

                case 'float':
                    $sanitized[ $field ] = floatval( $value );
                    break;

                default:
                    $sanitized[ $field ] = sanitize_text_field( $value );
                    break;
            }

            // Length validation
            if ( ! empty( $rule['max_length'] ) && strlen( $sanitized[ $field ] ) > $rule['max_length'] ) {
                $errors[] = sprintf(
                    /* translators: 1: field name, 2: max length */
                    esc_html__( '%1$s must not exceed %2$d characters.', 'aqualuxe' ),
                    $rule['label'] ?? $field,
                    $rule['max_length']
                );
            }

            if ( ! empty( $rule['min_length'] ) && strlen( $sanitized[ $field ] ) < $rule['min_length'] ) {
                $errors[] = sprintf(
                    /* translators: 1: field name, 2: min length */
                    esc_html__( '%1$s must be at least %2$d characters.', 'aqualuxe' ),
                    $rule['label'] ?? $field,
                    $rule['min_length']
                );
            }
        }

        if ( ! empty( $errors ) ) {
            return new \WP_Error( 'validation_failed', implode( ' ', $errors ) );
        }

        return $sanitized;
    }

    /**
     * Generate secure nonce
     *
     * @since 1.0.0
     * @param string $action Nonce action
     * @return string
     */
    public function create_nonce( $action ) {
        return wp_create_nonce( 'aqualuxe_' . $action );
    }

    /**
     * Verify secure nonce
     *
     * @since 1.0.0
     * @param string $nonce Nonce value
     * @param string $action Nonce action
     * @return bool
     */
    public function verify_nonce( $nonce, $action ) {
        return wp_verify_nonce( $nonce, 'aqualuxe_' . $action );
    }

    /**
     * Rate limiting for AJAX requests
     *
     * @since 1.0.0
     * @param string $action Action name
     * @param int    $limit  Rate limit (requests per minute)
     * @return bool
     */
    public function rate_limit( $action, $limit = 60 ) {
        $user_ip = $_SERVER['REMOTE_ADDR'] ?? '';
        $transient_key = 'aqualuxe_rate_limit_' . md5( $user_ip . $action );
        
        $current_count = get_transient( $transient_key );
        
        if ( false === $current_count ) {
            set_transient( $transient_key, 1, MINUTE_IN_SECONDS );
            return true;
        }
        
        if ( $current_count >= $limit ) {
            return false;
        }
        
        set_transient( $transient_key, $current_count + 1, MINUTE_IN_SECONDS );
        return true;
    }
}