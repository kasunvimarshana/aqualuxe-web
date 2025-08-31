<section class="container max-w-7xl mx-auto px-4 py-12">
  <h2 class="text-2xl font-semibold mb-6"><?php esc_html_e('Featured', 'aqualuxe'); ?></h2>
  <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
    <?php if (class_exists('WooCommerce')): ?>
      <?php
      $q = new WP_Query([
        'post_type' => 'product',
        'posts_per_page' => 8
      ]);
      while ($q->have_posts()): $q->the_post(); global $product; ?>
        <a href="<?php the_permalink(); ?>" class="block border rounded p-3 hover-lift card">
          <?php if (has_post_thumbnail()) { the_post_thumbnail('medium', ['class' => 'w-full h-auto rounded mb-2', 'loading' => 'lazy']); } ?>
          <div class="font-semibold"><?php the_title(); ?></div>
          <div class="text-primary"><?php if (is_object($product)) echo $product->get_price_html(); ?></div>
        </a>
      <?php endwhile; wp_reset_postdata(); ?>
    <?php else: ?>
      <?php $q = new WP_Query(['posts_per_page' => 8]); while ($q->have_posts()): $q->the_post(); ?>
        <a href="<?php the_permalink(); ?>" class="block border rounded p-3 hover-lift card">
          <?php if (has_post_thumbnail()) { the_post_thumbnail('medium', ['class' => 'w-full h-auto rounded mb-2', 'loading' => 'lazy']); } ?>
          <div class="font-semibold"><?php the_title(); ?></div>
        </a>
      <?php endwhile; wp_reset_postdata(); ?>
    <?php endif; ?>
  </div>
</section>
