<?php defined('ABSPATH') || exit; get_header('shop'); ?>
<main class="container mx-auto px-4 py-10 grid md:grid-cols-[240px_1fr] gap-8">
  <?php do_action('woocommerce_before_main_content'); ?>
  <nav class="space-y-2" aria-label="Account Navigation">
    <?php do_action('woocommerce_account_navigation'); ?>
  </nav>
  <section>
    <?php do_action('woocommerce_account_content'); ?>
  </section>
  <?php do_action('woocommerce_after_main_content'); ?>
</main>
<?php get_footer('shop'); ?>
