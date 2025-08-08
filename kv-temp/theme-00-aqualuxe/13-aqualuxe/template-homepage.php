<?php
/**
 * Template Name: Luxury Homepage
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

get_header(); ?>

<main id="content" class="site-main">

  <!-- Hero Section -->
  <section class="hero-section" style="background-image: url('<?php echo esc_url(get_stylesheet_directory_uri()); ?>/assets/images/hero-bg.jpg');">
    <div class="hero-content">
      <h1 class="hero-title"><?php esc_html_e('Exquisite Ornamental Fish', 'aqualuxe'); ?></h1>
      <p class="hero-subtitle"><?php esc_html_e('Premium aquatic companions for discerning collectors', 'aqualuxe'); ?></p>
      <a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>" class="hero-button"><?php esc_html_e('Explore Collection', 'aqualuxe'); ?></a>
    </div>
  </section>

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

  <!-- Promotional Banner -->
  <section class="promo-banner">
    <div class="promo-banner-content">
      <h3><?php esc_html_e('Limited Time Offer', 'aqualuxe'); ?></h3>
      <p><?php esc_html_e('Get 15% off on all premium fish varieties this month only', 'aqualuxe'); ?></p>
      <a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>" class="button button-accent"><?php esc_html_e('Shop Now', 'aqualuxe'); ?></a>
    </div>
  </section>

  <!-- Testimonials Section -->
  <section class="testimonials-section luxury-section">
    <div class="luxury-section-title">
      <h2><?php esc_html_e('What Our Clients Say', 'aqualuxe'); ?></h2>
    </div>
    
    <div class="testimonials-container">
      <div class="luxury-testimonial">
        <div class="luxury-testimonial-content">
          <?php esc_html_e('The quality of fish and service is exceptional. My aquarium has never looked more beautiful!', 'aqualuxe'); ?>
        </div>
        <div class="luxury-testimonial-author">
          <?php esc_html_e('- Sarah Johnson, Aquatic Enthusiast', 'aqualuxe'); ?>
        </div>
      </div>
      
      <div class="luxury-testimonial">
        <div class="luxury-testimonial-content">
          <?php esc_html_e('Aqualuxe provides the most stunning ornamental fish I\'ve ever seen. Highly recommended for serious collectors.', 'aqualuxe'); ?>
        </div>
        <div class="luxury-testimonial-author">
          <?php esc_html_e('- Michael Chen, Marine Biologist', 'aqualuxe'); ?>
        </div>
      </div>
    </div>
  </section>

  <!-- Call to Action Section -->
  <section class="cta-section">
    <div class="cta-section-content">
      <h2><?php esc_html_e('Ready to Enhance Your Aquarium?', 'aqualuxe'); ?></h2>
      <p><?php esc_html_e('Discover our exclusive collection of premium ornamental fish', 'aqualuxe'); ?></p>
      <a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>" class="button button-accent"><?php esc_html_e('View Collection', 'aqualuxe'); ?></a>
    </div>
  </section>

</main><!-- #content -->

<?php get_footer(); ?>