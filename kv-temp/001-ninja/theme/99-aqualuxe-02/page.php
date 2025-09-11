<?php if (! defined('ABSPATH')) { exit; }
get_header(); ?>
<section class="container mx-auto px-4 py-10">
  <?php while (have_posts()) : the_post(); ?>
    <article <?php post_class('prose dark:prose-invert max-w-none'); ?>>
      <h1 class="mb-4"><?php the_title(); ?></h1>
      <div class="entry-content"><?php the_content(); ?></div>
    </article>
  <?php endwhile; ?>
</section>
<?php get_footer();
