<?php
/**
 * The template for displaying the front page
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

get_header();
?>

	<main id="primary" class="site-main">
		
		<?php
		// Hero Section
		get_template_part( 'template-parts/homepage/hero' );
		
		// Featured Products Section (if WooCommerce is active)
		if ( class_exists( 'WooCommerce' ) ) {
			get_template_part( 'template-parts/homepage/featured-products' );
		}
		
		// Services Section
		get_template_part( 'template-parts/homepage/services' );
		
		// About Section
		get_template_part( 'template-parts/homepage/about' );
		
		// Testimonials Section
		get_template_part( 'template-parts/homepage/testimonials' );
		
		// Latest Blog Posts Section
		get_template_part( 'template-parts/homepage/latest-posts' );
		
		// Call to Action Section
		get_template_part( 'template-parts/homepage/cta' );
		
		// Contact Section
		get_template_part( 'template-parts/homepage/contact' );
		?>

	</main><!-- #main -->

<?php
get_footer();