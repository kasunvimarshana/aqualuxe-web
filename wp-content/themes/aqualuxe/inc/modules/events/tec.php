<?php
/**
 * The Events Calendar compatibility.
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Add custom wrapper for events
add_action( 'tribe_events_before_the_event_title', 'aqualuxe_events_wrapper_start', 5 );
add_action( 'tribe_events_after_the_event_title', 'aqualuxe_events_wrapper_end', 15 );

function aqualuxe_events_wrapper_start() {
    echo '<div class="aqualuxe-events-wrapper">';
}

function aqualuxe_events_wrapper_end() {
    echo '</div>';
}
