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

	<main id="primary" class="site-main py-12">
		<div class="container mx-auto px-4">
			<section class="error-404 not-found bg-white dark:bg-dark-700 rounded-lg shadow-soft p-8 text-center">
				<div class="max-w-3xl mx-auto">
					<header class="page-header mb-8">
						<div class="error-image mb-8">
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="w-32 h-32 mx-auto text-primary-600 dark:text-primary-400">
								<path fill="currentColor" d="M256 0C114.6 0 0 114.6 0 256s114.6 256 256 256 256-114.6 256-256S397.4 0 256 0zm0 464c-114.7 0-208-93.31-208-208S141.3 48 256 48s208 93.31 208 208-93.3 208-208 208zm-64-304c-17.67 0-32 14.33-32 32s14.33 32 32 32 32-14.33 32-32-14.33-32-32-32zm128 0c-17.67 0-32 14.33-32 32s14.33 32 32 32 32-14.33 32-32-14.33-32-32-32zm-63.9 160.8c-45.95 0-88.23-23.84-112.6-63.36-5.623-9.125-2.812-21.08 6.312-26.7 9.125-5.594 21.08-2.812 26.7 6.312C197.2 261.7 225.1 276.8 256.1 276.8s58.94-15.12 75.19-39.67c5.625-9.125 17.59-11.91 26.7-6.312 9.125 5.625 11.94 17.58 6.312 26.7-24.32 39.52-66.6 63.36-112.6 63.36z"/>
							</svg>
						</div>
						<h1 class="page-title text-5xl md:text-6xl font-serif font-medium mb-4"><?php esc_html_e( '404', 'aqualuxe' ); ?></h1>
						<p class="text-xl md:text-2xl text-dark-600 dark:text-dark-300 mb-6"><?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'aqualuxe' ); ?></p>
					</header><!-- .page-header -->

					<div class="page-content prose dark:prose-invert max-w-none">
						<p><?php esc_html_e( 'It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'aqualuxe' ); ?></p>

						<div class="search-form-container my-8 max-w-lg mx-auto">
							<?php get_search_form(); ?>
						</div>

						<div class="error-404-widgets grid grid-cols-1 md:grid-cols-2 gap-8 mt-12 text-left">
							<div class="widget">
								<h2 class="text-lg font-medium mb-4 pb-2 border-b border-gray-200 dark:border-dark-600"><?php esc_html_e( 'Recent Posts', 'aqualuxe' ); ?></h2>
								<ul class="space-y-2">
									<?php
									wp_get_archives(
										array(
											'type'  => 'postbypost',
											'limit' => 5,
										)
									);
									?>
								</ul>
							</div>

							<div class="widget">
								<h2 class="text-lg font-medium mb-4 pb-2 border-b border-gray-200 dark:border-dark-600"><?php esc_html_e( 'Most Used Categories', 'aqualuxe' ); ?></h2>
								<ul class="space-y-2">
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
						</div>

						<div class="mt-8">
							<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn-primary">
								<?php esc_html_e( 'Back to Homepage', 'aqualuxe' ); ?>
							</a>
						</div>
					</div><!-- .page-content -->
				</div>
			</section><!-- .error-404 -->
		</div>
	</main><!-- #main -->

<?php
get_footer();