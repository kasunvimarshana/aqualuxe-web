<?php
/**
 * AquaLuxe Widget Importer
 *
 * @package AquaLuxe
 * @subpackage Modules
 */

namespace AquaLuxe\Modules\Demo_Importer;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Widget_Importer
 *
 * Handles importing widgets from a JSON file.
 */
class Widget_Importer {

	/**
	 * Import widgets from the given file.
	 *
	 * @param string $file Path to the JSON file.
	 * @return array Results of the import.
	 */
	public function import( $file ) {
		$data = json_decode( file_get_contents( $file ), true );
		$widget_data = $this->parse_import_data( $data );
		return $this->import_widgets( $widget_data );
	}

	/**
	 * Parse the import data.
	 *
	 * @param array $data The raw data from the file.
	 * @return array The parsed data.
	 */
	private function parse_import_data( $data ) {
		$sidebars_data = $data[0];
		$widget_data = $data[1];
		$current_sidebars = get_option( 'sidebars_widgets' );

		// Add new sidebars.
		foreach ( $sidebars_data as $sidebar_id => $widgets ) {
			if ( ! isset( $current_sidebars[ $sidebar_id ] ) ) {
				$current_sidebars[ $sidebar_id ] = array();
			}
			$current_sidebars[ $sidebar_id ] = array_merge( $current_sidebars[ $sidebar_id ], $widgets );
		}

		// Don't add duplicates.
		foreach ( $current_sidebars as $sidebar_id => $widgets ) {
			if ( is_array( $widgets ) ) {
				$current_sidebars[ $sidebar_id ] = array_unique( $widgets );
			}
		}

		update_option( 'sidebars_widgets', $current_sidebars );

		return $widget_data;
	}

	/**
	 * Import the widgets.
	 *
	 * @param array $widget_data The widget data to import.
	 * @return array The results of the import.
	 */
	private function import_widgets( $widget_data ) {
		$results = array();
		foreach ( $widget_data as $widget_id => $widget_settings ) {
			$widget_base_id = preg_replace( '/-[0-9]+$/', '', $widget_id );
			$widget_instance_id = str_replace( $widget_base_id . '-', '', $widget_id );

			$widget_options = get_option( 'widget_' . $widget_base_id, array() );
			$widget_options[ $widget_instance_id ] = $widget_settings;
			update_option( 'widget_' . $widget_base_id, $widget_options );

			$results[ $widget_id ] = 'imported';
		}
		return $results;
	}
}
