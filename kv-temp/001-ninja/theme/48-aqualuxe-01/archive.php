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
		<div class="container mx-auto px-4 py-8">
			<div class="flex flex-wrap -mx-4">
				<div class="w-full lg:w-2/3 px-4">
					<?php if ( have_posts() ) : ?>

						<header class="page-header mb-8">
							<?php
							the_archive_title( '<h1 class="page-title text-3xl md:text-4xl font-serif font-bold mb-4">', '</h1>' );
							the_archive_description( '<div class="archive-description prose dark:prose-invert">', '</div>' );
							?>
						</header><!-- .page-header -->

						<div class="archive-posts">
							<?php
							/* Start the Loop */
							while ( have_posts() ) :
								the_post();

								/*
								 * Include the Post-Type-specific template for the content.
								 * If you want to override this in a child theme, then include a file
								 * called content-___.php (where ___ is the Post Type name) and that will be used instead.
								 */
								get_template_part( 'template-parts/content/content', 'archive' );

							endwhile;
							?>
						</div>

						<div class="pagination-wrapper my-8">
							<?php aqualuxe_pagination(); ?>
						</div>

					<?php else : ?>

						<?php get_template_part( 'template-parts/content/content', 'none' ); ?>

					<?php endif; ?>
				</div>

				<div class="w-full lg:w-1/3 px-4">
					<?php get_sidebar(); ?>
				</div>
			</div>
		</div>
	</main><!-- #main -->

<?php
get_footer();