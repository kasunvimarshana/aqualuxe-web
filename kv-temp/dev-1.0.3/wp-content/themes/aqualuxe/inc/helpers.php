<?php
/**
 * AquaLuxe Helper Functions
 *
 * @package AquaLuxe
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function aqualuxe_get_primary_color() {
    return get_theme_mod( 'aqualuxe_primary_color', '#0a3d62' );
}
