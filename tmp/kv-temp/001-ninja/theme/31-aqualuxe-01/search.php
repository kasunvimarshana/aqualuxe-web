<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package AquaLuxe
 */

get_header();
?>

	<main id="primary" class="site-main py-12">
		<div class="container mx-auto px-4">
			<?php if ( have_posts() ) : ?>

				<header class="page-header mb-8">
					<h1 class="page-title text-4xl md:text-5xl font-serif font-medium mb-4">
						<?php
						/* translators: %s: search query. */
						printf( esc_html__( 'Search Results for: %s', 'aqualuxe' ), '<span class="text-primary-600 dark:text-primary-400">' . get_search_query() . '</span>' );
						?>
					</h1>
					
					<div class="search-form-container mt-6">
						<?php get_search_form(); ?>
					</div>
				</header><!-- .page-header -->

				<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
					<div class="lg:col-span-2">
						<?php
						/* Start the Loop */
						while ( have_posts() ) :
							the_post();

							/**
							 * Run the loop for the search to output the results.
							 * If you want to overload this in a child theme then include a file
							 * called content-search.php and that will be used instead.
							 */
							get_template_part( 'template-parts/content', 'search' );

						endwhile;

						aqualuxe_enhanced_pagination();
						?>
					</div>

					<div class="sidebar">
						<?php get_sidebar(); ?>
					</div>
				</div>

			<?php else : ?>

				<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
					<div class="lg:col-span-2">
						<?php get_template_part( 'template-parts/content', 'none' ); ?>
					</div>

					<div class="sidebar">
						<?php get_sidebar(); ?>
					</div>
				</div>

			<?php endif; ?>
		</div>
	</main><!-- #main -->

<?php
get_footer();