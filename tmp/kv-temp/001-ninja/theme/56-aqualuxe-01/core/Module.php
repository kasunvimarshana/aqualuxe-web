<?php
/**
 * Module base class
 *
 * @package AquaLuxe
 */

namespace AquaLuxe\Core;

/**
 * Abstract Module class that all modules must extend
 */
abstract class Module {
    /**
     * Module ID
     *
     * @var string
     */
    protected $id;

    /**
     * Module name
     *
     * @var string
     */
    protected $name;

    /**
     * Module description
     *
     * @var string
     */
    protected $description;

    /**
     * Module version
     *
     * @var string
     */
    protected $version = '1.0.0';

    /**
     * Module dependencies
     *
     * @var array
     */
    protected $dependencies = [];

    /**
     * Module settings
     *
     * @var array
     */
    protected $settings = [];

    /**
     * Module constructor
     */
    public function __construct() {
        $this->setup();
        $this->register_hooks();
    }

    /**
     * Setup module properties
     *
     * @return void
     */
    abstract protected function setup();

    /**
     * Register module hooks
     *
     * @return void
     */
    abstract protected function register_hooks();

    /**
     * Initialize the module
     *
     * @return void
     */
    abstract public function init();

    /**
     * Render the module
     *
     * @param array $args Module arguments.
     * @return void
     */
    abstract public function render( $args = [] );

    /**
     * Get module ID
     *
     * @return string
     */
    public function get_id() {
        return $this->id;
    }

    /**
     * Get module name
     *
     * @return string
     */
    public function get_name() {
        return $this->name;
    }

    /**
     * Get module description
     *
     * @return string
     */
    public function get_description() {
        return $this->description;
    }

    /**
     * Get module version
     *
     * @return string
     */
    public function get_version() {
        return $this->version;
    }

    /**
     * Get module dependencies
     *
     * @return array
     */
    public function get_dependencies() {
        return $this->dependencies;
    }

    /**
     * Get module settings
     *
     * @return array
     */
    public function get_settings() {
        return $this->settings;
    }

    /**
     * Check if module has a specific dependency
     *
     * @param string $dependency Dependency ID.
     * @return boolean
     */
    public function has_dependency( $dependency ) {
        return in_array( $dependency, $this->dependencies, true );
    }

    /**
     * Check if module is active
     *
     * @return boolean
     */
    public function is_active() {
        // Check if all dependencies are met
        foreach ( $this->dependencies as $dependency ) {
            if ( ! aqualuxe_module_exists( $dependency ) || ! aqualuxe_module_is_active( $dependency ) ) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get module template part
     *
     * @param string $template Template name.
     * @param array  $args     Template arguments.
     * @return void
     */
    protected function get_template_part( $template, $args = [] ) {
        $template_path = 'modules/' . $this->id . '/' . $template;
        aqualuxe_get_template_part( $template_path, $args );
    }

    /**
     * Enqueue module styles
     *
     * @return void
     */
    protected function enqueue_styles() {
        $module_style = 'assets/dist/css/modules/' . $this->id . '.css';
        
        if ( file_exists( get_template_directory() . '/' . $module_style ) ) {
            wp_enqueue_style(
                'aqualuxe-module-' . $this->id,
                get_template_directory_uri() . '/' . $module_style,
                [],
                $this->version
            );
        }
    }

    /**
     * Enqueue module scripts
     *
     * @return void
     */
    protected function enqueue_scripts() {
        $module_script = 'assets/dist/js/modules/' . $this->id . '.js';
        
        if ( file_exists( get_template_directory() . '/' . $module_script ) ) {
            wp_enqueue_script(
                'aqualuxe-module-' . $this->id,
                get_template_directory_uri() . '/' . $module_script,
                ['aqualuxe-app'],
                $this->version,
                true
            );
        }
    }

    /**
     * Register module customizer settings
     *
     * @param \WP_Customize_Manager $wp_customize Customizer instance.
     * @return void
     */
    public function register_customizer_settings( $wp_customize ) {
        // To be implemented by child classes if needed
    }
}