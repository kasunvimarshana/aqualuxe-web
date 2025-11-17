<?php
namespace AquaLuxe\Core;

class Compat_WooCommerce {
    public static function is_active(): bool {
        return class_exists('WooCommerce');
    }

    public static function product_image_placeholder(): string {
        return '<div class="bg-slate-100 aspect-square"></div>';
    }
}
