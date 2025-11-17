<?php

declare(strict_types=1);

namespace AquaLuxe\Core\Modules\WooFallback;

use AquaLuxe\Core\Helpers;

class Bootstrap
{
    public static function init(): void
    {
        // Replace WooCommerce-specific templates/menus if WooCommerce is not active.
        Helpers::wp('add_filter', ['template_include', [self::class, 'template_include']]);
    }

    public static function template_include(string $template): string
    {
        $isWooActive = \class_exists('WooCommerce');
        $isShop = (bool) Helpers::wp('is_shop');
        if (! $isWooActive && $isShop) {
            $idx = Helpers::wp('get_index_template');
            return is_string($idx) && $idx ? $idx : $template;
        }
        return $template;
    }
}
