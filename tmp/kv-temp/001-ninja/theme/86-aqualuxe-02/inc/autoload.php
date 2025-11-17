<?php
/**
 * PSR-4 like autoloader for theme classes under AquaLuxe namespace.
 */

spl_autoload_register(function ($class) {
    if (strpos($class, 'AquaLuxe\\') !== 0) {
        return;
    }
    $path = str_replace('AquaLuxe\\', '', $class);
    $path = str_replace('\\', DIRECTORY_SEPARATOR, $path);
    $file = AQUALUXE_INC . DIRECTORY_SEPARATOR . strtolower($path) . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});
