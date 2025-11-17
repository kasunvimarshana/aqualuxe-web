<?php
namespace AquaLuxe;

defined('ABSPATH') || exit;

class Modules
{
    public static function boot(): void
    {
        $dir = AQUALUXE_PATH . 'modules/';
        if (!is_dir($dir)) return;
        $folders = glob($dir . '*', GLOB_ONLYDIR) ?: [];
        // Determine enabled modules
        $enabled = get_theme_mod('aqlx_modules_enabled', []);
        if (!is_array($enabled) || empty($enabled)) {
            $enabled = array_map('basename', $folders); // enable all by default
        }
        $enabled = apply_filters('aqualuxe/modules_enabled', $enabled);
        foreach ($folders as $folder) {
            $name = basename($folder);
            if (!in_array($name, $enabled, true)) continue;
            $bootstrap = $folder . '/bootstrap.php';
            if (file_exists($bootstrap)) require_once $bootstrap;
        }
    }
}
