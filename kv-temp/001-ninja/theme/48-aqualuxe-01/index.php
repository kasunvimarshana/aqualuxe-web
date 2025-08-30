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

// Get the layout
$blog_layout = aqualuxe_get_option('blog_layout', 'grid');
$blog_columns = aqualuxe_get_option('blog_columns', '3');
$blog_sidebar = aqualuxe_get_option('blog_sidebar', 'right-sidebar');
$has_sidebar = ($blog_sidebar !== 'no-sidebar');
?>

	<main id="primary" class="site-main <?php echo esc_attr($blog_sidebar); ?>">
		<div class="container">
			<div class="row">
				<div class="<?php echo $has_sidebar ? 'col-lg-9' : 'col-lg-12'; ?> <?php echo ($blog_sidebar === 'left-sidebar') ? 'order-lg-2' : ''; ?>">
					
					<?php do_action('aqualuxe_content_top'); ?>

					<?php if ( have_posts() ) : ?>

						<div class="posts-wrapper posts-<?php echo esc_attr($blog_layout); ?> columns-<?php echo esc_attr($blog_columns); ?>">
							<?php
							/* Start the Loop */
							while ( have_posts() ) :
								the_post();

								/*
								 * Include the Post-Type-specific template for the content.
								 * If you want to override this in a child theme, then include a file
								 * called content-___.php (where ___ is the Post Type name) and that will be used instead.
								 */
								get_template_part( 'template-parts/content', get_post_type() );

							endwhile;
							?>
						</div>

						<?php do_action('aqualuxe_archive_bottom'); ?>

					<?php else : ?>

						<?php get_template_part( 'template-parts/content', 'none' ); ?>

					<?php endif; ?>
				</div>

				<?php if ($has_sidebar) : ?>
					<div class="col-lg-3 <?php echo ($blog_sidebar === 'left-sidebar') ? 'order-lg-1' : ''; ?>">
						<?php get_sidebar(); ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</main><!-- #main -->

<?php
get_footer();