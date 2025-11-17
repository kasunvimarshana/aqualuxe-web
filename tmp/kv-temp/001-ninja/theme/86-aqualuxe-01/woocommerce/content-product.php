<?php
defined('ABSPATH') || exit;
global $product;
if (empty($product) || !$product->is_visible()) { return; }
?>
<li <?php wc_product_class('card border border-slate-200 dark:border-slate-800 rounded-lg overflow-hidden', $product); ?>>
  <a href="<?php the_permalink(); ?>" class="block">
    <?php woocommerce_template_loop_product_thumbnail(); ?>
    <div class="p-4">
      <h2 class="woocommerce-loop-product__title font-semibold"><?php the_title(); ?></h2>
      <?php woocommerce_template_loop_price(); ?>
    </div>
  </a>
</li>
