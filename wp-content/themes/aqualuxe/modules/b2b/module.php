<?php
namespace AquaLuxe\Modules\B2B;

class Module {
    public static function init(): void {
        add_action('init', [__CLASS__, 'register_roles']);
        add_filter('woocommerce_product_get_price', [__CLASS__, 'tiered_pricing'], 20, 2);
    }

    public static function register_roles(): void {
        add_role('wholesale_customer', __('Wholesale Customer','aqualuxe'), [ 'read' => true ]);
    }

    public static function tiered_pricing($price, $product) {
        if (!class_exists('WooCommerce')) return $price;
        if (current_user_can('wholesale_customer')) {
            return (float) $price * 0.9; // simple 10% wholesale discount
        }
        return $price;
    }
}
