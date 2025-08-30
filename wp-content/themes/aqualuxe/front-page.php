<?php /* Template: Front Page */ get_header(); ?>
<main id="primary" class="container mx-auto px-4 py-10">
  <section class="hero grid lg:grid-cols-2 gap-8 items-center mb-12">
    <div>
      <h1 class="text-4xl md:text-5xl font-bold mb-4"><?php echo esc_html__('Bringing elegance to aquatic life – globally', 'aqualuxe'); ?></h1>
      <p class="text-lg opacity-90 mb-6"><?php echo esc_html__('Premium ornamental fish, plants, and custom aquariums with worldwide reach.', 'aqualuxe'); ?></p>
      <div class="flex gap-3">
        <a class="btn-primary" href="<?php echo esc_url(home_url('/shop')); ?>"><?php esc_html_e('Shop Now', 'aqualuxe'); ?></a>
        <a class="btn-outline" href="<?php echo esc_url(home_url('/services')); ?>"><?php esc_html_e('Book a Service', 'aqualuxe'); ?></a>
      </div>
    </div>
    <div>
      <?php if (has_post_thumbnail()) { the_post_thumbnail('aqlx-hero', ['class'=>'rounded-lg w-full h-auto','loading'=>'eager']); } ?>
    </div>
  </section>

  <?php if (class_exists('WooCommerce')): ?>
  <section class="featured mb-12">
    <h2 class="text-2xl font-semibold mb-4"><?php esc_html_e('Featured Products', 'aqualuxe'); ?></h2>
    <?php echo do_shortcode('[products limit="8" columns="4" visibility="featured"]'); ?>
  </section>
  <?php endif; ?>

  <section class="testimonials mb-12">
    <h2 class="text-2xl font-semibold mb-4"><?php esc_html_e('Testimonials', 'aqualuxe'); ?></h2>
    <div class="grid md:grid-cols-3 gap-6">
      <?php for($i=0;$i<3;$i++): ?>
        <blockquote class="p-4 rounded-lg bg-white dark:bg-slate-800 shadow">
          <p>“<?php esc_html_e('Stunning quality and unmatched service.', 'aqualuxe'); ?>”</p>
          <footer class="mt-2 text-sm opacity-80">— AquaLuxe Client</footer>
        </blockquote>
      <?php endfor; ?>
    </div>
  </section>
</main>
<?php get_footer(); ?>
