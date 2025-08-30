<?php
/**
 * Security Functions
 *
 * Functions for enhancing theme security including input sanitization,
 * output escaping, nonce verification, and user capability checks.
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Class AquaLuxe_Security
 */
class AquaLuxe_Security {

    /**
     * Constructor
     */
    public function __construct() {
        // Add security headers
        add_action( 'send_headers', array( $this, 'add_security_headers' ) );
        
        // Disable XML-RPC
        add_filter( 'xmlrpc_enabled', '__return_false' );
        
        // Remove WordPress version from head
        remove_action( 'wp_head', 'wp_generator' );
        
        // Remove WordPress version from RSS feeds
        add_filter( 'the_generator', '__return_empty_string' );
        
        // Disable user enumeration
        add_action( 'init', array( $this, 'disable_user_enumeration' ) );
        
        // Hide login errors
        add_filter( 'login_errors', array( $this, 'hide_login_errors' ) );
        
        // Disable file editing in admin
        if ( ! defined( 'DISALLOW_FILE_EDIT' ) ) {
            define( 'DISALLOW_FILE_EDIT', true );
        }
        
        // Add nonce to logout URL
        add_action( 'wp_logout', array( $this, 'add_logout_nonce' ) );
        
        // Verify AJAX requests
        add_action( 'admin_init', array( $this, 'verify_ajax_requests' ) );
        
        // Add security to comments
        add_filter( 'preprocess_comment', array( $this, 'verify_comment_form' ) );
        
        // Add security to contact forms
        add_action( 'wp_loaded', array( $this, 'process_contact_form' ) );
        
        // Add security to search form
        add_action( 'pre_get_posts', array( $this, 'sanitize_search_query' ) );
    }

    /**
     * Add security headers
     */
    public function add_security_headers() {
        // X-Content-Type-Options
        header( 'X-Content-Type-Options: nosniff' );
        
        // X-Frame-Options
        header( 'X-Frame-Options: SAMEORIGIN' );
        
        // X-XSS-Protection
        header( 'X-XSS-Protection: 1; mode=block' );
        
        // Referrer-Policy
        header( 'Referrer-Policy: strict-origin-when-cross-origin' );
        
        // Permissions-Policy
        header( 'Permissions-Policy: geolocation=(self), microphone=(), camera=()' );
    }

    /**
     * Disable user enumeration
     */
    public function disable_user_enumeration() {
        if ( ! is_admin() && isset( $_GET['author'] ) && ! current_user_can( 'edit_posts' ) ) {
            wp_safe_redirect( home_url(), 301 );
            exit;
        }
    }

    /**
     * Hide login errors
     *
     * @param string $error The error message.
     * @return string Modified error message.
     */
    public function hide_login_errors( $error ) {
        return __( 'Invalid username or password.', 'aqualuxe' );
    }

    /**
     * Add nonce to logout URL
     */
    public function add_logout_nonce() {
        $redirect_to = isset( $_REQUEST['redirect_to'] ) ? $_REQUEST['redirect_to'] : home_url();
        $redirect_to = wp_validate_redirect( $redirect_to, home_url() );
        
        wp_safe_redirect( $redirect_to );
        exit;
    }

    /**
     * Verify AJAX requests
     */
    public function verify_ajax_requests() {
        if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
            // Check referer
            check_ajax_referer( 'aqualuxe_ajax_nonce', 'security' );
            
            // Check capabilities
            if ( ! current_user_can( 'read' ) ) {
                wp_send_json_error( array( 'message' => __( 'You do not have permission to perform this action.', 'aqualuxe' ) ) );
                exit;
            }
        }
    }

    /**
     * Verify comment form
     *
     * @param array $commentdata Comment data.
     * @return array Modified comment data.
     */
    public function verify_comment_form( $commentdata ) {
        // Check if comment form is submitted
        if ( isset( $_POST['comment'] ) ) {
            // Verify nonce
            if ( ! isset( $_POST['aqualuxe_comment_nonce'] ) || ! wp_verify_nonce( $_POST['aqualuxe_comment_nonce'], 'aqualuxe_comment_nonce' ) ) {
                wp_die( __( 'Security check failed. Please try again.', 'aqualuxe' ), __( 'Security Error', 'aqualuxe' ), array( 'back_link' => true ) );
            }
            
            // Sanitize comment data
            $commentdata['comment_content'] = sanitize_textarea_field( $commentdata['comment_content'] );
            $commentdata['comment_author'] = sanitize_text_field( $commentdata['comment_author'] );
            $commentdata['comment_author_email'] = sanitize_email( $commentdata['comment_author_email'] );
            $commentdata['comment_author_url'] = esc_url_raw( $commentdata['comment_author_url'] );
        }
        
        return $commentdata;
    }

    /**
     * Process contact form
     */
    public function process_contact_form() {
        // Check if contact form is submitted
        if ( isset( $_POST['aqualuxe_contact_form'] ) ) {
            // Verify nonce
            if ( ! isset( $_POST['aqualuxe_contact_nonce'] ) || ! wp_verify_nonce( $_POST['aqualuxe_contact_nonce'], 'aqualuxe_contact_nonce' ) ) {
                wp_die( __( 'Security check failed. Please try again.', 'aqualuxe' ), __( 'Security Error', 'aqualuxe' ), array( 'back_link' => true ) );
            }
            
            // Sanitize form data
            $name = isset( $_POST['name'] ) ? sanitize_text_field( $_POST['name'] ) : '';
            $email = isset( $_POST['email'] ) ? sanitize_email( $_POST['email'] ) : '';
            $subject = isset( $_POST['subject'] ) ? sanitize_text_field( $_POST['subject'] ) : '';
            $message = isset( $_POST['message'] ) ? sanitize_textarea_field( $_POST['message'] ) : '';
            
            // Validate form data
            $errors = array();
            
            if ( empty( $name ) ) {
                $errors['name'] = __( 'Please enter your name.', 'aqualuxe' );
            }
            
            if ( empty( $email ) ) {
                $errors['email'] = __( 'Please enter your email address.', 'aqualuxe' );
            } elseif ( ! is_email( $email ) ) {
                $errors['email'] = __( 'Please enter a valid email address.', 'aqualuxe' );
            }
            
            if ( empty( $subject ) ) {
                $errors['subject'] = __( 'Please enter a subject.', 'aqualuxe' );
            }
            
            if ( empty( $message ) ) {
                $errors['message'] = __( 'Please enter a message.', 'aqualuxe' );
            }
            
            // Process form if no errors
            if ( empty( $errors ) ) {
                // Set up email
                $to = get_option( 'admin_email' );
                $subject = sprintf( __( '[%s] Contact Form: %s', 'aqualuxe' ), get_bloginfo( 'name' ), $subject );
                $body = sprintf( __( 'Name: %s', 'aqualuxe' ), $name ) . "\r\n\r\n";
                $body .= sprintf( __( 'Email: %s', 'aqualuxe' ), $email ) . "\r\n\r\n";
                $body .= sprintf( __( 'Message: %s', 'aqualuxe' ), $message ) . "\r\n\r\n";
                $headers = array(
                    'From: ' . $name . ' <' . $email . '>',
                    'Reply-To: ' . $name . ' <' . $email . '>',
                );
                
                // Send email
                $sent = wp_mail( $to, $subject, $body, $headers );
                
                // Redirect to thank you page
                if ( $sent ) {
                    $redirect_url = add_query_arg( 'contact', 'success', wp_get_referer() );
                } else {
                    $redirect_url = add_query_arg( 'contact', 'error', wp_get_referer() );
                }
                
                wp_safe_redirect( $redirect_url );
                exit;
            } else {
                // Store errors in session
                if ( ! session_id() ) {
                    session_start();
                }
                
                $_SESSION['aqualuxe_contact_errors'] = $errors;
                $_SESSION['aqualuxe_contact_data'] = array(
                    'name' => $name,
                    'email' => $email,
                    'subject' => $subject,
                    'message' => $message,
                );
                
                // Redirect back to form
                wp_safe_redirect( wp_get_referer() );
                exit;
            }
        }
    }

    /**
     * Sanitize search query
     *
     * @param WP_Query $query The WP_Query instance.
     */
    public function sanitize_search_query( $query ) {
        if ( ! is_admin() && $query->is_main_query() && $query->is_search() ) {
            $search_term = $query->get( 's' );
            $search_term = sanitize_text_field( $search_term );
            $query->set( 's', $search_term );
        }
    }
}

// Initialize the class
new AquaLuxe_Security();

/**
 * Sanitize and escape functions
 */

/**
 * Sanitize text field with additional security measures
 *
 * @param string $input The input to sanitize.
 * @return string Sanitized input.
 */
function aqualuxe_sanitize_text( $input ) {
    return sanitize_text_field( $input );
}

/**
 * Sanitize textarea field with additional security measures
 *
 * @param string $input The input to sanitize.
 * @return string Sanitized input.
 */
function aqualuxe_sanitize_textarea( $input ) {
    return sanitize_textarea_field( $input );
}

/**
 * Sanitize email field with additional security measures
 *
 * @param string $input The input to sanitize.
 * @return string Sanitized input.
 */
function aqualuxe_sanitize_email( $input ) {
    return sanitize_email( $input );
}

/**
 * Sanitize URL field with additional security measures
 *
 * @param string $input The input to sanitize.
 * @return string Sanitized input.
 */
function aqualuxe_sanitize_url( $input ) {
    return esc_url_raw( $input );
}

/**
 * Sanitize checkbox field
 *
 * @param bool $input The input to sanitize.
 * @return bool Sanitized input.
 */
function aqualuxe_sanitize_checkbox( $input ) {
    return ( isset( $input ) && true === $input ) ? true : false;
}

/**
 * Sanitize select field
 *
 * @param string $input   The input to sanitize.
 * @param array  $options The valid options.
 * @param string $default The default value.
 * @return string Sanitized input.
 */
function aqualuxe_sanitize_select( $input, $options, $default = '' ) {
    if ( array_key_exists( $input, $options ) ) {
        return $input;
    }
    
    return $default;
}

/**
 * Sanitize number field
 *
 * @param int $input   The input to sanitize.
 * @param int $min     The minimum value.
 * @param int $max     The maximum value.
 * @param int $default The default value.
 * @return int Sanitized input.
 */
function aqualuxe_sanitize_number( $input, $min = 0, $max = 100, $default = 0 ) {
    $input = absint( $input );
    
    if ( $input < $min || $input > $max ) {
        return $default;
    }
    
    return $input;
}

/**
 * Sanitize float field
 *
 * @param float $input   The input to sanitize.
 * @param float $min     The minimum value.
 * @param float $max     The maximum value.
 * @param float $default The default value.
 * @return float Sanitized input.
 */
function aqualuxe_sanitize_float( $input, $min = 0, $max = 100, $default = 0 ) {
    $input = (float) $input;
    
    if ( $input < $min || $input > $max ) {
        return $default;
    }
    
    return $input;
}

/**
 * Sanitize hex color field
 *
 * @param string $input The input to sanitize.
 * @return string Sanitized input.
 */
function aqualuxe_sanitize_hex_color( $input ) {
    return sanitize_hex_color( $input );
}

/**
 * Sanitize date field
 *
 * @param string $input The input to sanitize.
 * @return string Sanitized input.
 */
function aqualuxe_sanitize_date( $input ) {
    $date = date_create( $input );
    
    if ( $date ) {
        return date_format( $date, 'Y-m-d' );
    }
    
    return '';
}

/**
 * Sanitize array field
 *
 * @param array  $input    The input to sanitize.
 * @param string $sanitize The sanitize function to use.
 * @return array Sanitized input.
 */
function aqualuxe_sanitize_array( $input, $sanitize = 'sanitize_text_field' ) {
    if ( ! is_array( $input ) ) {
        return array();
    }
    
    $output = array();
    
    foreach ( $input as $key => $value ) {
        if ( is_array( $value ) ) {
            $output[ $key ] = aqualuxe_sanitize_array( $value, $sanitize );
        } else {
            $output[ $key ] = $sanitize( $value );
        }
    }
    
    return $output;
}

/**
 * Escape HTML with additional security measures
 *
 * @param string $input The input to escape.
 * @return string Escaped input.
 */
function aqualuxe_esc_html( $input ) {
    return esc_html( $input );
}

/**
 * Escape URL with additional security measures
 *
 * @param string $input The input to escape.
 * @return string Escaped input.
 */
function aqualuxe_esc_url( $input ) {
    return esc_url( $input );
}

/**
 * Escape attribute with additional security measures
 *
 * @param string $input The input to escape.
 * @return string Escaped input.
 */
function aqualuxe_esc_attr( $input ) {
    return esc_attr( $input );
}

/**
 * Escape textarea with additional security measures
 *
 * @param string $input The input to escape.
 * @return string Escaped input.
 */
function aqualuxe_esc_textarea( $input ) {
    return esc_textarea( $input );
}

/**
 * Create nonce field with additional security measures
 *
 * @param string $action  The nonce action.
 * @param string $name    The nonce name.
 * @param bool   $referer Whether to include the referer field.
 * @param bool   $echo    Whether to echo or return the nonce field.
 * @return string The nonce field.
 */
function aqualuxe_nonce_field( $action, $name, $referer = true, $echo = true ) {
    return wp_nonce_field( $action, $name, $referer, $echo );
}

/**
 * Create nonce URL with additional security measures
 *
 * @param string $url    The URL to add the nonce to.
 * @param string $action The nonce action.
 * @param string $name   The nonce name.
 * @return string The URL with the nonce.
 */
function aqualuxe_nonce_url( $url, $action, $name = '_wpnonce' ) {
    return wp_nonce_url( $url, $action, $name );
}

/**
 * Verify nonce with additional security measures
 *
 * @param string $nonce  The nonce to verify.
 * @param string $action The nonce action.
 * @return bool Whether the nonce is valid.
 */
function aqualuxe_verify_nonce( $nonce, $action ) {
    return wp_verify_nonce( $nonce, $action );
}

/**
 * Check user capability with additional security measures
 *
 * @param string $capability The capability to check.
 * @param int    $user_id    The user ID to check.
 * @return bool Whether the user has the capability.
 */
function aqualuxe_user_can( $capability, $user_id = null ) {
    if ( null === $user_id ) {
        $user_id = get_current_user_id();
    }
    
    return user_can( $user_id, $capability );
}

/**
 * Add nonce to form
 *
 * @param string $action The nonce action.
 * @param string $name   The nonce name.
 */
function aqualuxe_add_nonce_to_form( $action, $name ) {
    wp_nonce_field( $action, $name );
}

/**
 * Verify form nonce
 *
 * @param string $action The nonce action.
 * @param string $name   The nonce name.
 * @return bool Whether the nonce is valid.
 */
function aqualuxe_verify_form_nonce( $action, $name ) {
    if ( ! isset( $_POST[ $name ] ) ) {
        return false;
    }
    
    return wp_verify_nonce( $_POST[ $name ], $action );
}

/**
 * Add nonce to AJAX request
 *
 * @param string $action The nonce action.
 * @return string The nonce.
 */
function aqualuxe_add_ajax_nonce( $action ) {
    return wp_create_nonce( $action );
}

/**
 * Verify AJAX nonce
 *
 * @param string $action The nonce action.
 * @param string $name   The nonce name.
 * @return bool Whether the nonce is valid.
 */
function aqualuxe_verify_ajax_nonce( $action, $name = 'security' ) {
    if ( ! isset( $_REQUEST[ $name ] ) ) {
        return false;
    }
    
    return check_ajax_referer( $action, $name, false );
}

/**
 * Check user role with additional security measures
 *
 * @param string $role    The role to check.
 * @param int    $user_id The user ID to check.
 * @return bool Whether the user has the role.
 */
function aqualuxe_user_has_role( $role, $user_id = null ) {
    if ( null === $user_id ) {
        $user_id = get_current_user_id();
    }
    
    $user = get_userdata( $user_id );
    
    if ( ! $user ) {
        return false;
    }
    
    return in_array( $role, (array) $user->roles, true );
}

/**
 * Add comment form nonce
 */
function aqualuxe_add_comment_form_nonce() {
    wp_nonce_field( 'aqualuxe_comment_nonce', 'aqualuxe_comment_nonce' );
}
add_action( 'comment_form', 'aqualuxe_add_comment_form_nonce' );

/**
 * Add contact form nonce
 *
 * @param string $content The form content.
 * @return string Modified form content.
 */
function aqualuxe_add_contact_form_nonce( $content ) {
    if ( strpos( $content, 'aqualuxe_contact_form' ) !== false ) {
        $nonce_field = wp_nonce_field( 'aqualuxe_contact_nonce', 'aqualuxe_contact_nonce', false, false );
        $content = str_replace( '<form', '<form ' . $nonce_field, $content );
    }
    
    return $content;
}
add_filter( 'the_content', 'aqualuxe_add_contact_form_nonce' );