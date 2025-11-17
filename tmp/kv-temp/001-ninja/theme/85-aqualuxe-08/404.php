<?php
/**
 * 404 template
 */

defined('ABSPATH') || exit;
get_header();
?>
<main id="primary" class="site-main container mx-auto px-4 py-16 text-center">
  <h1 class="text-3xl font-bold mb-4"><?php esc_html_e('Page not found', 'aqualuxe'); ?></h1>
  <p class="mb-6"><?php esc_html_e('The page you are looking for doesn’t exist or has been moved.', 'aqualuxe'); ?></p>
  <?php get_search_form(); ?>
</main>
<?php get_footer();
