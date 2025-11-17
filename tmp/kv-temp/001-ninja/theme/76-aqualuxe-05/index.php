<?php
defined('ABSPATH') || exit;
get_header(); ?>

<div class="<?php echo esc_attr(aqlx_container_class()); ?> py-8">
  <?php if (have_posts()) : ?>
    <div class="grid md:grid-cols-3 gap-6">
      <?php while (have_posts()) : the_post(); ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class('border rounded-lg p-4 bg-white dark:bg-slate-800'); ?>>
          <header>
            <h2 class="text-xl font-semibold mb-2"><a href="<?php the_permalink(); ?>" class="hover:underline"><?php the_title(); ?></a></h2>
            <?php aqlx_posted_on(); ?>
          </header>
          <div class="entry-content mt-3">
            <?php the_excerpt(); ?>
          </div>
        </article>
      <?php endwhile; ?>
    </div>
    <div class="mt-8 flex justify-between">
      <div class="nav-previous"><?php previous_posts_link('&laquo; ' . esc_html__('Newer', 'aqualuxe')); ?></div>
      <div class="nav-next"><?php next_posts_link(esc_html__('Older', 'aqualuxe') . ' &raquo;'); ?></div>
    </div>
  <?php else : ?>
    <p><?php esc_html_e('No content found.', 'aqualuxe'); ?></p>
  <?php endif; ?>
</div>

<?php get_footer();
