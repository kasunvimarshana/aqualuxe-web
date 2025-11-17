<?php get_header(); ?>
<div class="container max-w-7xl mx-auto px-4 py-10">
  <h1 class="text-3xl font-semibold mb-6"><?php printf(esc_html__('Search results for "%s"', 'aqualuxe'), esc_html(get_search_query())); ?></h1>
  <?php if (have_posts()): ?>
    <div class="grid md:grid-cols-3 gap-6">
    <?php while (have_posts()): the_post(); ?>
      <a href="<?php the_permalink(); ?>" class="block border rounded p-4 hover-lift">
        <h2 class="font-semibold mb-2"><?php the_title(); ?></h2>
        <p class="text-sm opacity-70"><?php echo esc_html(wp_strip_all_tags(get_the_excerpt())); ?></p>
      </a>
    <?php endwhile; ?>
    </div>
  <?php else: ?>
    <p><?php esc_html_e('No results found.', 'aqualuxe'); ?></p>
  <?php endif; ?>
</div>
<?php get_footer(); ?>
