<?php
/**
 * Simple PSR-4-like autoloader for AquaLuxe theme classes.
 */

declare(strict_types=1);

if (! defined('ABSPATH')) {
    exit;
}

spl_autoload_register(static function ($class): void {
    if (strpos($class, 'AquaLuxe\\') !== 0) {
        return;
    }

    $relative = str_replace(['AquaLuxe\\', '\\'], ['', '/'], $class);
    $path = AQUALUXE_THEME_DIR . 'inc/' . $relative . '.php';
    if (file_exists($path)) {
        require_once $path;
    }
});
