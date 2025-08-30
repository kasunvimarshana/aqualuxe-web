<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package AquaLuxe
 */

get_header();
?>

	<main id="primary" class="site-main">
		<div class="container mx-auto px-4 py-8">
			<div class="flex flex-wrap -mx-4">
				<?php
				$sidebar_layout = get_theme_mod( 'aqualuxe_content_layout', 'right-sidebar' );
				$content_class = 'w-full';
				
				if ( is_active_sidebar( 'sidebar-1' ) && $sidebar_layout !== 'no-sidebar' ) {
					$content_class = 'w-full lg:w-2/3';
					if ( $sidebar_layout === 'left-sidebar' ) {
						$content_class .= ' lg:order-2';
					}
				}
				?>
				
				<div class="<?php echo esc_attr( $content_class ); ?> px-4">
					<?php
					while ( have_posts() ) :
						the_post();

						get_template_part( 'templates/content', 'single' );

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

					endwhile; // End of the loop.
					?>
				</div>
				
				<?php
				if ( is_active_sidebar( 'sidebar-1' ) && $sidebar_layout !== 'no-sidebar' ) :
					$sidebar_class = 'w-full lg:w-1/3 px-4';
					if ( $sidebar_layout === 'left-sidebar' ) {
						$sidebar_class .= ' lg:order-1';
					}
				?>
					<aside id="secondary" class="widget-area <?php echo esc_attr( $sidebar_class ); ?>">
						<?php aqualuxe_before_sidebar(); ?>
						<?php dynamic_sidebar( 'sidebar-1' ); ?>
						<?php aqualuxe_after_sidebar(); ?>
					</aside><!-- #secondary -->
				<?php endif; ?>
			</div>
		</div>
	</main><!-- #main -->

<?php
get_footer();