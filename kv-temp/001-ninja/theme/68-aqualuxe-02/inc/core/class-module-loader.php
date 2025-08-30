<?php
/**
 * Module Loader Class
 *
 * @package AquaLuxe
 * @subpackage Core
 * @since 1.0.0
 */

namespace AquaLuxe\Core;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Module Loader Class
 * 
 * This class is responsible for loading and managing theme modules.
 */
class Module_Loader {
    /**
     * Instance of this class
     *
     * @var Module_Loader
     */
    private static $instance = null;

    /**
     * Loaded modules
     *
     * @var array
     */
    private $modules = [];

    /**
     * Get the singleton instance
     *
     * @return Module_Loader
     */
    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    private function __construct() {
        // Nothing to do here
    }

    /**
     * Load all modules
     *
     * @return array
     */
    public function load_modules() {
        // Get all module directories
        $module_dirs = glob( AQUALUXE_MODULES_DIR . '*', GLOB_ONLYDIR );
        
        if ( empty( $module_dirs ) ) {
            return [];
        }
        
        // Load each module
        foreach ( $module_dirs as $module_dir ) {
            $module_name = basename( $module_dir );
            $this->load_module( $module_name );
        }
        
        do_action( 'aqualuxe_modules_loaded', $this->modules );
        
        return $this->modules;
    }

    /**
     * Load a specific module
     *
     * @param string $module_name
     * @return object|null
     */
    public function load_module( $module_name ) {
        // Check if the module is already loaded
        if ( isset( $this->modules[ $module_name ] ) ) {
            return $this->modules[ $module_name ];
        }
        
        // Check if the module directory exists
        $module_dir = AQUALUXE_MODULES_DIR . $module_name;
        if ( ! is_dir( $module_dir ) ) {
            return null;
        }
        
        // Check if the module is enabled
        if ( ! $this->is_module_enabled( $module_name ) ) {
            return null;
        }
        
        // Check if the module class file exists
        $module_class_file = $module_dir . '/class-' . $module_name . '.php';
        if ( ! file_exists( $module_class_file ) ) {
            return null;
        }
        
        // Include the module class file
        require_once $module_class_file;
        
        // Get the module class name
        $module_class = '\\AquaLuxe\\Modules\\' . ucfirst( $module_name ) . '\\' . ucfirst( $module_name );
        
        // Check if the module class exists
        if ( ! class_exists( $module_class ) ) {
            return null;
        }
        
        // Initialize the module
        $module = new $module_class();
        
        // Store the module
        $this->modules[ $module_name ] = $module;
        
        do_action( 'aqualuxe_module_loaded', $module_name, $module );
        
        return $module;
    }

    /**
     * Check if a module is enabled
     *
     * @param string $module_name
     * @return boolean
     */
    public function is_module_enabled( $module_name ) {
        // Check if the module config file exists
        $module_config_file = AQUALUXE_MODULES_DIR . $module_name . '/config.php';
        if ( ! file_exists( $module_config_file ) ) {
            return true; // Default to enabled if no config file
        }
        
        // Include the module config file
        $config = include $module_config_file;
        
        // Check if the module is enabled
        if ( isset( $config['enabled'] ) ) {
            return (bool) $config['enabled'];
        }
        
        return true; // Default to enabled
    }

    /**
     * Get all loaded modules
     *
     * @return array
     */
    public function get_modules() {
        return $this->modules;
    }

    /**
     * Get a specific module
     *
     * @param string $module_name
     * @return object|null
     */
    public function get_module( $module_name ) {
        return isset( $this->modules[ $module_name ] ) ? $this->modules[ $module_name ] : null;
    }

    /**
     * Check if a module is loaded
     *
     * @param string $module_name
     * @return boolean
     */
    public function is_module_loaded( $module_name ) {
        return isset( $this->modules[ $module_name ] );
    }

    /**
     * Get all available modules
     *
     * @return array
     */
    public function get_available_modules() {
        $modules = [];
        
        // Get all module directories
        $module_dirs = glob( AQUALUXE_MODULES_DIR . '*', GLOB_ONLYDIR );
        
        if ( empty( $module_dirs ) ) {
            return $modules;
        }
        
        // Get module info
        foreach ( $module_dirs as $module_dir ) {
            $module_name = basename( $module_dir );
            $module_info = $this->get_module_info( $module_name );
            
            if ( $module_info ) {
                $modules[ $module_name ] = $module_info;
            }
        }
        
        return $modules;
    }

    /**
     * Get module information
     *
     * @param string $module_name
     * @return array|null
     */
    public function get_module_info( $module_name ) {
        // Check if the module directory exists
        $module_dir = AQUALUXE_MODULES_DIR . $module_name;
        if ( ! is_dir( $module_dir ) ) {
            return null;
        }
        
        // Check if the module config file exists
        $module_config_file = $module_dir . '/config.php';
        if ( ! file_exists( $module_config_file ) ) {
            return [
                'name'        => $module_name,
                'title'       => ucfirst( $module_name ),
                'description' => '',
                'version'     => '1.0.0',
                'author'      => '',
                'enabled'     => true,
            ];
        }
        
        // Include the module config file
        $config = include $module_config_file;
        
        // Set default values
        $config = wp_parse_args(
            $config,
            [
                'name'        => $module_name,
                'title'       => ucfirst( $module_name ),
                'description' => '',
                'version'     => '1.0.0',
                'author'      => '',
                'enabled'     => true,
            ]
        );
        
        return $config;
    }

    /**
     * Enable a module
     *
     * @param string $module_name
     * @return boolean
     */
    public function enable_module( $module_name ) {
        return $this->update_module_status( $module_name, true );
    }

    /**
     * Disable a module
     *
     * @param string $module_name
     * @return boolean
     */
    public function disable_module( $module_name ) {
        return $this->update_module_status( $module_name, false );
    }

    /**
     * Update module status
     *
     * @param string $module_name
     * @param boolean $enabled
     * @return boolean
     */
    private function update_module_status( $module_name, $enabled ) {
        // Check if the module directory exists
        $module_dir = AQUALUXE_MODULES_DIR . $module_name;
        if ( ! is_dir( $module_dir ) ) {
            return false;
        }
        
        // Get module info
        $module_info = $this->get_module_info( $module_name );
        
        if ( ! $module_info ) {
            return false;
        }
        
        // Update the enabled status
        $module_info['enabled'] = (bool) $enabled;
        
        // Create or update the config file
        $config_content = "<?php\n\nreturn " . var_export( $module_info, true ) . ";\n";
        $config_file = $module_dir . '/config.php';
        
        if ( file_put_contents( $config_file, $config_content ) ) {
            do_action( 'aqualuxe_module_status_updated', $module_name, $enabled );
            return true;
        }
        
        return false;
    }
}