<?php
/**
 * Autoloader Interface
 *
 * Defines the contract for autoloading classes and modules in the AquaLuxe theme.
 * This interface ensures consistent autoloading behavior across different components.
 *
 * @package AquaLuxe
 * @subpackage Core\Interfaces
 * @since 1.0.0
 * @author AquaLuxe Development Team
 */

namespace AquaLuxe\Core\Interfaces;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Autoloader Interface
 *
 * Provides a contract for implementing PSR-4 compatible autoloading
 * within the AquaLuxe theme architecture.
 *
 * @since 1.0.0
 */
interface Autoloader_Interface {

    /**
     * Register the autoloader with PHP's SPL autoloader stack.
     *
     * @since 1.0.0
     * @return bool True on success, false on failure.
     */
    public function register(): bool;

    /**
     * Unregister the autoloader from PHP's SPL autoloader stack.
     *
     * @since 1.0.0
     * @return bool True on success, false on failure.
     */
    public function unregister(): bool;

    /**
     * Add a namespace prefix and its base directory to the autoloader.
     *
     * @since 1.0.0
     * @param string $prefix The namespace prefix.
     * @param string $base_dir The base directory for class files in the namespace.
     * @param bool   $prepend If true, prepend the base directory to the stack instead of appending.
     * @return void
     */
    public function add_namespace( string $prefix, string $base_dir, bool $prepend = false ): void;

    /**
     * Load the class file for a given class name.
     *
     * @since 1.0.0
     * @param string $class_name The fully qualified class name.
     * @return string|false The mapped file name on success, or false on failure.
     */
    public function load_class( string $class_name );
}