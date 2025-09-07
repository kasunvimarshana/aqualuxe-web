<?php
/**
 * PSR-4 Autoloader
 *
 * @package AquaLuxe
 * @since   1.1.0
 */

namespace AquaLuxe\Core;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Autoloader
 */
class Autoloader {

	/**
	 * The namespace prefix.
	 *
	 * @var string
	 */
	private string $prefix = 'AquaLuxe\\';

	/**
	 * The base directory for the namespace prefix.
	 *
	 * @var string
	 */
	private string $base_dir = '';

	/**
	 * Registers the autoloader.
	 */
	public function register(): void {
		spl_autoload_register( [ $this, 'load_class' ] );
	}

	/**
	 * Set the base directory.
	 *
	 * @param string $base_dir The base directory.
	 */
	public function set_base_dir( string $base_dir ): void {
		$this->base_dir = rtrim( $base_dir, '/' ) . '/';
	}

	/**
	 * Loads the class file for a given class name.
	 *
	 * @param string $class The fully-qualified class name.
	 */
	public function load_class( string $class ): void {
		// Check if the class uses the namespace prefix.
		$len = \strlen( $this->prefix );
		if ( \strncmp( $this->prefix, $class, $len ) !== 0 ) {
			// No, move to the next registered autoloader.
			return;
		}

		// Get the relative class name.
		$relative_class = \substr( $class, $len );

		// Replace namespace separators with directory separators.
		$path_parts = \explode( '\\', $relative_class );
		$class_name = \array_pop( $path_parts );

		// Convert class name from Pascal_Case to class-kebab-case.php.
		$file_name = 'class-' . \str_replace( '_', '-', \strtolower( $class_name ) ) . '.php';

		// Build the full path.
		$file_path = \implode( '/', $path_parts );
		$file      = $this->base_dir . ( ! empty( $file_path ) ? \strtolower( $file_path ) . '/' : '' ) . $file_name;

		// If the file exists, require it.
		if ( \file_exists( $file ) ) {
			require $file;
		}
	}
}
