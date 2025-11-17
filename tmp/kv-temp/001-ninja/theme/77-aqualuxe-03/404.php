<?php get_header(); ?>
<section class="container mx-auto px-4 py-20 text-center">
  <h1 class="text-5xl font-extrabold mb-4">404</h1>
  <p class="mb-6"><?php esc_html_e('Page not found.', 'aqualuxe'); ?></p>
  <a class="px-6 py-3 bg-sky-600 text-white rounded" href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Go Home', 'aqualuxe'); ?></a>
</section>
<?php get_footer(); ?>
