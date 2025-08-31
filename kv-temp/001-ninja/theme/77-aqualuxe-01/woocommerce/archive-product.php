<?php
/**
 * WooCommerce product archive
 */
if (!defined('ABSPATH')) exit;
get_header('shop'); ?>
<div class="container mx-auto px-4 py-10">
  <header class="woocommerce-products-header">
    <h1 class="text-3xl font-bold mb-6"><?php echo function_exists('woocommerce_page_title') ? esc_html(call_user_func('woocommerce_page_title', false)) : esc_html__('Shop','aqualuxe'); ?></h1>
    <?php if (function_exists('do_action')) call_user_func('do_action','woocommerce_archive_description'); ?>
  </header>
  <?php if (function_exists('woocommerce_product_loop') && call_user_func('woocommerce_product_loop')) { ?>
    <?php if (function_exists('do_action')) call_user_func('do_action','woocommerce_before_shop_loop'); ?>
    <?php if (function_exists('woocommerce_product_loop_start')) call_user_func('woocommerce_product_loop_start'); ?>
    <?php while (have_posts()) { the_post(); if (function_exists('do_action')) call_user_func('do_action','woocommerce_shop_loop'); if (function_exists('wc_get_template_part')) call_user_func('wc_get_template_part','content','product'); } ?>
    <?php if (function_exists('woocommerce_product_loop_end')) call_user_func('woocommerce_product_loop_end'); ?>
    <?php if (function_exists('do_action')) call_user_func('do_action','woocommerce_after_shop_loop'); ?>
  <?php } else { if (function_exists('do_action')) call_user_func('do_action','woocommerce_no_products_found'); } ?>
</div>
<?php get_footer('shop'); ?>
