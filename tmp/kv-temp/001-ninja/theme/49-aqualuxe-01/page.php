<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

get_header();

// Get page layout
$page_layout = get_theme_mod( 'aqualuxe_page_layout', 'no-sidebar' );

// Set content class based on page layout
$content_class = 'no-sidebar' === $page_layout ? 'content-area-full' : 'content-area';
?>

	<div class="site-content-wrap <?php echo esc_attr( str_replace( '-', '-', $page_layout ) ); ?>">
		<main id="primary" class="<?php echo esc_attr( $content_class ); ?>">

			<?php
			while ( have_posts() ) :
				the_post();

				get_template_part( 'template-parts/content', 'page' );

				// If comments are open or we have at least one comment, load up the comment template.
				if ( comments_open() || get_comments_number() ) :
					comments_template();
				endif;

			endwhile; // End of the loop.
			?>

		</main><!-- #main -->

		<?php
		// Sidebar
		if ( 'no-sidebar' !== $page_layout ) {
			get_sidebar();
		}
		?>
	</div><!-- .site-content-wrap -->

<?php
get_footer();