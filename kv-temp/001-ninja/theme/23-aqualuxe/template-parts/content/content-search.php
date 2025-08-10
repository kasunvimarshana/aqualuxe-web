<?php
/**
 * Template part for displaying results in search pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('mb-8 pb-8 border-b border-gray-200 dark:border-gray-700'); ?>>
	<header class="entry-header mb-3">
		<?php the_title( sprintf( '<h2 class="entry-title text-xl font-bold"><a class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300" href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>

		<?php if ( 'post' === get_post_type() ) : ?>
		<div class="entry-meta text-sm text-gray-600 dark:text-gray-400 mt-1">
			<?php
			aqualuxe_posted_on();
			aqualuxe_posted_by();
			?>
		</div><!-- .entry-meta -->
		<?php elseif ( 'page' === get_post_type() ) : ?>
			<div class="entry-meta text-sm text-gray-600 dark:text-gray-400 mt-1">
				<span class="post-type"><?php esc_html_e( 'Page', 'aqualuxe' ); ?></span>
			</div>
		<?php elseif ( 'product' === get_post_type() ) : ?>
			<div class="entry-meta text-sm text-gray-600 dark:text-gray-400 mt-1">
				<span class="post-type"><?php esc_html_e( 'Product', 'aqualuxe' ); ?></span>
				<?php if ( function_exists( 'wc_get_product' ) ) : 
					$product = wc_get_product( get_the_ID() );
					if ( $product ) : ?>
						<span class="product-price ml-2"><?php echo wp_kses_post( $product->get_price_html() ); ?></span>
					<?php endif;
				endif; ?>
			</div>
		<?php else : ?>
			<div class="entry-meta text-sm text-gray-600 dark:text-gray-400 mt-1">
				<span class="post-type"><?php echo esc_html( get_post_type_object( get_post_type() )->labels->singular_name ); ?></span>
			</div>
		<?php endif; ?>
	</header><!-- .entry-header -->

	<?php if ( has_post_thumbnail() ) : ?>
		<div class="search-thumbnail mb-3">
			<a href="<?php the_permalink(); ?>">
				<?php the_post_thumbnail( 'medium', array(
					'class' => 'w-full h-auto rounded',
				) ); ?>
			</a>
		</div>
	<?php endif; ?>

	<div class="entry-summary prose dark:prose-invert max-w-none text-sm">
		<?php the_excerpt(); ?>
	</div><!-- .entry-summary -->

	<footer class="entry-footer mt-3 flex flex-wrap gap-2 text-xs text-gray-600 dark:text-gray-400">
		<?php aqualuxe_entry_footer(); ?>
		
		<a href="<?php echo esc_url( get_permalink() ); ?>" class="inline-flex items-center text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
			<?php esc_html_e( 'Read More', 'aqualuxe' ); ?>
			<svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
				<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
			</svg>
		</a>
	</footer><!-- .entry-footer -->
</article><!-- #post-<?php the_ID(); ?> -->