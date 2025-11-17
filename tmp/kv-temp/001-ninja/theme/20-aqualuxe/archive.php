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
					<?php if ( have_posts() ) : ?>

						<header class="page-header mb-8">
							<?php
							the_archive_title( '<h1 class="page-title text-3xl font-bold mb-4">', '</h1>' );
							the_archive_description( '<div class="archive-description text-gray-600">', '</div>' );
							?>
						</header><!-- .page-header -->

						<?php
						$blog_layout = get_theme_mod( 'aqualuxe_blog_layout', 'grid' );
						
						if ( $blog_layout === 'grid' ) {
							echo '<div class="grid grid-cols-1 md:grid-cols-2 gap-8">';
						}
						
						/* Start the Loop */
						while ( have_posts() ) :
							the_post();

							/*
							* Include the Post-Type-specific template for the content.
							* If you want to override this in a child theme, then include a file
							* called content-___.php (where ___ is the Post Type name) and that will be used instead.
							*/
							get_template_part( 'templates/content', $blog_layout );

						endwhile;
						
						if ( $blog_layout === 'grid' ) {
							echo '</div>';
						}

						aqualuxe_pagination();

					else :

						get_template_part( 'templates/content', 'none' );

					endif;
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