<?php
namespace AquaLuxe\Modules\Multivendor;

class Module {
    public static function init(): void {
        // Detect popular multivendor plugins and adjust templates minimally.
        add_filter('body_class', [__CLASS__, 'classes']);
    }
    public static function classes($classes) {
        if (class_exists('WeDevs_Dokan')) $classes[] = 'has-dokan';
        if (defined('WCV_VERSION')) $classes[] = 'has-wcvendors';
        if (defined('WCMP_PLUGIN_TOKEN')) $classes[] = 'has-wcmp';
        return $classes;
    }
}
