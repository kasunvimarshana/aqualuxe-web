<?php if (! defined('ABSPATH')) { exit; }
get_header(); ?>
<section class="container mx-auto px-4 py-20 text-center">
  <h1 class="text-4xl font-bold mb-2">404</h1>
  <p class="opacity-80 mb-6"><?php esc_html_e('Page not found.', 'aqualuxe'); ?></p>
  <a class="text-brand" href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Back to home', 'aqualuxe'); ?></a>
</section>
<?php get_footer();
