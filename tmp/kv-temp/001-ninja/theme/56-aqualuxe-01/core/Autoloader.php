<?php
/**
 * Autoloader
 *
 * @package AquaLuxe
 */

namespace AquaLuxe\Core;

/**
 * Class responsible for autoloading classes
 */
class Autoloader {
    /**
     * Namespace prefixes
     *
     * @var array
     */
    private $prefixes = [];

    /**
     * Register the autoloader
     *
     * @return void
     */
    public function register() {
        spl_autoload_register( [ $this, 'autoload' ] );
    }

    /**
     * Add a namespace prefix
     *
     * @param string $prefix The namespace prefix.
     * @param string $base_dir The base directory for class files.
     * @return void
     */
    public function add_namespace( $prefix, $base_dir ) {
        // Normalize the prefix
        $prefix = trim( $prefix, '\\' ) . '\\';

        // Normalize the base directory with a trailing separator
        $base_dir = rtrim( $base_dir, DIRECTORY_SEPARATOR ) . '/';

        // Initialize the namespace prefix array
        if ( isset( $this->prefixes[ $prefix ] ) === false ) {
            $this->prefixes[ $prefix ] = [];
        }

        // Add the base directory for the namespace prefix
        array_push( $this->prefixes[ $prefix ], $base_dir );
    }

    /**
     * Autoload a class
     *
     * @param string $class The fully-qualified class name.
     * @return string|false The mapped file name on success, false on failure.
     */
    public function autoload( $class ) {
        // The current namespace prefix
        $prefix = $class;

        // Work backwards through the namespace names of the fully-qualified
        // class name to find a mapped file name
        while ( false !== $pos = strrpos( $prefix, '\\' ) ) {
            // Retain the trailing namespace separator in the prefix
            $prefix = substr( $class, 0, $pos + 1 );

            // The rest is the relative class name
            $relative_class = substr( $class, $pos + 1 );

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
     * Load the mapped file for a namespace prefix and relative class
     *
     * @param string $prefix The namespace prefix.
     * @param string $relative_class The relative class name.
     * @return string|false The mapped file name on success, false on failure.
     */
    private function load_mapped_file( $prefix, $relative_class ) {
        // Are there any base directories for this namespace prefix?
        if ( isset( $this->prefixes[ $prefix ] ) === false ) {
            return false;
        }

        // Look through base directories for this namespace prefix
        foreach ( $this->prefixes[ $prefix ] as $base_dir ) {
            // Replace the namespace prefix with the base directory,
            // replace namespace separators with directory separators
            // in the relative class name, append with .php
            $file = $base_dir . str_replace( '\\', '/', $relative_class ) . '.php';

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
     * Require a file if it exists
     *
     * @param string $file The file to require.
     * @return bool True if the file exists, false if not.
     */
    private function require_file( $file ) {
        if ( file_exists( $file ) ) {
            require $file;
            return true;
        }
        return false;
    }
}