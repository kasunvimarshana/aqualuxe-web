<?php
/**
 * Module Loader
 *
 * This file is responsible for loading all the modules in the /modules directory.
 * Each module is expected to be in its own directory and have a main file
 * with the same name as the directory (e.g., /modules/dark-mode/dark-mode.php).
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Get all module directories.
$modules = glob( AQUALUXE_THEME_DIR . 'modules/*', GLOB_ONLYDIR );

if ( $modules ) {
	foreach ( $modules as $module ) {
		$module_file = $module . '/' . basename( $module ) . '.php';
		if ( file_exists( $module_file ) ) {
			require_once $module_file;
		}
	}
}
