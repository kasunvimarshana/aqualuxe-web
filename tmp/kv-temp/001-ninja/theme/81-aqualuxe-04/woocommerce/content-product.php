<?php
/**
 * The template for displaying product content within loops
 * Overrides to add Quick View and Wishlist buttons.
 */
if (!defined('ABSPATH')) { exit; }

global $product;
if (empty($product) || !$product->is_visible()) return;
?>
<li <?php if (function_exists('wc_product_class')) { call_user_func('wc_product_class', 'ax-card p-3', $product); } else { echo 'class="ax-card p-3"'; } ?>>
  <a href="<?php the_permalink(); ?>" class="block">
  <?php if (function_exists('woocommerce_get_product_thumbnail')) { echo call_user_func('woocommerce_get_product_thumbnail', 'card'); } ?>
    <h2 class="font-semibold mt-2 text-base"><?php the_title(); ?></h2>
  <?php if (function_exists('woocommerce_template_loop_price')) call_user_func('woocommerce_template_loop_price'); ?>
  </a>
  <div class="mt-2 flex items-center gap-2">
  <?php if (function_exists('woocommerce_template_loop_add_to_cart')) call_user_func('woocommerce_template_loop_add_to_cart'); ?>
    <button type="button" class="px-3 py-2 border rounded" data-qv="<?php echo esc_attr( get_the_ID() ); ?>"><?php esc_html_e('Quick View','aqualuxe'); ?></button>
    <button type="button" class="px-3 py-2 border rounded" aria-pressed="false" data-wishlist="<?php echo esc_attr( get_the_ID() ); ?>">♡</button>
  </div>
</li>
