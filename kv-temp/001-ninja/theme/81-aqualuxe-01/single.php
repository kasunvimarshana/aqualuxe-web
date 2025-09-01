<?php if (!defined('ABSPATH')) exit; get_header(); ?>
<div class="mx-auto px-4 <?php echo esc_attr(get_theme_mod('aqualuxe_container_width','max-w-7xl')); ?> py-8">
<?php if (have_posts()): while(have_posts()): the_post(); ?>
  <article <?php post_class('prose dark:prose-invert max-w-none'); ?>>
    <h1 class="text-3xl font-bold"><?php the_title(); ?></h1>
    <?php the_content(); ?>
  </article>
<?php endwhile; endif; ?>
</div>
<?php get_footer(); ?>
