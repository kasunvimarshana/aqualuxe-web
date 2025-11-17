<?php
/**
 * Related Products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/related.php.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package AquaLuxe
 * @version 3.9.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( $related_products ) : ?>

	<section class="related products mt-12 pt-8 border-t border-gray-200 dark:border-dark-600">
		<?php
		$heading = apply_filters( 'woocommerce_product_related_products_heading', __( 'Related products', 'aqualuxe' ) );

		if ( $heading ) :
			?>
			<h2 class="text-2xl font-serif font-medium mb-6"><?php echo esc_html( $heading ); ?></h2>
		<?php endif; ?>
		
		<div class="relative">
			<?php woocommerce_product_loop_start(); ?>

				<?php foreach ( $related_products as $related_product ) : ?>

						<?php
						$post_object = get_post( $related_product->get_id() );

						setup_postdata( $GLOBALS['post'] =& $post_object ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited, Squiz.PHP.DisallowMultipleAssignments.Found

						wc_get_template_part( 'content', 'product' );
						?>

				<?php endforeach; ?>

			<?php woocommerce_product_loop_end(); ?>
			
			<!-- Navigation arrows for slider -->
			<div class="absolute top-1/2 -left-4 transform -translate-y-1/2">
				<button class="related-prev bg-white dark:bg-dark-700 text-dark-700 dark:text-white p-2 rounded-full shadow-md hover:bg-gray-100 dark:hover:bg-dark-600 transition-colors duration-200">
					<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
					</svg>
					<span class="sr-only"><?php esc_html_e( 'Previous', 'aqualuxe' ); ?></span>
				</button>
			</div>
			<div class="absolute top-1/2 -right-4 transform -translate-y-1/2">
				<button class="related-next bg-white dark:bg-dark-700 text-dark-700 dark:text-white p-2 rounded-full shadow-md hover:bg-gray-100 dark:hover:bg-dark-600 transition-colors duration-200">
					<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
					</svg>
					<span class="sr-only"><?php esc_html_e( 'Next', 'aqualuxe' ); ?></span>
				</button>
			</div>
		</div>
	</section>
	<?php
endif;

wp_reset_postdata();