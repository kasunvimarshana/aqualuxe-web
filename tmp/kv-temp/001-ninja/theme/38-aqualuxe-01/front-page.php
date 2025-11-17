<?php
/**
 * The template for displaying the front page
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

get_header();
?>

<div id="primary" class="content-area full-width">
	<main id="main" class="site-main">

		<?php
		// Hero Section
		get_template_part( 'templates/parts/sections/hero' );

		// Featured Products Section (if WooCommerce is active)
		if ( class_exists( 'WooCommerce' ) ) {
			get_template_part( 'templates/parts/sections/featured-products' );
		}

		// Categories Section (if WooCommerce is active)
		if ( class_exists( 'WooCommerce' ) ) {
			get_template_part( 'templates/parts/sections/product-categories' );
		}

		// About Section
		get_template_part( 'templates/parts/sections/about' );

		// Services Section
		get_template_part( 'templates/parts/sections/services' );

		// Testimonials Section
		get_template_part( 'templates/parts/sections/testimonials' );

		// Latest Posts Section
		get_template_part( 'templates/parts/sections/latest-posts' );

		// Newsletter Section
		get_template_part( 'templates/parts/sections/newsletter' );

		// If the front page is set to display a static page, show its content
		if ( get_option( 'show_on_front' ) === 'page' ) {
			while ( have_posts() ) {
				the_post();
				get_template_part( 'templates/parts/content/content', 'page' );
			}
		}
		?>

	</main><!-- #main -->
</div><!-- #primary -->

<?php
get_footer();