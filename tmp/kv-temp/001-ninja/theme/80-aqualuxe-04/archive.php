<?php get_header(); ?>
<main id="content" class="container mx-auto px-4 py-10" tabindex="-1">
  <header class="mb-6"><h1 class="text-3xl font-semibold"><?php the_archive_title(); ?></h1></header>
  <?php if (have_posts()): echo '<div class="grid gap-6 md:grid-cols-3">'; while(have_posts()): the_post(); ?>
    <article <?php post_class('p-4 border rounded'); ?>>
      <a href="<?php the_permalink(); ?>" class="block">
        <h2 class="text-xl font-medium mb-1"><?php the_title(); ?></h2>
        <div class="text-sm opacity-80"><?php the_excerpt(); ?></div>
      </a>
    </article>
  <?php endwhile; echo '</div>'; the_posts_pagination(); else: ?>
    <p><?php esc_html_e('Nothing here yet.', 'aqualuxe'); ?></p>
  <?php endif; ?>
 </main>
<?php get_footer(); ?>