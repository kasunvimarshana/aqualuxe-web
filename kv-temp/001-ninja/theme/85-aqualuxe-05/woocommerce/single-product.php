<?php
/**
 * Single product template override.
 * Uses default Woo hooks to preserve compatibility.
 */

defined('ABSPATH') || exit;

get_header('shop');
?>
<main id="primary" class="site-main container mx-auto px-4 py-8">
  <?php
  do_action('woocommerce_before_main_content');

  while (have_posts()) {
      the_post();
      wc_get_template_part('content', 'single-product');
  }

  do_action('woocommerce_after_main_content');
  ?>
</main>
<?php get_footer('shop');
