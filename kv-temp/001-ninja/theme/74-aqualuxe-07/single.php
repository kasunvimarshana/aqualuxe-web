<?php get_header(); ?>
<main class="container mx-auto px-4 py-10">
  <?php if (have_posts()): while (have_posts()): the_post(); ?>
    <article <?php post_class('prose dark:prose-invert max-w-none'); ?> itemscope itemtype="https://schema.org/Article">
      <h1 class="text-4xl font-bold mb-6" itemprop="headline"><?php the_title(); ?></h1>
      <?php if (has_post_thumbnail()) { the_post_thumbnail('large', ['class' => 'rounded-lg mb-6', 'loading' => 'lazy']); } ?>
      <div class="entry-content" itemprop="articleBody"><?php the_content(); ?></div>
      <?php comments_template(); ?>
    </article>
  <?php endwhile; endif; ?>
</main>
<?php get_footer(); ?>
