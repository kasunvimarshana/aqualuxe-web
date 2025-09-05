<?php get_header(); ?>
<section class="container mx-auto px-4 py-16 text-center">
  <h1 class="text-4xl font-bold mb-4"><?php esc_html_e('Page not found', 'aqualuxe'); ?></h1>
  <p><?php esc_html_e('The page you are looking for doesn’t exist.', 'aqualuxe'); ?></p>
  <a href="<?php echo esc_url(home_url('/')); ?>" class="inline-block mt-6 px-5 py-3 rounded bg-sky-600 text-white"><?php esc_html_e('Go home', 'aqualuxe'); ?></a>
  </section>
<?php get_footer(); ?>
