<?php
/**
 * Module Interface
 *
 * Defines the contract for all modules in the AquaLuxe theme.
 * This interface ensures consistent module behavior across the theme.
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
 * Module Interface
 *
 * Provides a contract for implementing modules within the AquaLuxe theme.
 * All modules must implement this interface to ensure consistency.
 *
 * @since 1.0.0
 */
interface Module_Interface {

    /**
     * Initialize the module.
     *
     * @since 1.0.0
     * @return void
     */
    public function init(): void;

    /**
     * Get the module name.
     *
     * @since 1.0.0
     * @return string The module name.
     */
    public function get_name(): string;

    /**
     * Get the module description.
     *
     * @since 1.0.0
     * @return string The module description.
     */
    public function get_description(): string;

    /**
     * Get the module version.
     *
     * @since 1.0.0
     * @return string The module version.
     */
    public function get_version(): string;

    /**
     * Get the module dependencies.
     *
     * @since 1.0.0
     * @return array Array of required module names.
     */
    public function get_dependencies(): array;

    /**
     * Check if the module can be loaded.
     *
     * @since 1.0.0
     * @return bool True if the module can be loaded, false otherwise.
     */
    public function can_load(): bool;

    /**
     * Enable the module.
     *
     * @since 1.0.0
     * @return bool True on success, false on failure.
     */
    public function enable(): bool;

    /**
     * Disable the module.
     *
     * @since 1.0.0
     * @return bool True on success, false on failure.
     */
    public function disable(): bool;

    /**
     * Check if the module is enabled.
     *
     * @since 1.0.0
     * @return bool True if enabled, false otherwise.
     */
    public function is_enabled(): bool;
}