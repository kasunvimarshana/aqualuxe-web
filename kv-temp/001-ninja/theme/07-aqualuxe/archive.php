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

	<main id="primary" class="site-main container mx-auto px-4 py-8">

		<?php if ( have_posts() ) : ?>

			<header class="page-header mb-8">
				<?php
				the_archive_title( '<h1 class="page-title text-3xl md:text-4xl font-serif font-bold text-gray-900 dark:text-gray-100 mb-2">', '</h1>' );
				the_archive_description( '<div class="archive-description text-gray-600 dark:text-gray-400">', '</div>' );
				?>
			</header><!-- .page-header -->

			<div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
				<div class="lg:col-span-8">
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

					<?php get_template_part( 'templates/parts/pagination' ); ?>

				</div>

				<div class="lg:col-span-4">
					<?php get_sidebar(); ?>
				</div>
			</div>

		<?php else : ?>

			<?php get_template_part( 'templates/content', 'none' ); ?>

		<?php endif; ?>

	</main><!-- #main -->

<?php
get_footer();