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

	<main id="primary" class="site-main container">
		<section class="error-404 not-found py-16 text-center">
			<header class="page-header mb-8">
				<h1 class="page-title text-4xl font-bold mb-4"><?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'aqualuxe' ); ?></h1>
				<p class="text-xl text-gray-600"><?php esc_html_e( 'It looks like nothing was found at this location.', 'aqualuxe' ); ?></p>
			</header><!-- .page-header -->

			<div class="page-content">
				<div class="error-image mb-8">
					<img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/404.svg' ) ); ?>" alt="<?php esc_attr_e( '404 Not Found', 'aqualuxe' ); ?>" class="mx-auto max-w-md w-full">
				</div>

				<div class="search-form-container max-w-lg mx-auto mb-12">
					<p class="mb-4"><?php esc_html_e( 'Perhaps searching can help you find what you\'re looking for:', 'aqualuxe' ); ?></p>
					<?php get_search_form(); ?>
				</div>

				<div class="helpful-links max-w-xl mx-auto">
					<h2 class="text-2xl font-bold mb-4"><?php esc_html_e( 'Here are some helpful links instead:', 'aqualuxe' ); ?></h2>
					
					<div class="grid md:grid-cols-2 gap-6">
						<div class="helpful-links-column">
							<h3 class="text-xl font-semibold mb-3"><?php esc_html_e( 'Site Navigation', 'aqualuxe' ); ?></h3>
							<ul class="space-y-2">
								<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="text-primary hover:underline"><?php esc_html_e( 'Home', 'aqualuxe' ); ?></a></li>
								<li><a href="<?php echo esc_url( get_post_type_archive_link( 'post' ) ); ?>" class="text-primary hover:underline"><?php esc_html_e( 'Blog', 'aqualuxe' ); ?></a></li>
								<?php if ( class_exists( 'WooCommerce' ) ) : ?>
									<li><a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="text-primary hover:underline"><?php esc_html_e( 'Shop', 'aqualuxe' ); ?></a></li>
								<?php endif; ?>
								<li><a href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ); ?>" class="text-primary hover:underline"><?php esc_html_e( 'Latest Articles', 'aqualuxe' ); ?></a></li>
							</ul>
						</div>
						
						<div class="helpful-links-column">
							<h3 class="text-xl font-semibold mb-3"><?php esc_html_e( 'Popular Content', 'aqualuxe' ); ?></h3>
							<ul class="space-y-2">
								<?php
								// Get popular posts
								$popular_posts = new WP_Query(
									array(
										'posts_per_page' => 4,
										'orderby'        => 'comment_count',
										'order'          => 'DESC',
									)
								);

								if ( $popular_posts->have_posts() ) :
									while ( $popular_posts->have_posts() ) :
										$popular_posts->the_post();
										?>
										<li><a href="<?php the_permalink(); ?>" class="text-primary hover:underline"><?php the_title(); ?></a></li>
										<?php
									endwhile;
									wp_reset_postdata();
								else :
									?>
									<li><?php esc_html_e( 'No popular content found', 'aqualuxe' ); ?></li>
									<?php
								endif;
								?>
							</ul>
						</div>
					</div>
				</div>
			</div><!-- .page-content -->
		</section><!-- .error-404 -->
	</main><!-- #main -->

<?php
get_footer();