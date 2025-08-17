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

// Check if the page has a sidebar
$has_sidebar = is_active_sidebar( 'sidebar-1' ) && ! get_post_meta( get_the_ID(), '_aqualuxe_disable_sidebar', true );
?>

	<main id="primary" class="site-main">
		<div class="container mx-auto px-4 py-8">
			<div class="flex flex-wrap -mx-4">
				<div class="w-full <?php echo $has_sidebar ? 'lg:w-2/3' : 'max-w-4xl mx-auto'; ?> px-4">
					<?php
					while ( have_posts() ) :
						the_post();

						get_template_part( 'template-parts/content/content', 'page' );

					endwhile; // End of the loop.
					?>
				</div>

				<?php if ( $has_sidebar ) : ?>
					<div class="w-full lg:w-1/3 px-4">
						<?php get_sidebar(); ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</main><!-- #main -->

<?php
get_footer();