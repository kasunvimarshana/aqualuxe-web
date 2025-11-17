<?php
/**
 * Template Name: Homepage
 *
 * @package AquaLuxe
 */

get_header();
?>

<div id="primary" class="content-area">
	<main id="main" class="site-main">

		<?php
		/**
		 * Hook: aqualuxe_before_homepage_content.
		 *
		 * @hooked aqualuxe_homepage_hero_section - 10
		 */
		do_action( 'aqualuxe_before_homepage_content' );
		?>

		<div class="homepage-sections">
			<?php
			/**
			 * Hook: aqualuxe_homepage_sections.
			 *
			 * @hooked aqualuxe_homepage_featured_categories - 10
			 * @hooked aqualuxe_homepage_featured_products - 20
			 * @hooked aqualuxe_homepage_banner_section - 30
			 * @hooked aqualuxe_homepage_new_arrivals - 40
			 * @hooked aqualuxe_homepage_testimonials - 50
			 * @hooked aqualuxe_homepage_blog_posts - 60
			 * @hooked aqualuxe_homepage_brands_section - 70
			 * @hooked aqualuxe_homepage_newsletter - 80
			 */
			do_action( 'aqualuxe_homepage_sections' );
			?>
		</div>

		<?php
		/**
		 * Hook: aqualuxe_after_homepage_content.
		 */
		do_action( 'aqualuxe_after_homepage_content' );
		?>

	</main><!-- #main -->
</div><!-- #primary -->

<?php
get_footer();