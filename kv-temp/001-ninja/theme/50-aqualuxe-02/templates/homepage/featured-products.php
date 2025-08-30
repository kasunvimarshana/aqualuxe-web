<?php
/**
 * Template part for displaying the featured products section on the homepage
 *
 * @package AquaLuxe
 */

// Check if WooCommerce is active
if ( ! class_exists( 'WooCommerce' ) ) {
	return;
}

// Get featured products settings from customizer or use defaults
$featured_products_title = get_theme_mod( 'aqualuxe_featured_products_title', __( 'Featured Products', 'aqualuxe' ) );
$featured_products_subtitle = get_theme_mod( 'aqualuxe_featured_products_subtitle', __( 'Discover our premium selection of rare and exotic aquatic species', 'aqualuxe' ) );
$featured_products_count = get_theme_mod( 'aqualuxe_featured_products_count', 4 );
$featured_products_columns = get_theme_mod( 'aqualuxe_featured_products_columns', 4 );
$featured_products_type = get_theme_mod( 'aqualuxe_featured_products_type', 'featured' ); // Options: featured, best_selling, newest, sale
$featured_products_button_text = get_theme_mod( 'aqualuxe_featured_products_button_text', __( 'View All Products', 'aqualuxe' ) );
$featured_products_button_url = get_theme_mod( 'aqualuxe_featured_products_button_url', wc_get_page_permalink( 'shop' ) );

// Determine which products to display based on the selected type
$args = array(
	'posts_per_page' => $featured_products_count,
	'post_type'      => 'product',
	'post_status'    => 'publish',
);

switch ( $featured_products_type ) {
	case 'featured':
		$args['tax_query'] = array(
			array(
				'taxonomy' => 'product_visibility',
				'field'    => 'name',
				'terms'    => 'featured',
				'operator' => 'IN',
			),
		);
		break;
	case 'best_selling':
		$args['meta_key'] = 'total_sales';
		$args['orderby']  = 'meta_value_num';
		break;
	case 'newest':
		$args['orderby'] = 'date';
		$args['order']   = 'DESC';
		break;
	case 'sale':
		$product_ids_on_sale = wc_get_product_ids_on_sale();
		$args['post__in']    = array_merge( array( 0 ), $product_ids_on_sale );
		break;
}

$featured_products = new WP_Query( $args );

// Only display the section if products are found
if ( $featured_products->have_posts() ) :
?>

<section id="featured-products" class="featured-products-section">
	<div class="container">
		<div class="section-header">
			<?php if ( $featured_products_title ) : ?>
				<h2 class="section-title"><?php echo esc_html( $featured_products_title ); ?></h2>
			<?php endif; ?>
			
			<?php if ( $featured_products_subtitle ) : ?>
				<p class="section-subtitle"><?php echo esc_html( $featured_products_subtitle ); ?></p>
			<?php endif; ?>
		</div>
		
		<div class="featured-products-grid columns-<?php echo esc_attr( $featured_products_columns ); ?>">
			<?php
			while ( $featured_products->have_posts() ) :
				$featured_products->the_post();
				global $product;
				
				// Get product data
				$product_id = get_the_ID();
				$product_url = get_permalink();
				$product_title = get_the_title();
				$product_image = get_the_post_thumbnail( $product_id, 'woocommerce_thumbnail', array( 'class' => 'product-image' ) );
				$product_price = $product->get_price_html();
				$product_rating = wc_get_rating_html( $product->get_average_rating() );
				$product_is_on_sale = $product->is_on_sale();
				?>
				
				<div class="featured-product">
					<?php if ( $product_is_on_sale ) : ?>
						<span class="onsale"><?php esc_html_e( 'Sale!', 'aqualuxe' ); ?></span>
					<?php endif; ?>
					
					<?php if ( function_exists( 'aqualuxe_wishlist_button' ) ) : ?>
						<?php aqualuxe_wishlist_button( $product_id ); ?>
					<?php endif; ?>
					
					<a href="<?php echo esc_url( $product_url ); ?>" class="product-image-link">
						<?php if ( $product_image ) : ?>
							<?php echo $product_image; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						<?php else : ?>
							<img src="<?php echo esc_url( wc_placeholder_img_src() ); ?>" alt="<?php echo esc_attr( $product_title ); ?>" class="product-image">
						<?php endif; ?>
					</a>
					
					<div class="product-details">
						<h3 class="product-title">
							<a href="<?php echo esc_url( $product_url ); ?>"><?php echo esc_html( $product_title ); ?></a>
						</h3>
						
						<?php if ( $product_rating ) : ?>
							<div class="product-rating">
								<?php echo $product_rating; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							</div>
						<?php endif; ?>
						
						<div class="product-price">
							<?php echo $product_price; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</div>
						
						<div class="product-actions">
							<?php
							echo apply_filters(
								'woocommerce_loop_add_to_cart_link',
								sprintf(
									'<a href="%s" data-quantity="%s" class="%s" %s>%s</a>',
									esc_url( $product->add_to_cart_url() ),
									esc_attr( 1 ),
									esc_attr( 'button add_to_cart_button ' . $product->get_type() ),
									'data-product_id="' . esc_attr( $product_id ) . '" data-product_sku="' . esc_attr( $product->get_sku() ) . '"',
									esc_html( $product->add_to_cart_text() )
								),
								$product
							);
							?>
							
							<?php if ( function_exists( 'aqualuxe_quick_view_button' ) ) : ?>
								<?php aqualuxe_quick_view_button( $product_id ); ?>
							<?php endif; ?>
						</div>
					</div>
				</div>
				
			<?php endwhile; ?>
			<?php wp_reset_postdata(); ?>
		</div>
		
		<?php if ( $featured_products_button_text && $featured_products_button_url ) : ?>
			<div class="section-footer">
				<a href="<?php echo esc_url( $featured_products_button_url ); ?>" class="btn btn-primary"><?php echo esc_html( $featured_products_button_text ); ?></a>
			</div>
		<?php endif; ?>
	</div>
</section>

<?php endif; ?>