<?php get_header(); ?>
<main id="primary" class="container mx-auto px-4 py-8">
  <header class="mb-6"><h1 class="text-2xl font-semibold"><?php printf(esc_html__('Search results for "%s"', 'aqualuxe'), get_search_query()); ?></h1></header>
  <?php if (have_posts()): while (have_posts()): the_post(); get_template_part('template-parts/content', get_post_type()); endwhile; the_posts_pagination(); else: ?>
    <p><?php esc_html_e('Nothing matched your search terms.', 'aqualuxe'); ?></p>
  <?php endif; ?>
</main>
<?php get_footer(); ?>
