<?php
/**
 * AquaLuxe functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package AquaLuxe
 */

// Define theme constants
define('AQUALUXE_VERSION', '1.0.0');
define('AQUALUXE_DIR', trailingslashit(get_template_directory()));
define('AQUALUXE_URI', trailingslashit(get_template_directory_uri()));
define('AQUALUXE_ASSETS_URI', AQUALUXE_URI . 'assets/dist/');
define('AQUALUXE_CORE_DIR', AQUALUXE_DIR . 'core/');
define('AQUALUXE_INC_DIR', AQUALUXE_DIR . 'inc/');
define('AQUALUXE_MODULES_DIR', AQUALUXE_DIR . 'modules/');
define('AQUALUXE_TEMPLATES_DIR', AQUALUXE_DIR . 'templates/');

/**
 * AquaLuxe Theme Class
 * Main theme class that initializes everything
 */
final class AquaLuxe {
    /**
     * Instance of this class
     *
     * @var AquaLuxe
     */
    private static $instance = null;

    /**
     * Modules registry
     *
     * @var array
     */
    private $modules = [];

    /**
     * Is WooCommerce active
     *
     * @var bool
     */
    private $is_woocommerce_active = false;

    /**
     * Get the singleton instance
     *
     * @return AquaLuxe
     */
    public static function instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    private function __construct() {
        // Check if WooCommerce is active
        $this->is_woocommerce_active = class_exists('WooCommerce');

        // Load core files
        $this->load_core_files();

        // Initialize theme
        $this->init();

        // Initialize modules
        $this->init_modules();

        // Initialize hooks
        $this->init_hooks();
    }

    /**
     * Load core files
     */
    private function load_core_files() {
        // Load autoloader
        require_once AQUALUXE_CORE_DIR . 'autoloader.php';

        // Load helpers
        require_once AQUALUXE_CORE_DIR . 'helpers/general.php';
        require_once AQUALUXE_CORE_DIR . 'helpers/template.php';
        require_once AQUALUXE_CORE_DIR . 'helpers/assets.php';

        // Load setup files
        require_once AQUALUXE_CORE_DIR . 'setup/theme-setup.php';
        require_once AQUALUXE_CORE_DIR . 'setup/assets.php';
        require_once AQUALUXE_CORE_DIR . 'setup/menus.php';
        require_once AQUALUXE_CORE_DIR . 'setup/widgets.php';

        // Load customizer
        require_once AQUALUXE_INC_DIR . 'customizer/customizer.php';

        // Load WooCommerce support if active
        if ($this->is_woocommerce_active) {
            require_once AQUALUXE_CORE_DIR . 'setup/woocommerce.php';
        }
    }

    /**
     * Initialize theme
     */
    private function init() {
        // Setup theme
        add_action('after_setup_theme', 'aqualuxe_theme_setup');
        
        // Register widgets
        add_action('widgets_init', 'aqualuxe_widgets_init');
        
        // Enqueue scripts and styles
        add_action('wp_enqueue_scripts', 'aqualuxe_assets');
        
        // Admin scripts and styles
        add_action('admin_enqueue_scripts', 'aqualuxe_admin_assets');
        
        // Editor scripts and styles
        add_action('enqueue_block_editor_assets', 'aqualuxe_editor_assets');
    }

    /**
     * Initialize modules
     */
    private function init_modules() {
        // Get all module directories
        $module_dirs = glob(AQUALUXE_MODULES_DIR . '*', GLOB_ONLYDIR);
        
        foreach ($module_dirs as $module_dir) {
            $module_name = basename($module_dir);
            $module_file = $module_dir . '/module.php';
            
            // Check if module file exists
            if (file_exists($module_file)) {
                // Include module file
                require_once $module_file;
                
                // Get module class name
                $class_name = 'AquaLuxe_' . str_replace('-', '_', ucfirst($module_name)) . '_Module';
                
                // Check if class exists
                if (class_exists($class_name)) {
                    // Initialize module
                    $module = new $class_name();
                    
                    // Check if module is active
                    if ($module->is_active()) {
                        // Register module
                        $this->modules[$module_name] = $module;
                        
                        // Initialize module
                        $module->init();
                    }
                }
            }
        }
    }

    /**
     * Initialize hooks
     */
    private function init_hooks() {
        // Theme hooks
        add_action('wp_head', [$this, 'head_meta']);
        add_filter('body_class', [$this, 'body_classes']);
    }

    /**
     * Add meta tags to head
     */
    public function head_meta() {
        // Add viewport meta tag
        echo '<meta name="viewport" content="width=device-width, initial-scale=1">';
        
        // Add theme color meta tag
        $primary_color = get_theme_mod('primary_color', '#0077b6');
        echo '<meta name="theme-color" content="' . esc_attr($primary_color) . '">';
    }

    /**
     * Add classes to body
     *
     * @param array $classes Body classes
     * @return array Modified body classes
     */
    public function body_classes($classes) {
        // Add a class if WooCommerce is active
        if ($this->is_woocommerce_active) {
            $classes[] = 'woocommerce-active';
        } else {
            $classes[] = 'woocommerce-inactive';
        }
        
        // Add responsive class
        $classes[] = 'aqualuxe-responsive';
        
        // Add theme version
        $classes[] = 'aqualuxe-' . str_replace('.', '-', AQUALUXE_VERSION);
        
        return $classes;
    }

    /**
     * Check if WooCommerce is active
     *
     * @return bool
     */
    public function is_woocommerce_active() {
        return $this->is_woocommerce_active;
    }

    /**
     * Get module
     *
     * @param string $module_name Module name
     * @return object|null Module instance or null if not found
     */
    public function get_module($module_name) {
        return isset($this->modules[$module_name]) ? $this->modules[$module_name] : null;
    }

    /**
     * Check if module is active
     *
     * @param string $module_name Module name
     * @return bool
     */
    public function is_module_active($module_name) {
        return isset($this->modules[$module_name]);
    }
}

// Initialize the theme
function aqualuxe() {
    return AquaLuxe::instance();
}

// Start the theme
aqualuxe();