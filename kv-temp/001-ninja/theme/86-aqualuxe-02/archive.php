<?php get_header(); ?>
<main id="primary" class="site-main container mx-auto px-4 py-8" role="main">
  <header class="mb-6"><h1 class="text-3xl font-semibold"><?php the_archive_title(); ?></h1></header>
  <?php if (have_posts()) : ?>
    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
      <?php while (have_posts()) : the_post(); ?>
        <a class="card block border border-slate-200 dark:border-slate-800 rounded-lg p-4" href="<?php the_permalink(); ?>">
          <div class="font-semibold"><?php the_title(); ?></div>
        </a>
      <?php endwhile; ?>
    </div>
    <?php the_posts_pagination(); ?>
  <?php else: ?>
    <p><?php esc_html_e('No posts found.', 'aqualuxe'); ?></p>
  <?php endif; ?>
</main>
<?php get_footer(); ?>
