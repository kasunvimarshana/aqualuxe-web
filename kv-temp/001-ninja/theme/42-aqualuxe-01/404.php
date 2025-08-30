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
			<section class="error-404 not-found text-center">
				<div class="max-w-3xl mx-auto">
					<div class="error-image mb-8">
						<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/dist/images/404.svg' ); ?>" alt="<?php esc_attr_e( '404 Error', 'aqualuxe' ); ?>" class="mx-auto h-64">
					</div>

					<header class="page-header mb-8">
						<h1 class="page-title text-4xl md:text-5xl font-bold text-primary-800 mb-4"><?php esc_html_e( 'Oops! Page Not Found', 'aqualuxe' ); ?></h1>
						<div class="error-subtitle text-xl text-gray-600">
							<?php esc_html_e( 'The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.', 'aqualuxe' ); ?>
						</div>
					</header><!-- .page-header -->

					<div class="page-content prose max-w-none mx-auto">
						<div class="error-actions flex flex-col md:flex-row justify-center items-center space-y-4 md:space-y-0 md:space-x-6 mb-12">
							<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="inline-flex items-center bg-primary-600 hover:bg-primary-700 text-white font-medium py-3 px-6 rounded-md transition-colors">
								<i class="fas fa-home mr-2"></i> <?php esc_html_e( 'Back to Homepage', 'aqualuxe' ); ?>
							</a>
							<a href="#" class="inline-flex items-center bg-white border border-primary-600 hover:bg-gray-50 text-primary-600 font-medium py-3 px-6 rounded-md transition-colors" onclick="history.back(); return false;">
								<i class="fas fa-arrow-left mr-2"></i> <?php esc_html_e( 'Go Back', 'aqualuxe' ); ?>
							</a>
						</div>

						<div class="search-form-container bg-gray-50 p-8 rounded-lg mb-12">
							<h2 class="text-2xl font-bold text-gray-800 mb-4"><?php esc_html_e( 'Search Our Website', 'aqualuxe' ); ?></h2>
							<p class="mb-6"><?php esc_html_e( 'Perhaps you can find what you\'re looking for by searching below:', 'aqualuxe' ); ?></p>
							<?php get_search_form(); ?>
						</div>

						<div class="helpful-links">
							<h2 class="text-2xl font-bold text-gray-800 mb-6"><?php esc_html_e( 'Helpful Links', 'aqualuxe' ); ?></h2>
							
							<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
								<div class="helpful-links-col">
									<h3 class="text-xl font-bold text-primary-700 mb-4"><?php esc_html_e( 'Popular Pages', 'aqualuxe' ); ?></h3>
									<ul class="space-y-3">
										<?php
										// Get most viewed pages
										$popular_pages = new WP_Query(
											array(
												'post_type'      => 'page',
												'posts_per_page' => 5,
												'orderby'        => 'comment_count',
												'order'          => 'DESC',
											)
										);

										if ( $popular_pages->have_posts() ) :
											while ( $popular_pages->have_posts() ) :
												$popular_pages->the_post();
												?>
												<li>
													<a href="<?php the_permalink(); ?>" class="flex items-center text-primary-600 hover:text-primary-800 transition-colors">
														<i class="fas fa-angle-right mr-2"></i>
														<?php the_title(); ?>
													</a>
												</li>
												<?php
											endwhile;
											wp_reset_postdata();
										else :
											?>
											<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="flex items-center text-primary-600 hover:text-primary-800 transition-colors"><i class="fas fa-angle-right mr-2"></i> <?php esc_html_e( 'Home', 'aqualuxe' ); ?></a></li>
											<li><a href="<?php echo esc_url( home_url( '/about/' ) ); ?>" class="flex items-center text-primary-600 hover:text-primary-800 transition-colors"><i class="fas fa-angle-right mr-2"></i> <?php esc_html_e( 'About Us', 'aqualuxe' ); ?></a></li>
											<li><a href="<?php echo esc_url( home_url( '/services/' ) ); ?>" class="flex items-center text-primary-600 hover:text-primary-800 transition-colors"><i class="fas fa-angle-right mr-2"></i> <?php esc_html_e( 'Services', 'aqualuxe' ); ?></a></li>
											<li><a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="flex items-center text-primary-600 hover:text-primary-800 transition-colors"><i class="fas fa-angle-right mr-2"></i> <?php esc_html_e( 'Contact', 'aqualuxe' ); ?></a></li>
											<li><a href="<?php echo esc_url( home_url( '/blog/' ) ); ?>" class="flex items-center text-primary-600 hover:text-primary-800 transition-colors"><i class="fas fa-angle-right mr-2"></i> <?php esc_html_e( 'Blog', 'aqualuxe' ); ?></a></li>
											<?php
										endif;
										?>
									</ul>
								</div>
								
								<div class="helpful-links-col">
									<h3 class="text-xl font-bold text-primary-700 mb-4"><?php esc_html_e( 'Recent Posts', 'aqualuxe' ); ?></h3>
									<ul class="space-y-3">
										<?php
										$recent_posts = wp_get_recent_posts(
											array(
												'numberposts' => 5,
												'post_status' => 'publish',
											)
										);
										
										if ( ! empty( $recent_posts ) ) {
											foreach ( $recent_posts as $recent ) {
												echo '<li><a href="' . esc_url( get_permalink( $recent['ID'] ) ) . '" class="flex items-center text-primary-600 hover:text-primary-800 transition-colors"><i class="fas fa-angle-right mr-2"></i> ' . esc_html( $recent['post_title'] ) . '</a></li>';
											}
										} else {
											echo '<li>' . esc_html__( 'No recent posts found', 'aqualuxe' ) . '</li>';
										}
										?>
									</ul>
								</div>
							</div>
						</div>
					</div><!-- .page-content -->
				</div>
			</section><!-- .error-404 -->
		</div>
	</main><!-- #main -->

<?php
get_footer();