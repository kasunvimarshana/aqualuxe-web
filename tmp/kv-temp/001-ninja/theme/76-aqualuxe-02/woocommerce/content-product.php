<?php
defined('ABSPATH') || exit;

global $product;
if (empty($product) || !$product->is_visible()) return;
?>
<li <?php wc_product_class('group border rounded-lg overflow-hidden bg-white dark:bg-slate-800', $product); ?>>
  <a href="<?php the_permalink(); ?>" class="block aspect-square overflow-hidden">
    <?php woocommerce_template_loop_product_thumbnail(); ?>
  </a>
  <div class="p-4">
    <?php woocommerce_template_loop_product_title(); ?>
    <?php woocommerce_template_loop_price(); ?>
    <div class="mt-3 flex items-center gap-2">
      <?php woocommerce_template_loop_add_to_cart(); ?>
      <button class="aqlx-wishlist-btn text-sm" data-product-id="<?php echo esc_attr($product->get_id()); ?>" type="button">❤</button>
      <button class="aqlx-quick-view text-sm" data-product-id="<?php echo esc_attr($product->get_id()); ?>" type="button"><?php esc_html_e('Quick view', 'aqualuxe'); ?></button>
    </div>
  </div>
</li>
