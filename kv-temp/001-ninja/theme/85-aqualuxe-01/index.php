<?php
/**
 * Main template fallback.
 * Progressive enhancement: works without JS.
 */
get_header();
?>
<main id="primary" class="site-main" role="main">
  <?php if (have_posts()) : ?>
    <?php while (have_posts()) : the_post(); ?>
      <article id="post-<?php the_ID(); ?>" <?php post_class(); ?> role="article">
        <header class="entry-header">
          <h1 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
        </header>
        <div class="entry-content">
          <?php the_content(); ?>
        </div>
      </article>
    <?php endwhile; ?>
    <nav class="pagination" aria-label="Posts">
      <div class="nav-previous"><?php next_posts_link(__('Older posts', 'aqualuxe')); ?></div>
      <div class="nav-next"><?php previous_posts_link(__('Newer posts', 'aqualuxe')); ?></div>
    </nav>
  <?php else : ?>
    <p><?php _e('No content found.', 'aqualuxe'); ?></p>
  <?php endif; ?>
</main>
<?php get_footer(); ?>
