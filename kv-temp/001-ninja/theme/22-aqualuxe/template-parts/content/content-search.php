<?php
/**
 * Template part for displaying results in search pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'mb-8 pb-8 border-b border-gray-200 dark:border-gray-700' ); ?>>
	<div class="flex flex-col md:flex-row">
		<?php if ( has_post_thumbnail() ) : ?>
			<div class="entry-thumbnail md:w-1/4 mb-4 md:mb-0 md:mr-6">
				<a href="<?php the_permalink(); ?>" class="block overflow-hidden rounded-lg">
					<?php the_post_thumbnail( 'medium', array( 'class' => 'w-full h-auto hover:scale-105 transition-transform duration-300' ) ); ?>
				</a>
			</div>
		<?php endif; ?>
		
		<div class="<?php echo has_post_thumbnail() ? 'md:w-3/4' : 'w-full'; ?>">
			<header class="entry-header mb-3">
				<?php the_title( sprintf( '<h2 class="entry-title text-xl font-serif font-bold mb-2"><a href="%s" rel="bookmark" class="hover:text-primary-500 transition-colors duration-200">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>

				<?php if ( 'post' === get_post_type() ) : ?>
					<div class="entry-meta text-sm text-gray-600 dark:text-gray-400">
						<?php
						aqualuxe_posted_on();
						aqualuxe_posted_by();
						?>
					</div><!-- .entry-meta -->
				<?php elseif ( 'product' === get_post_type() && class_exists( 'WooCommerce' ) ) : ?>
					<div class="entry-meta text-sm text-gray-600 dark:text-gray-400">
						<?php
						// Display product price
						$product = wc_get_product( get_the_ID() );
						if ( $product ) {
							echo wp_kses_post( $product->get_price_html() );
						}
						?>
					</div>
				<?php endif; ?>
			</header><!-- .entry-header -->

			<div class="entry-summary prose dark:prose-invert max-w-none text-sm">
				<?php the_excerpt(); ?>
			</div><!-- .entry-summary -->

			<footer class="entry-footer mt-3">
				<a href="<?php the_permalink(); ?>" class="inline-flex items-center text-primary-500 hover:text-primary-600 transition-colors duration-200 text-sm font-medium">
					<?php 
					if ( 'product' === get_post_type() ) {
						esc_html_e( 'View Product', 'aqualuxe' );
					} else {
						esc_html_e( 'Read More', 'aqualuxe' );
					}
					?>
					<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" viewBox="0 0 20 20" fill="currentColor">
						<path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
					</svg>
				</a>
			</footer><!-- .entry-footer -->
		</div>
	</div>
</article><!-- #post-<?php the_ID(); ?> -->