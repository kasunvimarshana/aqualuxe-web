<?php if (!defined('ABSPATH')) exit; get_header(); ?>
<div class="mx-auto px-4 <?php echo esc_attr(get_theme_mod('aqualuxe_container_width','max-w-7xl')); ?> py-8">
<?php if (have_posts()): while(have_posts()): the_post(); ?>
  <article <?php post_class('prose dark:prose-invert max-w-none'); ?><?php echo aqualuxe_schema_attr('Article'); ?>>
    <h2 class="text-2xl font-bold"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
    <div class="entry-summary"><?php the_excerpt(); ?></div>
  </article>
  <hr class="my-6" />
<?php endwhile; the_posts_pagination(); else: ?>
  <p><?php esc_html_e('No content found.','aqualuxe'); ?></p>
<?php endif; ?>
</div>
<?php get_footer(); ?>
