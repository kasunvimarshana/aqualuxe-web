<?php
/**
 * Default page template
 */

defined('ABSPATH') || exit;
get_header();
?>
<main id="primary" class="site-main container mx-auto px-4 py-8">
  <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
      <h1 class="entry-title"><?php the_title(); ?></h1>
      <div class="entry-content"><?php the_content(); ?></div>
    </article>
  <?php endwhile; endif; ?>
</main>
<?php get_footer();
