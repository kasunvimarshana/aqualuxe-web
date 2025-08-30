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
		<section class="error-404 not-found py-16 text-center">
			<div class="max-w-3xl mx-auto">
				<header class="page-header mb-8">
					<h1 class="page-title text-4xl md:text-5xl font-bold text-primary-900 mb-4"><?php esc_html_e( 'Oops! Page not found.', 'aqualuxe' ); ?></h1>
					<p class="text-xl text-gray-600"><?php esc_html_e( 'The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.', 'aqualuxe' ); ?></p>
				</header><!-- .page-header -->

				<div class="error-image mb-8">
					<?php 
					// Display a custom 404 image if available, otherwise use a default one
					$error_image = aqualuxe_get_theme_option('404_image');
					if ($error_image) {
						echo '<img src="' . esc_url($error_image) . '" alt="' . esc_attr__('404 Error', 'aqualuxe') . '" class="mx-auto max-w-full h-auto">';
					} else {
						aqualuxe_svg_icon('404', array('class' => 'w-64 h-64 mx-auto text-primary-300'));
					}
					?>
				</div>

				<div class="page-content">
					<div class="search-form-container mb-8 max-w-md mx-auto">
						<p class="mb-4"><?php esc_html_e( 'Try searching for what you\'re looking for:', 'aqualuxe' ); ?></p>
						<?php get_search_form(); ?>
					</div>

					<div class="error-actions">
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 transition-colors">
							<?php aqualuxe_svg_icon( 'home', array( 'class' => 'w-5 h-5 mr-2' ) ); ?>
							<?php esc_html_e( 'Back to Homepage', 'aqualuxe' ); ?>
						</a>
					</div>

					<?php
					if ( aqualuxe_is_woocommerce_active() ) :
						?>
						<div class="featured-products mt-16">
							<h2 class="text-2xl font-bold mb-6"><?php esc_html_e( 'Featured Products', 'aqualuxe' ); ?></h2>
							<?php
							if ( function_exists( 'aqualuxe_featured_products' ) ) {
								aqualuxe_featured_products( 4 );
							} else {
								echo do_shortcode( '[products limit="4" columns="4" visibility="featured"]' );
							}
							?>
						</div>
						<?php
					endif;
					?>
				</div><!-- .page-content -->
			</div>
		</section><!-- .error-404 -->
	</main><!-- #main -->

<?php
get_footer();