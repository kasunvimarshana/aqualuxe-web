<?php if (!defined('ABSPATH')) exit; get_header(); ?>
<div class="mx-auto px-4 <?php echo esc_attr(get_theme_mod('aqualuxe_container_width','max-w-7xl')); ?> py-12 text-center">
  <h1 class="text-5xl font-black">404</h1>
  <p class="mt-4 opacity-75"><?php esc_html_e('Sorry, we couldn\'t find that page.','aqualuxe'); ?></p>
  <a class="ax-btn mt-6" href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Go Home','aqualuxe'); ?></a>
</div>
<?php get_footer(); ?>
