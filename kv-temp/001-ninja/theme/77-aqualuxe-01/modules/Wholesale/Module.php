<?php
namespace AquaLuxe\Modules\Wholesale;
if (!defined('ABSPATH')) exit;
class Module {
  public static function boot(): void {
    add_shortcode('aqlx_wholesale_app', [__CLASS__, 'render']);
  }
  public static function render(): string {
    return '<form class="max-w-xl p-4 border rounded"><h3 class="text-xl font-semibold mb-2">' . esc_html__('Wholesale Application','aqualuxe') . '</h3>'
      . '<label class="block mb-2">' . esc_html__('Business Name','aqualuxe') . '<input class="mt-1 w-full border rounded px-3 py-2" required></label>'
      . '<label class="block mb-2">' . esc_html__('Email','aqualuxe') . '<input type="email" class="mt-1 w-full border rounded px-3 py-2" required></label>'
      . '<button class="mt-2 px-4 py-2 bg-sky-600 text-white rounded">' . esc_html__('Submit','aqualuxe') . '</button></form>';
  }
}
