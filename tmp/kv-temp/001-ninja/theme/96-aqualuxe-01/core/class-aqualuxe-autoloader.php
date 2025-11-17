<?php
/**
 * AquaLuxe Autoloader
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * AquaLuxe_Autoloader class.
 */
class AquaLuxe_Autoloader {

	/**
	 * Run autoloader.
	 *
	 * Register a function as `__autoload()` implementation.
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 */
	public static function run() {
		spl_autoload_register( [ __CLASS__, 'autoload' ] );
	}

	/**
	 * Autoload.
	 *
	 * For a given class name, require the class file.
	 *
	 * @since 1.0.0
	 * @access private
	 * @static
	 *
	 * @param string $class Class name.
	 */
	private static function autoload( $class ) {
		if ( 0 !== strpos( $class, 'AquaLuxe' ) ) {
			return;
		}

		$class_name = str_replace( 'AquaLuxe_', '', $class );
		$file_name  = 'class-aqualuxe-' . str_replace( '_', '-', strtolower( $class_name ) ) . '.php';
		$file_path  = get_template_directory() . '/core/' . $file_name;

		if ( is_readable( $file_path ) ) {
			require_once $file_path;
		}
	}
}
