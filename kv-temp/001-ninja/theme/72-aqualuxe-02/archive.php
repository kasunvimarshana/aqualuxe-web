<?php get_header(); ?>
<main id="primary" class="container mx-auto px-4 py-8">
  <header class="mb-6"><h1 class="text-3xl font-semibold"><?php the_archive_title(); ?></h1></header>
  <?php if (have_posts()): ?>
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
      <?php while (have_posts()): the_post(); get_template_part('template-parts/content', get_post_type()); endwhile; ?>
    </div>
    <div class="mt-8"><?php the_posts_pagination(); ?></div>
  <?php else: ?>
    <p><?php esc_html_e('No content found.', 'aqualuxe'); ?></p>
  <?php endif; ?>
</main>
<?php get_footer(); ?>
