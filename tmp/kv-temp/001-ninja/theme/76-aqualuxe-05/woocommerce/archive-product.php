<?php
defined('ABSPATH') || exit;
get_header('shop'); ?>

<div class="<?php echo esc_attr(aqlx_container_class()); ?> py-8">
  <header class="woocommerce-products-header mb-6">
    <?php if (apply_filters('woocommerce_show_page_title', true)) : ?>
      <h1 class="text-3xl font-bold"><?php woocommerce_page_title(); ?></h1>
    <?php endif; ?>
    <?php do_action('woocommerce_archive_description'); ?>
  </header>

  <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
    <aside id="aqlx-shop-filters" class="md:col-span-1" aria-label="Shop filters">
      <?php if (function_exists('wc_get_price_decimals')) : ?>
        <form class="mb-6 space-y-2" method="get" action="">
          <div>
            <label for="aqlx-min-price" class="block text-sm font-medium mb-1"><?php esc_html_e('Min price', 'aqualuxe'); ?></label>
            <input id="aqlx-min-price" class="w-full border rounded px-3 py-2" type="number" step="0.01" name="min_price" value="<?php echo isset($_GET['min_price']) ? esc_attr(wp_unslash($_GET['min_price'])) : ''; ?>">
          </div>
          <div>
            <label for="aqlx-max-price" class="block text-sm font-medium mb-1"><?php esc_html_e('Max price', 'aqualuxe'); ?></label>
            <input id="aqlx-max-price" class="w-full border rounded px-3 py-2" type="number" step="0.01" name="max_price" value="<?php echo isset($_GET['max_price']) ? esc_attr(wp_unslash($_GET['max_price'])) : ''; ?>">
          </div>
          <?php // Preserve other GET params ?><div>
            <?php foreach ($_GET as $key => $val) { if (in_array($key, ['min_price','max_price','submit'], true)) continue; if (is_array($val)) { foreach ($val as $v) { echo '<input type="hidden" name="' . esc_attr($key) . '[]" value="' . esc_attr(wp_unslash($v)) . '" />'; } } else { echo '<input type="hidden" name="' . esc_attr($key) . '" value="' . esc_attr(wp_unslash($val)) . '" />'; } } ?>
          </div>
          <button type="submit" class="w-full bg-sky-600 hover:bg-sky-700 text-white px-4 py-2 rounded"><?php esc_html_e('Apply', 'aqualuxe'); ?></button>
        </form>
      <?php endif; ?>
      <?php if (is_user_logged_in()) : ?>
        <div class="mb-6">
          <h3 class="text-base font-semibold mb-2"><?php esc_html_e('Your saved filters', 'aqualuxe'); ?></h3>
          <?php echo do_shortcode('[aqlx_saved_filters]'); ?>
        </div>
      <?php endif; ?>
      <?php if (is_active_sidebar('shop-filters')) : ?>
        <?php dynamic_sidebar('shop-filters'); ?>
      <?php else : ?>
        <p class="text-sm text-slate-500"><?php esc_html_e('Add WooCommerce filtering widgets to the “Shop Filters” sidebar.', 'aqualuxe'); ?></p>
      <?php endif; ?>
    </aside>

  <section id="aqlx-shop-grid" class="md:col-span-3" aria-live="polite" aria-busy="false">
      <?php if (function_exists('aqlx_active_filters')) : $af = aqlx_active_filters(); if (!empty($af['chips'])) : ?>
        <div class="mb-4 flex flex-wrap items-center gap-2">
          <?php foreach ($af['chips'] as $chip) : ?>
            <a class="inline-flex items-center gap-1 px-2 py-1 text-sm rounded-full bg-slate-100 dark:bg-slate-800 hover:bg-slate-200" href="<?php echo esc_url($chip['url']); ?>">
              <span><?php echo esc_html($chip['label']); ?></span>
              <span aria-hidden="true">&times;</span>
            </a>
          <?php endforeach; ?>
          <a class="ml-auto text-sm text-sky-700 underline" href="<?php echo esc_url($af['clear_url']); ?>"><?php esc_html_e('Clear all', 'aqualuxe'); ?></a>
        </div>
      <?php endif; endif; ?>
      <?php if (woocommerce_product_loop()) : ?>
        <?php do_action('woocommerce_before_shop_loop'); ?>
        <?php woocommerce_product_loop_start(); ?>
        <?php if (wc_get_loop_prop('total')) : while (have_posts()) : the_post(); ?>
          <?php wc_get_template_part('content', 'product'); ?>
        <?php endwhile; endif; ?>
        <?php woocommerce_product_loop_end(); ?>
        <?php do_action('woocommerce_after_shop_loop'); ?>
      <?php else : ?>
        <?php do_action('woocommerce_no_products_found'); ?>
      <?php endif; ?>
    </section>
  </div>
</div>

<?php get_footer('shop');
