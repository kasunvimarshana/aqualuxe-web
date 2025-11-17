<?php get_header(); ?>
<main class="container mx-auto px-4 py-10">
  <h1 class="text-2xl font-bold mb-6"><?php printf(esc_html__('Search results for "%s"', 'aqualuxe'), get_search_query()); ?></h1>
  <?php if (have_posts()): while (have_posts()): the_post(); ?>
    <article <?php post_class('mb-6'); ?>>
      <a href="<?php the_permalink(); ?>" class="text-xl font-semibold"><?php the_title(); ?></a>
      <div class="text-sm opacity-80"><?php echo esc_html(wp_trim_words(get_the_excerpt(), 28)); ?></div>
    </article>
  <?php endwhile; the_posts_pagination(); else: ?>
    <p><?php esc_html_e('No results.', 'aqualuxe'); ?></p>
  <?php endif; ?>
</main>
<?php get_footer(); ?>
