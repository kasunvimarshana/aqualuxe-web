<?php defined('ABSPATH') || exit; global $product; ?>
<li <?php wc_product_class('group bg-white dark:bg-gray-900 rounded-lg border border-gray-100 dark:border-gray-800 overflow-hidden', $product); ?>>
  <a href="<?php the_permalink(); ?>" class="block">
    <?php woocommerce_template_loop_product_thumbnail(); ?>
    <div class="p-4">
      <?php woocommerce_template_loop_product_title(); ?>
      <?php woocommerce_template_loop_price(); ?>
    </div>
  </a>
  <div class="p-4 pt-0 flex gap-2">
    <?php woocommerce_template_loop_add_to_cart(); ?>
    <button class="ml-auto text-sm underline" data-wishlist-toggle data-product-id="<?php echo esc_attr($product->get_id()); ?>"><?php esc_html_e('Wishlist', 'aqualuxe'); ?></button>
    <button class="text-sm underline" data-quickview data-product-id="<?php echo esc_attr($product->get_id()); ?>"><?php esc_html_e('Quick View', 'aqualuxe'); ?></button>
  </div>
</li>
