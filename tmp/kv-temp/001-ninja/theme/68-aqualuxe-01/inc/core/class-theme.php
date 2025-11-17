<?php
/**
 * Main Theme Class
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
 * Main Theme Class
 * 
 * This class is responsible for initializing the theme and loading all required components.
 * It follows the singleton pattern to ensure only one instance is created.
 */
class Theme {
    /**
     * Instance of this class
     *
     * @var Theme
     */
    private static $instance = null;

    /**
     * Theme modules
     *
     * @var array
     */
    private $modules = [];

    /**
     * Get the singleton instance
     *
     * @return Theme
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
        $this->load_dependencies();
        $this->setup_hooks();
        $this->load_modules();
    }

    /**
     * Load required dependencies
     *
     * @return void
     */
    private function load_dependencies() {
        // Core classes
        require_once AQUALUXE_INC_DIR . 'core/class-assets.php';
        require_once AQUALUXE_INC_DIR . 'core/class-setup.php';
        require_once AQUALUXE_INC_DIR . 'core/class-template.php';
        require_once AQUALUXE_INC_DIR . 'core/class-hooks.php';
        
        // Module loader
        require_once AQUALUXE_INC_DIR . 'core/class-module-loader.php';
        
        // Customizer
        require_once AQUALUXE_INC_DIR . 'customizer/class-customizer.php';
        
        // Helpers
        require_once AQUALUXE_INC_DIR . 'helpers/class-utils.php';
    }

    /**
     * Setup theme hooks
     *
     * @return void
     */
    private function setup_hooks() {
        // Initialize core classes
        Assets::get_instance();
        Setup::get_instance();
        Template::get_instance();
        Hooks::get_instance();
        
        // Initialize customizer
        \AquaLuxe\Customizer\Customizer::get_instance();
        
        // Check if WooCommerce is active and initialize WooCommerce support
        add_action( 'after_setup_theme', [ $this, 'init_woocommerce_support' ] );
    }

    /**
     * Initialize WooCommerce support if the plugin is active
     *
     * @return void
     */
    public function init_woocommerce_support() {
        if ( $this->is_woocommerce_active() ) {
            require_once AQUALUXE_INC_DIR . 'core/class-woocommerce.php';
            \AquaLuxe\Core\WooCommerce::get_instance();
        }
    }

    /**
     * Check if WooCommerce is active
     *
     * @return boolean
     */
    public function is_woocommerce_active() {
        return class_exists( 'WooCommerce' );
    }

    /**
     * Load theme modules
     *
     * @return void
     */
    private function load_modules() {
        $module_loader = Module_Loader::get_instance();
        $this->modules = $module_loader->load_modules();
    }

    /**
     * Get loaded modules
     *
     * @return array
     */
    public function get_modules() {
        return $this->modules;
    }

    /**
     * Get a specific module by name
     *
     * @param string $module_name
     * @return object|null
     */
    public function get_module( $module_name ) {
        return isset( $this->modules[ $module_name ] ) ? $this->modules[ $module_name ] : null;
    }
}