<?php
namespace AquaLuxe;

class Modules {
    public static array $map = [
        'multilingual' => 'modules/multilingual/module.php',
        'darkmode' => 'modules/darkmode/module.php',
        'services' => 'modules/services/module.php',
        'events' => 'modules/events/module.php',
        'bookings' => 'modules/bookings/module.php',
        'subscriptions' => 'modules/subscriptions/module.php',
        'wholesale' => 'modules/wholesale/module.php',
        'auctions' => 'modules/auctions/module.php',
        'tradeins' => 'modules/tradeins/module.php',
        'affiliates' => 'modules/affiliates/module.php',
        'multicurrency' => 'modules/multicurrency/module.php',
        'filters' => 'modules/filters/module.php',
        'contact' => 'modules/contact/module.php',
        'franchise' => 'modules/franchise/module.php',
        'sustainability' => 'modules/sustainability/module.php',
        'vendors' => 'modules/vendors/module.php',
    ];

    public static function enabled(): array {
        $defaults = array_fill_keys(array_keys(self::$map), true);
        $enabled = \apply_filters('aqualuxe_modules_enabled', $defaults);
        return array_filter($enabled);
    }

    public static function init(): void {
        foreach (self::enabled() as $key => $on) {
            if (!$on || !isset(self::$map[$key])) continue;
            $path = \get_template_directory() . '/' . self::$map[$key];
            if (file_exists($path)) { require_once $path; }
        }
    }
}
