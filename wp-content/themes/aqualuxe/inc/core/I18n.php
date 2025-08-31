<?php
namespace AquaLuxe\Core;

if (!defined('ABSPATH')) exit;

class I18n {
    public static function boot(): void {
        load_theme_textdomain('aqualuxe', get_template_directory() . '/languages');
    }
}
