<?php
namespace AquaLuxe\Modules\RnD;
if (!defined('ABSPATH')) exit;
class Module {
  public static function boot(): void {
    add_shortcode('aqlx_rnd', [__CLASS__, 'render']);
  }
  public static function render(): string {
    return '<section class="prose dark:prose-invert"><h2>' . esc_html__('R&D & Sustainability','aqualuxe') . '</h2><p>' . esc_html__('Innovations in aquaculture, ethical sourcing, and low-impact logistics.','aqualuxe') . '</p></section>';
  }
}
