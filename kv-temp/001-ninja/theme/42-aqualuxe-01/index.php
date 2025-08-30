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

get_header();
?>

	<main id="primary" class="site-main py-12">
		<div class="container mx-auto px-4">
			<?php if ( is_home() && ! is_front_page() ) : ?>
				<header class="page-header mb-8">
					<h1 class="page-title text-3xl font-bold text-primary-800 mb-2">
						<?php single_post_title(); ?>
					</h1>
					<?php
					if ( function_exists( 'yoast_breadcrumb' ) ) {
						yoast_breadcrumb( '<div class="breadcrumbs text-sm text-gray-600 mb-4">', '</div>' );
					} else {
						aqualuxe_breadcrumbs();
					}
					?>
				</header>
			<?php endif; ?>

			<div class="content-area flex flex-wrap">
				<div class="primary-content w-full lg:w-2/3 lg:pr-8">
					<?php
					if ( have_posts() ) :

						/* Start the Loop */
						while ( have_posts() ) :
							the_post();

							/*
							 * Include the Post-Type-specific template for the content.
							 * If you want to override this in a child theme, then include a file
							 * called content-___.php (where ___ is the Post Type name) and that will be used instead.
							 */
							get_template_part( 'template-parts/content', get_post_type() );

						endwhile;

						// Pagination
						echo '<div class="pagination-container mt-8">';
						aqualuxe_pagination();
						echo '</div>';

					else :

						get_template_part( 'template-parts/content', 'none' );

					endif;
					?>
				</div>

				<div class="sidebar w-full lg:w-1/3 mt-8 lg:mt-0">
					<?php get_sidebar(); ?>
				</div>
			</div>
		</div>
	</main><!-- #main -->

<?php
get_footer();