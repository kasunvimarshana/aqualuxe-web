<?php
/** Search results template */
get_header();
?>
<main id="primary" class="site-main" role="main">
  <h1><?php printf(esc_html__('Search Results for: %s', 'aqualuxe'), get_search_query()); ?></h1>
  <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
      <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
      <?php the_excerpt(); ?>
    </article>
  <?php endwhile; else : ?>
    <p><?php _e('No results found.', 'aqualuxe'); ?></p>
  <?php endif; ?>
</main>
<?php get_footer(); ?>
