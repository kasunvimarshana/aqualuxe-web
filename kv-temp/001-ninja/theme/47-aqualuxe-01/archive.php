<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

get_header();
?>

	<main id="primary" class="site-main">
		<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
			<div class="lg:col-span-2">
				<?php if ( have_posts() ) : ?>

					<header class="page-header mb-8">
						<?php
						the_archive_title( '<h1 class="page-title text-3xl font-bold">', '</h1>' );
						the_archive_description( '<div class="archive-description mt-4 text-gray-600">', '</div>' );
						?>
					</header><!-- .page-header -->

					<div class="archive-layout grid grid-cols-1 md:grid-cols-2 gap-6">
						<?php
						/* Start the Loop */
						while ( have_posts() ) :
							the_post();

							/*
							* Include the Post-Type-specific template for the content.
							* If you want to override this in a child theme, then include a file
							* called content-___.php (where ___ is the Post Type name) and that will be used instead.
							*/
							get_template_part( 'templates/content', 'archive' );

						endwhile;
						?>
					</div>

					<?php
					aqualuxe_pagination();

				else :

					get_template_part( 'templates/content', 'none' );

				endif;
				?>
			</div>

			<div class="sidebar-container">
				<?php get_sidebar(); ?>
			</div>
		</div>
	</main><!-- #main -->

<?php
get_footer();