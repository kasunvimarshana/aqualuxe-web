<?php
defined('ABSPATH') || exit;
get_header(); ?>

<section class="relative overflow-hidden">
  <div class="<?php echo esc_attr(aqlx_container_class()); ?> py-16 md:py-24">
    <div class="grid md:grid-cols-2 gap-10 items-center">
      <div>
        <h1 class="text-4xl md:text-6xl font-extrabold leading-tight">
          <?php echo esc_html__('Bringing elegance to aquatic life – globally.', 'aqualuxe'); ?>
        </h1>
        <p class="mt-4 text-lg opacity-90"><?php echo esc_html__('Premium ornamental fish, plants, and aquariums for discerning collectors and businesses worldwide.', 'aqualuxe'); ?></p>
        <div class="mt-6 flex gap-3">
          <a href="<?php echo esc_url(home_url('/shop')); ?>" class="bg-sky-600 hover:bg-sky-700 text-white px-5 py-3 rounded"><?php esc_html_e('Shop Now', 'aqualuxe'); ?></a>
          <a href="<?php echo esc_url(home_url('/services')); ?>" class="border border-sky-600 text-sky-700 dark:text-sky-300 hover:bg-sky-50 dark:hover:bg-slate-800 px-5 py-3 rounded"><?php esc_html_e('Book a Service', 'aqualuxe'); ?></a>
        </div>
      </div>
      <div class="rounded-xl overflow-hidden shadow-lg">
        <?php echo wp_get_attachment_image(0, 'aqlx-hero', false, ['alt' => 'AquaLuxe Hero', 'loading' => 'lazy']); ?>
      </div>
    </div>
  </div>
</section>

<?php if (class_exists('WooCommerce')): ?>
<section class="py-12 bg-slate-50 dark:bg-slate-900/30">
  <div class="<?php echo esc_attr(aqlx_container_class()); ?>">
    <h2 class="text-2xl font-bold mb-6"><?php esc_html_e('Featured Products', 'aqualuxe'); ?></h2>
    <?php echo do_shortcode('[products limit="8" columns="4" visibility="featured"]'); ?>
  </div>
</section>
<?php endif; ?>

<section class="py-12">
  <div class="<?php echo esc_attr(aqlx_container_class()); ?>">
    <h2 class="text-2xl font-bold mb-6"><?php esc_html_e('Testimonials', 'aqualuxe'); ?></h2>
    <div class="grid md:grid-cols-3 gap-6">
      <?php for ($i=0;$i<3;$i++): ?>
      <blockquote class="p-6 border rounded-lg bg-white dark:bg-slate-800">
        <p class="italic">“<?php esc_html_e('Incredible quality and service. My reef tank has never looked better.', 'aqualuxe'); ?>”</p>
        <cite class="block mt-3 text-sm opacity-80">— Aqua Enthusiast</cite>
      </blockquote>
      <?php endfor; ?>
    </div>
  </div>
</section>

<section class="py-12 bg-slate-50 dark:bg-slate-900/30">
  <div class="<?php echo esc_attr(aqlx_container_class()); ?>">
    <h2 class="text-2xl font-bold mb-4"><?php esc_html_e('Join our newsletter', 'aqualuxe'); ?></h2>
    <form class="max-w-xl flex gap-3" method="post" action="#" onsubmit="return false;">
      <label class="sr-only" for="home-news-email"><?php esc_html_e('Email', 'aqualuxe'); ?></label>
      <input id="home-news-email" type="email" class="flex-1 border rounded px-4 py-3 dark:bg-slate-800" placeholder="you@example.com" required />
      <button class="bg-sky-600 hover:bg-sky-700 text-white px-5 py-3 rounded"><?php esc_html_e('Subscribe', 'aqualuxe'); ?></button>
    </form>
  </div>
</section>

<?php get_footer();
