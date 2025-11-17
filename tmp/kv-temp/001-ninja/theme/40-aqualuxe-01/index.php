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
?>

	<main id="primary" class="site-main">
		<div class="container">
			<?php if ( aqualuxe_has_sidebar() && is_active_sidebar( 'sidebar-1' ) ) : ?>
				<div class="row has-sidebar">
					<div class="col-content">
			<?php else : ?>
				<div class="row">
					<div class="col-full">
			<?php endif; ?>

			<?php
			if ( have_posts() ) :

				if ( is_home() && ! is_front_page() ) :
					?>
					<header>
						<h1 class="page-title screen-reader-text"><?php single_post_title(); ?></h1>
					</header>
					<?php
				endif;

				$blog_layout = get_theme_mod( 'aqualuxe_blog_layout', 'grid' );
				?>

				<div class="posts-wrapper posts-layout-<?php echo esc_attr( $blog_layout ); ?>">
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
				</div><!-- .posts-wrapper -->

				<?php
				aqualuxe_pagination();

			else :

				get_template_part( 'template-parts/content', 'none' );

			endif;
			?>

					</div><!-- .col-content or .col-full -->
					
					<?php if ( aqualuxe_has_sidebar() && is_active_sidebar( 'sidebar-1' ) ) : ?>
						<div class="col-sidebar">
							<?php get_sidebar(); ?>
						</div>
					<?php endif; ?>
				</div><!-- .row -->
		</div><!-- .container -->
	</main><!-- #main -->

<?php
get_footer();