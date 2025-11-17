<?php
/**
 * Fix for WordPress Recovery Mode Email Service warnings
 *
 * @package AquaLuxe
 */

/**
 * Filter to prevent array offset warnings in recovery mode email service
 */
function aqualuxe_recovery_mode_email_fix( $email_data ) {
    // If $email_data is not an array, provide a default array structure
    if ( ! is_array( $email_data ) ) {
        return array(
            'to'      => get_option( 'admin_email' ),
            'subject' => __( 'Recovery Mode Initiated', 'aqualuxe' ),
            'message' => __( 'Recovery mode has been initiated.', 'aqualuxe' ),
            'headers' => '',
        );
    }
    
    return $email_data;
}
add_filter( 'recovery_mode_email', 'aqualuxe_recovery_mode_email_fix', 5 );