<?php
namespace AquaLuxe\Core;

if (!defined('ABSPATH')) exit;

class Config {
    private static $instance;
    private array $options;

    private function __construct() {
        $defaults = [
            'modules' => [
                // Toggle modules by fully-qualified class names
                'AquaLuxe\\Modules\\DarkMode\\Module' => true,
                'AquaLuxe\\Modules\\Multilingual\\Module' => true,
                'AquaLuxe\\Modules\\DemoImporter\\Module' => true,
                'AquaLuxe\\Modules\\Woo\\Module' => true,
                'AquaLuxe\\Modules\\Wishlist\\Module' => true,
                'AquaLuxe\\Modules\\QuickView\\Module' => true,
                'AquaLuxe\\Modules\\Filtering\\Module' => true,
                'AquaLuxe\\Modules\\MultiCurrency\\Module' => true,
                'AquaLuxe\\Modules\\Bookings\\Module' => false,
                'AquaLuxe\\Modules\\Events\\Module' => false,
                'AquaLuxe\\Modules\\Subscriptions\\Module' => false,
                'AquaLuxe\\Modules\\Wholesale\\Module' => false,
                'AquaLuxe\\Modules\\Auctions\\Module' => false,
                'AquaLuxe\\Modules\\Franchise\\Module' => false,
                'AquaLuxe\\Modules\\RnD\\Module' => false,
                'AquaLuxe\\Modules\\Affiliate\\Module' => false,
            ],
        ];
        $this->options = apply_filters('aqualuxe/config', $defaults);
    }

    public static function instance(): self {
        if (!self::$instance) self::$instance = new self();
        return self::$instance;
    }

    public function get_enabled_modules(): array {
        $enabled = [];
        foreach (($this->options['modules'] ?? []) as $class => $on) {
            if ($on) $enabled[] = $class;
        }
        return $enabled;
    }

    public function is_module_enabled(string $class): bool {
        return !empty($this->options['modules'][$class]);
    }
}
