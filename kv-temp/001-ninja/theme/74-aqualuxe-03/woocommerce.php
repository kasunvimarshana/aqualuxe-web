<?php if (!class_exists('WooCommerce')) { require locate_template('index.php'); return; }
get_header(); ?>
<main class="container mx-auto px-4 py-10">
  <?php woocommerce_content(); ?>
</main>
<?php get_footer(); ?>
