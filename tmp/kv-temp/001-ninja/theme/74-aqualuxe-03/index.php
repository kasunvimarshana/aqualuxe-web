<?php get_header(); ?>
<main id="content" class="site-main container mx-auto px-4 py-10">
  <?php if (have_posts()): while (have_posts()): the_post(); ?>
    <article <?php post_class('prose dark:prose-invert max-w-none mb-10'); ?> itemscope itemtype="https://schema.org/Article">
      <h1 class="text-3xl font-bold mb-4" itemprop="headline"><?php the_title(); ?></h1>
      <div class="entry-content" itemprop="articleBody"><?php the_content(); ?></div>
    </article>
  <?php endwhile; else: ?>
    <p><?php esc_html_e('No content found.', 'aqualuxe'); ?></p>
  <?php endif; ?>
</main>
<?php get_footer(); ?>
