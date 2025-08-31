<?php get_header(); ?>
<main id="content" class="container mx-auto px-4 py-10" tabindex="-1">
  <h1 class="text-2xl font-semibold mb-4"><?php printf(esc_html__('Search results for: %s', 'aqualuxe'), get_search_query()); ?></h1>
  <?php if (have_posts()): while(have_posts()): the_post(); ?>
    <article class="mb-6">
      <a href="<?php the_permalink(); ?>" class="text-xl font-medium underline"><?php the_title(); ?></a>
      <div class="text-sm opacity-80"><?php the_excerpt(); ?></div>
    </article>
  <?php endwhile; the_posts_pagination(); else: ?>
    <p><?php esc_html_e('No results found.', 'aqualuxe'); ?></p>
  <?php endif; ?>
 </main>
<?php get_footer(); ?>