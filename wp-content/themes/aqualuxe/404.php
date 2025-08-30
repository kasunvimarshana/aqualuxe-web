<?php defined('ABSPATH') || exit; get_header(); ?>
<div class="<?php echo esc_attr(aqlx_container_class()); ?> py-24 text-center">
  <h1 class="text-5xl font-extrabold mb-4">404</h1>
  <p class="mb-6"><?php esc_html_e('Page not found.', 'aqualuxe'); ?></p>
  <a href="<?php echo esc_url(home_url('/')); ?>" class="text-sky-600 hover:underline"><?php esc_html_e('Back to home', 'aqualuxe'); ?></a>
</div>
<?php get_footer(); ?>
