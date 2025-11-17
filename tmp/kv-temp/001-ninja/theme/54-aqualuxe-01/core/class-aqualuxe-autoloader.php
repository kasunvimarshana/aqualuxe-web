<?php
/**
 * AquaLuxe Autoloader
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * AquaLuxe Autoloader Class
 * 
 * Handles autoloading of theme classes
 */
class AquaLuxe_Autoloader {
    /**
     * Class constructor
     */
    public function __construct() {
        spl_autoload_register([$this, 'autoload']);
    }

    /**
     * Autoload callback
     *
     * @param string $class_name The name of the class to load
     */
    public function autoload($class_name) {
        // Check if the class is a theme class
        if (strpos($class_name, 'AquaLuxe_') !== 0) {
            return;
        }

        // Convert class name to filename
        $file_name = 'class-' . str_replace('_', '-', strtolower($class_name)) . '.php';
        
        // Define paths to check
        $paths = [
            AQUALUXE_CORE_DIR . 'classes/',
            AQUALUXE_CORE_DIR . 'helpers/',
            AQUALUXE_CORE_DIR . 'hooks/',
        ];
        
        // Check module paths
        $module_name = $this->get_module_name_from_class($class_name);
        if ($module_name) {
            $paths[] = AQUALUXE_MODULES_DIR . $module_name . '/classes/';
            $paths[] = AQUALUXE_MODULES_DIR . $module_name . '/helpers/';
            $paths[] = AQUALUXE_MODULES_DIR . $module_name . '/hooks/';
        }
        
        // Look for the file in the paths
        foreach ($paths as $path) {
            $file = $path . $file_name;
            
            if (file_exists($file)) {
                require_once $file;
                return;
            }
        }
    }
    
    /**
     * Get module name from class name
     *
     * @param string $class_name The name of the class
     * @return string|null The module name or null if not a module class
     */
    private function get_module_name_from_class($class_name) {
        // Check if class name follows module pattern: AquaLuxe_ModuleName_*
        if (preg_match('/^AquaLuxe_([A-Za-z]+)_/', $class_name, $matches)) {
            $module_name = strtolower($matches[1]);
            
            // Convert camelCase to kebab-case
            $module_name = preg_replace('/([a-z])([A-Z])/', '$1-$2', $module_name);
            
            return strtolower($module_name);
        }
        
        return null;
    }
}