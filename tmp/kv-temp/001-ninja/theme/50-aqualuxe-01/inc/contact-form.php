<?php
/**
 * Contact Form Handler
 *
 * @package AquaLuxe
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Initialize contact form functionality
 */
function aqualuxe_contact_form_init() {
    // Register action to handle form submission
    add_action( 'admin_post_aqualuxe_contact_form', 'aqualuxe_handle_contact_form' );
    add_action( 'admin_post_nopriv_aqualuxe_contact_form', 'aqualuxe_handle_contact_form' );
}
add_action( 'init', 'aqualuxe_contact_form_init' );

/**
 * Handle contact form submission
 */
function aqualuxe_handle_contact_form() {
    // Check nonce for security
    if ( ! isset( $_POST['contact_nonce'] ) || ! wp_verify_nonce( $_POST['contact_nonce'], 'aqualuxe_contact_nonce' ) ) {
        wp_die( esc_html__( 'Security check failed. Please try again.', 'aqualuxe' ), esc_html__( 'Security Error', 'aqualuxe' ), array( 'response' => 403 ) );
    }

    // Get form data
    $name = isset( $_POST['contact_name'] ) ? sanitize_text_field( $_POST['contact_name'] ) : '';
    $email = isset( $_POST['contact_email'] ) ? sanitize_email( $_POST['contact_email'] ) : '';
    $phone = isset( $_POST['contact_phone'] ) ? sanitize_text_field( $_POST['contact_phone'] ) : '';
    $subject = isset( $_POST['contact_subject'] ) ? sanitize_text_field( $_POST['contact_subject'] ) : '';
    $service = isset( $_POST['contact_service'] ) ? sanitize_text_field( $_POST['contact_service'] ) : '';
    $message = isset( $_POST['contact_message'] ) ? sanitize_textarea_field( $_POST['contact_message'] ) : '';
    $consent = isset( $_POST['contact_consent'] ) ? true : false;

    // Validate required fields
    $errors = array();

    if ( empty( $name ) ) {
        $errors[] = esc_html__( 'Please enter your name.', 'aqualuxe' );
    }

    if ( empty( $email ) ) {
        $errors[] = esc_html__( 'Please enter your email address.', 'aqualuxe' );
    } elseif ( ! is_email( $email ) ) {
        $errors[] = esc_html__( 'Please enter a valid email address.', 'aqualuxe' );
    }

    if ( empty( $subject ) ) {
        $errors[] = esc_html__( 'Please enter a subject.', 'aqualuxe' );
    }

    if ( empty( $message ) ) {
        $errors[] = esc_html__( 'Please enter your message.', 'aqualuxe' );
    }

    if ( ! $consent ) {
        $errors[] = esc_html__( 'You must agree to the privacy policy.', 'aqualuxe' );
    }

    // If there are errors, redirect back with error messages
    if ( ! empty( $errors ) ) {
        $error_message = implode( '<br>', $errors );
        set_transient( 'aqualuxe_contact_errors', $error_message, 60 * 5 ); // Store for 5 minutes
        
        // Store form data to repopulate the form
        set_transient( 'aqualuxe_contact_form_data', array(
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'subject' => $subject,
            'service' => $service,
            'message' => $message,
        ), 60 * 5 ); // Store for 5 minutes
        
        // Redirect back to the contact page
        wp_safe_redirect( wp_get_referer() . '#aqualuxe-contact-form' );
        exit;
    }

    // Prepare email content
    $to = get_option( 'admin_email' );
    $email_subject = sprintf( '[%s] New Contact Form Submission: %s', get_bloginfo( 'name' ), $subject );
    
    $email_content = sprintf(
        "You have received a new contact form submission from your website %s.\n\n",
        get_bloginfo( 'name' )
    );
    
    $email_content .= sprintf( "Name: %s\n", $name );
    $email_content .= sprintf( "Email: %s\n", $email );
    
    if ( ! empty( $phone ) ) {
        $email_content .= sprintf( "Phone: %s\n", $phone );
    }
    
    $email_content .= sprintf( "Subject: %s\n", $subject );
    
    if ( ! empty( $service ) ) {
        $service_labels = array(
            'design' => esc_html__( 'Aquarium Design & Installation', 'aqualuxe' ),
            'maintenance' => esc_html__( 'Maintenance Services', 'aqualuxe' ),
            'health' => esc_html__( 'Aquatic Health Services', 'aqualuxe' ),
            'consultation' => esc_html__( 'Expert Consultation', 'aqualuxe' ),
            'commercial' => esc_html__( 'Commercial Installations', 'aqualuxe' ),
            'rare' => esc_html__( 'Rare Species Sourcing', 'aqualuxe' ),
            'other' => esc_html__( 'Other', 'aqualuxe' ),
        );
        
        $service_label = isset( $service_labels[ $service ] ) ? $service_labels[ $service ] : $service;
        $email_content .= sprintf( "Service: %s\n", $service_label );
    }
    
    $email_content .= sprintf( "\nMessage:\n%s\n", $message );
    $email_content .= sprintf( "\nThis message was sent on %s at %s", date_i18n( get_option( 'date_format' ) ), date_i18n( get_option( 'time_format' ) ) );

    // Set headers
    $headers = array();
    $headers[] = 'Content-Type: text/plain; charset=UTF-8';
    $headers[] = 'From: ' . $name . ' <' . $email . '>';
    $headers[] = 'Reply-To: ' . $email;

    // Send email
    $sent = wp_mail( $to, $email_subject, $email_content, $headers );

    // Store submission in database (optional)
    if ( function_exists( 'aqualuxe_store_contact_submission' ) ) {
        aqualuxe_store_contact_submission( array(
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'subject' => $subject,
            'service' => $service,
            'message' => $message,
            'date' => current_time( 'mysql' ),
            'ip' => aqualuxe_get_client_ip(),
            'status' => $sent ? 'sent' : 'failed',
        ) );
    }

    // Set success message
    if ( $sent ) {
        set_transient( 'aqualuxe_contact_success', esc_html__( 'Thank you for your message! We will get back to you as soon as possible.', 'aqualuxe' ), 60 * 5 ); // Store for 5 minutes
    } else {
        set_transient( 'aqualuxe_contact_errors', esc_html__( 'There was a problem sending your message. Please try again later.', 'aqualuxe' ), 60 * 5 ); // Store for 5 minutes
    }

    // Redirect back to the contact page
    wp_safe_redirect( wp_get_referer() . '#aqualuxe-contact-form' );
    exit;
}

/**
 * Get client IP address
 *
 * @return string
 */
function aqualuxe_get_client_ip() {
    $ip_keys = array(
        'HTTP_CLIENT_IP',
        'HTTP_X_FORWARDED_FOR',
        'HTTP_X_FORWARDED',
        'HTTP_X_CLUSTER_CLIENT_IP',
        'HTTP_FORWARDED_FOR',
        'HTTP_FORWARDED',
        'REMOTE_ADDR',
    );

    foreach ( $ip_keys as $key ) {
        if ( isset( $_SERVER[ $key ] ) && filter_var( $_SERVER[ $key ], FILTER_VALIDATE_IP ) ) {
            return sanitize_text_field( $_SERVER[ $key ] );
        }
    }

    return '127.0.0.1'; // Default local IP
}

/**
 * Display contact form messages
 */
function aqualuxe_display_contact_form_messages() {
    $error_message = get_transient( 'aqualuxe_contact_errors' );
    $success_message = get_transient( 'aqualuxe_contact_success' );
    
    if ( $error_message ) {
        echo '<div class="contact-form-message error">' . wp_kses_post( $error_message ) . '</div>';
        delete_transient( 'aqualuxe_contact_errors' );
    }
    
    if ( $success_message ) {
        echo '<div class="contact-form-message success">' . esc_html( $success_message ) . '</div>';
        delete_transient( 'aqualuxe_contact_success' );
    }
}
add_action( 'aqualuxe_before_contact_form', 'aqualuxe_display_contact_form_messages' );

/**
 * Get stored contact form data
 *
 * @return array
 */
function aqualuxe_get_contact_form_data() {
    $form_data = get_transient( 'aqualuxe_contact_form_data' );
    
    if ( $form_data ) {
        delete_transient( 'aqualuxe_contact_form_data' );
        return $form_data;
    }
    
    return array(
        'name' => '',
        'email' => '',
        'phone' => '',
        'subject' => '',
        'service' => '',
        'message' => '',
    );
}