<?php
/**
 * Template Name: Luxury Homepage with Widgets
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

get_header(); ?>

<main id="content" class="site-main">

  <!-- Hero Section Widget Area -->
  <?php if (is_active_sidebar('homepage-hero')) : ?>
    <div class="homepage-hero-widgets">
      <?php dynamic_sidebar('homepage-hero'); ?>
    </div>
  <?php endif; ?>

  <!-- Featured Products Section -->
  <section class="featured-products luxury-section">
    <div class="luxury-section-title">
      <h2><?php esc_html_e('Featured Collection', 'aqualuxe'); ?></h2>
    </div>
    
    <?php
    // Get featured products
    $featured_products = wc_get_products(array(
      'limit' => 6,
      'featured' => true,
      'status' => 'publish'
    ));
    
    if ($featured_products) : ?>
      <div class="woocommerce">
        <ul class="products columns-3">
          <?php foreach ($featured_products as $product) : ?>
            <li class="product">
              <a href="<?php echo esc_url($product->get_permalink()); ?>" class="woocommerce-LoopProduct-link">
                <?php echo $product->get_image('woocommerce_thumbnail'); ?>
                <h3 class="woocommerce-loop-product__title"><?php echo esc_html($product->get_name()); ?></h3>
                <span class="price"><?php echo $product->get_price_html(); ?></span>
              </a>
              <?php
              // Add to cart button
              woocommerce_template_loop_add_to_cart();
              ?>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>
  </section>

  <!-- Homepage Content Widget Area -->
  <?php if (is_active_sidebar('homepage-content')) : ?>
    <div class="homepage-content-widgets">
      <?php dynamic_sidebar('homepage-content'); ?>
    </div>
  <?php endif; ?>

</main><!-- #content -->

<?php get_footer(); ?>