<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

get_header();
?>

<main id="primary" class="site-main">

	<?php
	/**
	 * Hook: aqualuxe_before_main_content.
	 *
	 * @hooked aqualuxe_breadcrumbs - 10
	 */
	do_action( 'aqualuxe_before_main_content' );
	?>

	<div class="container">
		<div class="content-area">
			<?php
			while ( have_posts() ) :
				the_post();

				get_template_part( 'template-parts/content', 'page' );

				// If comments are open or we have at least one comment, load up the comment template.
				if ( comments_open() || get_comments_number() ) :
					comments_template();
				endif;

			endwhile; // End of the loop.
			?>
		</div><!-- .content-area -->

		<?php
		/**
		 * Hook: aqualuxe_sidebar.
		 *
		 * @hooked aqualuxe_get_sidebar - 10
		 */
		do_action( 'aqualuxe_sidebar' );
		?>
	</div><!-- .container -->

	<?php
	/**
	 * Hook: aqualuxe_after_main_content.
	 */
	do_action( 'aqualuxe_after_main_content' );
	?>

</main><!-- #primary -->

<?php
get_footer();