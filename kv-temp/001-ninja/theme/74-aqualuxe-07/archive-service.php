<?php get_header(); ?>
<main class="container mx-auto px-4 py-10">
  <h1 class="text-3xl font-bold mb-6"><?php esc_html_e('Services', 'aqualuxe'); ?></h1>
  <?php if (have_posts()): ?>
  <div class="grid md:grid-cols-3 gap-6">
    <?php while (have_posts()): the_post(); ?>
      <a href="<?php the_permalink(); ?>" class="p-4 rounded-lg border border-gray-100 dark:border-gray-800 block">
        <?php if (has_post_thumbnail()) { the_post_thumbnail('medium', ['class' => 'rounded mb-3']); } ?>
        <h2 class="text-xl font-semibold mb-1"><?php the_title(); ?></h2>
        <p class="opacity-80"><?php echo esc_html(wp_trim_words(get_the_excerpt(), 20)); ?></p>
      </a>
    <?php endwhile; ?>
  </div>
  <?php the_posts_pagination(['mid_size'=>2]); endif; ?>
</main>
<?php get_footer(); ?>
