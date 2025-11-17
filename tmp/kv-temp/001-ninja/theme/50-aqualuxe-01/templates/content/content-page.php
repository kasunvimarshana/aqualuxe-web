<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'page-content' ); ?>>
	<header class="page-header">
		<?php the_title( '<h1 class="page-title">', '</h1>' ); ?>
	</header><!-- .page-header -->

	<?php if ( has_post_thumbnail() ) : ?>
		<div class="page-thumbnail">
			<?php the_post_thumbnail( 'large', array( 'class' => 'page-image' ) ); ?>
			
			<?php if ( get_the_post_thumbnail_caption() ) : ?>
				<figcaption class="page-thumbnail-caption"><?php the_post_thumbnail_caption(); ?></figcaption>
			<?php endif; ?>
		</div>
	<?php endif; ?>

	<div class="page-content-inner">
		<?php
		the_content();

		wp_link_pages(
			array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'aqualuxe' ),
				'after'  => '</div>',
			)
		);
		?>
	</div><!-- .page-content-inner -->

	<?php if ( get_edit_post_link() ) : ?>
		<footer class="page-footer">
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
				'<span class="edit-link">',
				'</span>'
			);
			?>
		</footer><!-- .page-footer -->
	<?php endif; ?>
</article><!-- #post-<?php the_ID(); ?> -->