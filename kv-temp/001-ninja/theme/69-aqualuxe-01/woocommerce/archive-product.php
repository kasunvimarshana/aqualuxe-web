<?php
// WooCommerce Shop Archive Override with graceful fallback
if (!function_exists('WC')) {
    get_template_part('templates/home');
    return;
}
get_header('shop');
?>
<main id="main" class="site-main" role="main" itemscope itemtype="https://schema.org/CollectionPage">
  <?php woocommerce_content(); ?>
</main>
<?php get_footer('shop'); ?>
