<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

get_header();
?>

	<main id="primary" class="<?php echo esc_attr( aqualuxe_get_main_class() ); ?>">

		<?php
		if ( have_posts() ) :

			if ( is_home() && ! is_front_page() ) :
				?>
				<header>
					<h1 class="page-title screen-reader-text"><?php single_post_title(); ?></h1>
				</header>
				<?php
			endif;

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