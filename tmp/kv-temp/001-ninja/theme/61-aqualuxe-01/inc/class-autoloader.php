<?php
/**
 * Autoloader for AquaLuxe theme classes
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Autoloader class
 */
class AquaLuxe_Autoloader {
    /**
     * Path mappings for class prefixes
     *
     * @var array
     */
    private static $mappings = [
        'AquaLuxe\\Core\\'        => 'inc/core/',
        'AquaLuxe\\Admin\\'       => 'inc/admin/',
        'AquaLuxe\\PostTypes\\'   => 'inc/post-types/',
        'AquaLuxe\\Taxonomies\\'  => 'inc/taxonomies/',
        'AquaLuxe\\Widgets\\'     => 'inc/widgets/',
        'AquaLuxe\\WooCommerce\\' => 'inc/woocommerce/',
        'AquaLuxe\\Modules\\'     => 'modules/',
    ];

    /**
     * Register the autoloader
     */
    public static function register() {
        spl_autoload_register( [ __CLASS__, 'autoload' ] );
    }

    /**
     * Autoload classes
     *
     * @param string $class_name The class name to autoload.
     */
    public static function autoload( $class_name ) {
        // Check if the class uses our namespace
        foreach ( self::$mappings as $prefix => $path ) {
            $len = strlen( $prefix );
            if ( strncmp( $prefix, $class_name, $len ) !== 0 ) {
                continue;
            }

            // Get the relative class name
            $relative_class = substr( $class_name, $len );

            // Replace namespace separators with directory separators
            $file = AQUALUXE_DIR . $path . str_replace( '\\', '/', $relative_class ) . '.php';
            $file = str_replace( '_', '-', strtolower( $file ) );

            // If the file exists, require it
            if ( file_exists( $file ) ) {
                require_once $file;
                return;
            }
        }
    }
}

// Register the autoloader
AquaLuxe_Autoloader::register();