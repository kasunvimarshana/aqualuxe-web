<?php
/**
 * WPML Integration for AquaLuxe.
 *
 * @package AquaLuxe
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * The main WPML integration class.
 */
class AquaLuxe_WPML {

	/**
	 * Initialize the WPML integration.
	 */
	public static function init() {
		if ( ! function_exists( 'icl_object_id' ) ) {
			return;
		}

		add_action( 'aqualuxe_header_top', array( __CLASS__, 'add_language_switcher' ) );
	}

	/**
	 * Add a language switcher to the header.
	 */
	public static function add_language_switcher() {
		echo '<div class="aqualuxe-language-switcher">';
		do_action( 'wpml_add_language_selector' );
		echo '</div>';
	}
}
