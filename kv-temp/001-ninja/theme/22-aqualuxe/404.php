<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package AquaLuxe
 */

get_header();
?>

	<main id="primary" class="site-main py-16">
		<div class="container-fluid max-w-screen-xl mx-auto">
			<section class="error-404 not-found text-center max-w-2xl mx-auto">
				<div class="error-image mb-8">
					<svg xmlns="http://www.w3.org/2000/svg" class="h-48 w-48 mx-auto text-primary-500" viewBox="0 0 20 20" fill="currentColor">
						<path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
					</svg>
				</div>
				
				<header class="page-header mb-8">
					<h1 class="page-title text-6xl font-serif font-bold mb-4"><?php esc_html_e( '404', 'aqualuxe' ); ?></h1>
					<h2 class="text-2xl font-medium text-gray-600 dark:text-gray-400"><?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'aqualuxe' ); ?></h2>
				</header><!-- .page-header -->

				<div class="page-content prose dark:prose-invert mx-auto mb-8">
					<p><?php esc_html_e( 'It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'aqualuxe' ); ?></p>
					
					<?php get_search_form(); ?>
					
					<div class="mt-8">
						<h3><?php esc_html_e( 'Popular Categories', 'aqualuxe' ); ?></h3>
						<ul>
							<?php
							wp_list_categories(
								array(
									'orderby'    => 'count',
									'order'      => 'DESC',
									'show_count' => 1,
									'title_li'   => '',
									'number'     => 5,
								)
							);
							?>
						</ul>
					</div>
					
					<div class="mt-8">
						<h3><?php esc_html_e( 'Recent Posts', 'aqualuxe' ); ?></h3>
						<ul>
							<?php
							wp_get_archives(
								array(
									'type'      => 'postbypost',
									'limit'     => 5,
								)
							);
							?>
						</ul>
					</div>
				</div><!-- .page-content -->
				
				<div class="error-actions">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn-primary">
						<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
							<path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
						</svg>
						<?php esc_html_e( 'Back to Homepage', 'aqualuxe' ); ?>
					</a>
				</div>
			</section><!-- .error-404 -->
		</div>
	</main><!-- #main -->

<?php
get_footer();