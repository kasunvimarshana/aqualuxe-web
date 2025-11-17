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

				<header class="page-header mb-8">
					<?php
					the_archive_title( '<h1 class="page-title text-4xl md:text-5xl font-serif font-medium mb-4">', '</h1>' );
					the_archive_description( '<div class="archive-description prose dark:prose-invert max-w-none">', '</div>' );
					?>
				</header><!-- .page-header -->

				<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
					<div class="lg:col-span-2">
						<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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
						</div>

						<?php aqualuxe_pagination(); ?>

					</div>

					<div class="sidebar">
						<?php get_sidebar(); ?>
					</div>
				</div>

			<?php else : ?>

				<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
					<div class="lg:col-span-2">
						<?php get_template_part( 'template-parts/content', 'none' ); ?>
					</div>

					<div class="sidebar">
						<?php get_sidebar(); ?>
					</div>
				</div>

			<?php endif; ?>
		</div>
	</main><!-- #main -->

<?php
get_footer();