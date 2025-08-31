<?php /* Blog home */ get_header(); ?>
<div class="container mx-auto px-4 py-10 grid grid-cols-1 md:grid-cols-3 gap-8">
  <main class="md:col-span-2">
    <?php if (have_posts()): while(have_posts()): the_post(); ?>
      <article <?php post_class('mb-8'); ?>>
        <a class="block" href="<?php the_permalink(); ?>">
          <h2 class="text-2xl font-semibold mb-1"><?php the_title(); ?></h2>
          <p class="opacity-80 text-sm mb-2"><?php echo esc_html(get_the_date()); ?></p>
          <div class="prose max-w-none"><?php the_excerpt(); ?></div>
        </a>
      </article>
    <?php endwhile; the_posts_pagination(); else: ?>
      <p><?php esc_html_e('No posts yet.', 'aqualuxe'); ?></p>
    <?php endif; ?>
  </main>
  <aside>
    <?php dynamic_sidebar('sidebar-1'); ?>
  </aside>
</div>
<?php get_footer(); ?>
