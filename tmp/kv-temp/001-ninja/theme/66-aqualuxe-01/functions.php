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
define('AQUALUXE_CORE_DIR', AQUALUXE_DIR . 'core/');
define('AQUALUXE_MODULES_DIR', AQUALUXE_DIR . 'modules/');
define('AQUALUXE_INC_DIR', AQUALUXE_DIR . 'inc/');
define('AQUALUXE_TEMPLATES_DIR', AQUALUXE_DIR . 'templates/');
define('AQUALUXE_ASSETS_URI', AQUALUXE_URI . 'assets/');
define('AQUALUXE_DIST_URI', AQUALUXE_ASSETS_URI . 'dist/');

/**
 * Autoloader for AquaLuxe theme classes
 */
spl_autoload_register(function ($class_name) {
    // Only autoload AquaLuxe classes
    if (strpos($class_name, 'AquaLuxe\\') !== 0) {
        return;
    }

    // Remove the namespace prefix
    $class_name = str_replace('AquaLuxe\\', '', $class_name);
    
    // Convert namespace separators to directory separators
    $class_file = str_replace('\\', '/', $class_name) . '.php';
    
    // Check in core directory first
    $core_file = AQUALUXE_CORE_DIR . 'classes/' . $class_file;
    if (file_exists($core_file)) {
        require_once $core_file;
        return;
    }
    
    // Check in modules directory
    $module_parts = explode('/', $class_file);
    if (count($module_parts) > 1) {
        $module_name = $module_parts[0];
        array_shift($module_parts);
        $module_file = AQUALUXE_MODULES_DIR . strtolower($module_name) . '/classes/' . implode('/', $module_parts);
        if (file_exists($module_file)) {
            require_once $module_file;
            return;
        }
    }
});

/**
 * Load the core theme class
 */
require_once AQUALUXE_CORE_DIR . 'classes/Theme.php';

/**
 * Initialize the theme
 */
\AquaLuxe\Theme::get_instance();