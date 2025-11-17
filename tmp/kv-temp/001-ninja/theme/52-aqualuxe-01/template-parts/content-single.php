<?php
/**
 * Template part for displaying single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden'); ?>>
	<?php if ( has_post_thumbnail() ) : ?>
		<div class="post-thumbnail">
			<?php the_post_thumbnail( 'full', array( 'class' => 'w-full h-auto' ) ); ?>
		</div>
	<?php endif; ?>

	<div class="post-content p-6">
		<header class="entry-header mb-6">
			<?php the_title( '<h1 class="entry-title text-3xl font-bold">', '</h1>' ); ?>
			
			<div class="entry-meta text-sm text-gray-600 dark:text-gray-400 mt-3">
				<?php
				aqualuxe_posted_on();
				aqualuxe_posted_by();
				?>
			</div><!-- .entry-meta -->
		</header><!-- .entry-header -->

		<div class="entry-content prose dark:prose-invert max-w-none">
			<?php
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
			?>
		</div><!-- .entry-content -->

		<footer class="entry-footer mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
			<?php aqualuxe_entry_footer(); ?>
			
			<?php if ( get_the_author_meta( 'description' ) ) : ?>
				<div class="author-bio mt-8 bg-gray-100 dark:bg-gray-700 p-6 rounded-lg">
					<div class="flex items-center mb-4">
						<div class="author-avatar mr-4">
							<?php echo get_avatar( get_the_author_meta( 'ID' ), 80, '', '', array( 'class' => 'rounded-full' ) ); ?>
						</div>
						<h2 class="author-title text-xl font-bold">
							<?php
							printf(
								/* translators: %s: Author name */
								esc_html__( 'About %s', 'aqualuxe' ),
								get_the_author()
							);
							?>
						</h2>
					</div>
					<div class="author-description prose dark:prose-invert">
						<?php echo wp_kses_post( get_the_author_meta( 'description' ) ); ?>
					</div>
				</div>
			<?php endif; ?>
		</footer><!-- .entry-footer -->
	</div>
</article><!-- #post-<?php the_ID(); ?> -->