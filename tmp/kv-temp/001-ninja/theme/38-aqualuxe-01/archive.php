<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

get_header();
?>

<div id="primary" class="content-area">
	<main id="main" class="site-main">

		<?php if ( have_posts() ) : ?>

			<header class="page-header">
				<?php
				the_archive_title( '<h1 class="page-title">', '</h1>' );
				the_archive_description( '<div class="archive-description">', '</div>' );
				?>
			</header><!-- .page-header -->

			<?php
			$blog_layout = get_theme_mod( 'aqualuxe_blog_layout', 'grid' );
			?>

			<div class="posts-<?php echo esc_attr( $blog_layout ); ?>">
				<?php
				/* Start the Loop */
				while ( have_posts() ) :
					the_post();

					/*
					 * Include the Post-Type-specific template for the content.
					 * If you want to override this in a child theme, then include a file
					 * called content-___.php (where ___ is the Post Type name) and that will be used instead.
					 */
					get_template_part( 'templates/parts/content/content', get_post_type() );

				endwhile;
				?>
			</div>

			<?php
			aqualuxe_pagination();

		else :

			get_template_part( 'templates/parts/content/content', 'none' );

		endif;
		?>

	</main><!-- #main -->
</div><!-- #primary -->

<?php
$layout = get_theme_mod( 'aqualuxe_layout', 'right-sidebar' );
if ( 'full-width' !== $layout ) {
	get_sidebar();
}

get_footer();