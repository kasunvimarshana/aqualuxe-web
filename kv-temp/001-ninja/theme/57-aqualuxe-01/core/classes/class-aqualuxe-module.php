<?php
/**
 * Base Module Class
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Abstract class for all AquaLuxe modules
 */
abstract class AquaLuxe_Module {
    /**
     * Module ID
     *
     * @var string
     */
    protected $module_id = '';

    /**
     * Module name
     *
     * @var string
     */
    protected $module_name = '';

    /**
     * Module description
     *
     * @var string
     */
    protected $module_description = '';

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
    protected $dependencies = array();

    /**
     * Module settings
     *
     * @var array
     */
    protected $settings = array();

    /**
     * Is module active
     *
     * @var bool
     */
    protected $active = false;

    /**
     * Constructor
     */
    public function __construct() {
        $this->init();
    }

    /**
     * Initialize the module
     *
     * @return void
     */
    abstract public function init();

    /**
     * Setup module hooks
     *
     * @return void
     */
    abstract public function setup_hooks();

    /**
     * Get module ID
     *
     * @return string
     */
    public function get_id() {
        return $this->module_id;
    }

    /**
     * Get module name
     *
     * @return string
     */
    public function get_name() {
        return $this->module_name;
    }

    /**
     * Get module description
     *
     * @return string
     */
    public function get_description() {
        return $this->module_description;
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
     * Check if module is active
     *
     * @return bool
     */
    public function is_active() {
        return $this->active;
    }

    /**
     * Activate the module
     *
     * @return void
     */
    public function activate() {
        $this->active = true;
        $this->setup_hooks();
        do_action( 'aqualuxe_module_activated', $this->get_id() );
    }

    /**
     * Deactivate the module
     *
     * @return void
     */
    public function deactivate() {
        $this->active = false;
        do_action( 'aqualuxe_module_deactivated', $this->get_id() );
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
     * Update module settings
     *
     * @param array $settings New settings.
     * @return void
     */
    public function update_settings( $settings ) {
        $this->settings = wp_parse_args( $settings, $this->settings );
        update_option( 'aqualuxe_module_' . $this->get_id() . '_settings', $this->settings );
    }

    /**
     * Load module settings
     *
     * @return void
     */
    public function load_settings() {
        $saved_settings = get_option( 'aqualuxe_module_' . $this->get_id() . '_settings', array() );
        $this->settings = wp_parse_args( $saved_settings, $this->settings );
    }

    /**
     * Register customizer settings
     *
     * @param WP_Customize_Manager $wp_customize Theme Customizer object.
     * @return void
     */
    public function register_customizer_settings( $wp_customize ) {
        // To be implemented by child classes if needed
    }
}