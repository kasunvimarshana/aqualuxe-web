<?php get_header(); ?>
<section class="relative overflow-hidden">
  <div class="absolute inset-0 -z-10">
    <canvas id="aqlx-ocean" class="w-full h-full block"></canvas>
  </div>
  <div class="container mx-auto max-w-screen-xl px-4 py-16 text-center">
    <h1 class="text-4xl md:text-6xl font-extrabold tracking-tight mb-4"><?php esc_html_e('Bringing elegance to aquatic life – globally.', 'aqualuxe'); ?></h1>
    <p class="text-lg opacity-80 max-w-2xl mx-auto"><?php esc_html_e('Premium ornamental aquatics, sustainable practices, and luxe experiences.', 'aqualuxe'); ?></p>
    <div class="mt-8 flex flex-wrap gap-4 justify-center">
      <a href="<?php echo esc_url( home_url('/shop') ); ?>" class="inline-flex items-center px-6 py-3 bg-sky-600 text-white rounded"><?php esc_html_e('Shop Now', 'aqualuxe'); ?></a>
      <a href="<?php echo esc_url( home_url('/services') ); ?>" class="inline-flex items-center px-6 py-3 border border-sky-600 text-sky-600 rounded"><?php esc_html_e('Book a Service', 'aqualuxe'); ?></a>
    </div>
  </div>
</section>

<section class="container mx-auto max-w-screen-xl px-4 py-12">
  <h2 class="text-2xl font-bold mb-6"><?php esc_html_e('Featured', 'aqualuxe'); ?></h2>
  <div class="grid gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
    <?php if ( class_exists('WooCommerce') ) : ?>
  <?php echo function_exists('do_shortcode') ? call_user_func('do_shortcode', '[products limit="4" columns="4" visibility="featured"]') : ''; ?>
    <?php else : ?>
      <?php
      $q = new WP_Query([
        'post_type' => 'aqlx_service',
        'posts_per_page' => 4,
      ]);
      if ( $q->have_posts() ) : while ( $q->have_posts() ) : $q->the_post(); ?>
        <article class="border rounded p-4">
          <a href="<?php the_permalink(); ?>" class="block mb-2 font-semibold"><?php the_title(); ?></a>
          <div class="text-sm opacity-80"><?php the_excerpt(); ?></div>
        </article>
      <?php endwhile; wp_reset_postdata(); else: ?>
        <p class="opacity-70"><?php esc_html_e('Add services or activate WooCommerce to show featured items.', 'aqualuxe'); ?></p>
      <?php endif; ?>
    <?php endif; ?>
  </div>
</section>

<section class="bg-slate-50">
  <div class="container mx-auto max-w-screen-xl px-4 py-12">
    <h2 class="text-2xl font-bold mb-6"><?php esc_html_e('Testimonials', 'aqualuxe'); ?></h2>
    <div class="grid gap-6 md:grid-cols-3">
      <blockquote class="p-6 border rounded">“<?php esc_html_e('Stunning quality and top-notch service.', 'aqualuxe'); ?>” <span class="block mt-2 text-sm opacity-80">— Alex</span></blockquote>
      <blockquote class="p-6 border rounded">“<?php esc_html_e('Our custom aquarium is a showstopper.', 'aqualuxe'); ?>” <span class="block mt-2 text-sm opacity-80">— Priya</span></blockquote>
      <blockquote class="p-6 border rounded">“<?php esc_html_e('Ethical, sustainable, and luxurious.', 'aqualuxe'); ?>” <span class="block mt-2 text-sm opacity-80">— Omar</span></blockquote>
    </div>
  </div>
</section>

<?php get_footer(); ?>
