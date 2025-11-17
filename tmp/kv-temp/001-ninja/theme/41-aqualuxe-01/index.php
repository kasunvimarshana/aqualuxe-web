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

	<main id="primary" class="site-main container mx-auto px-4 py-12">

		<?php if ( is_home() && ! is_front_page() ) : ?>
			<header class="page-header mb-12">
				<h1 class="page-title text-4xl font-serif font-bold text-dark-900 dark:text-white mb-4">
					<?php single_post_title(); ?>
				</h1>
				<?php
				if ( function_exists( 'yoast_breadcrumb' ) ) {
					yoast_breadcrumb( '<div class="breadcrumbs text-sm text-dark-500 dark:text-dark-400">', '</div>' );
				}
				?>
			</header>
		<?php endif; ?>

		<div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
			<div class="lg:col-span-2">
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
						get_template_part( 'template-parts/content/content', get_post_type() );

					endwhile;

					get_template_part( 'template-parts/components/pagination' );

				else :

					get_template_part( 'template-parts/content/content', 'none' );

				endif;
				?>
			</div>

			<div class="lg:col-span-1">
				<?php get_sidebar(); ?>
			</div>
		</div>

	</main><!-- #main -->

<?php
get_footer();