<?php
/**
 * Core theme functions
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Theme setup.
 */
function aqualuxe_core_setup() {
    // Autoload modules.
    aqualuxe_autoload_modules();
}
add_action( 'after_setup_theme', 'aqualuxe_core_setup' );

/**
 * Autoload modules.
 */
function aqualuxe_autoload_modules() {
    $modules_dir = get_template_directory() . '/modules';
    if ( is_dir( $modules_dir ) ) {
        $modules = array_filter( glob( $modules_dir . '/*' ), 'is_dir' );
        foreach ( $modules as $module ) {
            $module_file = $module . '/' . basename( $module ) . '.php';
            if ( file_exists( $module_file ) ) {
                require_once $module_file;
            }
        }
    }
}

/**
 * Include CPT and Taxonomy files
 */
require get_template_directory() . '/inc/cpt.php';
require get_template_directory() . '/inc/taxonomies.php';
