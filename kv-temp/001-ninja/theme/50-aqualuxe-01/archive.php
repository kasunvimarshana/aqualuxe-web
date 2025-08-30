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
			<div class="content-sidebar">
				<div class="content-area">
					<?php if ( have_posts() ) : ?>

						<header class="page-header">
							<?php
							the_archive_title( '<h1 class="page-title">', '</h1>' );
							the_archive_description( '<div class="archive-description">', '</div>' );
							?>
						</header><!-- .page-header -->

						<div class="posts-grid">
							<?php
							/* Start the Loop */
							while ( have_posts() ) :
								the_post();

								/*
								* Include the Post-Type-specific template for the content.
								* If you want to override this in a child theme, then include a file
								* called content-___.php (where ___ is the Post Type name) and that will be used instead.
								*/
								get_template_part( 'templates/content/content', get_post_type() );

							endwhile;
							?>
						</div><!-- .posts-grid -->

						<?php
						aqualuxe_pagination();

					else :

						get_template_part( 'templates/content/content', 'none' );

					endif;
					?>
				</div><!-- .content-area -->
				
				<div class="widget-area">
					<?php get_sidebar(); ?>
				</div><!-- .widget-area -->
			</div><!-- .content-sidebar -->
		</div><!-- .container -->
	</main><!-- #main -->

<?php
get_footer();