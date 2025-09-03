<?php
/**
 * Product loop content
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

global $product;
?>
<li <?php if ( function_exists('wc_product_class') ) { call_user_func('wc_product_class','group relative'); } ?> >
	<?php if ( function_exists('do_action') ) { call_user_func('do_action','woocommerce_before_shop_loop_item'); } ?>
	<a href="<?php if ( function_exists('the_permalink') ) { call_user_func('the_permalink'); } ?>" class="block overflow-hidden rounded-md bg-white/5 hover:bg-white/10 transition p-4" data-quick-view="true" data-product-id="<?php echo function_exists('esc_attr') && $product ? call_user_func('esc_attr', $product->get_id() ) : ''; ?>">
		<?php if ( function_exists('do_action') ) { call_user_func('do_action','woocommerce_before_shop_loop_item_title'); } ?>
		<h2 class="woocommerce-loop-product__title mt-3 text-white font-semibold"><?php if ( function_exists('the_title') ) { call_user_func('the_title'); } ?></h2>
		<?php if ( function_exists('do_action') ) { call_user_func('do_action','woocommerce_after_shop_loop_item_title'); } ?>
	</a>
	<div class="mt-3 flex items-center justify-between gap-3">
		<?php if ( function_exists('woocommerce_template_loop_price') ) { call_user_func('woocommerce_template_loop_price'); } ?>
		<button class="btn btn-ghost" data-wishlist data-product-id="<?php echo function_exists('esc_attr') && $product ? call_user_func('esc_attr', $product->get_id() ) : ''; ?>"><?php echo function_exists('esc_html__') ? call_user_func('esc_html__','Wishlist','aqualuxe') : 'Wishlist'; ?></button>
	</div>
	<?php if ( function_exists('do_action') ) { call_user_func('do_action','woocommerce_after_shop_loop_item'); } ?>
</li>
