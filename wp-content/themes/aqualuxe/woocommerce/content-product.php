<?php
/**
 * Product Loop Item override for AquaLuxe
 */
if (!defined('ABSPATH')) { exit; }
?>
<li <?php wc_product_class('group rounded-lg p-3 bg-white dark:bg-slate-800 card', $product); ?>>
	<?php do_action('woocommerce_before_shop_loop_item'); ?>

	<a href="<?php the_permalink(); ?>" class="block mb-2">
		<?php do_action('woocommerce_before_shop_loop_item_title'); ?>
	</a>

	<h2 class="woocommerce-loop-product__title text-lg font-semibold mb-1"><?php the_title(); ?></h2>
	<?php do_action('woocommerce_after_shop_loop_item_title'); ?>

	<div class="flex items-center gap-2 mt-2">
		<a href="<?php the_permalink(); ?>" class="btn-outline"><?php esc_html_e('View', 'aqualuxe'); ?></a>
		<button class="btn-primary" data-aqlx-quickview data-product-id="<?php echo esc_attr(get_the_ID()); ?>"><?php esc_html_e('Quick View', 'aqualuxe'); ?></button>
		<button class="btn" data-aqlx-wishlist data-product-id="<?php echo esc_attr(get_the_ID()); ?>" aria-pressed="false">♡</button>
		<?php do_action('woocommerce_after_shop_loop_item'); ?>
	</div>
</li>
