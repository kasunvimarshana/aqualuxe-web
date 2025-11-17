<?php
namespace AquaLuxe\Core;

if (!defined('ABSPATH')) { exit; }

class Security {
    public static function init(): void {
        \add_filter('the_generator', '__return_empty_string');
        \add_action('init', [__CLASS__, 'disable_emoji']);
        \add_action('send_headers', [__CLASS__, 'headers']);
    }
    public static function disable_emoji(): void {
    \remove_action('wp_head', 'print_emoji_detection_script', 7);
    \remove_action('admin_print_scripts', 'print_emoji_detection_script');
    \remove_action('wp_print_styles', 'print_emoji_styles');
    \remove_action('admin_print_styles', 'print_emoji_styles');
    }
    public static function headers(): void {
    \header('X-Content-Type-Options: nosniff');
    \header('Referrer-Policy: no-referrer-when-downgrade');
    }
}
