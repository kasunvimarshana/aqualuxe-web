<?php if (!defined('ABSPATH')) exit; get_header(); ?>
<div class="mx-auto px-4 <?php echo esc_attr(get_theme_mod('aqualuxe_container_width','max-w-7xl')); ?> py-8">
  <?php if (shortcode_exists('ax_product_filters')) echo do_shortcode('[ax_product_filters]'); ?>
  <?php woocommerce_content(); ?>
</div>
<?php get_footer(); ?>
