<?php get_header(); ?>
<main id="primary" class="site-main container mx-auto px-4 py-20 text-center" role="main">
  <h1 class="text-5xl font-extrabold mb-4"><?php esc_html_e('Lost at sea', 'aqualuxe'); ?></h1>
  <p class="opacity-80 mb-8"><?php esc_html_e("We couldn’t find that page. Try searching or head back home.", 'aqualuxe'); ?></p>
  <?php get_search_form(); ?>
  <a class="btn btn-primary mt-8" href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Back to home', 'aqualuxe'); ?></a>
</main>
<?php get_footer(); ?>
