<?php get_header(); ?>
<main id="primary" class="site-main container mx-auto px-4 py-8" role="main">
  <header class="mb-6"><h1 class="text-3xl font-semibold"><?php printf(esc_html__('Search results for "%s"', 'aqualuxe'), esc_html(get_search_query())); ?></h1></header>
  <?php if (have_posts()) : ?>
    <ul class="space-y-4">
      <?php while (have_posts()) : the_post(); ?>
        <li><a class="underline hover:no-underline" href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
      <?php endwhile; ?>
    </ul>
    <?php the_posts_pagination(); ?>
  <?php else: ?>
    <p><?php esc_html_e('No results found.', 'aqualuxe'); ?></p>
  <?php endif; ?>
</main>
<?php get_footer(); ?>
