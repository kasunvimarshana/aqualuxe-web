<?php
/**
 * The main template file
 *
 * @package AquaLuxe
 */

get_header(); ?>

<main id="primary" class="site-main">
	<div class="container mx-auto px-4 py-8">
		<?php if ( have_posts() ) : ?>

			<header class="page-header mb-12">
				<?php
				the_archive_title( '<h1 class="page-title text-4xl font-bold text-gray-900 dark:text-white mb-4">', '</h1>' );
				the_archive_description( '<div class="archive-description text-lg text-gray-600 dark:text-gray-300">', '</div>' );
				?>
			</header>

			<div class="posts-grid grid gap-8 lg:grid-cols-2 xl:grid-cols-3">
				<?php while ( have_posts() ) : ?>
					<?php the_post(); ?>
					<?php get_template_part( 'templates/content', get_post_type() ); ?>
				<?php endwhile; ?>
			</div>

			<?php aqualuxe_pagination(); ?>

		<?php else : ?>

			<?php get_template_part( 'templates/content', 'none' ); ?>

		<?php endif; ?>
	</div>
</main>

<?php
get_sidebar();
get_footer();