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
	<header class="entry-header mb-6">
		<?php 
		// Only show the title if it's not hidden via custom field
		$hide_title = get_post_meta( get_the_ID(), '_aqualuxe_hide_title', true );
		if ( ! $hide_title ) :
			the_title( '<h1 class="entry-title text-3xl md:text-4xl font-bold">', '</h1>' ); 
		endif;
		?>
	</header><!-- .entry-header -->

	<?php if ( has_post_thumbnail() && ! aqualuxe_is_page_header_image() ) : ?>
		<div class="entry-thumbnail mb-6">
			<?php the_post_thumbnail( 'aqualuxe-featured', array( 'class' => 'w-full h-auto rounded-lg' ) ); ?>
		</div>
	<?php endif; ?>

	<div class="entry-content prose max-w-none">
		<?php
		the_content();

		wp_link_pages(
			array(
				'before' => '<div class="page-links mt-6 pt-6 border-t border-gray-200">' . esc_html__( 'Pages:', 'aqualuxe' ),
				'after'  => '</div>',
				'link_before' => '<span class="page-number">',
				'link_after'  => '</span>',
			)
		);
		?>
	</div><!-- .entry-content -->

	<?php if ( get_edit_post_link() ) : ?>
		<footer class="entry-footer mt-8 pt-4 border-t border-gray-200 text-sm">
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
				'<span class="edit-link flex items-center text-gray-600 hover:text-primary-600">',
				'</span>',
				null,
				'inline-flex items-center'
			);
			?>
		</footer><!-- .entry-footer -->
	<?php endif; ?>
</article><!-- #post-<?php the_ID(); ?> -->