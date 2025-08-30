<?php defined('ABSPATH') || exit; get_header(); ?>
<div class="<?php echo esc_attr(aqlx_container_class()); ?> py-8">
  <h1 class="text-3xl font-bold mb-6"><?php esc_html_e('Services', 'aqualuxe'); ?></h1>
  <?php if (have_posts()) : ?>
    <div class="grid md:grid-cols-3 gap-6">
      <?php while (have_posts()) : the_post(); ?>
        <article <?php post_class('border rounded-lg p-6 bg-white dark:bg-slate-800'); ?>>
          <h2 class="text-xl font-semibold mb-2"><a href="<?php the_permalink(); ?>" class="hover:underline"><?php the_title(); ?></a></h2>
          <div class="entry-summary"><?php the_excerpt(); ?></div>
        </article>
      <?php endwhile; ?>
    </div>
    <?php the_posts_pagination(); ?>
  <?php else: ?>
    <p><?php esc_html_e('No services found.', 'aqualuxe'); ?></p>
  <?php endif; ?>
</div>
<?php get_footer(); ?>
