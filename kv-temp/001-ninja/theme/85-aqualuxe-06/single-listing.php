<?php
/** Single Listing */
get_header();
?>
<main id="primary" class="site-main" role="main">
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
  <article <?php post_class('single-listing'); ?>>
    <header>
      <h1><?php the_title(); ?></h1>
      <div class="meta">
        <?php the_terms(get_the_ID(), 'vendor', __('Vendor: ', 'aqualuxe'), ', '); ?>
      </div>
    </header>
    <div class="content">
      <?php the_content(); ?>
    </div>
  </article>
<?php endwhile; endif; ?>
</main>
<?php get_footer(); ?>
