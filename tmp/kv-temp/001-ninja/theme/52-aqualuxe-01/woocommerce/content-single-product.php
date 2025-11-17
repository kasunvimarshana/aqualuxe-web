<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
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
<div id="product-<?php the_ID(); ?>" <?php wc_product_class( 'bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden', $product ); ?>>
	<div class="flex flex-wrap">
		<div class="w-full lg:w-1/2 p-6">
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

		<div class="w-full lg:w-1/2 p-6">
			<div class="summary entry-summary">
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
				
				<!-- Custom Product Features Section -->
				<?php if ( $product->get_short_description() ) : ?>
				<div class="product-features mt-8 border-t border-gray-200 dark:border-gray-700 pt-6">
					<h3 class="text-xl font-bold mb-4"><?php esc_html_e( 'Key Features', 'aqualuxe' ); ?></h3>
					<div class="prose dark:prose-invert">
						<?php echo wp_kses_post( $product->get_short_description() ); ?>
					</div>
				</div>
				<?php endif; ?>
				
				<!-- Custom Shipping Information -->
				<div class="shipping-info mt-8 border-t border-gray-200 dark:border-gray-700 pt-6">
					<h3 class="text-xl font-bold mb-4"><?php esc_html_e( 'Shipping & Returns', 'aqualuxe' ); ?></h3>
					<ul class="space-y-2">
						<li class="flex items-center">
							<svg class="w-5 h-5 text-primary mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
							<?php esc_html_e( 'Free shipping on orders over $50', 'aqualuxe' ); ?>
						</li>
						<li class="flex items-center">
							<svg class="w-5 h-5 text-primary mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
							<?php esc_html_e( 'Delivery within 3-5 business days', 'aqualuxe' ); ?>
						</li>
						<li class="flex items-center">
							<svg class="w-5 h-5 text-primary mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
							<?php esc_html_e( '30-day money-back guarantee', 'aqualuxe' ); ?>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</div>

	<div class="p-6 border-t border-gray-200 dark:border-gray-700">
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