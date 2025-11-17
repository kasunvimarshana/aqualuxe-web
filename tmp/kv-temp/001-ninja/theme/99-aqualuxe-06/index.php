<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 * @since 2.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

/**
 * Hook: aqualuxe_before_main_content
 *
 * @hooked aqualuxe_output_content_wrapper - 10
 */
do_action( 'aqualuxe_before_main_content' );
?>

<main id="primary" class="site-main" role="main">
	<?php
	/**
	 * Hook: aqualuxe_main_content_start
	 */
	do_action( 'aqualuxe_main_content_start' );

	if ( have_posts() ) :

		/* Start the Loop */
		while ( have_posts() ) :
			the_post();

			/*
			 * Include the Post-Type-specific template for the content.
			 * If you want to override this in a child theme, then include a file
			 * called content-___.php (where ___ is the Post Type name) and that will be used instead.
			 */
			get_template_part( 'templates/content/content', get_post_type() );

		endwhile;

		/**
		 * Hook: aqualuxe_after_posts_loop
		 *
		 * @hooked aqualuxe_posts_navigation - 10
		 */
		do_action( 'aqualuxe_after_posts_loop' );

	else :

		get_template_part( 'templates/content/content', 'none' );

	endif;

	/**
	 * Hook: aqualuxe_main_content_end
	 */
	do_action( 'aqualuxe_main_content_end' );
	?>
</main><!-- #primary -->

<?php
/**
 * Hook: aqualuxe_after_main_content
 *
 * @hooked aqualuxe_output_content_wrapper_end - 10
 * @hooked aqualuxe_get_sidebar - 20
 */
do_action( 'aqualuxe_after_main_content' );

get_footer();