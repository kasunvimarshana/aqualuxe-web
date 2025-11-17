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

	<main id="primary" class="site-main py-8">
		<div class="container-fluid max-w-screen-xl mx-auto">
			<div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
				<div class="lg:col-span-8">
					<?php if ( have_posts() ) : ?>

						<header class="page-header mb-8">
							<h1 class="page-title text-4xl font-serif font-bold mb-4">
								<?php
								/* translators: %s: search query. */
								printf( esc_html__( 'Search Results for: %s', 'aqualuxe' ), '<span class="text-primary-500">' . get_search_query() . '</span>' );
								?>
							</h1>
							
							<?php get_search_form(); ?>
						</header><!-- .page-header -->

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

						// Previous/next page navigation.
						the_posts_pagination( array(
							'prev_text'          => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" /></svg> ' . esc_html__( 'Previous', 'aqualuxe' ),
							'next_text'          => esc_html__( 'Next', 'aqualuxe' ) . ' <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" /></svg>',
							'class'              => 'pagination flex justify-center mt-8',
						) );

					else :

						get_template_part( 'template-parts/content/content', 'none' );

					endif;
					?>
				</div>
				
				<div class="lg:col-span-4">
					<?php get_sidebar(); ?>
				</div>
			</div>
		</div>
	</main><!-- #main -->

<?php
get_footer();