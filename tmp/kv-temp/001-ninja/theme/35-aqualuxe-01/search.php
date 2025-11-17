<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
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
			/**
			 * Hook: aqualuxe_search_header.
			 *
			 * @hooked aqualuxe_search_header_content - 10
			 */
			do_action( 'aqualuxe_search_header' );
			?>

			<?php
			/**
			 * Hook: aqualuxe_before_search_content.
			 *
			 * @hooked none
			 */
			do_action( 'aqualuxe_before_search_content' );
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
					get_template_part( 'templates/parts/content', 'search' );

				endwhile;
				?>
			</div><!-- .posts-grid -->

			<?php
			/**
			 * Hook: aqualuxe_after_search_content.
			 *
			 * @hooked aqualuxe_pagination - 10
			 */
			do_action( 'aqualuxe_after_search_content' );

		else :

			/**
			 * Hook: aqualuxe_no_search_results.
			 *
			 * @hooked aqualuxe_no_search_results_content - 10
			 */
			do_action( 'aqualuxe_no_search_results' );

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