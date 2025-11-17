<?php
/**
 * Module: Demo Importer
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * AquaLuxe_Module_Demo_Importer class.
 */
class AquaLuxe_Module_Demo_Importer {

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function __construct() {
		require_once dirname( __FILE__ ) . '/class-aqualuxe-demo-importer.php';
		new AquaLuxe_Demo_Importer();
	}
}
