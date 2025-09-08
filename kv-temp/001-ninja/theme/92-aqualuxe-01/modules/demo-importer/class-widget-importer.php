<?php
/**
 * Class for handling widget import.
 *
 * @package AquaLuxe
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class AquaLuxe_Widget_Importer {

	/**
	 * Parent importer instance.
	 * @var object
	 */
	private $parent;

	/**
	 * Constructor
	 * @param object $parent The parent importer instance.
	 */
	public function __construct( $parent ) {
		$this->parent = $parent;
	}

	/**
	 * Import widgets from a file.
	 * @param string $file_path Path to the .wie file.
	 */
	public function import_widgets( $file_path ) {
		$data = $this->get_data_from_file( $file_path );
		if ( is_wp_error( $data ) ) {
			$this->parent->public_log( 'Widget Import Error: ' . $data->get_error_message() );
			return;
		}

		$this->parent->public_log( '--- Widget data successfully read from file. ---' );
		$this->import_data( $data );
	}

	/**
	 * Get widget data from file.
	 * @param string $file_path Path to the .wie file.
	 * @return array|WP_Error
	 */
	private function get_data_from_file( $file_path ) {
		if ( ! file_exists( $file_path ) ) {
			return new WP_Error( 'widget_import_file_not_found', 'Widget import file not found.' );
		}
		$raw_data = file_get_contents( $file_path );
		return json_decode( $raw_data, true );
	}

	/**
	 * Import the widget data.
	 * @param array $data The widget data.
	 */
	private function import_data( $data ) {
		global $wp_registered_sidebars;

		if ( empty( $data ) || ! is_array( $data ) ) {
			$this->parent->public_log( 'ERROR: Widget import data could not be read. The file may be empty or corrupt.' );
			return;
		}

		$available_widgets = $this->get_available_widgets();
		$widget_instances = array();

		foreach ( $available_widgets as $widget_data ) {
			$widget_instances[ $widget_data['id_base'] ] = get_option( 'widget_' . $widget_data['id_base'] );
		}

		foreach ( $data as $sidebar_id => $widgets ) {
			if ( 'wp_inactive_widgets' === $sidebar_id ) {
				continue;
			}

			if ( isset( $wp_registered_sidebars[ $sidebar_id ] ) ) {
				$this->parent->public_log( "Importing widgets for sidebar: $sidebar_id" );
				$new_widgets = array();

				foreach ( $widgets as $widget_instance_id => $widget ) {
					$widget_type = trim( substr( $widget_instance_id, 0, strrpos( $widget_instance_id, '-' ) ) );
					$widget_id = (int) substr( strrchr( $widget_instance_id, '-' ), 1 );

					if ( ! $widget_type || ! $widget_id ) {
						$this->parent->public_log( "Skipping invalid widget ID: $widget_instance_id" );
						continue;
					}

					if ( isset( $available_widgets[ $widget_type ] ) ) {
						$widget_instances[ $widget_type ][ $widget_id ] = $widget;
						$new_widgets[] = $widget_type . '-' . $widget_id;
					} else {
						$this->parent->public_log( "Skipping widget of unknown type: $widget_type" );
					}
				}
				update_option( 'sidebars_widgets', array_merge( (array) get_option( 'sidebars_widgets' ), array( $sidebar_id => $new_widgets ) ) );
				$this->parent->public_log( "Sidebar '$sidebar_id' updated with " . count( $new_widgets ) . " widgets." );
			} else {
				$this->parent->public_log( "Skipping widgets for unregistered sidebar: $sidebar_id" );
			}
		}

		foreach ( $widget_instances as $widget_type => $instances ) {
			update_option( 'widget_' . $widget_type, $instances );
			$this->parent->public_log( "Updated options for widget type: $widget_type" );
		}
	}

	/**
	 * Get all available widgets.
	 * @return array
	 */
	private function get_available_widgets() {
		global $wp_registered_widget_controls;
		$widget_controls = $wp_registered_widget_controls;
		$available_widgets = array();

		foreach ( $widget_controls as $widget ) {
			if ( ! empty( $widget['id_base'] ) && ! isset( $available_widgets[ $widget['id_base'] ] ) ) {
				$available_widgets[ $widget['id_base'] ]['id_base'] = $widget['id_base'];
				$available_widgets[ $widget['id_base'] ]['name'] = $widget['name'];
			}
		}
		return $available_widgets;
	}
}
