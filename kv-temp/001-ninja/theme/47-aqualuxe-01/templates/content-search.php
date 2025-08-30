<?php
/**
 * Template part for displaying results in search pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('search-result mb-6 pb-6 border-b border-gray-200 last:border-0 last:mb-0 last:pb-0'); ?>>
	<header class="entry-header mb-3">
		<?php the_title( sprintf( '<h2 class="entry-title text-xl font-bold"><a href="%s" rel="bookmark" class="hover:text-primary-600 transition-colors">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>

		<?php if ( 'post' === get_post_type() ) : ?>
			<div class="entry-meta text-sm text-gray-600 mt-2">
				<?php
				aqualuxe_posted_on();
				aqualuxe_posted_by();
				?>
			</div><!-- .entry-meta -->
		<?php elseif ( 'page' === get_post_type() ) : ?>
			<div class="entry-meta text-sm text-gray-600 mt-2">
				<span class="post-type">
					<?php aqualuxe_svg_icon( 'page', array( 'class' => 'w-4 h-4 mr-1 inline-block' ) ); ?>
					<?php esc_html_e( 'Page', 'aqualuxe' ); ?>
				</span>
			</div>
		<?php elseif ( 'product' === get_post_type() && aqualuxe_is_woocommerce_active() ) : ?>
			<div class="entry-meta text-sm text-gray-600 mt-2">
				<span class="post-type">
					<?php aqualuxe_svg_icon( 'shopping-bag', array( 'class' => 'w-4 h-4 mr-1 inline-block' ) ); ?>
					<?php esc_html_e( 'Product', 'aqualuxe' ); ?>
				</span>
				<?php if ( function_exists( 'wc_get_product' ) ) : ?>
					<?php $product = wc_get_product( get_the_ID() ); ?>
					<?php if ( $product && $product->get_price_html() ) : ?>
						<span class="product-price ml-3">
							<?php echo wp_kses_post( $product->get_price_html() ); ?>
						</span>
					<?php endif; ?>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	</header><!-- .entry-header -->

	<?php if ( has_post_thumbnail() ) : ?>
		<div class="entry-thumbnail mb-3">
			<a href="<?php the_permalink(); ?>" class="block overflow-hidden rounded-lg w-24 h-24 float-left mr-4">
				<?php the_post_thumbnail( 'thumbnail', array( 'class' => 'w-full h-full object-cover' ) ); ?>
			</a>
		</div>
	<?php endif; ?>

	<div class="entry-summary prose prose-sm max-w-none">
		<?php the_excerpt(); ?>
	</div><!-- .entry-summary -->

	<footer class="entry-footer mt-3 text-sm">
		<?php if ( 'post' === get_post_type() ) : ?>
			<?php
			$categories_list = get_the_category_list( esc_html__( ', ', 'aqualuxe' ) );
			if ( $categories_list ) {
				/* translators: 1: list of categories. */
				printf( '<span class="cat-links text-gray-600">' . esc_html__( 'Posted in %1$s', 'aqualuxe' ) . '</span>', $categories_list ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
			?>
		<?php elseif ( 'product' === get_post_type() && aqualuxe_is_woocommerce_active() ) : ?>
			<?php
			$product_cats = get_the_terms( get_the_ID(), 'product_cat' );
			if ( $product_cats && ! is_wp_error( $product_cats ) ) {
				$cat_links = array();
				foreach ( $product_cats as $product_cat ) {
					$cat_links[] = '<a href="' . esc_url( get_term_link( $product_cat ) ) . '" class="text-gray-600 hover:text-primary-600">' . esc_html( $product_cat->name ) . '</a>';
				}
				echo '<span class="product-categories text-gray-600">' . esc_html__( 'Categories: ', 'aqualuxe' ) . join( ', ', $cat_links ) . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
			?>
		<?php endif; ?>

		<div class="read-more mt-2">
			<a href="<?php the_permalink(); ?>" class="inline-flex items-center text-primary-600 hover:text-primary-700 font-medium">
				<?php 
				if ( 'product' === get_post_type() && aqualuxe_is_woocommerce_active() ) {
					esc_html_e( 'View Product', 'aqualuxe' );
				} else {
					esc_html_e( 'Read More', 'aqualuxe' );
				}
				?>
				<?php aqualuxe_svg_icon( 'arrow-right', array( 'class' => 'w-4 h-4 ml-1' ) ); ?>
			</a>
		</div>
	</footer><!-- .entry-footer -->
</article><!-- #post-<?php the_ID(); ?> -->