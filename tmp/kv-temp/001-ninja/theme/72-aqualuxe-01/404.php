<?php get_header(); ?>
<main id="primary" class="container mx-auto px-4 py-20 text-center">
  <h1 class="text-5xl font-bold mb-4">404</h1>
  <p class="mb-6"><?php esc_html_e('We can\'t find that page.', 'aqualuxe'); ?></p>
  <a href="<?php echo esc_url(home_url('/')); ?>" class="btn-primary"><?php esc_html_e('Back to home', 'aqualuxe'); ?></a>
</main>
<?php get_footer(); ?>
