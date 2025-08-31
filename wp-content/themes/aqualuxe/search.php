<?php get_header(); ?>
<section class="container mx-auto px-4 py-10">
  <h1 class="text-3xl font-bold mb-6"><?php printf(esc_html__('Search: %s', 'aqualuxe'), esc_html(get_search_query())); ?></h1>
  <?php if (have_posts()): while (have_posts()): the_post(); ?>
    <article id="post-<?php the_ID(); ?>" <?php post_class('p-4 border rounded mb-4'); ?>>
      <h2 class="text-xl font-semibold mb-2"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
      <div><?php the_excerpt(); ?></div>
    </article>
  <?php endwhile; the_posts_pagination(); else: ?>
    <p><?php esc_html_e('No results found.', 'aqualuxe'); ?></p>
  <?php endif; ?>
</section>
<?php get_footer(); ?>
