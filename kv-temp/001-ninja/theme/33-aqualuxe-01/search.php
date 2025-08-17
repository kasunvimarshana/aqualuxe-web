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
						<h1 class="page-title text-3xl sm:text-4xl font-serif font-bold text-dark-800 dark:text-white mb-4">
							<?php
							/* translators: %s: search query. */
							printf( esc_html__( 'Search Results for: %s', 'aqualuxe' ), '<span class="text-primary-600 dark:text-primary-400">' . get_search_query() . '</span>' );
							?>
						</h1>
						
						<div class="search-form-container mb-8">
							<?php get_search_form(); ?>
						</div>
					</header><!-- .page-header -->

					<div class="search-results-count mb-6 text-gray-600 dark:text-gray-300">
						<?php
						$found_posts = $wp_query->found_posts;
						/* translators: %d: number of search results */
						printf( esc_html( _n( '%d result found', '%d results found', $found_posts, 'aqualuxe' ) ), $found_posts );
						?>
					</div>

					<div class="search-results-list space-y-8">
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