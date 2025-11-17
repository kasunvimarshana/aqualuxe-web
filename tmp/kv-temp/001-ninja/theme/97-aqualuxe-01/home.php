<?php
/**
 * The template for displaying the blog index/archive
 *
 * @package AquaLuxe
 */

get_header();
?>

	<main id="primary" class="site-main py-12">
    <div class="container mx-auto px-4">
		<?php if ( have_posts() ) : ?>

			<header class="page-header mb-8 border-b pb-4">
				<h1 class="page-title text-4xl font-bold">
					<?php
					if ( is_home() && ! is_front_page() ) {
						single_post_title();
					} else {
						echo esc_html__( 'From Our Blog', 'aqualuxe' );
					}
					?>
				</h1>
			</header>

			<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
			<?php
			/* Start the Loop */
			while ( have_posts() ) :
				the_post();
				get_template_part( 'templates/content', get_post_format() );
			endwhile;
			?>
			</div>
			<?php

			the_posts_navigation();

		else :
			get_template_part( 'templates/content', 'none' );
		endif;
		?>
    </div>
</main><!-- #main -->

<?php
get_footer();
