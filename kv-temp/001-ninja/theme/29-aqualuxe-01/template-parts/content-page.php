<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('bg-white dark:bg-dark-700 rounded-lg shadow-soft overflow-hidden'); ?>>
	<?php if ( has_post_thumbnail() && ! aqualuxe_is_page_header_hidden() ) : ?>
		<div class="page-thumbnail">
			<?php the_post_thumbnail( 'full', array( 'class' => 'w-full h-auto' ) ); ?>
		</div>
	<?php endif; ?>

	<div class="p-8">
		<?php if ( ! aqualuxe_is_page_title_hidden() ) : ?>
			<header class="entry-header mb-8">
				<?php the_title( '<h1 class="entry-title text-3xl md:text-4xl lg:text-5xl font-serif font-medium">', '</h1>' ); ?>
			</header><!-- .entry-header -->
		<?php endif; ?>

		<div class="entry-content prose dark:prose-invert max-w-none">
			<?php
			the_content();

			wp_link_pages(
				array(
					'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'aqualuxe' ),
					'after'  => '</div>',
				)
			);
			?>
		</div><!-- .entry-content -->

		<?php if ( get_edit_post_link() ) : ?>
			<footer class="entry-footer mt-8 pt-6 border-t border-gray-200 dark:border-dark-600">
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
					'<span class="edit-link inline-flex items-center text-sm text-dark-500 dark:text-dark-300 hover:text-primary-600 dark:hover:text-primary-400 transition-colors duration-200">',
					'</span>'
				);
				?>
			</footer><!-- .entry-footer -->
		<?php endif; ?>
	</div>
</article><!-- #post-<?php the_ID(); ?> -->