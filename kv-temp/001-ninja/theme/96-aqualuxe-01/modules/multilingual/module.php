<?php
/**
 * Module: Multilingual
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * AquaLuxe_Module_Multilingual class.
 */
class AquaLuxe_Module_Multilingual {

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function __construct() {
		add_action( 'init', [ $this, 'load_textdomain' ] );
	}

	/**
	 * Load textdomain.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function load_textdomain() {
		load_theme_textdomain( 'aqualuxe', get_template_directory() . '/languages' );
	}
}
