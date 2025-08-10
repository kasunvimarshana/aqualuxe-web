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

// Get page layout from meta or theme mod
$page_layout = get_post_meta( get_the_ID(), '_aqualuxe_page_layout', true );
if ( ! $page_layout || 'default' === $page_layout ) {
	$page_layout = get_theme_mod( 'aqualuxe_sidebar_position', 'right' );
}

$has_sidebar = is_active_sidebar( 'sidebar-1' ) && $page_layout !== 'none' && $page_layout !== 'full-width';
?>

	<main id="primary" class="site-main py-12">
		<div class="container mx-auto px-4">
			<?php if ( function_exists( 'aqualuxe_breadcrumbs' ) ) : ?>
				<?php aqualuxe_breadcrumbs(); ?>
			<?php endif; ?>

			<div class="flex flex-wrap">
				<div class="<?php echo $has_sidebar ? 'w-full lg:w-2/3 ' . ( $page_layout === 'left-sidebar' ? 'lg:order-2' : '' ) : 'w-full'; ?>">
					<?php
					while ( have_posts() ) :
						the_post();

						get_template_part( 'template-parts/content/content', 'page' );

						// If comments are open or we have at least one comment, load up the comment template.
						if ( comments_open() || get_comments_number() ) :
							comments_template();
						endif;

					endwhile; // End of the loop.
					?>
				</div><!-- .content-area -->

				<?php if ( $has_sidebar ) : ?>
					<aside id="secondary" class="widget-area w-full lg:w-1/3 <?php echo $page_layout === 'left-sidebar' ? 'lg:order-1 lg:pr-8' : 'lg:pl-8'; ?> mt-8 lg:mt-0">
						<?php dynamic_sidebar( 'sidebar-1' ); ?>
					</aside><!-- #secondary -->
				<?php endif; ?>
			</div><!-- .flex -->
		</div><!-- .container -->
	</main><!-- #main -->

<?php
get_footer();