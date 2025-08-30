<?php
/**
 * Template part for displaying results in search pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'search-result mb-8 pb-8 border-b border-gray-200 last:border-0 last:mb-0 last:pb-0' ); ?>>
	<div class="flex flex-col md:flex-row">
		<?php if ( has_post_thumbnail() ) : ?>
			<div class="entry-thumbnail md:w-1/4 md:mr-6 mb-4 md:mb-0">
				<a href="<?php the_permalink(); ?>" class="block overflow-hidden rounded-lg shadow-md">
					<?php the_post_thumbnail( 'medium', array( 'class' => 'w-full h-auto hover:scale-105 transition-transform duration-300' ) ); ?>
				</a>
			</div>
		<?php endif; ?>

		<div class="entry-content <?php echo has_post_thumbnail() ? 'md:w-3/4' : 'w-full'; ?>">
			<header class="entry-header mb-3">
				<?php the_title( sprintf( '<h2 class="entry-title text-xl font-bold text-primary-800 mb-2"><a href="%s" rel="bookmark" class="hover:text-primary-600 transition-colors">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>

				<?php if ( 'post' === get_post_type() ) : ?>
					<div class="entry-meta flex flex-wrap items-center text-xs text-gray-600">
						<?php
						aqualuxe_posted_on();
						
						// Post type
						echo '<span class="post-type ml-3">';
						echo '<i class="fas fa-file-alt mr-1"></i>';
						echo esc_html( get_post_type_object( get_post_type() )->labels->singular_name );
						echo '</span>';
						
						// Category for posts
						$categories_list = get_the_category_list( esc_html__( ', ', 'aqualuxe' ) );
						if ( $categories_list ) {
							/* translators: 1: list of categories. */
							printf( '<span class="cat-links ml-3"><i class="fas fa-folder-open mr-1"></i> %1$s</span>', $categories_list ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						}
						?>
					</div><!-- .entry-meta -->
				<?php elseif ( 'page' === get_post_type() ) : ?>
					<div class="entry-meta flex flex-wrap items-center text-xs text-gray-600">
						<span class="post-type">
							<i class="fas fa-file mr-1"></i>
							<?php echo esc_html( get_post_type_object( get_post_type() )->labels->singular_name ); ?>
						</span>
					</div><!-- .entry-meta -->
				<?php elseif ( 'product' === get_post_type() ) : ?>
					<div class="entry-meta flex flex-wrap items-center text-xs text-gray-600">
						<span class="post-type">
							<i class="fas fa-shopping-cart mr-1"></i>
							<?php echo esc_html( get_post_type_object( get_post_type() )->labels->singular_name ); ?>
						</span>
						
						<?php
						// Product categories
						$product_cats = get_the_terms( get_the_ID(), 'product_cat' );
						if ( $product_cats && ! is_wp_error( $product_cats ) ) {
							echo '<span class="product-cats ml-3"><i class="fas fa-tag mr-1"></i> ';
							$cat_names = array();
							foreach ( $product_cats as $cat ) {
								$cat_names[] = $cat->name;
							}
							echo esc_html( implode( ', ', $cat_names ) );
							echo '</span>';
						}
						?>
					</div><!-- .entry-meta -->
				<?php else : ?>
					<div class="entry-meta flex flex-wrap items-center text-xs text-gray-600">
						<span class="post-type">
							<i class="fas fa-file-alt mr-1"></i>
							<?php echo esc_html( get_post_type_object( get_post_type() )->labels->singular_name ); ?>
						</span>
					</div><!-- .entry-meta -->
				<?php endif; ?>
			</header><!-- .entry-header -->

			<div class="entry-summary prose prose-sm max-w-none mb-4">
				<?php the_excerpt(); ?>
			</div><!-- .entry-summary -->

			<footer class="entry-footer">
				<a href="<?php the_permalink(); ?>" class="inline-flex items-center text-primary-600 hover:text-primary-800 font-medium transition-colors">
					<?php esc_html_e( 'Read More', 'aqualuxe' ); ?> <i class="fas fa-arrow-right ml-2"></i>
				</a>
			</footer><!-- .entry-footer -->
		</div>
	</div>
</article><!-- #post-<?php the_ID(); ?> -->