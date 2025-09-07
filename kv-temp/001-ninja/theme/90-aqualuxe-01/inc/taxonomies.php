<?php
/**
 * Taxonomy Registration
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register Custom Taxonomies.
 */
function aqualuxe_register_taxonomies() {
    $tax_files = glob( get_template_directory() . '/inc/taxonomies/*.php' );
    foreach ( $tax_files as $tax_file ) {
        require_once $tax_file;
    }
}
add_action( 'init', 'aqualuxe_register_taxonomies' );
