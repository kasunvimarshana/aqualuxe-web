<?php get_header(); ?>
<section class="container mx-auto px-4 py-10">
  <?php if (have_posts()): while (have_posts()): the_post(); ?>
    <article id="post-<?php the_ID(); ?>" <?php post_class('prose dark:prose-invert max-w-none'); ?>>
      <h1 class="entry-title text-3xl font-bold mb-4"><?php the_title(); ?></h1>
      <div class="entry-meta text-sm opacity-70 mb-4"><?php echo esc_html(get_the_date()); ?></div>
      <div class="entry-content"><?php the_content(); ?></div>
    </article>
  <?php endwhile; endif; ?>
</section>
<?php get_footer(); ?>
