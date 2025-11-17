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
		if ( class_exists( 'WooCommerce' ) && aqualuxe_get_option( 'show_featured_products', true ) ) {
			get_template_part( 'template-parts/homepage/featured-products' );
		}
		
		// Services Section
		get_template_part( 'template-parts/homepage/services' );
		
		// About Section
		get_template_part( 'template-parts/homepage/about' );
		
		// Fish Catalog Section
		get_template_part( 'template-parts/homepage/fish-catalog' );
		
		// Testimonials Section
		get_template_part( 'template-parts/homepage/testimonials' );
		
		// CTA Section
		get_template_part( 'template-parts/homepage/cta' );
		
		// Latest Posts Section
		if ( aqualuxe_get_option( 'show_latest_posts', true ) ) {
			get_template_part( 'template-parts/homepage/latest-posts' );
		}
		
		// Newsletter Section
		if ( aqualuxe_get_option( 'show_newsletter', true ) ) {
			get_template_part( 'template-parts/homepage/newsletter' );
		}
		?>
	</main><!-- #main -->

<?php
get_footer();