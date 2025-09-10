<?php
/**
 * The front page template file
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

get_header();
?>

	<main id="primary" class="site-main">

		<?php get_template_part('templates/parts/home/hero'); ?>
		<?php get_template_part('templates/parts/home/services'); ?>
		<?php get_template_part('templates/parts/home/projects'); ?>
		<?php get_template_part('templates/parts/home/testimonials'); ?>
		<?php get_template_part('templates/parts/home/cta'); ?>

	</main><!-- #main -->

<?php
get_footer();
