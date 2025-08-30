<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package AquaLuxe
 * @version 1.0.0
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
<div id="product-<?php the_ID(); ?>" <?php wc_product_class( 'product-details-wrapper', $product ); ?>>
	<div class="product-gallery-summary">
		<div class="product-gallery-column">
			<?php
			/**
			 * Hook: woocommerce_before_single_product_summary.
			 *
			 * @hooked woocommerce_show_product_sale_flash - 10
			 * @hooked woocommerce_show_product_images - 20
			 * @hooked aqualuxe_woocommerce_product_360_view - 20
			 */
			do_action( 'woocommerce_before_single_product_summary' );
			
			// Display product badges
			if ( function_exists( 'aqualuxe_woocommerce_show_product_badges' ) ) {
				aqualuxe_woocommerce_show_product_badges();
			}
			
			// Create product thumbnails navigation
			$attachment_ids = $product->get_gallery_image_ids();
			if ( $attachment_ids && $product->get_image_id() ) {
				echo '<div class="product-thumbnails">';
				
				// Add main product image to thumbnails
				echo '<div class="product-thumbnail-item">';
				echo wp_get_attachment_image( $product->get_image_id(), 'thumbnail' );
				echo '</div>';
				
				// Add gallery images to thumbnails
				foreach ( $attachment_ids as $attachment_id ) {
					echo '<div class="product-thumbnail-item">';
					echo wp_get_attachment_image( $attachment_id, 'thumbnail' );
					echo '</div>';
				}
				
				echo '</div>';
			}
			?>
		</div>

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
			 * @hooked aqualuxe_woocommerce_countdown_timer - 15
			 * @hooked aqualuxe_woocommerce_stock_progress_bar - 25
			 * @hooked aqualuxe_woocommerce_estimated_delivery - 30
			 * @hooked aqualuxe_woocommerce_product_guarantee - 35
			 * @hooked aqualuxe_woocommerce_trust_badges - 40
			 * @hooked aqualuxe_woocommerce_social_share - 50
			 * @hooked aqualuxe_woocommerce_product_inquiry_form - 60
			 */
			do_action( 'woocommerce_single_product_summary' );
			?>
			
			<?php if ( $product->is_type( 'simple' ) && $product->is_in_stock() ) : ?>
			<!-- One-click purchase button -->
			<div class="one-click-purchase">
				<button type="button" class="button one-click-button">
					<i class="fas fa-bolt"></i> <?php esc_html_e( 'Buy Now', 'aqualuxe' ); ?>
				</button>
				<span class="one-click-info"><?php esc_html_e( 'Skip cart and go straight to checkout', 'aqualuxe' ); ?></span>
			</div>
			<?php endif; ?>
			
			<!-- Product highlights -->
			<div class="product-highlights">
				<h3><?php esc_html_e( 'Product Highlights', 'aqualuxe' ); ?></h3>
				<ul class="highlights-list">
					<?php
					// Get product highlights from product meta or use default
					$highlights = get_post_meta( $product->get_id(), '_aqualuxe_product_highlights', true );
					
					if ( ! empty( $highlights ) && is_array( $highlights ) ) {
						foreach ( $highlights as $highlight ) {
							echo '<li><i class="fas fa-check-circle"></i> ' . esc_html( $highlight ) . '</li>';
						}
					} else {
						// Default highlights based on product type
						if ( $product->is_type( 'simple' ) ) {
							echo '<li><i class="fas fa-check-circle"></i> ' . esc_html__( 'Premium quality', 'aqualuxe' ) . '</li>';
							echo '<li><i class="fas fa-check-circle"></i> ' . esc_html__( 'Live arrival guarantee', 'aqualuxe' ) . '</li>';
							echo '<li><i class="fas fa-check-circle"></i> ' . esc_html__( 'Expert packaging for safe shipping', 'aqualuxe' ) . '</li>';
							echo '<li><i class="fas fa-check-circle"></i> ' . esc_html__( 'Carefully selected for health and vitality', 'aqualuxe' ) . '</li>';
						} elseif ( $product->is_type( 'variable' ) ) {
							echo '<li><i class="fas fa-check-circle"></i> ' . esc_html__( 'Multiple varieties available', 'aqualuxe' ) . '</li>';
							echo '<li><i class="fas fa-check-circle"></i> ' . esc_html__( 'Premium quality', 'aqualuxe' ) . '</li>';
							echo '<li><i class="fas fa-check-circle"></i> ' . esc_html__( 'Live arrival guarantee', 'aqualuxe' ) . '</li>';
							echo '<li><i class="fas fa-check-circle"></i> ' . esc_html__( 'Expert packaging for safe shipping', 'aqualuxe' ) . '</li>';
						}
					}
					?>
				</ul>
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
	 * @hooked aqualuxe_woocommerce_product_video - 15
	 * @hooked aqualuxe_woocommerce_product_faq - 25
	 * @hooked aqualuxe_woocommerce_recently_viewed_products - 20
	 */
	do_action( 'woocommerce_after_single_product_summary' );
	?>
	
	<!-- Product care guide -->
	<div class="product-care-guide">
		<div class="care-guide-header">
			<h2><?php esc_html_e( 'Care Guide', 'aqualuxe' ); ?></h2>
			<p><?php esc_html_e( 'Follow these guidelines to ensure the health and longevity of your aquatic pets', 'aqualuxe' ); ?></p>
		</div>
		
		<div class="care-guide-content">
			<div class="care-guide-item">
				<div class="care-icon">
					<i class="fas fa-temperature-high"></i>
				</div>
				<div class="care-details">
					<h3><?php esc_html_e( 'Water Temperature', 'aqualuxe' ); ?></h3>
					<p><?php esc_html_e( 'Maintain water temperature between 72-82°F (22-28°C) for optimal health.', 'aqualuxe' ); ?></p>
				</div>
			</div>
			
			<div class="care-guide-item">
				<div class="care-icon">
					<i class="fas fa-vial"></i>
				</div>
				<div class="care-details">
					<h3><?php esc_html_e( 'Water Quality', 'aqualuxe' ); ?></h3>
					<p><?php esc_html_e( 'Test water regularly for ammonia, nitrite, nitrate, and pH. Perform 20-30% water changes weekly.', 'aqualuxe' ); ?></p>
				</div>
			</div>
			
			<div class="care-guide-item">
				<div class="care-icon">
					<i class="fas fa-filter"></i>
				</div>
				<div class="care-details">
					<h3><?php esc_html_e( 'Filtration', 'aqualuxe' ); ?></h3>
					<p><?php esc_html_e( 'Use appropriate filtration systems and clean filters regularly according to manufacturer instructions.', 'aqualuxe' ); ?></p>
				</div>
			</div>
			
			<div class="care-guide-item">
				<div class="care-icon">
					<i class="fas fa-utensils"></i>
				</div>
				<div class="care-details">
					<h3><?php esc_html_e( 'Feeding', 'aqualuxe' ); ?></h3>
					<p><?php esc_html_e( 'Feed high-quality food appropriate for this species. Feed small amounts 1-2 times daily.', 'aqualuxe' ); ?></p>
				</div>
			</div>
		</div>
	</div>
</div>

<?php do_action( 'woocommerce_after_single_product' ); ?>