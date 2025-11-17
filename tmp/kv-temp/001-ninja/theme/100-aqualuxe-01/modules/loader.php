<?php
/**
 * Module Loader
 *
 * This file is responsible for loading all the theme modules.
 *
 * @package AquaLuxe
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Define the path to the modules directory.
define( 'AQUALUXE_MODULES_DIR', AQUALUXE_THEME_DIR . '/modules' );

// Scan the modules directory and include the main file for each module.
$modules = glob( AQUALUXE_MODULES_DIR . '/*' );

if ( $modules ) {
	foreach ( $modules as $module ) {
		if ( is_dir( $module ) ) {
			$module_file = $module . '/module.php';
			if ( file_exists( $module_file ) ) {
				require_once $module_file;
			}
		}
	}
}
