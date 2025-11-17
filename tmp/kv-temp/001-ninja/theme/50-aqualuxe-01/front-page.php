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
		get_template_part( 'templates/homepage/hero' );
		
		// Featured Products Section (if WooCommerce is active)
		if ( class_exists( 'WooCommerce' ) ) {
			get_template_part( 'templates/homepage/featured-products' );
		}
		
		// About Section
		get_template_part( 'templates/homepage/about' );
		
		// Services Section
		get_template_part( 'templates/homepage/services' );
		
		// Testimonials Section
		get_template_part( 'templates/homepage/testimonials' );
		
		// Blog Section
		get_template_part( 'templates/homepage/blog' );
		
		// Newsletter Section
		get_template_part( 'templates/homepage/newsletter' );
		
		// Partners Section
		get_template_part( 'templates/homepage/partners' );
		?>
	</main><!-- #main -->

<?php
get_footer();