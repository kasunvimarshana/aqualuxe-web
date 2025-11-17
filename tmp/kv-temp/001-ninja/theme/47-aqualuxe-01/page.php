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
$has_sidebar = is_active_sidebar( 'sidebar-1' ) && ! aqualuxe_is_page_fullwidth();
?>

	<main id="primary" class="site-main">
		<?php if ( $has_sidebar ) : ?>
			<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
				<div class="lg:col-span-2">
		<?php endif; ?>

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

		<?php if ( $has_sidebar ) : ?>
				</div>
				<div class="sidebar-container">
					<?php get_sidebar(); ?>
				</div>
			</div>
		<?php endif; ?>
	</main><!-- #main -->

<?php
get_footer();