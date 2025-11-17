<?php
/**
 * Module Interface
 *
 * @package AquaLuxe
 */

namespace AquaLuxe\Core;

/**
 * Interface that all modules must implement
 */
interface ModuleInterface {
    /**
     * Initialize the module
     *
     * @return void
     */
    public function init();

    /**
     * Render the module
     *
     * @param array $args Module arguments.
     * @return void
     */
    public function render( $args = [] );

    /**
     * Get module ID
     *
     * @return string
     */
    public function get_id();

    /**
     * Get module name
     *
     * @return string
     */
    public function get_name();

    /**
     * Get module description
     *
     * @return string
     */
    public function get_description();

    /**
     * Get module version
     *
     * @return string
     */
    public function get_version();

    /**
     * Get module dependencies
     *
     * @return array
     */
    public function get_dependencies();

    /**
     * Check if module has a specific dependency
     *
     * @param string $dependency Dependency ID.
     * @return boolean
     */
    public function has_dependency( $dependency );

    /**
     * Check if module is active
     *
     * @return boolean
     */
    public function is_active();

    /**
     * Register module customizer settings
     *
     * @param \WP_Customize_Manager $wp_customize Customizer instance.
     * @return void
     */
    public function register_customizer_settings( $wp_customize );
}