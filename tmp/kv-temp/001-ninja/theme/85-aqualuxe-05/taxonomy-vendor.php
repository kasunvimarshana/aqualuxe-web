<?php
/** Vendor taxonomy archive */
get_header();
?>
<main id="primary" class="site-main" role="main">
  <header class="page-header"><h1><?php single_term_title(); ?></h1></header>
  <?php if (have_posts()) : ?>
    <div class="grid listings">
      <?php while (have_posts()) : the_post(); ?>
        <?php get_template_part('templates/parts/listing-card'); ?>
      <?php endwhile; ?>
    </div>
    <?php the_posts_pagination(['screen_reader_text' => __('Listings navigation', 'aqualuxe')]); ?>
  <?php else : ?>
    <p><?php _e('No listings found for this vendor.', 'aqualuxe'); ?></p>
  <?php endif; ?>
</main>
<?php get_footer(); ?>
