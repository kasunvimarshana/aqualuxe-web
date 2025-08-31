<?php get_header(); ?>
<main id="content" class="site-main container mx-auto px-4" tabindex="-1">
  <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
    <article id="post-<?php the_ID(); ?>" <?php post_class('prose max-w-none'); ?> aria-labelledby="post-title-<?php the_ID(); ?>">
      <h1 id="post-title-<?php the_ID(); ?>" class="entry-title text-3xl font-semibold mb-4"><?php the_title(); ?></h1>
      <div class="entry-content"><?php the_content(); ?></div>
    </article>
  <?php endwhile; else: ?>
    <p><?php esc_html_e('No content found.', 'aqualuxe'); ?></p>
  <?php endif; ?>
</main>
<?php get_footer(); ?>
