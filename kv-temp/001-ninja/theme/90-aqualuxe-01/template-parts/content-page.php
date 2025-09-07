<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('bg-white shadow-md rounded-lg overflow-hidden'); ?>>
	<header class="entry-header p-6">
		<?php the_title( '<h1 class="entry-title text-3xl font-bold">', '</h1>' ); ?>
	</header><!-- .entry-header -->

	<?php if ( has_post_thumbnail() ) : ?>
		<div class="post-thumbnail">
			<?php the_post_thumbnail('large', ['class' => 'w-full h-auto']); ?>
		</div>
	<?php endif; ?>

	<div class="entry-content p-6">
		<?php
		the_content();

		wp_link_pages(
			array(
				'before' => '<div class="page-links mt-4">' . esc_html__( 'Pages:', 'aqualuxe' ),
				'after'  => '</div>',
			)
		);
		?>
	</div><!-- .entry-content -->

	<?php if ( get_edit_post_link() ) : ?>
		<footer class="entry-footer p-6 bg-gray-50 border-t border-gray-200">
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
					get_the_title()
				),
				'<span class="edit-link text-sm text-gray-600 hover:text-blue-500">',
				'</span>'
			);
			?>
		</footer><!-- .entry-footer -->
	<?php endif; ?>
</article><!-- #post-<?php the_ID(); ?> -->
