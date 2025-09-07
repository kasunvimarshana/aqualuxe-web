<?php
/**
 * The main template file
 *
 * @package AquaLuxe
 */

get_header();
?>

	<main id="primary" class="site-main">

		<?php
		if ( have_posts() ) :

			if ( is_home() && ! is_front_page() ) :
				?>
				<header>
					<h1 class="page-title screen-reader-text"><?php single_post_title(); ?></h1>
				</header>
				<?php
			endif;

			/* Start the Loop */
			while ( have_posts() ) :
				the_post();

				get_template_part( 'templates/partials/content', get_post_type() );

			endwhile;

			the_posts_navigation();

		else :

			get_template_part( 'templates/partials/content', 'none' );

		endif;
		?>

	</main><!-- #main -->

<?php
get_sidebar();
get_footer();
