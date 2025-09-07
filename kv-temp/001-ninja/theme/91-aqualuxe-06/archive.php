<?php
/**
 * The template for displaying archive pages
 *
 * @package AquaLuxe
 */

get_header();
?>

	<main id="primary" class="site-main">

		<?php if ( have_posts() ) : ?>

			<header class="page-header alignwide">
				<?php
				the_archive_title( '<h1 class="page-title">', '</h1>' );
				the_archive_description( '<div class="archive-description">', '</div>' );
				?>
			</header><!-- .page-header -->

			<?php
			while ( have_posts() ) :
				the_post();
				get_template_part( 'template-parts/blog/content', get_post_format() );
			endwhile;

			the_posts_navigation();

		else :
			get_template_part( 'template-parts/content/content-none' );
		endif;
		?>
	</main><!-- #main -->

<?php
get_sidebar();
get_footer();
