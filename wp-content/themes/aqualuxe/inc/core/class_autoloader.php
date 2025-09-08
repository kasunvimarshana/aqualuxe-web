<?php
/**
 * Simple PSR-like autoloader for AquaLuxe namespace.
 */

namespace AquaLuxe\Core;

if (!defined('ABSPATH')) { exit; }

\spl_autoload_register(function ($class) {
    if (strpos($class, 'AquaLuxe\\') !== 0) {
        return;
    }
    $relative = substr($class, strlen('AquaLuxe\\'));
    $parts = explode('\\', $relative);
    $class_name = array_pop($parts);
    $dirs = array_map(function ($p) { return strtolower($p); }, $parts);
    $base_dir = trailingslashit(get_template_directory()) . 'inc/';
    $path_candidates = [];
    if (!empty($dirs)) {
        $dir_path = $base_dir . implode('/', $dirs) . '/class_' . strtolower($class_name) . '.php';
        $path_candidates[] = $dir_path;
    }
    $path_candidates[] = $base_dir . 'core/class_' . strtolower($class_name) . '.php';

    foreach ($path_candidates as $path) {
        if (file_exists($path)) {
            require_once $path;
            return;
        }
    }
});
