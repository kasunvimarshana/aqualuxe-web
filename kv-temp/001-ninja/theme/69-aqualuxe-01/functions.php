<?php
/**
 * AquaLuxe Theme Functions
 * Loads core, modules, assets, and theme supports.
 */

// Core includes
theme_require_dir(__DIR__ . '/core');

theme_require_dir(__DIR__ . '/inc');

// Load security, SEO, and performance modules
require_once __DIR__ . '/inc/security/security.php';
require_once __DIR__ . '/inc/seo/seo.php';
if (file_exists(__DIR__ . '/inc/performance.php')) {
    require_once __DIR__ . '/inc/performance.php';
}

// Load modules
theme_require_dir(__DIR__ . '/modules', true);

// Helper autoloader
function theme_require_dir($dir, $modules = false) {
    if (!is_dir($dir)) return;
    foreach (glob($dir . '/*.php') as $file) {
        require_once $file;
    }
    if ($modules) {
        foreach (glob($dir . '/*', GLOB_ONLYDIR) as $subdir) {
            theme_require_dir($subdir, false);
        }
    }
}
