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
</div>

<?php get_footer('shop');
