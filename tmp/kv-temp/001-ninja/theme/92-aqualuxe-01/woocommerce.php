<?php
/**
 * The template for displaying all WooCommerce pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

get_header();
?>
	<div class="flex flex-wrap md:flex-nowrap gap-8">
		<main id="primary" class="site-main flex-grow">
			<?php woocommerce_content(); ?>
		</main><!-- #main -->
		<?php 
		// On shop pages, we'll show the sidebar.
		if ( is_shop() || is_product_category() || is_product_tag() ) {
			get_sidebar();
		}
		?>
	</div>
<?php
get_footer();
