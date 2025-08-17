<?php
/**
 * AquaLuxe API Wrapper
 *
 * @package AquaLuxe
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * AquaLuxe_API_Wrapper Class
 *
 * Wrapper class for API functionality to ensure compatibility with or without WooCommerce
 */
class AquaLuxe_API_Wrapper {

    /**
     * Instance of this class.
     *
     * @var object
     */
    protected static $instance = null;

    /**
     * Initialize the class and set its properties.
     */
    public function __construct() {
        add_action('rest_api_init', array($this, 'register_api_routes'));
    }

    /**
     * Get instance of this class.
     *
     * @return AquaLuxe_API_Wrapper
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Register API routes
     */
    public function register_api_routes() {
        // Register non-WooCommerce API routes
        $this->register_non_woocommerce_routes();
        
        // Register WooCommerce-dependent API routes only if WooCommerce is active
        if (function_exists('aqualuxe_is_woocommerce_active') && aqualuxe_is_woocommerce_active()) {
            $this->register_woocommerce_routes();
        }
    }

    /**
     * Register non-WooCommerce API routes
     */
    private function register_non_woocommerce_routes() {
        // Load and initialize non-WooCommerce controllers
        $this->load_controller('class-aqualuxe-api-users-controller.php');
        
        // Add other non-WooCommerce controllers here
    }

    /**
     * Register WooCommerce-dependent API routes
     */
    private function register_woocommerce_routes() {
        // Load and initialize WooCommerce-dependent controllers
        $this->load_controller('class-aqualuxe-api-products-controller.php');
        $this->load_controller('class-aqualuxe-api-orders-controller.php');
        $this->load_controller('class-aqualuxe-api-auctions-controller.php');
        $this->load_controller('class-aqualuxe-api-care-guides-controller.php');
        $this->load_controller('class-aqualuxe-api-compatibility-checker-controller.php');
        $this->load_controller('class-aqualuxe-api-subscriptions-controller.php');
        $this->load_controller('class-aqualuxe-api-sync-controller.php');
        $this->load_controller('class-aqualuxe-api-trade-ins-controller.php');
        $this->load_controller('class-aqualuxe-api-water-calculator-controller.php');
    }

    /**
     * Load and initialize a controller
     *
     * @param string $file Controller file name
     */
    private function load_controller($file) {
        $controller_path = AQUALUXE_DIR . 'inc/api/controllers/' . $file;
        
        if (file_exists($controller_path)) {
            require_once $controller_path;
            
            // Get the class name from the file name
            $class_name = str_replace(array('class-', '.php'), '', $file);
            $class_name = str_replace('-', '_', $class_name);
            $class_name = str_replace('_', ' ', $class_name);
            $class_name = ucwords($class_name);
            $class_name = str_replace(' ', '_', $class_name);
            
            // Initialize the controller if the class exists
            if (class_exists($class_name)) {
                $controller = new $class_name();
                if (method_exists($controller, 'register_routes')) {
                    $controller->register_routes();
                }
            }
        }
    }
}

// Initialize the API wrapper
AquaLuxe_API_Wrapper::get_instance();