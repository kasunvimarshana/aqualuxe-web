<?php get_header(); ?>

<section class="container mx-auto px-4 py-10">
  <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
    <article <?php post_class('prose dark:prose-invert max-w-none'); ?> itemscope itemtype="https://schema.org/Article">
      <h1 class="text-3xl font-semibold mb-6" itemprop="headline"><?php the_title(); ?></h1>
      <div itemprop="articleBody"><?php the_content(); ?></div>
    </article>
  <?php endwhile; endif; ?>
</section>

<?php get_footer(); ?>
