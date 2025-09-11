<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

get_header();
?>

<main id="main" class="site-main">
	<div class="container mx-auto px-4 py-16">
		<div class="max-w-4xl mx-auto">
			<section class="error-404 not-found text-center">
				<header class="page-header mb-12">
					<h1 class="page-title text-4xl md:text-6xl font-bold text-gray-800 dark:text-white mb-4">
						<?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'aqualuxe' ); ?>
					</h1>
					<div class="text-8xl mb-6 text-gray-400">
						<?php echo convert_smilies( ':O' ); ?>
					</div>
				</header><!-- .page-header -->

				<div class="page-content">
					<p class="text-lg text-gray-600 dark:text-gray-300 mb-8">
						<?php esc_html_e( 'It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'aqualuxe' ); ?>
					</p>

					<div class="max-w-md mx-auto mb-12">
						<?php get_search_form(); ?>
					</div>

					<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
						<!-- Recent Posts -->
						<div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
							<h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">
								<?php esc_html_e( 'Recent Posts', 'aqualuxe' ); ?>
							</h2>
							<?php
							the_widget( 'WP_Widget_Recent_Posts', array(
								'title' => '',
								'number' => 5,
							) );
							?>
						</div>

						<!-- Most Used Categories -->
						<div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
							<h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">
								<?php esc_html_e( 'Most Used Categories', 'aqualuxe' ); ?>
							</h2>
							<ul class="space-y-2">
								<?php
								wp_list_categories(
									array(
										'orderby'    => 'count',
										'order'      => 'DESC',
										'show_count' => 1,
										'title_li'   => '',
										'number'     => 10,
										'echo'       => 1,
									)
								);
								?>
							</ul>
						</div>

						<!-- Archives and Tags -->
						<div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
							<h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">
								<?php esc_html_e( 'Archives', 'aqualuxe' ); ?>
							</h2>
							<?php
							/* translators: %1$s: smiley */
							$aqualuxe_archive_content = '<p class="text-sm text-gray-600 dark:text-gray-300 mb-4">' . 
								sprintf( 
									esc_html__( 'Try looking in the monthly archives. %1$s', 'aqualuxe' ), 
									convert_smilies( ':)' ) 
								) . '</p>';
							
							the_widget( 'WP_Widget_Archives', 'dropdown=1', "before_widget=<div class='widget-archives'>&after_widget=</div>&before_title=<h3 class='widget-title sr-only'>&after_title=</h3>&after_title=</h3>$aqualuxe_archive_content" );
							?>
							
							<div class="mt-6">
								<h3 class="text-lg font-medium text-gray-800 dark:text-white mb-3">
									<?php esc_html_e( 'Tag Cloud', 'aqualuxe' ); ?>
								</h3>
								<?php the_widget( 'WP_Widget_Tag_Cloud' ); ?>
							</div>
						</div>
					</div>

					<div class="mt-12 text-center">
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
							<?php esc_html_e( 'Back to Home', 'aqualuxe' ); ?>
						</a>
					</div>
				</div><!-- .page-content -->
			</section><!-- .error-404 -->
		</div>
	</div>
</main><!-- #main -->

<?php
get_footer();