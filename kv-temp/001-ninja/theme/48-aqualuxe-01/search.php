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

	<main id="primary" class="site-main">
		<div class="container mx-auto px-4 py-8">
			<div class="flex flex-wrap -mx-4">
				<div class="w-full lg:w-2/3 px-4">
					<?php if ( have_posts() ) : ?>

						<header class="page-header mb-8">
							<h1 class="page-title text-3xl md:text-4xl font-serif font-bold mb-6">
								<?php
								/* translators: %s: search query. */
								printf( esc_html__( 'Search Results for: %s', 'aqualuxe' ), '<span class="text-primary">' . get_search_query() . '</span>' );
								?>
							</h1>
							
							<div class="search-form-large mb-8">
								<?php get_search_form(); ?>
							</div>
							
							<div class="search-result-count text-gray-600 dark:text-gray-400">
								<?php
								printf(
									esc_html( _n( '%d result found', '%d results found', $wp_query->found_posts, 'aqualuxe' ) ),
									$wp_query->found_posts
								);
								?>
							</div>
						</header><!-- .page-header -->

						<div class="search-results">
							<?php
							/* Start the Loop */
							while ( have_posts() ) :
								the_post();

								/**
								 * Run the loop for the search to output the results.
								 * If you want to overload this in a child theme then include a file
								 * called content-search.php and that will be used instead.
								 */
								get_template_part( 'template-parts/content/content', 'search' );

							endwhile;
							?>
						</div>

						<div class="pagination-wrapper my-8">
							<?php aqualuxe_pagination(); ?>
						</div>

					<?php else : ?>

						<?php get_template_part( 'template-parts/content/content', 'none' ); ?>

					<?php endif; ?>
				</div>

				<div class="w-full lg:w-1/3 px-4">
					<?php get_sidebar(); ?>
				</div>
			</div>
		</div>
	</main><!-- #main -->

<?php
get_footer();