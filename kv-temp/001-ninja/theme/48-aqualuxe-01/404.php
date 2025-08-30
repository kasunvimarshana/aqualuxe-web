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

	<main id="primary" class="site-main">
		<div class="container mx-auto px-4 py-12 md:py-20">
			<div class="max-w-3xl mx-auto text-center">
				<div class="error-404 not-found">
					<div class="error-404-image mb-8">
						<div class="error-404-code text-8xl md:text-9xl font-bold text-primary opacity-20">404</div>
						<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/404.svg' ); ?>" alt="<?php esc_attr_e( 'Page not found', 'aqualuxe' ); ?>" class="max-w-md mx-auto">
					</div>
					
					<header class="page-header mb-6">
						<h1 class="page-title text-3xl md:text-4xl font-serif font-bold mb-4"><?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'aqualuxe' ); ?></h1>
					</header><!-- .page-header -->

					<div class="page-content prose dark:prose-invert max-w-none mb-8">
						<p><?php esc_html_e( 'It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'aqualuxe' ); ?></p>
						
						<div class="search-form-large my-8">
							<?php get_search_form(); ?>
						</div>
						
						<div class="error-404-suggestions grid md:grid-cols-2 gap-8 text-left mt-12">
							<div class="recent-posts">
								<h2 class="text-xl font-bold mb-4"><?php esc_html_e( 'Recent Posts', 'aqualuxe' ); ?></h2>
								<ul class="list-disc pl-5">
									<?php
									$recent_posts = wp_get_recent_posts( array(
										'numberposts' => 5,
										'post_status' => 'publish',
									) );
									
									foreach ( $recent_posts as $recent ) {
										printf(
											'<li><a href="%1$s">%2$s</a></li>',
											esc_url( get_permalink( $recent['ID'] ) ),
											esc_html( $recent['post_title'] )
										);
									}
									?>
								</ul>
							</div>
							
							<div class="most-used-categories">
								<h2 class="text-xl font-bold mb-4"><?php esc_html_e( 'Most Used Categories', 'aqualuxe' ); ?></h2>
								<ul class="list-disc pl-5">
									<?php
									wp_list_categories( array(
										'orderby'    => 'count',
										'order'      => 'DESC',
										'show_count' => 1,
										'title_li'   => '',
										'number'     => 5,
									) );
									?>
								</ul>
							</div>
						</div>
					</div><!-- .page-content -->
					
					<div class="error-404-actions mt-8">
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="inline-block px-6 py-3 bg-primary hover:bg-primary-dark text-white rounded-lg font-medium transition-colors">
							<?php esc_html_e( 'Back to Homepage', 'aqualuxe' ); ?>
						</a>
					</div>
				</div><!-- .error-404 -->
			</div>
		</div>
	</main><!-- #main -->

<?php
get_footer();