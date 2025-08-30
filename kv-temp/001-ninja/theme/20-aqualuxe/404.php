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
		<div class="container mx-auto px-4 py-16">
			<section class="error-404 not-found text-center max-w-3xl mx-auto">
				<header class="page-header mb-8">
					<h1 class="page-title text-4xl md:text-5xl font-bold mb-4"><?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'aqualuxe' ); ?></h1>
				</header><!-- .page-header -->

				<div class="page-content">
					<div class="error-image mb-8">
						<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/404.svg' ); ?>" alt="<?php esc_attr_e( '404 Error', 'aqualuxe' ); ?>" class="mx-auto max-w-full h-auto" width="400" height="300">
					</div>
					
					<p class="text-lg mb-8"><?php esc_html_e( 'It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'aqualuxe' ); ?></p>

					<div class="search-form mb-12 max-w-md mx-auto">
						<?php get_search_form(); ?>
					</div>

					<div class="widget-area grid grid-cols-1 md:grid-cols-2 gap-8 text-left">
						<div>
							<h2 class="text-xl font-bold mb-4"><?php esc_html_e( 'Most Used Categories', 'aqualuxe' ); ?></h2>
							<ul class="list-disc pl-5">
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

						<div>
							<?php
							/* translators: %1$s: smiley */
							$aqualuxe_archive_content = '<p>' . sprintf( esc_html__( 'Try looking in the monthly archives. %1$s', 'aqualuxe' ), convert_smilies( ':)' ) ) . '</p>';
							the_widget( 'WP_Widget_Archives', 'dropdown=1', "after_title=</h2>$aqualuxe_archive_content" );

							the_widget( 'WP_Widget_Tag_Cloud' );
							?>
						</div>
					</div>
					
					<div class="mt-12">
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="inline-block bg-primary hover:bg-primary-dark text-white font-bold py-3 px-6 rounded-md transition-colors duration-200">
							<?php esc_html_e( 'Back to Homepage', 'aqualuxe' ); ?>
						</a>
					</div>
				</div><!-- .page-content -->
			</section><!-- .error-404 -->
		</div>
	</main><!-- #main -->

<?php
get_footer();