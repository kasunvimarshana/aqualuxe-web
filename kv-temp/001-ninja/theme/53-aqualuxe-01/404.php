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

	<main id="primary" class="<?php echo esc_attr( aqualuxe_get_main_class() ); ?>">

		<section class="error-404 not-found">
			<div class="page-content">
				<div class="error-404-content text-center py-16">
					<h2 class="error-404-title text-6xl font-bold mb-4"><?php esc_html_e( '404', 'aqualuxe' ); ?></h2>
					<p class="error-404-description text-xl mb-8"><?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'aqualuxe' ); ?></p>
					<p class="mb-8"><?php esc_html_e( 'It looks like nothing was found at this location. Maybe try a search?', 'aqualuxe' ); ?></p>
					
					<div class="error-404-search max-w-md mx-auto mb-8">
						<?php get_search_form(); ?>
					</div>

					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="button button-primary">
						<?php esc_html_e( 'Back to Home', 'aqualuxe' ); ?>
					</a>
				</div>

				<div class="error-404-widgets mt-16">
					<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
						<div class="error-404-widget">
							<h2><?php esc_html_e( 'Recent Posts', 'aqualuxe' ); ?></h2>
							<ul>
								<?php
								wp_get_archives(
									array(
										'type'    => 'postbypost',
										'limit'   => 10,
									)
								);
								?>
							</ul>
						</div>

						<div class="error-404-widget">
							<h2><?php esc_html_e( 'Most Used Categories', 'aqualuxe' ); ?></h2>
							<ul>
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
						</div>

						<div class="error-404-widget">
							<h2><?php esc_html_e( 'Try a Tag Cloud', 'aqualuxe' ); ?></h2>
							<?php
							wp_tag_cloud(
								array(
									'smallest' => 12,
									'largest'  => 22,
								)
							);
							?>
						</div>
					</div>
				</div>
			</div><!-- .page-content -->
		</section><!-- .error-404 -->

	</main><!-- #primary -->

<?php
get_footer();