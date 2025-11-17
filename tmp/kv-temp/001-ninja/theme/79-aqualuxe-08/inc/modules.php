<?php
declare(strict_types=1);

namespace Aqualuxe\Inc;

/**
 * Register and load modules. Developers can filter the list to enable/disable.
 * We both hook into after_setup_theme and call immediately to ensure availability
 * even if templates access shortcodes very early. A guard prevents double-loads.
 */
function load_modules(): void {
    static $loaded = false;
    if ($loaded) { return; }
    $loaded = true;

    $modules = apply_filters('aqualuxe/modules', [
        'multilingual',
        'dark-mode',
        'services',
        'events',
        'subscriptions',
        'wholesale',
        'auctions',
        'affiliate',
        'currency',
        'wishlist',
        'quick-view',
        'filters',
        'contact',
        'search',
        'importer',
    ]);

    foreach ($modules as $module) {
        $file = AQUALUXE_MODULES . $module . '/init.php';
        if (file_exists($file)) {
            require_once $file;
        }
    }
}

// Load on standard hook
if (function_exists('add_action')) {
    add_action('after_setup_theme', __NAMESPACE__ . '\\load_modules');
}
// And load immediately for early availability (guarded to run once)
load_modules();
