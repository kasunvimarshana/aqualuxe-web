<?php
/**
 * AquaLuxe Autoloader
 *
 * @package AquaLuxe
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * AquaLuxe Autoloader Class
 */
class AquaLuxe_Autoloader {
    /**
     * Classes map
     *
     * @var array
     */
    private static $classes_map = [];

    /**
     * Run autoloader
     *
     * Register autoloader with spl autoload register.
     */
    public static function run() {
        spl_autoload_register([__CLASS__, 'autoload']);
    }

    /**
     * Autoload
     *
     * For classes that are prefixed with AquaLuxe_.
     *
     * @param string $class Class name.
     */
    public static function autoload($class) {
        if (0 !== strpos($class, 'AquaLuxe_')) {
            return;
        }

        if (isset(self::$classes_map[$class])) {
            $filename = AQUALUXE_DIR . self::$classes_map[$class];
        } else {
            $filename = self::get_filename_from_class($class);
        }

        if (is_readable($filename)) {
            require_once $filename;
        }
    }

    /**
     * Get filename from class
     *
     * Convert class name to filename.
     *
     * @param string $class Class name.
     * @return string Filename.
     */
    private static function get_filename_from_class($class) {
        // Remove AquaLuxe_ prefix
        $class_name = str_replace('AquaLuxe_', '', $class);
        
        // Convert to lowercase
        $class_name = strtolower($class_name);
        
        // Replace underscores with hyphens
        $class_name = str_replace('_', '-', $class_name);
        
        // Split into parts
        $parts = explode('-', $class_name);
        
        // Get the last part as the filename
        $filename = array_pop($parts);
        
        // Get the path
        $path = implode('/', $parts);
        
        // Build the full path
        if (!empty($path)) {
            $path = 'core/classes/' . $path . '/' . $filename . '.php';
        } else {
            $path = 'core/classes/' . $filename . '.php';
        }
        
        return AQUALUXE_DIR . $path;
    }

    /**
     * Add class to map
     *
     * @param string $class Class name.
     * @param string $path Path to file.
     */
    public static function add_class($class, $path) {
        self::$classes_map[$class] = $path;
    }
}

// Run the autoloader
AquaLuxe_Autoloader::run();

// Add core classes to map
AquaLuxe_Autoloader::add_class('AquaLuxe_Module', 'core/classes/module.php');
AquaLuxe_Autoloader::add_class('AquaLuxe_Assets', 'core/classes/assets.php');
AquaLuxe_Autoloader::add_class('AquaLuxe_Customizer', 'core/classes/customizer.php');
AquaLuxe_Autoloader::add_class('AquaLuxe_Walker_Nav_Menu', 'core/classes/walker-nav-menu.php');