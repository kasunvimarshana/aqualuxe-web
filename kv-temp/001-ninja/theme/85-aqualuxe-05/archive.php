<?php
/**
 * Default archive template
 */

defined('ABSPATH') || exit;
get_header();
?>
<main id="primary" class="site-main container mx-auto px-4 py-8">
  <header class="page-header">
    <h1 class="page-title"><?php the_archive_title(); ?></h1>
    <?php the_archive_description('<div class="archive-description">', '</div>'); ?>
  </header>
  <?php if (have_posts()) : ?>
    <div class="post-list grid md:grid-cols-2 gap-6">
      <?php while (have_posts()) : the_post(); ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class('card'); ?>>
          <h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
          <div class="entry-excerpt"><?php the_excerpt(); ?></div>
        </article>
      <?php endwhile; ?>
    </div>
    <div class="pagination"><?php the_posts_pagination(); ?></div>
  <?php else : ?>
    <p><?php esc_html_e('No posts found.', 'aqualuxe'); ?></p>
  <?php endif; ?>
</main>
<?php get_footer();
