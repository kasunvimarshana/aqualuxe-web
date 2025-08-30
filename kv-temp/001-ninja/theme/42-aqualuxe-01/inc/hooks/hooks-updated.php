<?php
/**
 * Custom hooks for the theme
 *
 * @package AquaLuxe
 */

// Include the original hooks.php content here
// ...

/**
 * Top Bar Right Hook
 * 
 * Used to add content to the right side of the top bar
 */
function aqualuxe_top_bar_right() {
    do_action( 'aqualuxe_top_bar_right' );
}

/**
 * Header Actions Hook
 * 
 * Used to add content to the header actions area
 */
function aqualuxe_header_actions() {
    do_action( 'aqualuxe_header_actions' );
}

/**
 * Footer Copyright Hook
 * 
 * Used to add content to the footer copyright area
 */
function aqualuxe_footer_copyright() {
    do_action( 'aqualuxe_footer_copyright' );
}