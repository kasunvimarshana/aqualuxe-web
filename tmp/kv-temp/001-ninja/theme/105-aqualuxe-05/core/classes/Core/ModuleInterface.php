<?php
/**
 * Module Interface
 *
 * Standard interface for all AquaLuxe modules to ensure consistency
 *
 * @package AquaLuxe\Core
 * @since 1.0.0
 */

namespace AquaLuxe\Core;

/**
 * Interface ModuleInterface
 *
 * Defines the contract that all modules must implement
 */
interface ModuleInterface {
    
    /**
     * Get the module name/identifier
     *
     * @return string
     */
    public function get_name();

    /**
     * Get the module version
     *
     * @return string
     */
    public function get_version();

    /**
     * Get module configuration
     *
     * @return array
     */
    public function get_config();

    /**
     * Check if the module is enabled
     *
     * @return bool
     */
    public function is_enabled();

    /**
     * Initialize the module
     *
     * @return void
     */
    public function init();

    /**
     * Get module dependencies
     *
     * @return array Array of module names this module depends on
     */
    public function get_dependencies();

    /**
     * Check if module can be loaded (dependencies satisfied)
     *
     * @return bool
     */
    public function can_load();

    /**
     * Activate the module
     *
     * @return bool Success/failure
     */
    public function activate();

    /**
     * Deactivate the module
     *
     * @return bool Success/failure
     */
    public function deactivate();

    /**
     * Get module assets (CSS/JS files)
     *
     * @return array
     */
    public function get_assets();

    /**
     * Get module admin settings
     *
     * @return array
     */
    public function get_admin_settings();
}