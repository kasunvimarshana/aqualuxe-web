<?php
/**
 * AquaLuxe Autoloader
 *
 * @package AquaLuxe
 */

namespace AquaLuxe;

/**
 * Class Autoloader
 */
class AquaLuxe_Autoloader {
    /**
     * Namespace separator
     *
     * @var string
     */
    const NS_SEPARATOR = '\\';

    /**
     * Constructor
     */
    public function __construct() {
        spl_autoload_register([$this, 'autoload']);
    }

    /**
     * Autoload function
     *
     * @param string $class_name Class name to load
     * @return void
     */
    public function autoload($class_name) {
        // Only handle our namespace
        if (strpos($class_name, 'AquaLuxe') !== 0) {
            return;
        }

        $file_path = $this->get_file_path_from_class_name($class_name);

        if (file_exists($file_path)) {
            require_once $file_path;
        }
    }

    /**
     * Get file path from class name
     *
     * @param string $class_name Class name
     * @return string File path
     */
    private function get_file_path_from_class_name($class_name) {
        $parts = explode(self::NS_SEPARATOR, $class_name);
        
        // Remove the first part (AquaLuxe)
        array_shift($parts);
        
        // Get the last part (class name)
        $class_file = array_pop($parts);
        
        // Convert class name to file name
        $class_file = 'class-' . strtolower(str_replace('_', '-', $class_file)) . '.php';
        
        // Build the path
        $path = AQUALUXE_DIR;
        
        if (!empty($parts)) {
            // Convert namespace parts to directory structure
            $namespace_path = strtolower(implode('/', $parts));
            $path .= $namespace_path . '/';
        }
        
        return $path . $class_file;
    }
}

// Initialize the autoloader
new AquaLuxe_Autoloader();