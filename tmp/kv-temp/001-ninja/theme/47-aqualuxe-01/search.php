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
		<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
			<div class="lg:col-span-2">
				<?php if ( have_posts() ) : ?>

					<header class="page-header mb-8">
						<h1 class="page-title text-3xl font-bold">
							<?php
							/* translators: %s: search query. */
							printf( esc_html__( 'Search Results for: %s', 'aqualuxe' ), '<span class="text-primary-600">' . get_search_query() . '</span>' );
							?>
						</h1>
					</header><!-- .page-header -->

					<div class="search-form-container mb-8">
						<?php get_search_form(); ?>
					</div>

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

					aqualuxe_pagination();

				else :

					?>
					<header class="page-header mb-8">
						<h1 class="page-title text-3xl font-bold">
							<?php
							/* translators: %s: search query. */
							printf( esc_html__( 'Search Results for: %s', 'aqualuxe' ), '<span class="text-primary-600">' . get_search_query() . '</span>' );
							?>
						</h1>
					</header><!-- .page-header -->

					<div class="search-form-container mb-8">
						<?php get_search_form(); ?>
					</div>

					<?php
					get_template_part( 'templates/content', 'none' );

				endif;
				?>
			</div>

			<div class="sidebar-container">
				<?php get_sidebar(); ?>
			</div>
		</div>
	</main><!-- #main -->

<?php
get_footer();