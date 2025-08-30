<?php
/**
 * AquaLuxe Module Loader Class
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * AquaLuxe Module Loader Class
 */
class AquaLuxe_Module_Loader {
    /**
     * Instance of this class
     *
     * @var AquaLuxe_Module_Loader
     */
    private static $instance;

    /**
     * Modules
     *
     * @var array
     */
    private $modules = array();

    /**
     * Active modules
     *
     * @var array
     */
    private $active_modules = array();

    /**
     * Main module loader instance
     *
     * @return AquaLuxe_Module_Loader
     */
    public static function instance() {
        if ( ! isset( self::$instance ) && ! ( self::$instance instanceof AquaLuxe_Module_Loader ) ) {
            self::$instance = new AquaLuxe_Module_Loader();
            self::$instance->init();
        }
        return self::$instance;
    }

    /**
     * Initialize module loader
     *
     * @return void
     */
    public function init() {
        // Load modules
        $this->load_modules();
        
        // Register hooks
        $this->register_hooks();
    }

    /**
     * Register hooks
     *
     * @return void
     */
    private function register_hooks() {
        // Register assets
        add_action( 'wp_enqueue_scripts', array( $this, 'register_frontend_assets' ), 10 );
        add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_assets' ), 10 );
        
        // Enqueue assets
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_frontend_assets' ), 20 );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ), 20 );
    }

    /**
     * Load modules
     *
     * @return void
     */
    private function load_modules() {
        // Get all module directories
        $module_dirs = glob( AQUALUXE_MODULES_DIR . '*', GLOB_ONLYDIR );
        
        foreach ( $module_dirs as $module_dir ) {
            $module_id = basename( $module_dir );
            $module_file = $module_dir . '/class-aqualuxe-' . $module_id . '-module.php';
            
            // Check if module file exists
            if ( file_exists( $module_file ) ) {
                // Include module file
                require_once $module_file;
                
                // Get module class name
                $class_name = 'AquaLuxe_' . str_replace( '-', '_', ucfirst( $module_id ) ) . '_Module';
                
                // Check if class exists
                if ( class_exists( $class_name ) ) {
                    // Initialize module
                    $module = new $class_name();
                    
                    // Add module to modules array
                    $this->modules[ $module_id ] = $module;
                    
                    // Add module to active modules array if active
                    if ( $module->is_module_active() ) {
                        $this->active_modules[ $module_id ] = $module;
                    }
                }
            }
        }
    }

    /**
     * Register frontend assets
     *
     * @return void
     */
    public function register_frontend_assets() {
        foreach ( $this->active_modules as $module ) {
            $module->register_assets();
        }
    }

    /**
     * Register admin assets
     *
     * @return void
     */
    public function register_admin_assets() {
        foreach ( $this->active_modules as $module ) {
            $module->register_assets();
        }
    }

    /**
     * Enqueue frontend assets
     *
     * @return void
     */
    public function enqueue_frontend_assets() {
        foreach ( $this->active_modules as $module ) {
            $module->enqueue_frontend_assets();
        }
    }

    /**
     * Enqueue admin assets
     *
     * @return void
     */
    public function enqueue_admin_assets() {
        foreach ( $this->active_modules as $module ) {
            $module->enqueue_admin_assets();
        }
    }

    /**
     * Get module
     *
     * @param string $module_id Module ID
     * @return AquaLuxe_Module|null
     */
    public function get_module( $module_id ) {
        return isset( $this->modules[ $module_id ] ) ? $this->modules[ $module_id ] : null;
    }

    /**
     * Get active module
     *
     * @param string $module_id Module ID
     * @return AquaLuxe_Module|null
     */
    public function get_active_module( $module_id ) {
        return isset( $this->active_modules[ $module_id ] ) ? $this->active_modules[ $module_id ] : null;
    }

    /**
     * Check if module is active
     *
     * @param string $module_id Module ID
     * @return bool
     */
    public function is_module_active( $module_id ) {
        return isset( $this->active_modules[ $module_id ] );
    }

    /**
     * Get all modules
     *
     * @return array
     */
    public function get_modules() {
        return $this->modules;
    }

    /**
     * Get active modules
     *
     * @return array
     */
    public function get_active_modules() {
        return $this->active_modules;
    }
}