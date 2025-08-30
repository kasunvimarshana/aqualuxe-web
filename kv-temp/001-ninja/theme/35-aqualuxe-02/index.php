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

$blog_layout = get_theme_mod( 'aqualuxe_blog_layout', 'grid' );
$blog_sidebar = get_theme_mod( 'aqualuxe_blog_sidebar', 'right' );
$content_class = 'site-main';
$content_class .= ' blog-layout-' . $blog_layout;

if ( 'left' === $blog_sidebar ) {
	$content_class .= ' has-left-sidebar';
} elseif ( 'right' === $blog_sidebar ) {
	$content_class .= ' has-right-sidebar';
}
?>

	<main id="primary" class="<?php echo esc_attr( $content_class ); ?>">

		<?php
		if ( have_posts() ) :

			if ( is_home() && ! is_front_page() ) :
				?>
				<header>
					<h1 class="page-title screen-reader-text"><?php single_post_title(); ?></h1>
				</header>
				<?php
			endif;

			/**
			 * Hook: aqualuxe_before_archive_content.
			 *
			 * @hooked none
			 */
			do_action( 'aqualuxe_before_archive_content' );
			?>

			<div class="posts-grid posts-grid-<?php echo esc_attr( $blog_layout ); ?>">
				<?php
				/* Start the Loop */
				while ( have_posts() ) :
					the_post();

					/*
					 * Include the Post-Type-specific template for the content.
					 * If you want to override this in a child theme, then include a file
					 * called content-___.php (where ___ is the Post Type name) and that will be used instead.
					 */
					get_template_part( 'templates/parts/content', get_post_type() );

				endwhile;
				?>
			</div><!-- .posts-grid -->

			<?php
			/**
			 * Hook: aqualuxe_after_archive_content.
			 *
			 * @hooked aqualuxe_pagination - 10
			 */
			do_action( 'aqualuxe_after_archive_content' );

		else :

			/**
			 * Hook: aqualuxe_no_posts_found.
			 *
			 * @hooked aqualuxe_no_posts_found_content - 10
			 */
			do_action( 'aqualuxe_no_posts_found' );

		endif;
		?>

	</main><!-- #main -->

<?php
/**
 * Hook: aqualuxe_sidebar.
 *
 * @hooked aqualuxe_get_sidebar - 10
 */
do_action( 'aqualuxe_sidebar' );

get_footer();