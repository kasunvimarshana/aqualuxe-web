<?php
/**
 * The Template for displaying all single products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

get_header( 'shop' ); ?>

<div class="container">
	<?php
		/**
		 * woocommerce_before_main_content hook.
		 *
		 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
		 * @hooked woocommerce_breadcrumb - 20
		 */
		do_action( 'woocommerce_before_main_content' );
	?>

	<div class="aqualuxe-product-layout">
		<?php while ( have_posts() ) : ?>
			<?php the_post(); ?>

			<?php wc_get_template_part( 'content', 'single-product' ); ?>

		<?php endwhile; // end of the loop. ?>
	</div>

	<?php
		/**
		 * woocommerce_after_main_content hook.
		 *
		 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
		 */
		do_action( 'woocommerce_after_main_content' );
	?>

	<?php
		/**
		 * Hook: aqualuxe_after_single_product.
		 *
		 * @hooked aqualuxe_recently_viewed_products - 10
		 * @hooked aqualuxe_product_instagram_feed - 20
		 */
		do_action( 'aqualuxe_after_single_product' );
	?>
</div>

<?php
// Display product features section if ACF is active and fields are set
if ( function_exists( 'get_field' ) ) {
	$features = get_field( 'product_features' );
	
	if ( $features ) {
		?>
		<section class="product-features">
			<div class="container">
				<h2><?php esc_html_e( 'Product Features', 'aqualuxe' ); ?></h2>
				
				<div class="features-grid">
					<?php foreach ( $features as $feature ) : ?>
						<div class="feature-item">
							<?php if ( ! empty( $feature['icon'] ) ) : ?>
								<div class="feature-icon">
									<img src="<?php echo esc_url( $feature['icon'] ); ?>" alt="<?php echo esc_attr( $feature['title'] ); ?>">
								</div>
							<?php endif; ?>
							
							<div class="feature-content">
								<h3><?php echo esc_html( $feature['title'] ); ?></h3>
								<p><?php echo esc_html( $feature['description'] ); ?></p>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</section>
		<?php
	}
}

// Display related products with custom layout
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
add_action( 'aqualuxe_after_single_product', 'aqualuxe_custom_related_products', 30 );

if ( ! function_exists( 'aqualuxe_custom_related_products' ) ) {
	/**
	 * Custom related products output
	 */
	function aqualuxe_custom_related_products() {
		global $product;
		
		if ( ! $product ) {
			return;
		}
		
		$related_products = array_filter( array_map( 'wc_get_product', wc_get_related_products( $product->get_id(), 4 ) ), 'wc_products_array_filter_visible' );
		
		if ( $related_products ) {
			?>
			<section class="related-products">
				<div class="container">
					<h2><?php esc_html_e( 'Related Products', 'aqualuxe' ); ?></h2>
					
					<div class="products columns-4">
						<?php foreach ( $related_products as $related_product ) : ?>
							<div class="product">
								<a href="<?php echo esc_url( $related_product->get_permalink() ); ?>">
									<?php echo $related_product->get_image(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
									<h3><?php echo esc_html( $related_product->get_name() ); ?></h3>
									<span class="price"><?php echo $related_product->get_price_html(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
								</a>
								<?php woocommerce_template_loop_add_to_cart(); ?>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			</section>
			<?php
		}
	}
}

get_footer( 'shop' );

/* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */