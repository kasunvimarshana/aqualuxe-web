<?php
/**
 * Template part for displaying results in search pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('bg-white rounded-lg shadow-md overflow-hidden mb-6'); ?>>
	<div class="flex flex-col md:flex-row">
		<?php if ( has_post_thumbnail() ) : ?>
			<div class="search-thumbnail md:w-1/4">
				<a href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
					<?php the_post_thumbnail( 'medium', array( 'class' => 'w-full h-full object-cover' ) ); ?>
				</a>
			</div>
		<?php endif; ?>

		<div class="search-content p-6 <?php echo has_post_thumbnail() ? 'md:w-3/4' : 'w-full'; ?>">
			<header class="entry-header mb-3">
				<?php the_title( sprintf( '<h2 class="entry-title text-xl font-bold mb-2"><a href="%s" rel="bookmark" class="text-gray-900 hover:text-primary transition-colors">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>

				<?php if ( 'post' === get_post_type() ) : ?>
					<div class="entry-meta text-sm text-gray-600">
						<?php
						aqualuxe_posted_by();
						aqualuxe_posted_on();
						?>
					</div><!-- .entry-meta -->
				<?php elseif ( 'page' === get_post_type() ) : ?>
					<div class="entry-meta text-sm text-gray-600">
						<span class="post-type"><?php esc_html_e( 'Page', 'aqualuxe' ); ?></span>
					</div>
				<?php elseif ( 'product' === get_post_type() && function_exists( 'aqualuxe_is_woocommerce_active' ) && aqualuxe_is_woocommerce_active() ) : ?>
					<div class="entry-meta text-sm text-gray-600">
						<span class="post-type"><?php esc_html_e( 'Product', 'aqualuxe' ); ?></span>
						<?php if ( function_exists( 'wc_get_product' ) ) : ?>
							<?php
							$product = wc_get_product( get_the_ID() );
							if ( $product ) :
								?>
								<span class="product-price ml-2">
									<?php echo wp_kses_post( $product->get_price_html() ); ?>
								</span>
							<?php endif; ?>
						<?php endif; ?>
					</div>
				<?php else : ?>
					<div class="entry-meta text-sm text-gray-600">
						<span class="post-type"><?php echo esc_html( get_post_type_object( get_post_type() )->labels->singular_name ); ?></span>
					</div>
				<?php endif; ?>
			</header><!-- .entry-header -->

			<div class="entry-summary mb-4">
				<?php the_excerpt(); ?>
			</div><!-- .entry-summary -->

			<footer class="entry-footer">
				<a href="<?php the_permalink(); ?>" class="read-more text-primary hover:underline font-medium">
					<?php 
					if ( 'product' === get_post_type() && function_exists( 'aqualuxe_is_woocommerce_active' ) && aqualuxe_is_woocommerce_active() ) {
						esc_html_e( 'View Product', 'aqualuxe' );
					} else {
						esc_html_e( 'Read More', 'aqualuxe' );
					}
					?>
					<span aria-hidden="true">&rarr;</span>
				</a>
			</footer><!-- .entry-footer -->
		</div>
	</div>
</article><!-- #post-<?php the_ID(); ?> -->