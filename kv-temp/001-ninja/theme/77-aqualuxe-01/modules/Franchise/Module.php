<?php
namespace AquaLuxe\Modules\Franchise;
if (!defined('ABSPATH')) exit;
class Module {
  public static function boot(): void {
    add_shortcode('aqlx_franchise', [__CLASS__, 'render']);
  }
  public static function render(): string {
    return '<div class="p-4 border rounded"><h3 class="text-xl font-semibold">' . esc_html__('Franchise & Licensing','aqualuxe') . '</h3>'
      . '<p class="opacity-80">' . esc_html__('Apply to become an AquaLuxe partner.','aqualuxe') . '</p>'
      . '<a class="mt-2 inline-block px-4 py-2 bg-sky-600 text-white rounded" href="#">' . esc_html__('Start Application','aqualuxe') . '</a></div>';
  }
}
