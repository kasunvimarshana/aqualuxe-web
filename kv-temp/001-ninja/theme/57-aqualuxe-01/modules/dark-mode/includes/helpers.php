<?php
/**
 * Dark Mode Helper Functions
 *
 * @package AquaLuxe
 * @subpackage Modules/DarkMode
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Check if dark mode is active
 *
 * @return bool
 */
function aqualuxe_is_dark_mode_active() {
    $dark_mode_module = aqualuxe_get_module( 'dark-mode' );
    
    if ( $dark_mode_module ) {
        return $dark_mode_module->is_dark_mode_active();
    }
    
    return false;
}

/**
 * Get dark mode toggle HTML
 *
 * @return string
 */
function aqualuxe_get_dark_mode_toggle() {
    ob_start();
    
    $dark_mode_module = aqualuxe_get_module( 'dark-mode' );
    
    if ( $dark_mode_module ) {
        $dark_mode_module->render_dark_mode_toggle();
    }
    
    return ob_get_clean();
}

/**
 * Output dark mode toggle
 *
 * @return void
 */
function aqualuxe_dark_mode_toggle() {
    echo aqualuxe_get_dark_mode_toggle();
}

/**
 * Register AJAX handlers for dark mode
 */
function aqualuxe_dark_mode_register_ajax_handlers() {
    // Save user preference
    add_action( 'wp_ajax_aqualuxe_save_dark_mode_preference', 'aqualuxe_ajax_save_dark_mode_preference' );
    add_action( 'wp_ajax_nopriv_aqualuxe_save_dark_mode_preference', 'aqualuxe_ajax_save_dark_mode_preference' );
}
add_action( 'init', 'aqualuxe_dark_mode_register_ajax_handlers' );

/**
 * AJAX handler for saving dark mode preference
 */
function aqualuxe_ajax_save_dark_mode_preference() {
    // Verify nonce
    if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'aqualuxe_dark_mode_nonce' ) ) {
        wp_send_json_error( array( 'message' => __( 'Security check failed', 'aqualuxe' ) ) );
    }

    // Get preference
    $preference = isset( $_POST['mode'] ) ? sanitize_text_field( wp_unslash( $_POST['mode'] ) ) : 'light';
    
    // Validate preference
    if ( ! in_array( $preference, array( 'light', 'dark' ), true ) ) {
        $preference = 'light';
    }
    
    // Set cookie for 30 days
    setcookie( 'aqualuxe_dark_mode', $preference, time() + ( 30 * DAY_IN_SECONDS ), COOKIEPATH, COOKIE_DOMAIN );
    
    wp_send_json_success( array( 'message' => __( 'Preference saved', 'aqualuxe' ) ) );
}

/**
 * Sanitize checkbox value
 *
 * @param bool $checked Whether the checkbox is checked.
 * @return bool
 */
function aqualuxe_sanitize_checkbox( $checked ) {
    return ( isset( $checked ) && true === $checked ) ? true : false;
}

/**
 * Sanitize select value
 *
 * @param string $input   The input from the setting.
 * @param object $setting The selected setting.
 * @return string
 */
function aqualuxe_sanitize_select( $input, $setting ) {
    // Get the list of choices from the control associated with the setting.
    $choices = $setting->manager->get_control( $setting->id )->choices;
    
    // Return input if valid or return default option.
    return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
}

/**
 * Sanitize float value
 *
 * @param float $input The input from the setting.
 * @return float
 */
function aqualuxe_sanitize_float( $input ) {
    return filter_var( $input, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION );
}