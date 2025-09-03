<?php
defined( 'ABSPATH' ) || exit;
global $product;
if ( empty( $product ) || ! $product->is_visible() ) {
	return;
}
?>
<li <?php if (function_exists('wc_product_class')) { call_user_func('wc_product_class', 'card border border-slate-200 dark:border-slate-800 rounded-lg overflow-hidden', $product); } else { echo 'class="card"'; } ?>>
  <?php if (function_exists('do_action')) { call_user_func('do_action', 'woocommerce_before_shop_loop_item'); } ?>
  <?php if (function_exists('do_action')) { call_user_func('do_action', 'woocommerce_before_shop_loop_item_title'); } ?>
  <div class="p-4">
    <?php if (function_exists('do_action')) { call_user_func('do_action', 'woocommerce_shop_loop_item_title'); } ?>
    <?php if (function_exists('do_action')) { call_user_func('do_action', 'woocommerce_after_shop_loop_item_title'); } ?>
  </div>
  <?php if (function_exists('do_action')) { call_user_func('do_action', 'woocommerce_after_shop_loop_item'); } ?>
</li>
