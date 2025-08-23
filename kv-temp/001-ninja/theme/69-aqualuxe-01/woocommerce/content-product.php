<?php
// WooCommerce Product Loop Item Override
if (!function_exists('WC')) {
    return;
}
?>
<article <?php wc_product_class(); ?> itemscope itemtype="https://schema.org/Product">
  <a href="<?php echo esc_url(get_the_permalink()); ?>" title="<?php echo esc_attr(the_title_attribute(['echo'=>false])); ?>">
    <?php
    if (has_post_thumbnail()) {
      echo get_the_post_thumbnail(get_the_ID(), 'shop_catalog', [
        'loading' => 'lazy',
        'alt' => esc_attr(get_the_title()),
        'itemprop' => 'image',
      ]);
    }
    ?>
    <h2 itemprop="name"><?php echo esc_html(get_the_title()); ?></h2>
  </a>
  <div class="price" itemprop="offers" itemscope itemtype="https://schema.org/Offer">
    <?php woocommerce_template_loop_price(); ?>
  </div>
  <?php woocommerce_template_loop_add_to_cart(); ?>
</article>
