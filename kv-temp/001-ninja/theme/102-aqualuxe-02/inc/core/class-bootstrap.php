<?php
/**
 * Bootstrap Class
 *
 * Main theme initialization and bootstrap class
 *
 * @package AquaLuxe\Core
 * @since 1.0.0
 */

namespace AquaLuxe\Core;

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class Bootstrap
 *
 * Handles theme initialization, autoloading, and module management
 *
 * @since 1.0.0
 */
class Bootstrap {

    /**
     * Theme configuration
     *
     * @var array
     */
    private $config = array();

    /**
     * Autoloader instance
     *
     * @var Autoloader
     */
    private $autoloader;

    /**
     * Loaded modules
     *
     * @var array
     */
    private $modules = array();

    /**
     * Core components
     *
     * @var array
     */
    private $core_components = array();

    /**
     * Initialize the bootstrap
     *
     * @since 1.0.0
     */
    public function init() {
        $this->setup_autoloader();
        $this->load_configuration();
        $this->init_theme();
    }

    /**
     * Setup the PSR-4 autoloader
     *
     * @since 1.0.0
     */
    private function setup_autoloader() {
        // Initialize autoloader
        $this->autoloader = new Autoloader();

        // Register theme namespace
        $this->autoloader->add_namespace( 'AquaLuxe\\', AQUALUXE_INC_DIR );

        // Register modules namespace
        $this->autoloader->add_namespace( 'AquaLuxe\\Modules\\', AQUALUXE_MODULES_DIR );

        // Register the autoloader
        $this->autoloader->register();
    }

    /**
     * Load theme configuration
     *
     * @since 1.0.0
     */
    private function load_configuration() {
        // Default configuration
        $this->config = array(
            'modules' => array(
                'assets'        => true,
                'customizer'    => true,
                'security'      => true,
                'performance'   => true,
                'seo'           => true,
                'multilingual'  => true,
                'dark_mode'     => true,
                'woocommerce'   => class_exists( 'WooCommerce' ),
                'demo_importer' => true,
            ),
            'features' => array(
                'custom_post_types' => true,
                'custom_taxonomies' => true,
                'custom_fields' => true,
            )
        );

        // Load custom configuration if exists
        $config_file = AQUALUXE_THEME_DIR . '/config/theme-config.php';
        if ( file_exists( $config_file ) ) {
            $custom_config = include $config_file;
            if ( is_array( $custom_config ) ) {
                $this->config = array_merge( $this->config, $custom_config );
            }
        }

        // Apply filters for configuration customization
        $this->config = apply_filters( 'aqualuxe_theme_config', $this->config );
    }

    /**
     * Initialize theme components
     *
     * @since 1.0.0
     */
    private function init_theme() {
        // Load core components first
        $this->load_core_components();

        // Load modules
        $this->load_modules();

        // Fire action after theme is fully loaded
        do_action( 'aqualuxe_theme_loaded', $this );
    }

    /**
     * Load core theme components
     *
     * @since 1.0.0
     */
    private function load_core_components() {
        $components = array(
            'assets'        => 'Core\\Assets',
            'template'      => 'Core\\Template',
            'hooks'         => 'Core\\Hooks',
        );

        foreach ( $components as $key => $class_name ) {
            $full_class_name = 'AquaLuxe\\' . $class_name;
            
            if ( class_exists( $full_class_name ) ) {
                $this->core_components[ $key ] = new $full_class_name();
                
                // Initialize if method exists
                if ( method_exists( $this->core_components[ $key ], 'init' ) ) {
                    $this->core_components[ $key ]->init();
                }
            }
        }

        // Fire action after core components are loaded
        do_action( 'aqualuxe_core_components_loaded', $this->core_components, $this );
    }

    /**
     * Load theme modules
     *
     * @since 1.0.0
     */
    private function load_modules() {
        $enabled_modules = $this->get_enabled_modules();

        // Sort modules by priority/dependencies
        $enabled_modules = $this->sort_modules_by_dependencies( $enabled_modules );

        foreach ( $enabled_modules as $module_key ) {
            $this->load_module( $module_key );
        }

        // Fire action after all modules are loaded
        do_action( 'aqualuxe_modules_loaded', $this->modules, $this );
    }

    /**
     * Load a single module
     *
     * @since 1.0.0
     * @param string $module_key Module identifier
     */
    private function load_module( $module_key ) {
        // Try to load from modules directory first
        $module_file = AQUALUXE_MODULES_DIR . '/' . $module_key . '/class-' . $module_key . '.php';
        
        if ( file_exists( $module_file ) ) {
            require_once $module_file;
            
            // Try to instantiate module class
            $class_name = 'AquaLuxe\\Modules\\' . ucfirst( $module_key ) . '\\' . ucfirst( $module_key );
            
            if ( class_exists( $class_name ) ) {
                $this->modules[ $module_key ] = new $class_name();
                
                // Initialize if method exists
                if ( method_exists( $this->modules[ $module_key ], 'init' ) ) {
                    $this->modules[ $module_key ]->init();
                }
            }
        } else {
            // Try to load from inc directory as fallback
            $inc_file = AQUALUXE_INC_DIR . '/modules/class-' . $module_key . '.php';
            
            if ( file_exists( $inc_file ) ) {
                require_once $inc_file;
                
                $class_name = 'AquaLuxe\\Modules\\' . str_replace( '_', '_', ucfirst( $module_key ) );
                
                if ( class_exists( $class_name ) ) {
                    $this->modules[ $module_key ] = new $class_name();
                    
                    if ( method_exists( $this->modules[ $module_key ], 'init' ) ) {
                        $this->modules[ $module_key ]->init();
                    }
                }
            }
        }
    }

    /**
     * Get enabled modules from configuration
     *
     * @since 1.0.0
     * @return array List of enabled module keys
     */
    private function get_enabled_modules() {
        $modules_config = $this->config['modules'] ?? array();
        return array_keys( array_filter( $modules_config ) );
    }

    /**
     * Sort modules by dependencies
     *
     * @since 1.0.0
     * @param array $modules List of module keys
     * @return array Sorted module keys
     */
    private function sort_modules_by_dependencies( array $modules ) {
        // Sort modules by priority/dependencies
        $priority_order = array(
            'assets',
            'security',
            'performance',
            'customizer',
            'seo',
            'multilingual',
            'dark_mode',
            'woocommerce',
            'demo_importer',
        );

        $sorted = array();
        
        // Add modules in priority order
        foreach ( $priority_order as $module ) {
            if ( in_array( $module, $modules, true ) ) {
                $sorted[] = $module;
            }
        }
        
        // Add remaining modules
        foreach ( $modules as $module ) {
            if ( ! in_array( $module, $sorted, true ) ) {
                $sorted[] = $module;
            }
        }

        return $sorted;
    }

    /**
     * Get loaded modules
     *
     * @since 1.0.0
     * @return array
     */
    public function get_modules() {
        return $this->modules;
    }

    /**
     * Get theme configuration
     *
     * @since 1.0.0
     * @return array
     */
    public function get_config() {
        return $this->config;
    }

    /**
     * Get a specific module
     *
     * @since 1.0.0
     * @param string $module_key Module identifier
     * @return mixed|null
     */
    public function get_module( $module_key ) {
        return $this->modules[ $module_key ] ?? null;
    }
}