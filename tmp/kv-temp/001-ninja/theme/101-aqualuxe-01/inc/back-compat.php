<?php
/**
 * Back compatibility functionality
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Prevent switching to AquaLuxe on old versions of WordPress.
 */
function aqualuxe_switch_theme() {
    switch_theme( WP_DEFAULT_THEME );
    unset( $_GET['activated'] );
    add_action( 'admin_notices', 'aqualuxe_upgrade_notice' );
}
add_action( 'after_switch_theme', 'aqualuxe_switch_theme' );

/**
 * Adds a message for unsuccessful theme switch.
 */
function aqualuxe_upgrade_notice() {
    $message = sprintf( __( 'AquaLuxe requires at least WordPress version 5.0. You are running version %s. Please upgrade and try again.', 'aqualuxe' ), $GLOBALS['wp_version'] );
    printf( '<div class="error"><p>%s</p></div>', $message );
}

/**
 * Prevents the Customizer from being loaded on WordPress versions prior to 5.0.
 */
function aqualuxe_customize() {
    wp_die( sprintf( __( 'AquaLuxe requires at least WordPress version 5.0. You are running version %s. Please upgrade and try again.', 'aqualuxe' ), $GLOBALS['wp_version'] ), '', array(
        'back_link' => true,
    ) );
}
add_action( 'load-customize.php', 'aqualuxe_customize' );

/**
 * Prevents the Theme Preview from being loaded on WordPress versions prior to 5.0.
 */
function aqualuxe_preview() {
    if ( isset( $_GET['preview'] ) ) {
        wp_die( sprintf( __( 'AquaLuxe requires at least WordPress version 5.0. You are running version %s. Please upgrade and try again.', 'aqualuxe' ), $GLOBALS['wp_version'] ) );
    }
}
add_action( 'template_redirect', 'aqualuxe_preview' );