<?php
/**
 * Template part for displaying featured products on the homepage
 *
 * @package AquaLuxe
 */

?>

<section class="featured-products py-16">
	<div class="container mx-auto px-4">
		<div class="text-center mb-12">
			<h2 class="text-3xl md:text-4xl font-serif font-medium mb-4"><?php esc_html_e( 'Featured Products', 'aqualuxe' ); ?></h2>
			<p class="text-lg text-dark-600 dark:text-dark-300 max-w-3xl mx-auto"><?php esc_html_e( 'Discover our most exclusive and sought-after aquatic treasures, handpicked for the discerning collector.', 'aqualuxe' ); ?></p>
		</div>
		
		<div class="relative">
			<?php
			if ( class_exists( 'WooCommerce' ) ) :
				$args = array(
					'post_type'      => 'product',
					'posts_per_page' => 4,
					'meta_key'       => 'total_sales',
					'orderby'        => 'meta_value_num',
					'order'          => 'desc',
					'meta_query'     => array(
						array(
							'key'     => '_featured',
							'value'   => 'yes',
							'compare' => '=',
						),
					),
				);
				$featured_query = new WP_Query( $args );
				
				if ( $featured_query->have_posts() ) :
					echo '<div class="products-slider swiper-container">';
					echo '<div class="swiper-wrapper">';
					
					while ( $featured_query->have_posts() ) : $featured_query->the_post();
						global $product;
						?>
						<div class="swiper-slide">
							<div class="product-card bg-white dark:bg-dark-700 rounded-lg shadow-soft overflow-hidden transition-all duration-300 hover:shadow-medium">
								<div class="relative overflow-hidden">
									<a href="<?php the_permalink(); ?>" class="block">
										<?php if ( has_post_thumbnail() ) : ?>
											<img src="<?php the_post_thumbnail_url( 'woocommerce_thumbnail' ); ?>" alt="<?php the_title_attribute(); ?>" class="w-full h-auto transition-transform duration-500 hover:scale-110">
										<?php else : ?>
											<img src="<?php echo esc_url( wc_placeholder_img_src() ); ?>" alt="<?php esc_attr_e( 'Placeholder', 'aqualuxe' ); ?>" class="w-full h-auto">
										<?php endif; ?>
									</a>
									
									<?php if ( $product->is_on_sale() ) : ?>
										<span class="product-badge sale absolute top-4 left-4 bg-accent-500 text-white px-2 py-1 text-xs font-bold uppercase rounded">
											<?php esc_html_e( 'Sale', 'aqualuxe' ); ?>
										</span>
									<?php endif; ?>
									
									<div class="product-actions absolute bottom-0 left-0 right-0 p-4 bg-gradient-to-t from-dark-900 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex justify-center space-x-2">
										<?php
										// Quick view button
										if ( function_exists( 'aqualuxe_quick_view_button' ) ) {
											aqualuxe_quick_view_button();
										}
										
										// Add to wishlist button
										if ( function_exists( 'aqualuxe_wishlist_button' ) ) {
											aqualuxe_wishlist_button();
										}
										
										// Add to cart button
										woocommerce_template_loop_add_to_cart( array(
											'class' => 'btn-primary btn-sm',
										) );
										?>
									</div>
								</div>
								
								<div class="product-content p-4">
									<h3 class="product-title text-lg font-medium mb-2">
										<a href="<?php the_permalink(); ?>" class="text-dark-800 dark:text-white hover:text-primary-600 dark:hover:text-primary-400 transition-colors duration-200">
											<?php the_title(); ?>
										</a>
									</h3>
									
									<div class="product-price text-lg font-bold text-dark-800 dark:text-white">
										<?php echo $product->get_price_html(); ?>
									</div>
									
									<?php if ( $product->get_rating_count() > 0 ) : ?>
										<div class="product-rating mt-2 flex items-center">
											<?php echo wc_get_rating_html( $product->get_average_rating() ); ?>
											<span class="text-sm text-dark-500 dark:text-dark-300 ml-1">
												(<?php echo $product->get_rating_count(); ?>)
											</span>
										</div>
									<?php endif; ?>
								</div>
							</div>
						</div>
						<?php
					endwhile;
					
					echo '</div>'; // End .swiper-wrapper
					
					// Navigation arrows
					?>
					<div class="products-slider-prev absolute top-1/2 -left-4 transform -translate-y-1/2 bg-white dark:bg-dark-700 text-dark-700 dark:text-white p-2 rounded-full shadow-md hover:bg-gray-100 dark:hover:bg-dark-600 transition-colors duration-200 z-10">
						<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
						</svg>
						<span class="sr-only"><?php esc_html_e( 'Previous', 'aqualuxe' ); ?></span>
					</div>
					<div class="products-slider-next absolute top-1/2 -right-4 transform -translate-y-1/2 bg-white dark:bg-dark-700 text-dark-700 dark:text-white p-2 rounded-full shadow-md hover:bg-gray-100 dark:hover:bg-dark-600 transition-colors duration-200 z-10">
						<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
						</svg>
						<span class="sr-only"><?php esc_html_e( 'Next', 'aqualuxe' ); ?></span>
					</div>
					<?php
					
					echo '</div>'; // End .products-slider
				
				else :
					echo '<div class="text-center py-8">';
					echo '<p>' . esc_html__( 'No featured products found.', 'aqualuxe' ) . '</p>';
					echo '</div>';
				endif;
				
				wp_reset_postdata();
			else :
				echo '<div class="text-center py-8">';
				echo '<p>' . esc_html__( 'WooCommerce is not active. Please install and activate WooCommerce to display featured products.', 'aqualuxe' ) . '</p>';
				echo '</div>';
			endif;
			?>
		</div>
		
		<div class="text-center mt-12">
			<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="btn btn-primary">
				<?php esc_html_e( 'View All Products', 'aqualuxe' ); ?>
			</a>
		</div>
	</div>
</section>