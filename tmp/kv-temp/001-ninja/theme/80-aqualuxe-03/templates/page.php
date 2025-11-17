<?php get_header(); ?>
<div class="container mx-auto px-4 py-10 grid gap-8 md:grid-cols-[2fr_1fr]">
  <main>
    <?php while(have_posts()): the_post(); ?>
      <article <?php post_class('prose max-w-none'); ?>>
        <h1 class="text-3xl font-semibold mb-4"><?php the_title(); ?></h1>
        <div class="entry-content"><?php the_content(); ?></div>
      </article>
    <?php endwhile; ?>
  </main>
  <aside>
    <?php dynamic_sidebar('sidebar-1'); ?>
  </aside>
</div>
<?php get_footer(); ?>
