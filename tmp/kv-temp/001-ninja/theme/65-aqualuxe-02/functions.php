<?php
/**
 * AquaLuxe functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package AquaLuxe
 */

// Define theme constants
define( 'AQUALUXE_VERSION', '1.0.0' );
define( 'AQUALUXE_DIR', trailingslashit( get_template_directory() ) );
define( 'AQUALUXE_URI', trailingslashit( get_template_directory_uri() ) );
define( 'AQUALUXE_ASSETS_URI', AQUALUXE_URI . 'assets/dist/' );
define( 'AQUALUXE_INC_DIR', AQUALUXE_DIR . 'inc/' );
define( 'AQUALUXE_MODULES_DIR', AQUALUXE_DIR . 'modules/' );
define( 'AQUALUXE_TEMPLATES_DIR', AQUALUXE_DIR . 'templates/' );

/**
 * AquaLuxe Class Autoloader
 *
 * @param string $class_name The name of the class to load.
 * @return void
 */
function aqualuxe_autoloader( $class_name ) {
    // Only handle classes with our prefix
    if ( strpos( $class_name, 'AquaLuxe\\' ) !== 0 ) {
        return;
    }

    // Remove the prefix
    $class_name = str_replace( 'AquaLuxe\\', '', $class_name );
    
    // Convert namespace separators to directory separators
    $class_file = str_replace( '\\', DIRECTORY_SEPARATOR, $class_name );
    
    // Check if it's a module class
    if ( strpos( $class_file, 'Modules' . DIRECTORY_SEPARATOR ) === 0 ) {
        $class_file = str_replace( 'Modules' . DIRECTORY_SEPARATOR, '', $class_file );
        $file_path = AQUALUXE_MODULES_DIR . $class_file . '.php';
    } else {
        // It's a core class
        $file_path = AQUALUXE_INC_DIR . $class_file . '.php';
    }
    
    // Include the file if it exists
    if ( file_exists( $file_path ) ) {
        require_once $file_path;
    }
}

// Register the autoloader
spl_autoload_register( 'aqualuxe_autoloader' );

/**
 * Initialize the theme
 */
require_once AQUALUXE_INC_DIR . 'Core/Theme.php';

// Bootstrap the theme
AquaLuxe\Core\Theme::get_instance();