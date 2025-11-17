<?php
/**
 * AquaLuxe Events Module
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Load compatibility file if The Events Calendar is active.
if ( class_exists( 'Tribe__Events__Main' ) ) {
    require_once AQUALUXE_THEME_DIR . '/inc/modules/events/tec.php';
}
