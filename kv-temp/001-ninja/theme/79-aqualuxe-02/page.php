<?php get_header(); ?>
<div class="container max-w-7xl mx-auto px-4 py-10">
  <?php if (have_posts()): while (have_posts()): the_post(); ?>
    <article id="post-<?php the_ID(); ?>" <?php post_class('prose dark:prose-invert max-w-none'); ?>>
      <h1 class="text-4xl font-bold mb-6"><?php the_title(); ?></h1>
      <div class="entry"><?php the_content(); ?></div>
    </article>
  <?php endwhile; endif; ?>
</div>
<?php get_footer(); ?>
