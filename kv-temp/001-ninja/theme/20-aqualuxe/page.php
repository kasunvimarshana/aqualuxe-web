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

	<main id="primary" class="site-main">
		<div class="container mx-auto px-4 py-8">
			<?php
			// Get page settings
			$page_layout = get_post_meta( get_the_ID(), '_aqualuxe_page_layout', true );
			$hide_title = get_post_meta( get_the_ID(), '_aqualuxe_hide_title', true );
			
			// Use page setting if available, otherwise use theme default
			$sidebar_layout = $page_layout ? $page_layout : get_theme_mod( 'aqualuxe_content_layout', 'right-sidebar' );
			
			// Check if we need to display content in a row with sidebar
			$has_sidebar = is_active_sidebar( 'sidebar-1' ) && $sidebar_layout !== 'no-sidebar' && $sidebar_layout !== 'full-width';
			
			if ( $has_sidebar ) :
			?>
			<div class="flex flex-wrap -mx-4">
			<?php endif; ?>
				
				<?php
				$content_class = 'w-full';
				
				if ( $has_sidebar ) {
					$content_class = 'w-full lg:w-2/3';
					if ( $sidebar_layout === 'left-sidebar' ) {
						$content_class .= ' lg:order-2';
					}
				}
				
				if ( $sidebar_layout === 'full-width' ) {
					$content_class .= ' max-w-none';
				}
				?>
				
				<div class="<?php echo esc_attr( $content_class ); ?> <?php echo $has_sidebar ? 'px-4' : ''; ?>">
					<?php
					while ( have_posts() ) :
						the_post();

						get_template_part( 'templates/content', 'page' );

						// If comments are open or we have at least one comment, load up the comment template.
						if ( comments_open() || get_comments_number() ) :
							comments_template();
						endif;

					endwhile; // End of the loop.
					?>
				</div>
				
				<?php
				if ( $has_sidebar ) :
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
				
			<?php if ( $has_sidebar ) : ?>
			</div>
			<?php endif; ?>
		</div>
	</main><!-- #main -->

<?php
get_footer();