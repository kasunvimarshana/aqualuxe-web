<?php
/**
 * PSR-4 Autoloader Implementation
 *
 * Implements PSR-4 autoloading for the AquaLuxe theme architecture.
 * This class provides automatic class loading based on namespace conventions.
 *
 * @package AquaLuxe
 * @subpackage Core
 * @since 2.0.0
 * @author Kasun Vimarshana <kasunv.com@gmail.com>
 */

namespace AquaLuxe\Core;

use AquaLuxe\Core\Interfaces\Autoloader_Interface;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * PSR-4 Autoloader Class
 *
 * Provides PSR-4 compatible autoloading functionality for the theme.
 * This implementation allows for efficient, standards-compliant class loading.
 *
 * @since 2.0.0
 * @implements Autoloader_Interface
 */
class Autoloader implements Autoloader_Interface {

	/**
	 * An associative array where the key is a namespace prefix and the value
	 * is an array of base directories for classes in that namespace.
	 *
	 * @since 2.0.0
	 * @var array<string, array<string>>
	 */
	protected array $prefixes = [];

	/**
	 * Register loader with SPL autoloader stack.
	 *
	 * @since 2.0.0
	 * @return bool True on success, false on failure.
	 */
	public function register(): bool {
		return spl_autoload_register( [ $this, 'load_class' ] );
	}

	/**
	 * Unregister loader from SPL autoloader stack.
	 *
	 * @since 2.0.0
	 * @return bool True on success, false on failure.
	 */
	public function unregister(): bool {
		return spl_autoload_unregister( [ $this, 'load_class' ] );
	}

	/**
	 * Adds a base directory for a namespace prefix.
	 *
	 * @since 2.0.0
	 * @param string $prefix The namespace prefix.
	 * @param string $base_dir A base directory for class files in the namespace.
	 * @param bool   $prepend If true, prepend the base directory to the stack.
	 * @return void
	 */
	public function add_namespace( string $prefix, string $base_dir, bool $prepend = false ): void {
		// Normalize namespace prefix
		$prefix = trim( $prefix, '\\' ) . '\\';

		// Normalize the base directory with a trailing separator
		$base_dir = rtrim( $base_dir, DIRECTORY_SEPARATOR ) . '/';

		// Initialize the namespace prefix array
		if ( ! isset( $this->prefixes[ $prefix ] ) ) {
			$this->prefixes[ $prefix ] = [];
		}

		// Retain the base directory for the namespace prefix
		if ( $prepend ) {
			array_unshift( $this->prefixes[ $prefix ], $base_dir );
		} else {
			array_push( $this->prefixes[ $prefix ], $base_dir );
		}
	}

	/**
	 * Loads the class file for a given class name.
	 *
	 * @since 2.0.0
	 * @param string $class_name The fully-qualified class name.
	 * @return string|false The mapped file name on success, or false on failure.
	 */
	public function load_class( string $class_name ) {
		// The current namespace prefix
		$prefix = $class_name;

		// Work backwards through the namespace names of the fully-qualified
		// class name to find a mapped file name
		while ( false !== $pos = strrpos( $prefix, '\\' ) ) {

			// Retain the trailing namespace separator in the prefix
			$prefix = substr( $class_name, 0, $pos + 1 );

			// The rest is the relative class name
			$relative_class = substr( $class_name, $pos + 1 );

			// Try to load a mapped file for the prefix and relative class
			$mapped_file = $this->load_mapped_file( $prefix, $relative_class );
			if ( $mapped_file ) {
				return $mapped_file;
			}

			// Remove the trailing namespace separator for the next iteration
			$prefix = rtrim( $prefix, '\\' );
		}

		// Never found a mapped file
		return false;
	}

	/**
	 * Load the mapped file for a namespace prefix and relative class.
	 *
	 * @since 2.0.0
	 * @param string $prefix The namespace prefix.
	 * @param string $relative_class The relative class name.
	 * @return string|false Boolean false if no mapped file can be loaded, or the name of the mapped file that was loaded.
	 */
	protected function load_mapped_file( string $prefix, string $relative_class ) {
		// Are there any base directories for this namespace prefix?
		if ( ! isset( $this->prefixes[ $prefix ] ) ) {
			return false;
		}

		// Look through base directories for this namespace prefix
		foreach ( $this->prefixes[ $prefix ] as $base_dir ) {

			// Replace the namespace prefix with the base directory,
			// replace namespace separators with directory separators
			// in the relative class name, append with .php
			$file = $base_dir
				. str_replace( '\\', '/', $relative_class )
				. '.php';

			// Convert class name to file name (WordPress convention: Class_Name -> class-name.php)
			$file = $this->convert_class_name_to_file_name( $file );

			// If the mapped file exists, require it
			if ( $this->require_file( $file ) ) {
				// Yes, we're done
				return $file;
			}
		}

		// Never found it
		return false;
	}

	/**
	 * Convert class name to WordPress file naming convention.
	 *
	 * @since 2.0.0
	 * @param string $file_path The file path with class name.
	 * @return string The converted file path.
	 */
	protected function convert_class_name_to_file_name( string $file_path ): string {
		// Extract the filename from the path
		$path_parts = pathinfo( $file_path );
		$dir = $path_parts['dirname'];
		$filename = $path_parts['filename'];

		// Convert PascalCase to kebab-case (WordPress convention)
		$converted_filename = strtolower( preg_replace( '/([a-z])([A-Z])/', '$1-$2', $filename ) );

		// Special handling for Interface suffix
		$converted_filename = str_replace( '-interface', '-interface', $converted_filename );

		return $dir . '/' . $converted_filename . '.php';
	}

	/**
	 * If a file exists, require it from the file system.
	 *
	 * @since 2.0.0
	 * @param string $file The file to require.
	 * @return bool True if the file exists, false if not.
	 */
	protected function require_file( string $file ): bool {
		if ( file_exists( $file ) ) {
			require $file;
			return true;
		}
		return false;
	}

	/**
	 * Get all registered namespace prefixes.
	 *
	 * @since 2.0.0
	 * @return array<string, array<string>> The registered prefixes.
	 */
	public function get_prefixes(): array {
		return $this->prefixes;
	}

	/**
	 * Clear all registered namespace prefixes.
	 *
	 * @since 2.0.0
	 * @return void
	 */
	public function clear_prefixes(): void {
		$this->prefixes = [];
	}
}
