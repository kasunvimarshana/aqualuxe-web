<?php if (!defined('ABSPATH')) exit; get_header(); ?>
<div class="mx-auto px-4 <?php echo esc_attr(get_theme_mod('aqualuxe_container_width','max-w-7xl')); ?> py-8 prose dark:prose-invert max-w-none">
<?php if (have_posts()): while(have_posts()): the_post(); ?>
  <h1 class="text-3xl font-bold"><?php the_title(); ?></h1>
  <div class="opacity-75 text-sm mb-4"><?php echo esc_html( get_post_meta(get_the_ID(),'event_date', true) ); ?></div>
  <?php the_content(); ?>
<?php endwhile; endif; ?>
</div>
<?php get_footer(); ?>
