<?php
/**
 * Template part for displaying single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('single-post'); ?>>
	<header class="entry-header">
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>

		<div class="entry-meta">
			<?php
			if ( function_exists( 'aqualuxe_posted_on' ) ) :
				aqualuxe_posted_on();
			endif;
			
			if ( function_exists( 'aqualuxe_posted_by' ) ) :
				aqualuxe_posted_by();
			endif;
			?>
		</div><!-- .entry-meta -->
	</header><!-- .entry-header -->

	<?php if ( has_post_thumbnail() ) : ?>
	<div class="post-thumbnail">
		<?php the_post_thumbnail('large', array('class' => 'single-post-thumbnail')); ?>
	</div>
	<?php endif; ?>

	<div class="entry-content">
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

	<footer class="entry-footer">
		<?php if ( function_exists( 'aqualuxe_entry_footer' ) ) : ?>
			<?php aqualuxe_entry_footer(); ?>
		<?php endif; ?>
	</footer><!-- .entry-footer -->

	<?php if ( function_exists( 'aqualuxe_author_bio' ) ) : ?>
		<?php aqualuxe_author_bio(); ?>
	<?php endif; ?>
</article><!-- #post-<?php the_ID(); ?> -->