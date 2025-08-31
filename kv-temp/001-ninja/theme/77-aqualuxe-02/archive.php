<?php get_header(); ?>
<section class="container mx-auto px-4 py-10">
  <h1 class="text-3xl font-bold mb-6"><?php the_archive_title(); ?></h1>
  <?php if (have_posts()): ?>
    <div class="grid md:grid-cols-3 gap-6">
      <?php while (have_posts()): the_post(); ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class('p-4 border rounded'); ?>>
          <h2 class="text-xl font-semibold mb-2"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
          <div class="text-sm opacity-70 mb-2"><?php echo esc_html(get_the_date()); ?></div>
          <div><?php the_excerpt(); ?></div>
        </article>
      <?php endwhile; ?>
    </div>
    <div class="mt-6"><?php the_posts_pagination(); ?></div>
  <?php else: ?>
    <p><?php esc_html_e('No posts found.', 'aqualuxe'); ?></p>
  <?php endif; ?>
</section>
<?php get_footer(); ?>
