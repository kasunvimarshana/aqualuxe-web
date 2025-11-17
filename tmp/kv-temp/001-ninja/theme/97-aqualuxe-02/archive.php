<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

get_header();
?>

	<main id="primary" class="site-main py-12">
    <div class="container mx-auto px-4">
		<?php if ( have_posts() ) : ?>

			<header class="page-header mb-8 border-b pb-4">
				<?php
				the_archive_title( '<h1 class="page-title text-4xl font-bold">', '</h1>' );
				the_archive_description( '<div class="archive-description mt-2 text-lg text-gray-600 dark:text-gray-400">', '</div>' );
				?>
			</header><!-- .page-header -->

			<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
			<?php
			/* Start the Loop */
			while ( have_posts() ) :
				the_post();

				/*
				 * Include the Post-Type-specific template for the content.
				 * If you want to override this in a child theme, then include a file
				 * called content-___.php (where ___ is the Post Type name) and that will be used instead.
				 */
				get_template_part( 'templates/content', get_post_type() );

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
