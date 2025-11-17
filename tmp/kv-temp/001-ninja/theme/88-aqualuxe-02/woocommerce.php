<?php
/**
 * WooCommerce wrapper template
 */

get_header(); ?>

<section class="container mx-auto px-4 py-10">
  <?php if (function_exists('woocommerce_content')) {
      call_user_func('woocommerce_content');
  } else { ?>
      <p><?php esc_html_e('WooCommerce is not active. Shop features are unavailable.', 'aqualuxe'); ?></p>
  <?php } ?>
</section>

<?php get_footer();
