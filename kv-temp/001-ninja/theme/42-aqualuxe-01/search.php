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
					<h1 class="page-title text-3xl font-bold text-primary-800 mb-2">
						<?php
						/* translators: %s: search query. */
						printf( esc_html__( 'Search Results for: %s', 'aqualuxe' ), '<span class="text-primary-600">' . get_search_query() . '</span>' );
						?>
					</h1>
					
					<?php
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
						<div class="search-form-container mb-8">
							<?php get_search_form(); ?>
						</div>

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
								get_template_part( 'template-parts/content', 'search' );

							endwhile;

							// Pagination
							echo '<div class="pagination-container mt-8">';
							aqualuxe_pagination();
							echo '</div>';
							?>
						</div>
					</div>

					<div class="sidebar w-full lg:w-1/3 mt-8 lg:mt-0">
						<?php get_sidebar(); ?>
					</div>
				</div>

			<?php else : ?>

				<div class="content-area flex flex-wrap">
					<div class="primary-content w-full lg:w-2/3 lg:pr-8">
						<div class="search-form-container mb-8">
							<h1 class="page-title text-3xl font-bold text-primary-800 mb-6">
								<?php
								/* translators: %s: search query. */
								printf( esc_html__( 'Search Results for: %s', 'aqualuxe' ), '<span class="text-primary-600">' . get_search_query() . '</span>' );
								?>
							</h1>
							<?php get_search_form(); ?>
						</div>

						<div class="no-results bg-gray-50 p-8 rounded-lg">
							<h2 class="text-2xl font-bold text-gray-800 mb-4"><?php esc_html_e( 'Nothing Found', 'aqualuxe' ); ?></h2>
							<div class="prose max-w-none">
								<p><?php esc_html_e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'aqualuxe' ); ?></p>
							</div>
							
							<div class="search-suggestions mt-8">
								<h3 class="text-xl font-bold text-gray-800 mb-4"><?php esc_html_e( 'Search Suggestions:', 'aqualuxe' ); ?></h3>
								<ul class="list-disc pl-6 space-y-2 text-gray-700">
									<li><?php esc_html_e( 'Check your spelling.', 'aqualuxe' ); ?></li>
									<li><?php esc_html_e( 'Try more general keywords.', 'aqualuxe' ); ?></li>
									<li><?php esc_html_e( 'Try different keywords that mean the same thing.', 'aqualuxe' ); ?></li>
									<li><?php esc_html_e( 'Try searching with short and simple keywords.', 'aqualuxe' ); ?></li>
								</ul>
							</div>
							
							<div class="mt-8">
								<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="inline-flex items-center bg-primary-600 hover:bg-primary-700 text-white font-medium py-2 px-4 rounded-md transition-colors">
									<i class="fas fa-home mr-2"></i> <?php esc_html_e( 'Back to Homepage', 'aqualuxe' ); ?>
								</a>
							</div>
						</div>
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