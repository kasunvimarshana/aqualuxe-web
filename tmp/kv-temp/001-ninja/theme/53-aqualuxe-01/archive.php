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

	<main id="primary" class="<?php echo esc_attr( aqualuxe_get_main_class() ); ?>">

		<?php if ( have_posts() ) : ?>

			<?php
			$blog_layout = aqualuxe_get_blog_layout();
			$blog_classes = 'blog-posts';
			
			if ( 'grid' === $blog_layout ) {
				$blog_classes .= ' blog-grid grid grid-cols-1 md:grid-cols-2 gap-6';
			} elseif ( 'masonry' === $blog_layout ) {
				$blog_classes .= ' blog-masonry grid grid-cols-1 md:grid-cols-2 gap-6';
			}
			?>

			<div class="<?php echo esc_attr( $blog_classes ); ?>">
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
			</div><!-- .blog-posts -->

			<?php
			aqualuxe_posts_pagination();

		else :

			get_template_part( 'templates/content', 'none' );

		endif;
		?>

	</main><!-- #primary -->

<?php
if ( aqualuxe_has_sidebar() ) {
	get_sidebar();
}
get_footer();