<?php get_header(); ?>
<main class="container mx-auto px-4 py-20 text-center">
  <h1 class="text-6xl font-bold mb-4">404</h1>
  <p class="opacity-80 mb-6"><?php esc_html_e('Page not found.', 'aqualuxe'); ?></p>
  <a class="btn-primary" href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Back to home', 'aqualuxe'); ?></a>
</main>
<?php get_footer(); ?>
