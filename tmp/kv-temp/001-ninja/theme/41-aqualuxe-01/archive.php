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

	<main id="primary" class="site-main container mx-auto px-4 py-12">

		<?php if ( have_posts() ) : ?>

			<header class="page-header mb-12">
				<?php
				if ( function_exists( 'yoast_breadcrumb' ) ) {
					yoast_breadcrumb( '<div class="breadcrumbs text-sm text-dark-500 dark:text-dark-400 mb-4">', '</div>' );
				}
				
				the_archive_title( '<h1 class="page-title text-4xl font-serif font-bold text-dark-900 dark:text-white mb-4">', '</h1>' );
				the_archive_description( '<div class="archive-description prose dark:prose-dark">', '</div>' );
				?>
			</header><!-- .page-header -->

			<div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
				<div class="lg:col-span-2">
					<div class="grid grid-cols-1 md:grid-cols-2 gap-8">
						<?php
						/* Start the Loop */
						while ( have_posts() ) :
							the_post();

							/*
							 * Include the Post-Type-specific template for the content.
							 * If you want to override this in a child theme, then include a file
							 * called content-___.php (where ___ is the Post Type name) and that will be used instead.
							 */
							get_template_part( 'template-parts/content/content', 'archive' );

						endwhile;
						?>
					</div>

					<?php get_template_part( 'template-parts/components/pagination' ); ?>
				</div>

				<div class="lg:col-span-1">
					<?php get_sidebar(); ?>
				</div>
			</div>

		<?php else : ?>

			<div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
				<div class="lg:col-span-2">
					<?php get_template_part( 'template-parts/content/content', 'none' ); ?>
				</div>

				<div class="lg:col-span-1">
					<?php get_sidebar(); ?>
				</div>
			</div>

		<?php endif; ?>

	</main><!-- #main -->

<?php
get_footer();