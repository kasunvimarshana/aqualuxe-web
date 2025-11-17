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

	<main id="primary" class="site-main container mx-auto px-4 py-12 md:py-20">

		<section class="error-404 not-found max-w-3xl mx-auto text-center">
			<header class="page-header mb-8">
				<h1 class="page-title text-4xl sm:text-5xl md:text-6xl font-serif font-bold text-dark-800 dark:text-white mb-4">
					<?php esc_html_e( '404', 'aqualuxe' ); ?>
				</h1>
				<p class="text-xl sm:text-2xl text-gray-600 dark:text-gray-300">
					<?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'aqualuxe' ); ?>
				</p>
			</header><!-- .page-header -->

			<div class="page-content">
				<div class="error-image mb-8">
					<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/404.svg' ); ?>" alt="<?php esc_attr_e( '404 Error', 'aqualuxe' ); ?>" class="max-w-md mx-auto">
				</div>

				<p class="mb-8 text-gray-600 dark:text-gray-300">
					<?php esc_html_e( 'It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'aqualuxe' ); ?>
				</p>

				<div class="search-form-container mb-12 max-w-lg mx-auto">
					<?php get_search_form(); ?>
				</div>

				<div class="error-actions flex flex-col sm:flex-row justify-center items-center space-y-4 sm:space-y-0 sm:space-x-4 mb-12">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="bg-primary-600 hover:bg-primary-700 text-white px-6 py-3 rounded-md transition-colors duration-200 inline-flex items-center">
						<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
						</svg>
						<?php esc_html_e( 'Back to Home', 'aqualuxe' ); ?>
					</a>
					
					<?php if ( class_exists( 'WooCommerce' ) ) : ?>
						<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="border border-primary-600 text-primary-600 hover:bg-primary-600 hover:text-white px-6 py-3 rounded-md transition-colors duration-200 inline-flex items-center dark:border-primary-400 dark:text-primary-400 dark:hover:bg-primary-500 dark:hover:text-white">
							<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
							</svg>
							<?php esc_html_e( 'Visit Shop', 'aqualuxe' ); ?>
						</a>
					<?php endif; ?>
				</div>

				<div class="widget-area grid grid-cols-1 md:grid-cols-2 gap-8 text-left max-w-3xl mx-auto">
					<div class="widget">
						<h2 class="widget-title text-xl font-bold mb-4 text-dark-800 dark:text-white">
							<?php esc_html_e( 'Recent Posts', 'aqualuxe' ); ?>
						</h2>
						<ul class="space-y-3">
							<?php
							$recent_posts = wp_get_recent_posts(
								array(
									'numberposts' => 5,
									'post_status' => 'publish',
								)
							);
							
							foreach ( $recent_posts as $post ) :
								?>
								<li>
									<a href="<?php echo esc_url( get_permalink( $post['ID'] ) ); ?>" class="text-gray-600 hover:text-primary-600 dark:text-gray-300 dark:hover:text-primary-400 transition-colors duration-200">
										<?php echo esc_html( $post['post_title'] ); ?>
									</a>
								</li>
								<?php
							endforeach;
							wp_reset_postdata();
							?>
						</ul>
					</div>

					<div class="widget">
						<h2 class="widget-title text-xl font-bold mb-4 text-dark-800 dark:text-white">
							<?php esc_html_e( 'Most Used Categories', 'aqualuxe' ); ?>
						</h2>
						<ul class="space-y-3">
							<?php
							wp_list_categories(
								array(
									'orderby'    => 'count',
									'order'      => 'DESC',
									'show_count' => 1,
									'title_li'   => '',
									'number'     => 5,
									'walker'     => new AquaLuxe_Category_Walker(),
								)
							);
							?>
						</ul>
					</div>
				</div>

			</div><!-- .page-content -->
		</section><!-- .error-404 -->

	</main><!-- #main -->

<?php
get_footer();