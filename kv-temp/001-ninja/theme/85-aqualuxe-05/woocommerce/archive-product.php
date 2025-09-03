<?php
/**
 * Product archive (shop) template override.
 * Uses default Woo hooks to preserve compatibility.
 */

defined('ABSPATH') || exit;

get_header('shop');
?>
<main id="primary" class="site-main container mx-auto px-4 py-8">
  <?php
  do_action('woocommerce_before_main_content');

  if (apply_filters('woocommerce_show_page_title', true)) {
      echo '<h1 class="page-title">' . woocommerce_page_title(false) . '</h1>';
  }

  do_action('woocommerce_archive_description');

  if (woocommerce_product_loop()) {
      do_action('woocommerce_before_shop_loop');

      woocommerce_product_loop_start();

      while (have_posts()) {
          the_post();
          do_action('woocommerce_shop_loop');
          wc_get_template_part('content', 'product');
      }

      woocommerce_product_loop_end();

      do_action('woocommerce_after_shop_loop');
  } else {
      do_action('woocommerce_no_products_found');
  }

  do_action('woocommerce_after_main_content');
  ?>
</main>
<?php get_footer('shop');
