<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

get_header();
?>

<div id="primary" class="content-area">
	<main id="main" class="site-main">

		<?php
		while ( have_posts() ) :
			the_post();

			get_template_part( 'templates/parts/content/content', 'single' );

			// If enabled, display the post navigation
			the_post_navigation(
				array(
					'prev_text' => '<span class="nav-subtitle">' . esc_html__( 'Previous:', 'aqualuxe' ) . '</span> <span class="nav-title">%title</span>',
					'next_text' => '<span class="nav-subtitle">' . esc_html__( 'Next:', 'aqualuxe' ) . '</span> <span class="nav-title">%title</span>',
				)
			);

			// If enabled, display the author bio
			if ( get_theme_mod( 'aqualuxe_show_author_bio', true ) ) {
				get_template_part( 'templates/parts/content/author', 'bio' );
			}

			// If enabled, display related posts
			if ( get_theme_mod( 'aqualuxe_show_related_posts', true ) ) {
				get_template_part( 'templates/parts/content/related', 'posts' );
			}

			// If comments are open or we have at least one comment, load up the comment template.
			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;

		endwhile; // End of the loop.
		?>

	</main><!-- #main -->
</div><!-- #primary -->

<?php
$layout = get_theme_mod( 'aqualuxe_layout', 'right-sidebar' );
if ( 'full-width' !== $layout ) {
	get_sidebar();
}

get_footer();