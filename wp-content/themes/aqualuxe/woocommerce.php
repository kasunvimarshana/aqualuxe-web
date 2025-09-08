<?php if (!defined('ABSPATH')) { exit; } get_header(); ?>
<main id="main" class="site_main" role="main">
  <div class="container mx-auto px-4 py-10">
    <?php woocommerce_content(); ?>
  </div>
</main>
<?php get_footer(); ?>
