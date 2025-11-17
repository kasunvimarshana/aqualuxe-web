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

	<main id="primary" class="site-main front-page" role="main">

		<?php
		// Hero Section
		get_template_part( 'templates/sections/section', 'hero' );

		// Featured Products Section (if WooCommerce is active)
		if ( class_exists( 'WooCommerce' ) ) {
			get_template_part( 'templates/sections/section', 'featured-products' );
		} else {
			get_template_part( 'templates/sections/section', 'featured-products-fallback' );
		}

		// About Section
		get_template_part( 'templates/sections/section', 'about' );

		// Services Section
		get_template_part( 'templates/sections/section', 'services' );

		// Testimonials Section
		get_template_part( 'templates/sections/section', 'testimonials' );

		// Latest Posts Section
		get_template_part( 'templates/sections/section', 'latest-posts' );

		// Newsletter Section
		get_template_part( 'templates/sections/section', 'newsletter' );

		// If the page has content, display it
		while ( have_posts() ) :
			the_post();
			
			// Only show the content if it's not empty
			$content = get_the_content();
			if ( ! empty( $content ) ) :
				?>
				<section class="page-content-section">
					<div class="container">
						<div class="page-content">
							<?php the_content(); ?>
						</div>
					</div>
				</section>
				<?php
			endif;
		endwhile;
		?>

	</main><!-- #primary -->

<?php
get_footer();