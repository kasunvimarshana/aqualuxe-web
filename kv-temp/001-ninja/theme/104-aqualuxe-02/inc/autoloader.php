<?php
/**
 * Theme autoloader
 * 
 * @package AquaLuxe
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Autoloader for theme classes
 */
spl_autoload_register(function ($class_name) {
    // Check if this is an AquaLuxe class
    if (strpos($class_name, 'AquaLuxe_') !== 0) {
        return;
    }
    
    // Convert class name to file name
    $class_file = strtolower(str_replace('_', '-', $class_name));
    $class_file = str_replace('aqualuxe-', '', $class_file);
    $class_file = 'class-' . $class_file . '.php';
    
    // Possible directories to search
    $directories = [
        AQUALUXE_INC_DIR,
        AQUALUXE_CORE_DIR,
        AQUALUXE_MODULES_DIR
    ];
    
    // Search in modules subdirectories
    if (is_dir(AQUALUXE_MODULES_DIR)) {
        $modules = scandir(AQUALUXE_MODULES_DIR);
        foreach ($modules as $module) {
            if ($module !== '.' && $module !== '..' && is_dir(AQUALUXE_MODULES_DIR . '/' . $module)) {
                $directories[] = AQUALUXE_MODULES_DIR . '/' . $module;
            }
        }
    }
    
    // Try to find and include the class file
    foreach ($directories as $directory) {
        $file_path = $directory . '/' . $class_file;
        if (file_exists($file_path)) {
            require_once $file_path;
            return;
        }
    }
});