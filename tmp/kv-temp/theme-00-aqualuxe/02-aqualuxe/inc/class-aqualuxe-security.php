<?php
/**
 * AquaLuxe Security Enhancements
 * 
 * @package AquaLuxe
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class AquaLuxe_Security {
    
    /**
     * Initialize security features
     */
    public static function init() {
        // Security headers
        add_action( 'send_headers', array( __CLASS__, 'add_security_headers' ) );
        
        // Hide WordPress version
        add_filter( 'the_generator', '__return_empty_string' );
        
        // Disable REST API for non-authenticated users
        add_filter( 'rest_authentication_errors', array( __CLASS__, 'disable_rest_api_for_non_auth_users' ) );
        
        // Disable XML-RPC
        add_filter( 'xmlrpc_enabled', '__return_false' );
        
        // Remove wp-json link from head
        remove_action( 'wp_head', 'rest_output_link_wp_head' );
        remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
        
        // Sanitize user input
        add_filter( 'preprocess_comment', array( __CLASS__, 'sanitize_comments' ) );
        
        // Prevent user enumeration
        add_action( 'wp', array( __CLASS__, 'prevent_user_enumeration' ) );
        
        // Secure login
        add_action( 'wp_login_failed', array( __CLASS__, 'log_failed_login' ) );
        add_filter( 'login_errors', array( __CLASS__, 'generic_login_errors' ) );
        
        // Content security policy
        add_action( 'wp_head', array( __CLASS__, 'content_security_policy' ), 1 );
        
        // XSS protection
        add_filter( 'wp_kses_allowed_html', array( __CLASS__, 'restrict_allowed_html' ), 10, 2 );
        
        // Sanitize uploads
        add_filter( 'wp_handle_upload_prefilter', array( __CLASS__, 'sanitize_file_uploads' ) );
    }
    
    /**
     * Add security headers
     */
    public static function add_security_headers() {
        // X-Content-Type-Options
        header( 'X-Content-Type-Options: nosniff' );
        
        // X-Frame-Options
        header( 'X-Frame-Options: SAMEORIGIN' );
        
        // X-XSS-Protection
        header( 'X-XSS-Protection: 1; mode=block' );
        
        // Referrer-Policy
        header( 'Referrer-Policy: strict-origin-when-cross-origin' );
        
        // Permissions-Policy
        header( 'Permissions-Policy: geolocation=(), microphone=(), camera=()' );
        
        // Strict-Transport-Security (only if HTTPS is enabled)
        if ( is_ssl() ) {
            header( 'Strict-Transport-Security: max-age=31536000; includeSubDomains; preload' );
        }
    }
    
    /**
     * Disable REST API for non-authenticated users
     */
    public static function disable_rest_api_for_non_auth_users( $access ) {
        if ( ! is_user_logged_in() ) {
            return new WP_Error( 'rest_cannot_access', __( 'Only authenticated users can access the REST API.', 'aqualuxe' ), array( 'status' => rest_authorization_required_code() ) );
        }
        
        return $access;
    }
    
    /**
     * Sanitize comments
     */
    public static function sanitize_comments( $commentdata ) {
        // Sanitize comment content
        $commentdata['comment_content'] = wp_kses( $commentdata['comment_content'], self::get_allowed_html() );
        
        // Sanitize author name
        $commentdata['comment_author'] = sanitize_text_field( $commentdata['comment_author'] );
        
        // Sanitize author email
        $commentdata['comment_author_email'] = sanitize_email( $commentdata['comment_author_email'] );
        
        // Sanitize author URL
        $commentdata['comment_author_url'] = esc_url_raw( $commentdata['comment_author_url'] );
        
        return $commentdata;
    }
    
    /**
     * Prevent user enumeration
     */
    public static function prevent_user_enumeration() {
        if ( ! is_admin() && ! empty( $_REQUEST['author'] ) ) {
            wp_redirect( home_url() );
            exit;
        }
    }
    
    /**
     * Log failed login attempts
     */
    public static function log_failed_login( $username ) {
        // Log the failed attempt
        error_log( sprintf(
            'Failed login attempt for username: %s from IP: %s on %s',
            $username,
            $_SERVER['REMOTE_ADDR'],
            date( 'Y-m-d H:i:s' )
        ) );
        
        // Optionally, you could implement rate limiting here
        // For example, lock out IPs after too many failed attempts
    }
    
    /**
     * Generic login errors
     */
    public static function generic_login_errors() {
        return __( 'Invalid username or password.', 'aqualuxe' );
    }
    
    /**
     * Content Security Policy
     */
    public static function content_security_policy() {
        // Only apply CSP on frontend
        if ( is_admin() ) {
            return;
        }
        
        // Basic CSP policy
        $csp = "default-src 'self'; ";
        $csp .= "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://www.google-analytics.com; ";
        $csp .= "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; ";
        $csp .= "img-src 'self' data: https:; ";
        $csp .= "font-src 'self' https://fonts.gstatic.com; ";
        $csp .= "connect-src 'self'; ";
        $csp .= "frame-ancestors 'self';";
        
        echo '<meta http-equiv="Content-Security-Policy" content="' . esc_attr( $csp ) . '">' . "\n";
    }
    
    /**
     * Restrict allowed HTML
     */
    public static function restrict_allowed_html( $tags, $context ) {
        // Only modify allowed HTML for posts
        if ( $context !== 'post' ) {
            return $tags;
        }
        
        // Remove potentially dangerous tags
        unset( $tags['script'] );
        unset( $tags['iframe'] );
        unset( $tags['object'] );
        unset( $tags['embed'] );
        unset( $tags['form'] );
        
        // Restrict attributes for certain tags
        if ( isset( $tags['a'] ) ) {
            $tags['a'] = array(
                'href' => true,
                'title' => true,
                'target' => true,
                'rel' => true,
            );
        }
        
        if ( isset( $tags['img'] ) ) {
            $tags['img'] = array(
                'src' => true,
                'alt' => true,
                'title' => true,
                'width' => true,
                'height' => true,
            );
        }
        
        return $tags;
    }
    
    /**
     * Sanitize file uploads
     */
    public static function sanitize_file_uploads( $file ) {
        // Sanitize filename
        $file['name'] = sanitize_file_name( $file['name'] );
        
        // Check file type
        $allowed_types = array( 'jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx' );
        $file_type = wp_check_filetype( $file['name'] );
        
        if ( ! in_array( $file_type['ext'], $allowed_types ) ) {
            $file['error'] = __( 'Invalid file type. Please upload a valid file.', 'aqualuxe' );
        }
        
        return $file;
    }
    
    /**
     * Get allowed HTML tags
     */
    private static function get_allowed_html() {
        return array(
            'a' => array(
                'href' => array(),
                'title' => array(),
            ),
            'br' => array(),
            'em' => array(),
            'strong' => array(),
            'p' => array(),
            'ul' => array(),
            'ol' => array(),
            'li' => array(),
        );
    }
    
    /**
     * Sanitize output
     */
    public static function sanitize_output( $output ) {
        return esc_html( $output );
    }
    
    /**
     * Sanitize URL
     */
    public static function sanitize_url( $url ) {
        return esc_url( $url );
    }
    
    /**
     * Sanitize text field
     */
    public static function sanitize_text_field( $text ) {
        return sanitize_text_field( $text );
    }
    
    /**
     * Nonce field
     */
    public static function nonce_field( $action = 'aqualuxe_nonce', $name = '_wpnonce', $referer = true, $echo = true ) {
        return wp_nonce_field( $action, $name, $referer, $echo );
    }
    
    /**
     * Verify nonce
     */
    public static function verify_nonce( $nonce, $action = 'aqualuxe_nonce' ) {
        return wp_verify_nonce( $nonce, $action );
    }
}

// Initialize
AquaLuxe_Security::init();