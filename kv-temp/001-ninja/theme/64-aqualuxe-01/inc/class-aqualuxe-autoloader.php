<?php
/**
 * AquaLuxe Autoloader
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * AquaLuxe Autoloader Class
 * 
 * Handles autoloading of theme classes
 */
class AquaLuxe_Autoloader {
    /**
     * Instance of this class
     *
     * @var AquaLuxe_Autoloader
     */
    private static $instance = null;

    /**
     * Class paths
     *
     * @var array
     */
    private $class_paths = [];

    /**
     * Get singleton instance
     *
     * @return AquaLuxe_Autoloader
     */
    public static function instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    private function __construct() {
        // Register autoloader
        spl_autoload_register( [ $this, 'autoload' ] );

        // Register default class paths
        $this->register_class_path( 'AquaLuxe_', AQUALUXE_INC_DIR );
        $this->register_class_path( 'AquaLuxe_Module_', AQUALUXE_MODULES_DIR );
    }

    /**
     * Register a class path
     *
     * @param string $prefix Class prefix
     * @param string $path Path to look for classes
     */
    public function register_class_path( $prefix, $path ) {
        $this->class_paths[ $prefix ] = trailingslashit( $path );
    }

    /**
     * Autoload classes
     *
     * @param string $class_name Class name
     */
    public function autoload( $class_name ) {
        // Loop through registered class paths
        foreach ( $this->class_paths as $prefix => $path ) {
            // Check if class name starts with prefix
            if ( strpos( $class_name, $prefix ) === 0 ) {
                // Remove prefix from class name
                $relative_class = substr( $class_name, strlen( $prefix ) );
                
                // Convert class name to file path
                $file_path = $path . $this->class_to_file_path( $relative_class );
                
                // Check if file exists
                if ( file_exists( $file_path ) ) {
                    // Include file
                    require_once $file_path;
                    
                    // Stop processing
                    break;
                }
            }
        }
    }

    /**
     * Convert class name to file path
     *
     * @param string $class_name Class name
     * @return string File path
     */
    private function class_to_file_path( $class_name ) {
        // Replace underscores with hyphens
        $file_name = strtolower( str_replace( '_', '-', $class_name ) );
        
        // Add class- prefix and .php extension
        return 'class-' . $file_name . '.php';
    }
}

// Initialize autoloader
AquaLuxe_Autoloader::instance();