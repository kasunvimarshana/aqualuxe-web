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

	<main id="primary" class="site-main container mx-auto px-4 py-8">

		<?php if ( have_posts() ) : ?>

			<header class="page-header mb-8">
				<h1 class="page-title text-3xl md:text-4xl font-serif font-bold text-gray-900 dark:text-gray-100 mb-2">
					<?php
					/* translators: %s: search query. */
					printf( esc_html__( 'Search Results for: %s', 'aqualuxe' ), '<span class="text-primary-600 dark:text-primary-400">' . get_search_query() . '</span>' );
					?>
				</h1>
				
				<?php get_search_form(); ?>
			</header><!-- .page-header -->

			<div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
				<div class="lg:col-span-8">
					<div class="search-results-count mb-4 text-gray-600 dark:text-gray-400">
						<?php
						global $wp_query;
						printf(
							/* translators: %d: the number of search results. */
							esc_html( _n( '%d result found', '%d results found', $wp_query->found_posts, 'aqualuxe' ) ),
							esc_html( $wp_query->found_posts )
						);
						?>
					</div>

					<div class="search-results-list divide-y divide-gray-200 dark:divide-dark-700">
						<?php
						/* Start the Loop */
						while ( have_posts() ) :
							the_post();

							/**
							 * Run the loop for the search to output the results.
							 * If you want to overload this in a child theme then include a file
							 * called content-search.php and that will be used instead.
							 */
							get_template_part( 'templates/content', 'search' );

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