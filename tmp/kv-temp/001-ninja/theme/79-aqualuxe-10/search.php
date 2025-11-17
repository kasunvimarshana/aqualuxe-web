<?php get_header(); ?>
<div class="container max-w-7xl mx-auto px-4 py-8">
  <h1 class="text-3xl font-semibold mb-6"><?php printf(esc_html__('Search results for "%s"', 'aqualuxe'), esc_html(get_search_query())); ?></h1>
  <?php if (have_posts()): ?>
    <div class="grid md:grid-cols-3 gap-6">
    <?php while (have_posts()): the_post(); ?>
      <?php if ('product' === get_post_type()) : ?>
        <?php get_template_part('template-parts/product', 'card', ['product_id' => get_the_ID()]); ?>
      <?php else: ?>
        <a href="<?php the_permalink(); ?>" class="block border rounded p-4 hover-lift">
          <h2 class="font-semibold mb-2"><?php the_title(); ?></h2>
          <p class="text-sm opacity-70"><?php echo esc_html(wp_strip_all_tags(get_the_excerpt())); ?></p>
        </a>
      <?php endif; ?>
    <?php endwhile; ?>
    </div>
  <?php else: ?>
    <p><?php esc_html_e('No results found.', 'aqualuxe'); ?></p>
    <?php if (class_exists('WooCommerce')): ?>
      <div class="mt-8">
        <h2 class="text-2xl font-semibold mb-4"><?php esc_html_e('Popular products', 'aqualuxe'); ?></h2>
        <div class="grid gap-6 md:grid-cols-3">
          <?php if (function_exists('wc_get_products')) { $products = call_user_func('wc_get_products', ['limit'=>6,'status'=>'publish','orderby'=>'date','order'=>'DESC']);
            foreach ($products as $prod) { if (! $prod) continue; get_template_part('template-parts/product', 'card', ['product_id' => $prod->get_id()]); }
          } ?>
        </div>
      </div>
    <?php endif; ?>
  <?php endif; ?>
</div>
<?php get_footer(); ?>
