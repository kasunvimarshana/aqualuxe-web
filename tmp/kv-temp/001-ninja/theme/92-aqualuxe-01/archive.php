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

	<main id="primary" class="site-main flex-grow">

		<?php if ( have_posts() ) : ?>

			<header class="page-header mb-8 p-6 bg-white dark:bg-gray-800 shadow-md rounded-lg">
				<?php
				the_archive_title( '<h1 class="page-title text-3xl font-bold text-gray-900 dark:text-white">', '</h1>' );
				the_archive_description( '<div class="archive-description mt-2 text-sm text-gray-600 dark:text-gray-400">', '</div>' );
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
					get_template_part( 'template-parts/content', get_post_type() );

				endwhile;
				?>
			</div>

			<?php
			the_posts_navigation(
				array(
					'prev_text' => '<span class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">' . esc_html__( 'Older posts', 'aqualuxe' ) . '</span>',
					'next_text' => '<span class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">' . esc_html__( 'Newer posts', 'aqualuxe' ) . '</span>',
				)
			);

		else :

			get_template_part( 'template-parts/content', 'none' );

		endif;
		?>

	</main><!-- #main -->

	<?php get_sidebar(); ?>
</div>

<?php
get_footer();
