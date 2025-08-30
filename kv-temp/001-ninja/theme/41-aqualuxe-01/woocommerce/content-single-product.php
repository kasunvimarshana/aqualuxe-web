<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package AquaLuxe
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

/**
 * Hook: woocommerce_before_single_product.
 *
 * @hooked woocommerce_output_all_notices - 10
 */
do_action( 'woocommerce_before_single_product' );

if ( post_password_required() ) {
	echo get_the_password_form(); // WPCS: XSS ok.
	return;
}
?>
<div id="product-<?php the_ID(); ?>" <?php wc_product_class( 'bg-white dark:bg-dark-800 rounded-lg shadow-sm p-6 md:p-8', $product ); ?>>
	<div class="product-main grid grid-cols-1 lg:grid-cols-2 gap-8">
		<div class="product-gallery">
			<?php
			/**
			 * Hook: woocommerce_before_single_product_summary.
			 *
			 * @hooked woocommerce_show_product_sale_flash - 10
			 * @hooked woocommerce_show_product_images - 20
			 */
			do_action( 'woocommerce_before_single_product_summary' );
			?>
		</div>

		<div class="product-summary">
			<?php
			/**
			 * Hook: woocommerce_single_product_summary.
			 *
			 * @hooked woocommerce_template_single_title - 5
			 * @hooked woocommerce_template_single_rating - 10
			 * @hooked woocommerce_template_single_price - 10
			 * @hooked woocommerce_template_single_excerpt - 20
			 * @hooked woocommerce_template_single_add_to_cart - 30
			 * @hooked woocommerce_template_single_meta - 40
			 * @hooked woocommerce_template_single_sharing - 50
			 * @hooked WC_Structured_Data::generate_product_data() - 60
			 */
			do_action( 'woocommerce_single_product_summary' );
			?>

			<?php if ( aqualuxe_product_has_features() ) : ?>
			<div class="product-features mt-8">
				<h3 class="text-lg font-medium text-dark-900 dark:text-white mb-4"><?php esc_html_e( 'Key Features', 'aqualuxe' ); ?></h3>
				<ul class="space-y-2">
					<?php foreach ( aqualuxe_get_product_features() as $feature ) : ?>
						<li class="flex items-start">
							<svg class="w-5 h-5 text-primary-600 dark:text-primary-400 mr-2 flex-shrink-0 mt-0.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
							</svg>
							<span class="text-dark-700 dark:text-dark-300"><?php echo wp_kses_post( $feature ); ?></span>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
			<?php endif; ?>

			<?php if ( aqualuxe_product_has_shipping_info() ) : ?>
			<div class="product-shipping-info mt-8 p-4 bg-gray-50 dark:bg-dark-700 rounded-lg">
				<h3 class="text-lg font-medium text-dark-900 dark:text-white mb-2"><?php esc_html_e( 'Shipping Information', 'aqualuxe' ); ?></h3>
				<div class="text-dark-700 dark:text-dark-300 text-sm">
					<?php echo wp_kses_post( aqualuxe_get_product_shipping_info() ); ?>
				</div>
			</div>
			<?php endif; ?>

			<?php if ( function_exists( 'YITH_WCWL' ) || function_exists( 'YITH_WOOCOMPARE' ) ) : ?>
			<div class="product-actions flex items-center space-x-4 mt-8 pt-4 border-t border-gray-200 dark:border-dark-700">
				<?php if ( function_exists( 'YITH_WCWL' ) ) : ?>
					<div class="wishlist-button">
						<?php echo do_shortcode( '[yith_wcwl_add_to_wishlist]' ); ?>
					</div>
				<?php endif; ?>

				<?php if ( function_exists( 'YITH_WOOCOMPARE' ) ) : ?>
					<div class="compare-button">
						<?php echo do_shortcode( '[yith_compare_button]' ); ?>
					</div>
				<?php endif; ?>
			</div>
			<?php endif; ?>
		</div>
	</div>

	<div class="product-tabs mt-12">
		<?php
		/**
		 * Hook: woocommerce_after_single_product_summary.
		 *
		 * @hooked woocommerce_output_product_data_tabs - 10
		 * @hooked woocommerce_upsell_display - 15
		 * @hooked woocommerce_output_related_products - 20
		 */
		do_action( 'woocommerce_after_single_product_summary' );
		?>
	</div>
</div>

<?php do_action( 'woocommerce_after_single_product' ); ?>