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

	<main id="primary" class="site-main container mx-auto px-4 py-12">

		<?php if ( have_posts() ) : ?>

			<header class="page-header mb-12">
				<h1 class="page-title text-4xl font-serif font-bold text-dark-900 dark:text-white mb-4">
					<?php
					/* translators: %s: search query. */
					printf( esc_html__( 'Search Results for: %s', 'aqualuxe' ), '<span class="text-primary-600 dark:text-primary-400">' . get_search_query() . '</span>' );
					?>
				</h1>
				
				<div class="search-form-container max-w-2xl mb-8">
					<?php get_search_form(); ?>
				</div>
				
				<div class="search-result-count text-dark-500 dark:text-dark-400">
					<?php
					/* translators: %d: the number of search results. */
					printf(
						esc_html( _n( '%d result found', '%d results found', (int) $wp_query->found_posts, 'aqualuxe' ) ),
						(int) $wp_query->found_posts
					);
					?>
				</div>
			</header><!-- .page-header -->

			<div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
				<div class="lg:col-span-2">
					<div class="search-results divide-y divide-gray-200 dark:divide-dark-700">
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

					<?php get_template_part( 'template-parts/components/pagination' ); ?>
				</div>

				<div class="lg:col-span-1">
					<?php get_sidebar(); ?>
				</div>
			</div>

		<?php else : ?>

			<header class="page-header mb-12">
				<h1 class="page-title text-4xl font-serif font-bold text-dark-900 dark:text-white mb-4">
					<?php
					/* translators: %s: search query. */
					printf( esc_html__( 'Search Results for: %s', 'aqualuxe' ), '<span class="text-primary-600 dark:text-primary-400">' . get_search_query() . '</span>' );
					?>
				</h1>
				
				<div class="search-form-container max-w-2xl mb-8">
					<?php get_search_form(); ?>
				</div>
			</header><!-- .page-header -->

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