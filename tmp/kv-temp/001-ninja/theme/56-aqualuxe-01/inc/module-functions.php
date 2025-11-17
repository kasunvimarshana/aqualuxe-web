<?php
/**
 * Module Functions
 *
 * Functions for working with modules
 *
 * @package AquaLuxe
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Get the module loader instance
 *
 * @return \AquaLuxe\Core\ModuleLoader
 */
function aqualuxe_module_loader() {
    return \AquaLuxe\Core\ModuleLoader::get_instance();
}

/**
 * Check if a module exists
 *
 * @param string $module_id Module ID.
 * @return boolean
 */
function aqualuxe_module_exists( $module_id ) {
    return aqualuxe_module_loader()->module_exists( $module_id );
}

/**
 * Check if a module is active
 *
 * @param string $module_id Module ID.
 * @return boolean
 */
function aqualuxe_module_is_active( $module_id ) {
    return aqualuxe_module_loader()->is_module_active( $module_id );
}

/**
 * Get a module instance
 *
 * @param string $module_id Module ID.
 * @return \AquaLuxe\Core\ModuleInterface|null
 */
function aqualuxe_get_module( $module_id ) {
    return aqualuxe_module_loader()->get_module( $module_id );
}

/**
 * Render a module
 *
 * @param string $module_id Module ID.
 * @param array  $args      Module arguments.
 * @return void
 */
function aqualuxe_render_module( $module_id, $args = [] ) {
    aqualuxe_module_loader()->render_module( $module_id, $args );
}

/**
 * Get all registered modules
 *
 * @return array
 */
function aqualuxe_get_modules() {
    return aqualuxe_module_loader()->get_modules();
}

/**
 * Get all active modules
 *
 * @return array
 */
function aqualuxe_get_active_modules() {
    return aqualuxe_module_loader()->get_active_modules();
}

/**
 * Register a module
 *
 * @param string $module_id    Module ID.
 * @param string $module_class Module class name.
 * @return void
 */
function aqualuxe_register_module( $module_id, $module_class ) {
    aqualuxe_module_loader()->register_module( $module_id, $module_class );
}