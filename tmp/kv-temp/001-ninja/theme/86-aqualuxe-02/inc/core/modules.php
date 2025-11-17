<?php
namespace AquaLuxe\Core;

class Modules
{
    public static function bootstrap(): void
    {
    $config = \apply_filters('aqualuxe/modules/config', [
            'dark-mode' => true,
            'multilingual' => true,
            'wishlist' => true,
            'seo' => true,
            'multicurrency' => false,
            'classifieds' => false,
            'vendors' => false,
            'roles' => false,
            'performance' => true,
            'sitemap' => true,
        ]);

        foreach ($config as $slug => $enabled) {
            if (!$enabled) {
                continue;
            }
            $file = AQUALUXE_MODULES . '/' . $slug . '/module.php';
            if (file_exists($file)) {
                require_once $file;
            }
        }
    }
}
