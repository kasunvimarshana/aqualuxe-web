<?php get_header(); ?>
<div class="container max-w-7xl mx-auto px-4 py-8">
  <?php if (have_posts()): while (have_posts()): the_post(); ?>
    <article id="post-<?php the_ID(); ?>" <?php post_class('prose dark:prose-invert max-w-none'); ?>>
      <h1 class="text-3xl font-semibold mb-4"><?php the_title(); ?></h1>
      <div class="entry"><?php the_content(); ?></div>
    </article>
  <?php endwhile; else: ?>
    <p><?php esc_html_e('No content found.', 'aqualuxe'); ?></p>
  <?php endif; ?>
</div>
<?php get_footer(); ?>
