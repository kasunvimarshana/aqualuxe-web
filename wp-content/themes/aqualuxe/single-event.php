<?php if (!defined('ABSPATH')) { exit; } get_header(); ?>
<main id="main" class="site_main" role="main">
  <div class="container mx-auto px-4 py-10">
    <?php if (have_posts()): while (have_posts()): the_post(); ?>
      <article <?php post_class('prose dark:prose-invert max-w-none'); ?>>
        <h1 class="text-3xl font-bold mb-4"><?php the_title(); ?></h1>
        <div class="entry_content"><?php the_content(); ?></div>
      </article>
    <?php endwhile; endif; ?>
  </div>
</main>
<?php get_footer(); ?>
