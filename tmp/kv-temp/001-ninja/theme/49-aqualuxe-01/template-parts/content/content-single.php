<?php
/**
 * Template part for displaying single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php
		if ( 'post' === get_post_type() ) :
			?>
			<div class="entry-meta">
				<?php
				aqualuxe_posted_on();
				aqualuxe_posted_by();
				
				// Show categories if enabled
				if ( get_theme_mod( 'aqualuxe_show_post_categories', true ) ) {
					$categories_list = get_the_category_list( esc_html__( ', ', 'aqualuxe' ) );
					if ( $categories_list ) {
						echo '<span class="cat-links">' . $categories_list . '</span>';
					}
				}
				?>
			</div><!-- .entry-meta -->
		<?php endif; ?>
		
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
	</header><!-- .entry-header -->

	<?php aqualuxe_post_thumbnail(); ?>

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
		<?php aqualuxe_entry_footer(); ?>
		
		<?php
		// Social sharing
		if ( function_exists( 'aqualuxe_social_sharing' ) && get_theme_mod( 'aqualuxe_display_social_sharing', true ) ) {
			aqualuxe_social_sharing();
		}
		?>
	</footer><!-- .entry-footer -->
</article><!-- #post-<?php the_ID(); ?> -->