<?php
// WooCommerce Single Product Override with graceful fallback
if (!function_exists('WC')) {
    get_template_part('templates/home');
    return;
}
get_header('shop');
?>
<main id="main" class="site-main" role="main" itemscope itemtype="https://schema.org/Product">
  <?php while (have_posts()) : the_post(); ?>
    <?php wc_get_template_part('content', 'single-product'); ?>
  <?php endwhile; ?>
</main>
<?php get_footer('shop'); ?>
