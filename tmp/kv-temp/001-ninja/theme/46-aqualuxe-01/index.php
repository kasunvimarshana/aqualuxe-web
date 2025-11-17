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
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

get_header();
?>

	<main id="primary" class="site-main container mx-auto px-4 py-8">

		<?php
		if ( have_posts() ) :

			if ( is_home() && ! is_front_page() ) :
				?>
				<header class="page-header mb-8">
					<?php aqualuxe_before_page_title(); ?>
					<h1 class="page-title text-3xl font-bold mb-4"><?php single_post_title(); ?></h1>
					<?php aqualuxe_after_page_title(); ?>
				</header>
				<?php
			endif;

			aqualuxe_before_archive_loop();

			/* Start the Loop */
			while ( have_posts() ) :
				the_post();

				/*
				 * Include the Post-Type-specific template for the content.
				 * If you want to override this in a child theme, then include a file
				 * called content-___.php (where ___ is the Post Type name) and that will be used instead.
				 */
				get_template_part( 'template-parts/content/content', get_post_type() );

			endwhile;

			aqualuxe_before_pagination();
			aqualuxe_pagination();
			aqualuxe_after_pagination();

			aqualuxe_after_archive_loop();

		else :

			get_template_part( 'template-parts/content/content', 'none' );

		endif;
		?>

	</main><!-- #main -->

<?php
get_sidebar();
get_footer();