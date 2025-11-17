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

	<main id="primary" class="<?php echo aqualuxe_get_main_content_class(); ?>">

		<?php if ( have_posts() ) : ?>

			<header class="page-header">
				<?php
				the_archive_title( '<h1 class="page-title">', '</h1>' );
				the_archive_description( '<div class="archive-description">', '</div>' );
				?>
			</header><!-- .page-header -->

			<?php
			$blog_layout = aqualuxe_get_blog_layout();
			$blog_columns = aqualuxe_get_blog_columns();
			
			if ( 'grid' === $blog_layout || 'masonry' === $blog_layout ) :
				echo '<div class="blog-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-' . esc_attr( $blog_columns ) . ' gap-8">';
			endif;

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
			
			if ( 'grid' === $blog_layout || 'masonry' === $blog_layout ) :
				echo '</div>';
			endif;

			aqualuxe_pagination();

		else :

			get_template_part( 'templates/content', 'none' );

		endif;
		?>

	</main><!-- #main -->

<?php
if ( aqualuxe_display_sidebar() ) :
	get_sidebar();
endif;
get_footer();