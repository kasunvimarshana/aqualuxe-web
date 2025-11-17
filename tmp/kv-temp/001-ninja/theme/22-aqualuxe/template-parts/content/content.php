<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'mb-12 pb-12 border-b border-gray-200 dark:border-gray-700' ); ?>>
	<header class="entry-header mb-6">
		<?php
		if ( is_singular() ) :
			the_title( '<h1 class="entry-title text-4xl font-serif font-bold mb-4">', '</h1>' );
		else :
			the_title( '<h2 class="entry-title text-2xl font-serif font-bold mb-3"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark" class="hover:text-primary-500 transition-colors duration-200">', '</a></h2>' );
		endif;

		if ( 'post' === get_post_type() ) :
			?>
			<div class="entry-meta flex flex-wrap items-center text-sm text-gray-600 dark:text-gray-400 mb-4">
				<?php
				aqualuxe_posted_by();
				aqualuxe_posted_on();
				?>
				
				<?php if ( get_comments_number() ) : ?>
					<span class="comments-link ml-4 flex items-center">
						<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
						</svg>
						<?php comments_popup_link(); ?>
					</span>
				<?php endif; ?>
			</div><!-- .entry-meta -->
		<?php endif; ?>
	</header><!-- .entry-header -->

	<?php if ( has_post_thumbnail() && ! is_singular() ) : ?>
		<div class="entry-thumbnail mb-6">
			<a href="<?php the_permalink(); ?>" class="block overflow-hidden rounded-lg">
				<?php the_post_thumbnail( 'aqualuxe-featured', array( 'class' => 'w-full h-auto hover:scale-105 transition-transform duration-300' ) ); ?>
			</a>
		</div>
	<?php endif; ?>

	<div class="entry-content prose dark:prose-invert max-w-none">
		<?php
		if ( is_singular() ) :
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
		else :
			the_excerpt();
			?>
			<div class="read-more mt-4">
				<a href="<?php the_permalink(); ?>" class="btn-primary">
					<?php esc_html_e( 'Read More', 'aqualuxe' ); ?>
					<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" viewBox="0 0 20 20" fill="currentColor">
						<path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
					</svg>
				</a>
			</div>
			<?php
		endif;
		?>
	</div><!-- .entry-content -->

	<?php if ( is_singular() && 'post' === get_post_type() ) : ?>
		<footer class="entry-footer mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
			<?php aqualuxe_entry_footer(); ?>
		</footer><!-- .entry-footer -->
	<?php endif; ?>
</article><!-- #post-<?php the_ID(); ?> -->