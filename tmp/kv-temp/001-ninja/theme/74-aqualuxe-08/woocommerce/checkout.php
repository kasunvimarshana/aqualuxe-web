<?php defined('ABSPATH') || exit; get_header('shop'); ?>
<main id="content" class="container mx-auto px-4 py-10" tabindex="-1">
  <?php do_action('woocommerce_before_main_content'); ?>
  <?php woocommerce_content(); ?>
  <?php do_action('woocommerce_after_main_content'); ?>
</main>
<?php get_footer('shop'); ?>
