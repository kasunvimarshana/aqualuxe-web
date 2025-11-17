<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package AquaLuxe
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

// Ensure visibility.
if ( empty( $product ) || ! $product->is_visible() ) {
	return;
}
?>
<li <?php wc_product_class( '', $product ); ?>>
	<div class="product-inner">
		<?php
		/**
		 * Hook: woocommerce_before_shop_loop_item.
		 *
		 * @hooked woocommerce_template_loop_product_link_open - 10
		 */
		do_action( 'woocommerce_before_shop_loop_item' );

		/**
		 * Hook: woocommerce_before_shop_loop_item_title.
		 *
		 * @hooked woocommerce_show_product_loop_sale_flash - 10
		 * @hooked woocommerce_template_loop_product_thumbnail - 10
		 * @hooked aqualuxe_woocommerce_show_product_badges - 9
		 * @hooked aqualuxe_woocommerce_before_shop_loop_item_title - 10
		 */
		?>
		<div class="product-thumbnail-wrapper">
			<?php
			// Sale flash and badges
			woocommerce_show_product_loop_sale_flash();
			
			// Custom badges
			if ( function_exists( 'aqualuxe_woocommerce_show_product_badges' ) ) {
				aqualuxe_woocommerce_show_product_badges();
			}
			
			// Product thumbnail
			woocommerce_template_loop_product_thumbnail();
			
			// Quick view and wishlist buttons
			if ( function_exists( 'aqualuxe_woocommerce_before_shop_loop_item_title' ) ) {
				aqualuxe_woocommerce_before_shop_loop_item_title();
			} else {
				// Fallback if function doesn't exist
				?>
				<div class="product-actions">
					<a href="#" class="quick-view-button" data-product-id="<?php echo esc_attr( $product->get_id() ); ?>" data-toggle="tooltip" title="<?php esc_attr_e( 'Quick View', 'aqualuxe' ); ?>"><i class="fas fa-eye"></i></a>
					
					<?php if ( defined( 'YITH_WCWL' ) && function_exists( 'yith_wcwl_add_to_wishlist_button' ) ) : ?>
						<?php echo do_shortcode( '[yith_wcwl_add_to_wishlist]' ); ?>
					<?php endif; ?>
					
					<?php if ( defined( 'YITH_WOOCOMPARE' ) && function_exists( 'yith_woocompare_add_compare_link' ) ) : ?>
						<?php echo do_shortcode( '[yith_compare_button]' ); ?>
					<?php endif; ?>
				</div>
				<?php
			}
			?>
		</div>

		<div class="product-content">
			<?php
			// Product category
			$categories = wc_get_product_category_list( $product->get_id(), ', ', '<span class="product-category">', '</span>' );
			if ( $categories ) {
				echo $categories; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
			
			/**
			 * Hook: woocommerce_shop_loop_item_title.
			 *
			 * @hooked woocommerce_template_loop_product_title - 10
			 * @hooked aqualuxe_woocommerce_shop_loop_item_category - 5
			 */
			do_action( 'woocommerce_shop_loop_item_title' );

			/**
			 * Hook: woocommerce_after_shop_loop_item_title.
			 *
			 * @hooked woocommerce_template_loop_rating - 5
			 * @hooked woocommerce_template_loop_price - 10
			 * @hooked aqualuxe_woocommerce_after_shop_loop_item_title - 5
			 */
			do_action( 'woocommerce_after_shop_loop_item_title' );
			
			// Product rating
			if ( wc_review_ratings_enabled() ) {
				echo wc_get_rating_html( $product->get_average_rating() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
			
			// Product price
			woocommerce_template_loop_price();
			
			// Product excerpt for featured products
			if ( $product->is_featured() ) {
				echo '<div class="product-short-description">' . wp_trim_words( $product->get_short_description(), 15 ) . '</div>';
			}
			
			// Stock status
			if ( ! $product->is_in_stock() ) {
				echo '<div class="out-of-stock-label">' . esc_html__( 'Out of Stock', 'aqualuxe' ) . '</div>';
			} elseif ( $product->is_on_backorder() ) {
				echo '<div class="backorder-label">' . esc_html__( 'Available on backorder', 'aqualuxe' ) . '</div>';
			} elseif ( $product->managing_stock() && $product->get_stock_quantity() <= 5 && $product->get_stock_quantity() > 0 ) {
				echo '<div class="low-stock-label">' . sprintf( esc_html__( 'Only %s left in stock', 'aqualuxe' ), $product->get_stock_quantity() ) . '</div>';
			}

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
</li>