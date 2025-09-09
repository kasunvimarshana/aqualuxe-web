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

		<?php
		get_template_part( 'templates/pages/home' );
		?>

	</main><!-- #main -->

<?php
get_footer();
