<?php
/**
 * Multivendor compatibility.
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Load Dokan compatibility file if the plugin is active.
 */
if ( class_exists( 'Dokan' ) ) {
    require_once AQUALUXE_THEME_DIR . '/inc/modules/multivendor/dokan.php';
}
