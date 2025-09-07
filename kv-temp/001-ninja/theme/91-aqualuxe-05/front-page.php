<?php
/**
 * The template for displaying the front page.
 *
 * @package AquaLuxe
 */

get_header();
?>

	<main id="main" class="site-main" role="main">

		<?php
		// Hero Section
		get_template_part( 'template-parts/front-page/hero' );

		// Featured Products Section (only if WooCommerce is active)
		if ( class_exists( 'WooCommerce' ) ) {
			get_template_part( 'template-parts/front-page/featured-products' );
		}

		// Brand Story Section
		get_template_part( 'template-parts/front-page/brand-story' );

		// Value Propositions Section
		get_template_part( 'template-parts/front-page/value-propositions' );

		// Testimonials Section
		get_template_part( 'template-parts/front-page/testimonials' );

		// Latest News Section
		get_template_part( 'template-parts/front-page/latest-news' );

		// Call to Action Section
		get_template_part( 'template-parts/front-page/cta' );
		?>

	</main><!-- #main -->

<?php
get_footer();
