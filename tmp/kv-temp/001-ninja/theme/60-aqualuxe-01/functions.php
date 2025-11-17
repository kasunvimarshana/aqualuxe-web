<?php
/**
 * AquaLuxe functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Define constants
define('AQUALUXE_VERSION', '1.0.0');
define('AQUALUXE_DIR', trailingslashit(get_template_directory()));
define('AQUALUXE_URI', trailingslashit(get_template_directory_uri()));
define('AQUALUXE_ASSETS_URI', AQUALUXE_URI . 'assets/dist/');
define('AQUALUXE_CORE_DIR', AQUALUXE_DIR . 'core/');
define('AQUALUXE_MODULES_DIR', AQUALUXE_DIR . 'modules/');
define('AQUALUXE_INC_DIR', AQUALUXE_DIR . 'inc/');

/**
 * AquaLuxe Theme Class
 * 
 * Main theme class that handles initialization and loading of the theme.
 */
final class AquaLuxe {
    /**
     * Instance of this class.
     *
     * @var object
     */
    protected static $instance = null;

    /**
     * Modules registry
     *
     * @var array
     */
    public $modules = [];

    /**
     * Module status
     *
     * @var array
     */
    public $module_status = [];

    /**
     * Return an instance of this class.
     *
     * @return object A single instance of this class.
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    public function __construct() {
        // Load core files
        $this->load_core_files();
        
        // Initialize theme
        $this->init();
        
        // Load modules
        $this->load_modules();
    }

    /**
     * Load core files
     */
    private function load_core_files() {
        // Load core functions
        require_once AQUALUXE_CORE_DIR . 'functions/core-functions.php';
        require_once AQUALUXE_CORE_DIR . 'functions/template-functions.php';
        require_once AQUALUXE_CORE_DIR . 'functions/enqueue-scripts.php';
        
        // Load core classes
        require_once AQUALUXE_CORE_DIR . 'classes/class-aqualuxe-setup.php';
        require_once AQUALUXE_CORE_DIR . 'classes/class-aqualuxe-customizer.php';
        require_once AQUALUXE_CORE_DIR . 'classes/class-aqualuxe-assets.php';
        require_once AQUALUXE_CORE_DIR . 'classes/class-aqualuxe-module-loader.php';
        
        // Load helper functions
        require_once AQUALUXE_INC_DIR . 'helpers/general-helpers.php';
        require_once AQUALUXE_INC_DIR . 'helpers/template-helpers.php';
        
        // Load template tags
        require_once AQUALUXE_INC_DIR . 'template-tags/content-tags.php';
        require_once AQUALUXE_INC_DIR . 'template-tags/header-tags.php';
        require_once AQUALUXE_INC_DIR . 'template-tags/footer-tags.php';
    }

    /**
     * Initialize theme
     */
    private function init() {
        // Setup theme
        new AquaLuxe_Setup();
        
        // Initialize customizer
        new AquaLuxe_Customizer();
        
        // Initialize assets
        new AquaLuxe_Assets();
        
        // Initialize module loader
        $this->module_loader = new AquaLuxe_Module_Loader();
    }

    /**
     * Load modules
     */
    private function load_modules() {
        $this->modules = $this->module_loader->get_modules();
        $this->module_status = $this->module_loader->get_module_status();
        
        // Initialize active modules
        $this->module_loader->init_modules();
    }
}

// Initialize the theme
function aqualuxe() {
    return AquaLuxe::get_instance();
}

// Start the theme
aqualuxe();