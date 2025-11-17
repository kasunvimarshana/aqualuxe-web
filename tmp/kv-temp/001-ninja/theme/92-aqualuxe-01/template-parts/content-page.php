<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden'); ?>>
	<header class="entry-header px-4 py-5 sm:px-6">
		<?php the_title( '<h1 class="entry-title text-3xl font-extrabold leading-tight text-gray-900 dark:text-white">', '</h1>' ); ?>
	</header><!-- .entry-header -->

	<?php aqualuxe_post_thumbnail(); ?>

	<div class="entry-content prose dark:prose-invert lg:prose-xl max-w-none px-4 py-5 sm:px-6">
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
		<footer class="entry-footer px-4 py-4 sm:px-6 border-t border-gray-200 dark:border-gray-700">
			<?php
			edit_post_link(
				sprintf(
					/* translators: %s: Name of current post. Only visible to screen readers. */
					esc_html__( 'Edit %s', 'aqualuxe' ),
					'<span class="screen-reader-text">' . get_the_title() . '</span>'
				),
				'<span class="edit-link text-sm text-gray-500 hover:text-blue-600">',
				'</span>'
			);
			?>
		</footer><!-- .entry-footer -->
	<?php endif; ?>
</article><!-- #post-<?php the_ID(); ?> -->
