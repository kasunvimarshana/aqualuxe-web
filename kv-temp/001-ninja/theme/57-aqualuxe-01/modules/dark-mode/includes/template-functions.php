<?php
/**
 * Dark Mode Template Functions
 *
 * @package AquaLuxe
 * @subpackage Modules/DarkMode
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Add dark mode class to body
 *
 * @param array $classes Body classes.
 * @return array
 */
function aqualuxe_dark_mode_body_class( $classes ) {
    if ( aqualuxe_is_dark_mode_active() ) {
        $classes[] = 'dark-mode';
    }
    
    return $classes;
}
add_filter( 'body_class', 'aqualuxe_dark_mode_body_class' );

/**
 * Add dark mode toggle to header
 *
 * @return void
 */
function aqualuxe_dark_mode_header_toggle() {
    $dark_mode_module = aqualuxe_get_module( 'dark-mode' );
    
    if ( $dark_mode_module && isset( $dark_mode_module->settings['toggle_position'] ) && 'header' === $dark_mode_module->settings['toggle_position'] ) {
        aqualuxe_dark_mode_toggle();
    }
}
add_action( 'aqualuxe_header_after_navigation', 'aqualuxe_dark_mode_header_toggle' );

/**
 * Add dark mode toggle to footer
 *
 * @return void
 */
function aqualuxe_dark_mode_footer_toggle() {
    $dark_mode_module = aqualuxe_get_module( 'dark-mode' );
    
    if ( $dark_mode_module && isset( $dark_mode_module->settings['toggle_position'] ) && 'footer' === $dark_mode_module->settings['toggle_position'] ) {
        aqualuxe_dark_mode_toggle();
    }
}
add_action( 'aqualuxe_footer_before_widgets', 'aqualuxe_dark_mode_footer_toggle' );

/**
 * Add dark mode CSS variables
 *
 * @return void
 */
function aqualuxe_dark_mode_css_variables() {
    $dark_mode_module = aqualuxe_get_module( 'dark-mode' );
    
    if ( ! $dark_mode_module || ! isset( $dark_mode_module->settings['custom_colors'] ) || ! $dark_mode_module->settings['custom_colors'] ) {
        return;
    }
    
    $css = '
        :root {
            --dark-mode-bg: ' . esc_attr( $dark_mode_module->settings['dark_background'] ) . ';
            --dark-mode-text: ' . esc_attr( $dark_mode_module->settings['dark_text'] ) . ';
            --dark-mode-transition: ' . esc_attr( $dark_mode_module->settings['transition_duration'] ) . 's;
        }
    ';
    
    echo '<style id="aqualuxe-dark-mode-custom-css">' . wp_strip_all_tags( $css ) . '</style>';
}
add_action( 'wp_head', 'aqualuxe_dark_mode_css_variables' );

/**
 * Add dark mode meta tag for mobile browsers
 *
 * @return void
 */
function aqualuxe_dark_mode_meta_tag() {
    $dark_mode_module = aqualuxe_get_module( 'dark-mode' );
    
    if ( ! $dark_mode_module ) {
        return;
    }
    
    $color_scheme = aqualuxe_is_dark_mode_active() ? 'dark' : 'light';
    
    echo '<meta name="color-scheme" content="' . esc_attr( $color_scheme ) . '">';
    echo '<meta name="theme-color" content="' . esc_attr( aqualuxe_is_dark_mode_active() ? $dark_mode_module->settings['dark_background'] : '#ffffff' ) . '">';
}
add_action( 'wp_head', 'aqualuxe_dark_mode_meta_tag' );