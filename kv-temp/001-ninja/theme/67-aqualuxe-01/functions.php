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
define( 'AQUALUXE_ASSETS_DIR', AQUALUXE_DIR . 'assets/dist/' );
define( 'AQUALUXE_INC_DIR', AQUALUXE_DIR . 'inc/' );
define( 'AQUALUXE_MODULES_DIR', AQUALUXE_DIR . 'modules/' );

/**
 * AquaLuxe Theme Class Autoloader
 *
 * @param string $class_name The class name to autoload.
 * @return void
 */
function aqualuxe_autoloader( $class_name ) {
    // Only handle our own classes
    if ( strpos( $class_name, 'AquaLuxe\\' ) !== 0 ) {
        return;
    }

    // Remove the namespace prefix
    $class_name = str_replace( 'AquaLuxe\\', '', $class_name );
    
    // Convert class name format to file name format
    $class_name = str_replace( '\\', '/', $class_name );
    $class_file = str_replace( '_', '-', strtolower( $class_name ) ) . '.php';
    
    // Check in inc directory first
    $file_path = AQUALUXE_INC_DIR . $class_file;
    
    if ( file_exists( $file_path ) ) {
        require_once $file_path;
        return;
    }
    
    // Check in modules directory
    $file_path = AQUALUXE_MODULES_DIR . $class_file;
    
    if ( file_exists( $file_path ) ) {
        require_once $file_path;
        return;
    }
}

// Register the autoloader
spl_autoload_register( 'aqualuxe_autoloader' );

/**
 * Initialize the theme
 */
require_once AQUALUXE_INC_DIR . 'core/class-theme.php';

// Bootstrap the theme
AquaLuxe\Core\Theme::get_instance();