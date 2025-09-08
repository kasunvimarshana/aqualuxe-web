<?php if (!defined('ABSPATH')) { exit; } get_header(); ?>
<main id="main" class="site_main" role="main">
  <section class="hero relative overflow-hidden">
    <div class="container mx-auto px-4 py-20 text-center">
      <h1 class="text-4xl md:text-6xl font-extrabold tracking-tight mb-4">Bringing elegance to aquatic life – globally.</h1>
      <p class="text-lg opacity-90 max-w-2xl mx-auto">Premium ornamental fish, aquatic plants, bespoke aquariums, and world-class services.</p>
      <div class="mt-8 flex items-center justify-center gap-4">
        <a class="btn btn_primary" href="<?php echo esc_url(home_url('/shop/')); ?>"><?php esc_html_e('Shop Now','aqualuxe'); ?></a>
        <a class="btn btn_secondary" href="<?php echo esc_url(home_url('/services/')); ?>"><?php esc_html_e('Book a Service','aqualuxe'); ?></a>
      </div>
    </div>
  </section>
  <section class="container mx-auto px-4 py-12">
    <h2 class="text-2xl font-semibold mb-6"><?php esc_html_e('Featured', 'aqualuxe'); ?></h2>
  <?php if (class_exists('WooCommerce')): echo \do_shortcode('[products limit="8" columns="4" visibility="featured"]'); else: ?>
      <p class="opacity-80"><?php esc_html_e('Enable WooCommerce to display featured products.', 'aqualuxe'); ?></p>
    <?php endif; ?>
  </section>
</main>
<?php get_footer(); ?>
