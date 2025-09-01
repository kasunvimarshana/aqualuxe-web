<?php if (!defined('ABSPATH')) exit; get_header(); ?>
<div class="mx-auto px-4 <?php echo esc_attr(get_theme_mod('aqualuxe_container_width','max-w-7xl')); ?> py-8 prose dark:prose-invert max-w-none">
<?php echo do_shortcode('[ax_breadcrumbs]'); ?>
<?php if (have_posts()): while(have_posts()): the_post(); the_content(); endwhile; endif; ?>
</div>
<?php get_footer(); ?>
