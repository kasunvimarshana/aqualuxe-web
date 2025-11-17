<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-shadow duration-300'); ?>>
	
	<?php aqualuxe_post_thumbnail(); ?>

	<header class="entry-header p-6">
		<?php
		if ( is_singular() ) :
			the_title( '<h1 class="entry-title text-3xl font-bold text-gray-900 dark:text-white mb-4">', '</h1>' );
		else :
			the_title( '<h2 class="entry-title text-2xl font-bold text-gray-900 dark:text-white"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark" class="hover:text-blue-600 dark:hover:text-blue-400">', '</a></h2>' );
		endif;

		if ( 'post' === get_post_type() ) :
			?>
			<div class="entry-meta text-sm text-gray-500 dark:text-gray-400 mt-2">
				<?php
				aqualuxe_posted_on();
				aqualuxe_posted_by();
				?>
			</div><!-- .entry-meta -->
		<?php endif; ?>
	</header><!-- .entry-header -->

	<div class="entry-content p-6 prose dark:prose-invert max-w-none">
		<?php
		if ( is_singular() ) {
			the_content(
				sprintf(
					wp_kses(
						/* translators: %s: Name of current post. Only visible to screen readers */
						__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'aqualuxe' ),
						array(
							'span' => array(
								'class' => array(),
							),
						)
					),
					wp_kses_post( get_the_title() )
				)
			);

			wp_link_pages(
				array(
					'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'aqualuxe' ),
					'after'  => '</div>',
				)
			);
		} else {
			the_excerpt();
		}
		?>
	</div><!-- .entry-content -->

	<footer class="entry-footer p-6 bg-gray-50 dark:bg-gray-700/50 border-t border-gray-200 dark:border-gray-600">
		<?php aqualuxe_entry_footer(); ?>
		<?php if ( ! is_singular() ) : ?>
			<a href="<?php the_permalink(); ?>" class="text-blue-600 dark:text-blue-400 hover:underline mt-4 inline-block"><?php _e( 'Continue reading', 'aqualuxe' ); ?> &rarr;</a>
		<?php endif; ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-<?php the_ID(); ?> -->
