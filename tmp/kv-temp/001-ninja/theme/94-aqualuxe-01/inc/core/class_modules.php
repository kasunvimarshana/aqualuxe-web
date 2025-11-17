<?php
namespace AquaLuxe\Core;

if (!defined('ABSPATH')) { exit; }

class Modules {
    public static function init(): void {
        add_action('after_setup_theme', [__CLASS__, 'load_modules'], 20);
    }

    public static function load_modules(): void {
        $modules_dir = AQUALUXE_PATH . 'inc/modules';
        $default_enabled = [
            'dark_mode' => true,
            'woocommerce' => true,
            'demo_importer' => true,
            'i18n' => true,
            'multicurrency' => true,
            'wishlist' => true,
            'quick_view' => true,
        ];
        $enabled = apply_filters('aqualuxe/modules/enabled', $default_enabled);

        foreach (glob($modules_dir . '/*', GLOB_ONLYDIR) as $module_path) {
            $slug = basename($module_path);
            if (!isset($enabled[$slug]) || !$enabled[$slug]) {
                continue;
            }
            $entry = trailingslashit($module_path) . 'module.php';
            if (file_exists($entry)) {
                require_once $entry;
                $nsSegment = str_replace('_', '', ucwords($slug, '_'));
                $class = "AquaLuxe\\Modules\\{$nsSegment}\\{$nsSegment}";
                if (class_exists($class) && method_exists($class, 'init')) {
                    \call_user_func([$class, 'init']);
                } else {
                    $fn = "AquaLuxe\\Modules\\{$nsSegment}\\init";
                    if (function_exists($fn)) { \call_user_func($fn); }
                }
            }
        }
    }
}
