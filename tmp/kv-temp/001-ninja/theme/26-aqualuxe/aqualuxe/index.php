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

// Get blog layout from theme mod
$blog_layout = get_theme_mod( 'aqualuxe_blog_layout', 'grid' );
$sidebar_position = get_theme_mod( 'aqualuxe_sidebar_position', 'right' );
$has_sidebar = is_active_sidebar( 'sidebar-1' ) && $sidebar_position !== 'none';
?>

	<main id="primary" class="site-main py-12">
		<div class="container mx-auto px-4">
			<div class="flex flex-wrap">
				<div class="<?php echo $has_sidebar ? 'w-full lg:w-2/3 ' . ( $sidebar_position === 'left' ? 'lg:order-2' : '' ) : 'w-full'; ?>">
					<?php
					if ( have_posts() ) :

						if ( is_home() && ! is_front_page() ) :
							?>
							<header>
								<h1 class="page-title screen-reader-text"><?php single_post_title(); ?></h1>
							</header>
							<?php
						endif;

						// Blog layout class
						$blog_class = '';
						switch ( $blog_layout ) {
							case 'grid':
								$blog_class = 'grid grid-cols-1 md:grid-cols-2 gap-8';
								break;
							case 'masonry':
								$blog_class = 'masonry-grid grid grid-cols-1 md:grid-cols-2 gap-8';
								break;
							case 'list':
								$blog_class = 'flex flex-col space-y-8';
								break;
							default:
								$blog_class = 'flex flex-col space-y-8';
								break;
						}
						?>

						<div class="blog-posts <?php echo esc_attr( $blog_class ); ?>">
							<?php
							/* Start the Loop */
							while ( have_posts() ) :
								the_post();

								/*
								 * Include the Post-Type-specific template for the content.
								 * If you want to override this in a child theme, then include a file
								 * called content-___.php (where ___ is the Post Type name) and that will be used instead.
								 */
								get_template_part( 'template-parts/content/content', get_post_type() );

							endwhile;
							?>
						</div><!-- .blog-posts -->

						<?php
						// Pagination
						if ( function_exists( 'aqualuxe_pagination' ) ) {
							aqualuxe_pagination();
						} else {
							the_posts_navigation();
						}

					else :

						get_template_part( 'template-parts/content/content', 'none' );

					endif;
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