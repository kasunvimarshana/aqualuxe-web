<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('mb-12'); ?>>
	<header class="entry-header mb-4">
		<?php
		if ( is_singular() ) :
			the_title( '<h1 class="entry-title text-4xl font-serif font-bold text-dark-900 dark:text-white">', '</h1>' );
		else :
			the_title( '<h2 class="entry-title text-2xl font-serif font-bold text-dark-900 dark:text-white"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark" class="hover:text-primary-600 dark:hover:text-primary-400 transition-colors">', '</a></h2>' );
		endif;

		if ( 'post' === get_post_type() ) :
			?>
			<div class="entry-meta flex flex-wrap items-center text-sm text-dark-500 dark:text-dark-400 mt-2">
				<?php
				aqualuxe_posted_by();
				aqualuxe_posted_on();
				?>
			</div><!-- .entry-meta -->
		<?php endif; ?>
	</header><!-- .entry-header -->

	<?php if ( has_post_thumbnail() && !is_singular() ) : ?>
		<div class="post-thumbnail mb-4">
			<a href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
				<?php the_post_thumbnail( 'medium_large', array( 'class' => 'w-full h-auto rounded-lg shadow-sm hover:shadow-md transition-shadow' ) ); ?>
			</a>
		</div>
	<?php endif; ?>

	<div class="entry-content prose dark:prose-dark max-w-none">
		<?php
		if ( is_singular() ) :
			the_content(
				sprintf(
					wp_kses(
						/* translators: %s: Name of current post. Only visible to screen readers */
						__( 'Continue reading<span class="sr-only"> "%s"</span>', 'aqualuxe' ),
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
			<div class="mt-4">
				<a href="<?php the_permalink(); ?>" class="inline-flex items-center text-primary-600 dark:text-primary-400 font-medium hover:underline">
					<?php esc_html_e( 'Read more', 'aqualuxe' ); ?>
					<svg class="ml-1 w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
						<path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd"></path>
					</svg>
				</a>
			</div>
		<?php endif; ?>
	</div><!-- .entry-content -->

	<?php if ( is_singular() ) : ?>
		<footer class="entry-footer mt-6 text-sm text-dark-500 dark:text-dark-400">
			<?php aqualuxe_entry_footer(); ?>
		</footer><!-- .entry-footer -->
	<?php endif; ?>
</article><!-- #post-<?php the_ID(); ?> -->