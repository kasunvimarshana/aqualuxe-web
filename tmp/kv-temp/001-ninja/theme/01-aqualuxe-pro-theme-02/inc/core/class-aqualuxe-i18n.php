<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this theme
 * so that it is ready for translation.
 *
 * @link       https://aqualuxe.pro
 * @since      1.0.0
 *
 * @package    AquaLuxe
 * @subpackage AquaLuxe/inc/core
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this theme
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    AquaLuxe
 * @subpackage AquaLuxe/inc/core
 * @author     Your Name <email@example.com>
 */
class AquaLuxe_i18n {


	/**
	 * Load the theme text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_theme_textdomain() {

		load_theme_textdomain(
			'aqualuxe',
			AQUALUXE_THEME_DIR . '/languages'
		);

	}



}
