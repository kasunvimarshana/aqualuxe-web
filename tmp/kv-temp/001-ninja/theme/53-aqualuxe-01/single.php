<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package AquaLuxe
 */

get_header();
?>

	<main id="primary" class="<?php echo esc_attr( aqualuxe_get_main_class() ); ?>">

		<?php
		while ( have_posts() ) :
			the_post();

			get_template_part( 'templates/content', 'single' );

			// If comments are open or we have at least one comment, load up the comment template.
			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;

			aqualuxe_post_navigation();

		endwhile; // End of the loop.
		?>

	</main><!-- #primary -->

<?php
if ( aqualuxe_has_sidebar() ) {
	get_sidebar();
}
get_footer();