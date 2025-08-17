<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package AquaLuxe
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

// Ensure visibility.
if ( empty( $product ) || ! $product->is_visible() ) {
	return;
}

// Extra classes
$classes = array('product-card');
?>
<li <?php wc_product_class( $classes, $product ); ?>>
	<div class="product-card-inner bg-white dark:bg-dark-800 rounded-lg shadow-sm overflow-hidden h-full flex flex-col transition-all duration-300 hover:shadow-md">
		<?php
		/**
		 * Hook: woocommerce_before_shop_loop_item.
		 *
		 * @hooked woocommerce_template_loop_product_link_open - 10
		 */
		do_action( 'woocommerce_before_shop_loop_item' );
		?>

		<div class="product-card-media relative">
			<?php
			/**
			 * Hook: woocommerce_before_shop_loop_item_title.
			 *
			 * @hooked woocommerce_show_product_loop_sale_flash - 10
			 * @hooked woocommerce_template_loop_product_thumbnail - 10
			 */
			do_action( 'woocommerce_before_shop_loop_item_title' );
			?>

			<?php if ( aqualuxe_is_product_new( $product ) ) : ?>
				<span class="product-badge product-badge-new absolute top-2 left-2 bg-secondary-500 text-white text-xs px-2 py-1 rounded-full">
					<?php esc_html_e( 'New', 'aqualuxe' ); ?>
				</span>
			<?php endif; ?>

			<?php if ( $product->is_on_sale() ) : ?>
				<span class="product-badge product-badge-sale absolute top-2 right-2 bg-accent-500 text-white text-xs px-2 py-1 rounded-full">
					<?php 
					if ( $product->is_type( 'variable' ) ) {
						echo esc_html__( 'Sale', 'aqualuxe' );
					} else {
						echo aqualuxe_get_sale_percentage( $product );
					}
					?>
				</span>
			<?php endif; ?>

			<div class="product-card-actions absolute bottom-2 right-2 flex flex-col space-y-2 opacity-0 group-hover:opacity-100 transition-opacity">
				<?php if ( function_exists( 'YITH_WCWL' ) ) : ?>
					<?php echo do_shortcode( '[yith_wcwl_add_to_wishlist product_id="' . $product->get_id() . '" icon="heart" label="" browse_wishlist_text="" already_in_wishlist_text="" product_added_text=""]' ); ?>
				<?php endif; ?>

				<?php if ( function_exists( 'YITH_WOOCOMPARE' ) ) : ?>
					<?php echo do_shortcode( '[yith_compare_button product_id="' . $product->get_id() . '" icon="repeat" label=""]' ); ?>
				<?php endif; ?>

				<?php if ( aqualuxe_quick_view_enabled() ) : ?>
					<a href="#" class="aqualuxe-quick-view bg-white dark:bg-dark-700 text-dark-900 dark:text-white w-8 h-8 rounded-full flex items-center justify-center shadow-sm hover:bg-gray-100 dark:hover:bg-dark-600 transition-colors" data-product-id="<?php echo esc_attr( $product->get_id() ); ?>" aria-label="<?php esc_attr_e( 'Quick view', 'aqualuxe' ); ?>">
						<svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
						</svg>
					</a>
				<?php endif; ?>
			</div>
		</div>

		<div class="product-card-content p-4 flex-grow flex flex-col">
			<div class="product-card-categories mb-1">
				<?php echo wc_get_product_category_list( $product->get_id(), ', ', '<span class="text-xs text-dark-500 dark:text-dark-400">', '</span>' ); ?>
			</div>

			<?php
			/**
			 * Hook: woocommerce_shop_loop_item_title.
			 *
			 * @hooked woocommerce_template_loop_product_title - 10
			 */
			do_action( 'woocommerce_shop_loop_item_title' );
			?>

			<?php
			/**
			 * Hook: woocommerce_after_shop_loop_item_title.
			 *
			 * @hooked woocommerce_template_loop_rating - 5
			 * @hooked woocommerce_template_loop_price - 10
			 */
			do_action( 'woocommerce_after_shop_loop_item_title' );
			?>

			<div class="product-card-footer mt-auto pt-4">
				<?php
				/**
				 * Hook: woocommerce_after_shop_loop_item.
				 *
				 * @hooked woocommerce_template_loop_product_link_close - 5
				 * @hooked woocommerce_template_loop_add_to_cart - 10
				 */
				do_action( 'woocommerce_after_shop_loop_item' );
				?>
			</div>
		</div>
	</div>
</li>