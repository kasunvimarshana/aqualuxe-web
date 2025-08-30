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
define('AQUALUXE_ASSETS_URI', trailingslashit(AQUALUXE_URI . 'assets/dist'));
define('AQUALUXE_CORE_DIR', trailingslashit(AQUALUXE_DIR . 'core'));
define('AQUALUXE_INC_DIR', trailingslashit(AQUALUXE_DIR . 'inc'));
define('AQUALUXE_MODULES_DIR', trailingslashit(AQUALUXE_DIR . 'modules'));
define('AQUALUXE_TEMPLATES_DIR', trailingslashit(AQUALUXE_DIR . 'templates'));

// Minimum PHP version check
if (version_compare(PHP_VERSION, '7.4', '<')) {
    require_once AQUALUXE_INC_DIR . 'functions/php-compat.php';
    return;
}

// Autoloader
require_once AQUALUXE_INC_DIR . 'classes/class-aqualuxe-autoloader.php';

// Initialize the theme
require_once AQUALUXE_CORE_DIR . 'class-aqualuxe-theme.php';

// Initialize the theme
function aqualuxe_init() {
    return \AquaLuxe\Core\AquaLuxe_Theme::get_instance();
}

// Start the theme
aqualuxe_init();

/**
 * Get asset path with version
 *
 * @param string $path Asset path
 * @return string Versioned asset path
 */
function aqualuxe_asset($path) {
    static $manifest = null;
    
    if (is_null($manifest)) {
        $manifest_path = AQUALUXE_DIR . 'assets/dist/mix-manifest.json';
        
        if (file_exists($manifest_path)) {
            $manifest = json_decode(file_get_contents($manifest_path), true);
        } else {
            $manifest = [];
        }
    }
    
    $path = '/' . ltrim($path, '/');
    
    if (isset($manifest[$path])) {
        return AQUALUXE_ASSETS_URI . ltrim($manifest[$path], '/');
    }
    
    return AQUALUXE_ASSETS_URI . ltrim($path, '/');
}