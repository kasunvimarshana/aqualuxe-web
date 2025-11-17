<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package AquaLuxe
 */

get_header();

// Get blog layout from theme mod
$blog_layout = get_theme_mod( 'aqualuxe_blog_layout', 'list' );
$sidebar_position = get_theme_mod( 'aqualuxe_sidebar_position', 'right' );
$has_sidebar = is_active_sidebar( 'sidebar-1' ) && $sidebar_position !== 'none';
?>

	<main id="primary" class="site-main py-12">
		<div class="container mx-auto px-4">
			<?php if ( function_exists( 'aqualuxe_breadcrumbs' ) ) : ?>
				<?php aqualuxe_breadcrumbs(); ?>
			<?php endif; ?>

			<div class="flex flex-wrap">
				<div class="<?php echo $has_sidebar ? 'w-full lg:w-2/3 ' . ( $sidebar_position === 'left' ? 'lg:order-2' : '' ) : 'w-full'; ?>">

					<?php if ( have_posts() ) : ?>

						<header class="page-header mb-8">
							<h1 class="page-title text-3xl font-bold mb-4">
								<?php
								/* translators: %s: search query. */
								printf( esc_html__( 'Search Results for: %s', 'aqualuxe' ), '<span class="text-primary">' . get_search_query() . '</span>' );
								?>
							</h1>

							<?php get_search_form(); ?>
						</header><!-- .page-header -->

						<?php
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

						<div class="search-results <?php echo esc_attr( $blog_class ); ?>">
							<?php
							/* Start the Loop */
							while ( have_posts() ) :
								the_post();

								/**
								 * Run the loop for the search to output the results.
								 * If you want to overload this in a child theme then include a file
								 * called content-search.php and that will be used instead.
								 */
								get_template_part( 'template-parts/content/content', 'search' );

							endwhile;
							?>
						</div><!-- .search-results -->

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