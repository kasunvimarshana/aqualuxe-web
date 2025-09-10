<?php if (! defined('ABSPATH')) { exit; }
get_header(); ?>

<section class="relative overflow-hidden">
  <div class="container mx-auto px-4 py-16 text-center">
    <h1 class="text-3xl md:text-5xl font-extrabold mb-4"><?php echo esc_html(get_bloginfo('name')); ?></h1>
    <p class="text-lg opacity-80 mb-8"><?php esc_html_e('Bringing elegance to aquatic life – globally.', 'aqualuxe'); ?></p>
    <div class="flex gap-4 justify-center">
      <a class="inline-block bg-brand text-white rounded px-6 py-3" href="<?php echo esc_url(get_permalink( wc_get_page_id('shop') )); ?>"><?php esc_html_e('Shop Now', 'aqualuxe'); ?></a>
      <a class="inline-block border border-slate-300 dark:border-slate-700 rounded px-6 py-3" href="<?php echo esc_url(home_url('/services')); ?>"><?php esc_html_e('Book a Service', 'aqualuxe'); ?></a>
    </div>
  </div>
</section>

<?php if (class_exists('WooCommerce')): ?>
<section class="container mx-auto px-4 py-12">
  <h2 class="text-2xl font-semibold mb-6"><?php esc_html_e('Featured Products', 'aqualuxe'); ?></h2>
  <?php echo do_shortcode('[products limit="4" columns="4" visibility="featured"]'); ?>
  <div class="mt-6"><a class="text-brand" href="<?php echo esc_url(get_permalink( wc_get_page_id('shop') )); ?>"><?php esc_html_e('View all products →', 'aqualuxe'); ?></a></div>
 </section>
<?php endif; ?>

<section class="container mx-auto px-4 py-12">
  <h2 class="text-2xl font-semibold mb-6"><?php esc_html_e('Testimonials', 'aqualuxe'); ?></h2>
  <?php echo do_shortcode('[aqlx_testimonials count="3"]'); ?>
</section>

<section class="container mx-auto px-4 py-12">
  <div class="bg-slate-50 dark:bg-slate-800 rounded p-6 md:p-10">
    <h2 class="text-2xl font-semibold mb-2"><?php esc_html_e('Newsletter', 'aqualuxe'); ?></h2>
    <p class="opacity-80 mb-4"><?php esc_html_e('Get the latest arrivals, care guides, and events.', 'aqualuxe'); ?></p>
    <form method="post" action="<?php echo esc_url(home_url('/')); ?>">
      <label class="sr-only" for="email">Email</label>
      <div class="flex gap-2">
        <input class="w-full rounded border-slate-300 dark:border-slate-700" type="email" id="email" name="email" required>
        <button class="bg-brand text-white rounded px-4" type="submit"><?php esc_html_e('Subscribe', 'aqualuxe'); ?></button>
      </div>
    </form>
  </div>
</section>

<?php get_footer();
