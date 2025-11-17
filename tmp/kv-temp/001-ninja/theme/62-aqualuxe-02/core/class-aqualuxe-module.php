<?php
/**
 * AquaLuxe Module Base Class
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * AquaLuxe Module Base Class
 * 
 * All modules should extend this class
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
    protected $module_version = '1.0.0';

    /**
     * Module dependencies
     *
     * @var array
     */
    protected $dependencies = array();

    /**
     * Module directory
     *
     * @var string
     */
    protected $module_dir = '';

    /**
     * Module URI
     *
     * @var string
     */
    protected $module_uri = '';

    /**
     * Is module active
     *
     * @var bool
     */
    protected $is_active = false;

    /**
     * Module settings
     *
     * @var array
     */
    protected $settings = array();

    /**
     * Constructor
     */
    public function __construct() {
        // Set module directory and URI
        $this->module_dir = AQUALUXE_MODULES_DIR . $this->module_id . '/';
        $this->module_uri = AQUALUXE_URI . 'modules/' . $this->module_id . '/';
        
        // Load module settings
        $this->load_settings();
        
        // Check if module is active
        $this->is_active = $this->is_module_active();
        
        // Initialize module if active
        if ( $this->is_active ) {
            $this->init();
        }
    }

    /**
     * Initialize module
     * 
     * This method should be overridden by child classes
     *
     * @return void
     */
    abstract public function init();

    /**
     * Load module settings
     *
     * @return void
     */
    protected function load_settings() {
        $default_settings = $this->get_default_settings();
        $saved_settings = get_option( 'aqualuxe_module_' . $this->module_id . '_settings', array() );
        
        $this->settings = wp_parse_args( $saved_settings, $default_settings );
    }

    /**
     * Get default settings
     *
     * @return array
     */
    protected function get_default_settings() {
        return array(
            'active' => true,
        );
    }

    /**
     * Check if module is active
     *
     * @return bool
     */
    public function is_module_active() {
        // Check if module is active in settings
        if ( isset( $this->settings['active'] ) && ! $this->settings['active'] ) {
            return false;
        }
        
        // Check dependencies
        foreach ( $this->dependencies as $dependency ) {
            if ( ! $this->check_dependency( $dependency ) ) {
                return false;
            }
        }
        
        return true;
    }

    /**
     * Check dependency
     *
     * @param string $dependency Dependency
     * @return bool
     */
    protected function check_dependency( $dependency ) {
        // Check if dependency is a module
        if ( strpos( $dependency, 'module:' ) === 0 ) {
            $module_id = str_replace( 'module:', '', $dependency );
            return AquaLuxe_Module_Loader::instance()->is_module_active( $module_id );
        }
        
        // Check if dependency is a plugin
        if ( strpos( $dependency, 'plugin:' ) === 0 ) {
            $plugin = str_replace( 'plugin:', '', $dependency );
            
            if ( $plugin === 'woocommerce' ) {
                return class_exists( 'WooCommerce' );
            }
            
            // Add more plugin checks as needed
            return false;
        }
        
        // Check if dependency is a PHP extension
        if ( strpos( $dependency, 'extension:' ) === 0 ) {
            $extension = str_replace( 'extension:', '', $dependency );
            return extension_loaded( $extension );
        }
        
        // Check if dependency is a PHP version
        if ( strpos( $dependency, 'php:' ) === 0 ) {
            $version = str_replace( 'php:', '', $dependency );
            return version_compare( PHP_VERSION, $version, '>=' );
        }
        
        // Check if dependency is a WordPress version
        if ( strpos( $dependency, 'wp:' ) === 0 ) {
            $version = str_replace( 'wp:', '', $dependency );
            return version_compare( get_bloginfo( 'version' ), $version, '>=' );
        }
        
        return true;
    }

    /**
     * Get module ID
     *
     * @return string
     */
    public function get_module_id() {
        return $this->module_id;
    }

    /**
     * Get module name
     *
     * @return string
     */
    public function get_module_name() {
        return $this->module_name;
    }

    /**
     * Get module description
     *
     * @return string
     */
    public function get_module_description() {
        return $this->module_description;
    }

    /**
     * Get module version
     *
     * @return string
     */
    public function get_module_version() {
        return $this->module_version;
    }

    /**
     * Get module directory
     *
     * @return string
     */
    public function get_module_dir() {
        return $this->module_dir;
    }

    /**
     * Get module URI
     *
     * @return string
     */
    public function get_module_uri() {
        return $this->module_uri;
    }

    /**
     * Get module setting
     *
     * @param string $key Setting key
     * @param mixed $default Default value
     * @return mixed
     */
    public function get_setting( $key, $default = null ) {
        return isset( $this->settings[ $key ] ) ? $this->settings[ $key ] : $default;
    }

    /**
     * Update module setting
     *
     * @param string $key Setting key
     * @param mixed $value Setting value
     * @return void
     */
    public function update_setting( $key, $value ) {
        $this->settings[ $key ] = $value;
        
        update_option( 'aqualuxe_module_' . $this->module_id . '_settings', $this->settings );
    }

    /**
     * Register assets
     *
     * @return void
     */
    public function register_assets() {
        // This method should be overridden by child classes if needed
    }

    /**
     * Enqueue frontend assets
     *
     * @return void
     */
    public function enqueue_frontend_assets() {
        // This method should be overridden by child classes if needed
    }

    /**
     * Enqueue admin assets
     *
     * @return void
     */
    public function enqueue_admin_assets() {
        // This method should be overridden by child classes if needed
    }
}