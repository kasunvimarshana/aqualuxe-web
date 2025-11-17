<?php
/**
 * AquaLuxe functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Define theme constants
define( 'AQUALUXE_VERSION', '1.0.0' );
define( 'AQUALUXE_DIR', trailingslashit( get_template_directory() ) );
define( 'AQUALUXE_URI', trailingslashit( get_template_directory_uri() ) );
define( 'AQUALUXE_ASSETS_URI', AQUALUXE_URI . 'assets/dist/' );
define( 'AQUALUXE_CORE_DIR', AQUALUXE_DIR . 'core/' );
define( 'AQUALUXE_MODULES_DIR', AQUALUXE_DIR . 'modules/' );
define( 'AQUALUXE_INC_DIR', AQUALUXE_DIR . 'inc/' );
define( 'AQUALUXE_TEMPLATES_DIR', AQUALUXE_DIR . 'templates/' );

/**
 * AquaLuxe Theme Class
 * 
 * Main theme class that bootstraps the theme
 */
final class AquaLuxe {
    /**
     * Instance of this class
     *
     * @var AquaLuxe
     */
    private static $instance;

    /**
     * Main theme instance
     *
     * @return AquaLuxe
     */
    public static function instance() {
        if ( ! isset( self::$instance ) && ! ( self::$instance instanceof AquaLuxe ) ) {
            self::$instance = new AquaLuxe();
            self::$instance->includes();
            self::$instance->init_hooks();
        }
        return self::$instance;
    }

    /**
     * Include required files
     *
     * @return void
     */
    private function includes() {
        // Load core functionality
        require_once AQUALUXE_CORE_DIR . 'class-aqualuxe-core.php';
        
        // Load helpers
        require_once AQUALUXE_INC_DIR . 'helpers.php';
        
        // Load theme setup
        require_once AQUALUXE_INC_DIR . 'class-aqualuxe-setup.php';
        
        // Load assets manager
        require_once AQUALUXE_INC_DIR . 'class-aqualuxe-assets.php';
        
        // Load template functions
        require_once AQUALUXE_INC_DIR . 'template-functions.php';
        require_once AQUALUXE_INC_DIR . 'template-tags.php';
        
        // Load customizer
        require_once AQUALUXE_INC_DIR . 'class-aqualuxe-customizer.php';
        
        // Load module system
        require_once AQUALUXE_CORE_DIR . 'class-aqualuxe-module.php';
        require_once AQUALUXE_CORE_DIR . 'class-aqualuxe-module-loader.php';
    }

    /**
     * Initialize hooks
     *
     * @return void
     */
    private function init_hooks() {
        // After setup theme
        add_action( 'after_setup_theme', array( $this, 'setup_theme' ) );
        
        // Init modules
        add_action( 'after_setup_theme', array( $this, 'init_modules' ), 20 );
        
        // Init WooCommerce if active
        add_action( 'after_setup_theme', array( $this, 'init_woocommerce' ), 30 );
    }

    /**
     * Setup theme
     *
     * @return void
     */
    public function setup_theme() {
        // Initialize theme setup
        AquaLuxe_Setup::instance();
        
        // Initialize assets
        AquaLuxe_Assets::instance();
        
        // Initialize customizer
        AquaLuxe_Customizer::instance();
    }

    /**
     * Initialize modules
     *
     * @return void
     */
    public function init_modules() {
        // Initialize module loader
        AquaLuxe_Module_Loader::instance();
    }

    /**
     * Initialize WooCommerce if active
     *
     * @return void
     */
    public function init_woocommerce() {
        // Check if WooCommerce is active
        if ( class_exists( 'WooCommerce' ) ) {
            require_once AQUALUXE_INC_DIR . 'woocommerce/class-aqualuxe-woocommerce.php';
            AquaLuxe_WooCommerce::instance();
        }
    }
}

// Initialize the theme
AquaLuxe::instance();