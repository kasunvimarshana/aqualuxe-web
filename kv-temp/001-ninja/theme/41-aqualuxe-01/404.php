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

	<main id="primary" class="site-main container mx-auto px-4 py-20">

		<section class="error-404 not-found max-w-3xl mx-auto text-center">
			<header class="page-header mb-12">
				<h1 class="page-title text-6xl font-serif font-bold text-dark-900 dark:text-white mb-4">
					<?php esc_html_e( '404', 'aqualuxe' ); ?>
				</h1>
				<p class="text-2xl text-dark-600 dark:text-dark-300 mb-8">
					<?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'aqualuxe' ); ?>
				</p>
				<div class="text-dark-500 dark:text-dark-400 max-w-xl mx-auto">
					<p><?php esc_html_e( 'It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'aqualuxe' ); ?></p>
				</div>
			</header><!-- .page-header -->

			<div class="page-content">
				<div class="search-form-container max-w-md mx-auto mb-12">
					<?php get_search_form(); ?>
				</div>

				<div class="error-actions flex flex-col sm:flex-row justify-center gap-4 mb-16">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn-primary">
						<?php esc_html_e( 'Back to Homepage', 'aqualuxe' ); ?>
					</a>
					
					<?php if ( aqualuxe_is_woocommerce_active() ) : ?>
						<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="btn btn-secondary">
							<?php esc_html_e( 'Browse Shop', 'aqualuxe' ); ?>
						</a>
					<?php endif; ?>
				</div>

				<div class="widget-area grid grid-cols-1 md:grid-cols-2 gap-8 text-left">
					<div class="widget widget_categories">
						<h2 class="widget-title text-xl font-bold text-dark-900 dark:text-white mb-4"><?php esc_html_e( 'Most Used Categories', 'aqualuxe' ); ?></h2>
						<ul class="space-y-2">
							<?php
							wp_list_categories(
								array(
									'orderby'    => 'count',
									'order'      => 'DESC',
									'show_count' => 1,
									'title_li'   => '',
									'number'     => 10,
								)
							);
							?>
						</ul>
					</div><!-- .widget -->

					<div class="widget widget_recent_entries">
						<h2 class="widget-title text-xl font-bold text-dark-900 dark:text-white mb-4"><?php esc_html_e( 'Recent Posts', 'aqualuxe' ); ?></h2>
						<ul class="space-y-2">
							<?php
							$recent_posts = wp_get_recent_posts(
								array(
									'numberposts' => 5,
									'post_status' => 'publish',
								)
							);
							
							foreach ( $recent_posts as $recent ) {
								printf(
									'<li><a href="%1$s">%2$s</a></li>',
									esc_url( get_permalink( $recent['ID'] ) ),
									esc_html( $recent['post_title'] )
								);
							}
							wp_reset_postdata();
							?>
						</ul>
					</div><!-- .widget -->
				</div><!-- .widget-area -->

			</div><!-- .page-content -->
		</section><!-- .error-404 -->

	</main><!-- #main -->

<?php
get_footer();