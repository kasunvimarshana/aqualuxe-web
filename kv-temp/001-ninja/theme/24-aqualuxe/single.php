<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package AquaLuxe
 */

get_header();

$sidebar_position = get_theme_mod( 'aqualuxe_sidebar_position', 'right' );
$has_sidebar = is_active_sidebar( 'sidebar-1' ) && $sidebar_position !== 'none';
?>

	<main id="primary" class="site-main py-12">
		<div class="container mx-auto px-4">
			<div class="flex flex-wrap">
				<div class="<?php echo $has_sidebar ? 'w-full lg:w-2/3 ' . ( $sidebar_position === 'left' ? 'lg:order-2' : '' ) : 'w-full'; ?>">
					<?php
					while ( have_posts() ) :
						the_post();

						get_template_part( 'template-parts/content/content', 'single' );

						// If comments are open or we have at least one comment, load up the comment template.
						if ( comments_open() || get_comments_number() ) :
							comments_template();
						endif;

						// Post navigation
						the_post_navigation(
							array(
								'prev_text' => '<span class="nav-subtitle">' . esc_html__( 'Previous:', 'aqualuxe' ) . '</span> <span class="nav-title">%title</span>',
								'next_text' => '<span class="nav-subtitle">' . esc_html__( 'Next:', 'aqualuxe' ) . '</span> <span class="nav-title">%title</span>',
								'class'     => 'post-navigation flex flex-col md:flex-row justify-between mt-8 pt-8 border-t border-gray-200 dark:border-gray-700',
							)
						);

						// Related posts
						if ( function_exists( 'aqualuxe_related_posts' ) ) {
							aqualuxe_related_posts();
						}

					endwhile; // End of the loop.
					?>
				</div><!-- .content-area -->

				<?php if ( $has_sidebar ) : ?>
					<aside id="secondary" class="widget-area w-full lg:w-1/3 <?php echo $sidebar_position === 'left' ? 'lg:order-1 lg:pr-8' : 'lg:pl-8'; ?> mt-8 lg:mt-0">
						<?php dynamic_sidebar( 'sidebar-1' ); ?>
					</aside><!-- #secondary -->
				<?php endif; ?>
			</div><!-- .flex -->
		</div><!-- .container -->
	</main><!-- #main -->

<?php
get_footer();