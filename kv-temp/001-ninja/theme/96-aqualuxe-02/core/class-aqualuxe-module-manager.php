<?php
/**
 * AquaLuxe Module Manager
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * AquaLuxe_Module_Manager class.
 */
class AquaLuxe_Module_Manager {

	/**
	 * Modules.
	 *
	 * @access private
	 *
	 * @var array
	 */
	private $_modules = [];

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function __construct() {
		$this->register_modules();
	}

	/**
	 * Register modules.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function register_modules() {
		$modules = [
			'dark-mode',
			'multilingual',
			'demo-importer',
			'shortcodes',
		];

		foreach ( $modules as $module_name ) {
			$this->register_module( $module_name );
		}
	}

	/**
	 * Register module.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param string $module_name Module name.
	 */
	public function register_module( $module_name ) {
		$file_path = get_template_directory() . '/modules/' . $module_name . '/module.php';
		if ( is_readable( $file_path ) ) {
			require_once $file_path;
			$class_name = 'AquaLuxe_Module_' . str_replace( '-', '_', ucwords( $module_name, '-' ) );
			if ( class_exists( $class_name ) ) {
				$this->_modules[ $module_name ] = new $class_name();
			}
		}
	}
}
