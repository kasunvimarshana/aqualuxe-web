<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'bg-white dark:bg-dark-800 rounded-lg overflow-hidden shadow-sm border border-gray-100 dark:border-dark-700 p-6 sm:p-8' ); ?>>
	<header class="entry-header mb-6">
		<?php
		if ( ! is_front_page() ) :
			the_title( '<h1 class="entry-title text-3xl sm:text-4xl font-serif font-bold text-dark-800 dark:text-white mb-4">', '</h1>' );
		endif;
		
		// Featured image
		if ( has_post_thumbnail() && ! get_theme_mod( 'aqualuxe_page_hide_featured_image', false ) ) :
			?>
			<div class="post-thumbnail mb-6">
				<?php
				the_post_thumbnail(
					'full',
					array(
						'class' => 'w-full h-auto rounded-lg',
						'alt'   => the_title_attribute( array( 'echo' => false ) ),
					)
				);
				?>
			</div>
		<?php endif; ?>
	</header><!-- .entry-header -->

	<div class="entry-content prose dark:prose-invert max-w-none text-gray-700 dark:text-gray-200">
		<?php
		the_content();

		wp_link_pages(
			array(
				'before' => '<div class="page-links mt-6 pt-6 border-t border-gray-200 dark:border-dark-700">' . esc_html__( 'Pages:', 'aqualuxe' ),
				'after'  => '</div>',
				'link_before' => '<span class="page-number px-3 py-1 bg-gray-100 dark:bg-dark-700 rounded-md mr-2">',
				'link_after'  => '</span>',
			)
		);
		?>
	</div><!-- .entry-content -->

	<?php if ( get_edit_post_link() ) : ?>
		<footer class="entry-footer mt-6 pt-6 border-t border-gray-200 dark:border-dark-700 text-sm text-gray-500 dark:text-gray-400">
			<?php
			edit_post_link(
				sprintf(
					wp_kses(
						/* translators: %s: Name of current post. Only visible to screen readers */
						__( 'Edit <span class="screen-reader-text">%s</span>', 'aqualuxe' ),
						array(
							'span' => array(
								'class' => array(),
							),
						)
					),
					wp_kses_post( get_the_title() )
				),
				'<span class="edit-link flex items-center">',
				'</span>',
				null,
				'inline-flex items-center px-3 py-1 bg-gray-100 dark:bg-dark-700 hover:bg-gray-200 dark:hover:bg-dark-600 rounded-md transition-colors duration-200'
			);
			?>
		</footer><!-- .entry-footer -->
	<?php endif; ?>
</article><!-- #post-<?php the_ID(); ?> -->