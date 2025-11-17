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
		
		<?php get_template_part( 'template-parts/home/hero-slider' ); ?>
		
		<?php get_template_part( 'template-parts/home/featured-categories' ); ?>
		
		<?php get_template_part( 'template-parts/home/featured-products' ); ?>
		
		<?php get_template_part( 'template-parts/home/promo-banner' ); ?>
		
		<?php get_template_part( 'template-parts/home/latest-products' ); ?>
		
		<?php get_template_part( 'template-parts/home/testimonials' ); ?>
		
		<?php get_template_part( 'template-parts/home/features' ); ?>
		
		<?php get_template_part( 'template-parts/home/blog-posts' ); ?>
		
		<?php get_template_part( 'template-parts/home/newsletter' ); ?>

	</main><!-- #main -->

<?php
get_footer();