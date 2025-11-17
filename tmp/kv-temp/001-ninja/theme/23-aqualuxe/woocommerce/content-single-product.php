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
<div id="product-<?php the_ID(); ?>" <?php wc_product_class( '', $product ); ?>>

	<div class="product-main flex flex-wrap -mx-4">
		<div class="product-gallery px-4 w-full md:w-1/2 mb-8 md:mb-0">
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

		<div class="product-summary px-4 w-full md:w-1/2">
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
				
				<?php
				// Display custom fields for aquarium products
				$scientific_name = get_post_meta( $product->get_id(), '_scientific_name', true );
				$adult_size = get_post_meta( $product->get_id(), '_adult_size', true );
				$tank_size = get_post_meta( $product->get_id(), '_tank_size', true );
				$temperature_range = get_post_meta( $product->get_id(), '_temperature_range', true );
				$ph_range = get_post_meta( $product->get_id(), '_ph_range', true );
				
				if ( $scientific_name || $adult_size || $tank_size || $temperature_range || $ph_range ) :
				?>
				<div class="product-custom-fields mt-6 mb-6 p-4 bg-gray-50 rounded-lg">
					<h3 class="text-lg font-medium mb-3"><?php esc_html_e( 'Product Specifications', 'aqualuxe' ); ?></h3>
					
					<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
						<?php if ( $scientific_name ) : ?>
						<div class="custom-field">
							<span class="label font-medium text-gray-700"><?php esc_html_e( 'Scientific Name:', 'aqualuxe' ); ?></span>
							<span class="value italic"><?php echo esc_html( $scientific_name ); ?></span>
						</div>
						<?php endif; ?>
						
						<?php if ( $adult_size ) : ?>
						<div class="custom-field">
							<span class="label font-medium text-gray-700"><?php esc_html_e( 'Adult Size:', 'aqualuxe' ); ?></span>
							<span class="value"><?php echo esc_html( $adult_size ); ?></span>
						</div>
						<?php endif; ?>
						
						<?php if ( $tank_size ) : ?>
						<div class="custom-field">
							<span class="label font-medium text-gray-700"><?php esc_html_e( 'Minimum Tank Size:', 'aqualuxe' ); ?></span>
							<span class="value"><?php echo esc_html( $tank_size ); ?></span>
						</div>
						<?php endif; ?>
						
						<?php if ( $temperature_range ) : ?>
						<div class="custom-field">
							<span class="label font-medium text-gray-700"><?php esc_html_e( 'Temperature Range:', 'aqualuxe' ); ?></span>
							<span class="value"><?php echo esc_html( $temperature_range ); ?></span>
						</div>
						<?php endif; ?>
						
						<?php if ( $ph_range ) : ?>
						<div class="custom-field">
							<span class="label font-medium text-gray-700"><?php esc_html_e( 'pH Range:', 'aqualuxe' ); ?></span>
							<span class="value"><?php echo esc_html( $ph_range ); ?></span>
						</div>
						<?php endif; ?>
					</div>
				</div>
				<?php endif; ?>
				
				<?php
				// Display product taxonomies
				$origins = get_the_terms( $product->get_id(), 'product_origin' );
				$difficulties = get_the_terms( $product->get_id(), 'product_difficulty' );
				$brands = get_the_terms( $product->get_id(), 'product_brand' );
				
				if ( $origins || $difficulties || $brands ) :
				?>
				<div class="product-taxonomies mt-6 mb-6">
					<?php if ( $brands && ! is_wp_error( $brands ) ) : ?>
					<div class="product-brand mb-2">
						<span class="label font-medium text-gray-700"><?php esc_html_e( 'Brand:', 'aqualuxe' ); ?></span>
						<?php
						$brand_links = array();
						foreach ( $brands as $brand ) {
							$brand_links[] = '<a href="' . esc_url( get_term_link( $brand ) ) . '" class="text-primary hover:text-primary-dark">' . esc_html( $brand->name ) . '</a>';
						}
						echo implode( ', ', $brand_links );
						?>
					</div>
					<?php endif; ?>
					
					<?php if ( $origins && ! is_wp_error( $origins ) ) : ?>
					<div class="product-origin mb-2">
						<span class="label font-medium text-gray-700"><?php esc_html_e( 'Origin:', 'aqualuxe' ); ?></span>
						<?php
						$origin_links = array();
						foreach ( $origins as $origin ) {
							$origin_links[] = '<a href="' . esc_url( get_term_link( $origin ) ) . '" class="text-primary hover:text-primary-dark">' . esc_html( $origin->name ) . '</a>';
						}
						echo implode( ', ', $origin_links );
						?>
					</div>
					<?php endif; ?>
					
					<?php if ( $difficulties && ! is_wp_error( $difficulties ) ) : ?>
					<div class="product-difficulty mb-2">
						<span class="label font-medium text-gray-700"><?php esc_html_e( 'Difficulty Level:', 'aqualuxe' ); ?></span>
						<?php
						$difficulty = reset( $difficulties ); // Get the first difficulty term
						$difficulty_class = '';
						
						switch ( $difficulty->slug ) {
							case 'beginner':
								$difficulty_class = 'text-green-600';
								break;
							case 'intermediate':
								$difficulty_class = 'text-yellow-600';
								break;
							case 'advanced':
								$difficulty_class = 'text-red-600';
								break;
							default:
								$difficulty_class = 'text-gray-600';
						}
						
						echo '<span class="' . esc_attr( $difficulty_class ) . '">' . esc_html( $difficulty->name ) . '</span>';
						?>
					</div>
					<?php endif; ?>
				</div>
				<?php endif; ?>
				
				<?php
				// Display product specifications if available
				$specifications = get_post_meta( $product->get_id(), '_product_specifications', true );
				
				if ( ! empty( $specifications ) && is_array( $specifications ) ) :
				?>
				<div class="product-specifications mt-6 mb-6">
					<h3 class="text-xl font-medium mb-4"><?php esc_html_e( 'Product Specifications', 'aqualuxe' ); ?></h3>
					<div class="overflow-x-auto">
						<table class="w-full border-collapse">
							<tbody>
								<?php foreach ( $specifications as $spec ) : ?>
									<?php if ( ! empty( $spec['label'] ) && ! empty( $spec['value'] ) ) : ?>
									<tr class="border-b border-gray-200">
										<th class="py-3 px-4 text-left text-gray-600 w-1/3"><?php echo esc_html( $spec['label'] ); ?></th>
										<td class="py-3 px-4 text-gray-900"><?php echo wp_kses_post( $spec['value'] ); ?></td>
									</tr>
									<?php endif; ?>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
				</div>
				<?php endif; ?>
				
				<div class="product-guarantee mt-6 mb-6 p-4 bg-green-50 rounded-lg">
					<div class="flex items-start">
						<div class="mr-3">
							<svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
							</svg>
						</div>
						<div>
							<h4 class="font-medium text-green-800 mb-1"><?php esc_html_e( 'AquaLuxe Guarantee', 'aqualuxe' ); ?></h4>
							<p class="text-sm text-green-700"><?php esc_html_e( 'We guarantee live arrival for all fish and plants. If your order arrives dead or damaged, we\'ll replace it or refund your purchase.', 'aqualuxe' ); ?></p>
						</div>
					</div>
				</div>
			</div>
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