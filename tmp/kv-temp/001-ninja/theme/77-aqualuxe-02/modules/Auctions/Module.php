<?php
namespace AquaLuxe\Modules\Auctions;
if (!defined('ABSPATH')) exit;
class Module {
  public static function boot(): void {
    add_shortcode('aqlx_auctions', [__CLASS__, 'render']);
  }
  public static function render(): string {
    return '<div class="grid md:grid-cols-3 gap-6">'
      . '<div class="p-4 border rounded"><h3>' . esc_html__('Premium Koi Auction','aqualuxe') . '</h3><p>' . esc_html__('Bidding opens Friday 7PM.','aqualuxe') . '</p></div>'
      . '<div class="p-4 border rounded"><h3>' . esc_html__('Rare Discus Pair','aqualuxe') . '</h3><p>' . esc_html__('Live stream this weekend.','aqualuxe') . '</p></div>'
      . '<div class="p-4 border rounded"><h3>' . esc_html__('Planted Tank Setup','aqualuxe') . '</h3><p>' . esc_html__('Timed auction next week.','aqualuxe') . '</p></div>'
      . '</div>';
  }
}
