<?php get_header(); ?>
<main class="container mx-auto px-4 py-10">
  <header class="mb-6"><h1 class="text-3xl font-bold"><?php the_archive_title(); ?></h1><p class="opacity-80"><?php the_archive_description(); ?></p></header>
  <?php if (have_posts()): ?>
    <div class="grid md:grid-cols-3 gap-6">
      <?php while (have_posts()): the_post(); ?>
        <article <?php post_class('p-4 rounded-lg bg-gray-50 dark:bg-gray-900'); ?>>
          <a href="<?php the_permalink(); ?>" class="block">
            <?php if (has_post_thumbnail()) { the_post_thumbnail('medium', ['class' => 'rounded mb-3', 'loading' => 'lazy']); } ?>
            <h2 class="text-xl font-semibold mb-1"><?php the_title(); ?></h2>
            <p class="text-sm opacity-80"><?php echo esc_html(wp_trim_words(get_the_excerpt(), 18)); ?></p>
          </a>
        </article>
      <?php endwhile; ?>
    </div>
    <div class="mt-8"><?php the_posts_pagination(); ?></div>
  <?php else: ?>
    <p><?php esc_html_e('No posts found.', 'aqualuxe'); ?></p>
  <?php endif; ?>
</main>
<?php get_footer(); ?>
