<?php
/**
 * AquaLuxe Autoloader
 *
 * PSR-4 compatible autoloader for the AquaLuxe theme.
 * Handles automatic loading of classes within the theme namespace.
 *
 * @package AquaLuxe
 * @subpackage Core
 * @since 1.0.0
 * @author AquaLuxe Development Team
 */

namespace AquaLuxe\Core;

use AquaLuxe\Core\Interfaces\Autoloader_Interface;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Autoloader Class
 *
 * Implements PSR-4 compatible autoloading for the AquaLuxe theme.
 * Provides automatic class loading based on namespace and file structure.
 *
 * @since 1.0.0
 */
class Autoloader implements Autoloader_Interface {

    /**
     * An associative array where the key is a namespace prefix and the value
     * is an array of base directories for classes in that namespace.
     *
     * @var array
     */
    protected $prefixes = [];

    /**
     * Register loader with SPL autoloader stack.
     *
     * @return bool True on success, false on failure.
     */
    public function register(): bool {
        return spl_autoload_register( array( $this, 'load_class' ) );
    }

    /**
     * Unregister loader from SPL autoloader stack.
     *
     * @return bool True on success, false on failure.
     */
    public function unregister(): bool {
        return spl_autoload_unregister( array( $this, 'load_class' ) );
    }

    /**
     * Adds a base directory for a namespace prefix.
     *
     * @param string $prefix The namespace prefix.
     * @param string $base_dir A base directory for class files in the namespace.
     * @param bool   $prepend If true, prepend the base directory to the stack instead of appending.
     * @return void
     */
    public function add_namespace( string $prefix, string $base_dir, bool $prepend = false ): void {
        // Normalize namespace prefix
        $prefix = trim( $prefix, '\\' ) . '\\';

        // Normalize the base directory with a trailing separator
        $base_dir = rtrim( $base_dir, DIRECTORY_SEPARATOR ) . '/';

        // Initialize the namespace prefix array
        if ( isset( $this->prefixes[ $prefix ] ) === false ) {
            $this->prefixes[ $prefix ] = array();
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
     * @param string $class The fully-qualified class name.
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
            // of strrpos()
            $prefix = rtrim( $prefix, '\\' );
        }

        // Never found a mapped file
        return false;
    }

    /**
     * Load the mapped file for a namespace prefix and relative class.
     *
     * @param string $prefix The namespace prefix.
     * @param string $relative_class The relative class name.
     * @return string|false Boolean false if no mapped file can be loaded, or the name of the mapped file that was loaded.
     */
    protected function load_mapped_file( string $prefix, string $relative_class ) {
        // Are there any base directories for this namespace prefix?
        if ( isset( $this->prefixes[ $prefix ] ) === false ) {
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
     * If a file exists, require it from the file system.
     *
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
     * Get all registered prefixes.
     *
     * @return array The registered prefixes.
     */
    public function get_prefixes(): array {
        return $this->prefixes;
    }

    /**
     * Convert class name to file path.
     *
     * @param string $class_name The class name.
     * @return string The file path.
     */
    public function class_to_file( string $class_name ): string {
        // Convert namespace separators to directory separators
        $file_path = str_replace( '\\', '/', $class_name );
        
        // Convert camelCase to kebab-case for file names
        $file_path = preg_replace( '/([a-z0-9])([A-Z])/', '$1-$2', $file_path );
        $file_path = strtolower( $file_path );
        
        return $file_path . '.php';
    }
}