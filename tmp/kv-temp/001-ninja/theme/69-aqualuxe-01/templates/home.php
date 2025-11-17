<?php
// Home: hero, featured, testimonials, newsletter
get_header();
?>
<main id="main" class="site-main" role="main" itemscope itemtype="https://schema.org/WebPage">
  <section class="hero" aria-label="Hero">
    <h1 itemprop="headline"><?php echo esc_html(get_bloginfo('name')); ?></h1>
    <p itemprop="description"><?php echo esc_html(get_bloginfo('description')); ?></p>
  </section>
  <section class="featured" aria-label="Featured Products">
    <?php if (function_exists('woocommerce_featured_products')) woocommerce_featured_products(); ?>
  </section>
  <section class="testimonials" aria-label="Testimonials">
    <!-- Testimonials content -->
  </section>
  <section class="newsletter" aria-label="Newsletter Signup">
    <!-- Newsletter form -->
  </section>
</main>
<?php get_footer(); ?>
