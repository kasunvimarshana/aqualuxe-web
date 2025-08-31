<?php get_header(); ?>
<div class="container max-w-7xl mx-auto px-4 py-20 text-center">
  <h1 class="text-5xl font-bold mb-4"><?php esc_html_e('Page not found', 'aqualuxe'); ?></h1>
  <p class="opacity-80 mb-6"><?php esc_html_e('The page you’re looking for doesn’t exist. Try searching.', 'aqualuxe'); ?></p>
  <?php get_search_form(); ?>
  <?php if (class_exists('WooCommerce')): ?>
    <div class="mt-10 text-left">
      <h2 class="text-2xl font-semibold mb-4"><?php esc_html_e('Popular products', 'aqualuxe'); ?></h2>
      <div class="grid gap-6 md:grid-cols-3">
        <?php
        if (function_exists('wc_get_products')) {
          $products = call_user_func('wc_get_products', ['limit' => 6, 'status' => 'publish', 'orderby' => 'date', 'order' => 'DESC']);
          foreach ($products as $prod) {
            if (! $prod) { continue; }
            get_template_part('template-parts/product', 'card', ['product_id' => $prod->get_id()]);
          }
        }
        ?>
      </div>
    </div>
  <?php endif; ?>
</div>
<?php get_footer(); ?>
