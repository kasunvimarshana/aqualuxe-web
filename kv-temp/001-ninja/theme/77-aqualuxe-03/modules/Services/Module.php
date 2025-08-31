<?php
namespace AquaLuxe\Modules\Services;

if (!defined('ABSPATH')) exit;

class Module {
    public static function boot(): void {
        add_shortcode('aqlx_services', [__CLASS__, 'render']);
    }

    public static function render(): string {
        return '<div class="grid md:grid-cols-2 gap-6">'
            . '<div class="p-4 border rounded"><h3>' . esc_html__('Design & Installation','aqualuxe') . '</h3><a class="mt-2 inline-block px-4 py-2 bg-sky-600 text-white rounded" href="#">' . esc_html__('Request Quote','aqualuxe') . '</a></div>'
            . '<div class="p-4 border rounded"><h3>' . esc_html__('Maintenance Plans','aqualuxe') . '</h3><a class="mt-2 inline-block px-4 py-2 bg-sky-600 text-white rounded" href="#">' . esc_html__('Book Now','aqualuxe') . '</a></div>'
            . '</div>';
    }
}
