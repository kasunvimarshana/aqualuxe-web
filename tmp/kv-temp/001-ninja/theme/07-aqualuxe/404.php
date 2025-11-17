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

	<main id="primary" class="site-main container mx-auto px-4 py-16">

		<section class="error-404 not-found text-center max-w-3xl mx-auto">
			<div class="error-image mb-8">
				<img src="<?php echo esc_url( get_theme_file_uri( '/assets/images/404.svg' ) ); ?>" alt="<?php esc_attr_e( '404 Error', 'aqualuxe' ); ?>" class="inline-block max-w-md w-full">
			</div>

			<header class="page-header mb-6">
				<h1 class="page-title text-4xl md:text-5xl font-serif font-bold text-gray-900 dark:text-gray-100 mb-4">
					<?php esc_html_e( 'Oops! Page Not Found', 'aqualuxe' ); ?>
				</h1>
				<p class="text-xl text-gray-600 dark:text-gray-400">
					<?php esc_html_e( 'It looks like nothing was found at this location. Maybe try a search?', 'aqualuxe' ); ?>
				</p>
			</header><!-- .page-header -->

			<div class="page-content">
				<div class="search-form-container max-w-lg mx-auto mb-12">
					<?php get_search_form(); ?>
				</div>

				<div class="error-suggestions mb-12">
					<h2 class="text-2xl font-serif font-bold text-gray-900 dark:text-gray-100 mb-4">
						<?php esc_html_e( 'Here are some helpful links instead:', 'aqualuxe' ); ?>
					</h2>
					<div class="flex flex-wrap justify-center gap-4">
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="inline-flex items-center px-5 py-2 bg-primary-600 dark:bg-primary-700 text-white rounded-lg hover:bg-primary-700 dark:hover:bg-primary-600 transition-colors duration-300">
							<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
							</svg>
							<?php esc_html_e( 'Back to Home', 'aqualuxe' ); ?>
						</a>
						
						<?php if ( class_exists( 'WooCommerce' ) ) : ?>
							<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="inline-flex items-center px-5 py-2 bg-accent-600 dark:bg-accent-700 text-white rounded-lg hover:bg-accent-700 dark:hover:bg-accent-600 transition-colors duration-300">
								<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
								</svg>
								<?php esc_html_e( 'Visit Shop', 'aqualuxe' ); ?>
							</a>
						<?php endif; ?>
						
						<a href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ); ?>" class="inline-flex items-center px-5 py-2 bg-gray-600 dark:bg-gray-700 text-white rounded-lg hover:bg-gray-700 dark:hover:bg-gray-600 transition-colors duration-300">
							<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
							</svg>
							<?php esc_html_e( 'Read Blog', 'aqualuxe' ); ?>
						</a>
						
						<a href="<?php echo esc_url( get_permalink( get_page_by_path( 'contact' ) ) ); ?>" class="inline-flex items-center px-5 py-2 bg-gray-600 dark:bg-gray-700 text-white rounded-lg hover:bg-gray-700 dark:hover:bg-gray-600 transition-colors duration-300">
							<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
							</svg>
							<?php esc_html_e( 'Contact Us', 'aqualuxe' ); ?>
						</a>
					</div>
				</div>

				<?php
				// Show recent posts if enabled in customizer
				if ( get_theme_mod( 'aqualuxe_404_show_recent_posts', true ) ) :
					$recent_posts = wp_get_recent_posts(
						array(
							'numberposts' => 3,
							'post_status' => 'publish',
						)
					);
					
					if ( ! empty( $recent_posts ) ) :
					?>
						<div class="recent-posts mb-8">
							<h2 class="text-2xl font-serif font-bold text-gray-900 dark:text-gray-100 mb-6 text-center">
								<?php esc_html_e( 'Recent Articles', 'aqualuxe' ); ?>
							</h2>
							<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
								<?php
								foreach ( $recent_posts as $recent_post ) :
									$post_id = $recent_post['ID'];
									?>
									<article class="bg-white dark:bg-dark-800 rounded-lg shadow-soft overflow-hidden transition-shadow duration-300 hover:shadow-medium">
										<?php if ( has_post_thumbnail( $post_id ) ) : ?>
											<a href="<?php echo esc_url( get_permalink( $post_id ) ); ?>" class="block aspect-w-16 aspect-h-9 overflow-hidden">
												<?php echo get_the_post_thumbnail( $post_id, 'medium', array( 'class' => 'w-full h-full object-cover transition-transform duration-300 hover:scale-105' ) ); ?>
											</a>
										<?php endif; ?>
										<div class="p-4">
											<h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">
												<a href="<?php echo esc_url( get_permalink( $post_id ) ); ?>" class="hover:text-primary-600 dark:hover:text-primary-400 transition-colors duration-300">
													<?php echo esc_html( get_the_title( $post_id ) ); ?>
												</a>
											</h3>
											<div class="text-sm text-gray-600 dark:text-gray-400">
												<?php echo esc_html( get_the_date( '', $post_id ) ); ?>
											</div>
										</div>
									</article>
								<?php endforeach; ?>
							</div>
						</div>
					<?php
					endif;
				endif;
				?>
			</div><!-- .page-content -->
		</section><!-- .error-404 -->

	</main><!-- #main -->

<?php
get_footer();