<?php defined('ABSPATH') || exit; global $product; ?>
<div class="p-4 max-w-2xl">
  <div class="flex gap-6">
    <div class="w-1/2">
      <?php if (has_post_thumbnail()) { the_post_thumbnail('large', ['class' => 'rounded']); } ?>
    </div>
    <div class="w-1/2">
      <h3 class="text-2xl font-bold mb-2"><?php the_title(); ?></h3>
      <div class="mb-3"><?php woocommerce_template_single_price(); ?></div>
      <div class="prose dark:prose-invert max-w-none mb-4"><?php echo wp_kses_post(wpautop(wp_trim_words(get_the_content(), 40))); ?></div>
      <?php woocommerce_template_single_add_to_cart(); ?>
    </div>
  </div>
</div>
