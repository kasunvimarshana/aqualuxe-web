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
?>
<li <?php wc_product_class( 'product-card bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow duration-300 overflow-hidden', $product ); ?>>
	<?php
	/**
	 * Hook: woocommerce_before_shop_loop_item.
	 *
	 * @hooked woocommerce_template_loop_product_link_open - 10
	 */
	do_action( 'woocommerce_before_shop_loop_item' );
	?>

	<div class="product-image relative">
		<?php
		/**
		 * Hook: woocommerce_before_shop_loop_item_title.
		 *
		 * @hooked woocommerce_show_product_loop_sale_flash - 10
		 * @hooked woocommerce_template_loop_product_thumbnail - 10
		 */
		do_action( 'woocommerce_before_shop_loop_item_title' );
		?>

		<?php if ( $product->is_on_sale() ) : ?>
			<span class="onsale absolute top-4 left-4 bg-red-500 text-white text-xs font-bold uppercase py-1 px-2 rounded-sm z-10">
				<?php esc_html_e( 'Sale', 'aqualuxe' ); ?>
			</span>
		<?php endif; ?>

		<?php if ( ! $product->is_in_stock() ) : ?>
			<span class="out-of-stock absolute top-4 left-4 bg-gray-500 text-white text-xs font-bold uppercase py-1 px-2 rounded-sm z-10">
				<?php esc_html_e( 'Out of Stock', 'aqualuxe' ); ?>
			</span>
		<?php endif; ?>

		<div class="product-actions absolute bottom-0 left-0 right-0 p-4 bg-gradient-to-t from-black to-transparent opacity-0 hover:opacity-100 transition-opacity duration-300 flex justify-center">
			<?php
			// Quick view button
			if ( function_exists( 'aqualuxe_add_quick_view_button' ) ) {
				echo '<button class="quick-view-button bg-white text-gray-700 p-2 rounded-full shadow-sm hover:shadow mx-1" data-product-id="' . esc_attr( $product->get_id() ) . '" aria-label="' . esc_attr__( 'Quick view', 'aqualuxe' ) . '">';
				echo '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>';
				echo '</button>';
			}

			// Wishlist button
			if ( function_exists( 'aqualuxe_add_wishlist_button' ) ) {
				echo '<button class="wishlist-button bg-white text-gray-700 p-2 rounded-full shadow-sm hover:shadow mx-1" data-product-id="' . esc_attr( $product->get_id() ) . '" aria-label="' . esc_attr__( 'Add to wishlist', 'aqualuxe' ) . '">';
				echo '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>';
				echo '</button>';
			}
			?>
		</div>
	</div>

	<div class="product-details p-4">
		<?php
		/**
		 * Hook: woocommerce_shop_loop_item_title.
		 *
		 * @hooked woocommerce_template_loop_product_title - 10
		 */
		do_action( 'woocommerce_shop_loop_item_title' );

		/**
		 * Hook: woocommerce_after_shop_loop_item_title.
		 *
		 * @hooked woocommerce_template_loop_rating - 5
		 * @hooked woocommerce_template_loop_price - 10
		 */
		do_action( 'woocommerce_after_shop_loop_item_title' );
		?>

		<div class="product-meta flex flex-wrap items-center justify-between mt-2 mb-4">
			<?php if ( $product->get_rating_count() > 0 ) : ?>
				<div class="star-rating">
					<?php echo wc_get_rating_html( $product->get_average_rating() ); ?>
				</div>
			<?php endif; ?>

			<?php if ( $product->get_review_count() > 0 ) : ?>
				<div class="review-count text-sm text-gray-500">
					<?php echo esc_html( sprintf( _n( '%s review', '%s reviews', $product->get_review_count(), 'aqualuxe' ), $product->get_review_count() ) ); ?>
				</div>
			<?php endif; ?>
		</div>

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
</li>