<?php
/**
 * Template part for displaying latest products on the homepage
 *
 * @package AquaLuxe
 */

?>

<section class="latest-products py-16 bg-gray-50 dark:bg-dark-850">
	<div class="container mx-auto px-4">
		<div class="flex flex-col md:flex-row md:items-center md:justify-between mb-12">
			<div>
				<h2 class="text-3xl md:text-4xl font-serif font-medium mb-2"><?php esc_html_e( 'New Arrivals', 'aqualuxe' ); ?></h2>
				<p class="text-lg text-dark-600 dark:text-dark-300"><?php esc_html_e( 'Our latest additions to the AquaLuxe collection', 'aqualuxe' ); ?></p>
			</div>
			<div class="mt-4 md:mt-0">
				<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="btn btn-outline">
					<?php esc_html_e( 'View All', 'aqualuxe' ); ?>
					<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
					</svg>
				</a>
			</div>
		</div>
		
		<?php
		if ( class_exists( 'WooCommerce' ) ) :
			$args = array(
				'post_type'      => 'product',
				'posts_per_page' => 8,
				'orderby'        => 'date',
				'order'          => 'desc',
			);
			$latest_query = new WP_Query( $args );
			
			if ( $latest_query->have_posts() ) :
				?>
				<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
					<?php
					while ( $latest_query->have_posts() ) : $latest_query->the_post();
						global $product;
						?>
						<div class="product-card bg-white dark:bg-dark-700 rounded-lg shadow-soft overflow-hidden transition-all duration-300 hover:shadow-medium group">
							<div class="relative overflow-hidden">
								<a href="<?php the_permalink(); ?>" class="block">
									<?php if ( has_post_thumbnail() ) : ?>
										<img src="<?php the_post_thumbnail_url( 'woocommerce_thumbnail' ); ?>" alt="<?php the_title_attribute(); ?>" class="w-full h-auto transition-transform duration-500 group-hover:scale-110">
									<?php else : ?>
										<img src="<?php echo esc_url( wc_placeholder_img_src() ); ?>" alt="<?php esc_attr_e( 'Placeholder', 'aqualuxe' ); ?>" class="w-full h-auto">
									<?php endif; ?>
								</a>
								
								<?php if ( $product->is_on_sale() ) : ?>
									<span class="product-badge sale absolute top-4 left-4 bg-accent-500 text-white px-2 py-1 text-xs font-bold uppercase rounded">
										<?php esc_html_e( 'Sale', 'aqualuxe' ); ?>
									</span>
								<?php elseif ( $product->is_featured() ) : ?>
									<span class="product-badge featured absolute top-4 left-4 bg-primary-500 text-white px-2 py-1 text-xs font-bold uppercase rounded">
										<?php esc_html_e( 'Featured', 'aqualuxe' ); ?>
									</span>
								<?php elseif ( ! $product->get_date_created()->is_null() && $product->get_date_created()->getTimestamp() > strtotime( '-14 days' ) ) : ?>
									<span class="product-badge new absolute top-4 left-4 bg-secondary-500 text-white px-2 py-1 text-xs font-bold uppercase rounded">
										<?php esc_html_e( 'New', 'aqualuxe' ); ?>
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
						<?php
					endwhile;
					?>
				</div>
				<?php
			else :
				echo '<div class="text-center py-8">';
				echo '<p>' . esc_html__( 'No products found.', 'aqualuxe' ) . '</p>';
				echo '</div>';
			endif;
			
			wp_reset_postdata();
		else :
			echo '<div class="text-center py-8">';
			echo '<p>' . esc_html__( 'WooCommerce is not active. Please install and activate WooCommerce to display latest products.', 'aqualuxe' ) . '</p>';
			echo '</div>';
		endif;
		?>
	</div>
</section>