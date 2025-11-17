<?php
namespace AquaLuxe\Core;

if (!defined('ABSPATH')) { exit; }

class Roles {
    public static function init(): void {
        \add_action('after_setup_theme', [__CLASS__, 'register']);
    }
    public static function register(): void {
        \add_role('wholesale_customer', __('Wholesale Customer','aqualuxe'), ['read'=>true]);
        \add_role('vendor', __('Vendor','aqualuxe'), ['read'=>true, 'edit_products'=>true]);
    }
}
