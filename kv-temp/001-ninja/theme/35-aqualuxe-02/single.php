<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package AquaLuxe
 */

get_header();

$blog_sidebar = get_theme_mod( 'aqualuxe_blog_sidebar', 'right' );
$content_class = 'site-main';

if ( 'left' === $blog_sidebar ) {
	$content_class .= ' has-left-sidebar';
} elseif ( 'right' === $blog_sidebar ) {
	$content_class .= ' has-right-sidebar';
}
?>

	<main id="primary" class="<?php echo esc_attr( $content_class ); ?>">

		<?php
		while ( have_posts() ) :
			the_post();

			get_template_part( 'templates/parts/content', 'single' );

			/**
			 * Hook: aqualuxe_after_post_content.
			 *
			 * @hooked aqualuxe_post_tags - 10
			 * @hooked aqualuxe_post_navigation - 20
			 * @hooked aqualuxe_related_posts - 30
			 */
			do_action( 'aqualuxe_after_post_content' );

			/**
			 * Hook: aqualuxe_comments_before.
			 *
			 * @hooked none
			 */
			do_action( 'aqualuxe_comments_before' );

			// If comments are open or we have at least one comment, load up the comment template.
			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;

			/**
			 * Hook: aqualuxe_comments_after.
			 *
			 * @hooked none
			 */
			do_action( 'aqualuxe_comments_after' );

		endwhile; // End of the loop.
		?>

	</main><!-- #main -->

<?php
/**
 * Hook: aqualuxe_sidebar.
 *
 * @hooked aqualuxe_get_sidebar - 10
 */
do_action( 'aqualuxe_sidebar' );

get_footer();