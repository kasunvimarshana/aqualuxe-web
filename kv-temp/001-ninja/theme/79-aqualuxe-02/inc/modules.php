<?php
declare(strict_types=1);

namespace Aqualuxe\Inc;

/**
 * Register and load modules. Developers can filter the list to enable/disable.
 */
add_action('after_setup_theme', static function (): void {
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
        'importer',
    ]);

    foreach ($modules as $module) {
        $file = AQUALUXE_MODULES . $module . '/init.php';
        if (file_exists($file)) {
            require_once $file;
        }
    }
});
