<?php
/**
 * Custom Walker for Mega Menu (Admin/Backend)
 *
 * This adds the description of the mega menu fields to the admin menu editor.
 *
 * @package AquaLuxe
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class AquaLuxe_Mega_Menu_Edit_Walker extends Walker_Nav_Menu_Edit {
	/**
	 * Starts the element output.
	 */
	public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		$item_output = '';
		parent::start_el( $item_output, $item, $depth, $args, $id );

		// Inject the custom fields
		$position = '<p class="field-move';
		$output .= str_replace( $position, $this->get_custom_fields( $item, $depth, $args ) . $position, $item_output );
	}

	/**
	 * Get the custom fields HTML.
	 *
	 * @param object $item  Menu item data object.
	 * @param int    $depth Depth of menu item.
	 * @param array  $args  An array of arguments.
	 * @return string
	 */
	protected function get_custom_fields( $item, $depth, $args = array() ) {
		ob_start();
		$item_id = intval( $item->ID );

		// This is where we will render our custom fields
		do_action( 'wp_nav_menu_item_custom_fields', $item_id, $item, $depth, $args );

		return ob_get_clean();
	}
}
