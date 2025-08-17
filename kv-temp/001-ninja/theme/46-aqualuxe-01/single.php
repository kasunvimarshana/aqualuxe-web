<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package AquaLuxe
 */

get_header();

// Get sidebar position
$sidebar_position = get_theme_mod( 'aqualuxe_sidebar_position', 'right' );

// Set content class based on sidebar position
$content_class = 'none' === $sidebar_position ? 'content-area-full' : 'content-area';
?>

	<div class="site-content-wrap <?php echo esc_attr( 'sidebar-' . $sidebar_position ); ?>">
		<main id="primary" class="<?php echo esc_attr( $content_class ); ?>">

			<?php
			while ( have_posts() ) :
				the_post();

				get_template_part( 'template-parts/content', 'single' );

				// Post navigation
				the_post_navigation(
					array(
						'prev_text' => '<span class="nav-subtitle">' . esc_html__( 'Previous:', 'aqualuxe' ) . '</span> <span class="nav-title">%title</span>',
						'next_text' => '<span class="nav-subtitle">' . esc_html__( 'Next:', 'aqualuxe' ) . '</span> <span class="nav-title">%title</span>',
					)
				);

				// If comments are open or we have at least one comment, load up the comment template.
				if ( comments_open() || get_comments_number() ) :
					comments_template();
				endif;

				// Related posts
				if ( function_exists( 'aqualuxe_related_posts' ) && get_theme_mod( 'aqualuxe_display_related_posts', true ) ) {
					aqualuxe_related_posts();
				}

			endwhile; // End of the loop.
			?>

		</main><!-- #main -->

		<?php
		// Sidebar
		if ( 'none' !== $sidebar_position ) {
			get_sidebar();
		}
		?>
	</div><!-- .site-content-wrap -->

<?php
get_footer();