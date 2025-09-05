<?php get_header(); ?>
<section class="container mx-auto px-4 py-10">
  <h1 class="text-3xl font-semibold mb-6"><?php the_archive_title(); ?></h1>
  <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
      <article <?php post_class('rounded-lg border border-slate-200/70 dark:border-slate-800/80 p-5'); ?>>
        <a href="<?php the_permalink(); ?>" class="block"><h2 class="text-xl font-semibold mb-2"><?php the_title(); ?></h2></a>
        <div class="prose dark:prose-invert max-w-none"><?php the_excerpt(); ?></div>
      </article>
    <?php endwhile; endif; ?>
  </div>
  <div class="mt-8"><?php the_posts_pagination(['mid_size' => 2]); ?></div>
</section>
<?php get_footer(); ?>
