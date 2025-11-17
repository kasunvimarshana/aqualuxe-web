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

	<main id="primary" class="<?php echo aqualuxe_get_main_content_class(); ?>">

		<?php
		while ( have_posts() ) :
			the_post();

			get_template_part( 'templates/content', 'single' );

			// If comments are open or we have at least one comment, load up the comment template.
			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;

			// Post navigation
			if ( ! get_post_meta( get_the_ID(), '_aqualuxe_hide_post_navigation', true ) ) :
				aqualuxe_post_navigation();
			endif;

			// Related posts
			if ( ! get_post_meta( get_the_ID(), '_aqualuxe_hide_related_posts', true ) ) :
				aqualuxe_related_posts();
			endif;

		endwhile; // End of the loop.
		?>

	</main><!-- #main -->

<?php
if ( aqualuxe_display_sidebar() ) :
	get_sidebar();
endif;
get_footer();