<?php
/**
 * Events Module
 *
 * Provides integration with an events plugin (e.g., The Events Calendar).
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Check if The Events Calendar is active.
 *
 * @return bool
 */
function aqualuxe_is_events_calendar_active() {
    return class_exists( 'Tribe__Events__Main' );
}

/**
 * Custom functions and hooks for The Events Calendar.
 */
if ( aqualuxe_is_events_calendar_active() ) {

    /**
     * Enqueue custom styles for the events module.
     */
    function aqualuxe_events_styles() {
        // Example: wp_enqueue_style( 'aqualuxe-events', AQUALUXE_THEME_URI . 'modules/events/assets/css/events.css' );
    }
    add_action( 'wp_enqueue_scripts', 'aqualuxe_events_styles' );

    // You can add more customizations here, such as modifying the event templates.
}
