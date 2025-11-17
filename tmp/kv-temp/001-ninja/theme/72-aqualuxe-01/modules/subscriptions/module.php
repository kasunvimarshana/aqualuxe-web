<?php
namespace AquaLuxe\Modules\Subscriptions;

class Module {
    public static function init(): void {
        // If Woo Subscriptions is active, you could register additional hooks here.
        add_shortcode('aqlx_membership_notice', [__CLASS__, 'notice']);
    }
    public static function notice(): string {
        if (!class_exists('WooCommerce')) return '';
        $txt = __('Members enjoy exclusive discounts and early access to rare species.','aqualuxe');
        return '<div class="p-4 rounded bg-sky-50 border border-sky-200">' . esc_html($txt) . '</div>';
    }
}
