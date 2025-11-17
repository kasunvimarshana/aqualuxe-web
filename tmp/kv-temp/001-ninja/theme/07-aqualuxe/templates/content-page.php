<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header mb-8">
		<?php the_title( '<h1 class="entry-title text-3xl md:text-4xl lg:text-5xl font-serif font-bold text-gray-900 dark:text-gray-100">', '</h1>' ); ?>
	</header><!-- .entry-header -->

	<?php if ( has_post_thumbnail() && get_theme_mod( 'aqualuxe_page_featured_image', true ) ) : ?>
		<div class="entry-thumbnail mb-8">
			<div class="aspect-w-16 aspect-h-9 overflow-hidden rounded-lg">
				<?php the_post_thumbnail( 'full', array( 'class' => 'w-full h-full object-cover' ) ); ?>
			</div>
			<?php if ( $caption = get_the_post_thumbnail_caption() ) : ?>
				<div class="thumbnail-caption text-sm text-gray-600 dark:text-gray-400 mt-2 text-center">
					<?php echo wp_kses_post( $caption ); ?>
				</div>
			<?php endif; ?>
		</div>
	<?php endif; ?>

	<div class="entry-content prose dark:prose-invert lg:prose-lg max-w-none">
		<?php
		the_content();

		wp_link_pages(
			array(
				'before' => '<div class="page-links mt-6 pt-6 border-t border-gray-200 dark:border-dark-700">' . esc_html__( 'Pages:', 'aqualuxe' ),
				'after'  => '</div>',
			)
		);
		?>
	</div><!-- .entry-content -->

	<?php if ( get_edit_post_link() && get_theme_mod( 'aqualuxe_show_edit_link', true ) ) : ?>
		<footer class="entry-footer mt-8 pt-6 border-t border-gray-200 dark:border-dark-700">
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
				'<span class="edit-link inline-flex items-center text-sm text-gray-600 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors duration-300">',
				'</span>'
			);
			?>
		</footer><!-- .entry-footer -->
	<?php endif; ?>
</article><!-- #post-<?php the_ID(); ?> -->