<?php
/**
 * CPT Registration
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register Custom Post Types.
 */
function aqualuxe_register_cpts() {
    $cpt_files = glob( get_template_directory() . '/inc/cpt/*.php' );
    foreach ( $cpt_files as $cpt_file ) {
        require_once $cpt_file;
    }
}
add_action( 'init', 'aqualuxe_register_cpts' );
