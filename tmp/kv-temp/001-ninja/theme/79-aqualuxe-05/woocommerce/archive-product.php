<?php
defined('ABSPATH') || exit;
get_header('shop'); ?>
<div class="container max-w-7xl mx-auto px-4 py-10">
  <h1 class="text-3xl font-semibold mb-6"><?php echo function_exists('woocommerce_page_title') ? esc_html((string) call_user_func('woocommerce_page_title', false)) : esc_html__('Shop', 'aqualuxe'); ?></h1>
  <?php if (function_exists('woocommerce_product_loop')): ?>
    <?php do_action('woocommerce_before_main_content'); ?>
    <?php if (call_user_func('woocommerce_product_loop')) { if (function_exists('woocommerce_output_all_notices')) { call_user_func('woocommerce_output_all_notices'); } if (function_exists('woocommerce_catalog_ordering')) { call_user_func('woocommerce_catalog_ordering'); } if (function_exists('woocommerce_product_loop_start')) { call_user_func('woocommerce_product_loop_start'); } while (have_posts()) { the_post(); do_action('woocommerce_shop_loop'); if (function_exists('wc_get_template_part')) { call_user_func('wc_get_template_part','content','product'); } } if (function_exists('woocommerce_product_loop_end')) { call_user_func('woocommerce_product_loop_end'); } do_action('woocommerce_after_shop_loop'); } else { do_action('woocommerce_no_products_found'); } ?>
    <?php do_action('woocommerce_after_main_content'); ?>
  <?php else: ?>
    <p><?php esc_html_e('WooCommerce is not active.', 'aqualuxe'); ?></p>
  <?php endif; ?>
</div>
<?php get_footer('shop'); ?>
