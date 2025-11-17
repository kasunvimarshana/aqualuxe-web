<?php
/**
 * Template part for displaying results in search pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'search-result py-6 first:pt-0 last:pb-0' ); ?>>
	<div class="flex flex-col md:flex-row md:items-start">
		<?php if ( has_post_thumbnail() ) : ?>
			<div class="entry-thumbnail md:w-1/4 md:mr-6 mb-4 md:mb-0">
				<a href="<?php the_permalink(); ?>" class="block aspect-w-16 aspect-h-9 overflow-hidden rounded-lg">
					<?php the_post_thumbnail( 'medium', array( 'class' => 'w-full h-full object-cover transition-transform duration-300 hover:scale-105' ) ); ?>
				</a>
			</div>
		<?php endif; ?>

		<div class="entry-content <?php echo has_post_thumbnail() ? 'md:w-3/4' : 'w-full'; ?>">
			<header class="entry-header mb-2">
				<?php the_title( sprintf( '<h2 class="entry-title text-xl font-serif font-bold text-gray-900 dark:text-gray-100 mb-1"><a href="%s" rel="bookmark" class="hover:text-primary-600 dark:hover:text-primary-400 transition-colors duration-300">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>

				<?php if ( 'post' === get_post_type() ) : ?>
					<div class="entry-meta flex flex-wrap items-center text-xs text-gray-600 dark:text-gray-400 mb-2">
						<?php
						aqualuxe_posted_on();
						aqualuxe_post_categories();
						?>
					</div><!-- .entry-meta -->
				<?php elseif ( 'page' === get_post_type() ) : ?>
					<div class="entry-meta text-xs text-gray-600 dark:text-gray-400 mb-2">
						<span class="post-type">
							<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
							</svg>
							<?php esc_html_e( 'Page', 'aqualuxe' ); ?>
						</span>
					</div>
				<?php elseif ( 'product' === get_post_type() && class_exists( 'WooCommerce' ) ) : ?>
					<div class="entry-meta text-xs text-gray-600 dark:text-gray-400 mb-2">
						<span class="post-type">
							<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
							</svg>
							<?php esc_html_e( 'Product', 'aqualuxe' ); ?>
						</span>
						<?php if ( $product = wc_get_product( get_the_ID() ) ) : ?>
							<span class="product-price ml-2">
								<?php echo $product->get_price_html(); ?>
							</span>
						<?php endif; ?>
					</div>
				<?php endif; ?>
			</header><!-- .entry-header -->

			<div class="entry-summary text-gray-700 dark:text-gray-300 text-sm mb-3">
				<?php the_excerpt(); ?>
			</div><!-- .entry-summary -->

			<footer class="entry-footer">
				<a href="<?php the_permalink(); ?>" class="inline-flex items-center text-sm text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 font-medium transition-colors duration-300">
					<?php 
					if ( 'product' === get_post_type() && class_exists( 'WooCommerce' ) ) {
						esc_html_e( 'View Product', 'aqualuxe' );
					} elseif ( 'page' === get_post_type() ) {
						esc_html_e( 'View Page', 'aqualuxe' );
					} else {
						esc_html_e( 'Read More', 'aqualuxe' );
					}
					?>
					<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
					</svg>
				</a>
			</footer><!-- .entry-footer -->
		</div>
	</div>
</article><!-- #post-<?php the_ID(); ?> -->