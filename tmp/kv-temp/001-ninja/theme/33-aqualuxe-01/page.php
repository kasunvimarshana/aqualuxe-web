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
?>

	<main id="primary" class="site-main container mx-auto px-4 py-8">

		<?php
		while ( have_posts() ) :
			the_post();

			// Display breadcrumbs if function exists
			if ( function_exists( 'aqualuxe_breadcrumbs' ) ) :
				aqualuxe_breadcrumbs();
			endif;

			// Get page layout from theme mod
			$page_layout = get_theme_mod( 'aqualuxe_page_layout', 'default' );
			
			// Check if current page has a custom layout set
			$custom_layout = get_post_meta( get_the_ID(), 'aqualuxe_page_layout', true );
			if ( ! empty( $custom_layout ) && 'default' !== $custom_layout ) {
				$page_layout = $custom_layout;
			}

			// Determine if sidebar should be shown
			$show_sidebar = ( 'right-sidebar' === $page_layout || 'left-sidebar' === $page_layout );
			
			// Set column classes based on layout
			$content_class = $show_sidebar ? 'lg:col-span-8' : 'lg:col-span-12';
			$sidebar_class = 'lg:col-span-4';
			
			// Reorder columns for left sidebar layout
			$grid_class = ( 'left-sidebar' === $page_layout ) ? 'flex-col-reverse lg:flex-row' : '';
			?>

			<div class="grid grid-cols-1 lg:grid-cols-12 gap-8 <?php echo esc_attr( $grid_class ); ?>">
				<div class="<?php echo esc_attr( $content_class ); ?>">
					<?php
					get_template_part( 'template-parts/content/content', 'page' );

					// If comments are open or we have at least one comment, load up the comment template.
					if ( comments_open() || get_comments_number() ) :
						comments_template();
					endif;
					?>
				</div>

				<?php if ( $show_sidebar ) : ?>
					<div class="<?php echo esc_attr( $sidebar_class ); ?>">
						<?php get_sidebar(); ?>
					</div>
				<?php endif; ?>
			</div>

		<?php endwhile; // End of the loop. ?>

	</main><!-- #main -->

<?php
get_footer();