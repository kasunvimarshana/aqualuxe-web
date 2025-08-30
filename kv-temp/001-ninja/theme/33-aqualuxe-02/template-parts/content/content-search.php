<?php
/**
 * Template part for displaying results in search pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'search-result bg-white dark:bg-dark-800 rounded-lg overflow-hidden shadow-sm border border-gray-100 dark:border-dark-700 p-6 transition-all duration-300 hover:shadow-md' ); ?>>
	<header class="entry-header mb-3 flex items-start">
		<?php if ( has_post_thumbnail() ) : ?>
			<div class="post-thumbnail mr-4 flex-shrink-0 hidden sm:block">
				<a href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
					<?php
					the_post_thumbnail(
						'thumbnail',
						array(
							'class' => 'w-20 h-20 object-cover rounded',
							'alt'   => the_title_attribute( array( 'echo' => false ) ),
						)
					);
					?>
				</a>
			</div>
		<?php endif; ?>

		<div class="entry-header-content">
			<?php the_title( sprintf( '<h2 class="entry-title text-xl font-serif font-bold mb-2"><a href="%s" class="text-dark-800 dark:text-white hover:text-primary-600 dark:hover:text-primary-400 transition-colors duration-200" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>

			<?php if ( 'post' === get_post_type() ) : ?>
				<div class="entry-meta text-sm text-gray-500 dark:text-gray-400">
					<?php
					aqualuxe_posted_on();
					aqualuxe_posted_by();
					?>
				</div><!-- .entry-meta -->
			<?php elseif ( 'product' === get_post_type() && class_exists( 'WooCommerce' ) ) : ?>
				<div class="product-meta text-sm text-gray-500 dark:text-gray-400 flex items-center">
					<span class="product-price mr-4">
						<?php
						$product = wc_get_product( get_the_ID() );
						echo wp_kses_post( $product->get_price_html() );
						?>
					</span>
					
					<?php if ( $product->get_average_rating() > 0 ) : ?>
						<span class="product-rating flex items-center">
							<span class="star-rating inline-flex text-yellow-400" role="img">
								<?php
								for ( $i = 1; $i <= 5; $i++ ) {
									if ( $i <= $product->get_average_rating() ) {
										echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">';
										echo '<path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />';
										echo '</svg>';
									} else {
										echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">';
										echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />';
										echo '</svg>';
									}
								}
								?>
							</span>
							<span class="ml-1">(<?php echo esc_html( $product->get_review_count() ); ?>)</span>
						</span>
					<?php endif; ?>
				</div>
			<?php else : ?>
				<div class="entry-meta text-sm text-gray-500 dark:text-gray-400">
					<span class="post-type">
						<?php
						$post_type_obj = get_post_type_object( get_post_type() );
						echo esc_html( $post_type_obj->labels->singular_name );
						?>
					</span>
				</div>
			<?php endif; ?>
		</div>
	</header><!-- .entry-header -->

	<div class="entry-summary text-gray-600 dark:text-gray-300 mb-4">
		<?php the_excerpt(); ?>
	</div><!-- .entry-summary -->

	<footer class="entry-footer flex items-center justify-between text-sm">
		<a href="<?php the_permalink(); ?>" class="read-more text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 font-medium transition-colors duration-200">
			<?php
			if ( 'product' === get_post_type() ) {
				esc_html_e( 'View Product', 'aqualuxe' );
			} else {
				esc_html_e( 'Read More', 'aqualuxe' );
			}
			?>
			<span class="sr-only"><?php echo esc_html( get_the_title() ); ?></span>
			<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
				<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
			</svg>
		</a>

		<?php
		// Show categories and tags for posts
		if ( 'post' === get_post_type() ) {
			$categories_list = get_the_category_list( esc_html__( ', ', 'aqualuxe' ) );
			if ( $categories_list ) {
				echo '<span class="cat-links text-gray-500 dark:text-gray-400">' . $categories_list . '</span>';
			}
		}
		
		// Show product categories for products
		if ( 'product' === get_post_type() && class_exists( 'WooCommerce' ) ) {
			$product_cats = get_the_terms( get_the_ID(), 'product_cat' );
			if ( ! empty( $product_cats ) && ! is_wp_error( $product_cats ) ) {
				$cat_links = array();
				foreach ( $product_cats as $product_cat ) {
					$cat_links[] = '<a href="' . esc_url( get_term_link( $product_cat ) ) . '" class="hover:text-primary-600 dark:hover:text-primary-400 transition-colors duration-200">' . esc_html( $product_cat->name ) . '</a>';
				}
				echo '<span class="product-cats text-gray-500 dark:text-gray-400">' . implode( ', ', $cat_links ) . '</span>';
			}
		}
		?>
	</footer><!-- .entry-footer -->
</article><!-- #post-<?php the_ID(); ?> -->