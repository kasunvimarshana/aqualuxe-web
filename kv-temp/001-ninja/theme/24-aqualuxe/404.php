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
		<div class="container mx-auto px-4">
			<section class="error-404 not-found max-w-3xl mx-auto text-center">
				<div class="error-image mb-8">
					<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/404.svg' ); ?>" alt="<?php esc_attr_e( '404 Error', 'aqualuxe' ); ?>" class="mx-auto h-64">
				</div>

				<header class="page-header mb-8">
					<h1 class="page-title text-4xl font-bold mb-4"><?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'aqualuxe' ); ?></h1>
					<p class="text-lg text-gray-600 dark:text-gray-400"><?php esc_html_e( 'It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'aqualuxe' ); ?></p>
				</header><!-- .page-header -->

				<div class="page-content">
					<div class="search-form-container max-w-md mx-auto mb-8">
						<?php get_search_form(); ?>
					</div>

					<div class="error-suggestions grid grid-cols-1 md:grid-cols-2 gap-8 text-left max-w-2xl mx-auto">
						<div class="recent-posts">
							<h2 class="text-xl font-semibold mb-4"><?php esc_html_e( 'Recent Posts', 'aqualuxe' ); ?></h2>
							<ul class="space-y-2">
								<?php
								$recent_posts = wp_get_recent_posts( array(
									'numberposts' => 5,
									'post_status' => 'publish',
								) );
								
								foreach ( $recent_posts as $post ) {
									echo '<li class="text-primary hover:text-primary-dark transition-colors"><a href="' . esc_url( get_permalink( $post['ID'] ) ) . '">' . esc_html( $post['post_title'] ) . '</a></li>';
								}
								wp_reset_postdata();
								?>
							</ul>
						</div>

						<div class="popular-categories">
							<h2 class="text-xl font-semibold mb-4"><?php esc_html_e( 'Popular Categories', 'aqualuxe' ); ?></h2>
							<ul class="space-y-2">
								<?php
								wp_list_categories( array(
									'orderby'    => 'count',
									'order'      => 'DESC',
									'show_count' => true,
									'title_li'   => '',
									'number'     => 5,
								) );
								?>
							</ul>
						</div>
					</div>

					<div class="back-home mt-12">
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="inline-flex items-center px-6 py-3 bg-primary hover:bg-primary-dark text-white font-medium rounded-md transition-colors">
							<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
								<path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
							</svg>
							<?php esc_html_e( 'Back to Home', 'aqualuxe' ); ?>
						</a>
					</div>
				</div><!-- .page-content -->
			</section><!-- .error-404 -->
		</div><!-- .container -->
	</main><!-- #main -->

<?php
get_footer();