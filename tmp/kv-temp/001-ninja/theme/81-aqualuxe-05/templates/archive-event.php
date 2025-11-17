<?php if (!defined('ABSPATH')) exit; get_header(); ?>
<div class="mx-auto px-4 <?php echo esc_attr(get_theme_mod('aqualuxe_container_width','max-w-7xl')); ?> py-8">
  <?php echo do_shortcode('[ax_breadcrumbs]'); ?>
  <h1 class="text-3xl font-bold mb-6"><?php esc_html_e('Events','aqualuxe'); ?></h1>
  <div class="grid md:grid-cols-3 gap-6">
  <?php if (have_posts()): while(have_posts()): the_post(); ?>
    <article class="border rounded p-4">
      <a href="<?php the_permalink(); ?>" class="block font-semibold mb-2"><?php the_title(); ?></a>
      <div class="opacity-75 text-sm mb-2"><?php echo esc_html( get_post_meta(get_the_ID(),'event_date', true) ); ?></div>
      <div class="text-sm"><?php the_excerpt(); ?></div>
    </article>
  <?php endwhile; else: ?>
    <p><?php esc_html_e('No events found.','aqualuxe'); ?></p>
  <?php endif; ?>
  </div>
</div>
<?php get_footer(); ?>
