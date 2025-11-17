<?php
namespace AquaLuxe\Core;

if (!defined('ABSPATH')) exit;

class Blocks {
    public static function boot(): void {
        // Register pattern categories and simple block styles, minimal and optional.
        if (function_exists('register_block_pattern_category')) {
            register_block_pattern_category('aqualuxe', ['label' => __('AquaLuxe', 'aqualuxe')]);
        }
    }
}
