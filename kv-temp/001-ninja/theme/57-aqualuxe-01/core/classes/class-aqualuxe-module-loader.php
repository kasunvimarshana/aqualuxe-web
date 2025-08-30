<?php
/**
 * Module Loader Class
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Module Loader class
 */
class AquaLuxe_Module_Loader {
    /**
     * Registered modules
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
     * Module configurations
     *
     * @var array
     */
    private $module_configs = array();

    /**
     * Instance of this class
     *
     * @var AquaLuxe_Module_Loader
     */
    private static $instance = null;

    /**
     * Get instance of this class
     *
     * @return AquaLuxe_Module_Loader
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
        $this->module_configs = $this->get_modules_config();
        $this->load_active_modules();
        add_action( 'init', array( $this, 'initialize_modules' ), 5 );
        add_action( 'customize_register', array( $this, 'register_customizer_settings' ) );
    }

    /**
     * Get modules configuration
     *
     * @return array Modules configuration
     */
    private function get_modules_config() {
        $default_modules = array(
            'dark-mode' => array(
                'name' => 'Dark Mode',
                'description' => 'Adds dark mode functionality with persistent user preference',
                'enabled' => true,
                'class' => 'AquaLuxe_Dark_Mode_Module',
                'file' => 'modules/dark-mode/module.php',
            ),
            'multilingual' => array(
                'name' => 'Multilingual',
                'description' => 'Adds multilingual support',
                'enabled' => true,
                'class' => 'AquaLuxe_Multilingual_Module',
                'file' => 'modules/multilingual/module.php',
            ),
            'performance' => array(
                'name' => 'Performance',
                'description' => 'Optimizes theme performance',
                'enabled' => true,
                'class' => 'AquaLuxe_Performance_Module',
                'file' => 'modules/performance/module.php',
            ),
            'seo' => array(
                'name' => 'SEO',
                'description' => 'Enhances search engine optimization',
                'enabled' => true,
                'class' => 'AquaLuxe_SEO_Module',
                'file' => 'modules/seo/module.php',
            ),
            'subscriptions' => array(
                'name' => 'Subscriptions',
                'description' => 'Adds subscription functionality',
                'enabled' => true,
                'requires_woocommerce' => true,
                'class' => 'AquaLuxe_Subscriptions_Module',
                'file' => 'modules/subscriptions/module.php',
            ),
            'auctions' => array(
                'name' => 'Auctions',
                'description' => 'Adds auction functionality',
                'enabled' => true,
                'requires_woocommerce' => true,
                'class' => 'AquaLuxe_Auctions_Module',
                'file' => 'modules/auctions/module.php',
            ),
            'bookings' => array(
                'name' => 'Bookings',
                'description' => 'Adds booking functionality',
                'enabled' => true,
                'requires_woocommerce' => true,
                'class' => 'AquaLuxe_Bookings_Module',
                'file' => 'modules/bookings/module.php',
            ),
            'events' => array(
                'name' => 'Events',
                'description' => 'Adds events and ticketing functionality',
                'enabled' => true,
                'class' => 'AquaLuxe_Events_Module',
                'file' => 'modules/events/module.php',
            ),
            'wholesale' => array(
                'name' => 'Wholesale',
                'description' => 'Adds wholesale/B2B functionality',
                'enabled' => true,
                'requires_woocommerce' => true,
                'class' => 'AquaLuxe_Wholesale_Module',
                'file' => 'modules/wholesale/module.php',
            ),
            'services' => array(
                'name' => 'Services',
                'description' => 'Adds services functionality',
                'enabled' => true,
                'class' => 'AquaLuxe_Services_Module',
                'file' => 'modules/services/module.php',
            ),
            'franchise' => array(
                'name' => 'Franchise',
                'description' => 'Adds franchise/licensing functionality',
                'enabled' => true,
                'class' => 'AquaLuxe_Franchise_Module',
                'file' => 'modules/franchise/module.php',
            ),
            'sustainability' => array(
                'name' => 'Sustainability',
                'description' => 'Adds R&D and sustainability functionality',
                'enabled' => true,
                'class' => 'AquaLuxe_Sustainability_Module',
                'file' => 'modules/sustainability/module.php',
            ),
            'affiliate' => array(
                'name' => 'Affiliate',
                'description' => 'Adds affiliate/referral functionality',
                'enabled' => true,
                'class' => 'AquaLuxe_Affiliate_Module',
                'file' => 'modules/affiliate/module.php',
            ),
        );

        // Allow modules to be filtered
        return apply_filters( 'aqualuxe_modules', $default_modules );
    }

    /**
     * Register a module
     *
     * @param string $module_id Module ID.
     * @param string $module_class Module class name.
     * @param string $module_path Module file path.
     * @return bool
     */
    public function register_module( $module_id, $module_class, $module_path ) {
        if ( isset( $this->modules[ $module_id ] ) ) {
            return false;
        }

        $this->modules[ $module_id ] = array(
            'id'    => $module_id,
            'class' => $module_class,
            'path'  => $module_path,
        );

        return true;
    }

    /**
     * Initialize modules
     *
     * @return void
     */
    public function initialize_modules() {
        // Register all modules from configuration
        foreach ( $this->module_configs as $module_id => $config ) {
            // Skip modules that require WooCommerce if it's not active
            if ( isset( $config['requires_woocommerce'] ) && $config['requires_woocommerce'] && ! class_exists( 'WooCommerce' ) ) {
                continue;
            }

            $module_path = AQUALUXE_THEME_DIR . '/' . $config['file'];
            $this->register_module( $module_id, $config['class'], $module_path );
        }

        // Load and initialize registered modules
        foreach ( $this->modules as $module_id => $module_data ) {
            // Include the module file if it exists
            if ( file_exists( $module_data['path'] ) ) {
                require_once $module_data['path'];
            }

            // Check if the module class exists
            if ( class_exists( $module_data['class'] ) ) {
                // Create an instance of the module
                $module = new $module_data['class']();
                
                // Store the module instance
                $this->modules[ $module_id ]['instance'] = $module;
                
                // Activate the module if it's in the active modules list
                if ( in_array( $module_id, $this->active_modules, true ) ) {
                    $module->activate();
                }
            }
        }

        // Action hook after modules are loaded
        do_action( 'aqualuxe_modules_loaded' );
    }

    /**
     * Get all registered modules
     *
     * @return array
     */
    public function get_modules() {
        return $this->modules;
    }

    /**
     * Get a specific module
     *
     * @param string $module_id Module ID.
     * @return mixed
     */
    public function get_module( $module_id ) {
        return isset( $this->modules[ $module_id ] ) ? $this->modules[ $module_id ] : false;
    }

    /**
     * Get module instance
     *
     * @param string $module_id Module ID.
     * @return mixed
     */
    public function get_module_instance( $module_id ) {
        return isset( $this->modules[ $module_id ]['instance'] ) ? $this->modules[ $module_id ]['instance'] : false;
    }

    /**
     * Activate a module
     *
     * @param string $module_id Module ID.
     * @return bool
     */
    public function activate_module( $module_id ) {
        if ( ! isset( $this->modules[ $module_id ] ) ) {
            return false;
        }

        $module_instance = $this->get_module_instance( $module_id );
        
        if ( ! $module_instance ) {
            return false;
        }

        // Check dependencies
        $dependencies = $module_instance->get_dependencies();
        foreach ( $dependencies as $dependency ) {
            if ( ! in_array( $dependency, $this->active_modules, true ) ) {
                // Dependency not active, try to activate it
                if ( ! $this->activate_module( $dependency ) ) {
                    return false;
                }
            }
        }

        // Activate the module
        $module_instance->activate();
        
        // Add to active modules if not already there
        if ( ! in_array( $module_id, $this->active_modules, true ) ) {
            $this->active_modules[] = $module_id;
            update_option( 'aqualuxe_active_modules', $this->active_modules );
        }

        return true;
    }

    /**
     * Deactivate a module
     *
     * @param string $module_id Module ID.
     * @return bool
     */
    public function deactivate_module( $module_id ) {
        if ( ! isset( $this->modules[ $module_id ] ) ) {
            return false;
        }

        $module_instance = $this->get_module_instance( $module_id );
        
        if ( ! $module_instance ) {
            return false;
        }

        // Check if any active modules depend on this one
        foreach ( $this->modules as $id => $module ) {
            $instance = $this->get_module_instance( $id );
            if ( $instance && $instance->is_active() ) {
                $dependencies = $instance->get_dependencies();
                if ( in_array( $module_id, $dependencies, true ) ) {
                    // Deactivate dependent module first
                    $this->deactivate_module( $id );
                }
            }
        }

        // Deactivate the module
        $module_instance->deactivate();
        
        // Remove from active modules
        $key = array_search( $module_id, $this->active_modules, true );
        if ( false !== $key ) {
            unset( $this->active_modules[ $key ] );
            $this->active_modules = array_values( $this->active_modules ); // Reindex array
            update_option( 'aqualuxe_active_modules', $this->active_modules );
        }

        return true;
    }

    /**
     * Load active modules from database
     *
     * @return void
     */
    private function load_active_modules() {
        $saved_active_modules = get_option( 'aqualuxe_active_modules', array() );
        
        // If no saved active modules, use the enabled modules from config as default
        if ( empty( $saved_active_modules ) ) {
            foreach ( $this->module_configs as $module_id => $config ) {
                if ( isset( $config['enabled'] ) && $config['enabled'] ) {
                    // Skip modules that require WooCommerce if it's not active
                    if ( isset( $config['requires_woocommerce'] ) && $config['requires_woocommerce'] && ! class_exists( 'WooCommerce' ) ) {
                        continue;
                    }
                    $this->active_modules[] = $module_id;
                }
            }
            
            // Save the default active modules
            update_option( 'aqualuxe_active_modules', $this->active_modules );
        } else {
            $this->active_modules = $saved_active_modules;
        }
    }

    /**
     * Register customizer settings for all active modules
     *
     * @param WP_Customize_Manager $wp_customize Theme Customizer object.
     * @return void
     */
    public function register_customizer_settings( $wp_customize ) {
        foreach ( $this->modules as $module_id => $module_data ) {
            $module_instance = $this->get_module_instance( $module_id );
            if ( $module_instance && $module_instance->is_active() ) {
                $module_instance->register_customizer_settings( $wp_customize );
            }
        }
    }

    /**
     * Get enabled modules
     *
     * @return array Enabled modules
     */
    public function get_enabled_modules() {
        $enabled_modules = array();

        foreach ( $this->active_modules as $module_id ) {
            $module_instance = $this->get_module_instance( $module_id );
            if ( $module_instance && $module_instance->is_active() ) {
                $enabled_modules[ $module_id ] = array(
                    'id' => $module_id,
                    'name' => $module_instance->get_name(),
                    'description' => $module_instance->get_description(),
                    'version' => $module_instance->get_version(),
                );
            }
        }

        return $enabled_modules;
    }

    /**
     * Check if a module is enabled
     *
     * @param string $module_id Module ID.
     * @return bool Whether the module is enabled
     */
    public function is_module_enabled( $module_id ) {
        $module_instance = $this->get_module_instance( $module_id );
        return $module_instance && $module_instance->is_active();
    }
}