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

	<main id="primary" class="site-main py-12">
		<div class="container mx-auto px-4">
			<?php if ( have_posts() ) : ?>

				<header class="page-header mb-8">
					<?php
					the_archive_title( '<h1 class="page-title text-3xl font-bold text-primary-800 mb-2">', '</h1>' );
					the_archive_description( '<div class="archive-description prose max-w-none text-gray-600 mt-4">', '</div>' );
					
					// Breadcrumbs
					if ( function_exists( 'yoast_breadcrumb' ) ) {
						yoast_breadcrumb( '<div class="breadcrumbs text-sm text-gray-600 mt-4">', '</div>' );
					} else {
						aqualuxe_breadcrumbs();
					}
					?>
				</header><!-- .page-header -->

				<div class="content-area flex flex-wrap">
					<div class="primary-content w-full lg:w-2/3 lg:pr-8">
						<div class="archive-posts grid grid-cols-1 md:grid-cols-2 gap-6">
							<?php
							/* Start the Loop */
							while ( have_posts() ) :
								the_post();

								/*
								 * Include the Post-Type-specific template for the content.
								 * If you want to override this in a child theme, then include a file
								 * called content-___.php (where ___ is the Post Type name) and that will be used instead.
								 */
								get_template_part( 'template-parts/content', 'archive' );

							endwhile;
							?>
						</div>

						<?php
						// Pagination
						echo '<div class="pagination-container mt-8">';
						aqualuxe_pagination();
						echo '</div>';
						?>

					</div>

					<div class="sidebar w-full lg:w-1/3 mt-8 lg:mt-0">
						<?php get_sidebar(); ?>
					</div>
				</div>

			<?php else : ?>

				<div class="content-area flex flex-wrap">
					<div class="primary-content w-full lg:w-2/3 lg:pr-8">
						<?php get_template_part( 'template-parts/content', 'none' ); ?>
					</div>

					<div class="sidebar w-full lg:w-1/3 mt-8 lg:mt-0">
						<?php get_sidebar(); ?>
					</div>
				</div>

			<?php endif; ?>
		</div>
	</main><!-- #main -->

<?php
get_footer();