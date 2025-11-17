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

		<?php
		// Display breadcrumbs if function exists
		if ( function_exists( 'aqualuxe_breadcrumbs' ) ) :
			aqualuxe_breadcrumbs();
		endif;
		?>

		<div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
			<div class="lg:col-span-8">
				<?php if ( have_posts() ) : ?>

					<header class="page-header mb-8">
						<?php
						the_archive_title( '<h1 class="page-title text-3xl sm:text-4xl font-serif font-bold text-dark-800 dark:text-white mb-4">', '</h1>' );
						the_archive_description( '<div class="archive-description text-gray-600 dark:text-gray-300">', '</div>' );
						?>
					</header><!-- .page-header -->

					<?php
					// Display category filter if enabled
					if ( is_category() && function_exists( 'aqualuxe_category_filter' ) ) :
						aqualuxe_category_filter();
					endif;
					?>

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
							get_template_part( 'template-parts/content/content', get_post_type() );

						endwhile;
						?>
					</div>

					<div class="pagination-container mt-12">
						<?php aqualuxe_pagination(); ?>
					</div>

				<?php else : ?>

					<?php get_template_part( 'template-parts/content/content', 'none' ); ?>

				<?php endif; ?>
			</div>

			<div class="lg:col-span-4">
				<?php get_sidebar(); ?>
			</div>
		</div>

	</main><!-- #main -->

<?php
get_footer();