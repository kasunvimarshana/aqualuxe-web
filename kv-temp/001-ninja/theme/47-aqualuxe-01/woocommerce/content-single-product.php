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
<div id="product-<?php the_ID(); ?>" <?php wc_product_class( 'single-product-details', $product ); ?>>

	<div class="product-main-content">
		<div class="product-gallery-summary">
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

				<?php
				// Display vendor information if enabled
				if (aqualuxe_get_theme_option('aqualuxe_vendor_display', false) && function_exists('aqualuxe_get_product_vendor')) :
					$vendor = aqualuxe_get_product_vendor($product->get_id());
					if ($vendor) :
				?>
					<div class="product-vendor mt-4 pt-4 border-t border-gray-200">
						<h4 class="text-sm font-medium mb-2"><?php esc_html_e('Sold by:', 'aqualuxe'); ?></h4>
						<div class="vendor-info flex items-center">
							<?php if (!empty($vendor['logo'])) : ?>
								<img src="<?php echo esc_url($vendor['logo']); ?>" alt="<?php echo esc_attr($vendor['name']); ?>" class="vendor-logo w-10 h-10 rounded-full mr-3">
							<?php endif; ?>
							<div class="vendor-details">
								<h5 class="vendor-name font-medium"><?php echo esc_html($vendor['name']); ?></h5>
								<?php if (!empty($vendor['rating'])) : ?>
									<div class="vendor-rating flex items-center text-sm">
										<div class="star-rating" role="img" aria-label="<?php echo sprintf(__('Rated %s out of 5', 'aqualuxe'), $vendor['rating']); ?>">
											<span style="width: <?php echo esc_attr($vendor['rating'] / 5 * 100); ?>%;">
												<?php echo sprintf(__('Rated %s out of 5', 'aqualuxe'), $vendor['rating']); ?>
											</span>
										</div>
										<?php if (!empty($vendor['rating_count'])) : ?>
											<span class="rating-count text-xs text-gray-500 ml-1">(<?php echo esc_html($vendor['rating_count']); ?>)</span>
										<?php endif; ?>
									</div>
								<?php endif; ?>
							</div>
						</div>
					</div>
				<?php
					endif;
				endif;
				?>

				<?php
				// Display estimated delivery if available
				if (function_exists('aqualuxe_get_estimated_delivery')) :
					$estimated_delivery = aqualuxe_get_estimated_delivery($product->get_id());
					if ($estimated_delivery) :
				?>
					<div class="estimated-delivery mt-4 pt-4 border-t border-gray-200">
						<div class="flex items-center">
							<?php aqualuxe_svg_icon('clock', array('class' => 'w-5 h-5 mr-2 text-primary-600')); ?>
							<div>
								<span class="delivery-label text-sm font-medium"><?php esc_html_e('Estimated Delivery:', 'aqualuxe'); ?></span>
								<span class="delivery-date text-sm ml-1"><?php echo esc_html($estimated_delivery); ?></span>
							</div>
						</div>
					</div>
				<?php
					endif;
				endif;
				?>

				<?php
				// Display product availability
				if (function_exists('aqualuxe_product_availability')) :
					$availability = aqualuxe_product_availability($product->get_id());
					if ($availability) :
				?>
					<div class="product-availability mt-4">
						<div class="flex items-center">
							<?php if ($availability['status'] === 'in_stock') : ?>
								<?php aqualuxe_svg_icon('check-circle', array('class' => 'w-5 h-5 mr-2 text-green-600')); ?>
								<span class="availability-text text-sm text-green-600"><?php echo esc_html($availability['text']); ?></span>
							<?php elseif ($availability['status'] === 'low_stock') : ?>
								<?php aqualuxe_svg_icon('alert-circle', array('class' => 'w-5 h-5 mr-2 text-orange-600')); ?>
								<span class="availability-text text-sm text-orange-600"><?php echo esc_html($availability['text']); ?></span>
							<?php else : ?>
								<?php aqualuxe_svg_icon('x-circle', array('class' => 'w-5 h-5 mr-2 text-red-600')); ?>
								<span class="availability-text text-sm text-red-600"><?php echo esc_html($availability['text']); ?></span>
							<?php endif; ?>
						</div>
					</div>
				<?php
					endif;
				endif;
				?>

				<?php
				// Display product actions (wishlist, compare, etc.)
				if (aqualuxe_get_theme_option('aqualuxe_wishlist', true)) :
					$in_wishlist = false;
					if (function_exists('aqualuxe_is_product_in_wishlist')) {
						$in_wishlist = aqualuxe_is_product_in_wishlist($product->get_id());
					}
				?>
					<div class="product-actions mt-6 flex items-center space-x-4">
						<button class="wishlist-button flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:text-primary-600 hover:border-primary-600 transition-colors duration-200 <?php echo $in_wishlist ? 'in-wishlist' : ''; ?>" data-product-id="<?php echo esc_attr($product->get_id()); ?>">
							<?php aqualuxe_svg_icon('heart', array('class' => 'w-5 h-5 mr-2')); ?>
							<span><?php echo $in_wishlist ? esc_html__('Remove from Wishlist', 'aqualuxe') : esc_html__('Add to Wishlist', 'aqualuxe'); ?></span>
						</button>

						<?php if (function_exists('aqualuxe_product_compare_button')) : ?>
							<?php aqualuxe_product_compare_button($product->get_id()); ?>
						<?php endif; ?>
					</div>
				<?php endif; ?>

				<?php
				// Social sharing
				if (function_exists('aqualuxe_product_share_buttons')) :
				?>
					<div class="product-share mt-6 pt-6 border-t border-gray-200">
						<h4 class="text-sm font-medium mb-2"><?php esc_html_e('Share this product:', 'aqualuxe'); ?></h4>
						<?php aqualuxe_product_share_buttons(); ?>
					</div>
				<?php endif; ?>

				<?php
				// Trust badges
				if (function_exists('aqualuxe_product_trust_badges')) :
				?>
					<div class="product-trust-badges mt-6 pt-6 border-t border-gray-200">
						<?php aqualuxe_product_trust_badges(); ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>

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

<?php do_action( 'woocommerce_after_single_product' ); ?>