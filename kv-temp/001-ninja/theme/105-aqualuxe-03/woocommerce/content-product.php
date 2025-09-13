<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

defined('ABSPATH') || exit;

global $product;

// Ensure visibility.
if (empty($product) || !$product->is_visible()) {
	return;
}
?>
<li <?php wc_product_class('product-item relative bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden group transition-all duration-300 hover:shadow-xl hover:-translate-y-1', $product); ?>>
	
	<div class="product-image-wrapper relative overflow-hidden">
		<?php
		/**
		 * Hook: woocommerce_before_shop_loop_item
		 *
		 * @hooked woocommerce_template_loop_product_link_open - 10
		 */
		do_action('woocommerce_before_shop_loop_item');

		/**
		 * Hook: woocommerce_before_shop_loop_item_title
		 *
		 * @hooked woocommerce_show_product_loop_sale_flash - 10
		 * @hooked woocommerce_template_loop_product_thumbnail - 10
		 */
		do_action('woocommerce_before_shop_loop_item_title');
		?>
		
		<!-- Quick Actions Overlay -->
		<div class="product-actions absolute inset-0 bg-black bg-opacity-40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
			<div class="action-buttons flex space-x-2">
				
				<!-- Quick View Button -->
				<button type="button" 
					class="quick-view-btn bg-white dark:bg-gray-800 text-gray-800 dark:text-white p-3 rounded-full shadow-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
					data-product-id="<?php echo esc_attr($product->get_id()); ?>"
					title="<?php esc_attr_e('Quick View', 'aqualuxe'); ?>">
					<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
					</svg>
				</button>
				
				<!-- Add to Wishlist Button -->
				<?php if (is_user_logged_in()) : ?>
					<?php
					$wishlist = get_user_meta(get_current_user_id(), 'aqualuxe_wishlist', true);
					$wishlist = is_array($wishlist) ? $wishlist : array();
					$in_wishlist = in_array($product->get_id(), $wishlist);
					?>
					<button type="button" 
						class="wishlist-btn bg-white dark:bg-gray-800 text-gray-800 dark:text-white p-3 rounded-full shadow-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors <?php echo $in_wishlist ? 'text-red-600' : ''; ?>"
						data-product-id="<?php echo esc_attr($product->get_id()); ?>"
						data-in-wishlist="<?php echo esc_attr($in_wishlist ? 'true' : 'false'); ?>"
						title="<?php esc_attr_e($in_wishlist ? 'Remove from wishlist' : 'Add to wishlist', 'aqualuxe'); ?>">
						<svg class="w-5 h-5" fill="<?php echo $in_wishlist ? 'currentColor' : 'none'; ?>" stroke="currentColor" viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
						</svg>
					</button>
				<?php endif; ?>
				
			</div>
		</div>
	</div>

	<div class="product-content p-4">
		<?php
		/**
		 * Hook: woocommerce_shop_loop_item_title
		 *
		 * @hooked woocommerce_template_loop_product_title - 10
		 */
		do_action('woocommerce_shop_loop_item_title');

		/**
		 * Hook: woocommerce_after_shop_loop_item_title
		 *
		 * @hooked woocommerce_template_loop_rating - 5
		 * @hooked woocommerce_template_loop_price - 10
		 */
		do_action('woocommerce_after_shop_loop_item_title');
		?>
		
		<!-- Product Short Description -->
		<?php if ($product->get_short_description()) : ?>
			<div class="product-excerpt text-sm text-gray-600 dark:text-gray-400 mb-4 line-clamp-2">
				<?php echo wp_kses_post($product->get_short_description()); ?>
			</div>
		<?php endif; ?>
		
		<!-- Stock Status -->
		<div class="stock-status mb-4">
			<?php if ($product->is_in_stock()) : ?>
				<span class="in-stock inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">
					<svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
						<path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
					</svg>
					<?php esc_html_e('In Stock', 'aqualuxe'); ?>
				</span>
			<?php else : ?>
				<span class="out-of-stock inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100">
					<svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
						<path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
					</svg>
					<?php esc_html_e('Out of Stock', 'aqualuxe'); ?>
				</span>
			<?php endif; ?>
		</div>

		<?php
		/**
		 * Hook: woocommerce_after_shop_loop_item
		 *
		 * @hooked woocommerce_template_loop_product_link_close - 5
		 * @hooked woocommerce_template_loop_add_to_cart - 10
		 */
		do_action('woocommerce_after_shop_loop_item');
		?>
	</div>

</li>