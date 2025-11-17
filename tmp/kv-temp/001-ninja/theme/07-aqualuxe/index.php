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

	<main id="primary" class="site-main container mx-auto px-4 py-8">

		<?php if ( is_home() && ! is_front_page() ) : ?>
			<header class="page-header mb-8">
				<h1 class="page-title text-3xl md:text-4xl font-serif font-bold text-gray-900 dark:text-gray-100">
					<?php single_post_title(); ?>
				</h1>
			</header>
		<?php endif; ?>

		<div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
			<div class="lg:col-span-8">
				<?php
				if ( have_posts() ) :

					if ( is_home() && ! is_front_page() ) :
						?>
						<header>
							<h1 class="page-title screen-reader-text"><?php single_post_title(); ?></h1>
						</header>
						<?php
					endif;

					/* Start the Loop */
					while ( have_posts() ) :
						the_post();

						/*
						 * Include the Post-Type-specific template for the content.
						 * If you want to override this in a child theme, then include a file
						 * called content-___.php (where ___ is the Post Type name) and that will be used instead.
						 */
						get_template_part( 'templates/content', get_post_type() );

					endwhile;

					get_template_part( 'templates/parts/pagination' );

				else :

					get_template_part( 'templates/content', 'none' );

				endif;
				?>
			</div>

			<div class="lg:col-span-4">
				<?php get_sidebar(); ?>
			</div>
		</div>

	</main><!-- #main -->

<?php
get_footer();