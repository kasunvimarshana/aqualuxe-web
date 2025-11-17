<?php
namespace AquaLuxe\Modules\Subscriptions;

if (!defined('ABSPATH')) exit;

class Module {
    public static function boot(): void {
        add_shortcode('aqlx_subscriptions', [__CLASS__, 'render']);
    }

    public static function render(): string {
        return '<div class="p-4 border rounded"><h3>' . esc_html__('Membership Tiers', 'aqualuxe') . '</h3><ul><li>Silver</li><li>Gold</li><li>Platinum</li></ul></div>';
    }
}
