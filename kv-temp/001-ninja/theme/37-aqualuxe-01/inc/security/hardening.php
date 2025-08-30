<?php
/**
 * Security hardening measures for AquaLuxe theme
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
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
		// Remove WordPress version from head.
		remove_action( 'wp_head', 'wp_generator' );

		// Disable XML-RPC if not needed.
		add_filter( 'xmlrpc_enabled', '__return_false' );

		// Disable file editing in admin.
		if ( ! defined( 'DISALLOW_FILE_EDIT' ) ) {
			define( 'DISALLOW_FILE_EDIT', true );
		}

		// Add security headers.
		add_action( 'send_headers', array( $this, 'add_security_headers' ) );

		// Prevent information disclosure.
		add_filter( 'login_errors', array( $this, 'custom_login_error' ) );

		// Disable user enumeration.
		add_action( 'init', array( $this, 'disable_user_enumeration' ) );
        
        // Add nonce verification to forms.
        add_action( 'init', array( $this, 'add_nonce_verification' ) );
        
        // Filter comment data to prevent XSS.
        add_filter( 'preprocess_comment', array( $this, 'filter_comment_data' ) );
        
        // Sanitize file names on upload.
        add_filter( 'sanitize_file_name', array( $this, 'sanitize_uploaded_file_name' ), 10, 1 );
	}

	/**
	 * Add security headers
	 */
	public function add_security_headers() {
		// X-Content-Type-Options.
		header( 'X-Content-Type-Options: nosniff' );

		// X-Frame-Options.
		header( 'X-Frame-Options: SAMEORIGIN' );

		// X-XSS-Protection.
		header( 'X-XSS-Protection: 1; mode=block' );

		// Referrer-Policy.
		header( 'Referrer-Policy: strict-origin-when-cross-origin' );

		// Permissions-Policy.
		header( 'Permissions-Policy: geolocation=(), microphone=(), camera=()' );
        
        // Content-Security-Policy (optional, commented out as it may break some sites).
        // header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval' https://www.google-analytics.com; style-src 'self' 'unsafe-inline'; img-src 'self' data: https://www.google-analytics.com; font-src 'self' data:; connect-src 'self' https://www.google-analytics.com");
	}

	/**
	 * Custom login error message
	 *
	 * @return string
	 */
	public function custom_login_error() {
		return esc_html__( 'Login credentials are incorrect. Please try again.', 'aqualuxe' );
	}

	/**
	 * Disable user enumeration
	 */
	public function disable_user_enumeration() {
		if ( isset( $_GET['author'] ) && ! is_admin() ) {
			wp_safe_redirect( home_url(), 301 );
			exit;
		}
	}
    
    /**
     * Add nonce verification to forms
     */
    public function add_nonce_verification() {
        // Add nonce field to comment form.
        add_action( 'comment_form', array( $this, 'add_comment_form_nonce' ) );
        
        // Verify comment form nonce.
        add_filter( 'pre_comment_approved', array( $this, 'verify_comment_form_nonce' ), 10, 2 );
    }
    
    /**
     * Add nonce field to comment form
     */
    public function add_comment_form_nonce() {
        wp_nonce_field( 'aqualuxe_comment_nonce', 'aqualuxe_comment_nonce', false );
    }
    
    /**
     * Verify comment form nonce
     *
     * @param mixed $approved    The approval status.
     * @param array $commentdata Comment data.
     * @return mixed
     */
    public function verify_comment_form_nonce( $approved, $commentdata ) {
        // Skip nonce check for logged-in users or if it's a trackback/pingback.
        if ( is_user_logged_in() || in_array( $commentdata['comment_type'], array( 'trackback', 'pingback' ), true ) ) {
            return $approved;
        }
        
        // Check the nonce.
        if ( ! isset( $_POST['aqualuxe_comment_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['aqualuxe_comment_nonce'] ) ), 'aqualuxe_comment_nonce' ) ) {
            return 'spam';
        }
        
        return $approved;
    }
    
    /**
     * Filter comment data to prevent XSS
     *
     * @param array $commentdata Comment data.
     * @return array
     */
    public function filter_comment_data( $commentdata ) {
        if ( isset( $commentdata['comment_content'] ) ) {
            $commentdata['comment_content'] = wp_kses_post( $commentdata['comment_content'] );
        }
        
        if ( isset( $commentdata['comment_author'] ) ) {
            $commentdata['comment_author'] = sanitize_text_field( $commentdata['comment_author'] );
        }
        
        if ( isset( $commentdata['comment_author_url'] ) ) {
            $commentdata['comment_author_url'] = esc_url_raw( $commentdata['comment_author_url'] );
        }
        
        if ( isset( $commentdata['comment_author_email'] ) ) {
            $commentdata['comment_author_email'] = sanitize_email( $commentdata['comment_author_email'] );
        }
        
        return $commentdata;
    }
    
    /**
     * Sanitize uploaded file name
     *
     * @param string $filename File name.
     * @return string
     */
    public function sanitize_uploaded_file_name( $filename ) {
        // Remove special characters.
        $filename = preg_replace( '/[^a-zA-Z0-9._-]/', '', $filename );
        
        // Remove multiple dots.
        $filename = preg_replace( '/\.+/', '.', $filename );
        
        // Remove executable file extensions.
        $executable_extensions = array( 'php', 'phtml', 'php3', 'php4', 'php5', 'php7', 'pht', 'phar', 'exe', 'pl', 'py', 'jsp', 'asp', 'aspx', 'cgi', 'sh', 'bat' );
        $file_parts = explode( '.', $filename );
        $extension = end( $file_parts );
        
        if ( in_array( strtolower( $extension ), $executable_extensions, true ) ) {
            $filename .= '.txt';
        }
        
        return $filename;
    }

	/**
	 * Verify nonce for admin actions
	 *
	 * @param string $nonce Nonce to verify.
	 * @param string $action Action name.
	 * @return bool
	 */
	public static function verify_nonce( $nonce, $action ) {
		if ( ! wp_verify_nonce( $nonce, $action ) ) {
			return false;
		}
		return true;
	}

	/**
	 * Check user capabilities
	 *
	 * @param string $capability Capability to check.
	 * @return bool
	 */
	public static function check_capability( $capability = 'manage_options' ) {
		if ( ! current_user_can( $capability ) ) {
			return false;
		}
		return true;
	}

	/**
	 * Sanitize and validate input
	 *
	 * @param mixed  $input Input to sanitize.
	 * @param string $type Type of sanitization.
	 * @return mixed
	 */
	public static function sanitize_input( $input, $type = 'text' ) {
		switch ( $type ) {
			case 'email':
				return sanitize_email( $input );
			case 'url':
				return esc_url_raw( $input );
			case 'int':
				return intval( $input );
			case 'float':
				return floatval( $input );
			case 'html':
				return wp_kses_post( $input );
			case 'color':
				return sanitize_hex_color( $input );
			case 'textarea':
				return sanitize_textarea_field( $input );
			case 'filename':
				return sanitize_file_name( $input );
			case 'key':
				return sanitize_key( $input );
			case 'title':
				return sanitize_title( $input );
			case 'text':
			default:
				return sanitize_text_field( $input );
		}
	}
    
    /**
     * Validate and sanitize array of inputs
     *
     * @param array  $input_array Array of inputs.
     * @param string $type Type of sanitization.
     * @return array
     */
    public static function sanitize_array( $input_array, $type = 'text' ) {
        if ( ! is_array( $input_array ) ) {
            return array();
        }
        
        $sanitized_array = array();
        
        foreach ( $input_array as $key => $value ) {
            if ( is_array( $value ) ) {
                $sanitized_array[ sanitize_key( $key ) ] = self::sanitize_array( $value, $type );
            } else {
                $sanitized_array[ sanitize_key( $key ) ] = self::sanitize_input( $value, $type );
            }
        }
        
        return $sanitized_array;
    }
}

// Initialize security measures.
new AquaLuxe_Security();