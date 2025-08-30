<?php get_header(); ?>
<?php do_action('aqualuxe/home/before'); ?>
<section class="relative overflow-hidden">
  <div class="container mx-auto px-4 py-16 text-center fade-in">
    <h1 class="text-4xl md:text-6xl font-extrabold mb-4"><?php esc_html_e('Bringing elegance to aquatic life – globally.', 'aqualuxe'); ?></h1>
    <p class="text-lg opacity-80 max-w-2xl mx-auto"><?php esc_html_e('Premium ornamental fish, aquatic plants, and bespoke aquariums for connoisseurs and professionals.', 'aqualuxe'); ?></p>
    <div class="mt-6 flex gap-3 justify-center">
      <a href="<?php echo esc_url(home_url('/shop/')); ?>" class="btn-primary"><?php esc_html_e('Shop Now', 'aqualuxe'); ?></a>
      <a href="<?php echo esc_url(home_url('/services/')); ?>" class="rounded-md border border-gray-300 px-4 py-2"><?php esc_html_e('Book a Service', 'aqualuxe'); ?></a>
    </div>
  </div>
</section>

<?php if (class_exists('WooCommerce')): ?>
<section class="container mx-auto px-4 py-12">
  <h2 class="text-2xl font-bold mb-6"><?php esc_html_e('Featured Products', 'aqualuxe'); ?></h2>
  <?php echo do_shortcode('[products limit="8" columns="4" visibility="featured"]'); ?>
</section>
<?php endif; ?>

<section class="container mx-auto px-4 py-12">
  <h2 class="text-2xl font-bold mb-6"><?php esc_html_e('Testimonials', 'aqualuxe'); ?></h2>
  <div class="grid md:grid-cols-3 gap-6">
    <blockquote class="p-6 rounded-lg bg-gray-50 dark:bg-gray-900">“<?php esc_html_e('Exquisite quality and reliable export logistics.', 'aqualuxe'); ?>”<footer class="mt-3">— Mira, Dubai</footer></blockquote>
    <blockquote class="p-6 rounded-lg bg-gray-50 dark:bg-gray-900">“<?php esc_html_e('Our showroom aquariums are show-stoppers.', 'aqualuxe'); ?>”<footer class="mt-3">— Kenji, Tokyo</footer></blockquote>
    <blockquote class="p-6 rounded-lg bg-gray-50 dark:bg-gray-900">“<?php esc_html_e('Professional service and healthy livestock.', 'aqualuxe'); ?>”<footer class="mt-3">— Ana, Lisbon</footer></blockquote>
  </div>
</section>

<section class="container mx-auto px-4 py-12">
  <h2 class="text-2xl font-bold mb-4"><?php esc_html_e('Join our newsletter', 'aqualuxe'); ?></h2>
  <form class="flex gap-2" onsubmit="return false;">
    <input type="email" required placeholder="<?php esc_attr_e('Email address', 'aqualuxe'); ?>" class="px-3 py-2 rounded-md bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700">
    <button class="btn-primary"><?php esc_html_e('Subscribe', 'aqualuxe'); ?></button>
  </form>
</section>
<?php do_action('aqualuxe/home/after'); ?>
<?php get_footer(); ?>
