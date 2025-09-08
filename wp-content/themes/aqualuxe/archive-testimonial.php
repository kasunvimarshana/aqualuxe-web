<?php if (!defined('ABSPATH')) { exit; } get_header(); ?>
<main id="main" class="site_main" role="main">
  <div class="container mx-auto px-4 py-10">
    <h1 class="text-3xl font-bold mb-6"><?php post_type_archive_title(); ?></h1>
    <?php if (have_posts()): ?>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <?php while (have_posts()): the_post(); ?>
          <article <?php post_class('card bg-white dark:bg-slate-800 rounded-lg shadow p-4'); ?>>
            <h2 class="text-xl font-semibold"><a href="<?php the_permalink(); ?>" class="hover:underline"><?php the_title(); ?></a></h2>
            <div class="mt-2 text-slate-700 dark:text-slate-200"><?php the_excerpt(); ?></div>
          </article>
        <?php endwhile; ?>
      </div>
      <nav class="mt-8"><?php the_posts_pagination(); ?></nav>
    <?php else: ?>
      <p><?php esc_html_e('No testimonials found.', 'aqualuxe'); ?></p>
    <?php endif; ?>
  </div>
</main>
<?php get_footer(); ?>
