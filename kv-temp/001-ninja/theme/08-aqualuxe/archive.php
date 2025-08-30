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

	<main id="primary" class="site-main">
		<div class="container">
			<?php if ( function_exists( 'aqualuxe_breadcrumbs' ) ) : ?>
				<?php aqualuxe_breadcrumbs(); ?>
			<?php endif; ?>

			<?php if ( have_posts() ) : ?>

				<header class="page-header">
					<?php
					the_archive_title( '<h1 class="page-title">', '</h1>' );
					the_archive_description( '<div class="archive-description">', '</div>' );
					?>
				</header><!-- .page-header -->

				<div class="archive-grid">
					<?php
					/* Start the Loop */
					while ( have_posts() ) :
						the_post();

						/*
						 * Include the Post-Type-specific template for the content.
						 * If you want to override this in a child theme, then include a file
						 * called content-___.php (where ___ is the Post Type name) and that will be used instead.
						 */
						get_template_part( 'template-parts/content', get_post_type() );

					endwhile;
					?>
				</div><!-- .archive-grid -->

				<?php
				if ( function_exists( 'aqualuxe_pagination' ) ) :
					aqualuxe_pagination();
				else :
					the_posts_navigation();
				endif;

			else :

				get_template_part( 'template-parts/content', 'none' );

			endif;
			?>
		</div><!-- .container -->
	</main><!-- #main -->

<?php
get_sidebar();
get_footer();