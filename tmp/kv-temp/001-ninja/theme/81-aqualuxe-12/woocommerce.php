<?php if (!defined('ABSPATH')) exit; get_header(); ?>
<div class="mx-auto px-4 <?php echo esc_attr(get_theme_mod('aqualuxe_container_width','max-w-7xl')); ?> py-8">
  <?php echo do_shortcode('[ax_breadcrumbs]'); ?>
  <?php if (shortcode_exists('ax_product_filters')) echo do_shortcode('[ax_product_filters]'); ?>
  <?php if (function_exists('woocommerce_content')) { call_user_func('woocommerce_content'); } ?>
</div>
<?php get_footer(); ?>
