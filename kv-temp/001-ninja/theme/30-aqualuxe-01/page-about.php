<?php
/**
 * Template Name: About Page
 *
 * The template for displaying the About page
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

get_header();
?>

	<main id="primary" class="site-main">
		
		<?php get_template_part( 'template-parts/about/hero' ); ?>
		
		<?php get_template_part( 'template-parts/about/mission' ); ?>
		
		<?php get_template_part( 'template-parts/about/history' ); ?>
		
		<?php get_template_part( 'template-parts/about/values' ); ?>
		
		<?php get_template_part( 'template-parts/about/sustainability' ); ?>
		
		<?php get_template_part( 'template-parts/about/team' ); ?>
		
		<?php get_template_part( 'template-parts/about/cta' ); ?>

	</main><!-- #main -->

<?php
get_footer();