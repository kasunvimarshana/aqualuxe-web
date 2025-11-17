<?php get_header(); ?>
<div class="container max-w-7xl mx-auto px-4 py-20 text-center">
  <h1 class="text-5xl font-bold mb-4"><?php esc_html_e('Page not found', 'aqualuxe'); ?></h1>
  <p class="opacity-80 mb-6"><?php esc_html_e('The page you’re looking for doesn’t exist. Try searching.', 'aqualuxe'); ?></p>
  <?php get_search_form(); ?>
</div>
<?php get_footer(); ?>
