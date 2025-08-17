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

// Get the layout setting
$sidebar_layout = get_theme_mod( 'aqualuxe_page_layout', 'no-sidebar' );
$container_class = 'no-sidebar' === $sidebar_layout ? 'container-fluid' : 'container';
?>

	<main id="primary" class="site-main <?php echo esc_attr( $container_class ); ?>">
		<div class="row">
			<div class="<?php echo esc_attr( aqualuxe_get_content_classes( $sidebar_layout ) ); ?>">
				<?php
				while ( have_posts() ) :
					the_post();

					get_template_part( 'templates/content/content', 'page' );

					// If comments are open or we have at least one comment, load up the comment template.
					if ( comments_open() || get_comments_number() ) :
						comments_template();
					endif;

				endwhile; // End of the loop.
				?>
			</div>

			<?php
			// Include sidebar if layout is not 'no-sidebar'
			if ( 'no-sidebar' !== $sidebar_layout && 'full-width' !== $sidebar_layout ) {
				get_sidebar();
			}
			?>
		</div>
	</main><!-- #main -->

<?php
get_footer();