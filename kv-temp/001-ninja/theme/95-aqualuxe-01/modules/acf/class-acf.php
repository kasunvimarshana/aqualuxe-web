<?php
/**
 * ACF Integration for AquaLuxe.
 *
 * @package AquaLuxe
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * The main ACF integration class.
 */
class AquaLuxe_ACF {

	/**
	 * Initialize the ACF integration.
	 */
	public static function init() {
		// Add hooks for ACF integration.
		add_filter( 'acf/settings/save_json', array( __CLASS__, 'set_acf_json_save_point' ) );
		add_filter( 'acf/settings/load_json', array( __CLASS__, 'add_acf_json_load_point' ) );
	}

	/**
	 * Set the save point for ACF JSON files.
	 *
	 * @param string $path The default path.
	 * @return string The modified path.
	 */
	public static function set_acf_json_save_point( $path ) {
		// Update path
		$path = get_stylesheet_directory() . '/modules/acf/acf-json';

		// Return path
		return $path;
	}

	/**
	 * Add a load point for ACF JSON files.
	 *
	 * @param array $paths The default paths.
	 * @return array The modified paths.
	 */
	public static function add_acf_json_load_point( $paths ) {
		// Remove original path (optional)
		unset( $paths[0] );

		// Append our new path
		$paths[] = get_stylesheet_directory() . '/modules/acf/acf-json';

		return $paths;
	}
}
