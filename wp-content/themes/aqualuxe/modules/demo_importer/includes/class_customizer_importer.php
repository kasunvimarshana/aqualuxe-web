<?php
/**
 * AquaLuxe Customizer Importer
 *
 * @package AquaLuxe
 * @subpackage Modules
 */

namespace AquaLuxe\Modules\Demo_Importer;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Customizer_Importer
 *
 * Handles importing customizer settings from a JSON file.
 */
class Customizer_Importer {

	/**
	 * Import customizer settings from the given file.
	 *
	 * @param string $file Path to the JSON file.
	 * @return array Results of the import.
	 */
	public function import( $file ) {
		$data = json_decode( file_get_contents( $file ), true );

		// Remove the theme_mods_aqualuxe[nav_menu_locations] as it's handled by the content importer.
		if ( isset( $data['theme_mods_aqualuxe']['nav_menu_locations'] ) ) {
			unset( $data['theme_mods_aqualuxe']['nav_menu_locations'] );
		}

		$results = array();
		foreach ( $data as $key => $val ) {
			if ( 'theme_mods_aqualuxe' === $key ) {
				foreach ( $val as $mod_key => $mod_val ) {
					set_theme_mod( $mod_key, $mod_val );
				}
			} else {
				update_option( $key, $val );
			}
			$results[ $key ] = 'imported';
		}
		return $results;
	}
}
